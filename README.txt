=== Custom Post Type to Map Store ===
Contributors: lriaudel, madvic
Donate link: https://www.paypal.me/lriaudel
Tags: map, geojson, openstreetmap, open street map, store locator, locator, store, cpt, custom post type, post type, posttype, json, free
Requires at least: 3.0.1
Tested up to: 5.4
Stable tag: 5.4
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An another Store Locator on WordPress but with OpenStreetMap &amp; Leaflet

== Description ==

The plugin allows to :

1. Generate a [GEOJson](https://en.wikipedia.org/wiki/GeoJSON) feed from coordinates recorded in the sample posts.
2. Display this feed on a map with a shortcode.

## Features

* Mapping from a Post Type to generate a GeoJson feed of all posts.
* Exposure this Geojson feeds on the WordPress Rest-API
* Possibility to make a template for the map markers popup
* Coordinate reading compatibility for :
    * a text field
    * an ACF Google Map field
    * an ACF field for the [ACF OpenStreetMap Fields](https://wordpress.org/plugins/acf-openstreetmap-field/) extension 
* Displaying a map by shortcode of all points.

== Installation ==

This section describes how to install the plugin and get it working.
1. Upload `cpt-to-map-store.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In admin, Settings > CPT to Map Store

== Frequently Asked Questions ==

= What are the requirements? =

* Have a Post Type with Latitide and Longitude meta fields.
* And add a custom fields (https://wordpress.org/support/article/custom-fields/, ACF plugin, Pods or others...)

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png

== Changelog ==

= 1.0.1 =
* Fix enqueue scripts and styles
* Fix isset var
* Add translate terms

= 1.0.0 =
* initial

== Information importante ! ==
Have fun

==