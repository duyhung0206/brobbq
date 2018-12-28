<?php

/**
 * Tells the link shortening system to shorten links for each individual social network
 * if UTM tracking is enabled
 *
 * @param bool $bool
 *
 * @return bool
 *
 */
function dpsp_utm_tracking_shorten_link_for_individual_network( $bool = false ) {

	$settings = get_option( 'dpsp_settings', array() );
	
	if( ! empty( $settings['utm_tracking'] ) )
		return true;

	return $bool;

}
add_filter( 'dpsp_link_shortening_do_for_individual_network', 'dpsp_utm_tracking_shorten_link_for_individual_network', 10 );


/**
 * Adds the Google Analytics UTM tracking parameters to the url of the post
 *
 * @param string $post_url
 * @param string $network_slug
 *
 * @return string
 *
 */
function dpsp_add_ga_utm_tracking( $post_url, $network_slug ) {

	$post_obj = dpsp_get_current_post();

	if( $post_obj )
		$post_id = $post_obj->ID;
	else
		return $post_url;


	$settings = get_option( 'dpsp_settings' );

	if( empty( $settings['utm_tracking'] ) || empty( $settings['utm_source'] ) || empty( $settings['utm_medium'] ) || empty( $settings['utm_campaign'] ) )
		return $post_url;

	// Set the UTM source to the network slug
	$utm_source = ( ( trim($settings['utm_source']) === '{{network_name}}' ) ? $network_slug : trim( $settings['utm_source'] ) );

	// Add the queries to the post_url
	$post_url = add_query_arg( array( 'utm_source' => $utm_source, 'utm_medium' => trim($settings['utm_medium']), 'utm_campaign' => trim($settings['utm_campaign']) ), $post_url );

	return $post_url;

}
add_filter( 'dpsp_get_network_share_link_post_url', 'dpsp_add_ga_utm_tracking', 10, 2 );