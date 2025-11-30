<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Models\Outage;
use Carbon\Carbon;

class DetectOutages extends Command
{
    protected $signature = 'detect:outages';
    protected $description = 'Detect devices that went offline and create/close outages';

    public function handle()
    {
        $timeout = 20; // seconds before device considered OFF
        $now = Carbon::now();

        $devices = Device::all();

        foreach ($devices as $device) {

            // If device stopped reporting → consider OFF
            $isOffline = $device->last_seen && 
                         $device->last_seen->lt($now->subSeconds($timeout));

            if ($isOffline && $device->status != 'OFF') {

                // CREATE OUTAGE
                Outage::create([
                    'device_id' => $device->id,
                    'household_id' => $device->household_id,
                    'started_at' => Carbon::now(),
                    'status' => 'active',
                ]);

                $device->status = 'OFF';
                $device->save();
            }

            // If device turned ON → close outage
            if ($device->status == 'ON') {
                $openOutage = Outage::where('device_id', $device->id)
                    ->whereNull('ended_at')
                    ->first();

                if ($openOutage) {
                    $openOutage->ended_at = Carbon::now();
                    $openOutage->duration_seconds = Carbon::now()->diffInSeconds($openOutage->started_at);
                    $openOutage->duration_minutes = Carbon::now()->diffInMinutes($openOutage->started_at);
                    $openOutage->status = 'closed';
                    $openOutage->save();
                }
            }
        }

        return Command::SUCCESS;
    }
}
