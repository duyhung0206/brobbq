<?php

	/*
	 * Function that displays the pop-up sharing buttons
	 *
	 */
	function dpsp_output_front_end_pop_up() {

		if( !dpsp_is_location_displayable( 'pop_up' ) )
			return;

		$settings = dpsp_get_location_settings('pop_up');

		// Set output
		$output = '';

		// Classes for the wrapper
		$wrapper_classes   = array( 'dpsp-pop-up-buttons-wrapper' );
		$wrapper_classes[] = ( isset( $settings['display']['shape'] ) ? 'dpsp-shape-' . $settings['display']['shape'] : '' );
		$wrapper_classes[] = ( isset( $settings['display']['size'] ) ? 'dpsp-size-' . $settings['display']['size'] : 'dpsp-size-medium' );
		$wrapper_classes[] = ( isset( $settings['display']['spacing'] ) ? 'dpsp-has-spacing' : '' );
		$wrapper_classes[] = ( isset( $settings['display']['show_labels'] ) || isset( $settings['display']['show_count'] ) ? '' : 'dpsp-no-labels' );
		$wrapper_classes[] = ( isset( $settings['display']['show_count'] ) ? 'dpsp-has-buttons-count' : '' );
		$wrapper_classes[] = ( isset( $settings['display']['show_mobile'] ) ? 'dpsp-show-on-mobile' : 'dpsp-hide-on-mobile' );

		// Button total share counts
		$minimum_count 	  = ( ! empty( $settings['display']['minimum_count'] ) ? (int)$settings['display']['minimum_count'] : 0 );
		$show_total_count = ( $minimum_count <= (int)dpsp_get_post_total_share_count() && ! empty( $settings['display']['show_count_total'] ) ? true : false );

		$wrapper_classes[] = ( $show_total_count ? 'dpsp-show-total-share-count' : '' );
		$wrapper_classes[] = ( $show_total_count ? ( ! empty( $settings['display']['total_count_position'] ) ? 'dpsp-show-total-share-count-' . $settings['display']['total_count_position'] : 'dpsp-show-total-share-count-before' ) : '' );

		// Button styles
		$wrapper_classes[] = ( isset( $settings['button_style'] ) ? 'dpsp-button-style-' . $settings['button_style'] : '' );

		$wrapper_classes = implode( ' ', array_filter( $wrapper_classes ) );

		// Set intro animation
		$intro_animation = ( !empty( $settings['display']['intro_animation'] ) && $settings['display']['intro_animation'] != '-1' ? 'dpsp-animation-' . esc_attr( $settings['display']['intro_animation'] ) : 'no-animation' );

		// Set show on mobile class
		$show_mobile	= ( empty( $settings['display']['show_mobile'] ) ? 'dpsp-hide-on-mobile' : '' );

		// Set trigger extra data
		$trigger_data   = array();
		$trigger_data[] = 'data-trigger-scroll="' . ( isset( $settings['display']['show_after_scrolling'] ) ? ( !empty( $settings['display']['scroll_distance'] ) ? (int)str_replace( '%', '', trim( $settings['display']['scroll_distance'] ) ) : 0 ) : 'false' ) . '"';
		$trigger_data[] = 'data-trigger-exit="' . ( !empty( $settings['display']['show_exit_intent'] ) ? 'true' : 'false' ) . '"';
		$trigger_data[] = 'data-trigger-delay="' . ( !empty( $settings['display']['show_time_delay'] ) ? $settings['display']['show_time_delay'] : 'false' ) . '"';
		$trigger_data   = implode( ' ', array_filter( $trigger_data ) );

		// Set session duration if any
		$session_data   = 'data-session="' . ( !empty( $settings['display']['session_length'] ) ? $settings['display']['session_length'] : '0' ) . '"';

		// Start outputting the pop-up
		$output .= '<div id="dpsp-pop-up" class="' . $show_mobile . ' ' . $intro_animation . '" ' . $trigger_data . ' ' . $session_data . '>';

			// Output close button
			$output .= '<span id="dpsp-pop-up-close"></span>';

			$output .= '<div id="dpsp-pop-up-content">';

				// Output the pop-up title
				$output .= ( !empty( $settings['display']['title'] ) ? apply_filters( 'dpsp_output_pop_up_title', '<h2>' . $settings['display']['title'] . '</h2>' ) : '' );

				// Output the pop-up message
				$output .= ( !empty( $settings['display']['message'] ) ? wpautop( $settings['display']['message'] ) : '' );

			$output .= '</div>';

			// Start outputting the share buttons
			$output .= '<div id="dpsp-pop-up-buttons" class="' . $wrapper_classes . '">';

				// Output total share counts
				if( $show_total_count )
					$output .= dpsp_get_output_total_share_count( 'pop_up' );

				// Gets the social network buttons
				if( isset( $settings['networks'] ) ) 
					$output .= dpsp_get_output_network_buttons( $settings, 'share', 'pop_up' );

			$output .= '</div>';

		$output .= '</div>';

		// Add the overlay
		$output .= '<div id="dpsp-pop-up-overlay" class="' . $show_mobile . ' ' . $intro_animation . '"></div>';

		
		echo apply_filters( 'dpsp_output_front_end_pop_up', $output );

	}
	add_action( 'wp_footer', 'dpsp_output_front_end_pop_up' );


	/*
	 * Adds extra mark-up just after the content so we know when to pop the pop-up
	 *
	 */
	function dpsp_output_front_end_pop_up_content_markup( $content ) {

		$pop_up_settings = dpsp_get_location_settings( 'pop_up' );

		if( isset( $pop_up_settings['display']['show_post_bottom'] ) )
			$content .= '<span id="dpsp-post-bottom"></span>';

		return $content;

	}
	add_filter( 'the_content', 'dpsp_output_front_end_pop_up_content_markup' );