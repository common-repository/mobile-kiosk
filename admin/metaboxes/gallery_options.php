<?php
/**
 * @file
 * Template file for the gallery options meta box
 */
?>


<div class="row">
	<div class="col-sm-4">
		<ul class="list-group gallery-options-toggles">
			<button type="button" data-id="appearance" class="list-group-item active">Appearance</button>
			<button type="button" data-id="navigation" class="list-group-item">Navigation</button>
			<button type="button" data-id="mailchimp" class="list-group-item">Mailchimp</button>
			<button type="button" data-id="sources" class="list-group-item">Add-on Source(s)</button>
			<button type="button" data-id="business_info" class="list-group-item">Business Info</button>
		</ul>
	</div>
	
	<div class="col-sm-8">
		<?php include('gallery_options/appearance.php');?>
		<?php include('gallery_options/navigation.php');?>
		<?php include('gallery_options/mailchimp.php');?>
		<?php include('gallery_options/sources.php');?>
		<?php include('gallery_options/business-info.php');?>
	</div>
</div>



















