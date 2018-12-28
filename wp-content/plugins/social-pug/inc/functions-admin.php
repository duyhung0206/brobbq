<?php

	/*
	 * Displays the HTML of the plugin admin header
	 *
	 */
	function dpsp_admin_header() {

		$page = ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'dpsp' ) !== false ? trim( $_GET['page'] ) : '' );

		echo '<div class="dpsp-page-header">';
			echo '<span class="dpsp-logo">';
				echo '<span class="dpsp-logo-inner">Social Pug <span>Pro</span> </span>';
				echo '<small class="dpsp-version">v.' . DPSP_VERSION . '</small>';
			echo '</span>';

			echo '<nav>';
				echo '<a href="' . dpsp_get_documentation_link( $page ) . '" target="_blank"><i class="dashicons dashicons-book"></i>Documentation</a>';
			echo '</nav>';
		echo '</div>';

	}


	/*
	 * Returns the link to the docs depending on the page the user is on
	 *
	 */
	function dpsp_get_documentation_link( $page ) {

		$page = str_replace( 'dpsp-', '', $page );

		switch( $page ) {

			case 'sidebar':
				$url = 'http://docs.devpups.com/social-pug/floating-sidebar-settings/';
				break;

			case 'content':
				$url = 'http://docs.devpups.com/social-pug/before-and-after-content-settings/';
				break;

			case 'mobile':
				$url = 'http://docs.devpups.com/social-pug/mobile-sticky-settings/';
				break;

			case 'import-export':
				$url = 'http://docs.devpups.com/social-pug/import-and-export-settings/';
				break;

			default:
				$url = 'http://docs.devpups.com/';
				break;
		}

		return $url;

	}


	/*
	 * Displays the HTML for a given tool
	 * 
	 * @param array $tool
	 *
	 */
	function dpsp_output_tool_box( $tool_slug, $tool ) {

		echo '<div class="dpsp-col-1-4">';
			echo '<div class="dpsp-tool-wrapper">';

				// Tool image
				echo '<img src="' . ( strpos( $tool['img'], 'http' ) === false ? DPSP_PLUGIN_DIR_URL . $tool['img'] : $tool['img'] ) . '" />';

				// Tool name
				echo '<h4 class="dpsp-tool-name">' . $tool['name'] . '</h4>';

				$tool_active = dpsp_is_tool_active( $tool_slug );

				// Tool actions
				echo '<div class="dpsp-tool-actions dpsp-' . ( $tool_active ? 'active' : 'inactive' ) . '">';
				
					// Tool admin page
					echo '<a class="dpsp-tool-settings" href="' . admin_url( $tool['admin_page'] ) . '"><i class="dashicons dashicons-admin-generic"></i>' . __( 'Settings', 'social-pug' ) . '</a>';

					// Tool activation switch
					echo '<div class="dpsp-switch small">';

						echo ( $tool_active ? '<span>' . __( 'Active', 'social-pug' ) . '</span>' : '<span>' . __( 'Inactive', 'social-pug' ) . '</span>' );

						echo '<input id="dpsp-' . $tool_slug . '-active" data-tool="' . esc_attr( $tool_slug ) . '" data-tool-activation="' . esc_attr( !empty( $tool['activation_setting'] ) ? $tool['activation_setting'] : '' ) . '" class="cmn-toggle cmn-toggle-round" type="checkbox" value="1"' . ( $tool_active ? 'checked' : '' ) . ' />';
						echo '<label for="dpsp-' . $tool_slug . '-active"></label>';

					echo '</div>';

				echo '</div>';

			echo '</div>';
		echo '</div>';

	}

	
	/**
	 * Function that displays the HTML for a settings field
	 *
	 */
	function dpsp_settings_field( $type, $name, $saved_value = '', $label = '', $options = array(), $tooltip = '', $editor_settings = array() ) {

		$settings_field_slug = ( !empty($label) ? strtolower(str_replace(' ', '-', $label)) : '' );

		echo '<div class="dpsp-setting-field-wrapper dpsp-setting-field-' . $type . ( is_array( $options ) && count( $options ) == 1 ? ' dpsp-single' : ( is_array( $options ) && count( $options ) > 1 ? ' dpsp-multiple' : '' ) ) . ' ' . ( !empty($label) ? 'dpsp-has-field-label dpsp-setting-field-' . $settings_field_slug : '' ) . '">';

		switch( $type ) {

			// Display input type text
			case 'text':

				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				echo '<input type="text" ' . ( isset( $label ) ? 'id="' . esc_attr( $name ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" value="' . esc_attr( $saved_value ) . '" />';
				break;

			// Display textareas
			case 'textarea':
				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				echo '<textarea ' . ( isset( $label ) ? 'id="' . esc_attr( $name ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '">' . $saved_value . '</textarea>';

				break;

			// Display wp_editors
			case 'editor':
				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				wp_editor( $saved_value, $name, $editor_settings );

				break;

			// Display input type radio
			case 'radio':

				echo !empty( $label ) ? '<label class="dpsp-setting-field-label">' . $label . '</label>' : '';
				
				if( !empty( $options ) ) {
					foreach( $options as $option_value => $option_name ) {
						echo '<input type="radio" id="' . esc_attr( $name ) . '[' . esc_attr( $option_value ) . ']' . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $option_value ) . '" ' . checked( $option_value, $saved_value, false ) . ' />';
						echo '<label for="' . esc_attr( $name ) . '[' . esc_attr( $option_value ) . ']' . '" class="dpsp-settings-field-radio">' . ( isset( $option_name ) ? $option_name : $option_value ) . '<span></span></label>';
					}
				}
				break;

			// Display input type checkbox
			case 'checkbox':
			
				// If no options are passed make the main label as the label for the checkbox
				if( count( $options ) == 1 ) {

					if( is_array( $saved_value ) )
						$saved_value = $saved_value[0];

					echo '<input type="checkbox" ' . ( isset( $label ) ? 'id="' . esc_attr( $name ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" value="' . esc_attr( $options[0] ) . '" ' . checked( $options[0], $saved_value, false ) . ' />';
					echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '<span></span></label>' : '';

				// Else display checkboxes just like radios
				} else {

					echo !empty( $label ) ? '<label class="dpsp-setting-field-label">' . $label . '</label>' : '';

					if( !empty( $options ) ) {
						foreach( $options as $option_value => $option_name ) {
							echo '<input type="checkbox" id="' . esc_attr( $name ) . '[' . esc_attr( $option_value ) . ']' . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $option_value ) . '" ' . ( in_array( $option_value, $saved_value ) ? 'checked' : '' ) . ' />';
							echo '<label for="' . esc_attr( $name ) . '[' . esc_attr( $option_value ) . ']' . '" class="dpsp-settings-field-checkbox">' . ( isset( $option_name ) ? $option_name : $option_value ) . '<span></span></label>';
						}
					}

				}
				break;

			case 'select':

				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';
				echo '<select id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '">';

					foreach( $options as $option_value => $option_name ) {
						echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $saved_value, $option_value, false ) . '>' . $option_name . '</option>';
					}

				echo '</select>';

				break;

			case 'color-picker':
				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				echo '<input class="dpsp-color-picker" type="text" ' . ( isset( $label ) ? 'id="' . esc_attr( $name ) . '"' : '' ) . ' name="' . esc_attr( $name ) . '" value="' . esc_attr( $saved_value ) . '" />';
				break;

			case 'image':
				echo !empty( $label ) ? '<label for="' . esc_attr( $name ) . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				echo '<div>';
					
					if( !empty( $saved_value['id'] ) ) {
						$thumb_details = wp_get_attachment_image_src( $saved_value['id'], 'medium' );
						$image_details = wp_get_attachment_image_src( $saved_value['id'], 'full' );
					}

					if( !empty( $thumb_details[0] ) && !empty( $image_details[0] ) ) {
						$thumb_src   = $thumb_details[0];
						$image_src 	 = $image_details[0];
					} else {
						$thumb_src   = '';
						$image_src 	 = '';
						$saved_value['id'] = '';
					}

					echo '<div>';
						echo '<img src="' . esc_attr( $thumb_src ) . '">';
					echo '</div>';

					echo '<a class="dpsp-image-select button button-primary ' . ( !empty( $saved_value['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Select Image', 'social-pug' ) . '</a>';
					echo '<a class="dpsp-image-remove button button-secondary ' . ( empty( $saved_value['id'] ) ? 'hidden' : '' ) . '" href="#">' . __( 'Remove Image', 'social-pug' ) . '</a>';

					echo '<input class="dpsp-image-id" type="hidden" name="' . esc_attr( $name ) . '[id]" value="' . esc_attr( $saved_value['id'] ) . '" />';
					echo '<input class="dpsp-image-src" type="hidden" name="' . esc_attr( $name ) . '[src]" value="' . esc_attr( $image_src ) . '" />';

				echo '</div>';

				break;

		} // end of switch


		// Tooltip
		if( ! empty( $tooltip ) ) {

			dpsp_output_backend_tooltip( $tooltip );

		}
		
		do_action( 'dpsp_inner_after_settings_field', $settings_field_slug, $type, $name );

		echo '</div>';

	}


	/**
	 * Set the column_count option to 1 when displaying the buttons inside the WP dashboard admin
	 *
	 * @param array $settings 	- the settings array for the current location
	 * @param string $action 	- the current type of action ( share/follow )
	 * @param string $location 	- the display location for the buttons
	 *
	 * @return array
	 *
	 */
	function dpsp_admin_buttons_display_column_count_to_one( $settings, $action, $location ) {

		if( empty( $settings['display']['column_count'] ) )
			return $settings;

		if( ! is_admin() )
			return $settings;

		$settings['display']['column_count'] = 1;

		return $settings;

	}
	add_filter( 'dpsp_network_buttons_outputter_settings', 'dpsp_admin_buttons_display_column_count_to_one', 10, 3 );


	/**
	 * Returns the HTML output with the selectable networks
	 *
	 * @param array $networks - the networks available to be sorted
	 * @param array $settings_networks - the networks saved for the location
	 *
	 */
	function dpsp_output_selectable_networks( $networks = array(), $settings_networks ) {

		$output = '<div id="dpsp-networks-selector-wrapper">';

			$output .= '<ul id="dpsp-networks-selector">';

				if( !empty($networks) ) {
					foreach( $networks as $network_slug => $network_name ) {
						$output .= '<li>';
							$output .= '<div class="dpsp-network-item" data-network="' . $network_slug . '" data-network-name="' . $network_name . '" ' . ( isset( $settings_networks[$network_slug] ) ? 'data-checked="true"' : '' ) . '>';
							$output .= '<div class="dpsp-network-item-checkbox dpsp-icon-ok"></div>';
							$output .= '<div class="dpsp-network-item-name-wrapper dpsp-network-' . $network_slug . '">';
								$output .= '<span class="dpsp-list-icon dpsp-list-icon-social dpsp-icon-' . $network_slug . '"><!-- --></span>';
								$output .= '<h4>' . $network_name . '</h4>';
							$output .= '</div>';
						$output .= '</li>';
					}
				}

			$output .= '</ul>';

			$output .= '<div id="dpsp-networks-selector-footer">';
				$output .= '<a href="#" class="button button-primary">Apply Selection</a>';
			$output .= '</div>';

		$output .= '</div>';

		return $output;
	}


	/*
	 * Returns the HTML output with the sortable networks
	 *
	 */
	function dpsp_output_sortable_networks( $networks, $settings_name ) {

		$output = '<ul class="dpsp-social-platforms-sort-list sortable">';

			if( !empty($networks) ) {
				foreach( $networks as $network_slug => $network ) {

					$output .= '<li data-network="' . $network_slug . '">';

						// The sort handle
						$output .= '<div class="dpsp-sort-handle"><!-- --></div>';

						// The social network icon
						$output .= '<div class="dpsp-list-icon dpsp-list-icon-social dpsp-icon-' . $network_slug . '"><!-- --></div>';

						// The label edit field
						$output .= '<div class="dpsp-list-input-wrapper">';
							$output .= '<input type="text" placeholder="' . __( 'This button has no label text.', 'social-pug' ) . '" name="' . $settings_name . '[networks][' . $network_slug . '][label]" value="' . ( isset( $network['label'] ) ? esc_attr( $network['label'] ) : dpsp_get_network_name( $network_slug ) ) . '" />';
						$output .= '</div>';

						// List item actions
						$output .= '<div class="dpsp-list-actions">';
							$output .= '<a class="dpsp-list-edit-label" href="#"><span class="dashicons dashicons-edit"></span>' . __( 'Edit Label' ) . '</a>';
							$output .= '<a class="dpsp-list-remove" href="#"><span class="dashicons dashicons-no-alt"></span>' . __( 'Remove' ) . '</a>';
						$output .= '</div>';
					$output .= '</li>';

				}
			}

		$output .= '</ul>';

		return $output;
	}


	/*
	 * Outputs the HTML of the tooltip
 	 *
	 * @param string tooltip - the text of the tooltip
	 * @param bool $return 	 - wether to return or to output the HTML
	 *
	 */
	function dpsp_output_backend_tooltip( $tooltip = '', $return = false ) {

		$output = '<div class="dpsp-setting-field-tooltip-wrapper ' . ( ( strpos( $tooltip,  '</a>' ) !== false ) ? 'dpsp-has-link' : '' ) . '">';
			$output .= '<span class="dpsp-setting-field-tooltip-icon"></span>';
			$output .= '<div class="dpsp-setting-field-tooltip dpsp-transition">' . $tooltip . '</div>';
		$output .= '</div>';

		if( $return )
			return $output;
		else
			echo $output;

	}

	/*
	 * Registers an extra column for the shares with all active custom post types
	 *
	 */
	function dpsp_register_custom_post_type_columns() {

	    $active_post_types = dpsp_get_active_post_types();

	    if( !empty( $active_post_types ) ) {
	        foreach( $active_post_types as $post_type ) {
	            add_filter( 'manage_' . $post_type . '_posts_columns', 'dpsp_set_shares_column' );
	            add_filter( 'manage_edit-' . $post_type . '_sortable_columns', 'dpsp_set_shares_column_sortable' );
	            add_action( 'manage_' . $post_type . '_posts_custom_column' , 'dpsp_output_shares_column', 10, 2 );
	        }
	    }

	}
	add_action( 'admin_init', 'dpsp_register_custom_post_type_columns' );


	/**
	 * Adds the Shares column to all active post types
	 *
	 * @param array $columns
	 *
	 * @return array
	 *
	 */
	function dpsp_set_shares_column( $columns ) {

		$column_output = '<span class="dpsp-list-table-shares"><i class="dashicons dashicons-share"></i><span>' . __( 'Shares', 'social-pug' ) . '</span></span>';

	    if( isset( $columns['date'] ) ) {

	        $array = array_slice( $columns, 0, array_search( 'date', array_keys( $columns ) ) );

	        $array['dpsp_shares'] = $column_output;

	        $columns = array_merge( $array, $columns );

	    } else {
	        $columns['dpsp_shares'] = $column_output;
	    }

	    return $columns;
	}


	/**
	 * Defines the total shares column as sortable
	 *
	 * @param array $columns
	 *
	 * @return array
	 *
	 */
	function dpsp_set_shares_column_sortable( $columns ) {
		
		$columns['dpsp_shares'] = 'dpsp_shares';

		return $columns;
	}


	/**
	 * Outputs the share counts in the Shares columns
	 *
	 * @param string $column_name
	 * @param int $post_id
	 *
	 */
	function dpsp_output_shares_column( $column_name, $post_id ) {

	    if( $column_name == 'dpsp_shares' ) {

	        echo  '<span class="dpsp-list-table-post-share-count">' . dpsp_get_post_total_share_count( $post_id ) . '</span>';

	    }

	}


	/**
	 * Check to see if the user selected to order the posts by share counts and
	 * changes the query accordingly
	 *
	 * @param WP_Query $query
	 *
	 */
	function dpsp_pre_get_posts_shares_query( $query ) {

		if( ! is_admin() )
			return;

		$orderby = $query->get( 'orderby' );

		if( $orderby == 'dpsp_shares' ) {
			$query->set( 'meta_key', 'dpsp_networks_shares_total' );
			$query->set( 'orderby', 'meta_value_num' );
		}

	}
	add_action( 'pre_get_posts', 'dpsp_pre_get_posts_shares_query' );


	/**
	 * Makes a call to Facebook to scrape the post's Open Graph data after the post has been saved
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 *
	 */
	function dpsp_save_post_facebook_scrape_url( $post_id, $post ) {

		if( ! is_admin() )
			return;

		$not_allowed_post_statuses = array( 'draft', 'auto-draft', 'future', 'pending', 'trash' );

		if( in_array( $post->post_status, $not_allowed_post_statuses ) )
			return;

		$post_url = get_permalink( $post );
		$post_url = urlencode( $post_url );

		$url = add_query_arg( array( 'id' => $post_url, 'scrape' => 'true' ), 'https://graph.facebook.com/' );

		$response = wp_remote_post( $url );
		
	}
	add_action( 'save_post', 'dpsp_save_post_facebook_scrape_url', 99, 2 );


	/*
	 * Display admin notices for our pages
	 *
	 */
	function dpsp_admin_notices() {

		// Exit if settings updated is not present
		if( !isset( $_GET['settings-updated'] ) )
			return;

		$admin_page = ( isset( $_GET['page'] ) ? $_GET['page'] : '' );

		// Show these notices only on dpsp pages
		if( strpos( $admin_page, 'dpsp' ) === false || $admin_page == 'dpsp-register-version' )
			return;

		// Get messages
		$message_id = ( isset( $_GET['dpsp_message_id'] ) ? $_GET['dpsp_message_id'] : 0 );
		$message 	= dpsp_get_admin_notice_message( $message_id );

		$class = ( isset( $_GET['dpsp_message_class'] ) ? $_GET['dpsp_message_class'] : 'updated' );;

		if( isset( $message ) ) {

			echo '<div class="dpsp-admin-notice notice is-dismissible ' . esc_attr( $class ) . '">';
	        	echo '<p>' . esc_attr( $message ) . '</p>';
	        echo '</div>';
		}

	}
	add_action( 'admin_notices', 'dpsp_admin_notices' );


	/**
	 * Returns a human readable message given a message id
	 *
	 * @param int $message_id
	 *
	 */
	function dpsp_get_admin_notice_message( $message_id ) {

		$messages = apply_filters( 'dpsp_get_admin_notice_message', array(
			__( 'Settings saved.', 'social-pug' ),
			__( 'Settings imported.', 'social-pug' ),
			__( 'Please select an import file.', 'social-pug' ),
			__( 'Import file is not valid.', 'social-pug' )
		));

		return $messages[ $message_id ];
	}
	

	/*
	 * Adds admin notifications for entering the license serial key
	 *
	 */
	function dpsp_serial_admin_notification() {

		if( !current_user_can( 'manage_options' ) )
			return;

		$dpsp_settings = get_option( 'dpsp_settings' );

		$serial = ( !empty( $dpsp_settings['product_serial'] ) ? $dpsp_settings['product_serial'] : '' );

		// Check to see if serial is saved in the database
		if( empty( $serial ) ) {

			$notice_classes = 'dpsp-serial-missing';
			$message 		= sprintf( __( 'Your <strong>Social Pug</strong> license serial key is empty. Please <a href="%1$s">register your copy</a> to receive automatic updates and support. <br /><br /> Need a license key? <a class="dpsp-get-license button button-primary" target="_blank" href="%2$s">Get your license here</a>', 'social-pug' ), admin_url( 'admin.php?page=dpsp-settings' ), 'http://www.devpups.com/' );

		} 

		/*
		else {

			// Check if serial is valid
			$serial_status = get_option( 'dpsp_product_serial_status' );

			if( $serial_status === false || $serial_status == -1 ) {

				$notice_classes = 'error';
				$message 		= sprintf( __( 'The serial you have provided for <strong>Social Pug</strong> is invalid. To receive automatic updates and support please enter a valid license key. <br /><br /> <a class="dpsp-get-license button button-primary" target="_blank" href="%1$s">Get your license here</a>', 'social-pug' ), 'http://www.devpups.com/' );

			} elseif( $serial_status == 0 ) {

				$notice_classes = 'error';
				$message 		= sprintf( __( 'Your <strong>Social Pug</strong> serial key has expired. Please <a href="%1$s" target="_blank">renew your license</a> to receive automatic updates and support. <br /><br /> <a class="dpsp-get-license button button-primary" target="_blank" href="%1$s">Renew your license</a>', 'social-pug' ), 'https://www.devpups.com/social-pug/features' );

			} elseif( $serial_status == 2 ) {

				if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_renew_1', true ) == '' ) {

					$notice_classes = 'notice-warning';
					$message 		= sprintf( __( 'Your <strong>Social Pug</strong> serial key is about to expire. Please <a href="%1$s">renew your license</a> to receive automatic updates and support. <br /><br /> <a class="dpsp-get-license button button-primary" target="_blank" href="%1$s">Renew your license</a>', 'social-pug' ), 'https://www.devpups.com/social-pug/features' );
					$extra_content  = '<a href="' . add_query_arg( array( 'dpsp_admin_notice_renew_1' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>';

				}

			}

		}
		*/

		// Display the notice if notice classes have been added
		if( isset( $notice_classes ) ) {
			echo '<div class="dpsp-admin-notice notice ' . $notice_classes . '">';
	        	echo '<p>' . $message .  '</p>';

	        	if( isset($extra_content) )
	        		echo $extra_content;

	        echo '</div>';
		}

	}
	add_action( 'admin_notices', 'dpsp_serial_admin_notification' );


	/**
	 * Add admin notice to anounce the removal of StumbleUpon
	 *
	 */
	function dpsp_admin_notice_stumbleupon_removal() {

		// Do not display this notice if user cannot activate plugins
		if( ! current_user_can( 'activate_plugins' ) )
			return;

		// Don't show this if the plugin has been activated after 29th of July 2018
		$first_activation = get_option( 'dpsp_first_activation', '' );

		if( empty( $first_activation ) )
			return;

		if( $first_activation > strtotime( '2018-07-29 00:00:00' ) )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_stumbleupon_removal', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice notice notice-error">';

			echo '<h4>' . __( 'Social Pug Important Notification', 'social-pug' ) . '</h4>';

        	echo '<p>' . sprintf( __( 'As mentioned in our email notification, StumbleUpon no longer exists. They have started a new venture, namely Mix, and all support for StumbleUpon has been stopped. You can read more about it %shere%s.', 'social-pug' ), '<a href="https://community.mix.com/blog/2018/7/2/note-from-stumbleupon-thank-you-for-being-part-of-the-magic" target="_blank">', '</a>' ) . '</p>';

        	echo '<p>' . __( 'Considering this, Social Pug removes the StumbleUpon button. If you are having display issues for a social share tool, re-saving the settings for that particular share tool should fix the problem.', 'social-pug' ) . '</p>';

        	echo '<p>' . __( 'For the time being Mix does not offer any social sharing API to replace StumbleUpon. If they will start offering such functionality, we will look into adding it to Social Pug.', 'social-pug' ) . '</p>';

        	echo '<p><a href="' . add_query_arg( array( 'dpsp_admin_notice_stumbleupon_removal' => 1 ) ) . '">' . __( 'Thank you, I understand.', 'social-pug' ) . '</a></p>';

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_stumbleupon_removal' );


	/**
	 * Add admin notice to anounce the removal of NewShareCounts and the addition of OpenShareCount
	 *
	 */
	function dpsp_admin_notice_opensharecount() {

		// Do not display this notice if user cannot activate plugins
		if( ! current_user_can( 'activate_plugins' ) )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_opensharecount', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice notice notice-error">';

			echo '<h4>' . __( 'Social Pug Important Notification', 'social-pug' ) . '</h4>';

        	echo '<p>' . __( 'NewShareCounts, the third party Twitter share counts platform we have partnered a while ago to bring back tweet counts, has unfortunately closed.', 'social-pug' ) . '</p>';

        	echo '<p>' . sprintf( __( 'To help save your Twitter share counts, we have integrated Social Pug with another third party option, namely OpenShareCount. If you were using NewShareCounts and wish to take advantage of the new integration you will have to register your website with OpenShareCount. %sTo do this click here%s.', 'social-pug' ), '<a href="http://opensharecount.com/" target="_blank">', '</a>' ) . '</p>';

        	echo '<p><a href="' . add_query_arg( array( 'dpsp_admin_notice_opensharecount' => 1 ) ) . '">' . __( 'Thank you, I understand.', 'social-pug' ) . '</a></p>';

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_opensharecount' );


	/*
	 * Add admin notice to anounce Icon Animations
	 *
	 */
	function dpsp_admin_notice_button_icon_animation() {

		// Do not display this notice if user cannot activate plugins
		if( ! current_user_can( 'activate_plugins' ) )
			return;

		// Do not display this notice if the icon animation has already been activated
		if( get_option( 'dpsp_admin_notice_activate_button_icon_animation', '' ) != '' )
			return;

		if( get_option( 'dpsp_admin_notice_dismiss_button_icon_animation', '' ) != '' )
			return;

		// Check to see if the icon animation is enabled somewhere, if it is don't show the notice
		$locations 		   = array( 'sidebar', 'content' );
		$animation_enabled = false;

		foreach( $locations as $location_slug ) {

			$location_settings = dpsp_get_location_settings( $location_slug );

			if( ! empty( $location_settings['display']['icon_animation'] ) ) {
				$animation_enabled = true;
				update_option( 'dpsp_admin_notice_activate_button_icon_animation', 1 );
			}

		}

		if( true === $animation_enabled )
			return;


		// Echo the admin notice
		echo '<div class="dpsp-admin-notice dpsp-admin-notice-icon-animation notice notice-info">';

        	echo '<h4>' . __( 'Make your social share buttons stand out even more!', 'social-pug' ) . '</h4>';

        	echo '<p>' . __( "We've introduced the simplest icon animation that will make the biggest difference for your users.", 'social-pug' ) . '</p>';

        	echo '<a class="button-primary" href="' . add_query_arg( array( 'dpsp_admin_notice_activate_button_icon_animation' => 1 ) ) . '">' . __( 'Activate Icon Animation', 'social-pug' ) . '</a>';

        	echo '<a href="' . add_query_arg( array( 'dpsp_admin_notice_dismiss_button_icon_animation' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>'; 

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_button_icon_animation' );


	/**
	 * Adds a success notice for the admin to let him/her know that the icon animation has been
	 * activated successfuly
	 *
	 */
	function dpsp_admin_notice_activate_button_icon_animation_done() {

		// Do not display this notice if user cannot activate plugins
		if( ! current_user_can( 'activate_plugins' ) )
			return;

		// Show the admin notice only if the animation activation was successful
		if( empty( $_GET['dpsp_admin_notice_activate_button_icon_animation_done'] ) )
			return;

		echo '<div class="notice notice-success">';

			echo '<p>' . __( "Icon animation activated successfully! Please clear your website's cache if the buttons don't display properly.", 'social-pug' ) . '</p>';

		echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_activate_button_icon_animation_done' );


	/**
	 * Activates the icon animation for tools that support this feature
	 *
	 */
	function dpsp_admin_notice_activate_button_icon_animation() {

		if( empty( $_GET['dpsp_admin_notice_activate_button_icon_animation'] ) )
			return;

		$activated = get_option( 'dpsp_admin_notice_activate_button_icon_animation', '' );

		if( ! empty( $activated ) )
			return;

		// Get all locations
		$locations = dpsp_get_network_locations();

		// Go through each location and update the icon animation setting
		foreach( $locations as $location_slug ) {

			// Get location settings
			$location_settings = dpsp_get_location_settings( $location_slug );

			if( empty( $location_settings ) )
				continue;

			$location_settings['display']['icon_animation'] = 'yes';

			// Update option with values
			update_option( 'dpsp_location_' . $location_slug, $location_settings );

		}

		// The activation process has been done
		update_option( 'dpsp_admin_notice_activate_button_icon_animation', 1 );

		wp_redirect( add_query_arg( array( 'dpsp_admin_notice_activate_button_icon_animation_done' => 1 ) ) );

	}
	add_action( 'admin_init', 'dpsp_admin_notice_activate_button_icon_animation' );


	/**
	 * Adds a notice to let users know about version 2.4
	 *
	 */
	function dpsp_admin_notice_launch_version_2_4() {

		// Do not display this notice if user cannot activate plugins
		if( ! current_user_can( 'activate_plugins' ) )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_launch_version_2_4', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice notice notice-info">';

        	echo '<h4>' . __( "You have just updated Social Pug to version 2.4.", 'social-pug' ) . '</h4>';

        	echo '<p>' . __( "Social Pug version 2.4 comes with some big changes to help you grow your social media reach.", 'social-pug' ) . '</p>';

        	echo '<p><a class="button-primary" target="_blank" href="https://devpups.com/blog/social-pug-2-4-new-social-sharing-tools-to-boost-your-social-media-reach/">' . __( "See what's new!", 'social-pug' ) . '</a></p>';

        	echo '<a href="' . add_query_arg( array( 'dpsp_admin_notice_launch_version_2_4' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>'; 

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_launch_version_2_4' );


	/*
	 * Handle admin notices dismissals
	 *
	 */
	function dpsp_admin_notice_dismiss() {

		if( isset( $_GET['dpsp_admin_notice_twitter_counts'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_twitter_counts', 1, true );

		if( isset( $_GET['dpsp_admin_notice_renew_1'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_renew_1', 1, true );

		if( isset( $_GET['dpsp_admin_notice_dismiss_button_icon_animation'] ) )
			update_option( 'dpsp_admin_notice_dismiss_button_icon_animation', 1 );

		if( isset( $_GET['dpsp_admin_notice_stumbleupon_removal'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_stumbleupon_removal', 1, true );

		if( isset( $_GET['dpsp_admin_notice_opensharecount'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_opensharecount', 1, true );

		if( isset( $_GET['dpsp_admin_notice_launch_version_2_4'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_launch_version_2_4', 1, true );

	}
	add_action( 'admin_init', 'dpsp_admin_notice_dismiss' );


	/*
	 * Remove dpsp query args from the URL
	 *
	 * @param array $removable_query_args 	- the args that WP will remove
	 *
	 */
	function dpsp_removable_query_args( $removable_query_args ) {

		$new_args = array( 'dpsp_message_id', 'dpsp_message_class', 'dpsp_admin_notice_dismiss_button_icon_animation', 'dpsp_admin_notice_activate_button_icon_animation', 'dpsp_admin_notice_activate_button_icon_animation_done' );

		return array_merge( $new_args, $removable_query_args );

	}
	add_filter( 'removable_query_args', 'dpsp_removable_query_args' );