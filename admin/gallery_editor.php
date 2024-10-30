<?php
/**
 * @file
 * Customize the editing experience for gallery content type
 * This is where most of the custom functionality for the admin editing happens
 */


// Add our hooks for adding meta boxes
add_action( 'load-post.php', 'mobile_kiosk_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'mobile_kiosk_post_meta_boxes_setup' );



/**
 * Add action for creating gallery meta boxes
 */
function mobile_kiosk_post_meta_boxes_setup() {
	// hook adding the meta boxes 
	add_action( 'add_meta_boxes', 'mobile_kiosk_add_post_meta_boxes' );
	
	// Hook saving the meta boxes
	add_action('save_post', 'mobile_kiosk_save_post_meta_boxes', 10, 2);
}


/**
 * Create the meta boxes for the gallery posts
 */
function mobile_kiosk_add_post_meta_boxes() {
	
	// Gallery Options
	add_meta_box(
		'gallery-options',
		esc_html__( 'Gallery Options', 'gallery-options' ),
		'mobile_kiosk_gallery_options_meta_box', 
		'kioskgallery', 
		'normal',  
		'high' 
	);
	
	// Gallery Slides
	add_meta_box(
		'gallery-slides',
		esc_html__( 'Gallery Slides', 'gallery-slides' ),
		'mobile_kiosk_gallery_slides_meta_box', 
		'kioskgallery', 
		'normal',  
		'low' 
	);
	
}


/**
 * Callback for the gallery options meta box
 */
function mobile_kiosk_gallery_options_meta_box($object, $box) {
	
	// Try to load the gallery logo
	$gallery_logo_id = get_post_meta($object->ID, 'gallery_logo', TRUE);
	$thumbnail = (!empty($gallery_logo_id)) ? wp_get_attachment_image_src($gallery_logo_id, 'thumbnail', TRUE)[0] : NULL;
	
	// Get the available sources
	$sources = mobile_kiosk_get_slide_sources();
	
	// Get the sources for this gallery
	$selectedSources = (get_post_meta($object->ID, 'sources', TRUE)) ? get_post_meta($object->ID, 'sources', TRUE) : array();
	
	// Load our HTML template
	include('metaboxes/gallery_options.php');
}


/**
 * Callback for gallery slides meta box
 */
function mobile_kiosk_gallery_slides_meta_box($object, $box) {
	// Get all slides related to the object
	$args = array(
		'post_type' => 'kioskslide',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'gallery_id',
				'value' => $object->ID,
				'compare' => '='
			)
		),
		'orderby' => 'meta_value_num',
		'meta_key' => 'slide_order',
		'order' => 'ASC'
	);
	$slides = new WP_Query($args);
	
	// Include our template
	include('metaboxes/gallery_slides.php');
}


/**
 * Fetches the HTML template for editing slides and returns it
 *
 * @return HTML
 */
function mobile_kiosk_get_slide_edit_template($edit = FALSE, $slide = NULL) {
	// Fetch the template for editing / creating a kiosk slide and return that
	ob_start();
	include('slide_editor.php');
	$template .= ob_get_contents();
	ob_end_clean();
	
	echo $template;
	exit;
}


/**
 * Create a new slide
 */
function mobile_kiosk_create_new_slide() {
	// Make sure we have the fields we need
	if (!$_REQUEST['slide_title']) {
		echo json_encode(array('success' => FALSE, 'message' => "You must fill out at least a title for the slide"));
		exit;
	}
	
	// Create the new post 
	$newSlide = array(
		'post_title' => $_REQUEST['slide_title'],
		'post_content' => (isset($_REQUEST['slide_content'])) ? $_REQUEST['slide_content'] : '',
		'post_type' => 'kioskslide',
		'post_status' => 'publish'
	);
	$newSlide_id = wp_insert_post($newSlide);
	
	// Load up the new post object
	$slide = new stdClass();
	$slide->post = get_post($newSlide_id);
	
	// Do we need to add a featured image?
	if (!empty($_REQUEST['featured_image_id'])) {
		set_post_thumbnail($slide->post, $_REQUEST['featured_image_id']);
		
		// Add this to what we're passing back
		$slide->thumbnail = wp_get_attachment_image_src($_REQUEST['featured_image_id'], 'thumbnail', TRUE)[0];
	}
	
	// Attach the slide to this gallery via meta
	
	// Render this out using our template
	ob_start();
	include('slide_preview.php');
	$template .= ob_get_contents();
	ob_end_clean();
	
	// Return that it was created
	echo json_encode(array(
		'success' => TRUE,
		'message' => "Slide successfully created",
		'template' => $template
	));
	exit;
}



