<?php
/**
 * @file
 * Template file for the gallery options meta box
 */
?>


<p><label for="sources">Sources</label></p>
<p>
	<?php foreach ($sources as $source): ?>
		<input type="checkbox" name="sources[]" class="mobile-kiosk-sources" id="sources_<?php print $source['key'];?>" value="<?php print $source['key'];?>" <?php if (in_array($source['key'], $selectedSources)): ?>checked<?php endif;?>>
		<label for="sources_<?php print $source['key'];?>"><?php print $source['label'];?></label>
	<?php endforeach;?>
</p>
