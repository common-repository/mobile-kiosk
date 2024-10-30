<div class="preloader">

	<div class="container">
		<div class="col-sm-4 col-sm-offset-4">
			<?php if (!empty($gallery_logo_id)): ?>
				<div class="logo">
					<img src="<?php print wp_get_attachment_image_src($gallery_logo_id, 'full', TRUE)[0];?>">
				</div>
			<?php endif;?>	
			<div class="uil-ripple-css" style="transform:scale(0.6);"><div></div><div></div></div>
			<p class="caption">loading...</p>
		</div>
	</div>
	
</div>