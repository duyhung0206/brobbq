<?php
/*
 * Meta-boxes file
 *
 */


	/*
	 * Individual posts share statistics meta-box
	 *
	 */
	function dpsp_meta_boxes() {
		
		$screens = get_post_types( array( 'public' => true ) );

		if( empty( $screens ) )
			return;

		foreach( $screens as $screen ) {
			// Share option meta-box
			add_meta_box( 'dpsp_share_options', __( 'Share Options', 'social-pug' ), 'dpsp_share_options_output', $screen, 'normal', 'core' );
			// Share statistics meta-box
			add_meta_box( 'dpsp_share_statistics', __( 'Share Statistics', 'social-pug' ), 'dpsp_share_statistics_output', $screen, 'normal', 'core' );
		}

	}
	add_action( 'add_meta_boxes', 'dpsp_meta_boxes' );


	/*
	 * Callback for the Share Options meta box
	 *
	 */
	function dpsp_share_options_output( $post ) {

		// Pull share options meta data
		$share_options = get_post_meta( $post->ID, 'dpsp_share_options', true );

		if( empty( $share_options ) )
			$share_options = array();

		// Nonce field
		wp_nonce_field( 'dpsp_meta_box', 'dpsptkn' );

		/**
		 * New version
		 *
		 */
		echo '<div id="dpsp_share_options_content">';

			// General social media content
			echo '<div class="dpsp-section">';

				// Social media image
				echo '<div class="dpsp-setting-field-wrapper dpsp-setting-field-image">';
					echo '<label for="dpsp_share_options[custom_image]">' . '<span class="dpsp-icon-share"></span>' . __( 'Social Media Image', 'social-pug' );
						echo dpsp_output_backend_tooltip( __( 'Add an image that will populate the "og:image" Open Graph meta tag. For maximum exposure on Facebook, Google+ or LinkedIn we recommend an image size of 1200px X 630px.', 'social-pug' ) );
					echo '</label>';
					echo '<div>';

						$thumb_details = array();
						$image_details = array();
						
						if( ! empty( $share_options['custom_image']['id'] ) ) {
							$thumb_details = wp_get_attachment_image_src( $share_options['custom_image']['id'], 'medium' );
							$image_details = wp_get_attachment_image_src( $share_options['custom_image']['id'], 'full' );
						}

						if( !empty( $thumb_details[0] ) && !empty( $image_details[0] ) ) {
							$thumb_src   = $thumb_details[0];
							$image_src 	 = $image_details[0];
						} else {
							$thumb_src   = DPSP_PLUGIN_DIR_URL . 'assets/img/custom-social-media-image.png';
							$image_src 	 = '';
							$share_options['custom_image']['id'] = '';
						}

						echo '<div>';
							echo '<img src="' . esc_attr( $thumb_src ) . '">';
							echo '<span class="dpsp-field-image-placeholder" data-src="' . DPSP_PLUGIN_DIR_URL . 'assets/img/custom-social-media-image.png"></span>';
						echo '</div>';

						echo '<a class="dpsp-image-select button button-primary ' . ( !empty( $share_options['custom_image']['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Select Image', 'social-pug' ) . '</a>';
						echo '<a class="dpsp-image-remove button button-secondary ' . ( empty( $share_options['custom_image']['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Remove Image', 'social-pug' ) . '</a>';

						echo '<input class="dpsp-image-id" type="hidden" name="dpsp_share_options[custom_image][id]" value="' . esc_attr( $share_options['custom_image']['id'] ) . '" />';
						echo '<input class="dpsp-image-src" type="hidden" name="dpsp_share_options[custom_image][src]" value="' . esc_attr( $image_src ) . '" />';

					echo '</div>';
				echo '</div>';

				// Social media title
				echo '<div class="dpsp-setting-field-wrapper">';

					$maximum_count 	 = 70;
					$current_count 	 = ( ! empty( $share_options['custom_title'] ) ? strlen( wp_kses_post( $share_options['custom_title'] ) ) : 0 );
					$remaining_count = $maximum_count - $current_count;

					echo '<label for="dpsp_share_options[custom_title]">' . '<span class="dpsp-icon-share"></span>' . __( 'Social Media Title', 'social-pug' ) . '<span class="dpsp-textarea-characters-remaining-wrapper" data-maximum-count="' . $maximum_count . '"><span class="dpsp-textarea-characters-remaining">' . $remaining_count . '</span> ' . __( 'Characters Remaining', 'social-pug' ) . '</span>';
						echo dpsp_output_backend_tooltip( __( 'Add a title that will populate the "og:title" Open Graph meta tag. This will be used when users share your content on Facebook, Google+ or LinkedIn. The title of the post will be used if this field is empty.', 'social-pug' ) );
					echo '</label>';
					echo '<textarea id="dpsp_share_options[custom_title]" name="dpsp_share_options[custom_title]" placeholder="' . __( 'Write a social media title...', 'social-pug' ) . '">' . ( isset( $share_options['custom_title'] ) ? wp_kses_post( $share_options['custom_title'] ) : '' ) . '</textarea>';
				echo '</div>';

				// Social media description
				echo '<div class="dpsp-setting-field-wrapper">';

					$maximum_count 	 = 200;
					$current_count 	 = ( ! empty( $share_options['custom_description'] ) ? strlen( wp_kses_post( $share_options['custom_description'] ) ) : 0 );
					$remaining_count = $maximum_count - $current_count;

					echo '<label for="dpsp_share_options[custom_description]">' . '<span class="dpsp-icon-share"></span>' . __( 'Social Media Description', 'social-pug' ) . '<span class="dpsp-textarea-characters-remaining-wrapper" data-maximum-count="' . $maximum_count . '"><span class="dpsp-textarea-characters-remaining">' . $remaining_count . '</span> ' . __( 'Characters Remaining', 'social-pug' ) . '</span>';
						echo dpsp_output_backend_tooltip( __( 'Add a description that will populate the "og:description" Open Graph meta tag. This will be used when users share your content on Facebook, Google+ or LinkedIn.', 'social-pug' ) );
					echo '</label>';
					echo '<textarea id="dpsp_share_options[custom_description]" name="dpsp_share_options[custom_description]" placeholder="' . __( 'Write a social media description...', 'social-pug' ) . '">' . ( isset( $share_options['custom_description'] ) ? wp_kses_post( $share_options['custom_description'] ) : '' ) . '</textarea>';
				echo '</div>';

			echo '</div>';


			// Individual networks social media content
			echo '<div class="dpsp-section">';

				// Pinterest image
				echo '<div class="dpsp-setting-field-wrapper dpsp-setting-field-image">';
					echo '<label for="dpsp_share_options[custom_image_pinterest]">' . '<span class="dpsp-icon-pinterest"></span>' . __( 'Pinterest Image', 'social-pug' );
						echo dpsp_output_backend_tooltip( __( 'Add an image that will be used when this post is shared on Pinterest. For maximum exposure we recommend using an image that has a 2:3 aspect ratio, for example 800px X 1200px.', 'social-pug' ) );
					echo '</label>';
					echo '<div>';
						
						$thumb_details = array();
						$image_details = array();

						if( ! empty( $share_options['custom_image_pinterest']['id'] ) ) {
							$thumb_details = wp_get_attachment_image_src( $share_options['custom_image_pinterest']['id'], 'medium' );
							$image_details = wp_get_attachment_image_src( $share_options['custom_image_pinterest']['id'], 'full' );
						}

						if( !empty( $thumb_details[0] ) && !empty( $image_details[0] ) ) {
							$thumb_src   = $thumb_details[0];
							$image_src 	 = $image_details[0];
						} else {
							$thumb_src   = DPSP_PLUGIN_DIR_URL . 'assets/img/custom-social-media-image-pinterest.png';
							$image_src 	 = '';
							$share_options['custom_image_pinterest']['id'] = '';
						}

						echo '<div>';
							echo '<img src="' . esc_attr( $thumb_src ) . '">';
							echo '<span class="dpsp-field-image-placeholder" data-src="' . DPSP_PLUGIN_DIR_URL . 'assets/img/custom-social-media-image-pinterest.png"></span>';
						echo '</div>';

						echo '<a class="dpsp-image-select button button-primary ' . ( !empty( $share_options['custom_image_pinterest']['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Select Image', 'social-pug' ) . '</a>';
						echo '<a class="dpsp-image-remove button button-secondary ' . ( empty( $share_options['custom_image_pinterest']['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Remove Image', 'social-pug' ) . '</a>';

						echo '<input class="dpsp-image-id" type="hidden" name="dpsp_share_options[custom_image_pinterest][id]" value="' . esc_attr( $share_options['custom_image_pinterest']['id'] ) . '" />';
						echo '<input class="dpsp-image-src" type="hidden" name="dpsp_share_options[custom_image_pinterest][src]" value="' . esc_attr( $image_src ) . '" />';

					echo '</div>';
				echo '</div>';

				// Pinterest description
				echo '<div class="dpsp-setting-field-wrapper">';
					echo '<label for="dpsp_share_options[custom_description_pinterest]">' . '<span class="dpsp-icon-pinterest"></span>' . __( 'Pinterest Description', 'social-pug' );
						echo dpsp_output_backend_tooltip( __( 'Add a customized message that will be used when this post is shared on Pinterest.', 'social-pug' ) );
					echo '</label>';
					echo '<textarea id="dpsp_share_options[custom_description_pinterest]" name="dpsp_share_options[custom_description_pinterest]" placeholder="' . __( 'Write a custom Pinterest description...', 'social-pug' ) . '">' . ( isset( $share_options['custom_description_pinterest'] ) ? wp_kses_post( $share_options['custom_description_pinterest'] ) : '' ) . '</textarea>';
				echo '</div>';

				// Twitter custom tweet
				echo '<div class="dpsp-setting-field-wrapper">';

					$maximum_count 	 = apply_filters( 'dpsp_tweet_maximum_count', 280 ) - 23; /* 23 is the length of the post's link */
					$current_count 	 = ( ! empty( $share_options['custom_tweet'] ) ? strlen( wp_kses_post( $share_options['custom_tweet'] ) ) : 0 );
					$remaining_count = $maximum_count - $current_count;

					echo '<label for="dpsp_share_options[custom_tweet]">' . '<span class="dpsp-icon-twitter"></span>' . __( 'Custom Tweet', 'social-pug' ) . '<span class="dpsp-textarea-characters-remaining-wrapper" data-maximum-count="' . $maximum_count . '"><span class="dpsp-textarea-characters-remaining">' . $remaining_count . '</span> ' . __( 'Characters Remaining', 'social-pug' ) . '</span>';
						echo dpsp_output_backend_tooltip( __( 'Add a customized tweet that will be used when this post is shared on Twitter.', 'social-pug' ) );
					echo '</label>';
					echo '<textarea id="dpsp_share_options[custom_tweet]" name="dpsp_share_options[custom_tweet]" placeholder="' . __( 'Write a custom tweet...', 'social-pug' ) . '">' . ( isset( $share_options['custom_tweet'] ) ? wp_kses_post( $share_options['custom_tweet'] ) : '' ) . '</textarea>';
					echo '<p class="description">' . __( 'Given that the post URL is added to the tweet by default the maximum number of characters for the tweet is 257.', 'social-pug' ) . '</p>';
				echo '</div>';

			echo '</div>';

		echo '</div>';

		// Overwrite options
		echo '<h4 class="dpsp-section-title">' . __( 'Display Options', 'social-pug' ) . '</h4>';
		echo '<div>';
			dpsp_settings_field( 'checkbox', 'dpsp_share_options[locations_overwrite][]', ( isset( $share_options['locations_overwrite']) ? $share_options['locations_overwrite'] : array() ), __( 'Hide buttons for the', 'social-pug' ), dpsp_get_network_locations( 'all', false ) );
		echo '</div>';
		echo '<div>';
			dpsp_settings_field( 'checkbox', 'dpsp_share_options[locations_overwrite_show][]', ( isset( $share_options['locations_overwrite_show']) ? $share_options['locations_overwrite_show'] : array() ), __( 'Show buttons for the', 'social-pug' ), dpsp_get_network_locations( 'all', false ) );
		echo '</div>';

	}


	/*
	 * Callback for the share statistics meta-box
	 *
	 */
	function dpsp_share_statistics_output( $post ) {

		$networks = dpsp_get_active_networks();

		if( !empty( $networks ) ) {

			echo '<div class="dpsp-statistic-bars-wrapper">';

			$networks_shares = get_post_meta( $post->ID, 'dpsp_networks_shares', true );
			$networks_shares = ( !empty( $networks_shares ) ? $networks_shares : array() );

			// Get total share counts
			$total_shares = dpsp_get_post_total_share_count( $post->ID );

			// Shares header
			echo '<div class="dpsp-statistic-bar-wrapper dpsp-statistic-bar-header">';
				echo '<label>' . __( 'Network', 'social-pug' ) . '</label>';
				echo '<div class="dpsp-network-share-count"><span class="dpsp-count">' . __( 'Shares', 'social-pug' ) . '</span><span class="dpsp-divider">|</span><span class="dpsp-percentage">%</span></div>';
			echo '</div>';

			// Actual shares per network
			foreach( $networks as $network_slug ) {

				// Jump to the next one if the network by some chance does not support
				// share count
				if( !in_array( $network_slug, dpsp_get_networks_with_social_count() ) )
					continue;

				// Get current network social share count
				$network_shares = ( isset($networks_shares[$network_slug]) ? $networks_shares[$network_slug] : 0 );

				// Get the percentage of the total shares for current network
				$share_percentage = ( $total_shares != 0 ? (float)($network_shares / $total_shares * 100) : 0 );

				echo '<div class="dpsp-statistic-bar-wrapper dpsp-statistic-bar-wrapper-network">';
					echo '<label>' . dpsp_get_network_name( $network_slug ) . '</label>';

					echo '<div class="dpsp-statistic-bar dpsp-statistic-bar-' . $network_slug . '">';
						echo '<div class="dpsp-statistic-bar-inner" style="width:' . round( $share_percentage, 1 ) . '%"></div>';
					echo '</div>';

					echo '<div class="dpsp-network-share-count"><span class="dpsp-count">' . $network_shares . '</span><span class="dpsp-divider">|</span><span class="dpsp-percentage">' . round( $share_percentage, 2 ) . '</span></div>';
				echo '</div>';

			}

			// Shares footer with total count
			echo '<div class="dpsp-statistic-bar-wrapper dpsp-statistic-bar-footer">';
				echo '<label>' . __( 'Total shares', 'social-pug' ) . '</label>';
				echo '<div class="dpsp-network-share-count"><span class="dpsp-count">' . $total_shares . '</span></div>';
			echo '</div>';

			// Refresh counts button
			echo '<div id="dpsp-refresh-share-counts-wrapper">';
				echo '<a id="dpsp-refresh-share-counts" class="button-secondary" href="#">' . __( 'Refresh shares', 'social-pug' ) . '</a>';
				echo '<span class="spinner"></span>';
				echo wp_nonce_field( 'dpsp_refresh_share_counts', 'dpsp_refresh_share_counts', false, false );
			echo '</div>';

			echo '</div>';

		}
		
	}


	/**
	 * Ajax callback action that refreshes the social counts for the "Share Statistics"
	 * meta-box from each single edit post admin screen
	 *
	 */
	function dpsp_refresh_share_counts() {

		if( empty( $_POST['action'] ) || empty( $_POST['nonce'] ) || empty( $_POST['post_id'] ) )
			return;

		if( $_POST['action'] != 'dpsp_refresh_share_counts' )
			return;

		if( ! wp_verify_nonce( $_POST['nonce'], 'dpsp_refresh_share_counts' ) )
			return;

		$post_id = (int)$_POST['post_id'];

		// Flush existing shares before pulling a new set
		update_post_meta( $post_id, 'dpsp_networks_shares', '' );

		// Get social shares from the networks
		$share_counts   = dpsp_pull_post_share_counts( $post_id );

		// Update share counts in the db
		$shares_updated = dpsp_update_post_share_counts( $post_id, $share_counts );

		// Echos the share statistics 
		dpsp_share_statistics_output( get_post( $post_id ) );

		wp_die();

	}
	add_action( 'wp_ajax_dpsp_refresh_share_counts', 'dpsp_refresh_share_counts' );


	/*
	 * Save meta data for Social Pug meta boxes
	 *
	 */
	function dpsp_save_post_meta( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['dpsptkn'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['dpsptkn'], 'dpsp_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/*
		 * Clear cached shortened links
		 */
		delete_post_meta( $post_id, 'dpsp_short_link_bitly' );


		/*
		 * Save information for the Share Options metabox
		 */
		if( isset( $_POST['dpsp_share_options'] ) )
			$share_options = $_POST['dpsp_share_options'];
		else
			$share_options = '';

		update_post_meta( $post_id, 'dpsp_share_options', $share_options );

	}
	add_action( 'save_post', 'dpsp_save_post_meta' );