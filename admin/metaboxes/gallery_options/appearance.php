<div id="gallery_options_appearance" class="gallery-options active">
	<!-- logo -->
	<p><label for="show_logo">Logo</label></p>
	<img src="<?php print $thumbnail;?>" class="thumbnail gallery-logo-preview" <?php if (!empty($thumbnail)): ?>style="display:inline-block;"<?php endif;?>>
	<p><a href="#" id="remove_gallery_logo" <?php if (!empty($thumbnail)): ?>style="display:inline-block;"<?php endif;?>>Remove Logo</a></p>
	<p>
		<input type="button" id="upload_logo_trigger" class="meta-image-button button" value="Choose or Upload an Image" />
		<input type="hidden" name="gallery_logo" id="gallery_logo" value="<?php print get_post_meta($object->ID, 'gallery_logo', TRUE);?>">
	</p>
	<!-- end logo -->
	
	<!-- primary color -->
	<p><label for="primary_color">Primary Color</label></p>
	<p><input type="text" class="primary-color" name="primary_color" id="primary_color" value="<?php if (get_post_meta($object->ID, 'primary_color', TRUE)):?><?php print get_post_meta($object->ID, 'primary_color', TRUE);?><?php else: ?>#515151<?php endif;?>"></p>
	<!-- end primary color -->
	
	
	<!-- layout -->
	<p><label for="slide_layout">Layout</label></p>
	<p>
		<button class="mobile-kiosk-layout-button <?php if (get_post_meta($object->ID, 'slide_layout', TRUE) == 'panel-left' || !get_post_meta($object->ID, 'slide_layout', TRUE)):?>active<?php endif;?>" data-layout="panel-left" type="button"><img src="<?php print plugins_url('../../../assets/img/left-panel.jpg', __FILE__ );?>"></button>
		<button class="mobile-kiosk-layout-button <?php if (get_post_meta($object->ID, 'slide_layout', TRUE) == 'bar-bottom'):?>active<?php endif;?>" data-layout="bar-bottom" type="button"><img src="<?php print plugins_url('../../../assets/img/bottom-bar.jpg', __FILE__ );?>"></button>
		<input type="hidden" name="slide_layout" id="slide_layout" value="<?php print get_post_meta($object->ID, 'slide_layout', TRUE);?>">
	</p>	
	<!-- end layout -->
	
	
	<!-- primary color -->
	<div id="bottom_bar_text_color_wrapper" <?php if (get_post_meta($object->ID, 'slide_layout', TRUE) == 'bar-bottom'):?>style="display:block"<?php else: ?>style="display: none;"<?php endif;?>>
		<p><label for="bottom_bar_text_color">Bottom Bar Text Color</label></p>
		<p><input type="text" class="primary-color" name="bottom_bar_text_color" id="bottom_bar_text_color" value="<?php if (get_post_meta($object->ID, 'bottom_bar_text_color', TRUE)):?><?php print get_post_meta($object->ID, 'bottom_bar_text_color', TRUE);?><?php else: ?>#313131<?php endif;?>"></p>
		<!-- end primary color -->
	</div>
	
</div>