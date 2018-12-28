<?php

/**
 * Because certain widgets / plugins reset the global $post variable
 * we are going to cache it when WP has just loaded, so that we have the
 * original post available at all times
 *
 */
function dpsp_cache_post_object() {

	global $dpsp_cache_wp_post;

	$dpsp_cache_wp_post = null;

	if( is_singular() && ! is_front_page() && ! is_home() )	{

		global $post;
		$dpsp_cache_wp_post = $post;

	}
	
}
add_action( 'wp', 'dpsp_cache_post_object' );


/**
 * Returns the current post object
 *
 * @return mixed - WP_Post | null
 *
 */
function dpsp_get_current_post() {

	global $dpsp_cache_wp_post;

	if( ! is_null( $dpsp_cache_wp_post ) )
		return $dpsp_cache_wp_post;

	global $post;

	if( ! is_null( $post ) )
		return $post;

	return null;

}


/**
 * Returns the post object for the given post id
 *
 * @param int $post_id
 *
 * @param mixed - WP_Post | null
 *
 */
function dpsp_get_post( $post_id = 0 ) {

	if( empty( $post_id ) )
		return null;

	$current_post = dpsp_get_current_post();

	if( ! is_null( $current_post ) && $current_post->ID == $post_id )
		return $current_post;

	return get_post( $post_id );

}


/**
 * Returns the url of the given post
 *
 * @param int $post_id
 *
 * @return string
 *
 */
function dpsp_get_post_url( $post_id = 0 ) {

	$post_obj = dpsp_get_post( $post_id );

	if( is_null( $post_obj ) )
		return '';

	$post_url = get_permalink( $post_obj );

	/**
	 * Filter the post URL before returning
	 *
	 * @param string $post_url
	 * @param int    $post_id
	 *
	 */
	return apply_filters( 'dpsp_get_post_url', $post_url, $post_obj->ID );

}


/**
 * Returns the title of the given post
 *
 * @param int $post_id
 *
 * @return string
 *
 */
function dpsp_get_post_title( $post_id = 0 ) {

	$post_obj = dpsp_get_post( $post_id );

	if( is_null( $post_obj ) )
		return '';

	$post_title = $post_obj->post_title;

	/**
	 * Filter the post title before returning
	 *
	 * @param string $post_title
	 * @param int    $post_id
	 *
	 */
	return apply_filters( 'dpsp_get_post_title', $post_title, $post_obj->ID );

}


/**
 * Returns the a description for the given post
 *
 * @param int $post_id
 *
 * @return string
 *
 */
function dpsp_get_post_description( $post_id = 0 ) {

	$post_obj = dpsp_get_post( $post_id );

	if( is_null( $post_obj ) )
		return '';

	// Check to see if the post has an excerpt
	if( ! empty( $post_obj->post_excerpt ) )

		$post_description = $post_obj->post_excerpt;

	// If not, strip the content
	elseif( ! empty( $post_obj->post_content ) ) {

		$post_description = strip_shortcodes( $post_obj->post_content );
		$post_description = wp_trim_words( $post_description, apply_filters( 'dpsp_post_description_length', 35 ), '' );

	} else 
		$post_description = '';

	/**
	 * Filter the post description before returning
	 *
	 * @param string $post_description
	 * @param int    $post_id
	 *
	 */
	return apply_filters( 'dpsp_get_post_description', $post_description, $post_obj->ID );

}


/**
 * Returns the featured image data for the given post
 *
 * @param int    $post_id
 * @param string $size
 *
 * @return mixed array | null
 *
 */
function dpsp_get_post_image_data( $post_id = 0, $size = 'full' ) {

	$post_obj = dpsp_get_post( $post_id );

	if( is_null( $post_obj ) )
		return null;

	$post_thumbnail_id   = get_post_thumbnail_id( $post_obj->ID );
	$post_thumbnail_data = wp_get_attachment_image_src( $post_thumbnail_id, $size );

	if( false === $post_thumbnail_data )
		$post_thumbnail_data = null;

	/**
	 * Filter the post image data before returning
	 *
	 * @param array  $post_thumbnail_data
	 * @param int    $post_id
	 * @param string $size
	 *
	 */
	return apply_filters( 'dpsp_get_post_image_data', $post_thumbnail_data, $post_obj->ID, $size );

}


/**
 * Returns the featured image URL for the given post
 *
 * @param int    $post_id
 * @param string $size
 *
 * @return mixed string | false
 *
 */
