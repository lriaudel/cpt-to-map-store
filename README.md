# Custom Post Type to Map Store
An another Store Locator on WordPress but with OpenStreetMap &amp; Leaflet and Meta Fields

WordPress repository: (https://wordpress.org/plugins/cpt-to-map-store/)

-----------------------

**The plugin allows to :**

1. Generate a [GEOJson](https://en.wikipedia.org/wiki/GeoJSON) feed from coordinates recorded in the sample posts.
2. Display this feed on a map with a shortcode.

## Fonctionality

- Mapping from a Post Type to generate a GeoJson feed of all posts.
- Exposure this Geojson feeds on the WordPress Rest-API
- Possibility to make a template for the map markers popup
- Coordinate reading compatibility for :
    - a text field
    - an ACF Google Map field
    - an ACF field for the [ACF OpenStreetMap Field](https://wordpress.org/plugins/acf-openstreetmap-field/) extension
- Displaying a map by shortcode of all points.
- Open Street Map put in cache

## Hook

* The marker content
You can customize more finely the marker popup content.

Example:
```
function bindPopup_content_filter( $bindPopup_content, $post_id ) {
	return "Yeahhhhh";
}

add_filter( 'cpt_to_map_store_bindPopup_content', 'bindPopup_content_filter', 10, 2 );
```

## Cache
This plugin works with [Tiles Proxy for OpenStreetMap](https://wordpress.org/plugins/osm-tiles-proxy/) for put in cache the map tiles.

## Screenshot

### First page admin
Admin > Settings > CPT to Map Store
![Screenshot admin 1](https://raw.githubusercontent.com/lriaudel/cpt-to-map-store/master/.wordpress.org/screenshot-1.png "Screeshot admin 1")

### Second page admin

Admin > Settings > CPT to Map Store > Map

![Screenshot admin 2](https://raw.githubusercontent.com/lriaudel/cpt-to-map-store/master/.wordpress.org/screenshot-2.png "Screeshot admin 2")

### Rest-API
![GeoJson](https://raw.githubusercontent.com/lriaudel/cpt-to-map-store/master/.wordpress.org/screenshot-3.png "GeoJson")

### Map
![Map](https://raw.githubusercontent.com/lriaudel/cpt-to-map-store/master/.wordpress.org/screenshot-4.png "Map")