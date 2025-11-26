/**
 * Service Worker for SOLECO Web Push Notifications
 * Handles push events and displays notifications
 */

const CACHE_NAME = 'soleco-push-v1';
const NOTIFICATION_ICON = '/photos/icon.png';

// Install event
self.addEventListener('install', function(event) {
    console.log('Service Worker: Install event');
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', function(event) {
    console.log('Service Worker: Activate event');
    event.waitUntil(self.clients.claim());
});

// Push event - handle incoming push notifications
self.addEventListener('push', function(event) {
    console.log('Service Worker: Push event received');
    
    let notificationData = {
        title: 'SOLECO Alert',
        body: 'Power outage detected',
        icon: NOTIFICATION_ICON,
        badge: NOTIFICATION_ICON,
        tag: 'soleco-alert',
        requireInteraction: true,
        actions: [
            {
                action: 'view_dashboard',
                title: 'View Dashboard',
                icon: NOTIFICATION_ICON
            },
            {
                action: 'dismiss',
                title: 'Dismiss',
                icon: NOTIFICATION_ICON
            }
        ]
    };
    
    // Parse push data if available
    if (event.data) {
        try {
            const pushData = event.data.json();
            notificationData = {
                ...notificationData,
                ...pushData,
                data: pushData.data || {}
            };
        } catch (error) {
            console.error('Failed to parse push data:', error);
        }
    }
    
    // Show notification
    event.waitUntil(
        self.registration.showNotification(notificationData.title, notificationData)
    );
});

// Notification click event
self.addEventListener('notificationclick', function(event) {
    console.log('Service Worker: Notification click event');
    
    event.notification.close();
    
    if (event.action === 'view_dashboard') {
        // Open dashboard in new window/tab
        event.waitUntil(
            clients.openWindow('/admin/dashboard')
        );
    } else if (event.action === 'dismiss') {
        // Just close the notification (already closed above)
        return;
    } else {
        // Default action - open dashboard
        event.waitUntil(
            clients.openWindow('/admin/dashboard')
        );
    }
});

// Background sync (if supported)
self.addEventListener('sync', function(event) {
    console.log('Service Worker: Background sync event');
    
    if (event.tag === 'outage-check') {
        event.waitUntil(
            // Perform background outage check
            checkForOutages()
        );
    }
});

// Helper function for background outage check
async function checkForOutages() {
    try {
        const response = await fetch('/api/outages/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            console.log('Background outage check completed');
        }
    } catch (error) {
        console.error('Background outage check failed:', error);
    }
}

// Handle push subscription changes
self.addEventListener('pushsubscriptionchange', function(event) {
    console.log('Service Worker: Push subscription change event');
    
    event.waitUntil(
        // Re-subscribe to push notifications
        self.registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: event.oldSubscription.options.applicationServerKey
        }).then(function(subscription) {
            // Send new subscription to server
            return fetch('/api/webpush/resubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    subscription: subscription
                })
            });
        })
    );
});
