<?php
// Root file for API - Handles incoming data from Arduino

// Establish a connection to database
//include("database.php");

    // Check that device corresponding to device_id aka MAC-address is found from database
	$result = mysqli_query($db_connection,"SELECT * FROM data WHERE device_id = '18fe349dbadf'");

	while($row = mysqli_fetch_array($result))
	{
		return $row['device_id'];
		return $row['date_and_time'];
		return $row['latitude'];
		return $row['longitude'];
	}
	mysqli_close($db_connection);
?>

