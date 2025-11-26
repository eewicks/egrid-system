<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Household;
use App\Models\Outage;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArduinoController extends Controller
{
    public function store(Request $request)
    {
        // Optional API Key
        $expectedKey = config('services.arduino.key');
        if ($expectedKey && $request->header('X-ARDUINO-KEY') !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_id' => 'required|string|max:191',
            'status'    => 'required|in:ON,OFF',
        ]);

        $now = now();
        $status = strtoupper($validated['status']);

        DB::transaction(function () use ($validated, $status, $now) {
            // Log status change
            StatusLog::create([
                'device_id' => $validated['device_id'],
                'status'    => $status,
            ]);

            // Sync device meta
            $device = Device::where('device_id', $validated['device_id'])->first();
            if (!$device) {
                $device = Device::create([
                    'device_id'      => $validated['device_id'],
                    'barangay'       => 'Unknown',
                    'household_name' => 'Household '.$validated['device_id'],
                    'status'         => $status,
                    'last_seen'      => $now,
                ]);
            } else {
                $device->forceFill([
                    'status' => $status,
                    'last_seen' => $now,
                ])->save();
            }

            // Ensure household record
            $household = Household::firstOrCreate(
                ['device_id' => $validated['device_id']],
                [
                    'device_pk' => $device->id ?? null,
                    'name' => $device->household_name ?? ('Household '.$validated['device_id']),
                    'location' => $device->barangay ?? null,
                ]
            );

            $household->fill([
                'device_pk' => $device->id ?? $household->device_pk,
                'name' => $device->household_name ?? $household->name,
                'location' => $device->barangay ?? $household->location,
                'last_status' => $status,
                'last_seen' => $now,
            ])->save();

            $openOutage = $household->outages()
                ->whereNull('ended_at')
                ->latest('started_at')
                ->first();

            if ($status === 'OFF' && !$openOutage) {
                $household->outages()->create([
                    'device_id' => $household->device_id,
                    'started_at' => $now,
                    'week_number' => $now->isoWeek(),
                    'iso_year' => $now->isoWeekYear(),
                ]);
            }

            if ($status === 'ON' && $openOutage) {
                $openOutage->closeAt($now);
            }

            // If OFF → create alert log for auditing
            if ($status === 'OFF') {
                DB::table('alert_logs')->insert([
                    'device_id'  => $validated['device_id'],
                    'barangay'   => $device->barangay ?? 'Unknown',
                    'alert_type' => 'OUTAGE',
                    'message'    => 'Power outage detected in ' . ($device->barangay ?? 'Unknown'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });

        return response()->json([
            'ok' => true,
            'received' => $validated,
            'timestamp' => $now->toDateTimeString(),
        ]);
    }
}
