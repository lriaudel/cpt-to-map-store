<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              riaudel.net
 * @since             1.0.0
 * @package           Cpt_To_Map_Store
 *
 * @wordpress-plugin
 * Plugin Name:       CPT to Map Store
 * Plugin URI:        riaudel.net
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ludovic RIAUDEL
 * Author URI:        riaudel.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cpt-to-map-store
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CPT_TO_MAP_STORE_VERSION', '1.0.0' );
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
require plugin_dir_path( __FILE__ ) . 'includes/class-cpt-to-map-store.php';

require plugin_dir_path( __FILE__ ) . 'includes/functions.php';


/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_cpt_to_map_store() {

	$plugin = new Cpt_To_Map_Store();

}
run_cpt_to_map_store();
