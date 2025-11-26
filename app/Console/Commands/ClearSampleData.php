<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Models\StatusLog;
use Illuminate\Support\Facades\DB;

class ClearSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:sample-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all sample data to ensure only real Arduino device data is used';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing sample data to ensure real-time Arduino data only...');
        
        // Clear sample devices (DEV001, DEV002, DEV003, DEV004)
        $sampleDeviceIds = ['DEV001', 'DEV002', 'DEV003', 'DEV004'];
        
        $deletedDevices = Device::whereIn('device_id', $sampleDeviceIds)->delete();
        $this->info("Deleted {$deletedDevices} sample devices");
        
        // Clear sample status logs
        $deletedLogs = StatusLog::whereIn('device_id', $sampleDeviceIds)->delete();
        $this->info("Deleted {$deletedLogs} sample status logs");
        
        // Clear any power outages data that might be sample data
        $deletedOutages = DB::table('power_outages')->delete();
        $this->info("Deleted {$deletedOutages} power outage records");
        
        $this->info('Sample data cleared successfully!');
        $this->info('Dashboard will now only display real-time data from Arduino devices.');
        
        // Show current real data counts
        $realDevices = Device::count();
        $realLogs = StatusLog::count();
        
        $this->info("Current real data:");
        $this->info("- Devices: {$realDevices}");
        $this->info("- Status Logs: {$realLogs}");
        
        if ($realDevices == 0) {
            $this->warn('No real devices found. Add Arduino devices to start monitoring.');
        }
        
        return 0;
    }
}
