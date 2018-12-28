<?php

/**
 * Registers Branch as a link shortening service
 *
 * @param array $services
 *
 * @return array
 *
 */
function dpsp_register_available_link_shortening_service_branch( $services = array() ) {

	$services['branch'] = 'Branch';

	return $services;

}
add_filter( 'dpsp_available_link_shortening_services', 'dpsp_register_available_link_shortening_service_branch' );


/**
 * Adds the needed settings fields for Branch
 *
 * @param array $settings
 *
 */
function dpsp_link_shortening_service_settings_branch( $settings ) {

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_key]', ( isset($settings['branch_key']) ? $settings['branch_key'] : '' ), __( 'Branch Key', 'social-pug' ), '', sprintf( __( 'You will need to create an account on %1$sBranch.io%2$s and retrieve your Branch Key from %3$sthis link%4$s.', 'social-pug' ), '<a href="https://dashboard.branch.io" target="_blank">', '</a>' , '<a href="https://dashboard.branch.io/account-settings/app" target="_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_id_parameter]', ( isset($settings['branch_custom_id_parameter']) ? $settings['branch_custom_id_parameter'] : '' ), __( 'ID Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain WordPress post ID. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_title_parameter]', ( isset($settings['branch_custom_title_parameter']) ? $settings['branch_custom_title_parameter'] : '' ), __( 'Title Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain post title. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_description_parameter]', ( isset($settings['branch_custom_description_parameter']) ? $settings['branch_custom_description_parameter'] : '' ), __( 'Description Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain post description. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_image_url_parameter]', ( isset($settings['branch_custom_image_url_parameter']) ? $settings['branch_custom_image_url_parameter'] : '' ), __( 'Image URL Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain post image URL. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_date_parameter]', ( isset($settings['branch_custom_date_parameter']) ? $settings['branch_custom_date_parameter'] : '' ), __( 'Date Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain post date. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

	echo dpsp_settings_field( 'text', 'dpsp_settings[branch_custom_post_url_parameter]', ( isset($settings['branch_custom_post_url_parameter']) ? $settings['branch_custom_post_url_parameter'] : '' ), __( 'URL Parameter', 'social-pug' ), '', sprintf( __( 'Key in %1$slink data object%2$s to contain post permalink URL. Not set if blank.', 'social-pug' ), '<a href="https://docs.branch.io/pages/links/integrate/#custom-data" target=_blank">', '</a>' ) );

}
add_action( 'dpsp_settings_subsection_link_shortening_branch', 'dpsp_link_shortening_service_settings_branch', 10 );


/**
 * Shortens the post_url link through Branch and returns the result
 *
 * @param string $post_url
 * @param string $network_slug
 *
 * @return string
 *
 */
function dpsp_shorten_link_branch( $post_url, $network_slug ) {

	$settings = get_option( 'dpsp_settings' );
	$post_obj = dpsp_get_current_post();

	$post_id 		  = $post_obj->ID;
	$post_title 	  = dpsp_get_post_title();
	$post_description = dpsp_get_post_description();
	$post_image_url   = dpsp_get_post_image_url();
	$post_date 		  = get_the_date( 'Y-m-d\TG:i:s', $post_id );

	$post_url_cleaned = preg_replace( '/\?.*/', '', $post_url );

	// Exit if the key is not present
	if( empty( $settings['branch_key'] ) )
		return $post_url;

	$branch_api_endpoint = 'https://api.branch.io';
	$branch_api_method   = '/v1/url';

	$custom_parameters = array(
		'$fallback_url'  => $post_url,
		'$desktop_url'   => $post_url,
		'$ios_url' 		 => $post_url,
		'$android_url' 	 => $post_url,
		'$canonical_url' => $post_url_cleaned,
		'$canonical_identifier' => $post_url_cleaned,
		'$og_title' 	  => $post_title,
		'$og_description' => $post_description,
		'$og_image_url'   => $post_image_url,
		$settings['branch_custom_id_parameter'] 		 => $post_id,
		$settings['branch_custom_title_parameter'] 		 => $post_title,
		$settings['branch_custom_description_parameter'] => $post_description,
		$settings['branch_custom_image_url_parameter'] 	 => $post_image_url,
		$settings['branch_custom_date_parameter'] 		 => $post_date,
		$settings['branch_custom_post_url_parameter'] 	 => $post_url
	);

	$custom_data = array();

	foreach( $custom_parameters as $key => $value ) {
		if( $key != '' ) {
			$custom_data[$key] = $value;
		}
	}

	$link_data = array(
		'branch_key' => $settings['branch_key'],
		'feature' => 'SocialPug Share',
		'data' => $custom_data
	);

	$response = wp_remote_post( $branch_api_endpoint . $branch_api_method, array(
		'method' => 'POST',
		'timeout' => 15,
		'headers' => array(
			'Content-Type' => 'application/json'
		),
		'body' => wp_json_encode($link_data)
	));

	// Process response and add shortened URL
	if( wp_remote_retrieve_response_code( $response ) == 200 && wp_remote_retrieve_body( $response ) != '' ) {

		$body = json_decode( wp_remote_retrieve_body( $response ), ARRAY_A );

		// Check to see if the url has been shortened
		if( ! empty( $body['url'] ) ) {

			// Update the shortened url in the post meta
			$post_url = $body['url'];

		}

	}

	return $post_url;

}
add_filter( 'dpsp_shorten_link_branch', 'dpsp_shorten_link_branch', 10, 2 );