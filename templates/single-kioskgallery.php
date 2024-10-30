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
$show_navigation = get_post_meta($post->ID, 'show_navigation', true);
$show_arrows = get_post_meta($post->ID, 'show_arrows', true);
$signup_form_shortcode = get_post_meta($post->ID, 'signup_form', true);
$primary_color = get_post_meta($post->ID, 'primary_color', true);

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="apple-mobile-web-app-capable" content="yes">
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
				
				
			</style>
		<?php endif;?>
		
		<?php wp_head();?>
	</head>
	<body>

		<button id="goFullScreen" class="fullscreen"><i class="fa fa-expand" aria-hidden="true"></i></button>
		<button id="hideFullScreen" class="fullscreen"><i class="fa fa-compress" aria-hidden="true"></i></button>
		
		<div class="watermark">
			<?php if (!empty($signup_form_shortcode)): ?>
				<a href="#" class="trigger-lead-form">Get Notified</a>
			<?php endif;?>
			
			<?php if ($gallery_logo_id): ?>
				<img class="gallery-logo" src="<?php print wp_get_attachment_image_src($gallery_logo_id, 'full', TRUE)[0];?>">
			<?php endif;?>
		</div>
		
		<?php $images = mobile_kiosk_page_images($post->ID); ?>
		
		<!-- Slider main container -->
		<div class="swiper-container">
		    <!-- Additional required wrapper -->
		    <div class="swiper-wrapper">
		        <!-- Slides -->
		        
		        <?php foreach ($images as $image): ?>
			        <div class="swiper-slide" style="background-image: url('<?php print $image->img;?>');">
				        <?php if (!empty($image->description) || $image->idx): ?>
					        <div class="text-panel">
						        <h2 class="title"><?php print $image->title;?></h2>
						        <div class="description"><?php print apply_filters('the_content', $image->description);?></div>
					        </div>
				        <?php endif;?>
			        </div>
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
			// Init the swiper
			var swiper = new Swiper('.swiper-container', {
		        pagination: '.swiper-pagination',
		        paginationClickable: true,
		        autoplay: <?php print $slide_duration * 1000;?>,
		        loop: true,
		        nextButton: '.swiper-button-next',
		        prevButton: '.swiper-button-prev',
		    });
		</script>
		
		
		<?php wp_footer(); ?>
	</body>
</html>