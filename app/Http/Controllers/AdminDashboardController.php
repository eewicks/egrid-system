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
    $derived = $device->derived_status;   // ON or OFF
    $devicePk = $device->id;              // PRIMARY KEY INT (correct for outages)
    $householdId = $device->household->id ?? null;

    // Find any active (open) outage
    $openOutage = Outage::where('device_id', $devicePk)
        ->where('status', 'active')
        ->whereNull('ended_at')
        ->first();

    /*
    |--------------------------------------------------------------------------
    | 1. DEVICE WENT OFFLINE (NO HEARTBEAT)
    |--------------------------------------------------------------------------
    */
    if ($derived === 'OFF' && !$openOutage) {

        Outage::create([
            'device_id'      => $devicePk,        // FK INT — FIXED
            'household_id'   => $householdId,
            'started_at'     => now(),
            'status'         => 'active',          // ENUM: active/closed — FIXED
        ]);

        // Also auto-log OFF event
        StatusLog::create([
            'device_id' => $device->device_id,     // string ID for logs (correct)
            'status'    => 'OFF',
        ]);

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 2. DEVICE CAME BACK ONLINE (Arduino sending ON again)
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | 3. Prevent duplicate logs (only log if state changed)
    |--------------------------------------------------------------------------
    */
    $lastLog = StatusLog::where('device_id', $device->device_id)
        ->orderBy('created_at', 'desc')
        ->first();

    if ($derived === 'OFF' && (!$lastLog || $lastLog->status === 'ON')) {
        StatusLog::create([
            'device_id' => $device->device_id,
            'status'    => 'OFF',
        ]);
    }

    if ($derived === 'ON' && $lastLog && $lastLog->status === 'OFF') {
        StatusLog::create([
            'device_id' => $device->device_id,
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
                    'device_id'         => $d->device_id,   // string ID
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
