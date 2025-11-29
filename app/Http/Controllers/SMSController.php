<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

class SMSController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    

    /**
     * Test SMS functionality
     */
    public function testSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'nullable|string|max:160'
        ]);

        try {
            $phone = $request->input('phone');
            $message = $request->input('message', 'Test SMS from SOLECO Monitoring System - ' . now()->format('Y-m-d H:i:s'));

            $result = $this->smsService->testSMS($phone, $message);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test SMS',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('SMS Test Error', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'SMS test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send outage alert SMS
     */
    public function sendOutageAlert(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'barangay' => 'required|string',
            'last_signal' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*.phone' => 'required|string',
            'recipients.*.name' => 'nullable|string'
        ]);

        try {
            $result = $this->smsService->sendOutageAlert(
                $request->input('device_id'),
                $request->input('barangay'),
                $request->input('last_signal'),
                $request->input('recipients')
            );

            return response()->json([
                'success' => true,
                'message' => 'Outage alert SMS sent',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Outage Alert SMS Error', [
                'error' => $e->getMessage(),
                'device_id' => $request->input('device_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send outage alert SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get SMS balance/credits
     */
    public function getBalance()
    {
        try {
            $balance = $this->smsService->getBalance();

            if ($balance) {
                return response()->json([
                    'success' => true,
                    'data' => $balance
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to retrieve SMS balance'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('SMS Balance Error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get SMS balance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simulate outage for testing
     */
    public function simulateOutage(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'barangay' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*.phone' => 'required|string'
        ]);

        try {
            $lastSignal = now()->subMinutes(10)->format('Y-m-d H:i:s');
            
            $result = $this->smsService->sendOutageAlert(
                $request->input('device_id'),
                $request->input('barangay'),
                $lastSignal,
                $request->input('recipients')
            );

            return response()->json([
                'success' => true,
                'message' => 'Outage simulation SMS sent successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Outage Simulation SMS Error', [
                'error' => $e->getMessage(),
                'device_id' => $request->input('device_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send simulation SMS: ' . $e->getMessage()
            ], 500);
        }
    }
}
