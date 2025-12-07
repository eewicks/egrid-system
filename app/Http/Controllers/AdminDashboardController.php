<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatusLog;
use App\Models\Device;
use App\Models\Outage;
use App\Models\Household;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboardtest');
    }

    /**
     * -------------------------------------------------------------------------
     * OUTAGE ENGINE (FINAL + CLEAN + PRODUCTION SAFE)
     * -------------------------------------------------------------------------
     */
    private function recordOutageIfMissing(Device $device)
    {
        $derived = $device->derived_status;     // ON or OFF
        $devicePk = $device->id;                // Devices table PK (INT)

        // Resolve household_id safely
        $householdId =
              $device->household_id
           ?? Household::where('device_pk', $devicePk)->value('id')
           ?? Household::where('device_id', $device->device_id)->value('id')
           ?? null;

        /*
        |--------------------------------------------------------------------------
        | FIND OPEN OUTAGE
        |--------------------------------------------------------------------------
        */
        $openOutage = Outage::where('device_id', $devicePk)
            ->where('status', 'active')
            ->whereNull('ended_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | DEVICE WENT OFFLINE (STOPPED sending heartbeat)
        |--------------------------------------------------------------------------
        */
        if ($derived === 'OFF' && !$openOutage) {

            Outage::create([
                'device_id'      => $devicePk,
                'household_id'   => $householdId,
                'started_at'     => now(),
                'status'         => 'active',
                'week_number'    => now()->isoWeek(),
                'iso_year'       => now()->year,
            ]);

            // Write OFF log only if last log wasn't OFF
            $last = StatusLog::where('device_id', $device->device_id)
                ->latest()
                ->first();

            if (!$last || $last->status === 'ON') {
                StatusLog::create([
                    'device_id' => $device->device_id,
                    'status'    => 'OFF',
                ]);
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DEVICE CAME BACK ONLINE (Heartbeat resumed)
        |--------------------------------------------------------------------------
        */
        if ($derived === 'ON' && $openOutage) {

            $end = $device->last_seen ?? now();

            $openOutage->update([
                'ended_at'         => $end,
                'duration_seconds' => $end->diffInSeconds($openOutage->started_at),
                'status'           => 'closed',
            ]);

            // Add ON log only if state changed
            $last = StatusLog::where('device_id', $device->device_id)->latest()->first();
            if ($last && $last->status === 'OFF') {
                StatusLog::create([
                    'device_id' => $device->device_id,
                    'status'    => 'ON',
                ]);
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE STATUS LOGS
        |--------------------------------------------------------------------------
        */
        $last = StatusLog::where('device_id', $device->device_id)->orderBy('created_at', 'desc')->first();

        if ($derived === 'OFF' && (!$last || $last->status === 'ON')) {
            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => 'OFF',
            ]);
        }

        if ($derived === 'ON' && $last && $last->status === 'OFF') {
            StatusLog::create([
                'device_id' => $device->device_id,
                'status'    => 'ON',
            ]);
        }
    }

    /**
     * USED BY DASHBOARD
     */
    public function deviceStatus()
    {
        $devices = Device::all();

        foreach ($devices as $d) {
            $this->recordOutageIfMissing($d);
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
     * SYSTEM STATS
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
