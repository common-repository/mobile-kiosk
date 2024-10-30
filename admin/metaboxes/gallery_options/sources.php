<div id="gallery_options_sources" class="gallery-options">
	<!-- sources -->
	<p>When enabled, these sources may or may not be displayed as a slide below, but will be visible when viewing the gallery.</p>
	<p>
		<?php foreach ($sources as $source): ?>
			<input type="checkbox" name="sources[]" class="mobile-kiosk-sources" id="sources_<?php print $source['key'];?>" value="<?php print $source['key'];?>" <?php if (in_array($source['key'], $selectedSources)): ?>checked<?php endif;?>>
			<label for="sources_<?php print $source['key'];?>"><?php print $source['label'];?></label>
		<?php endforeach;?>
	</p>
	<!-- end sources -->
	
</div>