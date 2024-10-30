<?php
/**
 * @file
 * Helper functions used by the plugin
 */
 



/**
 * Fetches the images (and title text, etc) from the local custom post type
 */
function mobile_kiosk_page_local_images($id)
{
	// Build our query
	$args = array(
		'post_type' => 'kioskslide',
		'posts_per_page' => -1,	
		'meta_query' => array(
			array(
				'key' => 'gallery_id',
				'value' => $id,
				'compare' => '='
			)
		)
	);
	
	// Get the slide order
	$slideOrder = get_post_meta($id, 'slide_order', true);
	
	if ($slideOrder == 'random') {
		$args['orderby'] = 'rand';
	}
	elseif ($slideOrder == 'order') {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = 'slide_order';
		$args['order'] = 'ASC';
	}
	
	// Perform the query, get the array of posts
	$query = new WP_Query($args);
	$posts = $query->posts;
	
	// Start building this image array
	$images = array();
	
	// Loop through these and extract the data we need
	foreach ($posts as $post)
	{
		$item = new stdClass();
		$item->title = $post->post_title;
		$item->description = $post->post_content;
		
		// Get the featured image
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full');
		$item->img = (!empty($image)) ? $image[0] : NULL;
		
		// Add this onto the array
		$images[] = $item;
	}
	
	// Return the array
	return $images;
}




/**
 * Render the slide using a template
 */
function mobile_kiosk_print_slide($image)
{
	$templatePath = 'templates/slide-template.php';
	
	// If there's no "type", just use the default template
	if (empty($image->type))
	{
		include(plugin_dir_path( __FILE__ ) . "../{$templatePath}");
	}
	else
	{
		include(plugin_dir_path( __FILE__ ) . "../../{$image->type}/{$templatePath}");
	}
}

