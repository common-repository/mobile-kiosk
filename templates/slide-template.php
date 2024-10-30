<div class="swiper-slide" style="background-image: url('<?php print $image->img;?>');">
    <?php if (!empty($image->description)): ?>
        <div class="text-panel">
	        <h2 class="title"><?php print $image->title;?></h2>
	        <div class="description"><?php print apply_filters('the_content', $image->description);?></div>
        </div>
    <?php endif;?>
</div>