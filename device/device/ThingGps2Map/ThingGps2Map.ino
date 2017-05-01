/*
*  This sketch sends mac address, date, latitude and longitude 
*  received from GPS to HTTP server using open wifi network
*  1. scan wifi networks and connect to open wifi if found
*  2. read data from gps device and send it to 5kilo.com server
*/

#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const int LED_PIN = 5;


byte ledStatus = LOW;
int status = WL_IDLE_STATUS;

// Use HTTPClient class
//WiFiClient client;
HTTPClient client;

const char mapHost[] = "http://5kilo.com/gps-2-map/api/?";
const int port = 80;

// GPS device settings for SoftwareSerial
static const int RXPin = 12, TXPin = 13;
static const uint32_t GPSBaud = 9600;

// The TinyGPS++ object
TinyGPSPlus gps;

// The serial connection to the GPS device
SoftwareSerial ss(RXPin, TXPin);

enum eStates
{
	EScan,
	EConnect,
	Esend
};

eStates state = EScan;
//eStates state = EConnect;

void setup() {
	Serial.begin(115200);
  ss.begin(GPSBaud);
	pinMode(LED_PIN, OUTPUT);
	
	digitalWrite(LED_PIN, HIGH); // Write LED high/low
	delay(1000);
	digitalWrite(LED_PIN, LOW); // Write LED high/low
  Serial.println("Setup done");
}


void loop() {
  
// main statechart loop
 
	switch (state)
	{
	  case EScan:
	  {
		  if (scan_Wifi())
			  state = Esend; // connect is not necessary
		  break;
	  }
	  case EConnect:
	  {
		  if (connectServer())
			  state = Esend;
		  break;
	  }
	  case Esend:
	  {
		  //get gpsdata and send it to server
		  if (!SendToServer())
			  state = EScan;
		  break;
	  }

	}
}


// connect to server
bool connectServer()
{
 
   
	Serial.print("connecting to ");
	Serial.println(mapHost);
  //client.begin("http://5kilo.com/gps-2-map/api/?");
/*
	if (!client.connect(mapHost, port)) {
		Serial.println("connection failed");
		delay(1000);
		return false;
	}*/
  Serial.println("Connected");
 
	return true;
  
}

/*
 * Scan wifi networks and connect to any open network
*/
bool scan_Wifi()
{
  const char android[] = "AndroidAP"; // testing purposes androidap can be used
  const char pwd[]=""; //set the password for the ssid
  
	// Set WiFi to station mode and disconnect from an AP if it was previously connected
	WiFi.mode(WIFI_STA);
	WiFi.disconnect();
   
	delay(100);

	Serial.println("scan start");

	// WiFi.scanNetworks will return the number of networks found
	int n = WiFi.scanNetworks();
	Serial.println("scan done");
	if (n == 0)
		Serial.println("no networks found");
	else
	{
		Serial.print(n);
		Serial.println(" networks found");
		for (int i = 0; i < n; ++i)
		{
			// Print SSID and RSSI for each network found
			Serial.print(i + 1);
			Serial.print(": ");
			Serial.print(WiFi.SSID(i));
			Serial.print(" (");
			Serial.print(WiFi.RSSI(i));
			Serial.print(")");
			Serial.println((WiFi.encryptionType(i) == ENC_TYPE_NONE) ? " " : "*");
      char ssid[100];
      WiFi.SSID(i).toCharArray(ssid, sizeof(ssid));

      //try to connect if open wifi or androidap was found
			if (WiFi.encryptionType(i) == ENC_TYPE_NONE || strcmp(ssid,android) == 0)
			{
         delay(100);
        if (WiFi.encryptionType(i) == ENC_TYPE_NONE)
        {
				  Serial.print("Connecting to open SSID:");
				  Serial.println(ssid);
				  WiFi.begin(ssid);
        }
       else //android
           WiFi.begin(android,pwd);
          
        int connectLoop=0;
        yield();
        
				while (WiFi.status() != WL_CONNECTED)
				{

					digitalWrite(LED_PIN, ledStatus); // Write LED high/low
					ledStatus = (ledStatus == HIGH) ? LOW : HIGH;

					delay(100);
          
					if (connectLoop> 100) {

						Serial.print("Timeout connecting to: ");
						Serial.println(ssid);
						digitalWrite(LED_PIN, LOW);
						 
						break;
					}
          
					connectLoop++;

				}
               
        delay(1000);
				if (WiFi.status() == WL_CONNECTED)
	      {
          Serial.print("Connected to ");
          Serial.println(ssid);
          return true;
        }

			}
		}


	}
  // could not connect to any wifi network
  // stays in scan state
	Serial.println("");
	return false;

}