/**
 * Show the template for adding an existing slide to a gallery
 */
function mobile_kiosk_show_add_slide() {
	// Get all available slides
	$args = array(
		'post_type' => 'kioskslide',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'gallery_id',
				'value' => FALSE,
				'type' => 'BOOLEAN'
			),
			array(
				'key' => 'gallery_id',
				'compare' => 'NOT EXISTS'
			),
		)	
	);
	$query = new WP_Query($args);
	$slides = $query->posts;
	
	// Get our template
	ob_start();
	include('add_slide.php');
	$template .= ob_get_contents();
	ob_end_clean();
	
	echo $template;
	exit;
}


/**
 * Update an existing slide
 */
function mobile_kiosk_update_slide() {
	// Get the id of the slide
	$id = $_REQUEST['id'];
	
	// Make sure we have the ID
	if (empty($id)) {
		return "Missing slide ID";
	}
	
	// Load up the post and update it
	$slide = get_post($id);
	$slide->post_title = (!empty($_REQUEST['slide_title'])) ? $_REQUEST['slide_title'] : $slide->post_title;
	$slide->post_content = (!empty($_REQUEST['slide_content'])) ? $_REQUEST['slide_content'] : $slide->post_content;
	wp_update_post($slide);
	
	// Was there an image passed in?
	if (!empty($_REQUEST['featured_image_id'])) {
		set_post_thumbnail($slide, $_REQUEST['featured_image_id']);
		$slide->thumbnail = wp_get_attachment_image_src($_REQUEST['featured_image_id'], 'medium', TRUE)[0];
	}
	
	// Return this back
	echo json_encode($slide);
	exit();
	
}


/**
 * Saving the meta boxes
 */
