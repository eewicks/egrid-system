<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use NotificationChannels\WebPush\PushSubscription;
use NotificationChannels\WebPush\WebPushMessage;
use Illuminate\Support\Facades\Log;

class WebPushController extends Controller
{
    /**
     * Get VAPID public key for client-side subscription
     */
    public function getVapidPublicKey(): JsonResponse
    {
        $publicKey = config('webpush.vapid.public_key');
        
        if (!$publicKey) {
            return response()->json(['error' => 'VAPID public key not configured'], 500);
        }
        
        return response()->json(['publicKey' => $publicKey]);
    }
    
    /**
     * Subscribe user to push notifications
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|string',
            'subscription.keys' => 'required|array',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string'
        ]);
        
        try {
            $subscription = $request->input('subscription');
            
            // Check if subscription already exists
            $existingSubscription = PushSubscription::where('endpoint', $subscription['endpoint'])->first();
            
            if ($existingSubscription) {
                // Update existing subscription
                $existingSubscription->update([
                    'keys' => json_encode($subscription['keys']),
                    'updated_at' => now()
                ]);
                
                Log::info('WebPush: Updated existing subscription');
            } else {
                // Create new subscription
                PushSubscription::create([
                    'endpoint' => $subscription['endpoint'],
                    'keys' => json_encode($subscription['keys']),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                Log::info('WebPush: Created new subscription');
            }
            
            return response()->json(['success' => true, 'message' => 'Successfully subscribed to push notifications']);
            
        } catch (\Exception $e) {
            Log::error('WebPush: Failed to subscribe: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to subscribe to push notifications'], 500);
        }
    }
    
    /**
     * Unsubscribe user from push notifications
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|string'
        ]);
        
        try {
            $endpoint = $request->input('subscription.endpoint');
            
            $subscription = PushSubscription::where('endpoint', $endpoint)->first();
            
            if ($subscription) {
                $subscription->delete();
                Log::info('WebPush: Removed subscription');
            }
            
            return response()->json(['success' => true, 'message' => 'Successfully unsubscribed from push notifications']);
            
        } catch (\Exception $e) {
            Log::error('WebPush: Failed to unsubscribe: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to unsubscribe from push notifications'], 500);
        }
    }
    
    /**
     * Handle subscription changes (re-subscription)
     */
    public function resubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|string',
            'subscription.keys' => 'required|array'
        ]);
        
        try {
            $subscription = $request->input('subscription');
            
            // Update or create subscription
            PushSubscription::updateOrCreate(
                ['endpoint' => $subscription['endpoint']],
                [
                    'keys' => json_encode($subscription['keys']),
                    'updated_at' => now()
                ]
            );
            
            Log::info('WebPush: Re-subscription completed');
            
            return response()->json(['success' => true, 'message' => 'Re-subscription successful']);
            
        } catch (\Exception $e) {
            Log::error('WebPush: Failed to resubscribe: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to resubscribe'], 500);
        }
    }
    
    /**
     * Test push notification
     */
    public function testNotification(): JsonResponse
    {
        try {
            $subscriptions = PushSubscription::all();
            
            if ($subscriptions->isEmpty()) {
                return response()->json(['error' => 'No subscriptions found'], 400);
            }
            
            $notification = new \App\Notifications\OutageDetected(
                'TEST_DEVICE_001',
                'Test Barangay',
                now()->format('Y-m-d H:i:s')
            );
            
            foreach ($subscriptions as $subscription) {
                $subscription->notify($notification);
            }
            
            Log::info('WebPush: Test notification sent to ' . $subscriptions->count() . ' subscribers');
            
            return response()->json([
                'success' => true, 
                'message' => 'Test notification sent to ' . $subscriptions->count() . ' subscribers'
            ]);
            
        } catch (\Exception $e) {
            Log::error('WebPush: Failed to send test notification: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send test notification'], 500);
        }
    }
}
