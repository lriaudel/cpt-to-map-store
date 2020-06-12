<?php

if ( !function_exists('cpt_to_map_store_accolade') ) {

	function cpt_to_map_store_accolade( $el ){

		return '{'.$el.'}';
	
	}

}



if ( !function_exists('cpt_to_map_store_group_by_id') ) {

	function cpt_to_map_store_group_by_id( $data , $by_column ) {
		//var_dump($data );

		$grouped = array();

		foreach( $data as $key => $item ) {

			if( !isset( $grouped[ $item->ID ] ) ){
				$grouped[ $item->ID ] = array( 'post_title' => $item->post_title, 'guid' => $item->guid, $item->meta_key => $item->meta_value );
			}
			$grouped[ $item->ID ] += [ $item->meta_key => $item->meta_value  ];
			
		}

		return $grouped;

	}
}

if ( !function_exists('cpt_to_map_store_mysql_to_textarea') ) {

	function cpt_to_map_store_mysql_to_textarea( $html) {
		return str_replace(array("\\r\\n","\\n","\\r"),array("\n","\n","\n"), html_entity_decode( $html ) );
	}
}
?>