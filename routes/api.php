<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoController;
use App\Models\Device;
use App\Models\StatusLog;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Simple check if API is online
Route::get('/arduino-signal-test', function () {
    return response()->json(['message' => 'API ROUTE IS WORKING']);
});

// Main Arduino ingestion route
Route::post('/arduino-signal', function (Request $request) {

    // Validate required POST fields
    if (!$request->device_id || !$request->status) {
        return response()->json(['error' => 'Missing device_id or status'], 400);
    }

    // Look up device by device_id
    $device = Device::where('device_id', $request->device_id)->first();

    if (!$device) {
        return response()->json(['error' => 'Device not found'], 404);
    }

    // Update heartbeat timestamp
    $device->last_seen = now();
    $device->save();

    // Log ON/OFF event
    StatusLog::create([
        'device_id' => $device->device_id,
        'status'    => $request->status,
    ]);

    return response()->json(['success' => true]);
});