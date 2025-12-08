<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outage;


class OutageController extends Controller
{
    public function latest()
{
    $latest = Outage::orderBy('id', 'desc')->first();

    if (!$latest) {
        return response()->json([
            'success' => false,
            'message' => 'No outage records found.'
        ]);
    }

    return response()->json([
        'success'     => true,
        'outage_id'   => $latest->id,
        'device_id'   => $latest->device_id,
        'household'   => $latest->household_name,
        'barangay'    => $latest->barangay,
        'status'      => $latest->status, // "active" or "closed"
        'started_at'  => $latest->started_at?->toDateTimeString(),
        'ended_at'    => $latest->ended_at?->toDateTimeString(),
        'duration'    => $latest->duration_seconds
    ]);
}

}
