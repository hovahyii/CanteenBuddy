#include <WiFi.h>
#include <HTTPClient.h>
#include <LiquidCrystal_I2C.h>

const char* ssid = "hovahyii.vercel.app";
const char* password = "hovah1234";

LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address to 0x27 for a 16 chars and 2 line display

void setup() {
  Serial.begin(115200);
  lcd.begin();
  lcd.backlight();
  connectToWiFi();
  
  // Print the IP address
  Serial.println(WiFi.localIP());
}

void loop() {
  // Leave empty if not needed, but it's required by Arduino framework
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
    lcd.setCursor(0, 0);
    lcd.print("Connecting...");
  }
  Serial.println("Connected to WiFi");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected");
  // Print the IP address
  Serial.println(WiFi.localIP());
}
