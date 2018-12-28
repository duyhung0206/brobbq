<?php

	/**
	 * Add the share inline content tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_share_inline_content( $tools = array() ) {

		$tools['share_content'] = array(
			'name' 		 		 => __( 'Inline Content', 'social-pug' ),
			'type'				 => 'share_tool',
			'activation_setting' => 'dpsp_location_content[active]',
			'img'		 		 => 'assets/img/tool-content.png',
			'admin_page' 		 => 'admin.php?page=dpsp-content'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_share_inline_content', 15 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_share_inline_content_include_files() {

		if( ! dpsp_is_tool_active( 'share_content' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-inline-content/submenu-page-content.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-inline-content/submenu-page-content.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-inline-content/functions-frontend.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-inline-content/functions-frontend.php' );

	}
	add_action( 'init', 'dpsp_tool_share_inline_content_include_files' );