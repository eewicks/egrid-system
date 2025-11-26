<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoController;
use App\Http\Controllers\ArduinoIngestController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/arduino-signal', [ArduinoController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('arduino.signal');
Route::get('/arduino-signal-test', function () {
    return "API ROUTE IS WORKING";
});

Route::post('/arduino-signal', function (Request $request) {
    // OPTIONAL: simple key check
    $expected = Config::get('services.arduino.key');
    if ($expected && $request->header('X-ARDUINO-KEY') !== $expected && $request->input('key') !== $expected) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $data = $request->validate([
        'device_id' => 'required|string|max:191',
        'status'    => 'nullable|in:ON,OFF', // default ON if not provided
    ]);

    $now = now();
    $status = $data['status'] ?? 'ON';
    
    // Create status log entry
    DB::table('status_logs')->insert([
        'device_id'  => $data['device_id'],
        'status'     => $status,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    // Update device's last_seen timestamp
    DB::table('devices')
        ->where('device_id', $data['device_id'])
        ->update(['last_seen' => $now]);

    // If status is OFF, create an outage alert log
    if ($status === 'OFF') {
        // Get device info for the alert
        $device = DB::table('devices')->where('device_id', $data['device_id'])->first();
        
        if ($device) {
            DB::table('alert_logs')->insert([
                'device_id'   => $data['device_id'],
                'barangay'    => $device->barangay ?? 'Unknown',
                'alert_type'  => 'OUTAGE',
                'message'     => 'Power outage detected in ' . ($device->barangay ?? 'Unknown') . ' - Device: ' . $data['device_id'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }

    return response()->json(['ok' => true, 'ts' => $now->toIso8601String()]);
});
