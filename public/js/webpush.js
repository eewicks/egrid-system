/**
 * Web Push Notification System for SOLECO Energy Grid Monitoring
 * Handles push notification subscription and display
 */

class WebPushManager {
    constructor() {
        this.isSupported = 'serviceWorker' in navigator && 'PushManager' in window;
        this.registration = null;
        this.subscription = null;
        this.vapidPublicKey = null;
        
        this.init();
    }
    
    async init() {
        if (!this.isSupported) {
            console.warn('Web Push Notifications are not supported in this browser');
            return;
        }
        
        try {
            // Get VAPID public key from server
            await this.getVapidPublicKey();
            
            // Register service worker
            await this.registerServiceWorker();
            
            // Check existing subscription
            await this.checkSubscription();
            
            console.log('Web Push Manager initialized successfully');
        } catch (error) {
            console.error('Failed to initialize Web Push Manager:', error);
        }
    }
    
    async getVapidPublicKey() {
        try {
            const response = await fetch('/api/webpush/vapid-public-key');
            const data = await response.json();
            this.vapidPublicKey = data.publicKey;
        } catch (error) {
            console.error('Failed to get VAPID public key:', error);
            throw error;
        }
    }
    
    async registerServiceWorker() {
        try {
            this.registration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered successfully');
        } catch (error) {
            console.error('Service Worker registration failed:', error);
            throw error;
        }
    }
    
    async checkSubscription() {
        try {
            this.subscription = await this.registration.pushManager.getSubscription();
            this.updateSubscriptionStatus();
        } catch (error) {
            console.error('Failed to check subscription:', error);
        }
    }
    
    async subscribe() {
        if (!this.isSupported) {
            throw new Error('Web Push Notifications are not supported');
        }
        
        if (!this.vapidPublicKey) {
            throw new Error('VAPID public key not available');
        }
        
        try {
            // Convert VAPID key to Uint8Array
            const applicationServerKey = this.urlBase64ToUint8Array(this.vapidPublicKey);
            
            // Subscribe to push notifications
            this.subscription = await this.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            });
            
            // Send subscription to server
            await this.sendSubscriptionToServer();
            
            this.updateSubscriptionStatus();
            this.showNotification('Success!', 'Push notifications are now enabled for SOLECO monitoring.');
            
            console.log('Successfully subscribed to push notifications');
            
        } catch (error) {
            console.error('Failed to subscribe to push notifications:', error);
            throw error;
        }
    }
    
    async unsubscribe() {
        if (!this.subscription) {
            throw new Error('No active subscription found');
        }
        
        try {
            await this.subscription.unsubscribe();
            await this.removeSubscriptionFromServer();
            
            this.subscription = null;
            this.updateSubscriptionStatus();
            this.showNotification('Unsubscribed', 'Push notifications have been disabled.');
            
            console.log('Successfully unsubscribed from push notifications');
            
        } catch (error) {
            console.error('Failed to unsubscribe from push notifications:', error);
            throw error;
        }
    }
    
    async sendSubscriptionToServer() {
        const response = await fetch('/api/webpush/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                subscription: this.subscription
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to send subscription to server');
        }
    }
    
    async removeSubscriptionFromServer() {
        const response = await fetch('/api/webpush/unsubscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                subscription: this.subscription
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to remove subscription from server');
        }
    }
    
    updateSubscriptionStatus() {
        const statusElement = document.getElementById('push-status');
        const subscribeBtn = document.getElementById('subscribe-btn');
        const unsubscribeBtn = document.getElementById('unsubscribe-btn');
        
        if (this.subscription) {
            if (statusElement) statusElement.textContent = 'Subscribed';
            if (statusElement) statusElement.className = 'badge badge-success';
            if (subscribeBtn) subscribeBtn.style.display = 'none';
            if (unsubscribeBtn) unsubscribeBtn.style.display = 'inline-block';
        } else {
            if (statusElement) statusElement.textContent = 'Not Subscribed';
            if (statusElement) statusElement.className = 'badge badge-warning';
            if (subscribeBtn) subscribeBtn.style.display = 'inline-block';
            if (unsubscribeBtn) unsubscribeBtn.style.display = 'none';
        }
    }
    
    showNotification(title, body, options = {}) {
        if (!('Notification' in window)) {
            console.warn('This browser does not support notifications');
            return;
        }
        
        if (Notification.permission === 'granted') {
            const notification = new Notification(title, {
                body: body,
                icon: '/photos/icon.png',
                badge: '/photos/icon.png',
                ...options
            });
            
            // Auto-close after 5 seconds
            setTimeout(() => {
                notification.close();
            }, 5000);
        }
    }
    
    async requestPermission() {
        if (!('Notification' in window)) {
            throw new Error('This browser does not support notifications');
        }
        
        const permission = await Notification.requestPermission();
        
        if (permission === 'denied') {
            throw new Error('Notification permission denied');
        }
        
        return permission;
    }
    
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');
        
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
    
    // Test method to simulate a notification
    testNotification() {
        this.showNotification(
            '⚠ Test Power Outage Alert',
            'This is a test notification from SOLECO monitoring system.',
            {
                tag: 'test-notification',
                requireInteraction: true
            }
        );
    }
}

// Initialize Web Push Manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.webPushManager = new WebPushManager();
    
    // Set up event listeners for buttons
    const subscribeBtn = document.getElementById('subscribe-btn');
    const unsubscribeBtn = document.getElementById('unsubscribe-btn');
    const testBtn = document.getElementById('test-notification-btn');
    
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', async function() {
            try {
                await window.webPushManager.requestPermission();
                await window.webPushManager.subscribe();
            } catch (error) {
                alert('Failed to subscribe: ' + error.message);
            }
        });
    }
    
    if (unsubscribeBtn) {
        unsubscribeBtn.addEventListener('click', async function() {
            try {
                await window.webPushManager.unsubscribe();
            } catch (error) {
                alert('Failed to unsubscribe: ' + error.message);
            }
        });
    }
    
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            window.webPushManager.testNotification();
        });
    }
});
