/**
 * Decode HTML entities
 * 
 * @source https://stackoverflow.com/questions/1912501/unescape-html-entities-in-javascript#answer-1912522
 */
function htmlDecode( encodedString ) {
	var e = document.createElement('div');
	e.innerHTML = encodedString;
	return e.childNodes[0].nodeValue;
}
  


var map = L.map('map_cpt_to_map_store');
var popup;

L.tileLayer( osm_tiles_url , {
		attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
		minZoom: 1,
		maxZoom: 21,
	}).addTo(map);

var geojsonLayer = L.geoJson(json, {

	onEachFeature: function (feature, layer) {
		var popup;

		if( '' !== feature.properties.bindPopup_content ){
			popup = feature.properties.bindPopup_content;
		}
		else {
			popup = '<strong>' + feature.properties.name + '</strong><br>' + feature.properties.description;
		}
	
		layer.bindPopup( popup );
	}

}).addTo(map);

//marker.bindPopup( getData(locations ,i, 1) );

map.fitBounds(geojsonLayer.getBounds(), {
	padding:[20, 20],
});


/* atester 
https://gis.stackexchange.com/questions/305646/import-local-geojson-file-into-leaflet
var data = fetchJSON('data/us-states.geojson')
			.then(function(data) { return data })
			*/
