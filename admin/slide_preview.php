<li class="slide-preview clearfix" data-id="<?php print $slide->post->ID;?>">
	<?php if (!empty($slide->thumbnail)): ?>
		<img class="thumbnail" src="<?php print $slide->thumbnail;?>">
	<?php endif;?>
	
	<h3><?php print $slide->post->post_title;?></h3>
	
	<input type="hidden" name="slides[]" value="<?php print $slide->post->ID;?>">
	
	<button class="button button-secondary button-large remove-slide" type="button">X</button>
	<a href="#edit" class="edit">Edit</a>
</li>