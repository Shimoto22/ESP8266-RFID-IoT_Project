#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

// RFID Module connections
#define SS_PIN D2
#define RST_PIN D1
MFRC522 mfrc522(SS_PIN, RST_PIN);

// On-board LED
#define ON_Board_LED 2

// WiFi credentials
const char* ssid = "Internet";
const char* password = "Fastwords-111";

// Web server
ESP8266WebServer server(80);

// LCD config
LiquidCrystal_I2C lcd(0x27, 16, 2);

// RFID variables
int readsuccess;
byte readcard[4];
String StrUID;

void setup() {
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();

  Wire.begin(D3, D4);
  lcd.init();
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("Connecting to");
  lcd.setCursor(0, 1);
  lcd.print("WiFi...");

  WiFi.begin(ssid, password);
  pinMode(ON_Board_LED, OUTPUT);
  digitalWrite(ON_Board_LED, HIGH);

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    digitalWrite(ON_Board_LED, LOW);
    delay(250);
    digitalWrite(ON_Board_LED, HIGH);
    delay(250);
  }

  digitalWrite(ON_Board_LED, HIGH);

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected!");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());
  delay(2000);

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan your ID");

  Serial.println("\nConnected to WiFi!");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Waiting for RFID scan...");
}

void loop() {
  readsuccess = getid();

  if (readsuccess) {
    digitalWrite(ON_Board_LED, LOW);

    HTTPClient http;
    String UIDresultSend = StrUID;
    String postData = "UIDresult=" + UIDresultSend;

    http.begin("http://192.168.1.10/NodeMCU-and-RFID-RC522-IoT-Projects/getUID.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST(postData);
    String payload = http.getString();

    Serial.println("UID Sent: " + UIDresultSend);
    Serial.print("HTTP Code: ");
    Serial.println(httpCode);
    Serial.println("Response: " + payload);

    lcd.clear();
    if (httpCode == 200) {
      lcd.setCursor(0, 0);
      lcd.print("ACCESS GRANTED");
    } else {
      lcd.setCursor(0, 0);
      lcd.print("ACCESS DENIED");
    }

    lcd.setCursor(0, 1);
    lcd.print("UID:");
    lcd.print(UIDresultSend);

    http.end();
    delay(3000);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Scan your ID");

    digitalWrite(ON_Board_LED, HIGH);
  }
}

// Read RFID UID
int getid() {
  if (!mfrc522.PICC_IsNewCardPresent()) return 0;
  if (!mfrc522.PICC_ReadCardSerial()) return 0;

  StrUID = "";
  for (int i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) StrUID += "0";
    StrUID += String(mfrc522.uid.uidByte[i], HEX);
  }
  StrUID.toUpperCase();

  Serial.println("Card UID: " + StrUID);

  mfrc522.PICC_HaltA();
  return 1;
}