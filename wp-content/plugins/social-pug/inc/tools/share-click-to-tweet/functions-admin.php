<?php

	/**
	 * Register the Click to Tweet setting tab in the Settings page
	 *
	 * @param array $tabs
	 *
	 * @return array
	 *
	 */
	function dpsp_tool_share_click_to_tweet_settings_tab( $tabs = array() ) {

		$tabs['click-to-tweet'] = __( 'Click to Tweet', 'social-pug' );

		return $tabs;
	}
	add_filter( 'dpsp_submenu_page_settings_tabs', 'dpsp_tool_share_click_to_tweet_settings_tab', 10 );


	/**
	 * Adds the settings fields for the Click to Tweet in the Settings page
	 *
	 * @param array $settings - the settings of the plugin
	 *
	 */
	function dpsp_tool_share_click_to_tweet_settings_tab_content( $settings ) {

		include 'views/view-submenu-page-settings-tab.php';

	}
	add_action( 'dpsp_submenu_page_settings_tab_click-to-tweet', 'dpsp_tool_share_click_to_tweet_settings_tab_content', 20 );


	/**
	 * Add custom buttons to the TinyMCE Editor
	 *
	 */
	function dpsp_tinymce_buttons() {

		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
	        add_filter( 'mce_buttons', 'dpsp_register_tinymce_buttons' );
	        add_filter( 'mce_external_plugins', 'dpsp_add_tinymce_buttons' );
	    }

	}
	add_action( 'admin_init', 'dpsp_tinymce_buttons' );

	function dpsp_register_tinymce_buttons( $buttons ) {

		$buttons[] = 'dpsp_click_to_tweet';

		return $buttons;

	}

	function dpsp_add_tinymce_buttons( $plugin_array ) {

		$plugin_array['dpsp_click_to_tweet'] = DPSP_PLUGIN_DIR_URL . 'assets/js/dashboard-tinymce.js';

		return $plugin_array;
		
	}