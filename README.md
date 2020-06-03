## Device Tracking and Meet History

### Installation

##### 1) Clone the project from repo.

##### 2) Copy .env.example file to .env file

##### 3) Open .env file and fill in the following fields:
        3.1) APP_URL = domain or IP address of host server
        
        3.2) MySQL settings - DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
            
        3.3) Save and exit           
    
##### 4) install docker-compose. 
      	NOTE: make sure docker-compose is installed and can be run without sudo command
      
##### 5) in root directory, run sh script install.sh (sh install.sh, ./install.sh)

## Documentation

DeviceTracker project allows to keep track of devices by entering their coordinates in certain table and add device in check queue, Program will automatically check every occurrence of certain device by chosen configurations. Configurations include minimum meeting time, maximum geographical range, altitude range and depth of analysis (which analyse chain connection between devices).

Before working program should be installed in your sql server. You may find instructions at README.txt file.

There are three types of intersection between devices "meet by checkpoint", "meet by close contact" and "meet by geotracker". In the first case program analyzes checkpoints spreader in research area, those checkpoint can be one of several types (enter, exit, route mark or unidentified), if device was near certain checkpoint whit other device (within time range specified in configuration) it will count as meet between those devices. "Meet by close contact" enter information about device meets and made for chain analysis, which means it will connect devices that meet through other devices, scale of this analysis can be increased infinitely. "Meet by geotracker" enter geographical information about devices, and output already met devices. Program will check is there any devices that must by analysed and will add them to queue for analyse by all three methods to generate meeting tree. All method use 'haversine' formula to calculate distance between coordinates. After that program will check every device that enter search requirement, if depth analysis is more than 1, program will check, by the same method, all devices that were found in first iteration excluding already found devices.

To start working with program should be added new device in meeting tree which require device id, time interval and field 'isBuilded' must be set as false, after that program will go through all trees and check if they are created, if not it will start process of finding intersections by all three methods and finding count of meeting for each method and all method combined, program will repeat this process every 5 minutes (time interval can be changed within the program by altering cron system in kernel.php).

All meetings between devices will be presented at new table, which will contain results for all result found by analysis, if there will be same occurrence of two devices, program will automatically overwrite certain row in table.
