<?php 
	/*
	 * Function that creates the sub-menu item and page for the pop-up tool of the share buttons
	 *
	 * @return void
	 *
	 */
	function dpsp_register_pop_up_subpage() {
		add_submenu_page( 'dpsp-social-pug', __('Pop-Up', 'social-pug'), __('Pop-Up', 'social-pug'), 'manage_options', 'dpsp-pop-up', 'dpsp_pop_up_subpage' );
	}
	add_action( 'admin_menu', 'dpsp_register_pop_up_subpage', 25 );


	/*
	 * Function that adds content to the pop-up icons subpage
	 *
	 * @return string
	 *
	 */
	function dpsp_pop_up_subpage() {

		include_once 'views/view-submenu-page-pop-up.php';

	}


	function dpsp_pop_up_register_settings() {

		register_setting( 'dpsp_location_pop_up', 'dpsp_location_pop_up', 'dpsp_pop_up_settings_sanitize' );

	}
	add_action( 'admin_init', 'dpsp_pop_up_register_settings' );


	/**
	 * Filter and sanitize settings
	 *
	 * @param array $new_settings
	 *
	 */
	function dpsp_pop_up_settings_sanitize( $new_settings ) {

		// Save default values even if values do not exist
		if( !isset( $new_settings['networks'] ) )
			$new_settings['networks'] = array();
		
		if( !isset( $new_settings['button_style'] ) )
			$new_settings['button_style'] = 1;

		$post_data = stripslashes_deep( $_POST );

		if( ! empty( $post_data['title'] ) )
			$new_settings['display']['title'] = $post_data['title'];

		if( ! empty( $post_data['message'] ) )
			$new_settings['display']['message'] = $post_data['message'];


		$new_settings = dpsp_array_strip_script_tags( $new_settings );

		return $new_settings;

	}