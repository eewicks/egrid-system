<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\StatusLog;
use Carbon\Carbon;

class DashboardTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * DISABLED: This seeder creates sample data which is not needed for production.
     * The dashboard should only display real-time data from Arduino devices.
     */
    public function run(): void
    {
        // DISABLED: Sample data seeder
        // This seeder has been disabled to ensure only real Arduino device data is used.
        // The dashboard will show real-time data from actual Arduino devices.
        
        echo "Sample data seeder is disabled. Dashboard will use real Arduino device data only.\n";
        echo "To enable real-time monitoring, ensure Arduino devices are sending data to the database.\n";
    }
}
