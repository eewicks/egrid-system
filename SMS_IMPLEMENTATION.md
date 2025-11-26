# SOLECO SMS Notification System Implementation

## Overview
This document describes the implementation of SMS notification functionality for the SOLECO Energy Grid Monitoring System. The system automatically sends SMS alerts when power outages are detected (no signal from Arduino devices for >5 minutes) and integrates with the existing Web Push notification system.

## System Architecture

### Backend Components

#### 1. SMS Service (`app/Services/SMSService.php`)
- **Primary SMS functionality** using Semaphore API
- **Phone number validation** and formatting for Philippine numbers
- **Bulk SMS sending** for multiple recipients
- **Error handling** and logging for failed attempts
- **Message templates** for different alert types

#### 2. SMS Configuration (`config/sms.php`)
- **API credentials** management
- **Message templates** for different alert types
- **Provider settings** (Semaphore, Twilio support)
- **Rate limiting** and retry settings

#### 3. SMS Controller (`app/Http/Controllers/SMSController.php`)
- **RESTful API endpoints** for SMS operations
- **Test SMS functionality**
- **Outage alert sending**
- **Balance checking**
- **Simulation capabilities**

#### 4. SMS Notification Class (`app/Notifications/SMSAlert.php`)
- **Laravel notification** for SMS alerts
- **Queue support** for background processing
- **Template-based messaging**
- **Integration with notification system**

#### 5. Updated Outage Detection (`app/Services/OutageDetectionService.php`)
- **Dual notification system** (SMS + Web Push)
- **Automatic SMS sending** on outage detection
- **Error logging** for failed SMS attempts
- **Recipient management**

### Database Integration

#### Alert Logs Table
- **SMS alerts** logged with `alert_type = 'sms'`
- **Error tracking** for failed SMS attempts
- **Audit trail** for all notification activities

## Configuration

### Environment Variables
Add to `.env` file:
```env
# Semaphore SMS API Configuration
SEMAPHORE_API_KEY=your_semaphore_api_key_here
SEMAPHORE_API_URL=https://api.semaphore.co/api/v4/messages
SEMAPHORE_SENDER_NAME=SOLECO
SEMAPHORE_ENABLED=true

# SMS Settings
SMS_MAX_RECIPIENTS=50
SMS_MESSAGE_LENGTH_LIMIT=160
SMS_RETRY_ATTEMPTS=3
SMS_RETRY_DELAY=5
```

