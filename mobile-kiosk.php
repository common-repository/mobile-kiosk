<?php 
/*
  Plugin Name: Mobile Kiosk
  Author: Figoli Quinn & Associates
  Description: Use Wordpress to create mobile kiosk applications. Add the page to your tablet "home" screen to have it look and feel just like an app, or use the "full screen" button on a desktop computer — great for use on TV screens.
  Version: 1.3.0
*/

// Load admin scripts and stylesheet
function mobile_kiosk_enqueue_admin(){
	wp_enqueue_media();
	$mobile_kiosk = array( 'template_url' => get_option('siteurl') );
	wp_enqueue_style( 'mobile-kiosk-style', plugin_dir_url(__FILE__ ) . '/assets/css/mobile_kiosk_admin.css', false, '1.0.0' );
    wp_enqueue_script( 'mobile-kiosk-tinymce', '//cdn.tinymce.com/4/tinymce.min.js', array('jquery') );
    wp_enqueue_script( 'mobile-kiosk-admin', plugins_url( '/assets/js/mobile-kiosk-admin.js', __FILE__ ), array('jquery', 'wp-color-picker') );
    wp_localize_script('mobile-kiosk-admin', 'mobile_kiosk', $mobile_kiosk);
    wp_enqueue_style( 'wp-color-picker' );
}
add_action('admin_enqueue_scripts', 'mobile_kiosk_enqueue_admin');

// Load scripts and stylesheet for the front end template
function mobile_kiosk_enqueue_page(){
    wp_enqueue_style( 'mobile-kiosk-page-style', plugin_dir_url(__FILE__ ) . '/assets/css/mobile_kiosk_page.css', false, '1.0.0', 'all');
    wp_enqueue_script( 'swiper', plugins_url( '/assets/js/vendor/swiper.jquery.min.js', __FILE__ ), array('jquery') );
    wp_enqueue_script( 'modernizr', plugins_url( '/assets/js/vendor/modernizr-custom.min.js', __FILE__ ), array('jquery') );
    wp_enqueue_script( 'mobile-kiosk-page', plugins_url( '/assets/js/mobile-kiosk-page.js', __FILE__ ), array('jquery') );
}
add_action('wp_enqueue_scripts', "mobile_kiosk_enqueue_page");


/**
 * Break out the functionality for organization
 */
require('admin/hooks.php');
require('admin/functions.php');
require('admin/routes.php');
require('admin/custom_post_types.php');
require('admin/gallery_editor.php');
require('admin/post_templates.php');









