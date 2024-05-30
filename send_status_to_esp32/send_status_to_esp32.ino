#include <WiFi.h>
#include <WebServer.h>
#include <LiquidCrystal_I2C.h>

const char* ssid = "hovahyii.vercel.app";
const char* password = "hovah1234";


const int lcdAddress = 0x27; // I2C address of your LCD
const int lcdCols = 16;      // Number of columns on your LCD
const int lcdRows = 2;       // Number of rows on your LCD

LiquidCrystal_I2C lcd(lcdAddress, lcdCols, lcdRows);

WebServer server(80);

String lcdSentence = "";

void displayLCD(const String& sentence) {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(sentence.substring(0, 16));  // Display the first 16 characters on the first line

  lcd.setCursor(0, 1);
  lcd.print(sentence.substring(16));  // Display the remaining characters on the second line
}

void handleLCDUpdate() {
  if (server.method() == HTTP_POST && server.hasArg("sentence")) {
    lcdSentence = server.arg("sentence");
    Serial.println("Received status: " + lcdSentence); // Debug message
    displayLCD(lcdSentence);
    server.send(200, "text/plain", "LCD updated");
  } else {
    Serial.println("No status provided or wrong method"); // Debug message
    server.send(400, "text/plain", "No status provided or wrong method");
  }
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  
  int attempt = 0;
  while (WiFi.status() != WL_CONNECTED && attempt < 30) { // Limit the number of attempts
    delay(1000);
    Serial.print(".");
    attempt++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConnected to WiFi");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Connected");
    Serial.print("ESP32 IP address: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\nFailed to connect to WiFi");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Failed");
  }
}

void setup() {
  Serial.begin(115200);
  lcd.begin();
  lcd.backlight();
  connectToWiFi();

  if (WiFi.status() == WL_CONNECTED) {
    server.on("/update_status", HTTP_POST, handleLCDUpdate);

    server.onNotFound([]() {
      server.send(404, "text/plain", "Not Found");
    });

    server.begin();
    Serial.println("HTTP server started");
  }
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    server.handleClient();
  }
}