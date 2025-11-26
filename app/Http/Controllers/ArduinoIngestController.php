<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\StatusLog;

class ArduinoIngestController extends Controller
{
    public function ingest(Request $request)
    {
        // Optional shared key
        $expected = config('services.arduino.key');
        $provided = $request->header('X-API-KEY');
        if ($expected && $provided !== $expected) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'household_id' => 'required|integer|min:1',
            'status'       => 'required|integer|in:0,1', // 1=ON, 0=OFF
        ]);

        $statusText = $data['status'] ? 'ON' : 'OFF';
        $name = 'Device '.$data['household_id'];  // or use a real external_id

        $device = Device::updateOrCreate(
            ['name' => $name],
            ['status' => $statusText, 'last_ping_at' => now()]
        );

        StatusLog::create([
            'device_id' => $device->id,
            'status'    => $statusText,
        ]);

        return response()->json(['ok' => true], 201);
    }
}
