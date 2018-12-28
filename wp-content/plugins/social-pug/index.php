<?php
/**
 * Plugin Name: Social Pug
 * Plugin URI: http://www.devpups.com/social-pug/
 * Description: Add beautiful social sharing buttons to your posts, pages and custom post types.
 * Version: 2.4.0
 * Author: DevPups
 * Text Domain: social-pug
 * Author URI: http://www.devpups.com/
 * License: GPL2
 */


Class Social_Pug {


	/*
	 * The Constructor
	 *
	 */
	public function __construct() {

		// Defining constants
		define('DPSP_VERSION', '2.4.0');
		define('DPSP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename(__FILE__) ) );
		define('DPSP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
		define('DPSP_TRANSLATION_TEXTDOMAIN', 'social-pug' );

		// Hooks
		add_action( 'init', array( $this, 'init_translation' ) );
		add_action( 'admin_menu', array( $this, 'add_main_menu_page' ), 10 );
		add_action( 'admin_menu', array( $this, 'remove_main_menu_page' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'init_admin_scripts' ), 100 );
		add_action( 'wp_enqueue_scripts', array( $this, 'init_front_end_scripts' ) );
		add_action( 'admin_init', array( $this, 'update_database' ) );
		add_action( 'plugins_loaded', array( $this, 'load_update_checker') );

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_plugin_action_links' ) );

		$this->load_resources_front_end();

		add_action( 'init', array( $this, 'load_resources_admin' ) );

		$this->load_tools();

	}


	/*
	 * Loads the translations files if they exist
	 *
	 */
	public function init_translation() {

		load_plugin_textdomain( DPSP_TRANSLATION_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );

	}


	/*
	 * Add the main menu page
	 *
	 */
	public function add_main_menu_page() {

		add_menu_page( __('Social Pug', 'social-pug'), __('Social Pug', 'social-pug'), 'manage_options', 'dpsp-social-pug', '','dashicons-share' );

	}


	/*
	 * Remove the main menu page as we will rely only on submenu pages
	 *
	 */
	public function remove_main_menu_page() {

		remove_submenu_page( 'dpsp-social-pug', 'dpsp-social-pug' );

	}


	/*
	 * Enqueue scripts and styles for the admin dashboard
	 *
	 */
	public function init_admin_scripts( $hook ) {

		if( strpos( $hook, 'dpsp' ) !== false ) {

            wp_register_script( 'select2-js', DPSP_PLUGIN_DIR_URL . 'assets/libs/select2/select2.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'select2-js' );

			wp_register_style( 'select2-css', DPSP_PLUGIN_DIR_URL . 'assets/libs/select2/select2.min.css' );
			wp_enqueue_style( 'select2-css' );

			wp_register_script( 'dpsp-touch-punch-js' , plugin_dir_url( __FILE__ ) . 'assets/js/jquery.ui.touch-punch.min.js', array('jquery-ui-sortable', 'jquery' ) );
			wp_enqueue_script( 'dpsp-touch-punch-js' );

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		wp_register_style( 'dpsp-dashboard-style', DPSP_PLUGIN_DIR_URL . 'assets/css/style-dashboard.css', array(), DPSP_VERSION );
		wp_enqueue_style( 'dpsp-dashboard-style' );

		wp_register_script( 'dpsp-dashboard-js' , DPSP_PLUGIN_DIR_URL . 'assets/js/dashboard.js', array( 'jquery-ui-sortable', 'jquery' ), DPSP_VERSION );
		wp_enqueue_script( 'dpsp-dashboard-js' );

		wp_register_style( 'dpsp-frontend-style', DPSP_PLUGIN_DIR_URL . 'assets/css/style-frontend.css', array(), DPSP_VERSION );
		wp_enqueue_style( 'dpsp-frontend-style' );

	}


	/*
	 * Enqueue scripts for the front-end
	 *
	 */
	public function init_front_end_scripts() {

		wp_register_style( 'dpsp-frontend-style', plugin_dir_url( __FILE__ ) . 'assets/css/style-frontend.css', array(), DPSP_VERSION );
		wp_enqueue_style( 'dpsp-frontend-style' );

		wp_register_script( 'dpsp-frontend-js', plugin_dir_url( __FILE__ ) . 'assets/js/front-end.js', array('jquery'), DPSP_VERSION );
		wp_enqueue_script( 'dpsp-frontend-js' );

	}


	/*
	 * Fallback for setting defaults when updating the plugin,
	 * as register_activation_hook does not fire for automatic updates
	 *
	 */
	public function update_database() {

		$dpsp_db_version = get_option( 'dpsp_version', '' );

		if( $dpsp_db_version != DPSP_VERSION ) {

			dpsp_default_settings();
			update_option( 'dpsp_version', DPSP_VERSION );

			// Add first time activation
			if( get_option( 'dpsp_first_activation', '' ) == '' ) {

				update_option( 'dpsp_first_activation', time() );


				/**
				 * Do extra actions on plugin's first ever activation
				 *
				 */
				do_action( 'dpsp_first_activation' );

			}

			// Update Sidebar button style from 1,2,3 to 1,5,8
			$dpsp_location_sidebar = dpsp_get_location_settings('sidebar');

			if( $dpsp_location_sidebar['button_style'] == 2 )
				$dpsp_location_sidebar['button_style'] = 5;

			if( $dpsp_location_sidebar['button_style'] == 3 )
				$dpsp_location_sidebar['button_style'] = 8;

			update_option( 'dpsp_location_sidebar', $dpsp_location_sidebar );

			/**
			 * Do extra database updates on plugin update
			 *
			 * @param string $dpsp_db_version - the previous version of the plugin
			 * @param string DPSP_VERSION     - the new (current) version of the plugin
			 *
			 */
			do_action( 'dpsp_update_database', $dpsp_db_version, DPSP_VERSION );

		}

	}


	/*
	 * Initialize plugin update checker
	 *
	 */
	public function load_update_checker() {

		$dpsp_settings = get_option( 'dpsp_settings' );

		if( !empty( $dpsp_settings['product_serial'] ) ) {

			$serial 	    = $dpsp_settings['product_serial'];
			$update_checker = new DPSP_PluginUpdateChecker( 'http://updates.devpups.com/?serial=' . $serial . '&action=get_update', __FILE__, 'social-pug' );

		}

	}


	/*
	 * Add extra action links in the plugins page
	 *
	 */
	public function add_plugin_action_links( $links ) {

		$links[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=dpsp-toolkit' ) ) . '">' . __( 'Settings', 'social-pug' ) . '</a>';

		return $links;

	}


	/*
	 * Include plugin files for the front end
	 *
	 */
	public function load_resources_front_end() {

		// Database version update file
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-version-update.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-version-update.php' );

		// Functions
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-post.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-post.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-tools.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-tools.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-mobile.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-mobile.php' );

		// Include classes
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/class-mobile-detect.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/class-mobile-detect.php' );

		// Share counts functions
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-share-counts.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-share-counts.php' );

		// Cron jobs
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-cron.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-cron.php' );

		// Widgets
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/admin-widgets.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/admin-widgets.php' );

		// Frontend rendering
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/class-buttons-outputter.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/class-buttons-outputter.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-frontend.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-frontend.php' );

		// Shortcodes
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/class-shortcodes.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/class-shortcodes.php' );

		// Update checker
		if ( file_exists( DPSP_PLUGIN_DIR . '/inc/class-update-checker.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/class-update-checker.php' );

		/**
		 * Helper hook to include files early
		 *
		 */
		do_action( 'dpsp_include_files' );

	}


	/*
	 * Include plugin files for the admin area
	 *
	 */
	public function load_resources_admin() {

		// Admin functions and pages
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/functions-admin.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/functions-admin.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-toolkit.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-toolkit.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-import-export.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-import-export.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-settings.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-settings.php' );

		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-debugger.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/submenu-page-debugger.php' );

		// Admin extras
		if( file_exists( DPSP_PLUGIN_DIR . '/inc/admin/admin-metaboxes.php' ) )
			include_once( DPSP_PLUGIN_DIR . '/inc/admin/admin-metaboxes.php' );

	}


	/**
	 * Include files for the tools in the toolkit
	 *
	 */
	public function load_tools() {

		$dirs = array_filter( glob( DPSP_PLUGIN_DIR . '/inc/tools/*' ), 'is_dir');

        foreach( $dirs as $dir ){
            if( file_exists( $file =  $dir . '/' . basename($dir) . '.php') ){
                include_once ( $file );
            }
        }

	}

}

// Let's get the party started
new Social_Pug;


/*
 * Activation hooks
 *
 */
register_activation_hook( __FILE__, 'dpsp_default_settings' );
register_activation_hook( __FILE__, 'dpsp_set_cron_jobs' );
register_activation_hook( __FILE__, 'dpsp_toolkit_activate' );


/*
 * Deactivation hooks
 *
 */
register_deactivation_hook( __FILE__, 'dpsp_stop_cron_jobs' );