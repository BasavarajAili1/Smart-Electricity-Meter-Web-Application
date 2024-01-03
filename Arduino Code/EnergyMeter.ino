#include <mcp3008.h>
#include <SPI.h>
#include<LiquidCrystal_I2C.h>
#include <WiFi.h>

char *ssid = "METER";            
char *pass = "123456789";
String payload = "";
int httpCode = 0;

// Post
// Get
char *server = "augean-competitions.000webhostapp.com";
char txt[] = "/WebSmartMeter/api/get_amount.php";
int contentLength = 0;
bool isValidContentType = false;
String contentType = "";
int count = 0;

WiFiClient client;

mcp3008 mcp = mcp3008();

const uint8_t _SS   = 15;   // Pins for SPI interface
const uint8_t _MISO = 12;
const uint8_t _MOSI = 13;   // example shows HSPI defaults
const uint8_t _SCLK = 14;

#define LDR_PIN    36
#define buzz       27


LiquidCrystal_I2C lcd(0x27, 16, 2);


float nVPP;   // Voltage measured across resistor
float nCurrThruResistorPP; // Peak Current Measured Through Resistor
float nCurrThruResistorRMS; // RMS current through Resistor
float nCurrentThruWire;     // Actual RMS current in Wire
double sensorValue1 = 0;
double sensorValue2 = 0;
int crosscount = 0;
int climb_flag = 0;
int val[100];
int max_v = 0;
double VmaxD = 0;
double VeffD = 0;
double Veff = 0;

float I_disp = 0;
int V_disp = 0;
int BalanceHold = 0, POWER = 0;

int RELAY = 32;
int pulse = 0, Unit = 0, Amount = 0, disp_amount = 0;
volatile int flag = 0;


void DISP(String R1, String R2, int D)
{
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(R1);
  lcd.setCursor(0, 1);
  lcd.print(R2);
  DELAY(D);
}

void DELAY(int mS)
{
  for (int i = 0; i < mS; i++)
  {
    delay(1);
    if (digitalRead(LDR_PIN) == 0)
      flag = 1;
  }
}

void GetData()
{
  if (client.connect(server, 80))
  {
    client.print(String("GET ") + String(txt) + " HTTP/1.1\r\n" +
                 "Host: " + server + "\r\n" +
                 "Cache-Control: no-cache\r\n" +
                 "Connection: close\r\n\r\n");
    unsigned long timeout = millis();
    Serial.println("Get function 1");
    while (client.available() == 0)
    {
      if (millis() - timeout > 5000)
      {
        Serial.println(">>> Client Timeout !");
        client.stop();
        return;
      }
    }
     Serial.println("Get function 2");
    while (client.available())
    {
      String line = client.readStringUntil('\n');
      line.trim();
      if (!line.length())
        break;
      
      if (line.startsWith("Content-Length: ")) {
        //        contentLength = atoi((getHeaderValue(line, "Content-Length: ")).c_str());
      }
      if (line.startsWith("Content-Type: ")) {
        //        contentType = getHeaderValue(line, "Content-Type: ");
        if (contentType == "application/octet-stream" || contentType == "application/macbinary") {
          isValidContentType = true;
        }
      }
    }
  }
  else
    Serial.println("Connection to " + String(server) + " failed. Please check your setup");
//  client.flush();
  String temp = "", amt = "";
  Serial.println((char)client.read());
  client.stop();
  Serial.print("Data: ");
  Serial.println(temp);

  int ver1 = 0, ver2 = 0;
  if ((-1 != (ver1 = temp.indexOf("amount"))) && (-1 != (ver1 = temp.indexOf("}"))))
  {
    amt = temp.substring(ver1 + 9, ver2 - 1);
    Amount = amt.toInt();
  }
}

void POSTDATA(String postStr)
{

  if (client.connect(server, 80))
  {
    client.print("POST /WebSmartMeter/api/update_parameter.php HTTP/1.1\n");
    client.print("Host: augean-competitions.000webhostapp.com\n");
    client.print("Connection: close\n");
    client.print("Content-Length: ");
    client.print(postStr.length());
    client.print("\n\n");
    client.print(postStr);
    delay(100);
    delay(100);
    String line = client.readStringUntil('\n');
    Serial.println(line);
  }
  client.stop();
}

void ReadVoltage()
{
  //  DISP("Reading Voltage", "and Current...", 1000);

  //  for (int l = 0; l < 100; l++)
  //  {
  for ( int i = 0; i < 100; i++ ) {
    sensorValue1 =  mcp.analogRead(0);
    if (sensorValue1 > 511) {
      val[i] = sensorValue1;
    }
    else {
      val[i] = 0;
    }
    DELAY(1);
  }

  max_v = 0;

  for ( int i = 0; i < 100; i++ )
  {
    if ( val[i] > max_v )
    {
      max_v = val[i];
    }
    val[i] = 0;
  }

  if (max_v != 0) {
    VmaxD = max_v;
    VeffD = VmaxD / sqrt(2);
    Veff = (((VeffD - 420.76) / -90.24) * -210.2) + 210.2;
  }
  else {
    Veff = 0;
  }
  //    delay(1);
  //  }

  //  Veff = Veff / 100;

  Veff = Veff - 530 - 45;
  if (Veff < 50)Veff = 0;
  V_disp = Veff;
  Serial.print("Voltage: ");
  Serial.println(V_disp);
  VmaxD = 0;

}

