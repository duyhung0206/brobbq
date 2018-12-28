<?php

/**
 * Hides social networks that are mobile only from the share tools when these are
 * displayed on devices that are not mobile
 *
 * @param array $settings 	- the settings array for the current location
 * @param string $action 	- the current type of action ( share/follow )
 * @param string $location 	- the display location for the buttons
 *
 * @return array
 *
 */
function dpsp_handle_mobile_only_networks( $settings, $action, $location ) {

	if( $action !== 'share' )
		return $settings;

	if( empty( $settings['networks'] ) || ! is_array( $settings['networks'] ) )
		return $settings;

	if( ! array_key_exists( 'whatsapp', $settings['networks'] ) )
		return $settings;

	$plugin_settings = get_option( 'dpsp_settings', array() );

	if( empty( $plugin_settings['whatsapp_display_only_mobile'] ) )
		return $settings;

	$mobile_detect = new DPSP_Mobile_Detect();

	// Remove WhatsApp from the networks array if we are not on a mobile device
	if( ! $mobile_detect->isMobile() ) {

		unset( $settings['networks']['whatsapp'] );

	} else {

		if( ! empty( $settings['display']['column_count'] ) && $settings['display']['column_count'] != 'auto' ) {

			$networks_count = count( $settings['networks'] );
			$column_count 	= (int)$settings['display']['column_count'];

			if( ( ( $networks_count + 1 ) / $column_count < 2 ) && ( $networks_count > $column_count ) ) {
				$settings['display']['column_count'] += 1;
			}

		}

	}

	return $settings;

}
add_filter( 'dpsp_network_buttons_outputter_settings', 'dpsp_handle_mobile_only_networks', 10, 3 );