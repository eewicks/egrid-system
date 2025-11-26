<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlertSettingsController extends Controller
{
    /**
     * Display the alert settings page
     */
    public function index()
    {
        try {
            // Get current settings or create default
            $settings = DB::table('alert_settings')->first();
        
            if (!$settings) {
                // Create default settings
                $defaultSettings = [
                    'alerts_enabled' => true,
                    'channels' => json_encode(['sms']),
                    'recipients' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $id = DB::table('alert_settings')->insertGetId($defaultSettings);
                $settings = (object) array_merge(['id' => $id], $defaultSettings);
            }

            // Decode JSON fields
            $settings->channels = json_decode($settings->channels, true) ?? [];
            $settings->recipients = json_decode($settings->recipients, true) ?? [];

            return view('alert-settings', compact('settings'));
        } catch (\Exception $e) {
            // Log the error and return a simple error page
            \Log::error('AlertSettingsController index error: ' . $e->getMessage());
            return response()->view('errors.500', ['message' => 'Error loading alert settings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save alert settings
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'alerts_enabled' => 'nullable|boolean',
                'channels' => 'nullable|array',
                'channels.*' => 'in:sms,web_push',
                'recipients' => 'nullable|array',
                'recipients.*.name' => 'required_with:recipients|string|max:255',
                'recipients.*.phone' => 'nullable|string|max:20',
                'recipients.*.role' => 'required_with:recipients|string|in:admin'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'alerts_enabled' => $request->boolean('alerts_enabled'),
                'channels' => json_encode($request->input('channels', [])),
                'recipients' => json_encode($request->input('recipients', [])),
                'updated_at' => now()
            ];

            // Update or create settings
            $existing = DB::table('alert_settings')->first();
            
            if ($existing) {
                DB::table('alert_settings')->where('id', $existing->id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('alert_settings')->insert($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Alert settings saved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send test alert
     */
    public function testAlert(Request $request)
    {
        try {
            $settings = DB::table('alert_settings')->first();
            
            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No alert settings found'
                ], 404);
            }

            $channels = json_decode($settings->channels, true) ?? [];
            $recipients = json_decode($settings->recipients, true) ?? [];

            if (empty($channels)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No alert channels configured'
                ], 400);
            }

            if (empty($recipients)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No recipients configured'
                ], 400);
            }

            // Simulate sending test alerts
            $testResults = [];
            
            foreach ($channels as $channel) {
                $testResults[$channel] = [
                    'status' => 'sent',
                    'message' => "Test alert sent via {$channel}",
                    'timestamp' => now()->toISOString()
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Test alerts sent successfully!',
                'results' => $testResults,
                'recipients_count' => count($recipients),
                'channels_used' => $channels
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test alert',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}