/* Author: Chris Nusbaum

*/

var map,
overlayArray = [];

//initialize map
function initialize_map(overlayArray) {

	//set starting point for map
	var latLong = new google.maps.LatLng(41.914541, 12.458496);

	// default map options
	var myOptions = {
		zoom: 2,
		center: latLong,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	};

	//create a new map instance for the #map_canvas element
	map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

} //initialize_map

function overlayAdd(overlayArray) {
	if (overlayArray.length) {
			for(i = 0, ii = overlayArray.length; i < ii ; i++) {
				overlayArray[i].setMap(map);
			} //end for
	}// end if
}// end overlayAdd


function deleteOverlays(overlayArray) {
	if (overlayArray) {
		for (i in overlayArray) {
			overlayArray[i].setMap(null);
		} //end for
	overlayArray.length = 0;
	} //end if
} //end deleteOverlays



$(document).ready(function() {

	$('input').live('click', function() {

		if (overlayArray.length) {
			for (i in overlayArray) {
				//clear all of the overlays
				overlayArray[i].setMap(null);
			} //end for
			//clear out array
			overlayArray.length = 0;
		} //end if

		//loop through each checked element and display map
		$('li input[type=checkbox]:checked').each(function() {
			var countryLink = $(this).attr('value'),
			empireName = $(this).parents('div').attr('id'),
			boundryLayer = new google.maps.KmlLayer('http://www.pixelrex.com/maps/' + empireName + '/' + countryLink + '.kml');
			//adds
			overlayArray.push(boundryLayer);
		
		});// end each


		overlayAdd(overlayArray);

	}); //end listener

}); // end doc ready

//Initialize Default Map
document.body.onload = initialize_map();