/*
 * Send macaddress, time and location to server
*/
bool SendToServer()
{
   // start feeding gps
    smartDelay(1000);
    
    String PostData = Send_Location_Time();
  
    client.begin(mapHost);
    //client.addHeader("Content-Type", "application/json");
    client.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    unsigned long start = millis(); //start timer to measure server response time
    int interval=3000; //lets wait 2seconds
    yield();
    int httpCode= client.POST(PostData);      
   
    
    while (true)
    {
      if (millis()-start>interval)
      {
        //no response from server
        client.end();
        return false;
      }
      
      Serial.print("Server response: ");
      Serial.println(httpCode);
                
      smartDelay(100);      
      String PostData = Send_Location_Time(); 
      //print the stuff to serial
      Serial.println(PostData);
      delay(100);
      start=millis();  //start timer again
      
      int httpCode= client.POST(PostData);
      yield();
      
    }
    
    client.end();
    return true;
}
	
/*
* returns gps location and time in url format:
*device_id=sldfasdl&date_and_time=yyy-mm-dd hh:mm:ss&latitude=65.008137&longitude=25.489753
*/	  

String Send_Location_Time()
{ 
  
  uint8_t mac[WL_MAC_ADDR_LENGTH];
  WiFi.macAddress(mac);
  String macAddress="";
  for (int i=0;i<WL_MAC_ADDR_LENGTH;i++)
    macAddress+=String(mac[i],HEX);
    
  String retval="device_id=";
  retval+=macAddress;
  retval+="&date_and_time=";
  if (gps.date.isValid())
  {
    retval+=gps.date.year();
    retval+="-";
    retval+=gps.date.month();
    retval+="-";
    retval+=gps.date.day();
    retval+=" ";
  }
  else retval+="Invalid ";

  if (gps.time.isValid())
  {
    retval+=gps.time.hour();
    retval+=":";
    retval+=gps.time.minute();
    retval+=":";
    retval+=gps.time.second();
//      retval+=":";
//      retval+=gps.time.centisecond();
       
  }
  else retval+="Invalid";
      
  if (gps.location.isValid())
  {
    char buf[12];
    retval+="&latitude=";
    //ftoa(buf,gps.location.lat(),6);
    dtostrf(gps.location.lat(),4, 6, buf);
    retval+=buf;
    retval+="&longitude=";
    //ftoa(buf,gps.location.lng(),6);   
    dtostrf(gps.location.lng(),4, 6, buf);
    retval+=buf;    
      
  }
  else retval+="&latidute=Invalid&longitude=Invalid";

   //Serial.println(retval);

   
  return (retval);

}

/*
* returns gps location and time in json format:
 *{"device_id":"23432423","latidute":"65.008137","longitude":"25.489753","DateTime":"12/5/2016 14:32:13.00"} 
*/

String Send_Location_Time_JSON()
{ 

  uint8_t mac[WL_MAC_ADDR_LENGTH];
  WiFi.macAddress(mac);
  String macAddress="";
  for (int i=0;i<WL_MAC_ADDR_LENGTH;i++)
    macAddress+=String(mac[i],HEX);
    
  String retval="{\"device_id\"";
  retval+=":\"";
  retval+= macAddress+"\",";
  retval+="\"date_and_time\"";
  retval+=":\"";
  if (gps.date.isValid())
  {
    retval+=gps.date.year();
    retval+="-";
    retval+=gps.date.month();
    retval+="-";
    retval+=gps.date.day();
    retval+=" ";
  }
  else retval+="Invalid ";

  if (gps.time.isValid())
  {
    retval+=gps.time.hour();
    retval+=":";
    retval+=gps.time.minute();
    retval+=":";
    retval+=gps.time.second();
//      retval+=":";
//      retval+=gps.time.centisecond();
       
  }
  else retval+="Invalid";
  retval+="\",";
      
  if (gps.location.isValid())
  {
    char buf[12];
    retval+="\"latidute\"";
    retval+=":\"";
    //ftoa(buf,gps.location.lat(),6);
    dtostrf(gps.location.lat(),4, 6, buf);
    retval+=buf;
    retval+="\",";
    retval+="\"longitude\"";
    retval+=":\"";
    //ftoa(buf,gps.location.lng(),6);
    dtostrf(gps.location.lng(),4, 6, buf);
    retval+=buf;
    retval+="\"";  
  }
  else retval+="\"latidute\":\"Invalid\",\"longitude\":\"Invalid\"";
  retval+="}";
  Serial.println(retval);

   
  return (retval);

}

/*
* This custom version of delay() ensures that the gps object
* is being "fed".
*/
static void smartDelay(unsigned long ms)
{
  unsigned long start = millis();
  do 
  {
    while (ss.available())
      gps.encode(ss.read());
      yield(); // give others some time..
  } while (millis() - start < ms);
}

// read gps data once in a second and yield
void gps_loop()
  {
    while (ss.available())
    {
      gps.encode(ss.read());
      delay(1000);
      yield();
    }
    
}



