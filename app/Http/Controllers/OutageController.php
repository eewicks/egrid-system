<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutageController extends Controller
{
    public function latest()
    {
        // Get most recent outage (active OR closed)
        $outage = DB::table('outages')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$outage) {
            return response()->json([
                'success' => false,
                'message' => 'No outages recorded'
            ]);
        }

        // Get device info
        $device = DB::table('devices')
            ->where('id', $outage->device_id)
            ->first();

        return response()->json([
            'success' => true,
            'outage_id' => $outage->id,

            // Device ID string (03, 04, DEVICE_01, etc.)
            'device_id' => $device->device_id ?? null,

            // Household info
            'household' => $device->household_name ?? 'Unknown Household',
            'barangay'  => $device->barangay ?? 'Unknown Barangay',

            // Outage status
            'status' => $outage->ended_at ? 'closed' : 'active',

            // Timestamps
            'started_at' => $outage->started_at,
            'ended_at'   => $outage->ended_at,

            // Duration if closed
            'duration' => $outage->duration_seconds ?? null
        ]);
    }
}
