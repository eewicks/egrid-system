# Arduino Integration with E-Grid Dashboard

## Setup Instructions for "Household 1 Device"

### Step 1: Find Your Device ID in the Database

1. Go to the Device Management page in your dashboard
2. Find the device with `household_name = "Household 1 Device"`
3. Note the `device_id` for that device (e.g., "DEVICE001", "HOUSEHOLD1", "DEV1", etc.)

### Step 2: Configure Arduino Code

Open `EGrid_Monitoring.ino` and update these values:

```cpp
// WiFi Credentials
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// Server Configuration
const char* serverAddress = "192.168.1.100";  // Your Laravel server IP
const int port = 8000;                         // Your Laravel port
const char* apiKey = "supersecret123";          // Must match ARDUINO_API_KEY in .env

// Device Configuration
const char* deviceId = "DEVICE001";            // MUST match device_id from database
```

### Step 3: Configure Laravel Server

**In your `.env` file:**
```env
ARDUINO_API_KEY=supersecret123
```

**In your database, ensure the device exists:**
```sql
INSERT INTO devices (device_id, household_name, barangay, status) 
VALUES ('DEVICE001', 'Household 1 Device', 'Sample Location', 'OFF');
```

### Step 4: Upload to Arduino

1. Connect your Arduino Uno WiFi Rev 2 to your computer
2. Select the correct board and port in Arduino IDE
3. Upload the code
4. Open Serial Monitor (115200 baud) to monitor communication

## How It Works

### Arduino Behavior:
- **Every 5 minutes**: Arduino sends a heartbeat with power status to Laravel
- **JSON Payload**: `{"device_id":"DEVICE001","status":"ON"}`
- **If online**: Status = "ON" (when powered)
- **If offline**: Arduino doesn't send → Dashboard shows offline

### Dashboard Behavior:
- **Checks `last_seen`** timestamp for each device
- **If `last_seen` < 5 minutes ago**: Shows "ONLINE"
- **If `last_seen` > 5 minutes ago**: Shows "OFFLINE"
- **Updates in real-time** when Arduino sends heartbeat

## Expected Serial Monitor Output

```
========================================
E-Grid Monitoring System - Arduino Client
========================================
Connecting to WiFi: YOUR_SSID
WiFi connected successfully!
=== WiFi Status ===
SSID: YOUR_SSID
IP Address: 192.168.1.XXX
Signal Strength (RSSI): -45 dBm
MAC Address: XX:XX:XX:XX:XX:XX
==================
=== Sending Heartbeat ===
Endpoint: /api/arduino-signal
Payload: {"device_id":"DEVICE001","status":"ON"}
HTTP Response Code: 200
Response: {"ok":true,"ts":"2025-01-26T12:00:00.000000Z"}
Power Status: ON
Heartbeat: SUCCESS
```

## Troubleshooting

### Arduino won't connect to WiFi:
- Check SSID and password are correct
- Ensure WiFi network is 2.4GHz (not 5GHz)
- Check signal strength

### Arduino won't connect to server:
- Verify server IP address is correct
- Ensure Laravel server is running
- Check if port 8000 is open and accessible
- Check firewall settings

### Dashboard shows offline even when Arduino is online:
- Check if API key matches between Arduino and .env
- Verify device_id exists in database
- Check Laravel logs for API errors
- Ensure heartbeat interval hasn't exceeded timeout

### Device not appearing in dashboard:
- Ensure device exists in `devices` table
- Verify `device_id` matches exactly
- Check database connection in Laravel

## Heartbeat Intervals

- **Default**: 5 minutes (300000 milliseconds)
- **Timeout**: Dashboard considers device offline after 5 minutes of no heartbeat
- **To change**: Edit `heartbeatMs` value in code

## Testing

1. Upload code to Arduino
2. Watch Serial Monitor for "Heartbeat: SUCCESS"
3. Go to dashboard and check "Household 1 Device" status
4. If shows "ONLINE" → Success!
5. Unplug Arduino and wait 5+ minutes → Should show "OFFLINE"

## Notes

- The dashboard checks `last_seen` timestamp, not the status field
- A device is considered online if it sent data within the last 5 minutes
- Power status (ON/OFF) is separate from online/offline status
- The Arduino sends its own power source status, not external power status

