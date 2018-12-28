<?php
/*
 * Class file that wraps up all shortcodes
 * The use of the class was not necessary, but I find it cleaner this way,
 * instead of using functions and/or separating functions in multiple files
 *
 */ 

Class Social_Pug_Shortcodes {

	/*
	 * Constructor function for all shortcodes
	 *
	 */
	public function __construct() {

		$shortcodes = array(
			'socialpug_share'  => __CLASS__ . '::share_buttons',
			'socialpug_follow' => __CLASS__ . '::follow_buttons',
			'socialpug_tweet'  => __CLASS__ . '::click_to_tweet'
		);

		foreach( $shortcodes as $shortcode_slug => $shortcode_callback ) {
			add_shortcode( $shortcode_slug, $shortcode_callback );
		}

	}


	/*
	 * Display the share buttons
	 * The buttons default to the settings for the Content location,
	 * but they can be overwritten by the custom attributes
	 *
	 */
	public static function share_buttons( $atts = array(), $content = null ) {

		/*
		 * Define supported attributes
		 *
		 * If "overwrite" is "yes" no settings from the Content location will be taken into account
		 *
		 */
		$args = shortcode_atts( array(
			'id'					=> '',
			'networks'				=> '',
			'networks_labels'		=> '',
			'button_style'			=> '',
			'shape' 		 		=> '',
			'size'					=> '',
			'columns'		 		=> '',
			'show_labels'	 		=> '',
			'button_spacing'		=> '',
			'show_count'	 		=> '',
			'show_total_count'		=> '',
			'total_count_position'	=> '',
			'count_round'			=> '',
			'minimum_count'			=> '',
			'show_mobile'			=> '',
			'overwrite'				=> 'no',
			'url'					=> '',
			'description'			=> ''
		), $atts );


		/*
		 * Modify settings based on the attributes
		 *
		 */

		// Location settings for the Content location
		$settings = dpsp_get_location_settings( 'content' );

		// If Overwrite is set to "yes" strip everything
		if( $args['overwrite'] == 'yes' )
			$settings = array();

		// Set networks and network labels
		if( !empty( $args['networks'] ) ) {

			$networks 		 = array_map( 'trim', explode( ',', $args['networks'] ) );
			$networks_labels = ( !empty( $args['networks_labels'] ) ? array_map( 'trim', explode( ',', $args['networks_labels'] ) ) : array() );

			// Set the array with the networks slug and labels
			foreach( $networks as $key => $network_slug ) {
				$networks[$network_slug]['label'] = ( isset( $networks_labels[$key] ) ? $networks_labels[$key] : dpsp_get_network_name( $network_slug ) );
				unset($networks[$key]);
			}

			$settings['networks'] = $networks;

		}

		// Set button style
		if( !empty( $args['button_style'] ) )
			$settings['button_style'] = $args['button_style'];
		// If no style is set, set the default to the first style
		if( !isset( $settings['button_style'] ) )
			$settings['button_style'] = 1;

		// Set display option
		if( !empty( $args['shape'] ) )
			$settings['display']['shape'] = $args['shape'];

		if( !empty( $args['size'] ) )
			$settings['display']['size'] = $args['size'];

		if( !empty( $args['columns'] ) )
			$settings['display']['column_count'] = $args['columns'];

		if( !empty( $args['show_labels'] ) )
			$settings['display']['show_labels'] = $args['show_labels'];

		if( !empty( $args['button_spacing'] ) )
			$settings['display']['spacing'] = $args['button_spacing'];

		if( !empty( $args['show_count'] ) )
			$settings['display']['show_count'] = $args['show_count'];

		if( !empty( $args['show_total_count'] ) )
			$settings['display']['show_count_total'] = $args['show_total_count'];

		if( !empty( $args['total_count_position'] ) )
			$settings['display']['total_count_position'] = $args['total_count_position'];

		if( !empty( $args['count_round'] ) )
			$settings['display']['count_round'] = $args['count_round'];

		if( !empty( $args['minimum_count'] ) )
			$settings['display']['minimum_count'] = $args['minimum_count'];

		if( !empty( $args['show_mobile'] ) )
			$settings['display']['show_mobile'] = $args['show_mobile'];


		$data = array();

		if( !empty( $args['url'] ) )
			$data['shortcode_url'] = $args['url'];
		
		if( !empty( $args['url'] ) )
			$data['shortcode_desc'] = $args['description'];


		// Remove all display settings that have "no" as a value
		foreach( $settings['display'] as $key => $value ) {
			if( $value == 'no' )
				unset( $settings['display'][$key] );
		}


		// Round counts cannot be changed direcly because they are too dependend
		// on the location settings saved in the database.
		// For the moment removing the filters and adding them again is the only solution
		if( !isset( $settings['display']['count_round'] ) ) {
			remove_filter( 'dpsp_get_output_post_shares_counts', 'dpsp_round_share_counts', 10, 2 );
			remove_filter( 'dpsp_get_output_total_share_count', 'dpsp_round_share_counts', 10, 2 );
		}


		/*
		 * Start outputing
		 *
		 */
		$output = '';

		// Classes for the wrapper
		$wrapper_classes = array( 'dpsp-shortcode-wrapper' );
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
			$output .= dpsp_get_output_network_buttons( $settings, 'share', 'content', $data );


		$output = '<div ' . ( !empty($args['id']) ? 'id="' . $args['id'] . '"' : '' ) . ' class="' . $wrapper_classes . '">' . $output . '</div>';

		// Add back the filters
		if( !isset( $settings['display']['count_round'] ) ) {
			add_filter( 'dpsp_get_output_post_shares_counts', 'dpsp_round_share_counts', 10, 2 );
			add_filter( 'dpsp_get_output_total_share_count', 'dpsp_round_share_counts', 10, 2 );
		}

		return $output;

	}


	/*
	 * Display the follow buttons
	 * The buttons default to the settings for the Follow Widget location,
	 * but they can be overwritten by the custom attributes
	 *
	 */
	public static function follow_buttons( $atts = array(), $content = null ) {

		/*
		 * Define supported attributes
		 *
		 * If "overwrite" is "yes" no settings from the Follow Widget location will be taken into account
		 *
		 */
		$args = shortcode_atts( array(
			'id'					=> '',
			'networks'				=> '',
			'networks_labels'		=> '',
			'button_style'			=> '',
			'shape' 		 		=> '',
			'size'					=> '',
			'columns'		 		=> '',
			'show_labels'	 		=> '',
			'button_spacing'		=> '',
			'overwrite'				=> 'no'
		), $atts );


		/*
		 * Modify settings based on the attributes
		 *
		 */

		// Location settings for the Content location
		$settings = dpsp_get_location_settings( 'follow_widget' );

		// If Overwrite is set to "yes" strip everything
		if( $args['overwrite'] == 'yes' )
			$settings = array();

		// Set networks and network labels
		if( !empty( $args['networks'] ) ) {

			$networks 		 = array_map( 'trim', explode( ',', $args['networks'] ) );
			$networks_labels = ( !empty( $args['networks_labels'] ) ? array_map( 'trim', explode( ',', $args['networks_labels'] ) ) : array() );

			// Set the array with the networks slug and labels
			foreach( $networks as $key => $network_slug ) {
				$networks[$network_slug]['label'] = ( isset( $networks_labels[$key] ) ? $networks_labels[$key] : dpsp_get_network_name( $network_slug ) );
				unset($networks[$key]);
			}

			$settings['networks'] = $networks;

		}

		// Set button style
		if( !empty( $args['button_style'] ) )
			$settings['button_style'] = $args['button_style'];
		// If no style is set, set the default to the first style
		if( !isset( $settings['button_style'] ) )
			$settings['button_style'] = 1;

		// Set display option
		if( !empty( $args['shape'] ) )
			$settings['display']['shape'] = $args['shape'];

		if( !empty( $args['size'] ) )
			$settings['display']['size'] = $args['size'];

		if( !empty( $args['columns'] ) )
			$settings['display']['column_count'] = $args['columns'];

		if( !empty( $args['show_labels'] ) )
			$settings['display']['show_labels'] = $args['show_labels'];

		if( !empty( $args['button_spacing'] ) )
			$settings['display']['spacing'] = $args['button_spacing'];

		// Remove all display settings that have "no" as a value
		foreach( $settings['display'] as $key => $value ) {
			if( $value == 'no' )
				unset( $settings['display'][$key] );
		}

		/*
		 * Start outputing
		 *
		 */
		$output = '';

		// Classes for the wrapper
		$wrapper_classes = array( 'dpsp-shortcode-follow-wrapper' );
		$wrapper_classes[] = ( isset( $settings['display']['shape'] ) ? 'dpsp-shape-' . $settings['display']['shape'] : '' );
		$wrapper_classes[] = ( isset( $settings['display']['size'] ) ? 'dpsp-size-' . $settings['display']['size'] : 'dpsp-size-medium' );
		$wrapper_classes[] = ( isset( $settings['display']['column_count'] ) ? 'dpsp-column-' . $settings['display']['column_count'] : '' );
		$wrapper_classes[] = ( isset( $settings['display']['spacing'] ) ? 'dpsp-has-spacing' : '' );
		$wrapper_classes[] = ( isset( $settings['display']['show_labels'] ) || isset( $settings['display']['show_count'] ) ? '' : 'dpsp-no-labels' );
		$wrapper_classes[] = ( isset( $settings['display']['show_count'] ) ? 'dpsp-has-buttons-count' : '' );
		$wrapper_classes[] = ( isset( $settings['display']['show_mobile'] ) ? 'dpsp-show-on-mobile' : 'dpsp-hide-on-mobile' );

		// Button styles
		$wrapper_classes[] = ( isset( $settings['button_style'] ) ? 'dpsp-button-style-' . $settings['button_style'] : '' );

		$wrapper_classes = implode( ' ', array_filter( $wrapper_classes ) );


		// Gets the social network buttons
		if( isset( $settings['networks'] ) )
			$output .= dpsp_get_output_network_buttons( $settings, 'follow', 'follow_widget' );


		$output = '<div ' . ( !empty($args['id']) ? 'id="' . $args['id'] . '"' : '' ) . ' class="' . $wrapper_classes . '">' . $output . '</div>';

		return $output;

	}


	/*
	 * Display the Click to Tweet box
	 *
	 */
	public static function click_to_tweet( $atts = array() ) {

		// Supported attributes
		$args = shortcode_atts( array(
			'tweet'		    => '',
			'display_tweet' => '',
			'style'		 	=> '',
			'remove_url' 	=> null,
			'remove_username' => null,
			'author_avatar' => ''
		), $atts );


		// Exit if there is no tweet
		if( empty( $args['tweet'] ) )
			return;

		// Get settings
		$settings = get_option( 'dpsp_settings', array() );


		// Set tweet
		$tweet = $args['tweet'];

		// Set display tweet
		$display_tweet = ( ! empty( $args['display_tweet'] ) ? $args['display_tweet'] : $args['tweet'] );

		// Check tweet length and slice it if it's too long
		if( strlen( $tweet ) > apply_filters( 'dpsp_tweet_maximum_count', 280 ) ) {
			$tweet = substr( $tweet , 0, ( apply_filters( 'dpsp_tweet_maximum_count', 280 ) - strlen( $tweet ) - 10 ) ) . '... ';
		}


		// Get the share link
		$network_share_link = dpsp_get_network_share_link( 'twitter', ( $args['remove_url'] == 'yes' ? '' : null ), $tweet, '' );

		// Handle @via 
		if( ! empty( $settings['twitter_username'] ) ) {

			$twitter_username = str_replace( '@', '', trim( $settings['twitter_username'] ) );

			if( $args['remove_username'] == 'yes' ) {
				$network_share_link = remove_query_arg( 'via', $network_share_link );

			} else {

				if( strpos( $network_share_link, 'via=' ) === false )
					$network_share_link = add_query_arg( array( 'via' => $twitter_username ), $network_share_link );

			}

		}


		// Get Click to Tweet style class
		$style 		 = ( ! empty( $args['style'] ) ? trim( $args['style'] ) : ( ! empty( $settings['ctt_style'] ) ? $settings['ctt_style'] : 1 ) );
		$style 		 = ( in_array( $style, array( 1,2,3,4,5 ) ) ? $style : 1 );
		$style_class = 'dpsp-style-' . $style;

		// Check to see if there's a style with author details
		$avatar = false;

		if( ! empty( $args['author_avatar'] ) && $args['author_avatar'] == 'yes' ) {

			// Get post
			global $dpsp_cache_wp_post;

			// Get the post author avatar
			if( $dpsp_cache_wp_post && ! empty( $dpsp_cache_wp_post->post_author ) ) {

				$avatar = get_avatar( $dpsp_cache_wp_post->post_author, apply_filters( 'dpsp_click_to_tweet_avatar', 85 ) );

				if( $avatar )
					$style_class .= ' dpsp-has-avatar';

			}
		}

		// Add cta position to the style class
		$style_class .= ' dpsp-click-to-tweet-cta-' . ( ! empty( $settings['ctt_link_position'] ) ? esc_attr( $settings['ctt_link_position'] ) : 'right' );

		// Add cta icon animation to the styles class
		$style_class .= ( ! empty( $settings['ctt_link_icon_animation'] ) ? ' dpsp-click-to-tweet-cta-icon-animation' : '' );
		
		// Output
		$output = '<a class="dpsp-click-to-tweet ' . $style_class . '" href="' . $network_share_link . '">';

			// Author avatar
			if( $avatar )
				$output .= $avatar;

			// Tweet content
			$output .= '<div class="dpsp-click-to-tweet-content">';
				$output .= $display_tweet;
			$output .= '</div>';

			// Tweet footer
			$output .= '<div class="dpsp-click-to-tweet-footer">';
				$output .= '<span class="dpsp-click-to-tweet-cta">' . ( !empty( $settings['ctt_link_text'] ) ? '<span>' . $settings['ctt_link_text'] . '</span>' : '' ) . '<i class="dpsp-network-btn dpsp-twitter"><span class="dpsp-network-icon"></span></i>' . '</span>';
			$output .= '</div>';

		$output .= '</a>';

		return $output;

	}

}

// Fire up shortcodes
new Social_Pug_Shortcodes;