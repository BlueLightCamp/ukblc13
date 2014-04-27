/* Geo-location */
function findmeifyoucan(){
     if( navigator.geolocation ){
          navigator.geolocation.getCurrentPosition( win, fail );
     }
     else {
          alert("No, you don't can has browzer support");
     }
}
 
function win(youare){
	window.longi=document.value = youare.coords.longitude;
	window.lati=document.value = youare.coords.latitude;
	
	// Show the map
	window.map = L.map('map').setView([lati, longi], 13);
	L.tileLayer('http://{s}.tile.cloudmade.com/{api_key}/997/256/{z}/{x}/{y}.png', {
	    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
	    maxZoom: 21
	}).addTo(map);
	window.marker = L.marker([lati, longi]).addTo(map);
}
function fail(){
	//didntwork
}

$(document).ready(function() {
	findmeifyoucan(); // Get their location
	//getTheCamera(); // Get their camera
 });
