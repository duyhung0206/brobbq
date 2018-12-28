<?php

	/**
	 * Add the import-export tool to the toolkit array
	 *
	 * @param array $tools
	 * 
	 * @return array
	 *
	 */
	function dpsp_tool_misc_import_export( $tools = array() ) {

		$tools['misc_import_export'] = array(
			'name' 		 		 => __( 'Import / Export Settings', 'social-pug' ),
			'type'				 => 'misc_tool',
			'img'		 		 => 'assets/img/tool-misc-import-export.png',
			'admin_page' 		 => 'admin.php?page=dpsp-import-export'
		);

		return $tools;

	}
	add_filter( 'dpsp_get_tools', 'dpsp_tool_misc_import_export', 35 );


	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_misc_import_export_include_files() {

		if( ! dpsp_is_tool_active( 'misc_import_export' ) )
			return;

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/misc-import-export/submenu-page-import-export.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/misc-import-export/submenu-page-import-export.php' );

	}
	add_action( 'init', 'dpsp_tool_misc_import_export_include_files' );