<?php

/**
 * Defines settings JS object
 *
 * @link       riaudel.net
 * @since      1.3
 * @package    Cpt_To_Map_Store
 * @subpackage Cpt_To_Map_Store/public
 * @author     Ludovic RIAUDEL <lriaudel@gmail.com>
 */

 class Cpt_To_Map_Store_Front_Settings {
	 
	/**
	 * Element id for creating the map
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$map_layer_id		Name of the map layer
	 */
	public $map_layer_id;

	/**
	 * Width
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$width		Layer Width
	 */
	public $width;

	/**
	 * Height
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$height		Layer Height
	 */
	public $height;

	/**
	 * Element id for creating the list
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$list_layer_id		Name of the list layer
	 */
	public $list_layer_id;

	/**
	 * Tile server
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$osm_tiles_url		Tile server url
	 */
	public $osm_tiles_url;

	/**
	 * Default map zoom
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$defaultZoom		Default zoom
	 */
	public $defaultZoom;

	/**
	 * Markers list
	 * 
	 * @since	1.3.0
	 * @access	public
	 * @var		string		$markers		markers list
	 */
	public $markers;

	/**
	 * Constructor
	 * 
	 * @since	1.3.0		
	 * @param	array		$		
	 */
	public function __construct( $post_id ) {

		$options = Cpt_To_Map_Store::get_option();

		$this->map_width = ( !empty($options['map-width']) ) ? $options['map-width'] : '100%';
		$this->map_height = ( !empty($options['map-height']) ) ? $options['map-height'] : '500px';

	}
			
 }

 ?>