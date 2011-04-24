/* Author: Chris Nusbaum

*/

//Initialize Default Map
document.body.onload = initialize_map();

$(document).ready(function() {
	
	$('li a').live('click', function() {
		var countryLink = $(this).html();
		var countryFunction = 'setUp' + countryLink + '()';
		
		countryFunction;
	});
	
})

function initialize_map() {

	//set default map center
	var latlng = new google.maps.LatLng(-34.397, 150.644);
	
	// default map options
	var myOptions = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};
	
	//create a new map instance for the #map_canvas element
	var map = new google.maps.Map(document.getElementById("map_canvas"),
		myOptions);


	// Defining New Marker
	var marker = new google.maps.Marker({
		position: latlng, 
		map: map,
		title:"Hello World!"
	});

	//event listenter
	google.maps.event.addListener(marker, 'click', function() {
	  map.setZoom(10);
	});
	
	
	//Center Map To England
	function setUpEngland() {
	  var darwin = new google.maps.LatLng(-12.461334, 130.841904);
	  map.setCenter(darwin);
	}

	/*
		google.maps.event.addListener(map, 'zoom_changed', function() {
			setTimeout(moveToEngland, 3000);
		});*/


}





















