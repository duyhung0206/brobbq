<?php

/**
 * Function that creates the sub-menu item and page for the debugger
 *
 * @return void
 *
 */
function dpsp_register_debugger_subpage() {

	$settings = get_option( 'dpsp_settings', array() );

	if( ! empty( $settings['debugger_enabled'] ) )
		add_submenu_page( 'dpsp-social-pug', __( 'Debugger', 'social-pug' ), __( 'Debugger', 'social-pug' ), 'manage_options', 'dpsp-debugger', 'dpsp_debugger_subpage' );

}
add_action( 'admin_menu', 'dpsp_register_debugger_subpage', 101 );


/**
 * Function that adds content to the debugger subpage
 *
 * @return string
 *
 */
function dpsp_debugger_subpage() {

	include_once 'views/view-submenu-page-debugger.php';

}