void ReadCurrent()
{
  //  for (int j = 0; j < 5; j++)
  //  {
  //    nVPP += getVPP();
  //    DELAY(1);
  //  }
  //  nVPP = nVPP / 5;
  nVPP = getVPP();
  nCurrThruResistorPP = (nVPP / 200.0) * 1000.0;
  nCurrThruResistorRMS = nCurrThruResistorPP * 0.707;
  I_disp = nCurrThruResistorRMS;
  I_disp = I_disp - 0.03;
  if (I_disp <= 0.03)I_disp = 0;
  Serial.println("Current: " + String(I_disp));

}

float getVPP()
{
  float result;
  uint16_t value;
  int maxValue = 0;
  uint32_t start_time = millis();
  while ((millis() - start_time) < 1000)
  {
    uint16_t value = mcp.analogRead(1);
    if (value > maxValue)
    {
      maxValue = value;
    }
    DELAY(1);
  }
  Serial.println(maxValue);
  result = (maxValue * 3.3) / 1023.0;

  return result;
}


void GSMFUN(String MSG)
{
  Serial2.println("AT");
  Serial.println("AT");
  delay(1000);
  Serial2.println("AT+CMGS=\"+919739793901\"");
  Serial.println("AT+CMGS=\"+919739793901\"");
  delay(1000);
  Serial2.print(MSG);
  Serial.println(MSG);
  delay(1000);
  Serial2.write(26);
  delay(1000);
}

void ConnectAP(void) {
  WiFi.mode(WIFI_STA);    /* Set WiFi to station mode */
  WiFi.disconnect();     /* disconnect from an AP if it was Previously  connected*/
  delay(100);
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) {
    delay(200);
    Serial.print(".");
  }
  IPAddress ip = WiFi.localIP();
  Serial.println(ip);
}

void setup()
{
  Serial.begin(9600);
  Serial2.begin(9600);
  pinMode(RELAY, OUTPUT);
  pinMode(buzz, OUTPUT);
  pinMode(LDR_PIN, INPUT);
  digitalWrite(RELAY, HIGH);
  digitalWrite(buzz, LOW);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("    WEL-COME    ");
  lcd.setCursor(0, 1);
  lcd.print("    ");
  delay(1000);
  mcp.begin(_SCLK, _MISO, _MOSI, _SS);
  DISP("  CONNECTING TO ", "   WiFi....!", 100);
  Serial.println("Connecting to ");
  Serial.println(ssid);
  ConnectAP();
  DISP("   CONNECTED    ", "   ", 1000);
  //need to add wifi connection function
}

void loop()
{
  GetData();
  ReadVoltage();
  DELAY(100);
  ReadCurrent();
  DELAY(100);
  DISP("Voltage:" + String(V_disp) + " V", "Current:" + String(I_disp) + " Amp", 800);
  POWER = (V_disp * I_disp);
  DISP("Power:" + String(POWER) + " watt", "   ", 800);
  if (flag == 1)
  {
    pulse += 1;
    if (pulse >= 10)
    {
      ++Unit;
      pulse = 0;
    }
    flag = 0;
  }
  Serial.print("pulse:");
  Serial.println(pulse);
  Serial.print("Unit:");
  Serial.println(Unit);
  //  Amount = (Unit * 5);
  //  Amount = (Amount + BalanceHold);
  Amount = (Amount - (Unit * 5));
  if (Amount < 0)Amount = 0;
  DISP("Units: " + String(Unit), "Amount: " + String(Amount), 800);
  if ((Amount > 0) && (Amount < 50))
  {
    digitalWrite(buzz, HIGH);
    DISP("LOW Balence", String(Amount) + " left", 500);
    digitalWrite(buzz, LOW);
    GSMFUN("LOW Balence Rs" + String(Amount) + " left");
  }
  if (Amount <= 0)
  {
    digitalWrite(RELAY, LOW);
    digitalWrite(buzz, HIGH);
    DISP("Nill Balence", " ", 500);
    digitalWrite(buzz, LOW);
    GSMFUN("Nill balence");
  }
  else
  {
    digitalWrite(RELAY, HIGH);
  }
  if (Amount <= 10)
  {
    digitalWrite(buzz, HIGH);
    digitalWrite(RELAY, LOW);
    DISP("  Low Balence   ", "  ", 2000);
    digitalWrite(buzz, LOW);
  }
  POSTDATA("&f1=" + String(V_disp) + "&f2=" + String(I_disp) + "&f3=" + String(POWER) + "&f4=" + String(Unit) + "&f5=" + String(Amount));
  DELAY(3000);

}
