<?php dpsp_admin_header(); ?>

<form method="post" action="options.php">

	<?php
	 	$dpsp_location_pop_up = get_option( 'dpsp_location_pop_up', 'not_set' );
		settings_fields( 'dpsp_location_pop_up' );
	?>
	
	<div class="dpsp-page-wrapper dpsp-page-pop-up wrap">

		<!-- Page Title -->
		<h1 class="dpsp-page-title">
			<?php _e('Configure Pop-Up Sharing Buttons', 'social-pug'); ?>

			<input type="hidden" name="dpsp_buttons_location" value="dpsp_location_pop_up" />
			<input type="hidden" name="dpsp_location_pop_up[active]" value="<?php echo ( isset( $dpsp_location_pop_up["active"] ) ? 1 : '' ); ?>" <?php echo ( !isset( $dpsp_location_pop_up["active"] ) ? 'disabled' : '' ); ?> />
		</h1>

		<!-- Networks Selectable and Sortable Panels -->
		<div id="dpsp-social-platforms-wrapper" class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Social Networks', 'social-pug' ); ?><a id="dpsp-select-networks" class="add-new-h2" href="#">Select networks</a></h3>

			<?php 
				$available_networks = dpsp_get_networks();
				echo dpsp_output_selectable_networks( $available_networks, ( ! empty( $dpsp_location_pop_up['networks'] ) ? $dpsp_location_pop_up['networks'] : array() ) ); 
			?>

			<div id="dpsp-sortable-networks-empty" <?php echo ( empty( $dpsp_location_pop_up['networks'] ) ? 'class="active" style="display: block;"' : 'style="display: none;"' ); ?>>
				<p><?php _e( 'Select which social networks to display', 'social-pug' ); ?></p>
			</div>

			<?php echo dpsp_output_sortable_networks( ( ! empty( $dpsp_location_pop_up['networks'] ) ? $dpsp_location_pop_up['networks'] : array() ), 'dpsp_location_pop_up' ); ?>

		</div>


		<!-- Button Style Settings -->
		<div class="dpsp-section-button-style dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Button Style', 'social-pug' ); ?></h3>

			<?php $settings = dpsp_get_back_end_display_option('dpsp_location_pop_up'); ?>

			<input type="radio" id="dpsp-settings-button-style-input-1" name="dpsp_location_pop_up[button_style]" value="1" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 1 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-1" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-1 dpsp-has-icon-background dpsp-has-button-background dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-2" name="dpsp_location_pop_up[button_style]" value="2" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 2 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-2" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-2 dpsp-has-icon-background dpsp-has-icon-dark dpsp-has-button-background dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-3" name="dpsp_location_pop_up[button_style]" value="3" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 3 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-3" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-3 dpsp-column-1 dpsp-has-icon-background dpsp-button-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-4" name="dpsp_location_pop_up[button_style]" value="4" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 4 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-4" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-4 dpsp-column-1 dpsp-has-button-background dpsp-icon-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-5" name="dpsp_location_pop_up[button_style]" value="5" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 5 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-5" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-5 dpsp-column-1 dpsp-button-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-6" name="dpsp_location_pop_up[button_style]" value="6" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 6 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-6" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-6 dpsp-column-1 dpsp-has-icon-background <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-7" name="dpsp_location_pop_up[button_style]" value="7" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 7 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-7" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-7 dpsp-column-1 dpsp-icon-hover <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>

			<input type="radio" id="dpsp-settings-button-style-input-8" name="dpsp_location_pop_up[button_style]" value="8" class="dpsp-settings-button-style-input" <?php echo isset( $dpsp_location_pop_up['button_style'] ) && $dpsp_location_pop_up['button_style'] == 8 ? 'checked="checked"' : ''; ?> />
			<label for="dpsp-settings-button-style-input-8" class="dpsp-settings-button-style dpsp-transition">
				<div class="dpsp-button-style-8 dpsp-column-1 <?php echo (isset($settings['display']['shape']) ? 'dpsp-shape-' . $settings['display']['shape'] : '' ); ?>">
					<?php echo dpsp_get_output_network_buttons( $settings ); ?>
				</div>
			</label>
		</div>


		<!-- Buttons Display Settings -->
		<div class="dpsp-icon-shape dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][shape]', ( isset($dpsp_location_pop_up['display']['shape']) ? $dpsp_location_pop_up['display']['shape'] : '' ), __( 'Button shape', 'social-pug' ), array( 'rectangular' => __( 'Rectangular', 'social-pug' ), 'rounded' => __( 'Rounded', 'social-pug' ), 'circle' => __( 'Circle', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][size]', ( isset($dpsp_location_pop_up['display']['size']) ? $dpsp_location_pop_up['display']['size'] : '' ), __( 'Button size', 'social-pug' ), array( 'small' => __( 'Small', 'social-pug' ), 'medium' => __( 'Medium', 'social-pug' ), 'large' => __( 'Large', 'social-pug' ) ) ); ?>
			
			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][icon_animation]', ( isset( $dpsp_location_pop_up['display']['icon_animation']) ? $dpsp_location_pop_up['display']['icon_animation'] : '' ), __( 'Show icon animation', 'social-pug' ), array('yes'), __( 'Will animate the social media icon when the user hovers over the button.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][column_count]', ( isset($dpsp_location_pop_up['display']['column_count']) ? $dpsp_location_pop_up['display']['column_count'] : '' ), __( 'Number of columns', 'social-pug' ), array( 'auto' => __( 'Width Auto', 'social-pug' ), '1' => __( '1 column', 'social-pug' ), '2' => __( '2 columns', 'social-pug' ), '3' => __( '3 columns', 'social-pug' ), '4' => __( '4 columns', 'social-pug' ), '5' => __( '5 columns', 'social-pug' ), '6' => __( '6 columns', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_labels]', ( isset( $dpsp_location_pop_up['display']['show_labels']) ? $dpsp_location_pop_up['display']['show_labels'] : '' ), __( 'Show button labels', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][spacing]', ( isset( $dpsp_location_pop_up['display']['spacing']) ? $dpsp_location_pop_up['display']['spacing'] : '' ), __( 'Button spacing', 'social-pug' ), array('yes') ); ?>

		</div>


		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Share Counts', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_count]', ( isset( $dpsp_location_pop_up['display']['show_count']) ? $dpsp_location_pop_up['display']['show_count'] : '' ), __( 'Show share count', 'social-pug' ), array('yes'), __( 'Display the share count for each social network.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_count_total]', ( isset( $dpsp_location_pop_up['display']['show_count_total']) ? $dpsp_location_pop_up['display']['show_count_total'] : '' ), __( 'Show total share count', 'social-pug' ), array('yes'), __( 'Display the share count for all social networks.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][total_count_position]', ( isset( $dpsp_location_pop_up['display']['total_count_position'] ) ? $dpsp_location_pop_up['display']['total_count_position'] : '' ), __( 'Total count position', 'social-pug' ), array( 'before' => __( 'Before Buttons', 'social-pug' ), 'after' => __( 'After Buttons', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][count_round]', ( isset( $dpsp_location_pop_up['display']['count_round']) ? $dpsp_location_pop_up['display']['count_round'] : '' ), __( 'Share count round', 'social-pug' ), array('yes'), __( 'If the share count for each network is bigger than 1000 it will be rounded to one decimal ( eg. 1267 will show as 1.2k ). Applies to Total Share Counts as well.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_pop_up[display][minimum_count]', ( isset( $dpsp_location_pop_up['display']['minimum_count']) ? $dpsp_location_pop_up['display']['minimum_count'] : '' ), __( 'Minimum share count', 'social-pug' ), '', __( 'Display share counts only if the total share count is higher than this value.', 'social-pug' ) ); ?>

		</div>

		<!-- Custom Colors Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Buttons Custom Colors', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_pop_up[display][custom_color]', ( isset( $dpsp_location_pop_up['display']['custom_color']) ? $dpsp_location_pop_up['display']['custom_color'] : '' ), __( 'Buttons color', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'color-picker', 'dpsp_location_pop_up[display][custom_hover_color]', ( isset( $dpsp_location_pop_up['display']['custom_hover_color']) ? $dpsp_location_pop_up['display']['custom_hover_color'] : '' ), __( 'Buttons hover color', 'social-pug' ), '' ); ?>

		</div>
		

		<!-- Pop-Up Display Settings -->
		<div class="dpsp-icon-shape dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Pop-Up Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'editor', 'title', ( isset( $dpsp_location_pop_up['display']['title']) ? $dpsp_location_pop_up['display']['title'] : 'Sharing is caring' ), __( 'Title', 'social-pug' ), array(), '', array( 'media_buttons' => false, 'teeny' => true, 'editor_height' => 60, 'tinymce' => array( 'toolbar1' => 'italic, underline, alignleft, aligncenter, alignright' ), 'quicktags' => false ) ); ?>

			<?php dpsp_settings_field( 'editor', 'message', ( isset( $dpsp_location_pop_up['display']['message']) ? $dpsp_location_pop_up['display']['message'] : 'Help spread the word. You\'re awesome for doing it!' ), __( 'Message', 'social-pug' ), array(), '', array( 'media_buttons' => false, 'textarea_rows' => 7, 'teeny' => true, 'editor_height' => 150 ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][intro_animation]', ( isset( $dpsp_location_pop_up['display']['intro_animation']) ? $dpsp_location_pop_up['display']['intro_animation'] : '' ), __( 'Intro Animation', 'social-pug' ), array( '-1' => __( 'No Animation', 'social-pug' ), '1' => __( 'Scale', 'social-pug' ), '2' => __( 'Fade In', 'social-pug' ), '3' => __( 'Slide Up', 'social-pug' ), '4' => __( 'Slide Down', 'social-pug' ) ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_after_scrolling]', ( isset( $dpsp_location_pop_up['display']['show_after_scrolling']) ? $dpsp_location_pop_up['display']['show_after_scrolling'] : '' ), __( 'Show after user scrolls', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_pop_up[display][scroll_distance]', ( isset( $dpsp_location_pop_up['display']['scroll_distance']) ? $dpsp_location_pop_up['display']['scroll_distance'] : '30' ), __( 'Scroll distance (%)', 'social-pug' ), array(), __( 'The distance in percentage (%) of the total page height the user has to scroll before the pop-up will appear.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_post_bottom]', ( isset( $dpsp_location_pop_up['display']['show_post_bottom']) ? $dpsp_location_pop_up['display']['show_post_bottom'] : '' ), __( 'Show when at the bottom of the post', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_exit_intent]', ( isset( $dpsp_location_pop_up['display']['show_exit_intent']) ? $dpsp_location_pop_up['display']['show_exit_intent'] : '' ), __( 'Show on exit intent', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_pop_up[display][show_time_delay]', ( isset( $dpsp_location_pop_up['display']['show_time_delay']) ? $dpsp_location_pop_up['display']['show_time_delay'] : '0' ), __( 'Show after "x" seconds', 'social-pug' ), '', __( 'Add a time delay ( in seconds ) until the pop-up should appear.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'select', 'dpsp_location_pop_up[display][session_length]', ( isset( $dpsp_location_pop_up['display']['session_length']) ? $dpsp_location_pop_up['display']['session_length'] : '' ), __( 'Show once every', 'social-pug' ), array( '0' => __( 'Everytime', 'social-pug' ), '1' => __( '1 days', 'social-pug' ), '2' => __( '2 days', 'social-pug' ), '3' => __( '3 days', 'social-pug' ), '4' => __( '4 days', 'social-pug' ), '5' => __( '5 days', 'social-pug' ), '6' => __( '6 days', 'social-pug' ), '7' => __( '7 days', 'social-pug' ) ), __( 'The pop-up will appear to users once every X number of days you select. If you select "Everytime" the pop-up will appear for each page the user visits.', 'social-pug' ) ); ?>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[display][show_mobile]', ( isset( $dpsp_location_pop_up['display']['show_mobile']) ? $dpsp_location_pop_up['display']['show_mobile'] : '' ), __( 'Show on mobile', 'social-pug' ), array('yes') ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_location_pop_up[display][screen_size]', ( isset( $dpsp_location_pop_up['display']['screen_size']) ? $dpsp_location_pop_up['display']['screen_size'] : '' ), __( 'Mobile screen width (pixels)', 'social-pug' ), '', __( 'For screen widths smaller than this value ( in pixels ) the buttons will be displayed on screen if the show on mobile option is checked.', 'social-pug' ) ); ?>
		</div>


		<!-- Post Type Display Settings -->
		<div class="dpsp-section">
			<h3 class="dpsp-section-title"><?php _e( 'Post Type Display Settings', 'social-pug' ); ?></h3>

			<?php dpsp_settings_field( 'checkbox', 'dpsp_location_pop_up[post_type_display][]', ( isset( $dpsp_location_pop_up['post_type_display']) ? $dpsp_location_pop_up['post_type_display'] : array() ), '', dpsp_get_post_types() ); ?>
		</div>


		<!-- Save Changes Button -->
		<input type="hidden" name="action" value="update" />
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
	
	</div>

</form>