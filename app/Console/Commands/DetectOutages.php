<?php

namespace App\Console\Commands;

use App\Models\Outage;
use Illuminate\Console\Command;

class DetectOutages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:detect-outages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $devices = Device::all();

    foreach ($devices as $device) {
        // Check if device has been quiet
        if ($device->last_seen && now()->diffInSeconds($device->last_seen) > 60) {

            // If not already in outage
            $existing = Outage::where('device_id', $device->id)
                ->whereNull('ended_at')
                ->first();

            if (!$existing) {
                Outage::create([
                    'device_id' => $device->id,
                    'household_id' => $device->household->id ?? null,
                    'started_at' => $device->last_seen,
                    'status' => 'active'
                ]);
            }

            $device->status = "OFF";
            $device->save();
        }
    }
}
}
