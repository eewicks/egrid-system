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
