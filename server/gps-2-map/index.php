<?php
	session_start();
	include("api/database.php");
   
   
   if($_SERVER["REQUEST_METHOD"] == "POST") 
   {
	// Get given username and password from login form
	$given_username = mysqli_real_escape_string($db_connection,$_POST['username']);
	$given_password = mysqli_real_escape_string($db_connection,$_POST['password']); 

	// Check that user matching the given credientials is found from database
	$sql = "SELECT id FROM users WHERE username = '$given_username' and password = '$given_password'";
	$result = mysqli_query($db_connection,$sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	//$active = $row['active'];
    
	$count = mysqli_num_rows($result);
      
    // If result matched $myusername and $mypassword, table row must be 1 row
    if($count == 1)
	{
		// Register given username to session
		$_SESSION['given_username']="given_username";
		$_SESSION['login_user'] = $given_username;
         
		header("location: map.php");
    }
	else
	{
		$error = "Username or password is incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		
		<link rel="icon" href="../../favicon.ico">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

		<!-- Custom styles for this template -->	
		<link href="css/custom.css" rel="stylesheet">

		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="js/ie-emulation-modes-warning.js"></script>
	
		<title>GPS-2-Map - Log in</title>
</head>

<body>
	<h2 class="form-signin-heading">Arduino GPS-2-Map Tracker Application</h2>
    <div class="container">
		<form class="form-signin" method="post" action="">
			<label for="username" class="sr-only">Username</label>
			<input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
			<label for="password" class="sr-only">Password</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
			<!-- Not impelemted yet 
			<div class="checkbox">
				<label>
					<input type="checkbox" value="remember-me"> Remember me
				</label>
			</div>
			-->
			<button class="btn btn-lg btn-success btn-block" id="submit" name="submit"type="submit">Log in</button>
      </form>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
