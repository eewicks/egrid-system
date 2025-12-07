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
     * OUTAGE ENGINE (CLEAN, FIXED, ACCURATE)
     * -------------------------------------------------------------------------
     * - Creates outage when Arduino STOPS sending ON (derived OFF)
     * - Closes outage when Arduino sends ON again
     * - Uses correct device_id (string)
     * - Uses last_seen for accurate duration
     * -------------------------------------------------------------------------
     */
    private function recordOutageIfMissing($device)
    {
        $derived = $device->derived_status;        // 'ON' or 'OFF' based on last_seen timeout
        $deviceId = $device->device_id;            // Correct device identifier (string)

        // Find any OPEN outage for this device
        $openOutage = Outage::where('device_id', $deviceId)
            ->whereNull('ended_at')
            ->first();

        // --------------------------------------------------------
        // 1. DEVICE WENT OFFLINE (Arduino stopped sending ON)
        // --------------------------------------------------------
        if ($derived === 'OFF' && !$openOutage) {
            Outage::create([
                'device_id'    => $deviceId,
                'household_id' => $device->household_id,
                'started_at'   => now(),
                'status'       => 'open',
            ]);
        }

        // --------------------------------------------------------
        // 2. DEVICE CAME BACK ONLINE (Arduino sending ON again)
        // --------------------------------------------------------
        if ($derived === 'ON' && $openOutage) {

            $endTime = $device->last_seen ? Carbon::parse($device->last_seen) : now();

            $openOutage->update([
                'ended_at'         => $endTime,
                'duration_seconds' => $endTime->diffInSeconds($openOutage->started_at),
                'status'           => 'closed',
            ]);
        }

        // --------------------------------------------------------
        // 3. AUTO CREATE STATUS LOGS (ON/OFF)
        // --------------------------------------------------------
        $lastLog = StatusLog::where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($derived === 'OFF' && (!$lastLog || $lastLog->status === 'ON')) {
            StatusLog::create([
                'device_id' => $deviceId,
                'status'    => 'OFF',
            ]);
        }

        if ($derived === 'ON' && $lastLog && $lastLog->status === 'OFF') {
            StatusLog::create([
                'device_id' => $deviceId,
                'status'    => 'ON',
            ]);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * RETURNS CLEAN JSON FOR DEVICE STATUS
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
                    'device_id'  => $device->device_id,
                    'status'     => $device->derived_status,
                    'last_seen'  => optional($device->last_seen)->toDateTimeString(),
                ];
            })
        ]);
    }

    /**
     * -------------------------------------------------------------------------
     * FRONTEND API FOR DEVICE CARDS
     * -------------------------------------------------------------------------
     */
    public function getDevices()
    {
        try {
            $thresholdMin = (int) cache(
                'settings.heartbeat_timeout_minutes',
                config('services.arduino.heartbeat_timeout_minutes', 1)
            );

            $now = Carbon::now();

            $devices = Device::orderBy('household_name')
                ->get()
                ->map(function ($d) use ($now, $thresholdMin) {

                    $lastSeen = $d->last_seen ? Carbon::parse($d->last_seen) : null;
                    $ageSecs  = $lastSeen ? $now->diffInSeconds($lastSeen) : null;
                    $fresh    = $lastSeen ? $ageSecs <= ($thresholdMin * 60) : false;

                    return [
                        'id'                => $d->id,
                        'device_id'         => $d->device_id,
                        'household_name'    => $d->household_name,
                        'barangay'          => $d->barangay,
                        'status'            => $fresh ? 'ON' : 'OFF',
                        'display_status'    => $fresh ? 'Active' : 'Inactive',
                        'status_badge_class'=> $fresh ? 'bg-success' : 'bg-danger',
                        'status_icon_class' => $fresh ? 'text-success' : 'text-danger',
                        'last_seen'         => $lastSeen ? $lastSeen->toDateTimeString() : null,
                        'last_seen_human'   => $lastSeen ? $lastSeen->diffForHumans() : 'Never',
                    ];
                });

            return response()->json([
                'success' => true,
                'devices' => $devices
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * SYSTEM STATS API (online/offline)
     * -------------------------------------------------------------------------
     */
    public function stats()
    {
        $latestPerDevice = StatusLog::select('device_id', DB::raw('MAX(created_at) as max_time'))
            ->groupBy('device_id');

        $latest = StatusLog::joinSub($latestPerDevice, 'lpd', function ($join) {
                $join->on('status_logs.device_id', '=', 'lpd.device_id')
                     ->on('status_logs.created_at', '=', 'lpd.max_time');
            })
            ->get();

        return response()->json([
            'totals' => [
                'devices' => $latest->count(),
                'on'      => $latest->where('status', 'ON')->count(),
                'off'     => $latest->where('status', 'OFF')->count(),
                'todayOutages' => StatusLog::where('status', 'OFF')
                    ->whereDate('created_at', now())
                    ->count(),
            ]
        ]);
    }

    public function logs()
    {
        return response()->json(
            StatusLog::latest()->take(20)->get(['device_id','status','created_at'])
        );
    }
}
