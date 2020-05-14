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
?>
<html>
<head>

</head>
<body>
<?php echo do_shortcode('[map_store]'); ?>
<?php wp_footer(); ?>
</body>
</html>