<?php dpsp_admin_header(); ?>

<form method="post" action="options.php">
	<div class="dpsp-page-wrapper dpsp-page-sticky-bar wrap">

		<?php
		 	$dpsp_location_sticky_bar = get_option( 'dpsp_location_sticky_bar', 'not_set' );
			settings_fields( 'dpsp_location_sticky_bar' );
		?>


		<!-- Page Title -->
		<h1 class="dpsp-page-title">
			<?php _e( 'Configure Sticky Bar Sharing Buttons', 'social-pug' ); ?>

			<input type="hidden" name="dpsp_buttons_location" value="dpsp_location_sticky_bar" />
			<input type="hidden" name="dpsp_location_sticky_bar[active]" value="<?php echo ( isset( $dpsp_location_sticky_bar["active"] ) ? 1 : '' ); ?>" <?php echo ( ! isset( $dpsp_location_sticky_bar["active"] ) ? 'disabled' : '' ); ?> />
		</h1>


		<!-- Networks Selectable and Sortable Panels -->
		<div id="dpsp-social-platforms-wrapper" class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Social Networks', 'social-pug' ); ?><a id="dpsp-select-networks" class="add-new-h2" href="#">Select networks</a></h3>

			<?php 
				$available_networks = dpsp_get_networks();
				echo dpsp_output_selectable_networks( $available_networks, ( ! empty( $dpsp_location_sticky_bar['networks'] ) ? $dpsp_location_sticky_bar['networks'] : array() ) ); 
			?>

			<div id="dpsp-sortable-networks-empty" <?php echo ( empty( $dpsp_location_sticky_bar['networks'] ) ? 'class="active" style="display: block;"' : 'style="display: none;"' ); ?>>
				<p><?php _e( 'Select which social networks to display', 'social-pug' ); ?></p>
			</div>

			<?php echo dpsp_output_sortable_networks( ( ! empty( $dpsp_location_sticky_bar['networks'] ) ? $dpsp_location_sticky_bar['networks'] : array() ), 'dpsp_location_sticky_bar' ); ?>

		</div>


		<!-- General Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sticky_bar[display][shape]', ( isset( $dpsp_location_sticky_bar['display']['shape'] ) ? $dpsp_location_sticky_bar['display']['shape'] : '' ), __( 'Button shape', 'social-pug' ), array( 'rectangular' => __( 'Rectangular', 'social-pug' ), 'rounded' => __( 'Rounded', 'social-pug' ), 'circle' => __( 'Circle', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[display][icon_animation]', ( isset( $dpsp_location_sticky_bar['display']['icon_animation']) ? $dpsp_location_sticky_bar['display']['icon_animation'] : '' ), __( 'Show icon animation', 'social-pug' ), array('yes'), __( 'Will animate the social media icon when the user hovers over the button.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sticky_bar[display][show_on_device]', ( isset( $dpsp_location_sticky_bar['display']['show_on_device'] ) ? $dpsp_location_sticky_bar['display']['show_on_device'] : '' ), __( 'Show on device', 'social-pug' ), array( 'mobile' => __( 'Mobile Only', 'social-pug' ), 'desktop' => __( 'Desktop Only', 'social-pug' ), 'all' => __( 'All Devices', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sticky_bar[display][screen_size]', ( isset( $dpsp_location_sticky_bar['display']['screen_size']) ? $dpsp_location_sticky_bar['display']['screen_size'] : '' ), __( 'Mobile screen width (pixels)', 'social-pug' ), '', __( 'For screen widths smaller than this value ( in pixels ) the Mobile Sticky will be displayed on screen.', 'social-pug' ) ); ?>
			
			<?php dpsp_settings_field( 'select', 'dpsp_location_sticky_bar[display][intro_animation]', ( isset( $dpsp_location_sticky_bar['display']['intro_animation']) ? $dpsp_location_sticky_bar['display']['intro_animation'] : '' ), __( 'Intro Animation', 'social-pug' ), array( '-1' => __( 'No Animation', 'social-pug' ), '1' => __( 'Fade In', 'social-pug' ), '2' => __( 'Slide In', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[display][show_after_scrolling]', ( isset( $dpsp_location_sticky_bar['display']['show_after_scrolling']) ? $dpsp_location_sticky_bar['display']['show_after_scrolling'] : '' ), __( 'Show after user scrolls', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sticky_bar[display][scroll_distance]', ( isset( $dpsp_location_sticky_bar['display']['scroll_distance']) ? $dpsp_location_sticky_bar['display']['scroll_distance'] : '' ), __( 'Scroll distance (%)', 'social-pug' ), '30', __( 'The distance in percentage (%) of the total page height the user has to scroll before the buttons will appear.', 'social-pug' ) ); ?>

		</div>


		<!-- Share Counts -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Share Counts', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[display][show_count]', ( isset( $dpsp_location_sticky_bar['display']['show_count']) ? $dpsp_location_sticky_bar['display']['show_count'] : '' ), __( 'Show share count', 'social-pug' ), array('yes'), __( 'Display the share count for each social network.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[display][show_count_total]', ( isset( $dpsp_location_sticky_bar['display']['show_count_total']) ? $dpsp_location_sticky_bar['display']['show_count_total'] : '' ), __( 'Show total share count', 'social-pug' ), array('yes'), __( 'Display the share count for all social networks. Is available only when the buttons are displayed on a desktop.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_sticky_bar[display][total_count_position]', ( isset( $dpsp_location_sticky_bar['display']['total_count_position'] ) ? $dpsp_location_sticky_bar['display']['total_count_position'] : '' ), __( 'Total count position', 'social-pug' ), array( 'before' => __( 'Before Buttons', 'social-pug' ), 'after' => __( 'After Buttons', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[display][count_round]', ( isset( $dpsp_location_sticky_bar['display']['count_round']) ? $dpsp_location_sticky_bar['display']['count_round'] : '' ), __( 'Share count round', 'social-pug' ), array('yes'), __( 'If the share count for each network is bigger than 1000 it will be rounded to one decimal ( eg. 1267 will show as 1.2k ).', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_sticky_bar[display][minimum_count]', ( isset( $dpsp_location_sticky_bar['display']['minimum_count']) ? $dpsp_location_sticky_bar['display']['minimum_count'] : '' ), __( 'Minimum share count', 'social-pug' ), '', __( 'Display share counts only if the total share count is higher than this value.', 'social-pug' ) ); ?>

		</div>


		<!-- Custom Colors Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Custom Colors', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_sticky_bar[display][custom_color]', ( isset( $dpsp_location_sticky_bar['display']['custom_color']) ? $dpsp_location_sticky_bar['display']['custom_color'] : '' ), __( 'Buttons color', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_sticky_bar[display][custom_hover_color]', ( isset( $dpsp_location_sticky_bar['display']['custom_hover_color']) ? $dpsp_location_sticky_bar['display']['custom_hover_color'] : '' ), __( 'Buttons hover color', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_sticky_bar[display][custom_background_color]', ( isset( $dpsp_location_sticky_bar['display']['custom_background_color']) ? $dpsp_location_sticky_bar['display']['custom_background_color'] : '' ), __( 'Bar background color', 'social-pug' ), '' ); ?>

		</div>
		

		<!-- Post Type Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Post Type Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_sticky_bar[post_type_display][]', ( isset( $dpsp_location_sticky_bar['post_type_display']) ? $dpsp_location_sticky_bar['post_type_display'] : array() ), '', dpsp_get_post_types() ); ?>
		</div>


		<!-- Save Changes Button -->
		<input type="hidden" name="action" value="update" />
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>

	</div>
</form>