<?php
/**
 * This file serves the purpose of updating database items when a new version of the plugin is released
 *
 */

/**
 * Updates needed to the database when updating to version 2.0.0
 *
 * In this version the new active_tools array has been added and we need to grab
 * all current active button locations and add them as active tools
 *
 * @param string $old_db_version  - the previous version of the plugin
 * @param string $new_db_version  - the new version of the plugin
 *
 */
function dpsp_version_update_2_0_0( $old_db_version, $new_db_version ) {

	// Do this only if the version is greater than 2.0.0
	if( false === version_compare( $new_db_version, '2.0.0', '>=' ) )
		return;

	// The version update is dependent on this function
	// Check to see if it exists first so we don't go into a fatal error
	if( ! function_exists( 'dpsp_is_location_active' ) )
		return;

	// Check to see if we've done this check before
	$version_updated = get_option( 'dpsp_version_update_2_0_0', false );

	if( $version_updated )
		return;


	$active_tools = get_option( 'dpsp_active_tools', array() );

	// Supported network locations in version 1.6.2
	$network_locations = array( 'sidebar', 'content', 'mobile', 'pop_up', 'follow_widget' );

	// If any of the supported network locations are active add them to the
	// active_tools array
	foreach( $network_locations as $location_slug ) {
		if( dpsp_is_location_active( $location_slug ) ) {

			if( $location_slug != 'follow_widget' )
				$tool_slug = 'share_' . $location_slug;
			else
				$tool_slug = $location_slug;

			array_push( $active_tools, $tool_slug );

		}
	}

	$active_tools = array_unique( $active_tools );

	update_option( 'dpsp_active_tools', $active_tools );


	// Save a true bool value in the database so we know we've done this 
	// version update
	update_option( 'dpsp_version_update_2_0_0', 1 );

}
add_action( 'dpsp_update_database', 'dpsp_version_update_2_0_0', 10, 2 );


/**
 * Updates needed to the database when updating to version 2.3.4
 *
 * In this version StumbleUpon has been removed and we need to remove it from
 * all location settings
 *
 * @param string $old_db_version  - the previous version of the plugin
 * @param string $new_db_version  - the new version of the plugin
 *
 */
function dpsp_version_update_2_3_4( $old_db_version, $new_db_version ) {

	// Do this only if the version is greater than 2.3.4
	if( false === version_compare( $new_db_version, '2.3.4', '>=' ) )
		return;

	// The version update is dependent on this function
	// Check to see if it exists first so we don't go into a fatal error
	if( ! function_exists( 'dpsp_is_location_active' ) )
		return;

	// Check to see if we've done this check before
	$version_updated = get_option( 'dpsp_version_update_2_3_4', false );

	if( $version_updated )
		return;

	// Get all network locations
	$locations = dpsp_get_network_locations( 'share', true );

	foreach( $locations as $location_slug ) {

		$location_settings = dpsp_get_location_settings( $location_slug );

		// If no networks are set, just go on to the next location
		if( empty( $location_settings['networks'] ) )
			continue;

		$networks = array_keys( $location_settings['networks'] );

		// If StumbleUpon is not present, jump to the next location
		if( ! in_array( 'stumbleupon', $networks ) )
			continue;

		// Remove StumbleUpon and update the settings
		unset( $location_settings['networks']['stumbleupon'] );

		update_option( 'dpsp_location_' . $location_slug, $location_settings );

	}

	// Save a true bool value in the database so we know we've done this 
	// version update
	update_option( 'dpsp_version_update_2_3_4', 1 );

}
add_action( 'dpsp_update_database', 'dpsp_version_update_2_3_4', 10, 2 );


/**
 * Updates needed to the database when updating to version 2.4.0
 *
 * In this version the Mobile Sticky sharing tool has been transformed into the 
 * Sticky Bar sharing tool and the settings need to be transfered
 *
 * @param string $old_db_version  - the previous version of the plugin
 * @param string $new_db_version  - the new version of the plugin
 *
 */
function dpsp_version_update_2_4_0( $old_db_version, $new_db_version ) {

	// Do this only if the version is greater than 2.4.0
	if( false === version_compare( $new_db_version, '2.4.0', '>=' ) )
		return;

	// The version update is dependent on this function
	// Check to see if it exists first so we don't go into a fatal error
	if( ! function_exists( 'dpsp_is_location_active' ) )
		return;

	// Check to see if we've done this check before
	$version_updated = get_option( 'dpsp_version_update_2_4_0', false );

	if( $version_updated )
		return;

	$settings_mobile = get_option( 'dpsp_location_mobile', array() );

	if( empty( $settings_mobile ) )
		return;

	// Additional settings
	$settings_mobile['display']['shape']		  = 'rounded';
	$settings_mobile['display']['icon_animation'] = 'yes';
	$settings_mobile['display']['show_on_device'] = 'mobile';

	update_option( 'dpsp_location_sticky_bar', $settings_mobile );


	// Need to update the active tools db option
	$active_tools = get_option( 'dpsp_active_tools', array() );

	if( is_array( $active_tools ) && in_array( 'share_mobile', $active_tools ) ) {

		$active_tools[] = 'share_sticky_bar';

		update_option( 'dpsp_active_tools', $active_tools );
		
	}


	// Save a true bool value in the database so we know we've done this 
	// version update
	update_option( 'dpsp_version_update_2_4_0', 1 );

}
add_action( 'dpsp_update_database', 'dpsp_version_update_2_4_0', 10, 2 );