<?php

/**
 * The file that defines the core plugin class
 *
 * @link       riaudel.net
 * @since      1.0.0
 *
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/includes
 * @author     Ludovic RIAUDEL <lriaudel@gmail.com>
 */
class Cpt_To_Map_Store {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * API slug of the plugin
	 * 
	 * @since 	1.0.0
	 * @access	public
	 * @var 	string
	 */
	public static $api_slug = 'cpt_to_map_store';

	/**
	 * Name of the page and of the settings options in table wp_options
	 * 
	 * @since 	1.0.0
	 * @access 	public
	 * @static 
	 * @var		string	
	 */
	public static $id_setting_page = 'cpt-to-map-store';

	/**
	 * Setting saved in database for this plugin
	 * 
	 * @since    1.0.0
	 * @access   public
	 * @var      array 
	 */
	public static $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->version = CPT_TO_MAP_STORE_VERSION;

		$this->plugin_name = CPT_TO_MAP_STORE_NAME;

		if( is_admin() ) 
			$this->define_admin_hooks();

		if( true || !is_admin() ) // public call in admin
			$this->define_public_hooks();

		$this->register_api_rest_route();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * - Cpt_To_Map_Store_i18n. Defines internationalization functionality.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since   1.0.0
	 * @access	public
	 * @return  string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Return an setting option or all options
	 * 
	 * @since 	1.0.0
	 * @access 	public
	 * @return 	mixed
	 */
	public static function get_option( $name = null ) {
		if( empty( self::$options ) ) {
			self::$options = get_option( self::$id_setting_page );
		}
		if( !empty( $name ) ) {
			if( isset( self::$options[ $name ] ) ) {
				return self::$options[ $name ];
			}
			elseif( $name == 'geo_post_type' ) {
				return 'post';
			}
			return false;
		}
		return self::$options;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_api_rest_route() {

		add_action( 'rest_api_init', function () {
			register_rest_route( self::$api_slug, self::get_option('geo_post_type'), array(
			  'methods' => 'GET',
			  'callback' => array($this, 'create_GEO_Object'),
			) );
		  } );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cpt-to-map-store-admin.php';
		$plugin_admin = new Cpt_To_Map_Store_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cpt-to-map-store-public.php';
		$plugin_public = new Cpt_To_Map_Store_Public( $this );
	}


	/**
	 * Get all post_type data
	 * 
	 * @since	1.0.0
	 * @access	public
	 * @return	object
	 */
	public function get_post_type_data() {

		$args = array(
			'post_type' => self::get_option('geo_post_type'),
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);
		
		$my_query = new WP_Query( $args );
		
		return $my_query->posts;
	}

	/**
	 * Get all metadata of the all post_types selected
	 * 
	 * @since	1.0.0
	 * @access	protected
	 * @return	object
	 */
	protected function get_post_type_metas_data() {

		global $wpdb;

		$query_sql = "
			SELECT
				p.ID,
				p.post_title,
				p.guid,
				meta_key,
				meta_value
			FROM
				{$wpdb->prefix}posts AS p
				INNER JOIN {$wpdb->prefix}postmeta AS m
					ON m.post_id = p.id
			WHERE
				p.post_type = %s
				AND post_status = 'publish'
				AND Substr(meta_key, 1, 1) <> '_'
		";

		$query_sql = $wpdb->prepare( $query_sql , self::get_option('geo_post_type') );
		//var_dump($query_sql);
		$fields = $wpdb->get_results($query_sql, OBJECT );
		//var_dump($fields);
		return $fields;
	}


	/**
	 * Recursive function for find a coord in the meta field
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	mixed
	 */
	private function _get_coord_recursive( $data, $var_name = 'lat' ) {

		// If var is array
		if ( is_array( $data ) ) {
			//var_dump('array');
			if ( array_key_exists($var_name, $data) ) {
				return $data[ $var_name ];
			}
			else {
				// Soucis... si valeur trouvé dans tableau, il faut chercher un tableau a parcourir... donc on retourne false pour le moment
				return false;
			}
		}
		
		// If var is serialized
		if( is_string( $data ) &&  $s = unserialize( $data ) ) {
			//var_dump('serial');
			return $this->_get_coord_recursive( $s , $var_name );
		}

		// If var is json		
		/*if ( $obj = json_decode( $data ) ) {
			var_dump('json');
			return $this->_get_coord_recursive( $obj , $var_name );
		}*/

		// If var is a double, you win
		if ( is_double( (double) $data ) ) {
			return $data;
		}

		return '';

	} // end _get_coord_recursive

	/**
	 * Get the coord in the meta field, by name
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	mixed
	 */
	private function get_coord( $meta , $field_name = 'latitude', $var_name = 'lat' ) {

		$coord = ( isset( $meta[ self::get_option($field_name) ] ) ) ? $meta[ self::get_option($field_name) ] : '';
		return $this->_get_coord_recursive( $coord, $var_name );

	} // end get_coord

	/**
	 * Get latitude 
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	mixed
	 */
	private function get_latitude( $meta ) {

		return $this->get_coord( $meta, 'latitude', 'lat' );

	} // end get_latitude

	/**
	 * Get longitude 
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	mixed
	 */
	private function get_longitude( $meta ) {

		return $this->get_coord( $meta, 'longitude', 'lng' );

	} // end get_longitude

	/**
	 * Get description 
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	string
	 */
	private function get_description( $meta ) {

			return ( isset( $meta[ self::get_option('description') ] ) ) ? $meta[ self::get_option('description') ] : '';

	} // end get_description 

	/**
	 * Return the template completed by the post_type
	 * 
	 * @since	1.0.0
	 * @access	private
	 * @return	string
	 */
	private function bindPopup_content( $metas ) {

			$template = self::get_option('template-popup');

			$models = array_map( 'cpt_to_map_store_accolade', array_keys($metas) );
			//var_dump($models, $metas);

		return cpt_to_map_store_mysql_to_textarea( str_replace($models, $metas, $template ) );
	}


	/**
	 * Return an associative array of all point, before to be transform in GEOJson
	 * 
	 * @since	1.0.0
	 * @access	public
	 * @return	mixed
	 */
	public function create_GEO_Object() {

		$latitude = '';
		$longitude = '';
		$description = '';
		$bindPopup_content = '';

		$active_bindPopup = ( empty( self::get_option('active-template') ) ) ? false : true; 

		$cpts = $this->get_post_type_data();
		$cpt_metas = $this->get_post_type_metas_data();

		$cpt_metas = cpt_to_map_store_group_by_id($cpt_metas, 'ID');

		//var_dump($compagnies);
		$points = array();
		$point = "";

		foreach( $cpts as $cpt ) {

			$latitude = $this->get_latitude( $cpt_metas[ $cpt->ID ] );
			$longitude = $this->get_longitude( $cpt_metas[ $cpt->ID ] );
			$description =  $this->get_description( $cpt_metas[ $cpt->ID ] );

			if ( $active_bindPopup ) {
				$bindPopup_content = $this->bindPopup_content( $cpt_metas[ $cpt->ID ] );

				/**
				 * Filter of the content to display on the marker popup
				 *
				 * @since	1.0.3
				 * @param	string	$bindPopup_content  The content to display in the marker popup
				 * @param	int		$cpt->ID	The post_id of the custom post type used for the map
				 */
				$bindPopup_content = apply_filters( 'cpt_to_map_store_bindPopup_content', $bindPopup_content, $cpt->ID );
			}

			//var_dump($latitude , $longitude);

			$point = array(
				"type" => "Feature",
				"id" => $cpt->ID,
				"geometry" => array(
					"type" => "Point",
					"coordinates" => array( $longitude, $latitude )
				),
				"properties" => array(
					"name" => stripslashes($cpt->post_title), 
					/*"category" => "",*/
					"description" => $description,
					'bindPopup_content' => $bindPopup_content,
				),
				"title" => stripslashes($cpt->post_title) 
			);

			array_push($points, $point);
		}

		$GEO_Object = array(
			"type" => "FeatureCollection",
			"features" => $points
		);
		//var_dump($GEO_Object);
		return $GEO_Object;
	}

	/**
	 * Return an Json Object of points
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return string (GEOJson)
	 */
	public function get_GEOJson() {
		/**
		 * https://stackoverflow.com/questions/45543930/php-json-sometimes-converts-float-numbers-to-integer/45543985
		 */
		return json_encode( $this->create_GEO_Object(), JSON_PRESERVE_ZERO_FRACTION);
	}

	/**
	 * Display a GEOJson like a file with header
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return string (GEOJson with header)
	 */
	public function display_GEOJson(){

		header('Content-Type: application/json');
		echo $this->get_GEOJson();

	}

}
