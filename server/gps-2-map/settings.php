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

<div class="container cusborder" style="min-height: 600px;">
	<br>
	<br>
	<br>
	<div class="col-md-6">
		<table>
        <tr>
          <th><label for="sel1">Select your device:</label></th>
          <th>
            <select class="form-control" id="sel1">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
            </select></th>
        </tr>

        <tr>
          <td><label for="sel2">Video display or hide: </label></td>
          <td>
            <select class="form-control" id="sel2">
              <option>display</option>
              <option>hide</option>
            </select>
          </td>
        </tr>
        </tr>
      </table>

	</div>
</div>

<footer class="footer navbar-inverse" style="height: 100px;">
	<div class="container">
		<p style="padding-top: 25px"><strong><a href="map.php">Arduino GPS-2-Map Tracker Application</a></strong><small class="pull-right">Copyright &#169; 2016 Ari, Risto, Esub & Yurong</small></p>
	</div>
</footer>

</body>
</html>