<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatusLog;
use App\Models\Device;
use App\Http\Controllers\TwilioSMSController;


class AdminDashboardController extends Controller
{
    public function index()
    {
        
        return view('dashboardtest');
    }

  
    public function stats()
    {
        // Count devices ON/OFF by latest status per device
        // Get latest log per device_id
        $latestPerDevice = StatusLog::select('device_id', DB::raw('MAX(created_at) as max_time'))
            ->groupBy('device_id');

        $latest = StatusLog::joinSub($latestPerDevice, 'lpd', function ($join) {
                $join->on('status_logs.device_id', '=', 'lpd.device_id')
                     ->on('status_logs.created_at', '=', 'lpd.max_time');
            })
            ->get(['status_logs.device_id', 'status_logs.status', 'status_logs.created_at']);

        $totalDevices = $latest->count();
        $onCount      = $latest->where('status', 'ON')->count();
        $offCount     = $latest->where('status', 'OFF')->count();

        // Outages today
        $todayOutages = StatusLog::whereDate('created_at', now()->toDateString())
                           ->where('status', 'OFF')
                           ->count();

        // Time-series: last 12 hours OFF counts per hour
        $series = StatusLog::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as h'), DB::raw('COUNT(*) as c'))
                    ->where('status', 'OFF')
                    ->where('created_at', '>=', now()->subHours(12))
                    ->groupBy('h')
                    ->orderBy('h')
                    ->get();

        return response()->json([
            'totals' => [
                'devices' => $totalDevices,
                'on'      => $onCount,
                'off'     => $offCount,
                'todayOutages' => $todayOutages,
            ],
            'series' => $series,
        ]);
    }

    // Latest 20 logs for the table
    public function logs()
    {
        $logs = StatusLog::latest()->take(20)->get(['device_id','status','created_at']);
        return response()->json($logs);
    }

    


    public function deviceStatus()
    {
        $thresholdMin = (int) cache(
            'settings.heartbeat_timeout_minutes',
            config('services.arduino.heartbeat_timeout_minutes', 5)
        );
        $now = Carbon::now();

        $devices = Device::orderBy('household_name')
            ->get(['device_id', 'household_name', 'barangay', 'status', 'last_seen'])
            ->map(function (Device $device) use ($now, $thresholdMin) {
                $lastSeen = $device->last_seen;
                $ageSecs = $lastSeen ? $now->diffInSeconds($lastSeen) : null;
                $fresh = $lastSeen ? $ageSecs <= ($thresholdMin * 60) : false;
                $rawStatus = strtoupper($device->status ?? 'OFF');
                $derived = ($fresh && $rawStatus === 'ON') ? 'ON' : 'OFF';

                return [
                    'device_id'      => $device->device_id,
                    'household_name' => $device->household_name ?? 'Unknown Household',
                    'barangay'       => $device->barangay ?? 'Unknown',
                    'status'         => $derived,
                    'raw_status'     => $rawStatus,
                    'last_seen'      => optional($lastSeen)->toDateTimeString(),
                    'age_secs'       => $ageSecs,
                    'fresh'          => $fresh,
                ];
            });

        return response()->json(['devices' => $devices]);
    }

    // JSON for devices with derived display status
public function getDevices()
{
    try {
        $thresholdMin = (int) cache(
            'settings.heartbeat_timeout_minutes',
            config('services.arduino.heartbeat_timeout_minutes', 1)
        );

        $now = \Carbon\Carbon::now();

        $devices = Device::orderBy('household_name')
            ->get()
            ->map(function ($d) use ($now, $thresholdMin) {

                // Convert last_seen to Carbon safely
                $lastSeen = $d->last_seen ? \Carbon\Carbon::parse($d->last_seen) : null;

                $ageSecs = $lastSeen ? $now->diffInSeconds($lastSeen) : null;

                $fresh = $lastSeen ? ($ageSecs <= ($thresholdMin * 60)) : false;

                $displayStatus = $fresh ? 'Active' : 'Inactive';

                return [
                    'id'   => $d->id,
                    'device_id' => $d->device_id,
                    'household_name' => $d->household_name,
                    'barangay' => $d->barangay,
                    'status' => $fresh ? 'ON' : 'OFF',

                    // Carbon safe format
                    'last_seen' => $lastSeen ? $lastSeen->toDateTimeString() : null,

                    'display_status' => $displayStatus,
                    'status_badge_class' => $fresh ? 'bg-success' : 'bg-danger',
                    'status_icon_class'  => $fresh ? 'text-success' : 'text-danger',

                    // Human readable time
                    'last_seen_human' => $lastSeen
                        ? $lastSeen->diffForHumans()
                        : 'Never',
                ];
            });

        return response()->json([
            'success' => true,
            'devices' => $devices
        ]);

    } catch (\Throwable $e) {

        // Debug output
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ], 500);
    }
}
}