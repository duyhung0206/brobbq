<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file. 
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function generatepress_child_enqueue_scripts() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'generatepress-rtl', trailingslashit( get_template_directory_uri() ) . 'rtl.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100 );

function wp_include_css()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri().'/css/bootstrap.min.css', array(), false, 'all');
    wp_enqueue_style('main-style', get_template_directory_uri().'/style.css', array(), false, 'all');
}

add_action('wp_enqueue_scripts', 'wp_include_css');