function dpsp_get_post_image_url( $post_id = 0, $size = 'full' ) {

	// Get post image data
	$image_data = dpsp_get_post_image_data( $post_id, $size );

	if( ! is_array( $image_data ) )
		return false;

	$post_thumbnail_url = $image_data[0];

	/**
	 * Filter the post image URL before returning
	 *
	 * @param array  $post_thumbnail_data
	 * @param int    $post_id
	 * @param string $size
	 *
	 */
	return apply_filters( 'dpsp_get_post_image_url', $post_thumbnail_url, $post_id, $size );

}


/**
 * Returns the custom post title set in the Custom Social Options meta-box for a given post
 *
 * @param int $post_id
 *
 * @return string
 *
 */
function dpsp_get_post_custom_title( $post_id = 0 ) {

	// Check to see if a custom title is in place
	$share_options = get_post_meta( $post_id, 'dpsp_share_options', true );

	// Set custom title
	$post_title = ( ! empty( $share_options['custom_title'] ) ? $share_options['custom_title'] : '' );

	return apply_filters( 'dpsp_get_post_custom_title', $post_title, $post_id );

}


/**
 * Returns the custom post description set in the Custom Social Options meta-box
 *
 * @return string
 *
 */
function dpsp_get_post_custom_description( $post_id = 0 ) {

	// Check to see if a custom description is in place
	$share_options = get_post_meta( $post_id, 'dpsp_share_options', true );

	// Set post description
	$post_description = ( ! empty( $share_options['custom_description'] ) ? $share_options['custom_description'] : '' );

	return apply_filters( 'dpsp_get_post_custom_description', $post_description, $post_id );

}


/**
 * Returns the custom post image data set in the Custom Social Options meta-box
 *
 * @return mixed array | null
 *
 */
function dpsp_get_post_custom_image_data( $post_id = 0, $size = 'full' ) {

	// Check to see if a custom description is in place
	$share_options = get_post_meta( $post_id, 'dpsp_share_options', true );

	if( empty( $share_options['custom_image']['id'] ) )
		return null;

	$post_image_id   = (int)$share_options['custom_image']['id'];
	$post_image_data = wp_get_attachment_image_src( $post_image_id, $size );

	return apply_filters( 'dpsp_get_post_custom_image_data', $post_image_data, $post_id, $size );

}


/**
 * If the custom post title of the post is set in the Custom Social Options meta-box,
 * return it instead of the default post title
 *
 * @param string
 *
 * @return string
 *
 */
function dpsp_add_custom_post_title( $post_title = '', $post_id = 0 ) {

	$custom_title = dpsp_get_post_custom_title( $post_id );
	$post_title   = ( ! empty( $custom_title ) ? $custom_title : $post_title );

	return $post_title;

}
add_filter( 'dpsp_get_post_title', 'dpsp_add_custom_post_title', 10, 2 );


/**
 * If the custom post description of the post is set in the Custom Social Options meta-box,
 * return it instead of the default post description
 *
 * @param string
 *
 * @return string
 *
 */
function dpsp_add_custom_post_description( $post_description = '', $post_id = 0 ) {

	$custom_description = dpsp_get_post_custom_description( $post_id );
	$post_description   = ( ! empty( $custom_description ) ? $custom_description : $post_description );

	return $post_description;

}
add_filter( 'dpsp_get_post_description', 'dpsp_add_custom_post_description', 10, 2 );


/**
 * If the custom post image data of the post is set in the Custom Social Options meta-box,
 * return it instead of the default post image data
 *
 * @param string
 *
 * @return string
 *
 */
function dpsp_add_custom_post_image_data( $post_image_data = array(), $post_id = 0, $size = '' ) {

	$custom_image_data = dpsp_get_post_custom_image_data( $post_id, $size );
	$post_image_data   = ( ! is_null( $custom_image_data ) ? $custom_image_data : $post_image_data );

	return $post_image_data;

}
add_filter( 'dpsp_get_post_image_data', 'dpsp_add_custom_post_image_data', 10, 3 );


/**
 * Returns the custom post description for Pinterest set in the Custom Social Options meta-box
 *
 * @return string
 *
 */
function dpsp_get_post_pinterest_description( $post_id = 0 ) {

	// Check to see if a custom description is in place
	$share_options = get_post_meta( $post_id, 'dpsp_share_options', true );

	// Set post Pinterest description
	$pinterest_description = ( ! empty( $share_options['custom_description_pinterest'] ) ? $share_options['custom_description_pinterest'] : '' );

	return apply_filters( 'dpsp_get_post_pinterest_description', $pinterest_description, $post_id );

}