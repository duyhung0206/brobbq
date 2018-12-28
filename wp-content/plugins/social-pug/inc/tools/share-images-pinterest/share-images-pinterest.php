<?php

	/**
	 * Add the share image on Pinterest tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_share_images_pinterest( $tools = array() ) {

		$tools['share_images'] = array(
			'name' 		 		 => __( 'Image Hover Pinterest Button', 'social-pug' ),
			'type'				 => 'share_tool',
			'img'		 		 => 'assets/img/tool-image-hover-pinterest.png',
			'admin_page' 		 => 'admin.php?page=dpsp-settings&dpsp-tab=pinterest-image-hover'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_share_images_pinterest', 30 );


	/**
	 * Register the image hover Pinterest setting tab in the Settings page
	 *
	 * @param array $tabs
	 *
	 * @return array
	 *
	 */
	function dpsp_tool_share_images_pinterest_settings_tab( $tabs = array() ) {

		if( dpsp_is_tool_active( 'share_images' ) )
			$tabs['pinterest-image-hover'] = __( 'Pinterest Image Hover', 'social-pug' );

		return $tabs;
	}
	add_filter( 'dpsp_submenu_page_settings_tabs', 'dpsp_tool_share_images_pinterest_settings_tab', 20 );


	/**
	 * Adds the settings fields for the Pinterest image hover in the Settings page
	 *
	 * @param array $settings - the settings of the plugin
	 *
	 */
	function dpsp_tool_share_images_pinterest_settings_tab_content( $settings ) {

		dpsp_settings_field( 'select', 'dpsp_settings[share_image_button_position]', ( isset( $settings['share_image_button_position'] ) ? $settings['share_image_button_position'] : '' ), __( 'Button position', 'social-pug' ), array( 'top_left' => __( 'Top Left', 'social-pug' ), 'top_right' => __( 'Top Right', 'social-pug' ), 'bottom_left' => __( 'Bottom Left', 'social-pug' ), 'bottom_right' => __( 'Bottom Right', 'social-pug' ), 'center' => __( 'Center', 'social-pug' ) ) );
		dpsp_settings_field( 'select', 'dpsp_settings[share_image_button_shape]', ( isset($settings['share_image_button_shape']) ? $settings['share_image_button_shape'] : '' ), __( 'Button shape', 'social-pug' ), array( 'rectangular' => __( 'Rectangular', 'social-pug' ), 'rounded' => __( 'Rounded', 'social-pug' ), 'circle' => __( 'Circle', 'social-pug' ) ) );
		dpsp_settings_field( 'text', 'dpsp_settings[share_image_minimum_image_width]', ( isset($settings['share_image_minimum_image_width']) ? $settings['share_image_minimum_image_width'] : '' ), __( 'Minimum image width (pixels)', 'social-pug' ), '200' );
		dpsp_settings_field( 'text', 'dpsp_settings[share_image_minimum_image_height]', ( isset($settings['share_image_minimum_image_height']) ? $settings['share_image_minimum_image_height'] : '' ), __( 'Minimum image height (pixels)', 'social-pug' ), '200' );
		dpsp_settings_field( 'checkbox', 'dpsp_settings[share_image_show_button_text_label]', ( isset($settings['share_image_show_button_text_label']) ? $settings['share_image_show_button_text_label'] : '' ), __( 'Show button label', 'social-pug' ), array('yes') );
		dpsp_settings_field( 'text', 'dpsp_settings[share_image_button_text_label]', ( isset($settings['share_image_button_text_label']) ? $settings['share_image_button_text_label'] : '' ), __( 'Button text label', 'social-pug' ) );
		dpsp_settings_field( 'checkbox', 'dpsp_settings[share_image_show_image_overlay]', ( isset($settings['share_image_show_image_overlay']) ? $settings['share_image_show_image_overlay'] : '' ), __( 'Show image overlay', 'social-pug' ), array('yes') );

	}
	add_action( 'dpsp_submenu_page_settings_tab_pinterest-image-hover', 'dpsp_tool_share_images_pinterest_settings_tab_content', 20 );


	/**
	 * Add the default settings for the image hover Pinterest button on
	 * database update
	 *
	 */
	function dpsp_tool_share_images_pinterest_add_default_settings() {

		$settings = get_option( 'dpsp_settings', array() );

		if( empty( $settings ) )
			return;

		if( ! empty( $settings['share_image_button_position'] ) )
			return;

		$settings['share_image_button_position'] 		= 'top_left';
		$settings['share_image_button_shape'] 			= 'rectangular';
		$settings['share_image_minimum_image_width'] 	= '200';
		$settings['share_image_minimum_image_height'] 	= '200';
		$settings['share_image_show_button_text_label'] = 'yes';
		$settings['share_image_button_text_label'] 		= __( 'Save', 'social-pug' );

		update_option( 'dpsp_settings', $settings );

	}
	add_action( 'dpsp_update_database', 'dpsp_tool_share_images_pinterest_add_default_settings' );


	/**
	 * Adds extra mark-up just after the content so we know when to pop the pop-up
	 *
	 */
	function dpsp_tool_share_images_pinterest_output_content_markup( $content ) {

		if( dpsp_is_tool_active( 'share_images' ) )
			$content .= '<span id="dpsp-post-content-markup"></span>';

		return $content;

	}
	add_filter( 'the_content', 'dpsp_tool_share_images_pinterest_output_content_markup' );


	/**
	 * Outputs the script needed to display properly the Pinterest button on the images
	 *
	 */
	function dpsp_tool_share_images_pinterest_output_needed_script() {
		
		if( ! is_singular( 'post' ) )
			return;

		$settings = get_option( 'dpsp_settings', array() );

		if( empty( $settings ) )
			return;

		$share_image_data = array();

		// Remove any setting that is not related to image hover share
		foreach( $settings as $key => $val ) {

			if( false !== strpos( $key, 'share_image_' ) ) {
				$share_image_data[ str_replace( 'share_image_' , '', $key ) ] = $val;
			}

		}

		if( empty( $share_image_data ) )
			return;

		// Get current post
		$current_post = dpsp_get_current_post();

		if( ! is_null( $current_post ) ) {

			// Add the pinterest description as a fallback
			$pinterest_description = dpsp_get_post_pinterest_description( $current_post->ID );

			if( ! empty( $pinterest_description ) )
				$share_image_data['pinterest_description'] = sanitize_text_field( $pinterest_description );

		}

		$contents = "
			var dpsp_pin_button_data = " . json_encode( $share_image_data ) . "
		";
		
		wp_add_inline_script( 'dpsp-frontend-js', $contents, 'before' );

	}
	add_action( 'wp_enqueue_scripts', 'dpsp_tool_share_images_pinterest_output_needed_script' );


	/**
	 * Add Pin Description field to the Add New Media box callback
	 *
	 * @param array   $form_fields - the current fields
	 * @param WP_Post $post
	 *
	 * @return array
	 *
	 */
	function dpsp_add_attachment_pin_description_field( $form_fields, $post ) {
	    
	    if( false === strpos( $post->post_mime_type, 'image' ) )
	    	return $form_fields;

	    $pin_description = get_post_meta( $post->ID, 'pin_description', true );

	    $form_fields['pin_description'] = array(
	    	'input'	=> 'textarea',
	        'value' => $pin_description ? $pin_description : '',
	        'label' => __( 'Social Pug: Pin Description' )
	    );

	    return $form_fields;

	}
	add_filter( 'attachment_fields_to_edit', 'dpsp_add_attachment_pin_description_field', 10, 2 );


	/**
	 * Save the Pin Description field callback
	 *
	 * @param int $attachment_id
	 *
	 */
	function dpsp_save_attachment_pin_description( $attachment_id ) {

	    if ( isset( $_REQUEST['attachments'][$attachment_id]['pin_description'] ) ) {

	        $pin_description = $_REQUEST['attachments'][$attachment_id]['pin_description'];
	        update_post_meta( $attachment_id, 'pin_description', $pin_description );

	    }

	}
	add_action( 'edit_attachment', 'dpsp_save_attachment_pin_description' );


	/**
	 * Callback to update the image html when sending the image from the Add New Media box to the WP editor
	 *
	 * @param string $html
	 * @param int    $attachment_id
	 *
	 * @return string
	 *
	 */
	function dpsp_image_send_to_editor_pin_description( $html, $attachment_id ) {

		$pin_description = get_post_meta( $attachment_id, 'pin_description', true );

		if( empty( $pin_description ) )
			return $html;

		$doc = DOMDocument::loadHTML( $html );

		foreach( $doc->getElementsByTagName( 'img' ) as $image ) {

			$image->setAttribute( 'data-pin-description', esc_attr( $pin_description ) );

		}

		$html = $doc->saveHTML();

		return $html;

	}
	add_filter( 'image_send_to_editor', 'dpsp_image_send_to_editor_pin_description', 20, 2 );