function mobile_kiosk_save_post_meta_boxes($post_id, $post) {
    
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );
	
	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post->ID ) )
		return $post->ID;
		
	// Get the current slides attached to this
	// Get all slides related to the object
	$args = array(
		'post_type' => 'kioskslide',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'gallery_id',
				'value' => $post->ID,
				'compare' => '='
			)
		)	
	);
	$query = new WP_Query($args);
	$existingSlides = $query->posts;
	
	// Remove slide connections that are no longer part of this
	foreach ($existingSlides as $existingSlide) {
		$remove = TRUE;
		
		// If there were slides pass through, see if any of these existing ones are sticking around
		if (!empty($_POST['slides'])) {
			foreach ($_POST['slides'] as $newSlide) {
				if ($newSlide == $existingSlide->ID) {
					$remove = FALSE;
				}
			}
		}
		
		// If it's going to be removed, remove the meta value
		if ($remove) {
			$meta_key = 'gallery_id';
			$meta_value = get_post_meta($existingSlide->ID, $meta_key, true);
			delete_post_meta($existingSlide->ID, $meta_key, $meta_value);
		}
	}
	
	
	// Get the sources
	if (!empty($_POST['sources'])) {
		$new_meta_value = $_POST['sources'];
		$meta_key = 'sources';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'sources';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
		
	// Get the slides
	if (!empty($_POST['slides'])) {
		$slides = $_POST['slides'];
		
		// Loop through the slides
		foreach ($slides as $key => $slide_id) {
			$slide_gallery_meta_key = 'gallery_id';
			$slide_order_meta_key = 'slide_order';
			
			// Load up the slide
			$slide = get_post($slide_id);
			
			// If it doesn't have this meta key, add it, otherwise, update what's there
			$gallery_id_meta = get_post_meta($slide_id, $slide_gallery_meta_key, true);
			if ($gallery_id_meta) {
				update_post_meta($slide_id, $slide_gallery_meta_key, $post->ID);
				// Update the value
			}
			else {
				// Add the meta
				add_post_meta($slide_id, $slide_gallery_meta_key, $post->ID, true);
			}
			
			
			// If it doesn't have this meta key, add it, otherwise, update what's there
			$slide_order_meta = get_post_meta($slide_id, $slide_order_meta_key, true);
			if ($gallery_id_meta) {
				update_post_meta($slide_id, $slide_order_meta_key, $key + 1);
				// Update the value
			}
			else {
				// Add the meta
				add_post_meta($slide_id, $slide_order_meta_key, $key + 1, true);
			}
		}
	}
	
	// Save / update the gallery options slide duration
	if (!empty($_POST['slide_duration'])) {
		$new_meta_value = $_POST['slide_duration'];
		$meta_key = 'slide_duration';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'slide_duration';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	if (!empty($_POST['auto_refresh'])) {
		$new_meta_value = $_POST['auto_refresh'];
		$meta_key = 'auto_refresh';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'auto_refresh';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	// Save / update the gallery options slide duration
	if (!empty($_POST['gallery_logo'])) {
		$new_meta_value = $_POST['gallery_logo'];
		$meta_key = 'gallery_logo';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'gallery_logo';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	// Save / update the gallery options show arrows
	if (!empty($_POST['show_arrows'])) {
		$new_meta_value = $_POST['show_arrows'];
		$meta_key = 'show_arrows';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		// Remove the key
		$meta_key = 'show_arrows';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options show navigation
	if (!empty($_POST['show_navigation'])) {
		$new_meta_value = $_POST['show_navigation'];
		$meta_key = 'show_navigation';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'show_navigation';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options slide order
	if (!empty($_POST['slide_order'])) {
		$new_meta_value = $_POST['slide_order'];
		$meta_key = 'slide_order';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'slide_order';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options slide layout
	if (!empty($_POST['slide_layout'])) {
		$new_meta_value = $_POST['slide_layout'];
		$meta_key = 'slide_layout';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'slide_layout';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	// Save / update the gallery options signup form
	if (!empty($_POST['signup_form'])) {
		$new_meta_value = $_POST['signup_form'];
		$meta_key = 'signup_form';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'signup_form';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	// Save / update the gallery options primary color
	if (!empty($_POST['primary_color'])) {
		$new_meta_value = $_POST['primary_color'];
		$meta_key = 'primary_color';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'primary_color';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options bottom bar text color
	if (!empty($_POST['bottom_bar_text_color'])) {
		$new_meta_value = $_POST['bottom_bar_text_color'];
		$meta_key = 'bottom_bar_text_color';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value, TRUE);
		}
	}
	else {
		$meta_key = 'bottom_bar_text_color';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options slide duration
	if (!empty($_POST['business_info_website'])) {
		$new_meta_value = $_POST['business_info_website'];
		$meta_key = 'business_info_website';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'business_info_website';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options slide duration
	if (!empty($_POST['business_info_phone'])) {
		$new_meta_value = $_POST['business_info_phone'];
		$meta_key = 'business_info_phone';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'business_info_phone';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	// Save / update the gallery options slide duration
	if (!empty($_POST['business_info_address'])) {
		$new_meta_value = $_POST['business_info_address'];
		$meta_key = 'business_info_address';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		
		if ($meta_value) {
			// Update the value
			update_post_meta($post->ID, $meta_key, $new_meta_value);
		}
		else {
			// Add the value
			add_post_meta($post->ID, $meta_key, $new_meta_value);
		}
	}
	else {
		$meta_key = 'business_info_address';
		$meta_value = get_post_meta($post->ID, $meta_key, TRUE);
		delete_post_meta($post->ID, $meta_key, $meta_value);
	}
	
	
	
	
    
}


