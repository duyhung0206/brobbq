<?php
/*

 It's not recommended to add functions to this file, as it will be lost if you ever update this child theme.
 Instead, consider adding your function into a plugin using Pluginception: https://wordpress.org/plugins/pluginception/
 
 */

if ( function_exists( 'generate_blog_get_defaults' ) ) :
	if ( !function_exists( 'forefront_new_blog_defaults' ) ) :
		add_filter( 'generate_blog_option_defaults','forefront_new_blog_defaults' );
		function forefront_new_blog_defaults( $new_defaults )
		{
			$new_defaults[ 'excerpt_length' ] = '55';
			$new_defaults[ 'read_more' ] = __('Read more...','generate-blog');
			$new_defaults[ 'masonry' ] = 'false';
			$new_defaults[ 'masonry_width' ] = 'width2';
			$new_defaults[ 'masonry_most_recent_width' ] = 'width4';
			$new_defaults[ 'masonry_load_more' ] = __('+ More','generate-blog');
			$new_defaults[ 'masonry_loading' ] = 'Loading...';
			$new_defaults[ 'post_image' ] = 'true';
			$new_defaults[ 'post_image_position' ] = 'post-image-above-header';
			$new_defaults[ 'post_image_alignment' ] = 'post-image-aligned-center';
			$new_defaults[ 'post_image_width' ] = '';
			$new_defaults[ 'post_image_height' ] = '';
			$new_defaults[ 'date' ] = 'true';
			$new_defaults[ 'author' ] = 'true';
			$new_defaults[ 'categories' ] = 'true';
			$new_defaults[ 'tags' ] = 'true';
			$new_defaults[ 'comments' ] = 'true';
			$new_defaults[ 'column_layout' ] = 0;
			$new_defaults[ 'columns' ] = '50';
			$new_defaults[ 'featured_column' ] = 0;
			return $new_defaults;
		}
	endif;
endif;

add_action('wp','forefront_setup');
function forefront_setup()
{
	
	if ( !function_exists( 'generate_blog_get_defaults' ) ) :
		remove_action( 'generate_after_entry_header', 'generate_post_image' );
		
		if ( function_exists('generate_page_header') ) :
			remove_action( 'generate_after_entry_header', 'generate_page_header_post_image' );
			add_action( 'generate_before_content', 'generate_page_header_post_image' );
		endif;
	endif;
	
}

/**
 * Add dynamic CSS
 * @since 0.6
 */
function forefront_custom_css()
{

	if ( function_exists( 'generate_spacing_get_defaults' ) ) :
		
		$spacing_settings = wp_parse_args( 
			get_option( 'generate_spacing_settings', array() ), 
			generate_spacing_get_defaults() 
		);
			
	endif;
	
	if ( function_exists( 'generate_blog_get_defaults' ) ) :
		
		$blog_settings = wp_parse_args( 
			get_option( 'generate_blog_settings', array() ), 
			generate_blog_get_defaults() 
		);
			
	endif;
	
	if ( function_exists('generate_spacing_get_defaults') ) :
		$top_padding = $spacing_settings['content_top'];
		$right_padding = $spacing_settings['content_right'];
		$bottom_padding = $spacing_settings['content_bottom'];
		$left_padding = $spacing_settings['content_left'];
	else :
		$top_padding = 40;
		$right_padding = 40;
		$bottom_padding = 40;
		$left_padding = 40;
	endif;
	
	$return = '';
	
	if ( function_exists( 'generate_blog_get_defaults' ) ) :
		if ( '' == $blog_settings['post_image_position'] ) :
			$return .= '.separate-containers .post-image, .separate-containers .inside-article .page-header-image-single, .separate-containers .inside-article .page-header-image, .separate-containers .inside-article .page-header-content-single, .no-sidebar .inside-article .page-header-image-single, .no-sidebar .inside-article .page-header-image, article .inside-article .page-header-post-image { margin: ' . $bottom_padding . 'px -' . $right_padding . 'px ' . $bottom_padding . 'px -' . $left_padding . 'px }';
		else :
			$return .= '.separate-containers .post-image, .separate-containers .inside-article .page-header-image-single, .separate-containers .inside-article .page-header-image, .separate-containers .inside-article .page-header-content-single, .no-sidebar .inside-article .page-header-image-single, .no-sidebar .inside-article .page-header-image, article .inside-article .page-header-post-image { margin: -' . $top_padding . 'px -' . $right_padding . 'px ' . $bottom_padding . 'px -' . $left_padding . 'px }';
		endif;
	else :
		$return .= '.separate-containers .post-image, .separate-containers .inside-article .page-header-image-single, .separate-containers .inside-article .page-header-image, .separate-containers .inside-article .page-header-content-single, .no-sidebar .inside-article .page-header-image-single, .no-sidebar .inside-article .page-header-image, article .inside-article .page-header-post-image { margin: -' . $top_padding . 'px -' . $right_padding . 'px ' . $bottom_padding . 'px -' . $left_padding . 'px }';
	endif;
	
	return $return;
}

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'forefront_scripts', 50 );
function forefront_scripts() {
	wp_add_inline_style( 'generate-style', forefront_custom_css() );
}

