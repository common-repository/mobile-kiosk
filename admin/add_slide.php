<?php
/**
 * @file
 * Show available slides to attach to a gallery
 */
?>

<h3>Add existing slide</h3>
<p>Add one or more of the slides below to the gallery</p>

<div class="available-slides">
	<ul>
		<?php if (!empty($slides)): ?>
			<?php foreach($slides as $slide): ?>
				<li class="slide-preview clearfix" data-id="<?php print $slide->ID;?>">
					<?php $thumbnail = wp_get_attachment_image_src($slide->ID, 'medium', TRUE)[0];?>
					<?php if (!empty($thumbnail)): ?>
						<img class="thumbnail" src="<?php print $thumbnail;?>">
					<?php endif;?>
					
					<h3><?php print $slide->post_title;?></h3>
					
					<button class="button button-secondary button-large remove-slide" type="button">X</button>
					<a href="#edit" class="edit">Edit</a>
				</li>
			<?php endforeach;?>
		<?php endif;?>
	</ul>
</div>

<p>
	<button id="fq_kiosk_gallery_add_existing_slides" class="button button-secondary button-large" type="button">Add Slide(s)</button>
	<button id="fq_kiosk_gallery_cancel_add_existing_slides" class="button button-secondary button-large" type="button">Close</button>
</p>