<?php 

	/*
	 * Function that creates the sub-menu item and page for the import/export
	 *
	 * @return void
	 *
	 */
	function dpsp_register_import_export_subpage() {
		add_submenu_page( 'dpsp-social-pug', __('Import/Export', 'social-pug'), __('Import/Export', 'social-pug'), 'manage_options', 'dpsp-import-export', 'dpsp_import_export_subpage' );
	}
	add_action( 'admin_menu', 'dpsp_register_import_export_subpage', 99 );


	/*
	 * Function that adds content to the import/export subpage
	 *
	 * @return string
	 *
	 */
	function dpsp_import_export_subpage() {

		include_once 'views/view-submenu-page-import-export.php';

	}


	/*
	 * Function that handles the processes for import and export settings
	 *
	 */
	function dpsp_process_import_export() {

		if( !isset( $_POST['_wpnonce'] ) || !wp_verify_nonce( $_POST['_wpnonce'], 'dpsp_import_export' ) )
			return;

		$admin_page = ( isset( $_GET['page'] ) ? $_GET['page'] : '' );


		/*
		 * Process import
		 */
		if( isset( $_POST['dpsp_import_settings'] ) ) {
			
			if( isset( $_FILES['dpsp_import_file'] ) && !empty( $_FILES['dpsp_import_file']['tmp_name'] ) ) {

				//Check to see if this is a json file
				if( strpos( $_FILES['dpsp_import_file']['name'], '.json' ) === false ) {
					wp_redirect( admin_url( 'admin.php?page=' . $admin_page . '&settings-updated=false&dpsp_message_id=3&dpsp_message_class=error' ) );
					exit;
				}


				$file_contents = json_decode( file_get_contents( $_FILES['dpsp_import_file']['tmp_name'] ), ARRAY_A );

				if( isset( $file_contents ) && !empty( $file_contents ) ) {

					// Add data into the locations
					if( isset( $file_contents['locations'] ) && !empty( $file_contents['locations'] ) ) {
						foreach( $file_contents['locations'] as $location => $locations_data ) {
							update_option( 'dpsp_location_' . $location, $locations_data );
						}
					}

					// Add data into general settings
					if( !empty( $file_contents['settings'] ) ) {
						update_option( 'dpsp_settings', $file_contents['settings'] );
					}

					// Add data into active tools
					if( !empty( $file_contents['active_tools'] ) ) {
						update_option( 'dpsp_active_tools', $file_contents['active_tools'] );
					}

					// Redirect with success message
					wp_redirect( admin_url( 'admin.php?page=' . $admin_page . '&settings-updated=false&dpsp_message_id=1' ) );
					exit;

				}

			} else {

				// Redirect with error message that the file is missing
				wp_redirect( admin_url( 'admin.php?page=' . $admin_page . '&settings-updated=false&dpsp_message_id=2&dpsp_message_class=error' ) );
				exit;
				
			}

		}


		/*
		 * Process export
		 */
		if( isset( $_POST['dpsp_export_settings'] ) ) {

			$export = array();

			/** 
			 * Export locations
			 *
			 */
			$locations = dpsp_get_network_locations();

			foreach( $locations as $location ) {

				$location_settings = get_option( 'dpsp_location_' . $location );

				$export['locations'][$location] = $location_settings;

			}

			/** 
			 * Export default settings
			 *
			 */
			$settings = get_option( 'dpsp_settings', array() );

			// Remove serial key if it exists, we don't want it exposed
			if( !empty( $settings['product_serial'] ) )
				unset( $settings['product_serial'] );

			$export['settings'] = $settings;

			/**
			 * Export active tools
			 *
			 */
			$active_tools = get_option( 'dpsp_active_tools', array() );

			$export['active_tools'] = $active_tools;

			/**
			 * Actual Export
			 *
			 */
			header('Content-disposition: attachment; filename=socialpug-export-' . strtolower( date("d-F-Y-H-i-s") ) . '.json');
			header('Content-type: application/json');

			echo( json_encode($export) );
			exit;

		}
		

	}
	add_action( 'admin_init', 'dpsp_process_import_export' );