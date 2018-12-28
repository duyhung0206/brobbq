<?php

	/**
	 * Add the share floating sidebar tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_share_sidebar( $tools = array() ) {

		$tools['share_sidebar'] = array(
			'name' 		 		 => __( 'Floating Sidebar', 'social-pug' ),
			'type'				 => 'share_tool',
			'activation_setting' => 'dpsp_location_sidebar[active]',
			'img'		 		 => 'assets/img/tool-sidebar.png',
			'admin_page' 		 => 'admin.php?page=dpsp-sidebar'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_share_sidebar', 10 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_share_sidebar_include_files() {

		if( ! dpsp_is_tool_active( 'share_sidebar' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-floating-sidebar/submenu-page-sidebar.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-floating-sidebar/submenu-page-sidebar.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-floating-sidebar/functions-frontend.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-floating-sidebar/functions-frontend.php' );

	}
	add_action( 'init', 'dpsp_tool_share_sidebar_include_files' );