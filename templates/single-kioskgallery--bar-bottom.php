<?php
/**
 * @file
 * Custom post template for kiosk gallery post type
 * This can be overridden in the theme folder in the sub directory {current_theme}/templates/single-kioskgallery.php
 */
 
// Get the gallery post
global $post;

// Get the meta data to be used on the page
$gallery_logo_id = get_post_meta($post->ID, 'gallery_logo', TRUE);
$slide_duration = get_post_meta($post->ID, 'slide_duration', true);
$refreshMin = get_post_meta($post->ID, 'auto_refresh', true);
$show_navigation = get_post_meta($post->ID, 'show_navigation', true);
$show_arrows = get_post_meta($post->ID, 'show_arrows', true);
$signup_form_shortcode = get_post_meta($post->ID, 'signup_form', true);
$primary_color = get_post_meta($post->ID, 'primary_color', true);
$bottom_bar_text_color = get_post_meta($post->ID, 'bottom_bar_text_color', true);
$website = get_post_meta($post->ID, 'business_info_website', TRUE);
$phone = get_post_meta($post->ID, 'business_info_phone', TRUE);
$address = get_post_meta($post->ID, 'business_info_address', TRUE);

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
		
		<link rel="apple-touch-icon" sizes="57x57" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-72x72.png">    
		<link rel="apple-touch-icon" sizes="144x144" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php print get_stylesheet_directory_uri();?>/apple-touch-icon-180x180.png">
		
		<?php // Allow overriding of the main stylesheet from the meta box ?>
		<?php if (!empty($primary_color)): ?>
			<style type="text/css">
				.swiper-slide .text-panel {
					border-top-color: <?php print $primary_color;?> !important;
				}
				
				.swiper-slide .text-panel .title {
					color: <?php print $primary_color;?> !important;
				}
				
				.footer-bar {
					background: <?php print $primary_color;?> !important;
					color: <?php print $bottom_bar_text_color;?> !important;?>
				}
				
				.swiper-button-prev, .swiper-button-next {
					color: <?php print $primary_color;?> !important;
				}
				
				.swiper-pagination-bullet-active {
					background: <?php print $primary_color;?> !important;
				}
				
				.trigger-lead-form {
					background: <?php print $primary_color;?> !important;
					border-color: <?php print $primary_color;?> !important;
				}
				
				.trigger-lead-form:hover {
					background: <?php print $primary_color;?> !important;
				}
				
				.preloader {
					background: <?php print $primary_color;?> !important;
				}
				
				
			</style>
		<?php endif;?>
		
		<?php wp_head();?>
	</head>
	<body>
		<?php if (!empty($slide_duration)): ?>
			<script>
				slide_duration = <?php print $slide_duration;?>
			</script>
		<?php endif;?>
		
		<?php include('preloader.php');?>

		<button id="goFullScreen" class="fullscreen"><i class="fa fa-expand" aria-hidden="true"></i></button>
		<button id="hideFullScreen" class="fullscreen"><i class="fa fa-compress" aria-hidden="true"></i></button>
		
		<div class="watermark bottom-bar">
			<?php if (!empty($signup_form_shortcode)): ?>
				<a href="#" class="trigger-lead-form">Get Notified</a>
			<?php endif;?>
		</div>
		
		<?php $images = mobile_kiosk_page_images($post->ID); ?>
		
		<!-- Slider main container -->
		<div class="swiper-container bottom-bar">
		    <!-- Additional required wrapper -->
		    <div class="swiper-wrapper">
		        <!-- Slides -->
		        
		        <?php foreach ($images as $image): ?>
			        <?php mobile_kiosk_print_slide($image); ?>
		        <?php endforeach;?>
		        ...
		    </div>
		    
		    <!-- If we need pagination -->
		    <?php if ($show_navigation): ?>
			    <div class="swiper-pagination"></div>
		    <?php endif;?>
		    
		    <!-- If we need navigation buttons -->
		    <?php if ($show_arrows): ?>
			    <div class="swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
			    <div class="swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
		    <?php endif;?>
		    
		    <!-- If we need scrollbar -->
		    <div class="swiper-scrollbar"></div>
		    
		    <div class="footer-bar">
			    
			    <?php if (!empty($gallery_logo_id)): ?>
					<div class="logo">
						<img class="gallery-logo" src="<?php print wp_get_attachment_image_src($gallery_logo_id, 'full', TRUE)[0];?>">
					</div>
				<?php endif;?>
				
				<div class="contact-info">
					<ul>
						<?php if (!empty($website)): ?>
							<li><?php print $website;?></li>
						<?php endif;?>
						
						<?php if (!empty($phone)): ?>
							<li><?php print $phone;?></li>
						<?php endif;?>
						
						<?php if (!empty($address)): ?>
							<li><?php print $address;?></li>
						<?php endif;?>
					</ul>
				</div>
			</div>
		</div>
		
		
		<?php if (!empty($signup_form_shortcode)): ?>
			<div class="lead-takeover">
				<div class="content">
					<button class="close">X</button>
					
					<?php print do_shortcode($signup_form_shortcode);?>
				</div>
			</div>
		<?php endif;?>
		
		
		<script>
			slide_duration = <?php print $slide_duration;?>;
			refreshMin = <?php print $refreshMin;?>;
		</script>
		
		
		<?php wp_footer(); ?>
	</body>
</html>