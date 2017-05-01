<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'gps2map');
define('DB_PASSWORD', 'pass123');
define('DB_DATABASE', 'gps2db');

// Assume that required database already exists, so connect to it
$db_connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);


// If connection failes continues by creating database with required tables
if (!$db_connection)
{
	echo "Error: Failed to connect to database.<br><br>";
	
	echo "Let's try to create database...<br>";
	
	$db_connection2 = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
	$create_database = "CREATE DATABASE gps2db";
	
	// If database if created succesfully, continue by creating tables
	if (mysqli_query($db_connection2, $create_database))
	{
			echo "Database created succesfully.<br>";
			mysqli_close($db_connection2);
			
			echo "Creating table users...<br>";
			$db_connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
			
			$create_users = "CREATE TABLE users (
			id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
			username VARCHAR(20) NOT NULL,
			password VARCHAR(30) NOT NULL,
			email VARCHAR(30)
			)";
			
			// If tables users is created succesfully, continue by creating table devices
			if (mysqli_query($db_connection, $create_users))
			{
				echo "Table users created succesfully.<br>";
				echo "Creating table devices...<br>";
				
				$create_devices = "CREATE TABLE devices (
				id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
				device_id VARCHAR(20) UNIQUE NOT NULL,
				name VARCHAR(30) NOT NULL
				)";
				
				// If table devices is created succesfully, continue by creating table data
				if (mysqli_query($db_connection, $create_devices))
				{
					echo "Table devices created succesfully.<br>";
					echo "Creating table data...<br>";
					
					$create_data = "CREATE TABLE data (
					id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
					device_id VARCHAR(20),
					date_and_time TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					latitude VARCHAR(30),
					longtitude VARCHAR(30)
					)";
					
					// If table data is created succesfully
					if (mysqli_query($db_connection, $create_data))
					{
						echo "Table data created succesfully.<br>";
						echo "All tables created succesfully.<br>";
						echo "Create initial user...<br>";
						
						// Insert initial user admin
						// CHANGE pass123 FOR MORE SECURE PASSWORD
						$insert_admin = "INSERT INTO users (username, password, email) 
						VALUES ('admin', 'admin', 'pass123')";
						
						// if initial user admin is created succesfully, insert initial device
						if (mysqli_query($db_connection, $insert_admin))
						{
							echo "Initial user created succesfully.<br>";
							echo "Set initial device...<br>";
							
							// Insert initial device
							// CHANGE device_id TO CORRECT MAC-ADRESS IF NEEDED
							$insert_device = "INSERT INTO devices (device_id, name)
							VALUES ('18fe349dbadf', 'Test-device')";
							
							if (mysqli_query($db_connection, $insert_device))
							{
								echo "Initial device set succesfully.<br><br>";
								echo "All database actions performed succesfully.<br>";
								echo "Continue...";
								$db_connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
							}
							else
							{
								echo "Error: Setting initial device failed.<br>";
							}
						}
						else
						{
							echo "Error: Failed to create initial user";
						}
					}
					else
					{
						echo "Error: Failed to create table data.";
					}
				}
				else
				{
					echo "Error: Failed to create table devices.";
				}
			}
			else
			{
				echo "Error: Failed to create table users.";
			}
	}
	else
	{
		echo "Error: Failed to create database.";
	}
}
?>