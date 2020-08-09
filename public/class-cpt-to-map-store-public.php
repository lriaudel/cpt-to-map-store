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

	/**
	 * @since	1.0.0
	 * @access	private
	 * @var		string		$class_cpt_to_map_store Class cpt_to_map_store
	 */
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
	 * List of all points
	 * 
	 * @since	1.3
	 * @access	public
	 * @var		string		$shortcode_name		Name of the shortcode for the list of all point
	 */
	public static $general_list_shortcode_name = 'list_map_store';

	/**
	 * Open Street Map Tile Serve
	 * 
	 * @since	1.1.0
	 * @access	public
	 * @var		string
	 */
	public $osm_tiles_url = '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

	/**
	 * Default ID of the layer
	 * 
	 * @since	1.2.0
	 * @access	public
	 * @var		string	$layer_id	Id of the div to displaying the map
	 */
	public $default_map_layer_id = 'map_cpt_to_map_store';

	/**
	 * ID of the layer
	 * 
	 * @since	1.2.0
	 * @access	public
	 * @var		string	$layer_id	Id of the div to displaying the map
	 */
	public $map_layer_id;

	/**
	 * ID of the list layer
	 * 
	 * @since	1.2.0
	 * @access	public
	 * @var		string	$layer_id	Id of the div to displaying the map
	 */
	public $list_layer_id = 'list_cpt_to_map_store';

	/**
	 * Post_id's list to display
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		array
	 */
	public $cpt_map_store_settings_list = array();

	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 * @since	1.2.0		Add single map
	 * @param	string		$plugin_name		The name of the plugin.
	 * @param	string		$version    		The version of this plugin.
	 */
	public function __construct( $class_cpt_to_map_store ) {

		$this->map_layer_id = $this->default_map_layer_id;
		$this->class_cpt_to_map_store = $class_cpt_to_map_store;
		$this->plugin_name = $class_cpt_to_map_store->get_plugin_name();
		$this->version = $class_cpt_to_map_store->get_version();

		// Add scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script_and_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script_and_styles' ) );


		add_shortcode( self::$general_shortcode_name, array( $this, 'create_map_store' ) );

		add_shortcode( self::$post_shortcode_name, array( $this, 'create_post_map_store' ) );

		add_shortcode( self::$general_list_shortcode_name, array( $this, 'create_list_map_store' ) );

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
				<?php echo '#'.esc_attr( $this->map_layer_id ); ?> {
					width: <?php echo esc_attr($map_width); ?>;
					height: <?php echo esc_attr($map_height); ?>;
				}
			</style>

		<?php

		return ob_get_contents();

	}

	/**
	 * Load dependances for shortcodes
	 * 
	 * @since	1.3.0
	 * @return	void
	 */
	public function init_shortcode() {

		if ( !class_exists('Cpt_To_Map_Store_Front_Settings') ) {

			require plugin_dir_path( __FILE__ ) . '/partials/class-to-map-store-front-settings.php';
		
		}

		wp_enqueue_script( 'map-store' );
		
		wp_enqueue_style('leaflet');

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
	 * @since	1.2.0		Add single map
	 * @var		int			$id		Post id point to display
	 * @return	string		HTML/JS OSM Map
	 */
	public function create_map_store( $post_id = null ) {

		$request = null;

		$options = Cpt_To_Map_Store::get_option();

		// If unique
		if( is_int( $post_id ) ) {
			$request['id'] = $post_id;
			$this->map_layer_id = $this->default_map_layer_id . "_" . $post_id;
		}
		else {
			$post_id = "";
		}

		ob_start();

		$this->get_custom_css( $options );

		$this->init_shortcode();

		/**
		 * Add the GEOjson Object and other var after scripts
		 */
		$this->add_var_and_feed( $options, $request );

		?>

		<div id="<?php echo $this->map_layer_id; ?>" class="cpt_to_map_store <?php echo ($post_id != "") ? 'unique' : 'general'; ?>"></div>

		<?php

		$map = ob_get_contents();

		ob_end_clean();
		//echo $map;
		return $map;

	} // end create_map_store()


	/**
	 * Add the GEOjson Object and other var
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$request Default $request to Rest-Api
	 * @return	void
	 */
	public function add_var_and_feed( $options, $request = null ) {

		// Get post_id
		$post_id = isset( $request['id'] ) ? $request['id'] : null;

		// Add post_id setting in array
		if( !isset( $this->cpt_map_store_settings_array[ $post_id ] ) ) {
			
			$cpt_map_store_settings = new Cpt_To_Map_Store_Front_Settings( $post_id );

			$default_zoom = ( !empty($options['default_zoom']) ) ? $options['default_zoom'] : '8';

			$cpt_map_store_settings = array(
				/**
				 * Element id for creating the map
				 */
				'map_layer_id'			=> esc_attr( $this->map_layer_id ),

				/**
				 * Element id for creating the list
				 */
				'list_layer_id'			=> esc_attr( $this->list_layer_id ),

				/**
				 * Tile server
				 */
				'osm_tiles_url'		=> $this->get_osm_tiles_url(),

				/**
				 * Default map zoom
				 */
				'defaultZoom'		=> esc_attr( $default_zoom ),

				/**
				 * Markers list
				 */
				'markers'			=> array(),

				/**
				 * Json Feed
				 */
				'json'				=> $this->class_cpt_to_map_store->create_GEO_Object( $request )
			);

			$this->cpt_map_store_settings_array[ $post_id ] = $cpt_map_store_settings;

		}

		// Localize script

			/**
			 * Add var options
			 */
			wp_localize_script( 'map-store', 'cpt_map_store_settings', $this->cpt_map_store_settings_array );

			/**
			 * Add json feed
			 */
			//wp_localize_script( 'map-store', 'json', $this->class_cpt_to_map_store->create_GEO_Object( $request ) );

	}


	/**
	 * 
	 * @since	1.3.0	Add point list
	 * @return	string	HTML/JS OSM Map
	 */
	function create_list_map_store() {

		$this->add_var_and_feed( Cpt_To_Map_Store::get_option(), null );

		?>

		<div id="cpt_to_map_store_list" class="cpt_to_map_store_list"></div>

		<?php

	} // create_list_map_store

}
