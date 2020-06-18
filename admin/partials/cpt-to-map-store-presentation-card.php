<div class="card">
		<h2 class="title"><?php _e('Objectives', 'cpt-to-map-store'); ?></h2>
		<p><ul><li><?php _e('The first goal is to retrieve the geographic coordinates present in a personalized content type in order to create a json file (<a href="https://en.wikipedia.org/wiki/GeoJSON">GEOJson></a> ; <a href="https://tools.ietf.org/html/rfc7946">RFC 7946</a>) usable by mapping tools such as Open Street Map or Google Map.', 'cpt-to-map-store'); ?><br>
		<?php _e('You can use this feed for your custom map.', 'cpt-to-map-store'); ?></li>
		<li><?php _e('The second goal is to propose an easy display of this data by a simple <a href="https://www.openstreetmap.org/">OSM map</a> (with <a href="https://leafletjs.com/">Leaflet</a>) and the possibility to customized the popup\'s marker rendering.', 'cpt-to-map-store'); ?>
		</li></ul></p>
		<h2 class="title"><?php _e('JSON Feed urls', 'cpt-to-map-store'); ?></h2>
		<p>
			<?php $api_link = get_rest_url(null, '/' . Cpt_To_Map_Store::$api_slug .'/'. Cpt_To_Map_Store::get_option('geo_post_type') . '/' ); ?>
			<a href="<?php echo $api_link; ?>" target="apri-rest-cpt-to-map-store"><?php echo $api_link; ?></a>
		</p>

		<h2 class="title"><?php _e('Map', 'cpt-to-map-store'); ?></h2>
		<p><?php _e('Shortcode : ', 'cpt-to-map-store'); ?><strong><?php echo '[' . Cpt_To_Map_Store_Public::$shortcode_name . ']'; ?></strong></p>

		<?php if( !class_exists ('MOEWE\OSM_Tiles_proxy\Proxy') ) : ?>

		<div class="spotlight">
			<h2 class="title"><?php _e('Putting a cache at OSM!', 'cpt-to-map-store'); ?></h2>
			<p><?php _e('This plugin works very well with the plugin', 'cpt-to-map-store'); ?> <a href="<?php echo admin_url('plugin-install.php?s=MOEWE+Tiles+Proxy+for+OpenStreetMap&tab=search&type=term'); ?>">Tiles Proxy for OpenStreetMap</a> <?php _e('to cache map tiles. It\'s free.', 'cpt-to-map-store'); ?></p>
		</div>

		<?php endif; ?>

		<hr>
		<p>
			<a href="https://github.com/lriaudel/cpt-to-map-store">
				<svg height="24" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="24" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg></a>
			<?php _e('You could help on the repository:', 'cpt-to-map-store'); ?> <br> <a href="https://github.com/lriaudel/cpt-to-map-store">https://github.com/lriaudel/cpt-to-map-store</a>
		</p>

		<hr>
		<p>
			<a href="https://www.paypal.me/lriaudel" class="donate"><?php _e('Donate', 'cpt-to-map-store'); ?></a>
		</p>
		
	</div>