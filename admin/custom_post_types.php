<?php
/**
 * @file
 * Handle custom post types necessary for plugin
 */


// Make sure thumbnails are supported
add_theme_support( 'post-thumbnails' );


/**
 * Create the custom post types we'll need
 */
add_action('init', 'mobile_kiosk_create_custom_post_types');
function mobile_kiosk_create_custom_post_types()
{

	// Gallery post type
	$labels = array(
		'name'               => _x( 'Kiosk Gallery', 'post type general name' ),
		'singular_name'      => _x( 'Kiosk Gallery', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'gallery' ),
		'add_new_item'       => __( 'Add New Kiosk Gallery' ),
		'edit_item'          => __( 'Edit Kiosk Gallery' ),
		'new_item'           => __( 'New Kiosk Gallery' ),
		'all_items'          => __( 'All Kiosk Galleries' ),
		'view_item'          => __( 'View Kiosk Galleries' ),
		'search_items'       => __( 'Search Kiosk Galleries' ),
		'not_found'          => __( 'No Kiosk Galleries found' ),
		'not_found_in_trash' => __( 'No Kiosk Galleries found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Kiosk Gallery'
	);
	
	$args = array(
	'labels'        => $labels,
	'description'   => 'Holds our Kiosk Galleries',
	'public'        => true,
	'publicly_queryable' => true,
	'menu_position' => 5,
	'menu_icon' => 'dashicons-tablet',
	'supports'      => array( 'title'),
	'has_archive'   => false,
	'exclude_from_search' => true 
	);
	register_post_type('kioskgallery', $args);
	
	// Slide post type
	$labels = array(
		'name'               => _x( 'Kiosk Slide', 'post type general name' ),
		'singular_name'      => _x( 'Kiosk Slide', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'slide' ),
		'add_new_item'       => __( 'Add New Kiosk Slide' ),
		'edit_item'          => __( 'Edit Kiosk Slide' ),
		'new_item'           => __( 'New Kiosk Slide' ),
		'all_items'          => __( 'All Kiosk Slides' ),
		'view_item'          => __( 'View Kiosk Slides' ),
		'search_items'       => __( 'Search Kiosk Slides' ),
		'not_found'          => __( 'No Kiosk Slides found' ),
		'not_found_in_trash' => __( 'No Kiosk Slides found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Kiosk Slide'
	);
	
	$args = array(
	'labels'        => $labels,
	'description'   => 'Holds our Kiosk Slides',
	'public'        => true,
	'publicly_queryable' => false,
	'menu_position' => 5,
	'menu_icon' => 'dashicons-images-alt',
	'supports'      => array( 'title'),
	'has_archive'   => false,
	'exclude_from_search' => true 
	);
	register_post_type('kioskslide', $args);

	
	flush_rewrite_rules();
}