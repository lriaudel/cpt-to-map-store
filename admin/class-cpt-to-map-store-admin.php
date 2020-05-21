<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       riaudel.net
 * @since      1.0.0
 *
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/admin
 * @author     Ludovic RIAUDEL <lriaudel@gmail.com>
 */
class Cpt_To_Map_Store_Admin {

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

	private $exclude_post_type = array( 'attachment', 'acf-field', 'acf-field-group' );

	/**
	 * Custom Post Type list
	 * 
	 * @since 	1.0.0
	 * @access 	private
	 * @var		array
	 */
	private $post_types;

	/**
	 * Parameter to seach CPT list
	 * 
	 * @since 	1.0.0
	 * @access 	private
	 * @var		array
	 */
	private $args_search_post_type = array(
									'show_ui' 	=> true,
								);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add un link in settings menu
		add_action('admin_menu', array( $this, 'add_general_settings_link' ) );

		// Add scripts and styles
		//$this->enqueue_scripts();
		//$this->enqueue_styles();
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $page;

		if ( isset( $_GET['page'] ) && $_GET['page'] == Cpt_To_Map_Store::$id_setting_page )
			wp_enqueue_style( $this->plugin_name, WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $page;

		if ( isset( $_GET['page'] ) && $_GET['page'] == Cpt_To_Map_Store::$id_setting_page )
			wp_enqueue_script( $this->plugin_name, WP_PLUGIN_URL.'/'.CPT_TO_MAP_STORE_NAME.'/assets/js/admin.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Add un link in settings menu
	 *
	 * @since    1.0.0
	 */
	public function add_general_settings_link() {

		add_options_page (
			 __( 'CPT to Map Store', 'cpt-to-map-store' ),
			 __( 'CPT to Map Store', 'cpt-to-map-store' ), 
			 'manage_options', 								/* capability */
			 Cpt_To_Map_Store::$id_setting_page,			/* Slug id menu */
			 array( $this, 'cpt_to_map_store_settings'), 	/* function */
			 null );
	}

	/**
	 * Setting page
	 * 
	 * @since	1.0.0
	 * 
	 * help : https://codex.wordpress.org/Validating_Sanitizing_and_Escaping_User_Data
	 */
	public function cpt_to_map_store_settings() {

		$options = array();

		$current = ( isset($_POST['tab']) ) 			? sanitize_text_field( $_POST['tab'] ) : 'cpt';

		if ( $_GET['page'] == Cpt_To_Map_Store::$id_setting_page ) {

			if ( isset($_REQUEST['action']) && 'update' == $_REQUEST['action'] ) {

				//var_dump($_REQUEST);
				$options['geo_post_type'] = 	( isset($_POST['geo_post_type']) ) 		? sanitize_key( $_POST['geo_post_type'] ) : '';
				$options['name'] = 				( isset($_POST['name']) ) 				? sanitize_key( $_POST['name'] ) : '';
				$options['latitude'] =			( isset($_POST['latitude']) ) 			? sanitize_key ( $_POST['latitude'] ) : '';
				$options['longitude'] = 		( isset($_POST['longitude']) ) 			? sanitize_key ( $_POST['longitude'] ) : '';
				$options['description'] = 		( isset($_POST['description']) ) 		? sanitize_key( $_POST['description'] ) : '';
				$options['active-template'] = 	( isset($_POST['active-template']) ) 	? sanitize_key( $_POST['active-template'] ) : '';
				$options['template-popup'] = 	( isset($_POST['template-popup']) ) 	? wp_kses_post( trim($_POST['template-popup']) ) : '';

				//var_dump( $options );
				$options['map-width'] = 		( isset($_POST['map-width']) ) 			? sanitize_text_field( trim($_POST['map-width']) ) : '';
				$options['map-height'] = 		( isset($_POST['map-height']) ) 		? sanitize_text_field( trim($_POST['map-height']) ) : '';

				$res = update_option( Cpt_To_Map_Store::$id_setting_page, $options, false );
				//var_dump($res, $options);

				ob_start();
			?>
			<?php if( $res ) : ?>
			<div class="notice notice-success is-dismissible inline">
				<p>
					<?php _e( 'Settings saved!', 'cpt-to-map-store' );?>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</p>
			</div>
			<?php endif; ?>

			<?php
			 ob_end_flush();
		
			}
			else{
				$options = get_option( Cpt_To_Map_Store::$id_setting_page );
				//var_dump($options);
			}

		}

		/**
		 * Load the setting's forms 
		 */
		include WP_PLUGIN_DIR . '/cpt-to-map-store/admin/partials/cpt-to-map-store-admin-settings.php';

	}


	/**
	 * Retreive sur Post Type to select
	 * 
	 * @since    1.0.0
	 */
	protected function get_post_types() {

		// vars
		$post_types = array();

		if( empty( $this->post_types ) ) {

			// get post type objects
			$objects = get_post_types( array('show_ui' 	=> true ), 'objects' );

			// loop
			foreach( $objects as $i => $object ) {
			
				// bail early if is exclude
				if( in_array($i, $this->exclude_post_type ) ) continue;
				
				// bail early if is builtin (WP) private post type
				// - nav_menu_item, revision, customize_changeset, etc
				if( $object->_builtin && !$object->public ) continue;
				
				// append
				$post_types[$i] = $object->label;
			}

			$this->post_types = $post_types;

		}
		else {
			$post_types = $this->post_types;
		}
		
		// filter
		$post_types = apply_filters( 'cpt_to_map_store_get_post_types', $post_types );
		
		// return
		return $post_types;
	}

	/**
	 * 
	 */
	private function get_all_fields_setting() {

		global $wpdb;

		// CPT Keys list
		$cpts = array_keys( $this->get_post_types() );

		// Array of %s like cpt
		$format_in  = array_fill(0, count($cpts), '%s');

		// Placeholder of string like %s, %s, %s
		$format_in = implode( ", ", $format_in );

		// Query with IN ( %s, %s, %s)
		$query_sql = "
			SELECT
				DISTINCT meta_key, post_type
			FROM
				{$wpdb->prefix}posts AS p
				INNER JOIN {$wpdb->prefix}postmeta AS m
					ON m.post_id = p.id
			WHERE
				p.post_type IN ( ".$format_in." )       
				AND Substr(meta_key, 1, 1) <> '_'
			LIMIT  50  
		";

		$query_sql = $wpdb->prepare( $query_sql , $cpts );
		//var_dump($query_sql);
		$fields = $wpdb->get_results($query_sql, OBJECT_K );

		return $fields;

	}

	/**
	 *  href='options-general.php?page=cpt-to-map-store&tab=<?php echo $tab; ?>'>
	 */
	private function admin_setting_tabs( $current = 'cpt' ) {
		$tabs = array( 'cpt' => __('Custom Post Type', 'cpt-to-map-store') , 'map' => __('Map', 'cpt-to-map-store') );
		?>
		<h2 class="nav-tab-wrapper">
		<?php foreach( $tabs as $tab => $name ) : ?>
			<?php $class = ( $tab == $current ) ? 'nav-tab-active' : ''; ?>
			<a id="<?php echo $tab; ?>"
				class='nav-tab <?php echo $class; ?>'
				href='#'>
					<?php echo $name; ?>
			</a>
		<?php endforeach; ?>
		</h2>
		<?php
	}

}
