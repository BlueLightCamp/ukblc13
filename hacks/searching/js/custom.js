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
	var longi;
	longi=document.value = youare.coords.longitude;
	var lati;
	lati=document.value = youare.coords.latitude;
	
	// Show the map
	var map = L.map('map').setView([lati, longi], 13);
	L.tileLayer('http://{s}.tile.cloudmade.com/d1daefa850c149108363da69126c8474/997/256/{z}/{x}/{y}.png', {
	    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
	    maxZoom: 21
	}).addTo(map);
	var marker = L.marker([lati, longi]).addTo(map);
}
function fail(){
	//didntwork
}


/* Give me your camera! */
function hasGetUserMedia() {
  // Note: Opera is unprefixed.
  return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia || navigator.msGetUserMedia);
}

function getTheCamera(){
	if (hasGetUserMedia()) {
		var onFailSoHard = function(e) {
		    console.log('Reeeejected!', e);
		  };

		  // Not showing vendor prefixes.
		  navigator.getUserMedia({video: true, audio: true}, function(localMediaStream) {
		    var video = document.querySelector('video');
		    video.src = window.URL.createObjectURL(localMediaStream);

		    // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
		    // See crbug.com/110938.
		    video.onloadedmetadata = function(e) {
		      // Ready to go. Do some stuff.
		    };
		  }, onFailSoHard);
	} else {
		alert('getUserMedia() is not supported in your browser');
	}
}

$(document).ready(function() {
	findmeifyoucan(); // Get their location
	//getTheCamera(); // Get their camera
 });