/**
 * Reset customizer settings so the child theme shows up
 */
add_action( 'admin_notices', 'forefront_reset_customizer_settings' );
function forefront_reset_customizer_settings() {
	global $pagenow;
	$generate_settings = get_option('generate_settings');
	
	if ( empty($generate_settings) )
		return;
	
	if ( is_admin() && $pagenow == "themes.php" && isset( $_GET['activated'] ) ) {
		?>
		<div class="updated settings-error notice is-dismissible">
			<p>
				<?php printf( __( '<strong>Almost done!</strong> Previous GeneratePress options detected in your database. Please <a href="%s">click here</a> to delete your current options for Forefront to take full effect.','forefront' ), admin_url('themes.php?page=generate-options#gen-delete') ); ?>
			</p>
		</div>
		<?php
	}
}
if ( !function_exists( 'forefront_new_defaults' ) ) :
add_filter( 'generate_option_defaults','forefront_new_defaults' );
function forefront_new_defaults( $new_defaults )
{
	$new_defaults[ 'hide_title' ] = '';
	$new_defaults[ 'hide_tagline' ] = '';
	$new_defaults[ 'logo' ] = '';
	$new_defaults[ 'container_width' ] = '1200';
	$new_defaults[ 'header_layout_setting' ] = 'contained-header';
	$new_defaults[ 'center_header' ] = 'true';
	$new_defaults[ 'center_nav' ] = 'true';
	$new_defaults[ 'nav_alignment_setting' ] = 'center';
	$new_defaults[ 'header_alignment_setting' ] = 'center';
	$new_defaults[ 'nav_layout_setting' ] = 'contained-nav';
	$new_defaults[ 'nav_position_setting' ] = 'nav-below-header';
	$new_defaults[ 'nav_search' ] = 'disable';
	$new_defaults[ 'nav_dropdown_type' ] = 'hover';
	$new_defaults[ 'content_layout_setting' ] = 'separate-containers';
	$new_defaults[ 'layout_setting' ] = 'both-sidebars';
	$new_defaults[ 'blog_layout_setting' ] = 'both-sidebars';
	$new_defaults[ 'single_layout_setting' ] = 'both-sidebars';
	$new_defaults[ 'post_content' ] = 'full';
	$new_defaults[ 'footer_layout_setting' ] = 'contained-footer';
	$new_defaults[ 'footer_widget_setting' ] = '3';
	$new_defaults[ 'back_to_top' ] = '';
	$new_defaults[ 'background_color' ] = '#222222';
	$new_defaults[ 'text_color' ] = '#3a3a3a';
	$new_defaults[ 'link_color' ] = '#1e73be';
	$new_defaults[ 'link_color_hover' ] = '#000000';
	$new_defaults[ 'link_color_visited' ] = '';
	
	return $new_defaults;
}
endif;

/**
 * Set default options
 */
