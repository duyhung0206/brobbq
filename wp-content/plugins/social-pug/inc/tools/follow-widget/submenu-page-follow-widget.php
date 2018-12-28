<?php 
	/*
	 * Function that creates the sub-menu item and page for the follow widget location of the share buttons
	 *
	 *
	 * @return void
	 *
	 */
	function dpsp_register_follow_widget_subpage() {
		add_submenu_page( 'dpsp-social-pug', __('Follow Widget', 'social-pug'), __('Follow Widget', 'social-pug'), 'manage_options', 'dpsp-follow-widget', 'dpsp_follow_widget_subpage' );
	}
	add_action( 'admin_menu', 'dpsp_register_follow_widget_subpage', 30 );


	/*
	 * Function that adds content to the follow widget icons subpage
	 *
	 * @return string
	 *
	 */
	function dpsp_follow_widget_subpage() {

		include_once 'views/view-submenu-page-follow-widget.php';

	}


	function dpsp_follow_widget_register_settings() {

		register_setting( 'dpsp_location_follow_widget', 'dpsp_location_follow_widget', 'dpsp_follow_widget_settings_sanitize' );

	}
	add_action( 'admin_init', 'dpsp_follow_widget_register_settings' );


	/*
	 * Filter and sanitize settings
	 *
	 * @param array $new_settings
	 *
	 */
	function dpsp_follow_widget_settings_sanitize( $new_settings ) {

		// Save default values even if values do not exist
		if( !isset( $new_settings['networks'] ) )
			$new_settings['networks'] = array();
		
		if( !isset( $new_settings['button_style'] ) )
			$new_settings['button_style'] = 1;

		$new_settings = dpsp_array_strip_script_tags( $new_settings );

		return $new_settings;

	}