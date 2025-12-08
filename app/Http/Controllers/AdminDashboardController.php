<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatusLog;
use App\Models\Device;
use App\Models\Outage;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboardtest');
    }

    public function dashboardStats()
{
    // online/offline counts
    $devices = Device::all();
    $onCount = 0;
    $offCount = 0;

    foreach ($devices as $d) {
        $onCount += $d->derived_status === 'ON' ? 1 : 0;
        $offCount += $d->derived_status === 'OFF' ? 1 : 0;
    }

    // monthly outages
    $monthlyOutages = Outage::whereMonth('started_at', now()->month)->count();

    // outages in last 24 hours
    $todayOutages = Outage::where('started_at', '>=', now()->subDay())->count();

    return response()->json([
        'success' => true,
        'online' => $onCount,
        'offline' => $offCount,
        'monthly' => $monthlyOutages,
        'today' => $todayOutages,
    ]);
}

 private function handleOutage(Device $device)
    {
        $status = $device->derived_status;

        $open = Outage::where("device_id", $device->id)
            ->where("status", "active")
            ->whereNull("ended_at")
            ->first();

        // GOING OFFLINE — create outage
        if ($status === "OFF" && !$open) {
            Outage::create([
                'device_id'    => $device->id,
                'household_id' => $device->household_id,
                'started_at'   => now(),
                'week_number'  => now()->isoWeek(),
                'iso_year'     => now()->isoWeekYear(),
                'status'       => "active"
            ]);

            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => "OFF"
            ]);
        }

        // COMING ONLINE — close outage
        if ($status === "ON" && $open) {
            $open->update([
                'ended_at'         => $device->last_seen ?? now(),
                'duration_seconds' => $device->last_seen
                    ? $device->last_seen->diffInSeconds($open->started_at)
                    : 0,
                'status'           => "closed"
            ]);

            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => "ON"
            ]);
        }
    }



    /**
     * -------------------------------------------------------------------------
     * OUTAGE ENGINE
     * -------------------------------------------------------------------------
     */
    private function recordOutageIfMissing($device)
    {
        $derived = $device->derived_status; // computed ON/OFF
        $devicePk = $device->id;            // primary key
        $householdId = $device->household_id;

        // NO outage if no household assigned
        if (!$householdId) {
            return;
        }

        // Look for active outage
        $openOutage = Outage::where('device_id', $devicePk)
            ->where('status', 'active')
            ->whereNull('ended_at')
            ->first();

        /*
        |------------------------------------------------------------
        | DEVICE OFFLINE → CREATE OUTAGE
        |------------------------------------------------------------
        */
        if ($derived === 'OFF' && !$openOutage) {
            Outage::create([
                'device_id'    => $devicePk,
                'household_id' => $householdId,
                'started_at'   => now(),
                'week_number'  => now()->isoWeek(),
                'iso_year'     => now()->isoWeekYear(),
                'status'       => 'active',
            ]);

            // Log OFF event once
            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => 'OFF',
            ]);

            return;
        }

        /*
        |------------------------------------------------------------
        | DEVICE ONLINE → CLOSE OUTAGE
        |------------------------------------------------------------
        */
        if ($derived === 'ON' && $openOutage) {
            $endTime = $device->last_seen ?? now();

            $openOutage->update([
                'ended_at'         => $endTime,
                'duration_seconds' => $endTime->diffInSeconds($openOutage->started_at),
                'status'           => 'closed',
            ]);

            // Log ON once
            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => 'ON',
            ]);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * USED BY DEVICE STATUS CHECKER
     * -------------------------------------------------------------------------
     */
    public function deviceStatus()
    {
        $devices = Device::all();

        foreach ($devices as $device) {
            $this->recordOutageIfMissing($device);
        }

        return response()->json([
            'devices' => $devices->map(function ($device) {
                return [
                    'device_id' => $device->device_id,
                    'status'    => $device->derived_status,
                    'last_seen' => optional($device->last_seen)->toDateTimeString(),
                ];
            })
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * FIXED getDevices() → REQUIRED BY DASHBOARD
     * -------------------------------------------------------------------------
     */
    public function getDevices()
    {
        try {
            $timeoutMin = (int) config('services.arduino.heartbeat_timeout_minutes', 1);
            $now = Carbon::now();

            $devices = Device::with('household')->get();

            // Run outage engine
            foreach ($devices as $device) {
                $this->recordOutageIfMissing($device);
            }

            // Transform for UI
            $output = $devices->map(function ($d) use ($now, $timeoutMin) {
                $lastSeen = $d->last_seen ? Carbon::parse($d->last_seen) : null;
                $secondsAgo = $lastSeen ? $now->diffInSeconds($lastSeen) : null;
                $isOnline = $secondsAgo !== null && $secondsAgo <= $timeoutMin * 60;

                return [
                    'device_id'       => $d->device_id,
                    'household_name'  => $d->household_name,
                    'barangay'        => $d->barangay,
                    'status'          => $isOnline ? 'ON' : 'OFF',
                    'display_status'  => $isOnline ? 'Active' : 'Inactive',
                    'last_seen'       => $lastSeen ? $lastSeen->toDateTimeString() : null,
                    'last_seen_human' => $lastSeen ? $lastSeen->diffForHumans() : 'Never',
                ];
            });

            return response()->json([
                'success' => true,
                'devices' => $output,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Server failed to load devices.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function latestOutageEvent()
{
    $latest = Outage::orderBy('id', 'desc')->first();

    if (!$latest) {
        return response()->json(['success' => false]);
    }

    $device = Device::find($latest->device_id);

    return response()->json([
        'success' => true,
        'outage_id' => $latest->id,
        'status' => $latest->status,  // active or closed
        'device_id' => $device->device_id ?? null,
        'household' => $device->household_name ?? 'Unknown',
        'barangay' => $device->barangay ?? 'Unknown',
        'started_at' => $latest->started_at?->toDateTimeString(),
        'ended_at' => $latest->ended_at?->toDateTimeString(),
        'duration' => $latest->duration_seconds,
    ]);
}

    /**
     * -------------------------------------------------------------------------
     * STATS (counts ON/OFF)
     * -------------------------------------------------------------------------
     */
    public function stats()
    {
        $latestPerDevice = StatusLog::select('device_id', DB::raw('MAX(created_at) as max_time'))
            ->groupBy('device_id');

        $latest = StatusLog::joinSub($latestPerDevice, 'lpd', function ($join) {
            $join->on('status_logs.device_id', '=', 'lpd.device_id')
                ->on('status_logs.created_at', '=', 'lpd.max_time');
        })->get();

        return response()->json([
            'totals' => [
                'devices' => $latest->count(),
                'on'      => $latest->where('status', 'ON')->count(),
                'off'     => $latest->where('status', 'OFF')->count(),
            ],
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * LAST 20 LOGS
     * -------------------------------------------------------------------------
     */
    public function logs()
    {
        return response()->json(
            StatusLog::latest()->take(20)->get()
        );
    }
}
