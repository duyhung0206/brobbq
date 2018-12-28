<?php

/**
 * Include link shortening tool files
 *
 */
function dpsp_include_files_link_shortening_services() {

	$dirs = array_filter( glob( DPSP_PLUGIN_DIR . '/inc/tools/misc-link-shortening/available-services/*' ), 'is_dir');

    foreach( $dirs as $dir ) {
        if( file_exists( $file =  $dir . '/' . basename($dir) . '.php') ){
            include_once ( $file );
        }
    }

}
add_action( 'init', 'dpsp_include_files_link_shortening_services' );


/**
 * Returns the link shortening services available
 *
 */
function dpsp_get_available_link_shortening_services() {

	/**
	 * Filter to dynamically add link shortening services
	 *
	 * @param array
	 *
	 */
	$services = apply_filters( 'dpsp_available_link_shortening_services', array() );

	$services = ( is_array( $services ) ? $services : array() );

	return $services;

}


/**
 * Adds the HTML needed for the link shortening services in the Settings page of the plugin
 *
 * @param array $settings
 *
 */
function dpsp_link_shortening_add_settings_section( $settings = array() ) {

	include 'views/view-submenu-page-settings-general-tab-link-shortening.php';

}
add_action( 'dpsp_submenu_page_settings_tab_general-settings', 'dpsp_link_shortening_add_settings_section', 20 );

/**
 * Purges all shortened links for the selected Link Shortening Service
 *
 */
function dpsp_purge_shortened_links() {

	if( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'dpsp_settings-options' ) ) {
		echo json_encode( array( 'success' => 0 ) );
		wp_die();
	}

	if( empty( $_POST['shortening_service'] ) ) {
		echo json_encode( array( 'success' => 0 ) );
		wp_die();
	}

	// Get link service
	$shortening_service = sanitize_text_field( $_POST['shortening_service'] );

	global $wpdb;

	// Delele all shortened links
	$result = $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'dpsp_short_link_' . $shortening_service ) );

	if( false !== $result ) {

		echo json_encode( array( 'success' => 1 ) );

	} else {

		echo json_encode( array( 'success' => 0 ) );

	}

	wp_die();

}
add_action( 'wp_ajax_dpsp_purge_shortened_links', 'dpsp_purge_shortened_links' );


/**
 * Listens for the updated message after a link shortening purge and displays a
 * success or error message to the user
 *
 */
function dpsp_purge_shortened_links_admin_notice() {

	if( empty( $_GET['page'] ) )
		return;

	if( $_GET['page'] != 'dpsp-settings' )
		return;

	if( empty( $_GET['updated'] ) )
		return;

	if( $_GET['updated'] == 'dpsp_purge_shortened_links_fail' )
		add_settings_error( 'dpsp_settings', 'purge_shortened_links', __( 'Something went wrong! Could not remove the shortened links.', 'social-pug' ), 'error' );
	
	if( $_GET['updated'] == 'dpsp_purge_shortened_links_success' )
		add_settings_error( 'dpsp_settings', 'purge_shortened_links', __( 'Shortened links purged successfully.', 'social-pug' ), 'updated' );

}
add_action( 'admin_init', 'dpsp_purge_shortened_links_admin_notice' );


/**
 * Shortens the post_url link through the different link shortening services available and caches the result
 *
 * @param string $post_url
 * @param string $network_slug
 *
 * @return string
 *
 */
function dpsp_shorten_link( $post_url, $network_slug ) {

	$post_obj = dpsp_get_current_post();

	if( $post_obj )
		$post_id = $post_obj->ID;
	else
		return $post_url;

	// Escape early for networks that do not support link shortening
	$networks_not_supported = array( 'pinterest' );

	/**
	 * Filter social networks that do not support link shortening
	 *
	 */
	$networks_not_supported = apply_filters( 'dpsp_shorten_link_networks_no_support', $networks_not_supported );

	if( in_array( $network_slug, $networks_not_supported ) )
		return $post_url;


	// Get the plugin general settings
	$settings = get_option( 'dpsp_settings', array() );

	if( empty( $settings['shortening_active'] ) )
		return $post_url;

	if( empty( $settings['shortening_service'] ) )
		return $post_url;

	// Get active service
	$active_service = $settings['shortening_service'];

	// Get cached short links
	$shortened_links = get_post_meta( $post_id, 'dpsp_short_link_' . $active_service, true );

	/**
	 * Check to see if returned value is an array
	 * If not, it's legacy code and must be transformed to array
	 *
	 */
	if( ! empty( $shortened_links ) && ! is_array( $shortened_links ) ) {

		$shortened_links = array( 'short_url' => $shortened_links );
		update_post_meta( $post_id, 'dpsp_short_link_' . $active_service, $shortened_links );

	}

	// If empty make sure it's an array
	if( empty( $shortened_links ) )
		$shortened_links = array();


	// Determine if we shorten link for the network or no
	$shorten_for_network = apply_filters( 'dpsp_link_shortening_do_for_individual_network', false, $active_service );


	/**
	 * If we have cached values return them without making any calls to the services
	 *
	 */
	if( false == $shorten_for_network ) {

		if( ! empty( $shortened_links['short_url'] ) )
			return $shortened_links['short_url'];

	}

	if( true == $shorten_for_network ) {

		if( ! empty( $shortened_links[$network_slug . '_short_url'] ) )
			return $shortened_links[$network_slug . '_short_url'];

	}

	/**
	 * Filter to shorten the URL by each individual service
	 *
	 * @param string $post_url
	 * @param string $network_slug
	 *
	 */
	$shortened_url = apply_filters( 'dpsp_shorten_link_' . $active_service, $post_url, ( $shorten_for_network ? $network_slug : null ) );

	// If the shorten url differs from the post_url, cache it
	if( $shortened_url != $post_url )
		$shortened_links[ ( $shorten_for_network ? $network_slug . '_' : '' ) . 'short_url' ] = $shortened_url;

	// Cache the short links
	if( ! empty( $shortened_links ) )
		update_post_meta( $post_id, 'dpsp_short_link_' . $active_service, $shortened_links );


	// Return the url
	if( $shorten_for_network )
		$post_url = ( ! empty( $shortened_links[$network_slug . '_short_url'] ) ? $shortened_links[$network_slug . '_short_url'] : $post_url );
	else
		$post_url = ( ! empty( $shortened_links['short_url'] ) ? $shortened_links['short_url'] : $post_url );

	return $post_url;

}
add_filter( 'dpsp_get_network_share_link_post_url', 'dpsp_shorten_link', 20, 2 );