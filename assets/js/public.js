
for ( var post_id in cpt_map_store_settings ) {

item = cpt_map_store_settings[post_id];

	if( item.json && !item.json.errors ) {
		cpt_to_map_store_build_map( item, item.json );
	}
	else {
		document.getElementById( item.div_id ).append( 'CPT to map Store: Error in geojson var. ' );

		if( json.errors ){
			document.getElementById( item.div_id ).append( Object.keys(json.errors)[0] );
		}
	}
}


function cpt_to_map_store_build_map( cpt_map_store_settings, json ) {

	var map = L.map( cpt_map_store_settings.map_layer_id );
	var popup;
	var nbFeature = Object.keys(cpt_map_store_settings.json.features).length;

	L.tileLayer( cpt_map_store_settings.osm_tiles_url , {
		attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
		minZoom: 1,
		maxZoom: 21,
	}).addTo(map);

	/**
	 * Add markers
	 */
	var geojsonLayer = L.geoJson(cpt_map_store_settings.json, {

		pointToLayer: function(geoJsonPoint, latlng){

			/**
			 * We create a default Icon
			 */
			di = new L.Icon.Default();
			di.options.className = "marker-"+geoJsonPoint.id;

			/**
			 * Add the marker in a list before to add to map
			 */
			return cpt_map_store_settings.markers[geoJsonPoint.id] = L.marker(latlng, {
				id: geoJsonPoint.id,
				icon: di
			});
		},

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

	/**
	 * Recadre la carte avec les points
	 * FitBounds only for more 2 markers
	 */
	if( nbFeature > 1) {

		map.fitBounds(geojsonLayer.getBounds(), {
			padding:[20, 20],
		});
	}
	else if( nbFeature == 1) {
		var coord = cpt_map_store_settings.json.features[0].geometry.coordinates;
		map.setView( [ coord[1], coord[0] ], cpt_map_store_settings.defaultZoom ); // inverted

	}

}

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