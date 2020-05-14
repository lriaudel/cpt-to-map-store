<?php

function accolade( $el ){

    return '{'.$el.'}';

}


function cpt_to_map_store_group_by_id( $data , $by_column ) {

    $grouped = array();

    foreach( $data as $key => $item ) {

        if( !is_array( $grouped[ $item->ID ] ) ){
            $grouped[ $item->ID ] = array( 'post_title' => $item->post_title, 'guid' => $item->guid, $item->meta_key => $item->meta_value );
        }
        $grouped[ $item->ID ] += [ $item->meta_key => $item->meta_value  ];
        
    }

    return $grouped;

}

function mysql_to_textarea( $html) {
    return str_replace(array("\\r\\n","\\n","\\r"),array("\n","\n","\n"), html_entity_decode( $html ) );
}

?>