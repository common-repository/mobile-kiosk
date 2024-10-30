<?php
/**
 * @file 
 * Set page templates needed in plugin
 */


// Define the template for the kiosk gallery post type
add_filter('single_template', 'mobile_kiosk_gallery_template');
function mobile_kiosk_gallery_template($template)
{
	global $wp_query, $post;

	// Only affect the kioskgallery post type	
	if ($post->post_type == 'kioskgallery')
	{
		// Get the plugin directory
		$plugin_path = WP_PLUGIN_DIR . '/mobile-kiosk/';	
		
		// Get the right template based on the layout for the gallery
		$layout = (get_post_meta($post->ID, 'slide_layout', TRUE)) ? get_post_meta($post->ID, 'slide_layout', TRUE) : 'panel-left';
		
		// The name of custom post type single template
        $template_name = "single-kioskgallery--{$layout}.php";
        
        // A specific single template for my custom post type exists in theme folder? Or it also doesn't exist in my plugin?
        if($template === get_stylesheet_directory() . '/' . $template_name || !file_exists($plugin_path . '/templates/' . $template_name)) {
            // Then return "single.php" or "single-my-custom-post-type.php" from theme directory.
            return $template;
        }
        
        // If not, return my plugin custom post type template.
        return $plugin_path . '/templates/' . $template_name;
	}
	
	return $template;
}