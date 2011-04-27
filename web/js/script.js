/* Author: Chris Nusbaum

*/

//Initialize Default Map
document.body.onload = initialize_map();

$(document).ready(function() {
	
	$('li li a').live('click', loadTime);
	
});

var map;

//console.log(map);

function initialize_map() {

	var latLong = new google.maps.LatLng(41.914541, 12.458496);
		
	// default map options
	var myOptions = {
		zoom: 7,
		center: latLong,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};
	
	//create a new map instance for the #map_canvas element
	map = new google.maps.Map(document.getElementById("map_canvas"),
		myOptions);
}

function loadTime() { 
	
	var countryLink = $(this).attr('title'), 
	civilizationDirectory = $(this).parent('#navList li').attr('id'),
	ctaLayer = new google.maps.KmlLayer('http://www.pixelrex.com/maps/' + civilizationDirectory + '/' + countryLink + '.kml');
	
	ctaLayer.setMap(map);
	
	console.log(civilizationDirectory);

}

















