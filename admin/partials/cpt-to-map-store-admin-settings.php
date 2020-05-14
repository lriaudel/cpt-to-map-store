<div class="wrap cpt-to-map-store-admin">

	<?php 
		ob_flush();
		flush();
	?>

	<h1 class="title-settings"><?php _e('Custom Post Type to Map Store', 'cpt-to-map-store'); ?></h1>

	<form class="form" method="post" action="options-general.php?page=<?php echo Cpt_To_Map_Store::$id_setting_page; ?>">

		<!-- TABS -->
		<?php $this->admin_setting_tabs( $current ); ?>

		<div class="fieldset-settings">

			<table class="form-table" id="form-cpt" role="presentation">
				<tbody>
					<tr>
						<th>
							<h3><?php _e('Object', 'cpt-to-map-store'); ?></h3>
						</th>
					</tr>
					<tr>
						<th scope="row">
							<label for="post_type"><?php _e('Post Type', 'cpt-to-map-store'); ?></label>
							<code>(<?php _e('Required', 'cpt-to-map-store'); ?>)</code>
						</th>
						<td>
						
							<?php $cpts =  $this->get_post_types(); ?>
							<!-- if the name is 'post_type' WP is done -->
							<select name="geo_post_type" id="geo_post_type" class="postform">

							<?php foreach( $cpts as $name => $label ) :  ?>
									
								<?php $selected = ( esc_attr($name) == $options['geo_post_type'] ) ? 'selected' : ''; ?>
								<option value="<?php echo esc_attr($name); ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
								
							<?php endforeach; ?>

							</select>

							<p class="description" id="geo_post_type-description">
								<?php _e("Choose a post type. If no fields are proposed in the drop-down menu in the \"Mapping\" zone below, it means that the meta fields have not been created or they are private fields (starting with `_`)." , 'cpt-to-map-store'); ?></p>

						</td>
					</tr>

					<tr>
						<th>
							<h3><?php _e('Mapping', 'cpt-to-map-store'); ?></h3>
						</th>
					</tr>

					<!-- NAME -->
					<tr>
						<th scope="row">
							<label for="name"><?php _e('Point name', 'cpt-to-map-store'); ?></label>
							<code>(<?php _e('Required', 'cpt-to-map-store'); ?>)</code>
						</th>
						<td>
							<select name="name" id="name" disabled>
								<option  value="post_title"><?php _e('Post Title (<em>post_title</em> - default)', 'cpt-to-map-store'); ?></option>
							</select>
							<p class="description" id="name-description"><?php _e("The title's marker is the Post Type's title" , 'cpt-to-map-store'); ?></p>
						</td>
					</tr>

					<?php
					/**
					 * Get meta fields list
					*/
					$fields = $this->get_all_fields_setting();
					?>

					<!-- LATITUDE -->
					<tr>
						<th scope="row">
							<label for="latitude"><?php _e('Latitude', 'cpt-to-map-store'); ?></label>
							<code>(<?php _e('Required', 'cpt-to-map-store'); ?>)</code>
						</th>
						<td>
						
							<select name="latitude" id="latitude">

								<option value=""><?php _e(' -- Select a latitude field -- ', 'cpt-to-map-store'); ?></option>

							<?php foreach( $fields as $field ) : ?>
								<?php $selected = ( esc_attr($field->meta_key) == $options['latitude'] ) ? 'selected' : ''; ?>
								<option <?php echo $selected; ?>
										value="<?php echo esc_attr($field->meta_key); ?>" 
										data-post-type="<?php echo esc_attr($field->post_type); ?>"
										class="post-type-field"
									>
									<?php echo $field->meta_key; ?>
								</option>
							<?php endforeach; ?>

							</select>
							<p class="description" id="latitude-description"><?php _e("The field where the value of the latitude is stored" , 'cpt-to-map-store'); ?></p>
						</td>
					</tr>

					<!-- LONGITUDE -->
					<tr>
						<th scope="row">
							<label for="longitude"><?php _e('Longitude', 'cpt-to-map-store'); ?></label>
							<code>(<?php _e('Required', 'cpt-to-map-store'); ?>)</code>
						</th>
						<td>
						
							<select name="longitude" id="longitude">

								<option value=""><?php _e(' -- Select a longitude field -- ', 'cpt-to-map-store'); ?></option>

							<?php foreach( $fields as $field ) : ?>
								<?php $selected = ( esc_attr($field->meta_key) == $options['longitude'] ) ? 'selected' : ''; ?>
								<option <?php echo $selected; ?>
										value="<?php echo esc_attr($field->meta_key); ?>" 
										data-post-type="<?php echo esc_attr($field->post_type); ?>"
										class="post-type-field"
									>
									<?php echo $field->meta_key; ?></option>
							<?php endforeach; ?>

							</select>
							<p class="description" id="longitude-description"><?php _e("The field where the value of the longitude is stored" , 'cpt-to-map-store'); ?></p>
						</td>
					</tr>

					<!-- Description point -->
					<tr>
						<th scope="row"><label for="description"><?php _e('Description point', 'cpt-to-map-store'); ?></label></th>
						<td>
							<p>
							<select name="description" id="description">

								<option value=""><?php _e(' -- Select a description field -- ', 'cpt-to-map-store'); ?></option>

							<?php foreach( $fields as $field ) : ?>
								<?php $selected = ( esc_attr($field->meta_key) == $options['description'] ) ? 'selected' : ''; ?>
								<option <?php echo $selected; ?>
										value="<?php echo esc_attr($field->meta_key); ?>" 
										data-post-type="<?php echo esc_attr($field->post_type); ?>"
										class="post-type-field"
									>
									<?php echo $field->meta_key; ?></option>
							<?php endforeach; ?>

							</select>
							</p>
							<p class="description" id="description-description"><?php _e("The field is displaying in the popup below the name." , 'cpt-to-map-store'); ?></p>
						</td>
					</tr>

					<!-- Active template -->
					<tr>
						<th scope="row"><label for="description"><?php _e('Active template', 'cpt-to-map-store'); ?></label></th>
						<td>
							<p>
								<fieldset>
									<legend class="screen-reader-text"><span>Membership</span></legend>
									<label for="active-template">
										<?php $checked = ( empty( $options['active-template'] ) ) ? '' : 'checked'; ?>
										<input name="active-template" type="checkbox" id="active-template" <?php echo $checked; ?>>
										<?php _e('Replaced the name and description by the template below', 'cpt-to-map-store'); ?>
									</label>
								</fieldset>
							</p>
						</td>
					</tr>

					<!-- Template -->
					<tr>
						<th scope="row"><label for="template-popup"><?php _e('Template popup', 'cpt-to-map-store'); ?></label></th>
						<td>
							<!-- Textarea -->
							<p>
								<textarea
								id="template-popup"
								name="template-popup"
								cols="80" rows="6"
								class="all-"><?php echo mysql_to_textarea( $options['template-popup'] ); ?></textarea>
							</p>						
							<p class="description" id="template-popup-description"><?php _e("You can customized the popup marker with any fields of the Post Type." , 'cpt-to-map-store'); ?></p>
							
							<!-- Fields list -->
							<p><strong><?php _e("Fields list:" , 'cpt-to-map-store'); ?></strong></p>
							<ul>
								<li class="badgeList"><span class="badge">{post_title}</span></li>
								<li class="badgeList"><span class="badge">{guid}</span></li>
							<?php foreach( $fields as $field ) : ?>
								<li class="badgeList"><span class="badge post-type-field" data-post-type="<?php echo esc_attr($field->post_type); ?>">{<?php echo $field->meta_key ?>}</span></li>
							<?php endforeach; ?>
							<li></li>
							</ul>

							<!-- Exemple -->
							<p><strong><?php _e("Example:" , 'cpt-to-map-store'); ?></strong></p>
							<textarea disabled 
								cols=80" rows="6"><a href="{guid}">
	<table>
		<tr><td><img src=""></td><td>{post_title}</td></tr>
		<tr><td></td><td><?php _e('For more info, click on the link!','cpt-to-map-store'); ?></td></tr>
	</table>
