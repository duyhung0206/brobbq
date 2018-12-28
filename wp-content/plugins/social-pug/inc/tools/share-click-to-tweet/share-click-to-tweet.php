<?php

	/**
	 * Include files for this tool
	 *
	 */
	function dpsp_tool_share_click_to_tweet_include_files() {

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/tools/share-click-to-tweet/functions-admin.php' ) )
			include( DPSP_PLUGIN_DIR . '/inc/tools/share-click-to-tweet/functions-admin.php' );

	}
	add_action( 'init', 'dpsp_tool_share_click_to_tweet_include_files' );