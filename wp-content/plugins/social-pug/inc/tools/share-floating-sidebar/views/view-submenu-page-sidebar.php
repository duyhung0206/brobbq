<?php dpsp_admin_header(); ?>

<form method="post" action="options.php">
	<div class="dpsp-page-wrapper dpsp-page-sidebar wrap">
		
		<?php
		 	$dpsp_location_sidebar = get_option( 'dpsp_location_sidebar', 'not_set' );
			settings_fields( 'dpsp_location_sidebar' );
		?>


		<!-- Page Title -->
		<h1 class="dpsp-page-title">
			<?php _e('Configure Sidebar Sharing Buttons', 'social-pug'); ?>

			<input type="hidden" name="dpsp_buttons_location" value="dpsp_location_sidebar" />
			<input type="hidden" name="dpsp_location_sidebar[active]" value="<?php echo ( isset( $dpsp_location_sidebar["active"] ) ? 1 : '' ); ?>" <?php echo ( !isset( $dpsp_location_sidebar["active"] ) ? 'disabled' : '' ); ?> />
		</h1>


		<!-- Networks Selectable and Sortable Panels -->
		<div id="dpsp-social-platforms-wrapper" class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Social Networks', 'social-pug' ); ?><a id="dpsp-select-networks" class="add-new-h2" href="#">Select networks</a></h3>

			<?php 
				$available_networks = dpsp_get_networks();
				echo dpsp_output_selectable_networks( $available_networks, ( ! empty( $dpsp_location_sidebar['networks'] ) ? $dpsp_location_sidebar['networks'] : array() ) ); 
			?>

			<div id="dpsp-sortable-networks-empty" <?php echo ( empty( $dpsp_location_sidebar['networks'] ) ? 'class="active" style="display: block;"' : 'style="display: none;"' ); ?>>
				<p><?php _e( 'Select which social networks to display', 'social-pug' ); ?></p>
			</div>

			<?php echo dpsp_output_sortable_networks( ( ! empty( $dpsp_location_sidebar['networks'] ) ? $dpsp_location_sidebar['networks'] : array() ), 'dpsp_location_sidebar' ); ?>

		</div>


		<!-- Button Style Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Button Style', 'social-pug' ); ?></h3>

			<?php $settings = dpsp_get_back_end_display_option('dpsp_location_sidebar'); ?>

			<input type="radio" id="dpsp-settings-button-style-input-1" name="dpsp_location_sidebar[button_style]" value="1" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_sidebar['button_style'] ) && $dpsp_location_sidebar['button_style'] == 1 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-1" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-1 dpsp-has-button-background dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings, 'share', 'sidebar' ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-5" name="dpsp_location_sidebar[button_style]" value="5" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_sidebar['button_style'] ) && $dpsp_location_sidebar['button_style'] == 5 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-5" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-5 dpsp-column-1 dpsp-button-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings, 'share', 'sidebar' ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-8" name="dpsp_location_sidebar[button_style]" value="8" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_sidebar['button_style'] ) && $dpsp_location_sidebar['button_style'] == 8 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-8" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-8 dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings, 'share', 'sidebar' ); ?>
				</div>
			</label>

		</div>


		<!-- General Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sidebar[display][shape]', $dpsp_location_sidebar['display']['shape'], __( 'Button shape', 'social-pug' ), array( 'rectangular' => __( 'Rectangular', 'social-pug' ), 'rounded' => __( 'Rounded', 'social-pug' ), 'circle' => __( 'Circle', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sidebar[display][size]', ( isset($dpsp_location_sidebar['display']['size']) ? $dpsp_location_sidebar['display']['size'] : '' ), __( 'Button size', 'social-pug' ), array( 'small' => __( 'Small', 'social-pug' ), 'medium' => __( 'Medium', 'social-pug' ), 'large' => __( 'Large', 'social-pug' ) ) ); ?>
			
			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][icon_animation]', ( isset( $dpsp_location_sidebar['display']['icon_animation']) ? $dpsp_location_sidebar['display']['icon_animation'] : '' ), __( 'Show icon animation', 'social-pug' ), array('yes'), __( 'Will animate the social media icon when the user hovers over the button.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sidebar[display][position]', $dpsp_location_sidebar['display']['position'], __( 'Buttons position', 'social-pug' ), array( 'left' => __( 'Left', 'social-pug' ), 'right' => __( 'Right', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][show_labels]', ( isset( $dpsp_location_sidebar['display']['show_labels']) ? $dpsp_location_sidebar['display']['show_labels'] : '' ), __( 'Show button labels', 'social-pug' ), array('yes') ); ?>
			
			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][spacing]', ( isset( $dpsp_location_sidebar['display']['spacing']) ? $dpsp_location_sidebar['display']['spacing'] : '' ), __( 'Button spacing', 'social-pug' ), array('yes'), __( 'Adds bottom spacing for each button.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sidebar[display][intro_animation]', ( isset( $dpsp_location_sidebar['display']['intro_animation']) ? $dpsp_location_sidebar['display']['intro_animation'] : '' ), __( 'Intro Animation', 'social-pug' ), array( '-1' => __( 'No Animation', 'social-pug' ), '1' => __( 'Fade In', 'social-pug' ), '2' => __( 'Slide In', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][show_after_scrolling]', ( isset( $dpsp_location_sidebar['display']['show_after_scrolling']) ? $dpsp_location_sidebar['display']['show_after_scrolling'] : '' ), __( 'Show after user scrolls', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sidebar[display][scroll_distance]', ( isset( $dpsp_location_sidebar['display']['scroll_distance']) ? $dpsp_location_sidebar['display']['scroll_distance'] : '' ), __( 'Scroll distance (%)', 'social-pug' ), '30', __( 'The distance in percentage (%) of the total page height the user has to scroll before the buttons will appear.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][show_mobile]', ( isset( $dpsp_location_sidebar['display']['show_mobile']) ? $dpsp_location_sidebar['display']['show_mobile'] : '' ), __( 'Show on mobile', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sidebar[display][screen_size]', ( isset( $dpsp_location_sidebar['display']['screen_size']) ? $dpsp_location_sidebar['display']['screen_size'] : '' ), __( 'Mobile screen width (pixels)', 'social-pug' ), '', __( 'For screen widths smaller than this value ( in pixels ) the buttons will be displayed on screen if the show on mobile option is checked.', 'social-pug' ) ); ?>

		</div>


		<!-- Share Counts -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Share Counts', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][show_count]', ( isset( $dpsp_location_sidebar['display']['show_count']) ? $dpsp_location_sidebar['display']['show_count'] : '' ), __( 'Show share count', 'social-pug' ), array('yes'), __( 'Display the share count for each social network.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][show_count_total]', ( isset( $dpsp_location_sidebar['display']['show_count_total']) ? $dpsp_location_sidebar['display']['show_count_total'] : '' ), __( 'Show total share count', 'social-pug' ), array('yes'), __( 'Display the share count for all social networks.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sidebar[display][total_count_position]', ( isset( $dpsp_location_sidebar['display']['total_count_position'] ) ? $dpsp_location_sidebar['display']['total_count_position'] : '' ), __( 'Total count position', 'social-pug' ), array( 'before' => __( 'Before Buttons', 'social-pug' ), 'after' => __( 'After Buttons', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[display][count_round]', ( isset( $dpsp_location_sidebar['display']['count_round']) ? $dpsp_location_sidebar['display']['count_round'] : '' ), __( 'Share count round', 'social-pug' ), array('yes'), __( 'If the share count for each network is bigger than 1000 it will be rounded to one decimal ( eg. 1267 will show as 1.2k ). Applies to Total Share Counts as well.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sidebar[display][minimum_count]', ( isset( $dpsp_location_sidebar['display']['minimum_count']) ? $dpsp_location_sidebar['display']['minimum_count'] : '' ), __( 'Minimum share count', 'social-pug' ), '', __( 'Display share counts only if the total share count is higher than this value.', 'social-pug' ) ); ?>

		</div>
		

		<!-- Custom Colors Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Custom Colors', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_sidebar[display][custom_color]', ( isset( $dpsp_location_sidebar['display']['custom_color']) ? $dpsp_location_sidebar['display']['custom_color'] : '' ), __( 'Buttons color', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_sidebar[display][custom_hover_color]', ( isset( $dpsp_location_sidebar['display']['custom_hover_color']) ? $dpsp_location_sidebar['display']['custom_hover_color'] : '' ), __( 'Buttons hover color', 'social-pug' ), '' ); ?>

		</div>


		<!-- Post Type Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Post Type Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sidebar[post_type_display][]', ( isset( $dpsp_location_sidebar['post_type_display']) ? $dpsp_location_sidebar['post_type_display'] : array() ), '', dpsp_get_post_types() ); ?>
		</div>


		<!-- Save Changes Button -->
		<input type="hidden" name="action" value="update" />
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
	
	</div>
</form>