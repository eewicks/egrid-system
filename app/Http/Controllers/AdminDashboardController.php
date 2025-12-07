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

    /**
     * -------------------------------------------------------------------------
     * OUTAGE ENGINE — FINAL WORKING VERSION
     * -------------------------------------------------------------------------
     * - Creates outage when Arduino stops sending ON
     * - Closes outage when Arduino comes back online
     * - Uses derived_status (heartbeat logic)
     * - Uses device primary key for outages
     * - Uses string device_id for logs
     * -------------------------------------------------------------------------
     */
   private function recordOutageIfMissing($device)
{
    $derived = $device->derived_status;
    $devicePk = $device->id;
    $householdId = $device->household->id ?? null;

    $openOutage = Outage::where('device_id', $devicePk)
        ->where('status', 'active')
        ->whereNull('ended_at')
        ->first();

    // ------------------------------
    // DEVICE WENT OFFLINE
    // ------------------------------
    if ($derived === 'OFF' && !$openOutage) {

        Outage::create([
            'device_id'      => $devicePk,
            'household_id'   => $householdId,
            'started_at'     => now(),
            'status'         => 'active',
            'week_number'    => now()->weekOfYear,
            'iso_year'       => now()->year,
        ]);

        StatusLog::create([
            'device_id' => $device->device_id,
            'status'    => 'OFF',
        ]);

        return;
    }

    // ------------------------------
    // DEVICE CAME BACK ONLINE
    // ------------------------------
    if ($derived === 'ON' && $openOutage) {

        $endTime = $device->last_seen ?? now();

        $openOutage->update([
            'ended_at'         => $endTime,
            'duration_seconds' => $endTime->diffInSeconds($openOutage->started_at),
            'status'           => 'closed',
        ]);

        StatusLog::create([
            'device_id' => $device->device_id,
            'status'    => 'ON',
        ]);

        return;
    }
}
    /**
     * -------------------------------------------------------------------------
     * API: USED BY DEVICE STATUS ENDPOINT
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
                    'device_id'  => $device->device_id, // string
                    'status'     => $device->derived_status,
                    'last_seen'  => optional($device->last_seen)->toDateTimeString(),
                ];
            })
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * API USED BY DASHBOARD DEVICE CARDS
     * -------------------------------------------------------------------------
     */
    public function getDevices()
    {
        $devices = Device::with('household')->get();

        // Run outage detection for each device
        foreach ($devices as $device) {
            $this->recordOutageIfMissing($device);
        }

        $timeoutMin = config('services.arduino.heartbeat_timeout_minutes', 1);
        $now = Carbon::now();

        return response()->json([
            'success' => true,
            'devices' => $devices->map(function ($d) use ($now, $timeoutMin) {

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
            }),
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * SYSTEM STATS API (Counts ON/OFF devices)
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
     * RECENT STATUS LOG API
     * -------------------------------------------------------------------------
     */
    public function logs()
    {
        return response()->json(
            StatusLog::latest()->take(20)->get()
        );
    }
}
