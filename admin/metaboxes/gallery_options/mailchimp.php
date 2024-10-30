<div id="gallery_options_mailchimp" class="gallery-options">
	<!-- mailchimp -->
	<p><label for="signup_form">Mailchimp Signup Form</label></p>
	<p>
		<textarea id="signup_form" name="signup_form"><?php print get_post_meta( $object->ID, 'signup_form', true);?></textarea>
	</p>
	<p><em>Add mailchimp form shortcode above to allow users to signup via a mailchimp form overlay on the kiosk (requires <a href="https://wordpress.org/plugins/mailchimp-for-wp/" target="_blank">Mailchimp for Wordpress plugin</a>)</em></p>
	<!-- end mailchimp -->
	
</div>