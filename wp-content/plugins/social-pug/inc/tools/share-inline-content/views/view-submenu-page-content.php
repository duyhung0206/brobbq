<?php dpsp_admin_header(); ?>

<form method="post" action="options.php">

	<?php
	 	$dpsp_location_content = get_option( 'dpsp_location_content', 'not_set' );
		settings_fields( 'dpsp_location_content' );
	?>
	
	<div class="dpsp-page-wrapper dpsp-page-content wrap">

		<!-- Page Title -->
		<h1 class="dpsp-page-title">
			<?php _e('Configure Content Sharing Buttons', 'social-pug'); ?>

			<input type="hidden" name="dpsp_buttons_location" value="dpsp_location_content" />
			<input type="hidden" name="dpsp_location_content[active]" value="<?php echo ( isset( $dpsp_location_content["active"] ) ? 1 : '' ); ?>" <?php echo ( !isset( $dpsp_location_content["active"] ) ? 'disabled' : '' ); ?> />
		</h1>

		<!-- Networks Selectable and Sortable Panels -->
		<div id="dpsp-social-platforms-wrapper" class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Social Networks', 'social-pug' ); ?><a id="dpsp-select-networks" class="add-new-h2" href="#">Select networks</a></h3>

			<?php
				$available_networks = dpsp_get_networks();
				echo dpsp_output_selectable_networks( $available_networks, ( ! empty( $dpsp_location_content['networks'] ) ? $dpsp_location_content['networks'] : array() ) ); 
			?>

			<div id="dpsp-sortable-networks-empty" <?php echo ( empty( $dpsp_location_content['networks'] ) ? 'class="active" style="display: block;"' : 'style="display: none;"' ); ?>>
				<p><?php _e( 'Select which social networks to display', 'social-pug' ); ?></p>
			</div>

			<?php echo dpsp_output_sortable_networks( ( ! empty( $dpsp_location_content['networks'] ) ? $dpsp_location_content['networks'] : array() ), 'dpsp_location_content' ); ?>

		</div>


		<!-- Button Style Settings -->
		<div class="dpsp-section-button-style dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Button Style', 'social-pug' ); ?></h3>

			<?php $settings = dpsp_get_back_end_display_option('dpsp_location_content'); ?>

			<input type="radio" id="dpsp-settings-button-style-input-1" name="dpsp_location_content[button_style]" value="1" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 1 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-1" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-1 dpsp-has-icon-background dpsp-has-button-background dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-2" name="dpsp_location_content[button_style]" value="2" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 2 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-2" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-2 dpsp-has-icon-background dpsp-has-icon-dark dpsp-has-button-background dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-3" name="dpsp_location_content[button_style]" value="3" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 3 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-3" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-3 dpsp-column-1 dpsp-has-icon-background dpsp-button-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-4" name="dpsp_location_content[button_style]" value="4" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 4 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-4" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-4 dpsp-column-1 dpsp-has-button-background dpsp-icon-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-5" name="dpsp_location_content[button_style]" value="5" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 5 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-5" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-5 dpsp-column-1 dpsp-button-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-6" name="dpsp_location_content[button_style]" value="6" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 6 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-6" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-6 dpsp-column-1 dpsp-has-icon-background <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-7" name="dpsp_location_content[button_style]" value="7" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 7 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-7" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-7 dpsp-column-1 dpsp-icon-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-8" name="dpsp_location_content[button_style]" value="8" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_content['button_style'] ) && $dpsp_location_content['button_style'] == 8 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-8" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-8 dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>
		</div>


		<!-- General Display Settings -->
		<div class="dpsp-icon-shape dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'select', 'dpsp_location_content[display][shape]', ( isset($dpsp_location_content['display']['shape']) ? $dpsp_location_content['display']['shape'] : '' ), __( 'Button shape', 'social-pug' ), array( 'rectangular' => __( 'Rectangular', 'social-pug' ), 'rounded' => __( 'Rounded', 'social-pug' ), 'circle' => __( 'Circle', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_content[display][size]', ( isset($dpsp_location_content['display']['size']) ? $dpsp_location_content['display']['size'] : '' ), __( 'Button size', 'social-pug' ), array( 'small' => __( 'Small', 'social-pug' ), 'medium' => __( 'Medium', 'social-pug' ), 'large' => __( 'Large', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][icon_animation]', ( isset( $dpsp_location_content['display']['icon_animation']) ? $dpsp_location_content['display']['icon_animation'] : '' ), __( 'Show icon animation', 'social-pug' ), array('yes'), __( 'Will animate the social media icon when the user hovers over the button.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_content[display][position]', ( isset($dpsp_location_content['display']['position']) ? $dpsp_location_content['display']['position'] : '' ), __( 'Buttons Position', 'social-pug' ), array( 'top' => __( 'Above Content', 'social-pug' ), 'bottom' => __( 'Below Content', 'social-pug' ), 'both' => __( 'Above and Below', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_content[display][column_count]', ( isset($dpsp_location_content['display']['column_count']) ? $dpsp_location_content['display']['column_count'] : '' ), __( 'Number of columns', 'social-pug' ), array( 'auto' => __( 'Width Auto', 'social-pug' ), '1' => __( '1 column', 'social-pug' ), '2' => __( '2 columns', 'social-pug' ), '3' => __( '3 columns', 'social-pug' ), '4' => __( '4 columns', 'social-pug' ), '5' => __( '5 columns', 'social-pug' ), '6' => __( '6 columns', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_content[display][message]', ( isset( $dpsp_location_content['display']['message']) ? $dpsp_location_content['display']['message'] : 'Sharing is caring!' ), __( 'Share Text', 'social-pug' ), '' ); ?>
			
			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][show_labels]', ( isset( $dpsp_location_content['display']['show_labels']) ? $dpsp_location_content['display']['show_labels'] : '' ), __( 'Show button labels', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][spacing]', ( isset( $dpsp_location_content['display']['spacing']) ? $dpsp_location_content['display']['spacing'] : '' ), __( 'Button spacing', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][show_mobile]', ( isset( $dpsp_location_content['display']['show_mobile']) ? $dpsp_location_content['display']['show_mobile'] : '' ), __( 'Show on mobile', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_content[display][screen_size]', ( isset( $dpsp_location_content['display']['screen_size']) ? $dpsp_location_content['display']['screen_size'] : '' ), __( 'Mobile screen width (pixels)', 'social-pug' ), '', __( 'For screen widths smaller than this value ( in pixels ) the buttons will be displayed on screen if the show on mobile option is checked.', 'social-pug' ) ); ?>
		</div>


		<!-- Share Counts -->
		<div class="dpsp-section">

			<h3 class="dpsp-section-title"><?php _e( 'Buttons Share Counts', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][show_count]', ( isset( $dpsp_location_content['display']['show_count']) ? $dpsp_location_content['display']['show_count'] : '' ), __( 'Show share count', 'social-pug' ), array('yes'), __( 'Display the share count for each social network.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][show_count_total]', ( isset( $dpsp_location_content['display']['show_count_total']) ? $dpsp_location_content['display']['show_count_total'] : '' ), __( 'Show total share count', 'social-pug' ), array('yes'), __( 'Display the share count for all social networks.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_content[display][total_count_position]', ( isset( $dpsp_location_content['display']['total_count_position'] ) ? $dpsp_location_content['display']['total_count_position'] : '' ), __( 'Total count position', 'social-pug' ), array( 'before' => __( 'Before Buttons', 'social-pug' ), 'after' => __( 'After Buttons', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[display][count_round]', ( isset( $dpsp_location_content['display']['count_round']) ? $dpsp_location_content['display']['count_round'] : '' ), __( 'Share count round', 'social-pug' ), array('yes'), __( 'If the share count for each network is bigger than 1000 it will be rounded to one decimal ( eg. 1267 will show as 1.2k ). Applies to Total Share Counts as well.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_content[display][minimum_count]', ( isset( $dpsp_location_content['display']['minimum_count']) ? $dpsp_location_content['display']['minimum_count'] : '' ), __( 'Minimum share count', 'social-pug' ), '', __( 'Display share counts only if the total share count is higher than this value.', 'social-pug' ) ); ?>

		</div>


		<!-- Custom Colors Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Custom Colors', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_content[display][custom_color]', ( isset( $dpsp_location_content['display']['custom_color']) ? $dpsp_location_content['display']['custom_color'] : '' ), __( 'Buttons color', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_content[display][custom_hover_color]', ( isset( $dpsp_location_content['display']['custom_hover_color']) ? $dpsp_location_content['display']['custom_hover_color'] : '' ), __( 'Buttons hover color', 'social-pug' ), '' ); ?>

		</div>


		<!-- Post Type Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Post Type Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_content[post_type_display][]', ( isset( $dpsp_location_content['post_type_display']) ? $dpsp_location_content['post_type_display'] : array() ), '', dpsp_get_post_types() ); ?>
		</div>


		<!-- Save Changes Button -->
		<input type="hidden" name="action" value="update" />
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
	
	</div>

</form>