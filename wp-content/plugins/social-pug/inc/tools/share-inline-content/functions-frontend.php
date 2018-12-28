<?php

	/*
	 * Function that displays the sharing buttons in the post content
	 *
	 */
	function dpsp_output_front_end_content( $content ) {

		if( ! dpsp_is_tool_active( 'share_content' ) )
			return;

		if( ! dpsp_is_location_displayable( 'content' ) )
			return $content;

		// Get saved settings
		$settings = dpsp_get_location_settings( 'content' );

		// Get the post object
		$post_obj = dpsp_get_current_post();

		if( ! $post_obj )
			return $content;

		// Set output
		$output = '';

		// Classes for the wrapper
		$wrapper_classes = array( 'dpsp-content-wrapper' );
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

		// Output total share counts
		if( $show_total_count )
			$output .= dpsp_get_output_total_share_count( 'content' );

		// Gets the social network buttons
		if( isset( $settings['networks'] ) )
			$output .= dpsp_get_output_network_buttons( $settings, 'share', 'content' );

		
		$output = apply_filters( 'dpsp_output_front_end_content', $output );

		// Wrap output for top and bottom cases
		$output_top 	= '<div id="dpsp-content-top" class="' . $wrapper_classes . '">' . $output . '</div>';
		$output_bottom 	= '<div id="dpsp-content-bottom" class="' . $wrapper_classes . '">' . $output . '</div>';

		// Share text
		if( !empty( $settings['display']['message'] ) ) {

			$share_text = '<p class="dpsp-share-text">' . esc_attr( $settings['display']['message'] ) . '</p>';

			$output_top    = $share_text . $output_top;
			$output_bottom = $share_text . $output_bottom;

		}

		// Concatenate output and content
		if( $settings['display']['position'] == 'top' ) {
			$content = $output_top . $content;
		} elseif( $settings['display']['position'] == 'bottom' ) {
			$content = $content . $output_bottom;
		} else {
			$content = $output_top . $content . $output_bottom;
		}

		return $content;

	}
	add_filter( 'the_content', 'dpsp_output_front_end_content' );
	add_filter( 'woocommerce_short_description', 'dpsp_output_front_end_content' );