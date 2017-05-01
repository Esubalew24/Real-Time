<?php
// Root file for API - Handles incoming data from Arduino

// For testing at localhost: 
// http://localhost/gps-2-map/server/gps-2-map/api/?device_id=18fe349dbadf&date_and_time=2016-12-08 17:08:00&latitude=65.059321&longitude=25.466309

// For testing at live:
// http://5kilo.com/gps-2-map/api/?device_id=18fe349dbadf&date_and_time=2016-12-10 14:10:00&latitude=65.059321&longitude=25.466309

// Establish a connection to database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'gps2map');
define('DB_PASSWORD', 'pass123');
define('DB_DATABASE', 'gps2db');
$db_connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

// Extract POST-data to variables
$device_id = mysqli_real_escape_string($db_connection, $_POST['device_id']);
$date_and_time = mysqli_real_escape_string($db_connection, $_POST['date_and_time']);
$latitude = mysqli_real_escape_string($db_connection, $_POST['latitude']);
$longitude = mysqli_real_escape_string($db_connection, $_POST['longitude']);

// Validate URL - If required parameters are found, continue processing
if(isset($device_id) && isset($date_and_time) && isset($latitude) && isset($longitude))
{
    // Check that device corresponding to device_id aka MAC-address is found from database
	$check_device = mysqli_query($db_connection,"SELECT device_id FROM devices WHERE device_id = '$device_id'");

	// If device corresponding to device_id is found, continue processing
	if(mysqli_num_rows($check_device)) 
	{
		// Find out if there is any existing location information in database for given device
		$query1 = mysqli_query($db_connection,"SELECT * FROM data WHERE device_id='$device_id'");
		
		// If yes, update information with given data
		if(mysqli_num_rows($query1))
		{
			$query2 = mysqli_query($db_connection,"UPDATE data SET date_and_time='$date_and_time', latitude='$latitude', longitude='$longitude' WHERE device_id='$device_id'");
		}
		// If not create a new row with given data
		else
		{
			// Insert information to database
			$query3 = mysqli_query($db_connection,"INSERT INTO data (device_id, date_and_time, latitude, longitude) VALUES ('$device_id', '$date_and_time', '$latitude', '$longitude')");
		
			// If query succees, respond with status code 200
			if($query3) 
			{
				header("HTTP/1.1 200 OK");
			}
			// If query fails, respond with status code 400
			else 
			{
				header("HTTP/1.1 400 Bad Request"); 
			}
		}
		
	}
	// If device is not found, respond with status code 401
	else 
	{
		header("HTTP/1.1 401 Unauthorized");
	}
}
// If URL validation fails, respond with status code 404 and display message
else
{
	header("HTTP/1.1 404 Not found");
	echo "<h1>404 Not found</h1>";
	return false;
}	

?>

