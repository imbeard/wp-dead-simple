<?php
/**
 * @package wp-dead-simple
 */

if ( ! function_exists( 'init_setup' ) ) :

	function init_setup() {

		load_theme_textdomain( 'wp-dead-simple', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		//add_theme_support( 'post-thumbnails' );
		//add_theme_support( 'automatic-feed-links' );

		register_nav_menus( array(
			'primary'  => __( 'Primary', 'wp-dead-simple' ),
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		add_image_size( 'big', 1920, 1080 );

	}
	
endif;
add_action( 'after_setup_theme', 'init_setup' );

function init_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'init_wp_title', 10, 2 );

function init_scripts() {
	
	// css
	wp_enqueue_style( 'wp-dead-simple-vendor', get_template_directory_uri() . '/public/css/vendor.css' );
	wp_enqueue_style( 'wp-dead-simple-app', get_template_directory_uri() . '/public/css/app.css' );

	// google maps
	//wp_enqueue_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?key=KEY');
	
	// js
	wp_enqueue_script( 'wp-dead-simple-vendor-js', get_template_directory_uri() . '/public/js/vendor.js' );
	wp_enqueue_script( 'wp-dead-simple-app-js', get_template_directory_uri() . '/public/js/app.js' );

	//wp_localize_script( 'wp-dead-simple-app-js', 'NAME', array( 'KEY' => 'VALUE' ) );
	
	// load theme style
	wp_enqueue_style( 'wp-dead-simple-style', get_stylesheet_uri() );

}
add_action( 'wp_enqueue_scripts', 'init_scripts' );

/**
 * Add Bootstrap's IE conditional html5 shiv and respond.js to header
 */
function conditional_js() {

	global $wp_scripts;

	wp_register_script( 'html5_shiv', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js', '', '', false );
	wp_register_script( 'respond_js', 'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js', '', '', false );

	$wp_scripts->add_data( 'html5_shiv', 'conditional', 'lt IE 9' );
	$wp_scripts->add_data( 'respond_js', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'conditional_js' );

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

function my_acf_init() {
	
	acf_update_setting('google_api_key', 'KEY');
}
if( function_exists('acf_update_setting') ) {
	add_action('acf/init', 'my_acf_init');
}

function get_image($name, $size = 'large', $id = null) {

	if( function_exists('get_field') ) {
		$id = $id ? $id : get_the_id();
		return ($image = get_field($name, $id)) ? $image['sizes'][$size] : null;
	}
	else {
		return false;
	}

}

function get_sub_image($name, $size = 'large') {

	if( function_exists('get_sub_field') ) {
		return ($image = get_sub_field($name)) ? $image['sizes'][$size] : null;
	}
	else {
		return false;
	}

}
