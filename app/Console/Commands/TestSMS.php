<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SMSService;
use App\Services\OutageDetectionService;

class TestSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone?} {--simulate-outage : Simulate a power outage alert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS functionality for SOLECO monitoring system';

    protected $smsService;
    protected $outageDetectionService;

    public function __construct(SMSService $smsService, OutageDetectionService $outageDetectionService)
    {
        parent::__construct();
        $this->smsService = $smsService;
        $this->outageDetectionService = $outageDetectionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone') ?: '+639123456789';
        $simulateOutage = $this->option('simulate-outage');

        $this->info('Testing SMS functionality for SOLECO Monitoring System...');
        $this->newLine();

        if ($simulateOutage) {
            $this->testOutageSimulation($phone);
        } else {
            $this->testBasicSMS($phone);
        }

        $this->newLine();
        $this->info('SMS testing completed!');
    }

    protected function testBasicSMS($phone)
    {
        $this->info("Testing basic SMS to: {$phone}");
        
        $message = "Test SMS from SOLECO Monitoring System - " . now()->format('Y-m-d H:i:s');
        
        $result = $this->smsService->testSMS($phone, $message);
        
        if ($result['success']) {
            $this->info('✅ SMS sent successfully!');
            $this->line("Message ID: " . ($result['message_id'] ?? 'N/A'));
        } else {
            $this->error('❌ SMS failed: ' . $result['error']);
        }
    }

    protected function testOutageSimulation($phone)
    {
        $this->info("Simulating power outage alert to: {$phone}");
        
        $recipients = [
            ['phone' => $phone, 'name' => 'Test Admin']
        ];
        
        $result = $this->smsService->sendOutageAlert(
            'TEST_DEVICE_001',
            'Test Barangay',
            now()->subMinutes(10)->format('Y-m-d H:i:s'),
            $recipients
        );
        
        $this->info("SMS Results:");
        $this->line("Total recipients: {$result['total']}");
        $this->line("Successful: {$result['success']}");
        $this->line("Failed: {$result['failures']}");
        
        if ($result['success'] > 0) {
            $this->info('✅ Outage alert SMS sent successfully!');
        } else {
            $this->error('❌ Outage alert SMS failed!');
        }
    }
}
