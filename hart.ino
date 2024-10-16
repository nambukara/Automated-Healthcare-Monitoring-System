#include <OneWire.h>               //temperature sensor
#include <DallasTemperature.h>    //temperature sensor
#include <WiFi.h>                 //wifi
#include <HTTPClient.h>           // sending HTTP requests to remort server

// temperature sensor connected to 32 pin
#define ONE_WIRE_BUS 32
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

// Heart rate sensor connected to GPIO 33
#define HEART_RATE_PIN 33

int heartRateValue = 0;

// Wi-Fi credentials
const char* ssid = "Tharidi";           // Replace with your SSID
const char* password = "Gamagetp9";   // Replace with your Wi-Fi password

// Server URL (your local server's IP address)
const char* serverName = "http://192.168.141.72/pulseminde_1.0/store_data.php"; // Replace with your server's IP

void setup() {
  //led blink
  pinMode(12, OUTPUT);

  // Start serial communication
  Serial.begin(115200);

  // Start the DS18B20 sensor
  sensors.begin();

  // Set heart rate sensor pin as input
  pinMode(HEART_RATE_PIN, INPUT);

  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println();
  Serial.println("Connected to WiFi");
}

void loop() {
  digitalWrite(12, HIGH);
   delay(1000);
   digitalWrite(12, LOW);
   delay(1000);

  // Request temperature measurement from DS18B20
  sensors.requestTemperatures();
  float temperatureC = sensors.getTempCByIndex(0);

  // Read heart rate sensor value
  heartRateValue = analogRead(HEART_RATE_PIN);

  // Convert the analog value to a readable pulse (this depends on your sensor)
  int pulse = map(heartRateValue, 0, 4095, 40, 100); // Adjust range based on sensor output

  // Calculate stress index
  float stress_index = (pulse - 80) / 80.0 + (temperatureC - 33) / 33.0;

  // Print temperature, heart rate, and stress index to Serial Monitor
  Serial.print("Temperature: ");
  Serial.print(temperatureC);
  Serial.println(" °C");

  Serial.print("Heart Rate: ");
  Serial.println(pulse);

  Serial.print("Stress index: ");
  Serial.println(stress_index);

  // Send data to server
  if (WiFi.status() == WL_CONNECTED) {

   

    HTTPClient http;

    // Specify request destination
    http.begin(serverName);

    // Specify content type
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Prepare data to send
    String httpRequestData = "stress_index=" + String(stress_index, 2) +
                              "&temperature=" + String(temperatureC, 2) +
                              "&heart_rate=" + String(pulse) ;


    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);

    // Check for success
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Server Response: " + response);
    } else {
      Serial.println("Error on sending POST: " + String(httpResponseCode));
    }

    // Free resources
    http.end();
  } else {
    Serial.println("WiFi not connected");
  }

  // Add a delay before the next reading
  delay(2000);  // 2-second delay
}
