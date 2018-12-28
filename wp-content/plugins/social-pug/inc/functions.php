<?php

	/**
	 * Returns an array with the positions where the social networks
	 * can be placed
	 *
	 * @return array
	 *
	 */
	function dpsp_get_network_locations( $for = 'all', $only_slugs = true ) {

		$locations_share = array(
			'sidebar' 		=> __( 'Floating Sidebar', 'social-pug' ),
			'content' 		=> __( 'Content', 'social-pug' ),
			'sticky_bar'	=> __( 'Sticky Bar', 'social-pug' ),
			'pop_up'  		=> __( 'Pop-Up', 'social-pug' )
		);

		$locations_follow = array(
			'follow_widget' => __( 'Follow Widget', 'social-pug' ),
		);

		switch( $for ) {
			case 'share':
				$locations = $locations_share;
				break;
			case 'follow':
				$locations = $locations_follow;
				break;
			case 'all':
				$locations = array_merge( $locations_share, $locations_follow );
				break;

		}

		$locations = apply_filters( 'dpsp_get_network_locations', $locations, $for );

		if( $only_slugs )
			$locations = array_keys( $locations );

		return $locations;

	}


	/**
	 * Returns the name of a location
	 *
	 * @param string $location_slug
	 *
	 * @return string
	 *
	 */
	function dpsp_get_network_location_name( $location_slug ) {

		$locations = dpsp_get_network_locations( 'all', false );

		if( isset( $locations[$location_slug] ) )
			return $locations[$location_slug];
		else
			return '';

	}

	/*
	 * Checks to see if the location is active or not
	 *
	 */
	function dpsp_is_location_active( $location_slug ) {

		$settings = dpsp_get_location_settings( $location_slug );

		if( isset( $settings['active'] ) )
			return true;
		else
			return false;

	}


	/**
	 * Determines whether the location should be displayed or not
	 *
	 * @param string $location_slug
	 *
	 * @return bool
	 *
	 */
	function dpsp_is_location_displayable( $location_slug ) {

		$return = true;

		// Get saved settings for the location
		$settings = dpsp_get_location_settings( $location_slug );

		if( empty( $settings ) )
			$return = false;

		if( ! isset( $settings['post_type_display'] ) || ( isset( $settings['post_type_display'] ) && ! is_singular( $settings['post_type_display'] ) ) )
			$return = false;

		return apply_filters( 'dpsp_is_location_displayable', $return, $location_slug, $settings );

	}


	/**
	 * Get settings for a particular location
	 * This is a developer friendly function
	 *
	 * @param string $location
	 *
	 * @return mixed null | array
	 *
	 */
	function dpsp_get_location_settings( $location = '' ) {

		// Return null if no location is provided
		if( empty( $location ) )
			return null;

		$location_settings = get_option( 'dpsp_location_' . $location, array() );

		return apply_filters( 'dpsp_get_location_settings', $location_settings, $location );

	}


	/**
	 * Function that returns all networks
	 *
	 * @param string $for - buttons for share(ing) or follow(ing)
	 *
	 * @return array
	 * 
	 */
	function dpsp_get_networks( $for = 'share' ) {

		$networks = array();

		$networks_share = array(
			'facebook'		=> 'Facebook',
			'twitter'		=> 'Twitter',
			'google-plus'	=> 'Google+',
			'pinterest'		=> 'Pinterest',
			'linkedin'		=> 'LinkedIn',
			'reddit'		=> 'Reddit',
			'vkontakte'		=> 'VK',
			'whatsapp'		=> 'WhatsApp',
			'pocket'		=> 'Pocket',
			'yummly'		=> 'Yummly',
			'buffer'		=> 'Buffer',
			'tumblr'		=> 'Tumblr',
			'xing'			=> 'Xing',
			'flipboard'		=> 'Flipboard',
			'telegram'	    => 'Telegram',
			'email'			=> 'Email',
			'print'			=> 'Print'
		);

		$networks_follow = array(
			'facebook' 		=> 'Facebook',
			'twitter'		=> 'Twitter',
			'google-plus'	=> 'Google+',
			'pinterest'		=> 'Pinterest',
			'linkedin'		=> 'LinkedIn',
			'reddit'		=> 'Reddit',
			'vkontakte'		=> 'VK',
			'instagram'		=> 'Instagram',
			'youtube'		=> 'YouTube',
			'vimeo'			=> 'Vimeo',
			'soundcloud'	=> 'SoundCloud',
			'twitch'		=> 'Twitch',
			'yummly'		=> 'Yummly',
			'behance'		=> 'Behance',
			'xing'			=> 'Xing',
			'github'		=> 'GitHub',
			'telegram'	    => 'Telegram'
		);
	
		switch ( $for ) {
			case 'share':
				$networks = $networks_share;
				break;
			
			case 'follow':
				$networks = $networks_follow;
				break;

			case 'all':
				$networks = array_merge( $networks_share, $networks_follow );
				break;

			default:
				break;
		}

		return apply_filters( 'dpsp_get_networks', $networks, $for );

	}


	/*
	 * Function that returns the name of a social network given its slug
	 *
	 */
	function dpsp_get_network_name( $slug ) {

		$nerworks = dpsp_get_networks('all');

		if( isset( $nerworks[$slug] ) )
			return $nerworks[$slug];
		else
			return '';
	}


	/*
	 * Returns all networks that are set in every location panel
	 *
	 * @return array;
	 *
	 */
	function dpsp_get_active_networks( $for = 'share' ) {

		$locations = dpsp_get_network_locations( $for );
		$networks  = array();

		foreach( $locations as $location ) {

			$location_settings = dpsp_get_location_settings( $location );

			if( isset( $location_settings['networks'] ) && !empty( $location_settings['networks'] ) ) {
				foreach( $location_settings['networks'] as $network_slug => $network ) {

					if( !in_array( $network_slug, $networks ) )
						$networks[] = $network_slug;

				}
			}

		}

		return apply_filters( 'dpsp_get_active_networks', $networks, $for );

	}


	/*
	 * Return an array of registered post types slugs and names
	 * 
	 * @return array
	 *
	 */
	function dpsp_get_post_types() {

		// Get default and custom post types
		$default_post_types = array( 'post', 'page' );
		$custom_post_types 	= get_post_types( array( 'public' => true, '_builtin' => false ) );
		$post_types 		= array_merge( $default_post_types, $custom_post_types );

		// The array we wish to return
		$return_post_types = array();

		foreach( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			$return_post_types[$post_type] = $post_type_object->labels->singular_name;
		}

		return apply_filters( 'dpsp_get_post_types', $return_post_types );

	}


	/*
	 * Returns the post types that are active for all locations
	 *
	 */
	function dpsp_get_active_post_types() {

		$locations  = dpsp_get_network_locations();
		$post_types = array();

		foreach( $locations as $location ) {

			$location_settings = get_option( 'dpsp_location_' . $location, array() );

			if( isset( $location_settings['active'] ) && !empty( $location_settings['post_type_display'] ) )
				$post_types = array_merge( $post_types, $location_settings['post_type_display'] );

		}

		$post_types = array_unique( $post_types );

		return $post_types;

	}


	/*
	 * Returns the saved option, but replaces the saved social network
	 * data with simple data to display in the back-end
	 *
	 * @param string $option_name
	 *
	 */
	function dpsp_get_back_end_display_option( $option_name ) {

		$settings = get_option($option_name);
		$networks = dpsp_get_networks('all');

		$settings_networks_count = ( ! empty( $settings['networks'] ) ? count( $settings['networks'] ) : 0 );

		if( $settings_networks_count > 2 ) {

			$current_network = 0;
			foreach( $settings['networks'] as $network_slug => $network ) {

				if( $current_network > 2 ) {
					unset( $settings['networks'][$network_slug] );
				} else {
					$settings['networks'][$network_slug] = array( 'label' => $networks[$network_slug] );
				}

				$current_network++;
			}

		} else {
			$settings['networks'] = array(
				'facebook'    => array( 'label' => 'Facebook' ),
				'twitter'	  => array( 'label' => 'Twitter' ),
				'google-plus' => array( 'label' => 'Google+')
			);
		}


		//Unset certain options
		unset( $settings['display']['show_count'] );

		return $settings;

	}


	/**
	 * Returns the share link for a social network given the network slug
   	 *
   	 * @param string $network_slug
   	 * @param string $post_url
   	 * @param string $post_title
   	 * @param string $post_description
   	 * @param string $post_image
	 *
	 * @return string
	 *
	 */
	function dpsp_get_network_share_link( $network_slug = '', $post_url = null, $post_title = null, $post_description = null, $post_image = null ) {

		if( empty( $network_slug ) )
			return '';

		if( is_null( $post_url ) ) {
			$post_obj = dpsp_get_current_post();
			$post_url = dpsp_get_post_url( $post_obj->ID );
		}

		if( is_null( $post_title ) ) {
			$post_obj   = dpsp_get_current_post();
			$post_title = dpsp_get_post_title( $post_obj->ID );
		}

		if( is_null( $post_description ) ) {
			$post_obj   	  = dpsp_get_current_post();
			$post_description = dpsp_get_post_description( $post_obj->ID );
		}


		// Late filtering
		$post_url   	  = urlencode( apply_filters( 'dpsp_get_network_share_link_post_url', $post_url, $network_slug ) );
		$post_title 	  = urlencode( apply_filters( 'dpsp_get_network_share_link_post_title', $post_title, $network_slug ) );
		$post_description = urlencode( apply_filters( 'dpsp_get_network_share_link_post_description', $post_description, $network_slug ) );
		$post_image 	  = apply_filters( 'dpsp_get_network_share_link_post_image', $post_image, $network_slug );

		switch( $network_slug ) {
			
			case 'facebook':
				$share_link = sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%1$s&t=%2$s', $post_url, $post_title );
				break;

			case 'twitter':
				$settings = get_option( 'dpsp_settings' );

				$via = ( !empty( $settings['twitter_username'] ) && !empty( $settings['tweets_have_username'] ) ) ? '&via=' . $settings['twitter_username'] : '';

				$share_link = sprintf( 'https://twitter.com/intent/tweet?text=%2$s&url=%1$s%3$s', $post_url, $post_title, $via );
				break;

			case 'google-plus':
				$share_link = sprintf( 'https://plus.google.com/share?url=%1$s', $post_url );
				break;

			case 'pinterest':
				if( !is_null( $post_image ) )
					$share_link = sprintf( 'http://pinterest.com/pin/create/button/?url=%1$s&media=%2$s&description=%3$s', $post_url, $post_image, $post_title );
				else
					$share_link = '#';
				break;

			case 'linkedin':
				$share_link = sprintf( 'https://www.linkedin.com/shareArticle?url=%1$s&title=%2$s&summary=%3$s&mini=true', $post_url, $post_title, $post_description );
				break;

			case 'reddit':
				$share_link = sprintf( 'https://www.reddit.com/submit?url=%1$s&title=%2$s', $post_url, $post_title );
				break;

			case 'vkontakte':
				$share_link = sprintf( 'http://vk.com/share.php?url=%1$s', $post_url );
				break;

			case 'whatsapp':
				$share_link = sprintf( 'whatsapp://send?text=%1$s+%2$s', $post_title, $post_url );
				break;

			case 'pocket':
				$share_link = sprintf( 'https://getpocket.com/edit?url=%1$s', $post_url );
				break;

			case 'yummly':
				$share_link = sprintf( 'http://www.yummly.com/urb/verify?url=%1$s&title=%2$s', $post_url, $post_title );
				break;

			case 'buffer':
				$share_link = sprintf( 'https://buffer.com/add?url=%1$s&text=%2$s', $post_url, $post_title );
				break;

			case 'tumblr':
				$share_link = sprintf( 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=%1$s', $post_url );
				break;

			case 'xing':
				$share_link = sprintf( 'https://www.xing.com/spi/shares/new?url=%1$s', $post_url );
				break;

			case 'flipboard':
				$share_link = sprintf( 'https://share.flipboard.com/bookmarklet/popout?v=2&url=%1$s&title=%2$s', $post_url, $post_title );
				break;

			case 'telegram':
				$share_link = sprintf( 'https://telegram.me/share/url?url=%1$s&text=%2$s', $post_url, $post_title );
				break;

			case 'email':
				$share_link = sprintf( 'mailto:?subject=%1$s&amp;body=%2$s', esc_attr(urldecode($post_title)), $post_url );
				break;

			case 'print':
				$share_link = '#';
				break;

			default:
				$share_link = '';
				break;
		}

		return apply_filters( 'dpsp_get_network_share_link', $share_link, $network_slug );

	}


	/**
	 * Returns the network follow link
	 * 
	 * @param string $network_slug
	 *
	 * @return string
	 *
	 */
	function dpsp_get_network_follow_link( $network_slug ) {

		$settings = get_option( 'dpsp_settings', array() );

		if( !empty( $settings[ $network_slug . '_username' ] ) )
			$network_handle = $settings[ $network_slug . '_username' ];
		else
			return '';

		// If it is a network username
		if( strpos( $network_handle, 'http' ) === false ) {

			switch( $network_slug ) {

				case 'facebook':
					$follow_link = sprintf('https://www.facebook.com/%1$s', $network_handle );
					break;

				case 'twitter':
					$follow_link = sprintf('https://twitter.com/%1$s', $network_handle );
					break;

				case 'google-plus':
					$follow_link = sprintf('https://plus.google.com/%1$s', $network_handle );
					break;

				case 'pinterest':
					$follow_link = sprintf('https://pinterest.com/%1$s', $network_handle );
					break;

				case 'linkedin':
					$follow_link = sprintf('https://www.linkedin.com/in/%1$s', $network_handle );
					break;

				case 'reddit':
					$follow_link = sprintf('https://www.reddit.com/user/%1$s', $network_handle );
					break;

				case 'vkontakte':
					$follow_link = sprintf('https://vk.com/%1$s', $network_handle );
					break;

				case 'instagram':
					$follow_link = sprintf('https://www.instagram.com/%1$s', $network_handle );
					break;

				case 'youtube':
					$follow_link = sprintf('https://www.youtube.com/user/%1$s', $network_handle );
					break;

				case 'vimeo':
					$follow_link = sprintf('https://vimeo.com/%1$s', $network_handle );
					break;

				case 'soundcloud':
					$follow_link = sprintf('https://soundcloud.com/%1$s', $network_handle );
					break;

				case 'twitch':
					$follow_link = sprintf('https://www.twitch.tv/%1$s/profile', $network_handle );
					break;

				case 'yummly':
					$follow_link = sprintf('https://www.yummly.com/profile/%1$s', $network_handle );
					break;

				case 'behance':
					$follow_link = sprintf('https://www.behance.net/%1$s', $network_handle );
					break;

				case 'xing':
					$follow_link = sprintf('https://www.xing.com/profile/%1$s', $network_handle );
					break;

				case 'github':
					$follow_link = sprintf('https://github.com/%1$s', $network_handle );
					break;

				case 'telegram':
					$follow_link = sprintf('https://telegram.me/%1$s', $network_handle );
					break;

				default:
					$follow_link = '';
					break;

			}

		// If it is full link
		} else {

			$follow_link = $network_handle;

		}

		return apply_filters( 'dpsp_get_network_follow_link', $follow_link, $network_slug );

	}


	/**
	 * Return Facebook, Pinterest and Google+ networks if no active networks are present
	 * on first ever activation of the plugin in order for the first ever cron job to pull
	 * the share counts for these three social networks.
	 *
	 * Without this, the cron job will be executed later and at first no share counts will be
	 * available for the last posts.
	 *
	 * @param array $networks
	 * @param string $for
	 *
	 * @return array
	 *
	 */
	function dpsp_first_activation_active_networks( $networks = array(), $for = 'share' ) {

		if( ! empty( $networks ) )
			return $networks;

		if( $for != 'share' )
			return $networks;

		$first_activation = get_option( 'dpsp_first_activation', '' );

		if( ! empty( $first_activation ) )
			return $networks;

		$networks = array( 'facebook', 'pinterest', 'google-plus' );

		return $networks;

	}
	add_filter( 'dpsp_get_active_networks', 'dpsp_first_activation_active_networks', 10, 2 );


	/**
	 * Function that adds the initial options and settings
	 *
	 */
	function dpsp_default_settings() {

		/*
		 * Add general settings
		 */
		$dpsp_settings = get_option( 'dpsp_settings', array() );

		// Click to Tweet
		if( !isset( $dpsp_settings['shortening_service'] ) )
			$dpsp_settings['shortening_service'] = 'bitly';

		if( !isset( $dpsp_settings['ctt_style'] ) )
			$dpsp_settings['ctt_style'] = 1;

		if( !isset( $dpsp_settings['ctt_link_text'] ) )
			$dpsp_settings['ctt_link_text'] = 'Click to Tweet';

		// Google Analytics UTM tracking
		if( !isset( $dpsp_settings['utm_source'] ) )
			$dpsp_settings['utm_source'] = '{{network_name}}';

		if( !isset( $dpsp_settings['utm_medium'] ) )
			$dpsp_settings['utm_medium'] = 'social';

		if( !isset( $dpsp_settings['utm_campaign'] ) )
			$dpsp_settings['utm_campaign'] = 'social-pug';

		// Update settings
		update_option( 'dpsp_settings', $dpsp_settings );


		/*
		 * Add default settings for each share buttons location
		 */
		$locations = dpsp_get_network_locations();

		foreach( $locations as $location ) {

			$location_settings = get_option( 'dpsp_location_' . $location, array() );

			if( ! empty( $location_settings ) )
				continue;

			// General settings for all locations
			$location_settings = array(
				'networks' 			=> array(),
				'button_style'		=> 1,
				'display' 			=> array( 'shape'	=> 'rectangular', 'size' => 'medium' ),
				'post_type_display' => array( 'post' )
			);

			// Individual settings per location
			switch( $location ) {

				case 'sidebar':
					$location_settings['display']['position'] = 'left';
					$location_settings['display']['icon_animation'] = 'yes';
					break;

				case 'content':
					$location_settings['display']['position'] 	  = 'top';
					$location_settings['display']['column_count'] = 'auto';
					$location_settings['display']['icon_animation'] = 'yes';
					$location_settings['display']['show_labels']  = 'yes';
					break;

				case 'mobile':
					$location_settings['display']['screen_size']  = '720';
					$location_settings['display']['column_count'] = '3';
					break;

				case 'pop_up':
					$location_settings['display']['icon_animation'] = 'yes';
					$location_settings['display']['show_labels']  = 'yes';
					$location_settings['display']['title']   = __( 'Sharing is Caring', 'social-pug' );
					$location_settings['display']['message'] = __( 'Help spread the word. You\'re awesome for doing it!', 'social-pug' );
					break;

				case 'follow_widget':
					$location_settings['display']['show_labels']  = 'yes';
					$location_settings['display']['show_mobile']  = 'yes';
					break;

			}

			// Update option with values
			update_option( 'dpsp_location_' . $location, $location_settings );

		}

	}


	/*
	 * Connects to DevPups to return the status of the serial key
	 *
	 */
	function dpsp_get_serial_key_status( $serial = '' ) {

		// Get serial from settings if the serial is not passed
		if( empty( $serial ) ) {
			$dpsp_settings = get_option( 'dpsp_settings' );
			$serial 	   = ( isset( $dpsp_settings['product_serial'] ) ? $dpsp_settings['product_serial'] : '' );
		}

		if( empty( $serial ) )
			return null;

		// Make request
		$request = wp_remote_get( add_query_arg( array( 'serial' => $serial, 'action' => 'check_serial' ), 'http://updates.devpups.com' ), array( 'timeout' => 30 ) );

		if( is_wp_error( $request ) )
			$request = wp_remote_get( add_query_arg( array( 'serial' => $serial, 'action' => 'check_serial' ), 'http://updates.devpups.com' ), array( 'timeout' => 30, 'sslverify' => false ) );
		

		if( !is_wp_error( $request ) && isset( $request['response']['code'] ) && $request['response']['code'] == 200 ) {
			$serial_status = trim( $request['body'] );

			return $serial_status;
		}

		return null;

	}


	/**
	 * Determines whether to display the buttons for a location by checking if
	 * the post has overwrite display option selected
	 *
	 */
	function dpsp_post_location_overwrite_option( $return, $location_slug, $settings ) {

		$post_obj = dpsp_get_current_post();

		if( ! $post_obj )
			return $return;

		// Pull share options meta data
		$share_options = get_post_meta( $post_obj->ID, 'dpsp_share_options', true );

		if( ! empty( $share_options['locations_overwrite'] ) && is_array( $share_options['locations_overwrite'] ) && in_array( $location_slug, $share_options['locations_overwrite'] ) )
			return false;

		if( ! empty( $share_options['locations_overwrite_show'] ) && is_array( $share_options['locations_overwrite_show'] ) && in_array( $location_slug, $share_options['locations_overwrite_show'] ) )
			return true;

		return $return;

	}
	add_filter( 'dpsp_is_location_displayable', 'dpsp_post_location_overwrite_option', 10, 3 );


	/*
	 * Add a transient to redirect to the Basic Information page after redirect
	 *
	 */
	function dpsp_toolkit_activate() {
		set_transient( '_dpsp_just_activated', true, 60 );
	}
	

	/*
	 * Redirect the admin to the Basic Information page after activating the plugin
	 *
	 */
	function dpsp_toolkit_activate_do_redirect() {

		if( !get_transient( '_dpsp_just_activated' ) )
			return;

		delete_transient( '_dpsp_just_activated' );

		if( is_multisite() )
			return;

		wp_safe_redirect( add_query_arg( array( 'page' => 'dpsp-toolkit' ), admin_url( 'admin.php' ) ) );

	}
	add_action( 'admin_init', 'dpsp_toolkit_activate_do_redirect' );


	/*
	 * Darkens a given color
	 *
	 */
	function dpsp_darken_color( $rgb, $darker ) {

		$hash = (strpos($rgb, '#') !== false) ? '#' : '';
		$rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
		if(strlen($rgb) != 6) return $hash.'000000';
		$darker = ($darker > 1) ? $darker : 1;

		list($R16,$G16,$B16) = str_split($rgb,2);

		$R = sprintf("%02X", floor(hexdec($R16)/$darker));
		$G = sprintf("%02X", floor(hexdec($G16)/$darker));
		$B = sprintf("%02X", floor(hexdec($B16)/$darker));

		return $hash.$R.$G.$B;
	}


	/**
     * Removes the script tags from the values of an array recursivelly
     *
     * @param array $array
     *
     * @return array
     *
     */
    function dpsp_array_strip_script_tags( $array = array() ) {

        if( empty( $array ) || ! is_array( $array ) )
            return array();

        foreach( $array as $key => $value ) {

            if( is_array( $value ) )
                $array[$key] = dpsp_array_strip_script_tags( $value );

            else
                $array[$key] = preg_replace( '@<(script)[^>]*?>.*?</\\1>@si', '', $value );

        }

        return $array;

    }