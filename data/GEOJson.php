<?php

define('DOING_AJAX', false);
define('WP_ADMIN', false);

// Load WordPress
$bootstrap = 'wp-load.php';
while( !is_file( $bootstrap ) ) {
	if( is_dir( '..' ) ) 
		chdir( '..' );
	else
		die( 'EN: Could not find WordPress! FR : Impossible de trouver WordPress !' );
}
require_once( $bootstrap );

$json = new Cpt_To_Map_Store();
 echo '<pre>';print_r( $json->create_GEO_Object() );echo '</pre>';
//$json->display_GEOJson();

?>