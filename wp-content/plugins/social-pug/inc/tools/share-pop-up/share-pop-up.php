<?php

	/**
	 * Add the share pop-up tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_share_pop_up( $tools = array() ) {

		$tools['share_pop_up'] = array(
			'name' 		 		 => __( 'Pop-Up', 'social-pug' ),
			'type'				 => 'share_tool',
			'activation_setting' => 'dpsp_location_pop_up[active]',
			'img'		 		 => 'assets/img/tool-pop-up.png',
			'admin_page' 		 => 'admin.php?page=dpsp-pop-up'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_share_pop_up', 25 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_share_pop_up_include_files() {

		if( ! dpsp_is_tool_active( 'share_pop_up' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-pop-up/submenu-page-pop-up.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-pop-up/submenu-page-pop-up.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-pop-up/functions-frontend.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-pop-up/functions-frontend.php' );

	}
	add_action( 'init', 'dpsp_tool_share_pop_up_include_files' );