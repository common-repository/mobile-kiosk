<div id="gallery_options_navigation" class="gallery-options">
	
	<!-- duration -->
	<p><label for="slide_duration">Slide Duration (in seconds)</label></p>
	<p><input class="widefat" type="text" name="slide_duration" id="slide_duration" value="<?php print esc_attr( get_post_meta( $object->ID, 'slide_duration', TRUE) );?>"></p>
	<!-- end duration -->
	
	<!-- arrows -->
	<p><label for="show_arrows">Show Prev / Next Arrows?</label></p>
	<p><input class="widefat" type="checkbox" name="show_arrows" id="show_arrows" <?php if (get_post_meta( $object->ID, 'show_arrows', TRUE)):?>checked<?php endif;?>></p>
	<!-- end arrows -->
	
	<!-- nav -->
	<p><label for="show_navigation">Show Bottom Navigation?</label></p>
	<p><input class="widefat" type="checkbox" name="show_navigation" id="show_navigation" <?php if (get_post_meta( $object->ID, 'show_navigation', TRUE)):?>checked<?php endif;?>></p>
	<!-- end nav -->
	
	<!-- order -->
	<p><label for="slide_order">Slide Order</label></p>
	<p>
		<select class="widefat" name="slide_order" id="slide_order">
			<option value="random" <?php if (get_post_meta($object->ID, 'slide_order', TRUE) == 'random' || !get_post_meta($object->ID, 'slide_order', TRUE)):?>selected<?php endif;?>>Random</option>
			<option value="order" <?php if (get_post_meta($object->ID, 'slide_order', TRUE) == 'order'):?>selected<?php endif;?>>List Order</option>
		</select>
	</p>
	<!-- end order -->
	
	<!-- auto refresh -->
	<p><label for="slide_order">Auto Refresh</label></p>
	<p><input class="widefat" type="text" name="auto_refresh" id="auto_refresh" value="<?php print esc_attr( get_post_meta( $object->ID, 'auto_refresh', TRUE) );?>"></p>
	<p>Number of minutes until automatically refreshes. Leave blank to prevent auto refresh</p>
	<!-- end order -->
	
	
</div>