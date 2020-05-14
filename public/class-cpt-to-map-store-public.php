<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       riaudel.net
 * @since      1.0.0
 *
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/public
 * @author     Ludovic RIAUDEL <lriaudel@gmail.com>
 */
class Cpt_To_Map_Store_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $class_cpt_to_map_store;

	public static $shortcode_name = 'map_store';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $class_cpt_to_map_store ) {

		$this->class_cpt_to_map_store = $class_cpt_to_map_store;
		$this->plugin_name = $class_cpt_to_map_store->get_plugin_name();
		$this->version = $class_cpt_to_map_store->get_version();

		$this->register_script_and_style();

		add_shortcode( self::$shortcode_name, array( $this, 'create_map' ) );
	}


	public function register_script_and_style() {

		// JS OSM Leaflet
		wp_register_script( 'leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet/leaflet.js', array(), $this->version, true );
		wp_register_script( 'esri-leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet-esri/esri-leaflet.js', array('leaflet'), $this->version, true );
		wp_register_script( 'leaflet-geocoder', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet-geocoder/esri-leaflet-geocoder.js', array('esri-leaflet'), $this->version, true );

		// CSS OSM Leaflet
		wp_register_style( 'leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet/leaflet.min.css', array(), $this->version, 'all' );

		// JS
		wp_register_script( 'map-store', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/js/public.js', array('leaflet'), $this->version, true );

		// CSS
		wp_register_style( 'map-store', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/css/public.css', array('leaflet'), $this->version, 'all' );

	}

	public function enqueue_script_and_styles() {
		if( $pagenow == 'edit.php' ) {
			wp_enqueue_style( 'map-store' );
			wp_enqueue_script( 'map-store' );
		}
	}

	public function get_custom_css(){

		$options = Cpt_To_Map_Store::get_option();

		//var_dump($options);

		$map_width = ( !empty($options['map-width']) ) ? $options['map-width'] : '100%';
		$map_height = ( !empty($options['map-height']) ) ? $options['map-height'] : '500px';

		ob_start();
		?>

			<style>
				#map_cpt_to_map_store {
					width: <?php echo $map_width; ?>;
					height: <?php echo $map_height; ?>;
				}
			</style>

		<?php

		return ob_get_contents();

	}


	public function create_map() {

		ob_start();

		$custom_map_store = $this->get_custom_css();

		wp_enqueue_script( 'map-store' );

		wp_enqueue_style('map-store');
		wp_add_inline_style( 'custom-map-store', $custom_map_store );

		wp_localize_script( 'map-store', 'json', $this->class_cpt_to_map_store->create_GEO_Object() );
		
		?>

			<div id="map_cpt_to_map_store"></div>

		<?php

		$map = ob_get_contents();

		ob_end_clean();

		return $map;

	} // end create_map()

}
