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
	
	//overlayArray;

} //initialize_map

function overlayAdd(overlayArray) {
	if (overlayArray.length) {
			for(i = 0, ii = overlayArray.length; i < ii ; i++) {
				overlayArray[i].setMap(map);
			} //end for
	}// end if
}


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

		if (overlayArray) {
			for (i in overlayArray) {
				overlayArray[i].setMap(null);
			} //end for
			overlayArray.length = 0;
		} //end if

		//get all checkboxes
		//var checkBoxList = document.getElementsByTagName('input');
		var checkBoxList = $('li input[type=checkbox]:checked').get();
			console.log(checkBoxList);
		//add a new KML Layer for every check box that is checked
		for (var i = 0, ii = checkBoxList.length ; i < ii; i++ ) {
			//get the value of the clicked element
			var countryLink = checkBoxList[i].value,
			//creates new instance of overlay
			empireName = checkBoxList[i].attr('id'),
			
			boundryLayer = new google.maps.KmlLayer('http://www.pixelrex.com/maps/' + empireName + '/' + countryLink + '.kml');
			//adds
			overlayArray.push(boundryLayer);
		}

		overlayAdd(overlayArray);

	}); //end listener

}); // end doc ready

//Initialize Default Map
document.body.onload = initialize_map();

