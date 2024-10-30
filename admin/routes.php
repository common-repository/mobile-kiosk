<?php
/**
 * @file
 * Define custom routes for our plugin
 */
 

// Add our endpoint on install
function install_mobile_kiosk() {
	// Add our endpoints
	mobile_kiosk_endpoint();
	
	// Flush the custom routes so these get added	
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'install_mobile_kiosk' );


/**
* Flush rewrite rules
*/
function uninstall_mobile_kiosk() {
	// Flush so it removes the endpoints
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'uninstall_mobile_kiosk' );
 
 
 /**
  *Add our new endpoint for checking for updates
  */
 function mobile_kiosk_endpoint()
 {
	// Show edit template
	add_rewrite_endpoint('fq/admin/mobile-kiosk/slide-edit', EP_ROOT);
	
	// Update a slide
	add_rewrite_endpoint('fq/admin/mobile-kiosk/slide-update', EP_ROOT);
	
	// Show the new slide template
	add_rewrite_endpoint('fq/admin/mobile-kiosk/slide-new', EP_ROOT);
	
	// Show the add slide template
	add_rewrite_endpoint('fq/admin/mobile-kiosk/show-add-slide', EP_ROOT);
	
	// Create a new slide
	add_rewrite_endpoint('fq/admin/mobile-kiosk/slide-create', EP_ROOT);
 }
 add_action( 'init', 'mobile_kiosk_endpoint' );
 
 
 /**
  * The routing for our endpoint
  */
function mobile_kiosk_routes($query) 
{		
	switch ($query->request)
	{
		// Show the new slide template
		case 'fq/admin/mobile-kiosk/slide-new':
			mobile_kiosk_get_slide_edit_template();
			break;
		
		// Show edit template
		case 'fq/admin/mobile-kiosk/slide-edit':
			$id = $_REQUEST['id'];
			$slide = NULL;
			
			// Load the slide post
			if (!empty($id)) {
				$slide = get_post($id);
			}
			mobile_kiosk_get_slide_edit_template(TRUE, $slide);
			break;
		
		// Update a slide
		case 'fq/admin/mobile-kiosk/slide-update':
			mobile_kiosk_update_slide();
			break;
		
		// Show the add slide template
		case 'fq/admin/mobile-kiosk/show-add-slide':
			mobile_kiosk_show_add_slide();
			break;
		
		// Create a new slide
		case 'fq/admin/mobile-kiosk/slide-create':
			mobile_kiosk_create_new_slide();
			break;
	}
}
add_action('parse_request', 'mobile_kiosk_routes');