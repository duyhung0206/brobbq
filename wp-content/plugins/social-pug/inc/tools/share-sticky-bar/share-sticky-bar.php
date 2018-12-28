<?php

	/**
	 * Add the share sticky bar tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_share_sticky_bar( $tools = array() ) {

		$tools['share_sticky_bar'] = array(
			'name' 		 		 => __( 'Sticky Bar', 'social-pug' ),
			'type'				 => 'share_tool',
			'activation_setting' => 'dpsp_location_sticky_bar[active]',
			'img'		 		 => 'assets/img/tool-mobile.png',
			'admin_page' 		 => 'admin.php?page=dpsp-sticky-bar'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_share_sticky_bar', 20 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_share_sticky_bar_include_files() {

		if( ! dpsp_is_tool_active( 'share_sticky_bar' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-sticky-bar/submenu-page-sticky-bar.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-sticky-bar/submenu-page-sticky-bar.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-sticky-bar/functions-frontend.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-sticky-bar/functions-frontend.php' );

	}
	add_action( 'init', 'dpsp_tool_share_sticky_bar_include_files' );