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
     * OUTAGE ENGINE (Final, Correct, Guaranteed)
     * -------------------------------------------------------------------------
     */
    private function recordOutageIfMissing($device)
    {
        $derived = $device->derived_status;   // 'ON' or 'OFF'
        $deviceId = $device->device_id;       // Correct identifier

        // Any open outage?
        $openOutage = Outage::where('device_id', $deviceId)
            ->whereNull('ended_at')
            ->first();

        // --------------------------------------------------------
        // DEVICE WENT OFFLINE → CREATE OUTAGE
        // --------------------------------------------------------
        if ($derived === 'OFF' && !$openOutage) {

            Outage::create([
                'device_id'     => $deviceId,
                'household_id'  => $device->household_id,
                'started_at'    => now(),
                'status'        => 'open',
            ]);

            StatusLog::create([
                'device_id' => $deviceId,
                'status'    => 'OFF',
            ]);
        }

        // --------------------------------------------------------
        // DEVICE CAME BACK ONLINE → CLOSE OUTAGE
        // --------------------------------------------------------
        if ($derived === 'ON' && $openOutage) {

            $end = $device->last_seen ? Carbon::parse($device->last_seen) : now();

            $openOutage->update([
                'ended_at'         => $end,
                'duration_seconds' => $end->diffInSeconds($openOutage->started_at),
                'status'           => 'closed',
            ]);

            StatusLog::create([
                'device_id' => $deviceId,
                'status'    => 'ON',
            ]);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * API USED BY DASHBOARD — NOW WITH OUTAGE DETECTION
     * -------------------------------------------------------------------------
     */
   public function getDevices()
{
    $devices = Device::all();

    // RUN OUTAGE ENGINE HERE
    foreach ($devices as $device) {
        $this->recordOutageIfMissing($device);
    }

    $now = Carbon::now();
    $timeoutMin = config('services.arduino.heartbeat_timeout_minutes', 1);

    return response()->json([
        'success' => true,
        'devices' => $devices->map(function ($d) use ($now, $timeoutMin) {

            $lastSeen = $d->last_seen ? Carbon::parse($d->last_seen) : null;
            $age = $lastSeen ? $now->diffInSeconds($lastSeen) : null;
            $fresh = $lastSeen ? ($age <= $timeoutMin * 60) : false;

            return [
                'device_id'       => $d->device_id,
                'household_name'  => $d->household_name,
                'barangay'        => $d->barangay,
                'status'          => $fresh ? 'ON' : 'OFF',
                'display_status'  => $fresh ? 'Active' : 'Inactive',
                'last_seen'       => $lastSeen ? $lastSeen->toDateTimeString() : null,
                'last_seen_human' => $lastSeen ? $lastSeen->diffForHumans() : 'Never',
            ];
        }),
    ]);
}

    /**
     * For charts and stats
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
            ],
        ]);
    }

    public function logs()
    {
        return response()->json(
            StatusLog::latest()->take(20)->get()
        );
    }
}
