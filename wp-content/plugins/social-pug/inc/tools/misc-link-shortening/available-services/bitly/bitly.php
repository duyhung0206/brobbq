<?php

/**
 * Registers Bitly as a link shortening service
 *
 * @param array $services
 *
 * @return array
 *
 */
function dpsp_register_available_link_shortening_service_bitly( $services = array() ) {

	$services['bitly'] = 'Bitly';

	return $services;

}
add_filter( 'dpsp_available_link_shortening_services', 'dpsp_register_available_link_shortening_service_bitly' );


/**
 * Adds the needed settings fields for Bitly
 *
 * @param array $settings
 *
 */
function dpsp_link_shortening_service_settings_bitly( $settings ) {

	echo dpsp_settings_field( 'text', 'dpsp_settings[bitly_access_token]', ( isset($settings['bitly_access_token']) ? $settings['bitly_access_token'] : '' ), __( 'Generic Access Token', 'social-pug' ), '', sprintf( __( 'You will need to create an account on %1$sBitly.com%2$s and generate the Generic Access Token from %3$sthis link%4$s.', 'social-pug' ), '<a href="http://www.bitly.com/" target="_blank">', '</a>' , '<a href="https://bitly.com/a/oauth_apps" target="_blank">', '</a>' ) );

}
add_action( 'dpsp_settings_subsection_link_shortening_bitly', 'dpsp_link_shortening_service_settings_bitly', 10 );


/**
 * Shortens the post_url link through Bitly and returns the result
 *
 * @param string $post_url
 * @param string $network_slug
 *
 * @return string
 *
 */
function dpsp_shorten_link_bitly( $post_url, $network_slug ) {

	$settings = get_option( 'dpsp_settings' );

	// Exit if the token is not present
	if( empty( $settings['bitly_access_token'] ) )
		return $post_url;

	$bitly_api_endpoint = 'https://api-ssl.bitly.com';
	$bitly_api_method   = '/v3/shorten';

	$response = wp_remote_get( add_query_arg( array( 'access_token' => $settings['bitly_access_token'], 'longUrl' => urlencode($post_url) ), $bitly_api_endpoint . $bitly_api_method ), array( 'timeout' => 15 ) );
	
	// Process response and add shortened URL
	if( wp_remote_retrieve_response_code( $response ) == 200 && wp_remote_retrieve_body( $response ) != '' ) {
		
		$body = json_decode( wp_remote_retrieve_body( $response ), ARRAY_A );

		// Check to see if the url has been shortened
		if( ! empty( $body['data']['url'] ) ) {

			// Update the shortened url in the post meta
			$post_url = $body['data']['url'];

		}

	}

	return $post_url;

}
add_filter( 'dpsp_shorten_link_bitly', 'dpsp_shorten_link_bitly', 10, 2 );