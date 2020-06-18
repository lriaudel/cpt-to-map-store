<?php
/**
 * CPT to Map Store
 *
 * @package           Cpt_To_Map_Store
 *
 * @wordpress-plugin
 * Plugin Name:       CPT to Map Store
 * Plugin URI:        https://github.com/lriaudel/cpt-to-map-store
 * Description:       An another Store Locator on WordPress but with OpenStreetMap &amp; Leaflet and Meta Fields
 * Version:           1.1.0
 * Author:            Ludovic RIAUDEL
 * Author URI:        https://riaudel.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cpt-to-map-store
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CPT_TO_MAP_STORE_VERSION', '1.1.0' );
define( 'CPT_TO_MAP_STORE_NAME', 'cpt-to-map-store');
define('CPT_TO_MAP_STORE_SETTING', 'cpt_to_map_store_settings');

register_activation_hook( __FILE__, 'activate_cpt_to_map_store' );
register_deactivation_hook( __FILE__, 'deactivate_cpt_to_map_store' );

function activate_cpt_to_map_store(){}
function deactivate_cpt_to_map_store(){}

/**
 * Load the plugin text domain for translation.
 *
 * @since    1.0.0
 */
function cpt_to_map_store_set_locale() {
	load_plugin_textdomain( CPT_TO_MAP_STORE_NAME, false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'cpt_to_map_store_set_locale' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
if ( !class_exists('Cpt_To_Map_Store') ) {

	require plugin_dir_path( __FILE__ ) . 'includes/class-cpt-to-map-store.php';

	require plugin_dir_path( __FILE__ ) . 'includes/functions.php';

}
else {

	add_action( 'admin_notices', function() {
		$class = 'notice notice-error';
		$message = __( 'Sorry, the slug and class used for the plugin CPT to Map Store is already used! The plugin is not launch.', 'cpt-to-map-store' );
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	} );

}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_cpt_to_map_store() {

	$plugin = new Cpt_To_Map_Store();

}
run_cpt_to_map_store();
