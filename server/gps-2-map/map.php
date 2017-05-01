<?php
	include('session.php');
    // Connection to API
	include('api/get_location.php');
?>

<html>
  <head>
    <title>GPS-2-Map</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="5" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css">
    <link rel="stylesheet" href="css/map.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>

  <body>

<!-- ***************** -->
<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="map.php">Arduino GPS-2-Map Tracker Application</a>
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $login_session; ?><span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="settings.php">Settings <img src="img/settings.jpg" ></a></li>
					<li><a href="logout.php">Logout <img src="img/logout.jpg"></a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>
<!-- ************** -->

 <div class="container cusborder">
   <div class="cus-btn"><button onclick="myFunction()" id="btn" class="btn btn-success btn-sx">Show video</button></div>

	<div class="row cus-row">
		<div class="col-md-12">
			<?php
				$date = date_create($row['date_and_time'], new DateTimeZone('Europe/Helsinki'));
				
				echo "<div name='device-name-span' id='device-name-span'><strong>Location of: </strong>" . $row['device_id'] . "</div>";
				echo "<div name='date-and-time-span' id='date-and-time-span'><strong>Date and time: </strong>";
				echo date_format($date, 'd.m.Y H:i:s');
				echo "</div>";
			?>
		</div>
		<div class="col-md-12" id="map">
			<div id="location">
				<iframe	name="map-frame" id="map-frame"
					width="100%"
					height="450"
					frameborder="0" style="border:0"
					src="<?php echo "https://www.google.com/maps/embed/v1/place?key=AIzaSyAHaSunABbzpxzaWB1R_N9IWw6g_NYxElo&q="; echo $row['latitude']; echo ","; echo $row['longitude']; ?>" allowfullscreen>
				</iframe>
			</div>
		</div>



		<div class="col-md-0" id="video">

			<div id="video-container" class="embed-responsive embed-responsive-16by9" style="visibility: hidden">
				<iframe width="560" height="350" src="https://www.youtube.com/embed/RZf5VRt3aZw" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
	</div>
	<div class="row">
		<br>
	</div>
   </div>


<footer class="footer navbar-inverse" style="min-height: 180px; padding-bottom: 0px;">
	<div class="container">
		<p style="padding-top: 25px"><strong><a href="map.php">Arduino GPS-2-Map Tracker Application</a></strong><small class="pull-right">Copyright &#169; 2016 Ari, Risto, Esub & Yurong</small></p>
	</div>
</footer>

</body>
<!--<script type="text/javascript">
       setInterval(refreshIframe2, 5000);
       function refreshIframe2() {
           var frame = document.getElementById("map-frame");
           frame.src = frame.src;
       }
</script>-->
<script>

    function myFunction() {

      var x = document.getElementById('video-container');
        if(x.style.visibility === 'hidden') {
          x.style.visibility = 'visible';
        } else {
          x.style.visibility = 'hidden';
        }

			var change = document.getElementById("btn");
	      if (change.innerHTML == "Show video")
	      {
	        change.innerHTML = "Hide video";
					$('#map').removeClass('col-md-12');
					$('#map').addClass('col-md-8');
					$('#video').removeClass('col-md-0');
					$('#video').addClass('col-md-4');
	      }
	      else {
	            change.innerHTML = "Show video";
							$('#video').removeClass('col-md-4');
							$('#video').addClass('col-md-0');
							$('#map').removeClass('col-md-8');
		          $('#map').addClass('col-md-12');
	                }

    }
</script>
</html>