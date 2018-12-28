<?php

/*
 * Class that handles the output of the social button list items and unordered
 * list wrapper
 *
 */
Class DPSP_Network_Buttons_Outputter {

	private $settings;

	private $action;

	private $location;

	private $post_id = 0;

	private $post_url = '';

	private $post_title = '';

	private $post_description = '';

	private $post_custom_tweet = '';

	private $post_custom_image_pinterest = '';

	private $post_custom_description_pinterest = '';

	private $networks_shares = array();

	private $current_network;

	private $networks_count;


	/*
	 * The constructor
	 *
	 */
	public function __construct( $settings = array(), $action = '', $location = '', $data = array() ) {

		$this->settings = apply_filters( 'dpsp_network_buttons_outputter_settings', $settings, $action, $location );
		$this->action 	= $action;
		$this->location = $location;

		// Set networks shares and post details
		if( $action == 'share' ) {

			$post_obj = dpsp_get_current_post();

			// Get post url and title
			if( !is_admin() ) {

				if( empty( $data['shortcode_url'] ) ) {
					$this->post_id    		= $post_obj->ID;
					$this->post_url   		= dpsp_get_post_url( $post_obj->ID );
					$this->post_title 		= dpsp_get_post_title( $post_obj->ID );
					$this->post_description = dpsp_get_post_description( $post_obj->ID );

				// If a shortcode URL is provided we don't use the post's data
				} else {
					$this->post_url   = $data['shortcode_url'];
					$this->post_title = ( !empty( $data['shortcode_desc'] ) ? $data['shortcode_desc'] : '' );
				}
				

				// Get custom sharable content ( custom tweet, pinterest image and pinterest description )
				$share_options = get_post_meta( $post_obj->ID, 'dpsp_share_options', true );

				if( !empty( $share_options['custom_tweet'] ) )
					$this->custom_tweet = $share_options['custom_tweet'];

				if( !empty( $share_options['custom_image_pinterest']['src'] ) )
					$this->post_custom_image_pinterest = urlencode( esc_url( $share_options['custom_image_pinterest']['src'] ) );

				if( !empty( $share_options['custom_description_pinterest'] ) )
					$this->post_custom_description_pinterest = $share_options['custom_description_pinterest'];

			}

			// Get networks share count for this post
			if( $post_obj )
				$networks_shares = apply_filters( 'dpsp_get_output_post_shares_counts', dpsp_get_post_share_counts( $post_obj->ID ), $location );

			$this->networks_shares = ( !empty( $networks_shares ) ? $networks_shares : array() );
		}

	}


	/*
	 * Returns the buttons with the wrapper
	 *
	 */
	public function get_render() {

		if( empty( $this->settings['networks'] ) )
			return '';

		// Set current network and networks count
		$this->current_network = 1;
		$this->networks_count  = count( $this->settings['networks'] );

		$wrapper_classes = array();
		$wrapper_classes[] = ( isset( $this->settings['display']['column_count'] ) ? 'dpsp-column-' . $this->settings['display']['column_count'] : '' );
		$wrapper_classes[] = ( isset( $this->settings['display']['icon_animation'] ) ? 'dpsp-has-button-icon-animation' : '' );

		$wrapper_classes = implode(' ', $wrapper_classes);

		// Start concatenating the output
		$output = '<ul class="dpsp-networks-btns-wrapper ' . ( ! empty( $this->location ) ? 'dpsp-networks-btns-' . str_replace( '_', '-', $this->location ) : '' ) . ' dpsp-networks-btns-' . ( $this->action ) . ' ' . $wrapper_classes .  '">';

		// Loop throught each network and create the button
		foreach( $this->settings['networks'] as $network_slug => $network ) {
		
			$output .= '<li>';
				$output .= $this->get_button_output( $network_slug, $network );
			$output .= '</li>';

			// Increment network count
			$this->current_network++;

		}

		$output .= '</ul>';

		return $output;
	}


	/*
	 * Returns the output for a single network button
	 *
	 */
	private function get_button_output( $network_slug, $network ) {

		// Get the link of the button
		$network_share_link = $this->get_button_link( $network_slug );

		// Get network shares
		$network_shares 	= $this->get_network_shares( $network_slug );

		// Check to see if the share counts should be displayed
		$show_share_counts  = ( in_array( $network_slug, dpsp_get_networks_with_social_count() ) && isset( $this->settings['display']['show_count'] ) && ( !isset( $this->settings['display']['minimum_count'] ) || empty( $this->settings['display']['minimum_count'] ) || $this->settings['display']['minimum_count'] < dpsp_get_post_total_share_count() ) ? true : false );

		// Make sure at least one share exists
		if( $show_share_counts && $network_shares == 0 )
			$show_share_counts = false;

		// Set button classes
		$button_classes   = array('dpsp-network-btn');
		$button_classes[] = ( isset($network_slug) ? 'dpsp-' . $network_slug : '' );
		$button_classes[] = ( ( empty( $network['label'] ) || !isset( $this->settings['display']['show_labels'] ) ) && ( !in_array( $network_slug, dpsp_get_networks_with_social_count() ) || ! $show_share_counts ) ? 'dpsp-no-label' : '' );
		$button_classes[] = ( $show_share_counts ? 'dpsp-has-count' : '' );
		$button_classes[] = ( $this->current_network == 1 ? 'dpsp-first' : '' );
		$button_classes[] = ( $this->current_network == $this->networks_count ? 'dpsp-last' : '' );
		
		// Filter the button classes
		$button_classes	  = apply_filters( 'dpsp_button_classes', $button_classes, $this->location, $network_shares );
		$button_classes   = array_filter($button_classes);

		if( $network_slug == 'pinterest' && $this->action == 'share' )
			$href_attribute = 'data-href="' . $network_share_link . '"';
		else
			$href_attribute = 'href="' . $network_share_link . '"';


		// Start concatenating the output
		$output = '<a rel="nofollow" ' . $href_attribute . ' class="' . implode( ' ', $button_classes ) . '" ' . ( $this->action == 'follow' ? 'target="_blank"' : '' ) . '>';

			$output .= '<span class="dpsp-network-icon"></span>';

			// Social network label and count wrapper
			$output .= '<span class="dpsp-network-label-wrapper">';

				// Labels output
				if( ( isset( $this->settings['display']['show_labels'] ) || is_admin() ) && $this->location != 'sidebar' )
					$output .= '<span class="dpsp-network-label">' . $network['label'] . '</span>';

				// Social count
				if( $show_share_counts )
					$output .= '<span class="dpsp-network-count">' . $network_shares . '</span>';

			$output .= '</span>';

		$output .= '</a>';

		// Add the label for the floating sidebar
		if( $this->location == 'sidebar' && ! is_admin() && ! empty( $this->settings['display']['show_labels'] ) && ! empty( $network['label'] ) )
			$output .= '<span class="dpsp-button-label">' . $network['label'] . '</span>';

		// Return the output
		return apply_filters( 'dpsp_get_button_output', $output, $network_slug, $this->action, $this->location );

	}


	/*
	 * Returns the link ( href ) of the button
	 *
	 */
	private function get_button_link( $network_slug ) {

		// Set the link for the share button
		if( $this->action == 'share' ) {

			// Get the share link for the admin / front-end
			if( !is_admin() ) {

				$post_image = null;
				$post_title = $this->post_title;
				$post_description = $this->post_description;

				// Replace post title with custom tweet for Twitter
				if( $network_slug == 'twitter' && !empty( $this->custom_tweet ) )
					$post_title = $this->custom_tweet;

				// Replace post title with custom pinterest description
				// and post image with custom image for Pinterest
				elseif( $network_slug == 'pinterest' ) {

					if( !empty( $this->post_custom_description_pinterest ) )
						$post_title = $this->post_custom_description_pinterest;

					if( !empty( $this->post_custom_image_pinterest ) )
						$post_image = $this->post_custom_image_pinterest;

				}

				// Filter values before getting the share links
				$post_url   	  = apply_filters( 'dpsp_get_button_share_link_url', $this->post_url, $this->post_id, $network_slug, $this->location );
				$post_title 	  = apply_filters( 'dpsp_get_button_share_link_title', $post_title, $this->post_id, $network_slug, $this->location );
				$post_description = apply_filters( 'dpsp_get_button_share_link_description', $post_description, $this->post_id, $network_slug, $this->location );
				$post_image 	  = apply_filters( 'dpsp_get_button_share_link_image', $post_image, $this->post_id, $network_slug, $this->location );

				$link = dpsp_get_network_share_link( $network_slug, $post_url, $post_title, $post_description, $post_image );

			} else
				$link = dpsp_get_network_share_link( $network_slug, '#', '', '' );

		// Set the link for a follow button
		} elseif( $this->action == 'follow' )
			$link = dpsp_get_network_follow_link( $network_slug );


		// Return the link
		return $link;

	}


	/*
	 * Returns the shares of a post for a particular network
	 *
	 */
	private function get_network_shares( $network_slug ) {

		$network_shares = ( isset( $this->networks_shares[$network_slug] ) ? $this->networks_shares[$network_slug] : 0 );
		$network_shares = apply_filters( 'dpsp_get_output_post_network_share_count', $network_shares, $this->location );

		return $network_shares;

	}

}