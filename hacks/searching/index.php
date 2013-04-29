<?php


?><!DOCTYPE html>
<html>
  <head>
    <title>BlueLightCamp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.css" />
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.5/leaflet.ie.css" />
	<![endif]-->
    <link href="css/custom.css" rel="stylesheet" media="screen">
    <link rel='stylesheet' href='css/popbox.css' type='text/css' media='screen' charset='utf-8'>
  </head>
  <body>
  	<div id="wrapper">
  		<div id="loco"></div>
  		<div id="map"></div>

		
				<div class='popbox'>
		  <a class='open' href='#'><img src='plus.png' style='width:14px;position:relative;'> Click Here!</a>
		  <div class='collapse'>
		    <div class='box'>
		      <div class='arrow'></div>
		      <div class='arrow-border'></div>
		        <form action="submit_location.php" method="post" id="subForm">
			  <p><small>Please type your message to the Searcher</small></p>
			  <div class="input">
			    <input type="text" name="searcherid" id="idnumber" placeholder="Searcher ID will be in a hidden field here" />
			  </div>
			  <div class="input">
			    <textarea name="chat" id="Message" placeholder="Comments"></textarea>
			  </div>
			  <input type="submit" value="Send" /> <a href="#" class="close">Cancel</a>
			</form>
		      </div>
		    </div>
		  </div>
		
		
		
		
		
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="http://cdn.leafletjs.com/leaflet-0.5/leaflet.js"></script>
	<!--script type="text/javascript" src="http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=DB6BA21C2386B465E0405F0ACA6034A7"></script-->
	<script src="js/custom.js"></script>
	<script src="js/popbox.js"></script>
  </body>
</html>