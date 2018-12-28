<?php

	/**
	 * Add the follow widget tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_follow_widget( $tools = array() ) {

		$tools['follow_widget'] = array(
			'name' 		 		 => __( 'Follow Widget', 'social-pug' ),
			'type'				 => 'follow_tool',
			'activation_setting' => 'dpsp_location_follow_widget[active]',
			'img'		 		 => 'assets/img/tool-follow-widget.png',
			'admin_page' 		 => 'admin.php?page=dpsp-follow-widget'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_follow_widget', 30 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_follow_widget_include_files() {

		if( ! dpsp_is_tool_active( 'follow_widget' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/follow-widget/submenu-page-follow-widget.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/follow-widget/submenu-page-follow-widget.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/follow-widget/admin-widget-follow.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/follow-widget/admin-widget-follow.php' );

	}
	add_action( 'widgets_init', 'dpsp_tool_follow_widget_include_files' );