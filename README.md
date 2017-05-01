# Arduino GPS-2-Map Tracker Application - README.md #
[Demo](http://5kilo.com/gps-2-map/map.php)

Username: admin

Password: admin

This repository contains Arduino GPS-2-Map Tracker application develop by Esubalew Workineh, Ari Kairala, Risto Juntunen,and Yurong Zhao as course work for Real Time Distributed Software Development -course of Univeristy Oulu.

The project aimed to build a location tracking system for visually impaired persons using Arduino-microcontroller unit and a web server. The Arduino unit uses separate GPS-module added to it to capture current location of the device. It then sends the location coordinates with time information for the web server through a network connection that is established with Arduino’s on-board WiFi-module. After that, the real time location of the device will be updated and displayed on a Google Map-view displayed on a website located on the web server. The website will also contain a place for a video stream data extension, that will be developed outside this project in future.

### This project repository contains two separate folders ###
* Device - Code for Arduino-microcontroller unit (C++)
* Server - Web application for server (PHP, HTML, CSS, JavaScript, MySQL)

### Content of this README-file ###
1. Cloning project repository
2. Setting up a server
3. Setting up Arduino device and environment
4. How to use the system
5. Limitations
6. Suggestions for future development

### 1. Cloning project repository ###
To start working with project clone or download this repository to your computer.
To clone repository using git use command "git clone https://username@bitbucket.org/risjug/arduino-gps-2-map.git". Replace _username_ with your own. If you want to download repository as zip-file click "Downdloads" from Bitbucket's navigation and then on next page click "Download repository".

### 2. Setting up a server ###
#### 2.1 Requirements ####
To establish a server for GPS-2-Map you need a HTTP-server, that supports PHP version 5.6 and MySQL. In other words very common LAMP-server (Linux, Apache, MySQL, PHP) will do perfectly.

#### 2.2 Change username and password - Not mandatory ####
By default you can log in to the web application with username **admin** and password **admin**. For development use these are okay, but if you want you can change these by editing file /gps-2-map/api/database.php.

See lines _72_ and _73_ from file and change the values.

Note that also device_id can be changed in this file. See next section 3.3.

#### 2.3 Change device_id aka MAC-address - If needed ####
By default the application is get's information from device that's device_id aka MAC-address can be seen in file /gps-2-map/api/database.php on line 84. If you are using different device, change value there to correspond to the one on your device.

#### 2.4 Moving files to server ####
Once you have server up and running create folder GPS-2-map to your server's WWW-root using FTP-program like FileZilla. Then copy all files from repository folder's subfolder called server to your server's WWW-root. Note: do not copy folder server itself, only subfolders and files under it.

#### 2.5 Create MySQL-user with required priviledged ####
Use phpMyAdmin or similiar MySQL-management tool to create user **gps2map** with password **pass123**.
Set privileges SELECT, INSERT, UPDATE and CREATE for this new user.

#### 2.6 Run application for first time to initialize database ####
Open GPS-2-Map server application by entering URL http://mydomain/gps-2-map on your Internet broswer. Replace _mydomain_ from URL with your server domain or IP-address.

This will show you log in page of the application. The application tryes to detect database called **gps2db** from your server and access it using MySQL-user you created on section 3.3. If database cannot be found, the application will create it including neccessary tables and initial data. Once message _All database actions performed succesfully_ is displayed, the process is finnished and the application is ready for use with initial settings. Username is **admin** and password is **admin** by default, if you did not change them on section 3.2.

### 3. Setting up Arduino device and environment ###
#### 3.1 Setting up the device ####
During development we used Sparkfun ESP8266 Thing Arduino board. In these instructions we assume, that your are using ESP8266 model. Instructions for power up the board, connect it to development-PC and setting up the Arduino IDE environment can be found here: https://learn.sparkfun.com/tutorials/esp8266-thing-hookup-guide/introduction. Follow the steps to connect FTD1 connection and Arduino IDE carefully.

If you use some other manufacturer's Arduino device and encounter problems, seek tutorial for you device

#### 3.2 Install TinyGPS++ and SoftwareSerial libraries using LibraryManager on Arduino IDE ####
* Select Sketch 
* Include Library
* Manage Libraries
* Type TinyGPS++ and click Install when found
* Wait for installation process to complete
* Type for SoftwareSerial and click Install when found
* Close LibraryManager

![readme_pic_2_2.png](https://bitbucket.org/repo/8Egx4g/images/2832156943-readme_pic_2_2.png)

#### 3.3 Find sketch file from repository ####
Make sure you have cloned or downloaded project repository to your computer.
Open sketch file for the device in Arduino IDE. The sketch file can be found from * your_Git_root\.arduino-gps-2-map\device\ThingGps2Map\ThingGps2Map.ino

#### 3.4 Powering the device ####
USB charger for mobile phones can be used to power up the board in development phase. During GPS functional testing USB power bank can be used if the test requires the board to be moved. Power bank should have output of 2.1A, because during development we found out that 1A is not enough.

#### 3.5 GPS-module ####
During the development project used Grove Seeed GPS as locating device. It has only serial interface and the only physical serial interface on the Arduino board is occupied by FTDI for uploading and monitoring the board. ThingGps2Map.ino sketch sets up softwareserial (required also by TinyGPS++ library ) for  communication with the GPS. The sketch sets software serial pins as  RXPin = 12, TXPin = 13. So the GPS should be wired accordingly using breadboard. Note that TX is not needed, since GPS data is only read.  The communication speed is set to 9600. Power and ground cables can be attached to 3V3 and GND pins. Picture below shows GPS cabling used in project.

![readme_pic_2_5.png](https://bitbucket.org/repo/8Egx4g/images/2592794252-readme_pic_2_5.png)

*Black = Ground connected to  GND, Green =Power  connected to 3V3,Yellow = RX connected to pin 12.*

#### 3.6 Monitoring ####
To monitor the Arduino device a proper terminal monitor is needed. The serial monitor included in the Arduino IDE does not work properly.  RealTerm proved to be good choise for monitoring.  Also serial monitor provided by Visual Studio Arduino addon seemed working. The key thing seems to be the ability to set RTS and DTR pins.
RealTerm can be downloaded from SourceForge: https://sourceforge.net/projects/realterm/

##### Instructions for monitoring with RealTerm: #####
* Clear RTS and DTR on Pins tab

![readme_pic_2_6_1.png](https://bitbucket.org/repo/8Egx4g/images/1705017383-readme_pic_2_6_1.png)

* Set Baud Rate to 115200 and select the correct board where FTDI driver is installed

* Press Change-button

![readme_pic_2_6_2.png](https://bitbucket.org/repo/8Egx4g/images/4184345684-readme_pic_2_6_2.png)

* Before uploading the sketch remember to release the port by pressing Open-button

* After the sketch has been uploaded press open-button to monitor again

#### 3.7 Recording debug prints ####
Realterm has also Capture feature to record debug prints from Arduino board for later inspection.
![readme_pic_2_7.png](https://bitbucket.org/repo/8Egx4g/images/314045841-readme_pic_2_7.png)

#### 3.8 WiFi ####
When running ThingGps2Map.ino sketch it tries to connect to any open network. If open network is not available, variables android[] = "AndroidAP" and pwd[]="" can be set to use a password protected network for testing purposes.

#### 3.9 Set Up domain ####
ThingGps2Map.ino sketch sends GPS data to domain http://5kilo.com/gps-2map, port 80. To change this two lines of code needs to be edited in the skecth: 

* const char mapHost[] = "http://5kilo.com/gps-2-map/api/?";

* const int port = 80;
 

### 4. How to use the system ###
#### To use the system simply run it with following steps ####
1. Switch the Arduino device to power source and power it on from switch
2. Open a web browser on your computer or mobile device and type URL http://mydomain/gps-2-map (replace mydomain, with your server's domain name)
3. Log in page is now show - Log in by typing username and password (default: **admin** **admin**) and click **Log in**
4. Map view is now shown and refreshed after every 5 seconds from newewst available location information of the device from database.
5. To switch on Video container, click _Show video_. To switch back, click _Hide vdeo_.
6. To view Settings page (not implemented yet), click on username on top right corner and select _Settings_
7. To log out, click on username on top right corner and select _Log out_

### 5. Limitations ###
* Whole web page on map view is beign refreshed, when checking newest data from database
* Functions to settings page are not implemented yet
* Weak security on the connection between Arduino and server

### 6. Suggestions for future development ###
* Video Container: The live video section is not functional at this moment, as it is not part of the project. We created a container for further implementation.
* Registeration form: System support multiple users, but registeration page to create new users neesds to be implemented, before it can be used
* Map view implementation need improving: Since the map view is using free Google API on viewframe, updating location data to map view requires refreshing whole page view, which is not really nice looking and can be problem with video container. Changin the map view to use some other map API togetger with JSON-format could improve the map view.
* Improved security to connection between Arduino and server: HTTPS-connection between Arduino and server could make data transfer from Arduino to server's API more secure.
