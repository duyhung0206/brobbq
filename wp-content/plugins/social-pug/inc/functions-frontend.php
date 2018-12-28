<?php

	/*
	 * Returns the social network buttons
	 * 
	 * @param array $settings 	- the current section settings for the social networks
	 * @param string $location 	- the location where the social networks will be displayed
	 *
	 */
	function dpsp_get_output_network_buttons( $settings, $action = 'share', $location = '', $data = array() ) {

		$buttons = new DPSP_Network_Buttons_Outputter( $settings, $action, $location, $data );
		$output  = $buttons->get_render();

		return $output;

	}


	/*
	 * Returns the HTML for the total share counts of the networks passed
	 * If no networks are passed, the total count for all active networks will be outputed
	 *
	 * @param string $location  - the location of the share buttons
	 * @param array $networks 	- list with all networks we wish to output total for
	 *
	 * @return int
	 *
	 */
	function dpsp_get_output_total_share_count( $location = '', $networks = array() ) {

		$post_obj = dpsp_get_current_post();

		if( ! $post_obj )
			return;

		$total_shares = dpsp_get_post_total_share_count( $post_obj->ID, $networks, $location );

		if( is_null($total_shares) )
			return '';

		// HTML output
		$output = '<div class="dpsp-total-share-wrapper">';
			$output .= '<span class="dpsp-icon-total-share"></span>';
			$output .= '<span class="dpsp-total-share-count">' . apply_filters( 'dpsp_get_output_total_share_count', $total_shares, $location ) . '</span>';
			$output .= '<span>' . apply_filters( 'dpsp_total_share_count_text', __( 'shares', 'social-pug' ) ) . '</span>';
		$output .= '</div>';

		return $output;
	}


	/*
	 * Outputs custom inline CSS needed for certain functionality
	 *
	 */
	function dpsp_output_inline_style() {

		// Styling default
		$output = '';

		/*
		 * Location: Mobile Sticky
		 */
		$dpsp_location_mobile = get_option( 'dpsp_location_mobile' );

		if( !empty( $dpsp_location_mobile['active'] ) ) {
			$screen_size = (int)$dpsp_location_mobile['display']['screen_size'];

			$output .= '
				@media screen and ( min-width : ' . $screen_size . 'px ) {
					#dpsp-mobile-sticky.opened { display: none; }
				}
			';
		}


		/**
		 * Handle locations
		 *
		 */
		$locations = dpsp_get_network_locations();

		foreach( $locations as $location ) {

			$location_settings = dpsp_get_location_settings( $location );

			// Jump to next one if location is not active
			if( empty( $location_settings['active'] ) )
				continue;

			/**
			 * Mobile display
			 *
			 */
			switch( $location ) {

				case 'sidebar':
					$tool_html_selector = '#dpsp-floating-sidebar';
					break;

				case 'content':
					$tool_html_selector = '.dpsp-content-wrapper';
					break;

				case 'pop_up':
					$tool_html_selector = '#dpsp-pop';
					break;

				default:
					$tool_html_selector = '';
					break;

			}

			if( ! empty( $tool_html_selector ) && empty( $location_settings['display']['show_mobile'] ) ) {

				$mobile_screen_width = ( ! empty( $location_settings['display']['screen_size'] ) ? (int)$location_settings['display']['screen_size'] : 720 );

				$output .= '
					@media screen and ( max-width : ' . $mobile_screen_width . 'px ) {
						' . $tool_html_selector . '.dpsp-hide-on-mobile { display: none !important; }
					}
				';

			}


			/**
			 * Custom colors
			 *
			 */
			$color 		 = !empty( $location_settings['display']['custom_color'] ) ? $location_settings['display']['custom_color'] : false;
			$hover_color = !empty( $location_settings['display']['custom_hover_color'] ) ? $location_settings['display']['custom_hover_color'] : false;

			// Have clases with normal line
			$location = str_replace( '_', '-', $location );

			// Handle sticky bar background
			if( ! empty( $location_settings['display']['custom_background_color'] ) )
				$output .= '#dpsp-sticky-bar-wrapper { background: ' . $location_settings['display']['custom_background_color'] . '; }';

			// Handle button styles
			switch ( $location_settings['button_style'] ) {

				// Button style 1
				case 1:

					if( $location == 'sidebar' ) {

						if( $color )
							$output .= '
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
									background: ' . $color . ';
									border-color: ' . $color . ';
								}
							';

						if( $hover_color )
							$output .= '
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
									border-color: ' . $hover_color . ' !important;
									background: ' . $hover_color . ' !important;
								}
							';

					} else {

						if( $color )
							$output .= '
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon,
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
									background: ' . $color . ';
									border-color: ' . $color . ';
								}
							';

						if( $hover_color )
							$output .= '
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon,
								.dpsp-button-style-1 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
									border-color: ' . $hover_color . ' !important;
									background: ' . $hover_color . ' !important;
								}
							';
					}
					break;

				// Button style 2
				case 2:
					if( $color )
						$output .= '
							.dpsp-button-style-2 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								background: ' . $color . ';
								border-color: ' . $color . ';
							}
							.dpsp-button-style-2 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								background: ' . dpsp_darken_color( $color, 1.1 ) . ';
								border-color: ' . dpsp_darken_color( $color, 1.1 ) . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-2 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover,
							.dpsp-button-style-2 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon {
								background: ' . $hover_color . ';
								border-color: ' . $hover_color . ';
							}
						';
					break;

				// Button style 3
				case 3:
					if( $color )
						$output .= '
							.dpsp-button-style-3 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								border-color: ' . $color . ';
								color: ' . $color . ';
							}
							.dpsp-button-style-3 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								background: ' . $color . ';
								border-color: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-3 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon,
							.dpsp-button-style-3 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
								border-color: ' . $hover_color . ' !important;
								background: ' . $hover_color . ' !important;
							}
						';
					break;


				// Button style 4
				case 4:
					if( $color )
						$output .= '
							.dpsp-button-style-4 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								background: ' . $color . ';
								border-color: ' . $color . ';
							}
							.dpsp-button-style-4 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								border-color: ' . $color . ';
								color: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-4 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon,
							.dpsp-button-style-4 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
								border-color: ' . $hover_color . ' !important;
								background: ' . $hover_color . ' !important;
							}
						';
					break;


				// Button style 5
				case 5:

					if( $color )
						$output .= '
							.dpsp-button-style-5 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon,
							.dpsp-button-style-5 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								border-color: ' . $color . ';							
								color: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-5 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
								border-color: ' . $hover_color . ';
								background: ' . $hover_color . ';
							}
						';
					break;


				// Button style 6
				case 6:

					if( $color )
						$output .= '
							.dpsp-button-style-6 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								color: ' . $color . ';
							}
							.dpsp-button-style-6 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								border-color: ' . $color . ';
								background: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-6 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
								color: ' . $hover_color . ';
							}
							.dpsp-button-style-6 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon {
								border-color: ' . $hover_color . ';
								background: ' . $hover_color . ';
							}
						';
					break;


				// Button style 7
				case 7:

					if( $color )
						$output .= '
							.dpsp-button-style-7 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn {
								color: ' . $color . ';
							}
							.dpsp-button-style-7 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								border-color: ' . $color . ';
								color: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-7 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover {
								color: ' . $hover_color . ';
							}
							.dpsp-button-style-7 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon {
								border-color: ' . $hover_color . ';
								background: ' . $hover_color . ';
							}
						';
					break;


				// Button style 8
				case 8:

					if( $color )
						$output .= '
							.dpsp-button-style-8 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn,
							.dpsp-button-style-8 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn .dpsp-network-icon {
								color: ' . $color . ';
							}
						';

					if( $hover_color )
						$output .= '
							.dpsp-button-style-8 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover,
							.dpsp-button-style-8 .dpsp-networks-btns-' . $location . ' .dpsp-network-btn:hover .dpsp-network-icon {
								color: ' . $hover_color . ';
							}
						';
					break;

			}
		}
		
		
		// Actually outputting the styling
		echo '<style type="text/css" data-source="Social Pug">' . apply_filters( 'dpsp_output_inline_style', $output ) . '</style>';

	}
	add_action( 'wp_head', 'dpsp_output_inline_style' );


	/*
	 * Output the meta tags needed by the social networks
	 *
	 */
	function dpsp_output_meta_tags() {

		$settings = get_option( 'dpsp_settings', array() );

		if( ! empty( $settings['disable_meta_tags'] ) )
			return;

		if( ! is_singular() )
			return;

		/**
		 * Get our own set of Open Graph tags
		 *
		 */
		$current_post   = dpsp_get_current_post();

		if( is_null( $current_post ) )
			return;

		$og_url 		= dpsp_get_post_url( $current_post->ID );
		$og_title 	  	= dpsp_get_post_title( $current_post->ID );
		$og_description = dpsp_get_post_description( $current_post->ID );
		$og_image_data	= dpsp_get_post_image_data( $current_post->ID );

		/**
		 * Get Yoast SEO set of Open Graph tags
		 *
		 * Given the large number of websites using Yoast, we'll do a check to see
		 * if Yoast is installed and if the user has added meta tags information in Yoast
		 *
		 * On the to-do list
		 *
		 */


		/**
		 * Disable Jackpack Open Graph tags
		 *
		 */
		add_filter( 'jetpack_enable_opengraph', '__return_false', 99 );
		add_filter( 'jetpack_enable_open_graph', '__return_false', 99 );


		// Begin output
		echo PHP_EOL . PHP_EOL . '<!-- Social Pug v.' . DPSP_VERSION . ' https://devpups.com/social-pug/ -->';

		/**
		 * Open Graph tags
		 *
		 */
		echo PHP_EOL . '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '" />';

		echo PHP_EOL . '<meta property="og:type" content="article" />';
		echo PHP_EOL . '<meta property="og:url"	content="' . esc_attr( $og_url ) . '" />';
		echo PHP_EOL . '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />';
		echo PHP_EOL . '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />';
		echo PHP_EOL . '<meta property="og:updated_time" content="' . date( 'c', strtotime( $current_post->post_modified ) ) . '" />';

		echo PHP_EOL . '<meta property="article:published_time" content="' . date( 'c', strtotime( $current_post->post_date ) ) . '" />';
		echo PHP_EOL . '<meta property="article:modified_time" content="' . date( 'c', strtotime( $current_post->post_modified ) ) . '" />';

		if( ! is_null( $og_image_data ) && is_array( $og_image_data ) ) {

			echo PHP_EOL . '<meta property="og:image" content="' . esc_attr( $og_image_data[0] ) . '" />';
			echo PHP_EOL . '<meta property="og:image:width" content="' . esc_attr( $og_image_data[1] ) . '" />';
			echo PHP_EOL . '<meta property="og:image:height" content="' . esc_attr( $og_image_data[2] ) . '" />';

		}

		/**
		 * Facebook specific tags
		 *
		 */
		if( ! empty( $settings['facebook_app_id'] ) )
			echo PHP_EOL . '<meta property="fb:app_id" content ="' . esc_attr( $settings['facebook_app_id'] ) . '" />';

		/**
		 * Twitter specific tags
		 *
		 */
		echo PHP_EOL . '<meta name="twitter:card" content="summary_large_image" />';
		echo PHP_EOL . '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '" />';
		echo PHP_EOL . '<meta name="twitter:description" content="' . esc_attr( $og_description ) . '" />';

		if( ! is_null( $og_image_data ) && is_array( $og_image_data ) ) {

			echo PHP_EOL . '<meta name="twitter:image" content="' . esc_attr( $og_image_data[0] ) . '" />';

		}

		/**
		 * Output extra meta tags
		 *
		 */
		do_action( 'dpsp_output_meta_tags' );

		// End output
		echo PHP_EOL . '<!-- Social Pug v.' . DPSP_VERSION . ' https://devpups.com/social-pug/ -->' . PHP_EOL;


	}
	add_action( 'wp_head', 'dpsp_output_meta_tags', 1 );


	/**
	 * Returns the HTML string for the social share buttons
	 *
	 * @param array $args
	 *
	 * Arguments array elements
	 *
	 * 'id'						- string
	 * 'networks'				- array
	 * 'networks_labels'		- array
	 * 'button_style'			- int (from 1 to 8)
	 * 'shape' 		 			- string (rectangle/rounded/circle)
	 * 'size'					- string (small/medium/large)
	 * 'columns'		 		- string (auto) / int (from 1 to 6),
	 * 'show_labels'	 		- bool
	 * 'button_spacing'			- bool
	 * 'show_count'	 			- bool
	 * 'show_total_count'		- bool
	 * 'total_count_position'	- string (before/after)
	 * 'count_round'			- bool
	 * 'minimum_count'			- int
	 * 'show_mobile'			- bool
	 * 'overwrite'				- bool
	 *
	 * @return string
	 *
	 */
	function dpsp_get_share_buttons( $args = array() ) {

		/*
		 * Modify settings based on the attributes
		 *
		 */
		$settings = array();

		// Set networks and network labels
		if( ! empty( $args['networks'] ) ) {

			$networks 		 = array_map( 'trim', $args['networks'] );
			$networks_labels = ( ! empty( $args['networks_labels'] ) ? $args['networks_labels'] : array() );

			// Set the array with the networks slug and labels
			foreach( $networks as $key => $network_slug ) {
				$networks[$network_slug]['label'] = ( isset( $networks_labels[$key] ) ? $networks_labels[$key] : dpsp_get_network_name( $network_slug ) );
				unset($networks[$key]);
			}

			$settings['networks'] = $networks;

		}

		// Set button style
		if( ! empty( $args['button_style'] ) )
			$settings['button_style'] = $args['button_style'];
		// If no style is set, set the default to the first style
		if( ! isset( $settings['button_style'] ) )
			$settings['button_style'] = 1;

		// Set buttons shape
		if( ! empty( $args['shape'] ) )
			$settings['display']['shape'] = $args['shape'];

		// Set buttons size
		if( ! empty( $args['size'] ) )
			$settings['display']['size'] = $args['size'];

		// Set columns
		if( ! empty( $args['columns'] ) )
			$settings['display']['column_count'] = $args['columns'];

		// Show labels
		if( isset( $args['show_labels'] ) )
			$settings['display']['show_labels'] = ( ! empty( $args['show_labels'] ) ? 'yes' : 'no' );

		// Button spacing
		if( isset( $args['button_spacing'] ) )
			$settings['display']['spacing'] = ( ! empty( $args['button_spacing'] ) ? 'yes' : 'no' );

		// Show count
		if( isset( $args['show_count'] ) )
			$settings['display']['show_count'] = ( ! empty( $args['show_count'] ) ? 'yes' : 'no' );

		// Show count total
		if( isset( $args['show_total_count'] ) )
			$settings['display']['show_count_total'] = ( ! empty( $args['show_total_count'] ) ? 'yes' : 'no' );

		// Total count position
		if( ! empty( $args['total_count_position'] ) )
			$settings['display']['total_count_position'] = $args['total_count_position'];

		// Share counts round
		if( isset( $args['count_round'] ) )
			$settings['display']['count_round'] = ( ! empty( $args['count_round'] ) ? 'yes' : 'no' );

		// Share minimum count
		if( ! empty( $args['minimum_count'] ) )
			$settings['display']['minimum_count'] = (int)$args['minimum_count'];

		// Show on mobile
		if( isset( $args['show_mobile'] ) )
			$settings['display']['show_mobile'] = ( ! empty( $args['show_mobile'] ) ? 'yes' : 'no' );


		// If Overwrite is set to "yes" strip everything
		if( empty( $args['overwrite'] ) ) {

			// Location settings for the Content location
			$saved_settings = dpsp_get_location_settings( 'content' );

			// Social networks
			$settings['networks'] = ( ! empty( $settings['networks'] ) ? $settings['networks'] : $saved_settings['networks'] );

			// Display settings
			$settings['display'] = array_merge( $saved_settings['display'], $settings['display'] );

		}

		// Remove all display settings that have "no" as a value
		foreach( $settings['display'] as $key => $value ) {
			if( $value == 'no' )
				unset( $settings['display'][$key] );
		}


		// Round counts cannot be changed direcly because they are too dependend
		// on the location settings saved in the database.
		// For the moment removing the filters and adding them again is the only solution
		if( ! isset( $settings['display']['count_round'] ) ) {
			remove_filter( 'dpsp_get_output_post_shares_counts', 'dpsp_round_share_counts', 10, 2 );
			remove_filter( 'dpsp_get_output_total_share_count', 'dpsp_round_share_counts', 10, 2 );
		}


		/*
		 * Start outputing
		 *
		 */
		$output = '';

		// Classes for the wrapper
		$wrapper_classes   = array( 'dpsp-share-buttons-wrapper' );
		$wrapper_classes[] = ( isset( $settings['display']['shape'] ) ? 'dpsp-shape-' . $settings['display']['shape'] : '' );
		$wrapper_classes[] = ( isset( $settings['display']['size'] ) ? 'dpsp-size-' . $settings['display']['size'] : 'dpsp-size-medium' );
		$wrapper_classes[] = ( isset( $settings['display']['column_count'] ) ? 'dpsp-column-' . $settings['display']['column_count'] : '' );
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


		$output = '<div ' . ( ! empty( $args['id'] ) ? 'id="' . esc_attr( $args['id'] ) . '"' : '' ) . ' class="' . $wrapper_classes . '">' . $output . '</div>';

		// Add back the filters
		if( ! isset( $settings['display']['count_round'] ) ) {
			add_filter( 'dpsp_get_output_post_shares_counts', 'dpsp_round_share_counts', 10, 2 );
			add_filter( 'dpsp_get_output_total_share_count', 'dpsp_round_share_counts', 10, 2 );
		}

		return $output;

	}