</a></textarea>

						</td>
					</tr>
				</tbody>
			</table>

			<!-- MAP -->
			<table class="form-table" id="form-map" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="map-width"><?php _e('Map Width', 'cpt-to-map-store'); ?></label>
						</th>
						<td>
							<?php $value = ( empty( $options['map-width'] ) ) ? '100%' : $options['map-width']; ?>
							<input id="map-width" name="map-width" type="text" class="" value="<?php echo $value; ?>">

							<p class="description" id="map-width-description"><?php _e("The width of the HTML div." , 'cpt-to-map-store'); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="map-height"><?php _e('Map Height', 'cpt-to-map-store'); ?></label>
						</th>
						<td>
							<?php $value = ( empty( $options['map-height'] ) ) ? '500px' : $options['map-height']; ?>
							<input id="map-height" name="map-height" type="text" value="<?php echo $value; ?>">

							<p class="description" id="map-height-description"><?php _e("The height of the HTML div." , 'cpt-to-map-store'); ?></p>

						</td>
					</tr>					
				</tbody>
			</table>

		</div> <!-- END FIELDSET SETTINGS -->


		<div class="fieldset-info">
			<?php
				/**
				 * Load the setting's forms 
				 */
				include WP_PLUGIN_DIR . '/cpt-to-map-store/admin/partials/cpt-to-map-store-presentation-card.php';
			?>
		</div>


		<?php
			// cette fonction ajoute plusieurs champs cachés au formulaire
			settings_fields( 'cpt-to-map-store-settings-update' );
		?>

		<p class="submit">
			<input name="save-cpt-to-map-store-settings" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save' ); ?>" />
		</p>
	</form>
</div>