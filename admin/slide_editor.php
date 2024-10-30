<?php
/**
 * @file
 * Template for our custom slide editor
 */
?>



<div class="slide-editor">
	
	<?php if ($edit): ?>
		<h3>Edit Slide</h3>
		<p>Use this to edit this slide</p>
	<?php else: ?>
		<h3>Create a new Slide</h3>
		<p>Use this to add a new slide to this gallery.</p>
	<?php endif;?>
	
		
	<div class="row">
		
		<div class="col-sm-9">
			<p><label for="slide_title">Title</label></p>
			<p><input type="text" class="widefat" name="slide_title" id="slide_title" <?php if (!empty($slide)):?>value="<?php print $slide->post_title;?>"<?php endif;?>></p>
			<textarea id="slide_content" name="slide_content"><?php if (!empty($slide)):?><?php print apply_filters('the_content', $slide->post_content);?><?php endif;?></textarea>
			
			<?php if ($edit): ?>
				<input type="hidden" id="edit_slide_id" name="edit_slide_id" value="<?php print $slide->ID;?>">
			<?php endif;?>
		</div>
		
		<div class="col-sm-3">
			<div class="slide-featured-image">
				
				<?php if ($edit): ?>
					<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $slide->ID ), 'medium' ); ?>
					<?php if (!empty($image)): ?>
						<img id="featured_image_preview" class="thumbnail" src="<?php print $image[0];?>">
					<?php endif;?>
					
					<a id="slide_add_featured_image" href="#" <?php if (!empty($image)):?>style="display: none;"<?php endif;?>>Set featured Image</a>
					<a id="slide_remove_featured_image" href="#" <?php if (!empty($image)):?>style="display: inline-block;"<?php endif;?>>Remove featured Image</a>
				<?php else: ?>
					<a id="slide_add_featured_image" href="#">Set featured Image</a>
					<a id="slide_remove_featured_image" href="#">Remove featured Image</a>
				<?php endif;?>
			</div>
		</div>
	
	</div>
	
	<div class="footer">
		<?php if ($edit): ?>
			<button id="fq_gallery_kiosk_update_slide" class="button button-primary button-large" type="button">Update Slide</button>
		<?php else: ?>
			<button id="fq_gallery_kiosk_save_new_slide" class="button button-primary button-large" type="button">Save Slide</button>
		<?php endif;?>
		<button id="fq_gallery_kiosk_cancel_slide" class="button button-secondary button-large" type="button">Cancel</button>
	</div>
			
</div>