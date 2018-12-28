<?php


	/*
	 * Add custom schedules to use for the cron jobs
	 *
	 */
	function dpsp_cron_schedules( $schedules ) {

		$schedules['dpsp_2x_hourly'] = array(
			'interval' => (3600 * 2),
			'display'  => __( 'Once every two hours', 'social-pug' )
		);

		$schedules['weekly'] = array(
			'interval' => ( 3600 * 24 * 7 ),
			'display'  => __( 'Once every week', 'social-pug' )
		);

		return $schedules;

	}
	add_filter( 'cron_schedules', 'dpsp_cron_schedules' );

	
	/*
	 * Set cron jobs
	 *
	 * @return void
	 *
	 */
	function dpsp_set_cron_jobs() {

		// Grabs share counts
		//if( false === wp_get_schedule( 'dpsp_cron_get_posts_networks_share_count' ) )
			//wp_schedule_event( time(), 'dpsp_2x_hourly', 'dpsp_cron_get_posts_networks_share_count' );

		// Verifies the serial key
		if( false === wp_get_schedule( 'dpsp_cron_update_serial_key_status' ) )
			wp_schedule_event( time(), 'daily', 'dpsp_cron_update_serial_key_status' );

	}


	/*
	 * Stop cron jobs
	 *
	 * @return void
	 *
	 */
	function dpsp_stop_cron_jobs() {

		// Remove deprecated cron
		wp_clear_scheduled_hook( 'dpsp_cron_get_posts_networks_share_count', array( '2x_hourly' ) );
		wp_clear_scheduled_hook( 'dpsp_cron_get_posts_networks_share_count', array( 'daily' ) );
		wp_clear_scheduled_hook( 'dpsp_cron_get_posts_networks_share_count', array( 'weekly' ) );
		
		wp_clear_scheduled_hook( 'dpsp_cron_get_posts_networks_share_count' );

		wp_clear_scheduled_hook( 'dpsp_cron_update_serial_key_status' );

	}


	/*
	 * Checks the status of the users serial key and updates the returned value
	 *
	 * @return void
	 *
	 */
	function dpsp_cron_update_serial_key_status() {

		dpsp_update_serial_key_status();

	}
	add_action( 'dpsp_cron_update_serial_key_status', 'dpsp_cron_update_serial_key_status' );


	/*
	 * Disables old unused cron jobs and enables the new ones
	 * 
	 * @return void
	 * 
	 */
	function dpsp_cron_disable_old_crons( $old_plugin_version = '', $new_plugin_version = '' ) {

		// In version 1.6.0 the cron job handling social shares was deprecated and 
		// three new cron jobs were added
		if( version_compare( $new_plugin_version, '1.6.0' ) != -1 ) {

			// Stop cron jobs
			dpsp_stop_cron_jobs();

			// Add new cron jobs
			dpsp_set_cron_jobs();
			
		}

	}
	add_action( 'dpsp_update_database', 'dpsp_cron_disable_old_crons', 10, 2 );


	/*
	 * Retreives the share counts for each post, for each network and saves
	 * them in the post meta
	 *
	 * @param string $recurrence
	 *
	 * @return void
	 *
	 */
	function dpsp_cron_get_posts_networks_share_count() {

		$settings = get_option( 'dpsp_settings', array() );

		/*
		 * Start with getting all post types saved in every 
		 * settings page. We only wish to get share counts for the
		 * posts that have these certain post types.
		 *
		 * Also get all active social networks from each of the
		 * settings page
		 *
		 */
		$locations  	 = dpsp_get_network_locations();
		$social_networks = dpsp_get_active_networks();
		$post_types 	 = array();

		foreach( $locations as $location ) {
			
			$location_settings = get_option( 'dpsp_location_' . $location );

			/*
			 * Get post types of settings page
			 *
			 */
			if( isset( $location_settings['post_type_display'] ) )
				$post_types = array_merge( $post_types, $location_settings['post_type_display'] );
			
		}


		/*
		 * Filter post types
		 *
		 */
		$post_types = array_unique( $post_types );
		$registered_post_types = get_post_types();

		foreach( $post_types as $key => $post_type ) {
			if( !in_array($post_type, $registered_post_types) )
				unset( $post_types[$key] );
		}


		/*
		 * Get all posts for each post type saved in every
		 * settings page and get network share counts
		 *
		 */
		$args = array( 'post_type' => $post_types, 'numberposts' => 20 );

		// If a facebook app access token is present grab more posts
		if( ! empty( $settings['facebook_app_access_token'] ) )
			$args['numberposts'] = 500;
		
		// Get posts
		$posts = get_posts( $args );


		// Exit execution for following statements
		if( empty( $posts ) )
			return;

		if( empty( $social_networks ) )
			return;


		// Continue if we reach this point
		foreach( $posts as $post_object ) {

			// Get social shares from the networks
			$share_counts 	= dpsp_pull_post_share_counts( $post_object->ID );

			// Update share counts in the db
			$shares_updated = dpsp_update_post_share_counts( $post_object->ID, $share_counts );


		} // End of posts loop
		
	}
	//add_action( 'dpsp_cron_get_posts_networks_share_count', 'dpsp_cron_get_posts_networks_share_count' );