<?php

	/**
	 * Returns all the tools available with all their data
	 *
	 * @param string $type
	 * @param bool $only_slugs
	 *
	 * @return array
	 *
	 */
	function dpsp_get_tools( $type = 'all', $only_slugs = false ) {

		// The tools array
		$tools = array();

		/**
		 * Possibility to add other tools into the tools array
		 *
		 * @param array $tools
		 *
		 */
		$tools = apply_filters( 'dpsp_get_tools', $tools );

		// Return only the tools of a certain type
		if( $type != 'all' && !empty( $tools ) ) {
			foreach( $tools as $tool_slug => $tool ) {
				if( $tool['type'] != $type )
					unset( $tools[$tool_slug] );
			}
		}

		// Return only the slugs
		if( $only_slugs )
			$tools = array_keys( $tools );

		return $tools;

	}


	/**
	 * Returns all active tools
	 *
	 * Does not take into account the custom activation settings of the tools
	 *
	 * @return array
	 *
	 */
	function dpsp_get_active_tools() {

		$active_tools = get_option( 'dpsp_active_tools', array() );

		return $active_tools;

	}


	/**
	 * Checks to see if the tool settings is active or not
	 *
	 * @param string $tool_slug
	 *
	 * @return bool
	 *
	 */
	function dpsp_is_tool_active( $tool_slug ) {

		$tools = dpsp_get_tools();

		// Handle if there is no particular activation setting for the tool
		if( empty( $tools[$tool_slug]['activation_setting'] ) ) {

			$active_tools = dpsp_get_active_tools();

			if( in_array( $tool_slug, $active_tools ) )
				return true;
			else
				return false;

		}

		$setting = $tools[$tool_slug]['activation_setting'];

		$option_name = explode( '[', $setting );
		$option_name = $option_name[0];

		$settings 	 = get_option( $option_name );

		if( isset( $settings[ str_replace( array( $option_name, '[', ']' ) , '', $setting ) ] ) )
			return true;
		else
			return false;

	}


	/**
	 * Activates a network location
	 *
	 */
	function dpsp_activate_tool() {

		if( empty( $_POST['dpsptkn'] ) || !wp_verify_nonce( $_POST['dpsptkn'], 'dpsptkn' ) ) {
			echo 0;
			wp_die();
		}

		$tool  = trim( $_POST['tool'] );
		$tools = dpsp_get_tools();

		// Update the tools array
		$active_tools = get_option( 'dpsp_active_tools', array() );

		if( ! in_array( $tool, $active_tools ) )
			array_push( $active_tools, $tool );

		$active_tools = array_unique( $active_tools );

		update_option( 'dpsp_active_tools', $active_tools );

		// Update the activation setting if there is one
		$tool_setting = ( ! empty( $tools[$tool]['activation_setting'] ) ? $tools[$tool]['activation_setting'] : '' );

		if( !empty( $tool_setting ) ) {

			$option_name   = explode( '[', $tool_setting );
			$option_name   = $option_name[0];

			$settings 	   = get_option( $option_name );
			$active_option = str_replace( array( $option_name, '[', ']' ) , '', $tool_setting );

			if( !isset( $settings[$active_option] ) ) {

				$settings[$active_option] = 1;
				update_option( $option_name, $settings );

			}

		}

		echo 1;
		wp_die();

	}
	add_action( 'wp_ajax_dpsp_activate_tool', 'dpsp_activate_tool' );


	/**
	 * Deactivates a network location
	 *
	 */
	function dpsp_deactivate_tool() {

		if( empty( $_POST['dpsptkn'] ) || !wp_verify_nonce( $_POST['dpsptkn'], 'dpsptkn' ) ) {
			echo 0;
			wp_die();
		}

		$tool  = trim( $_POST['tool'] );
		$tools = dpsp_get_tools();

		// Update the tools array
		$active_tools = get_option( 'dpsp_active_tools', array() );

		if( false !== ( $key = array_search( $tool, $active_tools ) ) ) {
			unset( $active_tools[$key] );
			$active_tools = array_values( $active_tools );
		}

		$active_tools = array_unique( $active_tools );

		update_option( 'dpsp_active_tools', $active_tools );

		// Update the activation setting if there is one
		$tool_setting = ( ! empty( $tools[$tool]['activation_setting'] ) ? $tools[$tool]['activation_setting'] : '' );

		if( !empty( $tool_setting ) ) {

			$option_name = explode( '[', $tool_setting );
			$option_name = $option_name[0];

			$settings 	 = get_option( $option_name );
			$active_option = str_replace( array( $option_name, '[', ']' ) , '', $tool_setting );

			if( isset( $settings[$active_option] ) ) {

				unset($settings[$active_option]);
				update_option( $option_name, $settings );

			}

		}

		echo 1;
		wp_die();

	}
	add_action( 'wp_ajax_dpsp_deactivate_tool', 'dpsp_deactivate_tool' );