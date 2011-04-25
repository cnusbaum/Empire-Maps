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
		zoom: 5,
		center: latLong,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};
	
	//create a new map instance for the #map_canvas element
	map = new google.maps.Map(document.getElementById("map_canvas"),
		myOptions);
		
	var ctaLayer = new google.maps.KmlLayer('http://www.pixelrex.com/maps/roman/100bc.kml');
	ctaLayer.setMap(map);
}

function loadTime() {
	console.log('test');
	
	var countryLink = $(this).attr('title');

	$.ajaxSetup({
		global: false,
		type: "GET",
		dataType: 'xml',
	 });

	$.ajax({
		url: "/maps/roman/44bc.kml",
		context: '#map_canvas',
		success: function(){
			console.log('success');
			//var ctaLayer = new google.maps.KmlLayer('http://empiremaps.local/maps/roman/44bc.kml');
			//ctaLayer.setMap(map);
	  	}
	});

};


















