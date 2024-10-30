<?php
/**
 * @file
 * Template for gallery slides meta box
 */
?>

<?php wp_nonce_field( basename( __FILE__ ), 'gallery-options_nonce' ); ?>

<p>
	<button id="fq_kiosk_gallery_add_slide" class="button button-primary button-large" type="button">Create New Slide</button>
	<button id="fq_kiosk_gallery_add_existin_slide_trigger" class="button button-secondary button-large" type="button">Add Existing Slide</button>
</p>

<div id="slide_editor_fetch_progress" class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
</div>


<div id="edit_slide_wrapper"></div>
<div id="add_slide_wrapper"></div>
<div id="slide_editor_wrapper"></div>
<div id="slide_messages"></div>

<h3>Slides in this gallery</h3>
<ul id="sortable" class="slides">
	<?php if ($slides->have_posts()): ?>
		<?php while($slides->have_posts()): $slides->the_post();?>
			<li class="slide-preview clearfix" data-id="<?php print get_the_ID();?>">
				<?php $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail', TRUE)[0];?>
				<?php if (!empty($thumbnail)): ?>
					<img class="thumbnail" src="<?php print $thumbnail;?>">
				<?php endif;?>
				
				<h3><?php the_title();?></h3>
				<input type="hidden" name="slides[]" value="<?php print get_the_ID();?>">
				<button class="button button-secondary button-large remove-slide" type="button">X</button>
				<a href="#edit" class="edit">Edit</a>
			</li>
		<?php endwhile;?>
	<?php else: ?>
		<li class="no-slides">Looks like this gallery is empty. Why don't you try adding or creating some slides?</li>
	<?php endif;?>
</ul>