### API Credentials Setup
1. **Register with Semaphore** (https://semaphore.co)
2. **Get API key** from your Semaphore dashboard
3. **Add credentials** to `.env` file
4. **Test connection** using provided commands

## Usage Instructions

### 1. Basic SMS Testing
```bash
# Test basic SMS functionality
php artisan sms:test +639123456789

# Test with custom message
php artisan sms:test +639123456789 --message="Custom test message"
```

### 2. Outage Simulation
```bash
# Simulate power outage alert
php artisan sms:test +639123456789 --simulate-outage

# Test outage detection system
php artisan outages:check --simulate=DEVICE_001
```

### 3. API Endpoints
```bash
# Test SMS via API
curl -X POST http://your-domain/api/sms/test \
  -H "Content-Type: application/json" \
  -d '{"phone": "+639123456789", "message": "Test message"}'

# Send outage alert
curl -X POST http://your-domain/api/sms/outage-alert \
  -H "Content-Type: application/json" \
  -d '{
    "device_id": "DEVICE_001",
    "barangay": "Test Barangay",
    "last_signal": "2024-01-15 14:30:00",
    "recipients": [{"phone": "+639123456789", "name": "Admin"}]
  }'

# Check SMS balance
curl -X GET http://your-domain/api/sms/balance
```

## Message Templates

### Power Outage Alert
```
⚠ Power outage detected in Barangay {barangay} — Last signal received {last_signal}. SOLECO Monitoring System
```

### Device Online Alert
```
✅ Device {device_id} in Barangay {barangay} is back online. SOLECO Monitoring System
```

### Test Alert
```
Test SMS from SOLECO Monitoring System - {timestamp}
```

## Integration with IoT Workflow

### 1. Outage Detection Process
1. **Arduino device** stops sending signals
2. **System detects** no signal for >5 minutes
3. **OutageDetectionService** triggers alerts
4. **Dual notifications** sent:
   - Web Push notification (browser)
   - SMS alert (mobile phone)
5. **Alert logs** created for both notification types

### 2. Notification Flow
```
Device Offline → Outage Detection → SMS Service → Semaphore API → Recipients
                ↓
            Web Push Service → Browser Notifications
                ↓
            Alert Logs → Database Storage
```

### 3. Error Handling
- **Failed SMS attempts** logged in `alert_logs` table
- **Retry mechanism** for temporary failures
- **Fallback options** if primary SMS service fails
- **Comprehensive logging** for debugging

## Testing and Validation

### 1. Unit Testing
```bash
# Test SMS service
php artisan sms:test +639123456789

# Test outage simulation
php artisan sms:test +639123456789 --simulate-outage
```

### 2. Integration Testing
```bash
# Test complete outage detection
php artisan outages:check --simulate=DEVICE_001

# Check alert logs
php artisan tinker
>>> \App\Models\AlertLog::where('alert_type', 'sms')->latest()->take(5)->get()
```

### 3. API Testing
- Use provided API endpoints for testing
- Monitor logs for successful/failed attempts
- Verify message delivery to recipients

## Security Considerations

### API Key Management
- **Secure storage** of API credentials in `.env`
- **Environment-specific** configurations
- **Access control** for SMS endpoints

### Rate Limiting
- **Recipient limits** to prevent abuse
- **Message length** validation
- **Retry mechanisms** with delays

### Phone Number Validation
- **Philippine number format** validation
- **International format** support
- **Invalid number** filtering

## Performance Optimization

### Queue Processing
- **Background SMS sending** using Laravel queues
- **Bulk SMS optimization** for multiple recipients
- **Error handling** without blocking main process

### Database Efficiency
- **Indexed queries** on alert logs
- **Efficient recipient** retrieval
- **Minimal database writes** for alerts

## Monitoring and Maintenance

### Log Analysis
```bash
# Check SMS logs
tail -f storage/logs/laravel.log | grep "SMS"

# Monitor alert logs
php artisan tinker
>>> \App\Models\AlertLog::where('alert_type', 'sms')->count()
```

### Balance Monitoring
```bash
# Check SMS credits
curl -X GET http://your-domain/api/sms/balance
```

### Health Checks
- **API connectivity** testing
- **Credits balance** monitoring
- **Delivery success** rates
- **Error rate** tracking

## Troubleshooting

### Common Issues

#### 1. SMS Not Sending
- Check API credentials in `.env`
- Verify phone number format
- Check Semaphore account balance
- Review error logs

#### 2. Invalid Phone Numbers
- Ensure Philippine format (+63XXXXXXXXX)
- Remove spaces and special characters
- Validate number length

#### 3. API Errors
- Check internet connectivity
- Verify API endpoint URLs
- Review rate limiting settings
- Check account status

### Debug Commands
```bash
# Test SMS service
php artisan sms:test +639123456789

# Check configuration
php artisan config:show sms

# View recent alerts
php artisan tinker
>>> \App\Models\AlertLog::latest()->take(10)->get()
```

## Future Enhancements

### Potential Improvements
1. **Multiple SMS providers** (Twilio, Globe Labs, etc.)
2. **Advanced message templates** with variables
3. **SMS scheduling** for maintenance windows
4. **Delivery status** tracking
5. **Recipient preferences** and opt-out functionality

### Scalability
- **Queue-based processing** for high volume
- **Load balancing** for multiple providers
- **Caching** for recipient data
- **Database optimization** for large-scale deployments

## Conclusion

The SMS notification system provides reliable, real-time alerts for power outages in the SOLECO Energy Grid Monitoring System. It integrates seamlessly with the existing Web Push notification system and provides comprehensive error handling, logging, and monitoring capabilities.

The system is production-ready with proper security measures, performance optimization, and comprehensive testing capabilities. It ensures that administrators receive immediate notifications about power outages through both browser and mobile channels.
