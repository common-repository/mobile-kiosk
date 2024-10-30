<?php
/**
 * @file
 * Add custom hooks so other plugins can add their own slides
 */
 
 
 /**
 * Fetch the images we need for our gallery
 * @param int $id (the id of the gallery post)
 *
 * @return array
 */
function mobile_kiosk_page_images($id)
{
	// Holds our images
	$images = array();
	
	// Fetch the images from the local custom post type
	$images = array_merge($images, mobile_kiosk_page_local_images($id));
	
	// Add in images applied through other plugins
	$images = apply_filters('kiosk_slide_gallery_images', array('images' => $images, 'id' => $id));
	
	// Handle weird index issue if there isn't anything else hooking in
	if (isset($images['images']))
	{
		$images = $images['images'];
	}
	
	// Do we need to shuffle them?
	if (get_post_meta($id, 'slide_order', true) == 'random') {
		shuffle($images);
	}

	// Return them back
	return $images;
}




/**
 * Get the available sources for the plugin to pull slides from
 * We're going to just include the slides from this plugin as default and not have it be selectable
 *
 * @return array
 */
function mobile_kiosk_get_slide_sources()
{
	$sources = array();
	
	// Allow other plugins to add their own sources
	$sources = apply_filters('mobile_kiosk_slide_sources', $sources);
	
	return $sources;
}