if ( !function_exists( 'forefront_get_color_defaults' ) ) :
add_filter( 'generate_color_option_defaults','forefront_get_color_defaults' );
function forefront_get_color_defaults( $forefront_color_defaults )
{
	$forefront_color_defaults[ 'header_background_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'header_text_color' ] = '#3a3a3a';
	$forefront_color_defaults[ 'header_link_color' ] = '#3a3a3a';
	$forefront_color_defaults[ 'header_link_hover_color' ] = '';
	$forefront_color_defaults[ 'site_title_color' ] = '#222222';
	$forefront_color_defaults[ 'site_tagline_color' ] = '#999999';
	$forefront_color_defaults[ 'navigation_background_color' ] = '#D33232';
	$forefront_color_defaults[ 'navigation_text_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'navigation_background_hover_color' ] = '#dd5656';
	$forefront_color_defaults[ 'navigation_text_hover_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'navigation_background_current_color' ] = '#dd5656';
	$forefront_color_defaults[ 'navigation_text_current_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'subnavigation_background_color' ] = '#dd5656';
	$forefront_color_defaults[ 'subnavigation_text_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'subnavigation_background_hover_color' ] = '#E87171';
	$forefront_color_defaults[ 'subnavigation_text_hover_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'subnavigation_background_current_color' ] = '#E87171';
	$forefront_color_defaults[ 'subnavigation_text_current_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'content_background_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'content_text_color' ] = '#3a3a3a';
	$forefront_color_defaults[ 'content_link_color' ] = '';
	$forefront_color_defaults[ 'content_link_hover_color' ] = '';
	$forefront_color_defaults[ 'content_title_color' ] = '';
	$forefront_color_defaults[ 'blog_post_title_color' ] = '#2D2D2D';
	$forefront_color_defaults[ 'blog_post_title_hover_color' ] = '#D33232';
	$forefront_color_defaults[ 'entry_meta_text_color' ] = '#888888';
	$forefront_color_defaults[ 'entry_meta_link_color' ] = '#666666';
	$forefront_color_defaults[ 'entry_meta_link_color_hover' ] = '#D33232';
	$forefront_color_defaults[ 'h1_color' ] = '';
	$forefront_color_defaults[ 'h2_color' ] = '';
	$forefront_color_defaults[ 'h3_color' ] = '';
	$forefront_color_defaults[ 'sidebar_widget_background_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'sidebar_widget_text_color' ] = '#3a3a3a';
	$forefront_color_defaults[ 'sidebar_widget_link_color' ] = '#686868';
	$forefront_color_defaults[ 'sidebar_widget_link_hover_color' ] = '#D33232';
	$forefront_color_defaults[ 'sidebar_widget_title_color' ] = '#000000';
	$forefront_color_defaults[ 'footer_widget_background_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'footer_widget_text_color' ] = '#3a3a3a';
	$forefront_color_defaults[ 'footer_widget_link_color' ] = '#1e73be';
	$forefront_color_defaults[ 'footer_widget_link_hover_color' ] = '#000000';
	$forefront_color_defaults[ 'footer_widget_title_color' ] = '#000000';
	$forefront_color_defaults[ 'footer_background_color' ] = '#D33232';
	$forefront_color_defaults[ 'footer_text_color' ] = '#ffffff';
	$forefront_color_defaults[ 'footer_link_color' ] = '#ffffff';
	$forefront_color_defaults[ 'footer_link_hover_color' ] = '#222222';
	$forefront_color_defaults[ 'form_background_color' ] = '#FAFAFA';
	$forefront_color_defaults[ 'form_text_color' ] = '#666666';
	$forefront_color_defaults[ 'form_background_color_focus' ] = '#FFFFFF';
	$forefront_color_defaults[ 'form_text_color_focus' ] = '#666666';
	$forefront_color_defaults[ 'form_border_color' ] = '#CCCCCC';
	$forefront_color_defaults[ 'form_border_color_focus' ] = '#BFBFBF';
	$forefront_color_defaults[ 'form_button_background_color' ] = '#666666';
	$forefront_color_defaults[ 'form_button_background_color_hover' ] = '#606060';
	$forefront_color_defaults[ 'form_button_text_color' ] = '#FFFFFF';
	$forefront_color_defaults[ 'form_button_text_color_hover' ] = '#FFFFFF';
	
	return $forefront_color_defaults;
}
endif;

/**
 * Set default options
 */
if ( !function_exists('forefront_get_default_fonts') ) :
add_filter( 'generate_font_option_defaults','forefront_get_default_fonts' );
function forefront_get_default_fonts( $forefront_font_defaults )
{	
	$forefront_font_defaults[ 'font_body' ] = 'Arial, Helvetica, sans-serif';
	$forefront_font_defaults[ 'body_font_weight' ] = 'normal';
	$forefront_font_defaults[ 'body_font_transform' ] = 'none';
	$forefront_font_defaults[ 'body_font_size' ] = '15';
	$forefront_font_defaults[ 'font_site_title' ] = 'Merriweather';
	$forefront_font_defaults[ 'font_site_title_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_site_title_variants' ] = 'regular,500,700,900';
	$forefront_font_defaults[ 'site_title_font_weight' ] = 'bold';
	$forefront_font_defaults[ 'site_title_font_transform' ] = 'none';
	$forefront_font_defaults[ 'site_title_font_size' ] = '60';
	$forefront_font_defaults[ 'mobile_site_title_font_size' ] = '30';
	$forefront_font_defaults[ 'font_site_tagline' ] = 'Roboto';
	$forefront_font_defaults[ 'font_site_tagline_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_site_tagline_variants' ] = '100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic';
	$forefront_font_defaults[ 'site_tagline_font_weight' ] = 'normal';
	$forefront_font_defaults[ 'site_tagline_font_transform' ] = 'none';
	$forefront_font_defaults[ 'site_tagline_font_size' ] = '15';
	$forefront_font_defaults[ 'font_navigation' ] = 'Roboto';
	$forefront_font_defaults[ 'font_navigation_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_navigation_variants' ] = '100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic';
	$forefront_font_defaults[ 'navigation_font_weight' ] = 'normal';
	$forefront_font_defaults[ 'navigation_font_transform' ] = 'none';
	$forefront_font_defaults[ 'navigation_font_size' ] = '15';
	$forefront_font_defaults[ 'font_widget_title' ] = 'Roboto';
	$forefront_font_defaults[ 'font_widget_title_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_widget_title_variants' ] = '100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic';
	$forefront_font_defaults[ 'widget_title_font_weight' ] = 'normal';
	$forefront_font_defaults[ 'widget_title_font_transform' ] = 'none';
	$forefront_font_defaults[ 'widget_title_font_size' ] = '20';
	$forefront_font_defaults[ 'widget_content_font_size' ] = '15';
	$forefront_font_defaults[ 'font_heading_1' ] = 'Roboto';
	$forefront_font_defaults[ 'font_heading_1_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_heading_1_variants' ] = '100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic';
	$forefront_font_defaults[ 'heading_1_weight' ] = '300';
	$forefront_font_defaults[ 'heading_1_transform' ] = 'none';
	$forefront_font_defaults[ 'heading_1_font_size' ] = '40';
	$forefront_font_defaults[ 'mobile_heading_1_font_size' ] = '30';
	$forefront_font_defaults[ 'font_heading_2' ] = 'Roboto';
	$forefront_font_defaults[ 'font_heading_2_category' ] = 'sans-serif';
	$forefront_font_defaults[ 'font_heading_2_variants' ] = '100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic';
	$forefront_font_defaults[ 'heading_2_weight' ] = '300';
	$forefront_font_defaults[ 'heading_2_transform' ] = 'none';
	$forefront_font_defaults[ 'heading_2_font_size' ] = '30';
	$forefront_font_defaults[ 'mobile_heading_2_font_size' ] = '25';
	$forefront_font_defaults[ 'font_heading_3' ] = 'inherit';
	$forefront_font_defaults[ 'heading_3_weight' ] = 'normal';
	$forefront_font_defaults[ 'heading_3_transform' ] = 'none';
	$forefront_font_defaults[ 'heading_3_font_size' ] = '20';
	$forefront_font_defaults[ 'footer_font_size' ] = '17';
	
	return $forefront_font_defaults;
}
endif;

/**
 * Prints the Post Image to post excerpts
 */
 
if ( ! function_exists( 'forefront_post_image' ) && !function_exists( 'generate_blog_get_defaults' ) ) :
	add_action( 'generate_before_content', 'forefront_post_image' );
	function forefront_post_image()
	{
		if ( !has_post_thumbnail() )
			return;
			
		if ( 'post' == get_post_type() && !is_single() ) {
		?>
			<div class="post-image">
				<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
			</div>
		<?php
		}
	}
endif;