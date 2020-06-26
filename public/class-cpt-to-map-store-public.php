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
	 * @since	1.0.0
	 * @access	private
	 * @var		string		$plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		string		$version    The current version of this plugin.
	 */
	private $version;

	private $class_cpt_to_map_store;

	/**
	 * General map Shortcode name
	 * 
	 * @since	1.0.0
	 * @access	public
	 * @var		string		$shortcode_name		Name of the shortcode for all points
	 */
	public static $general_shortcode_name = 'map_store';

	/**
	 * Point map Shortcode map
	 * 
	 * @since	1.2
	 * @access	public
	 * @var		string		$shortcode_name		Name of the shortcode for one point
	 */
	public static $post_shortcode_name = 'post_map_store';

	/**
	 * Open Street Map Tile Serve
	 * 
	 * @since	1.1.0
	 * @access	public
	 * @var		string
	 */
	public $osm_tiles_url = '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

	/**
	 * ID of the layer
	 */
	public $layer_id = 'map_cpt_to_map_store';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 * @since	1.2.0		Add single map
	 * @param	string		$plugin_name		The name of the plugin.
	 * @param	string		$version    		The version of this plugin.
	 */
	public function __construct( $class_cpt_to_map_store ) {

		$this->class_cpt_to_map_store = $class_cpt_to_map_store;
		$this->plugin_name = $class_cpt_to_map_store->get_plugin_name();
		$this->version = $class_cpt_to_map_store->get_version();

		// Add scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script_and_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script_and_styles' ) );


		add_shortcode( self::$general_shortcode_name, array( $this, 'create_map_store' ) );

		add_shortcode( self::$post_shortcode_name, array( $this, 'create_post_map_store' ) );

	}

	/**
	 * Get var osm_tiles_url
	 * 
	 * @since 	1.1.0
	 * @return	string	The OSM tile server URL
	 */
	public function get_osm_tiles_url() {
		/**
		 * Filter for get local cache of osm_tiles_proxy
		 */
		 return apply_filters( 'osm_tiles_proxy_get_proxy_url', $this->osm_tiles_url );
	}

	/**
	 * Register script and style
	 * 
	 * @since	1.0.0
	 * @since	1.1.0	Add filter for osm_tiles_proxy
	 * @return	void
	 */
	public function register_script_and_style() {

		/**
		 * Leaflet 
		 */
		wp_register_script( 'leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet/leaflet.js', array(), $this->version, true );
		wp_register_style( 'leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet/leaflet.min.css', array(), $this->version, 'all' );

		/**
		 * ESRI Leaflet 
		 */
		wp_register_script( 'esri-leaflet', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet-esri/esri-leaflet.js', array('leaflet'), $this->version, true );
		wp_register_script( 'leaflet-geocoder', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/lib/leaflet-geocoder/esri-leaflet-geocoder.js', array('esri-leaflet'), $this->version, true );

		// JS
		wp_register_script( 'map-store', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/js/public.js', array('leaflet'), $this->version, true );

		// CSS
		wp_register_style( 'map-store', WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/css/public.css', array('leaflet'), $this->version, 'all' );

	}

	public function enqueue_script_and_styles() {
		global $pagenow;
		
		if( $pagenow == 'edit.php' ) {
			wp_enqueue_style( 'leaflet' );
			wp_enqueue_script( 'map-store' );
		}
	}

	/**
	 * Get CSS for the shortcode
	 * 
	 * @since	1.0.0
	 * @var		$options		Plugins options
	 * @return	string			CSS code for map
	 */
	public function get_custom_css( $options ){

		$map_width = ( !empty($options['map-width']) ) ? $options['map-width'] : '100%';
		$map_height = ( !empty($options['map-height']) ) ? $options['map-height'] : '500px';

		ob_start();
		?>

			<style>
				#map_cpt_to_map_store {
					width: <?php echo esc_attr($map_width); ?>;
					height: <?php echo esc_attr($map_height); ?>;
				}
			</style>

		<?php

		return ob_get_contents();

	}

	/**
	 * Create the OSM map of one CPTs
	 * 
	 * @since	1.2.0	Add for single map
	 * @return	string	HTML/JS OSM Map
	 */
	public function create_post_map_store( $atts ){

		if( isset( $atts ) && isset( $atts['id'] ) ) {

			return $this->create_map_store( (int)$atts['id'] );
		}
		// if we don't have the id
		else {

			// get global ID
			global $post ;

			if( $post && isset($post->ID) ) {
				return $this->create_map_store( $post->ID );
			}
			else {
				return __('No ID found', 'cpt-to-map-store');
			}

		}

	} // end create_post_map_store()

	/**
	 * Create the OSM map of the CPTs
	 * 
	 * @since	1.0.0
	 * @since	1.2.0	Add single map
	 * @return	string	HTML/JS OSM Map
	 */
	public function create_map_store( $id = null ) {

		$request = null;

		$div_id = $this->layer_id;

		$options = Cpt_To_Map_Store::get_option();
		$default_zoom = ( !empty($options['default_zoom']) ) ? $options['default_zoom'] : '8';

		// If unique
		if( is_int($id) ) {
			$request['id'] = $id;
		}
		else {
			$id = "";
		}

		ob_start();

		$this->get_custom_css( $options );

		wp_enqueue_script( 'map-store' );
		
		wp_enqueue_style('leaflet');

		$cpt_map_store_settings = array(
			'div_id'			=> esc_attr( $div_id ),
			'osm_tiles_url'		=> $this->get_osm_tiles_url(),
			'defaultZoom'		=> esc_attr( $default_zoom )
		);

		/**
		 * Add default zoom for one marker
		 */
		wp_localize_script( 'map-store', 'cpt_map_store_settings', $cpt_map_store_settings );

		/**
		 * Add the GEOjson Object
		 */
		wp_localize_script( 'map-store', 'json', $this->class_cpt_to_map_store->create_GEO_Object( $request ) );
	
		?>

		<div id="<?php echo $div_id; ?>" class="cpt_to_map_store <?php echo ($id != "") ? 'unique' : 'general'; ?>"></div>

		<?php

		$map = ob_get_contents();

		ob_end_clean();
		//echo $map;
		return $map;

	} // end create_map_store()

}
