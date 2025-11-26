#include <WiFiNINA.h>
#include <avr/wdt.h>

const char* ssid = "Bacalla";
const char* password = "0101010101";
const char* serverIP = "152.67.203.21";
const int   serverPort = 8000;
const char* endpoint = "/api/arduino-signal";
const char* deviceId = "01";

const bool TEST_MODE_FLAG = true;
const int sensorPin = 2;
unsigned long lastHeartbeat = 0;
const unsigned long heartbeatInterval = 5000;

WiFiClient client;

bool ensureWiFi()
{
    if (WiFi.status() == WL_CONNECTED) return true;

    Serial.println("Reconnecting WiFi...");
    WiFi.disconnect();
    WiFi.begin(ssid, password);

    unsigned long start = millis();
    while (WiFi.status() != WL_CONNECTED && millis() - start < 15000UL) {
        delay(500);
        Serial.print(".");
    }
    Serial.println();

    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("WiFi reconnect failed.");
        return false;
    }

    Serial.print("WiFi Connected Successfully!. IP: ");
    Serial.println(WiFi.localIP());
    return true;
}

void sendStatusToServer(const String& status)
{
    if (!ensureWiFi()) return;

    Serial.println("Connecting to server...");
    if (!client.connect(serverIP, serverPort)) {
        Serial.println("server failed!.");
        return;
    }

    String payload = "{\"device_id\":\"" + String(deviceId) +
                     "\",\"status\":\"" + status + "\"}";

    String request =
        String("POST ") + endpoint + " HTTP/1.1\r\n" +
        "Host: " + serverIP + ":" + serverPort + "\r\n" +
        "Content-Type: application/json\r\n" +
        "Connection: close\r\n" +
        "Content-Length: " + payload.length() + "\r\n\r\n" +
        payload;

    client.print(request);
    Serial.println("--- Request ---");
    Serial.println(request);

    unsigned long timeout = millis();
    while (client.available() == 0) {
        if (millis() - timeout > 5000) {
            Serial.println(">>> Response timeout");
            client.stop();
            return;
        }
    }

    Serial.println("--- Response ---");
    while (client.available()) {
        Serial.print((char)client.read());
    }
    Serial.println("\n---------------");

    client.stop();
    delay(50);
}

void setup()
{
    Serial.begin(9600);
    pinMode(sensorPin, INPUT);
    delay(500);

    Serial.println("Connecting to WiFi...");
    WiFi.begin(ssid, password);

    if (!ensureWiFi()) {
        Serial.println("WiFi failed, restarting watchdog.");
        wdt_enable(WDTO_1S);
        while (true) {}
    }
}

void loop()
{
    String status;
    if (TEST_MODE_FLAG) {
        status = "ON";
    } else {
        status = digitalRead(sensorPin) == HIGH ? "ON" : "OFF";
    }

    if (millis() - lastHeartbeat >= heartbeatInterval) {
        Serial.print("Heartbeat -> ");
        Serial.println(status);
        sendStatusToServer(status);
        lastHeartbeat = millis();
    }
}