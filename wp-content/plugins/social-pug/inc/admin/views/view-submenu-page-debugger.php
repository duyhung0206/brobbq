<?php dpsp_admin_header(); ?>

<?php

	/**
	 * Get all system versions
	 *
	 */
	global $wp_version;

	$php_version 	= phpversion();
	$curl_version	= ( function_exists( 'curl_version' ) ? curl_version() : 'Not installed' );
	$curl_version	= ( is_array( $curl_version ) ? $curl_version['version'] : $curl_version );
	$dpsp_version	= DPSP_VERSION;

	/**
	 * Get all plugins and active plugins
	 *
	 */
	$plugins 		= get_plugins();
	$active_plugins = array();

	foreach( $plugins as $key => $plugin ) {
		if( is_plugin_active( $key ) )
			$active_plugins[$key]['Name'] = $plugin['Name'];
	}

	/**
	 * Get all Social Pug cron jobs
	 *
	 */
	$cron_jobs = array();

	if( false !== wp_get_schedule( 'dpsp_cron_get_posts_networks_share_count' ) )
		$cron_jobs[] = 'dpsp_cron_get_posts_networks_share_count';

	if( false !== wp_get_schedule( 'dpsp_cron_get_posts_networks_share_count', array( '2x_hourly' ) ) )
		$cron_jobs[] = 'dpsp_cron_get_posts_networks_share_count - 2x_hourly';

	if( false !== wp_get_schedule( 'dpsp_cron_get_posts_networks_share_count', array( 'daily' ) ) )
		$cron_jobs[] = 'dpsp_cron_get_posts_networks_share_count - daily';

	if( false !== wp_get_schedule( 'dpsp_cron_get_posts_networks_share_count', array( 'weekly' ) ) )
		$cron_jobs[] = 'dpsp_cron_get_posts_networks_share_count - weekly';

	/**
	 * Get serial check request response
	 *
	 */
	if( function_exists( 'dpsp_get_serial_key_request_response' ) )
		$serial_response = dpsp_get_serial_key_request_response();
	else
		$serial_response = null;

	if( !isset( $serial_response ) )
		$serial_response = '';

	$serial_status_db = get_option( 'dpsp_product_serial_status', '' );

	if( function_exists( 'dpsp_get_serial_key_status' ) )
		$serial_status_request = dpsp_get_serial_key_status();

	if( !isset( $serial_status_request ) )
		$serial_status_request = '';

?>

<div class="dpsp-page-wrapper dpsp-page-content wrap">
	
	<h1 class="dpsp-page-title"><?php echo __( 'System Status', 'social-pug' ); ?></h1>

<textarea readonly style="width: 100%; min-height: 600px;">
System Versions:
---------------------------------------------------------------------------------------------------&#13;
PHP Version: <?php echo $php_version; ?> &#13;
cURL Version: <?php echo $curl_version; ?> &#13;
WP Version: <?php echo $wp_version; ?> &#13;
Social Pug Version: <?php echo $dpsp_version; ?> &#13;
&#13;
All Plugins:
---------------------------------------------------------------------------------------------------&#13;
<?php 
	if( ! empty( $plugins ) ) {
		foreach( $plugins as $key => $plugin )
			echo esc_attr( $plugin['Name'] ) . ' (' . esc_attr( $key ) . ')' . '&#13;';
	} else {
		echo 'None' . '&#13;';
	}
?>
&#13;
Active Plugins:
---------------------------------------------------------------------------------------------------&#13;
<?php 
	if( ! empty( $active_plugins ) ) {
		foreach( $active_plugins as $key => $plugin )
			echo esc_attr( $plugin['Name'] ) . ' (' . esc_attr( $key ) . ')' . '&#13;';	
	} else {
		echo 'None' . '&#13;';
	}
	
?>
&#13;
Social Pug Cron Jobs:
---------------------------------------------------------------------------------------------------&#13;
<?php 
	if( ! empty( $cron_jobs ) ) {
		foreach( $cron_jobs as $cron_job )
			echo $cron_job . '&#13;';
	} else {
		echo 'None' . '&#13;';
	}
?>
&#13;
Serial response:
---------------------------------------------------------------------------------------------------&#13;
<?php echo esc_attr( $serial_response ); ?>
&#13;&#13;
Saved serial status:
---------------------------------------------------------------------------------------------------&#13;
<?php echo esc_attr( $serial_status_db ); ?>
&#13;&#13;
Request serial status:
---------------------------------------------------------------------------------------------------&#13;
<?php echo esc_attr( $serial_status_request ); ?>
</textarea>

</div>