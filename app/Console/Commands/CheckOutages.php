<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OutageDetectionService;

class CheckOutages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outages:check {--simulate= : Simulate outage for specific device ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for power outages and send push notifications';

    protected $outageDetectionService;

    public function __construct(OutageDetectionService $outageDetectionService)
    {
        parent::__construct();
        $this->outageDetectionService = $outageDetectionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $simulateDeviceId = $this->option('simulate');
        
        if ($simulateDeviceId) {
            $this->info("Simulating outage for device: {$simulateDeviceId}");
            try {
                $this->outageDetectionService->simulateOutage($simulateDeviceId);
                $this->info("Outage simulation completed successfully!");
            } catch (\Exception $e) {
                $this->error("Failed to simulate outage: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->info("Checking for power outages...");
            $this->outageDetectionService->checkForOutages();
            $this->info("Outage check completed!");
        }
        
        return 0;
    }
}
