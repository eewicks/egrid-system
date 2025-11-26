# SOLECO Web Push Notification System Implementation

## Overview
This document describes the implementation of a real-time Web Push Notification system for the SOLECO Energy Grid Monitoring System. The system detects power outages when no signal is received from Arduino Uno Wi-Fi devices for more than 5 minutes and sends browser push notifications to substation administrators.

## System Architecture

### Backend Components

#### 1. Database Tables
- **alert_logs**: Stores all push notification alerts
  - `id`, `device_id`, `barangay`, `message`, `alert_type`, `created_at`, `updated_at`
- **push_subscriptions**: Stores browser push subscription endpoints
  - `id`, `endpoint`, `keys`, `created_at`, `updated_at`

#### 2. Models
- **AlertLog**: Manages alert log entries with device relationships
- **Device**: Existing model with `device_id`, `barangay`, `last_seen`, `status`

#### 3. Notification Class
- **OutageDetected**: Laravel notification for power outages
  - Sends WebPush messages with device info and action buttons
  - Includes "View Dashboard" action for direct navigation

#### 4. Services
- **OutageDetectionService**: Core business logic for outage detection
  - Checks devices offline for >5 minutes
  - Prevents duplicate alerts (10-minute cooldown)
  - Sends notifications to all subscribed users

#### 5. Controllers
- **WebPushController**: Handles push notification API endpoints
  - `/api/webpush/vapid-public-key`: Returns VAPID public key
  - `/api/webpush/subscribe`: Subscribe to notifications
  - `/api/webpush/unsubscribe`: Unsubscribe from notifications
  - `/api/webpush/test`: Send test notification

#### 6. Console Commands
- **CheckOutages**: Artisan command for outage detection
  - `php artisan outages:check`: Check for outages
  - `php artisan outages:check --simulate=DEVICE_ID`: Simulate outage

### Frontend Components

#### 1. JavaScript (public/js/webpush.js)
- **WebPushManager**: Main class for push notification management
  - Handles subscription/unsubscription
  - Manages service worker registration
  - Provides test notification functionality

#### 2. Service Worker (public/sw.js)
- Handles incoming push events
- Displays notifications with action buttons
- Manages notification clicks and navigation

#### 3. UI Integration
- Added to `alert-settings.blade.php` with dedicated section
- Real-time subscription status display
- Test notification functionality
- Mobile and desktop support

## Configuration

### Environment Variables
Add to `.env` file:
```env
VAPID_SUBJECT=mailto:admin@soleco.com
VAPID_PUBLIC_KEY=j791I/iSKbZwYLAprBIHLFrUN+tE2VTPbigs3fUdjxQ=
VAPID_PRIVATE_KEY=crJxuTrmvlIdUTjvVkxEwkjjNX9B9FYRjQn5jJEZILI=
```

### VAPID Keys
VAPID (Voluntary Application Server Identification) keys are required for Web Push:
- **Public Key**: Used by browsers to identify your application
- **Private Key**: Used by server to sign push messages
- **Subject**: Email or URL identifying your application

## Usage Instructions

### 1. Setup
```bash
# Install dependencies (already done)
composer require laravel-notification-channels/webpush

# Run migrations
php artisan migrate

# Generate VAPID keys (if needed)
php artisan webpush:vapid
```

### 2. Testing
1. Navigate to `/settings/alerts` (Alert Settings page)
2. Scroll to "Web Push Notifications" section
3. Click "Enable Notifications" and allow browser permissions
4. Click "Test Notification" to verify functionality
5. Use `/test-push` for comprehensive testing

### 3. Outage Detection
```bash
# Manual outage check
php artisan outages:check

# Simulate outage for testing
php artisan outages:check --simulate=DEVICE_001

# Set up cron job for automatic checking (every 2 minutes)
*/2 * * * * cd /path/to/project && php artisan outages:check
```

### 4. Monitoring
- Check `alert_logs` table for sent notifications
- Monitor `push_subscriptions` for active subscribers
- View Laravel logs for debugging

## Notification Flow

### 1. Outage Detection
1. System checks devices every 2 minutes (via cron)
2. Identifies devices offline for >5 minutes
3. Creates alert log entry
4. Sends push notification to all subscribers

### 2. Push Notification
1. Browser receives push message
2. Service worker displays notification
3. User can click "View Dashboard" to navigate
4. Notification includes device and barangay information

### 3. User Experience
- Notifications appear even when tab is minimized
- Works on mobile and desktop browsers
- Direct navigation to dashboard
- Persistent until user interaction

## Browser Support

### Supported Browsers
- Chrome 50+
- Firefox 44+
- Safari 16+
- Edge 17+

### Requirements
- HTTPS connection (required for push notifications)
- User permission for notifications
- Service worker support

## Security Considerations

### VAPID Keys
- Keep private key secure
- Use different keys for production/staging
- Rotate keys periodically

### Permissions
- Users must explicitly grant notification permission
- No automatic subscription without user consent
- Easy unsubscribe functionality

## Troubleshooting

### Common Issues

#### 1. Notifications Not Appearing
- Check browser notification permissions
- Verify HTTPS connection
- Verify VAPID keys are correct

#### 2. Subscription Fails
- Check browser console for errors
- Verify service worker is registered
- Check network connectivity

#### 3. Outage Detection Not Working
- Verify cron job is running
- Check device `last_seen` timestamps
- Review Laravel logs for errors

### Debug Commands
```bash
# Check push subscriptions
php artisan tinker
>>> \NotificationChannels\WebPush\PushSubscription::count()

# Test notification manually
php artisan outages:check --simulate=DEVICE_001

# Check alert logs
>>> \App\Models\AlertLog::latest()->take(5)->get()
```

## Integration with IoT Workflow

### Arduino Device Integration
1. Arduino sends periodic signals to Laravel API
2. Laravel updates device `last_seen` timestamp
3. System monitors for signal gaps >5 minutes
4. Automatic outage detection and notification

### Real-time Monitoring
- Dashboard shows real-time device status
- Push notifications for immediate alerts
- Historical outage tracking in `alert_logs`
- Integration with existing analytics system

## Performance Considerations

### Database Optimization
- Indexed queries on `device_id` and `created_at`
- Efficient outage detection queries
- Minimal database writes for alerts

### Push Notification Limits
- Browser limits on notification frequency
- Service worker handles queuing
- Efficient message delivery

## Future Enhancements

### Potential Improvements
1. **Notification Preferences**: User-specific alert settings
2. **Alert Escalation**: Multiple notification levels
3. **Mobile App Integration**: Native app notifications
4. **Advanced Analytics**: Notification engagement tracking
5. **Multi-tenant Support**: Separate notifications per organization

### Scalability
- Queue-based notification processing
- Redis for subscription management
- Load balancing for high-volume systems

## Conclusion

The Web Push Notification system provides real-time power outage alerts for the SOLECO Energy Grid Monitoring System. It integrates seamlessly with the existing Laravel application and provides immediate notifications to administrators, ensuring rapid response to power outages in the monitored areas.

The system is production-ready with proper error handling, security considerations, and comprehensive testing capabilities. It supports both desktop and mobile browsers and provides an excellent user experience for critical infrastructure monitoring.
