(function($) {
	
	$(document).ready(function() {
		
		// Initialize our custom slide editor
		slideEditorInit();
		
		// Initialize our source items
		sourcesInit();
		
		// Initialize gallery options
		galleryOptionsInit();
	});
	
	/**
	 * Initializes our custom slide eidtor
	 */
	function slideEditorInit() {
		// Unbind anything that was previously bound
		$('body').off('click', '#fq_kiosk_gallery_add_slide, #fq_gallery_kiosk_save_new_slide, #fq_gallery_kiosk_cancel_slide', function() {
			
		});
		
		// Adding a new slide
		$('body').on('click', '#fq_kiosk_gallery_add_slide', function(e) {
			e.preventDefault();
			
			// Does the editor area already exist? If it does, don't add another
			if ($('.slide-editor').length == 0) {
				addSlideEditor();
			}
		});
		
		// Cancelling the slide editor
		$('body').on('click', '#fq_gallery_kiosk_cancel_slide', function(e) {
			removeSlideEditor();
		});
		
		// Add new slide
		$('body').on('click', '#fq_gallery_kiosk_save_new_slide', function(e) {
			// Save the tinymce content
			tinyMCE.get("slide_content").save();
			
			// Stop the form submission
			e.preventDefault();
			
			// Submit via an ajax promise
			var promise = $.ajax({
				url: mobile_kiosk.template_url + '/fq/admin/mobile-kiosk/slide-create',
				method: 'POST',
				data:{
					slide_title: $('[name="slide_title"]').val(),
					slide_content: $('[name="slide_content"]').val(),
					featured_image_id: $('[name="featured_image_id"]').val()
				}
			});		
			
			promise.done(function(data) {
				data = $.parseJSON(data);
				
				// Was this successful?
				if (!data.success) {
					// Add an alert with the error
					$('#slides_messages').html('<p class="alert alert-danger">' + data.message + '</p>');
				} else {
					// Add an alert that this was successful
					$('#slides_messages').html('<p class="alert alert-success">' + data.message + '</p>');
					
					// Add this to our list of slides
					$('.slides .no-slides').hide();
					$('.slides').append(data.template);
					
					// Remove the slide editor
					removeSlideEditor();
					fadeOutMessages();
				}
			});
			
		});
		
		
		// Enable adding existing slide
		$('body').on('click', '#fq_kiosk_gallery_add_existin_slide_trigger', function(e) {
			// Make sure the add slide functinoality isn't already visible
			if ($('#add_slide_wrapper .available-slides').length == 0) {
				removeSlideEditor();
				showAddSlide();
			}
		});
		
		// Allow removing of slides
		$('body').on('click', '.slides li .remove-slide', function(e) {
			var slide = $(this);
			
			// Fade it out and then remove it
			$(slide).closest('li').fadeOut('250');
			setTimeout(function() {
				$(slide).closest('li').remove();
			}, 500);
		});
		
		
		// Bind link for editing slide
		$('body').on('click', '.slide-preview .edit', function(e) {
			e.preventDefault();
			
			// Color the row
			$('li.editing').removeClass('editing');
			$(this).closest('li').addClass('editing');
			
			showSlideEdit($(this).closest('li').data('id'));
		});
		
		
		if ($('#sortable').length > 0) {
			$("#sortable").sortable({ 
			    cursor: "move", 
			    containment: "#gallery-slides",
			});
		}
		
		if ($('.primary-color').length > 0) {
			$('.primary-color').wpColorPicker();
		}
		
		
		galleryLogo();
		
	}
	
	
	/**
	 * Dynamically adds the slide editor template to the page
	 */
	function addSlideEditor() {
		// Show the user we're doing something
		$('#slide_editor_fetch_progress').fadeIn('fast');
		
		// Create a promise to fetch the template
		var promise = $.ajax({
			url: mobile_kiosk.template_url + '/fq/admin/mobile-kiosk/slide-new',
			method: 'GET'
		});
		
		// Add it to the page if it comes back
		promise.success(function(html) {
			// Hide our progress indicator
			$('#slide_editor_fetch_progress').fadeOut('fast');
			
			// Add in the code
			$('#slide_editor_wrapper').html(html);
			
			// Initialize the featured image piece
			featuredImageInit();
			
			// Initialize functionality for removing featured image
			removeFeaturedImageInit();
			
			// Initialize the TinyMCE Editor
			tinymce.EditorManager.editors = [];
			tinymce.init({ selector: 'slide_content', theme:'modern',  skin:'lightgray', menubar: false }); tinyMCE.execCommand('mceAddEditor', true, 'slide_content');
		});
	}
	
	
	/**
	 * Initialize editing featured image
	 */
	function featuredImageInit() {
		// Instantiates the variable that holds the media library frame.
	    var meta_image_frame;

	
	    // Runs when the image button is clicked.
	    $('#slide_add_featured_image').click(function(e){
	        // Prevents the default action from occuring.
	        e.preventDefault();
	        
		    var button = $(this);
		    var fieldLabel = 'Add featured image';
	
	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	            title: fieldLabel,
	            button: { text:  'Update featured image' },
	            library: { type: 'image' }
	        });
	
	        // Runs when an image is selected.
	        meta_image_frame.on('select', function(){
	
	            // Grabs the attachment selection and creates a JSON representation of the model.
	            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	            
	            // Create a hidden field to hold the media id
	            if ($('#featured_image_id').length == 0) {
		            $('.slide-featured-image').prepend('<input type="hidden" name="featured_image_id" id="featured_image_id" value="' + media_attachment.id + '">');
	            }
	            else {
			         $('#featured_image_id').val(media_attachment.id);   
	            }
	            
	            // Create our image preview
	            if ($('#featured_image_preview').length == 0) {
		            $('.slide-featured-image').prepend('<img id="featured_image_preview" class="thumbnail" src="' +  media_attachment.sizes.thumbnail.url + '">');
	            }
	            else {
			        $('#featured_image_preview').attr('src', media_attachment.sizes.thumbnail.url);   
	            }
	            
	            // Hide the link to add featured image
	            $('#slide_add_featured_image').hide();
	            
	            // Show the remove featured image
				$('#slide_remove_featured_image').show();
	            
	        });
	
	        // Opens the media library frame.
	        meta_image_frame.open();
	    });
	}
	
	
	/**
	 * Initialize removing featured image 
	 */
	function removeFeaturedImageInit() {
		$('body').on('click', '#slide_remove_featured_image', function(e) {
			// Remove the preview image and hidden field
			$('#featured_image_preview').remove();
			$('#featured_image_id').remove();
			
			// Show the add link, hide the remove link
			$('#slide_remove_featured_image').hide();
			$('#slide_add_featured_image').show();
		});
	}
	
	
	
	/**
	 * Removes the slide editor
	 */
	function removeSlideEditor() {
		$('#slide_editor_wrapper .slide-editor').fadeOut(250);
		setTimeout(function() {
			$('#slide_editor_wrapper .slide-editor').remove();
		}, 550);
	}
	
	
	/**
	 * Show the add slide dunctionality
	 */
	function showAddSlide() {
		// Create our promise to fetch the template
		var promise = $.ajax({
			url: mobile_kiosk.template_url + '/fq/admin/mobile-kiosk/show-add-slide',
			method: 'GET'
		});
		promise.done(function(html) {
			// Add the html template to the page
			$('#add_slide_wrapper').html(html);
			
			// Initialize this UI
			addSlideInit();
		});
	}
	
	
	/**
	 * Initialize the add slide UI
	 */
	function addSlideInit() {
		// Enable selecting the available slides to add
		$('body').on('click', '#add_slide_wrapper .available-slides li', function(e) {
			e.preventDefault();
			
			// Toggle the selected class
			$(this).toggleClass('selected');
		});
		
		// Remove the add slide UI
		$('body').on('click', '#fq_kiosk_gallery_cancel_add_existing_slides', function(e) {
			e.preventDefault();
			removeAddSlideUI();
		});
		
		// Handle adding the slides
		$('body').on('click', '#fq_kiosk_gallery_add_existing_slides', function(e) {
			// Get all of the "selected" slides that haven't already been added
			$('#add_slide_wrapper .available-slides li.selected').each(function() {
				var slideToAdd = $(this).clone();
				var exists = false;
				
				// Make sure this doesn't already reside in the slide list
				$('.slides li').each(function() {
					if ($(this).data('id') == $(slideToAdd).data('id')) {
						exists = true;
					}	
				});
				
				// Add our hiddend field to this
				$(slideToAdd).append('<input type="hidden" name="slides[]" value="' +  $(slideToAdd).data('id') + '">');
				
				if (!exists) {
					$('.slides').append($(slideToAdd));
				}
			});
			
			// Append this to the list of slides attached
			$('.no-slides').remove();
			
			// De-select all the avaialble slides
			$('#add_slide_wrapper .available-slides li').removeClass('selected');
			$('.slides li').removeClass('selected');
		});
	}
	
	
	/**
	 * Removes the add slide UI
	 */
	function removeAddSlideUI() {
		$('#add_slide_wrapper').html('');
	}
	
	
	/**
	 * Show the template for editing an existing slide
	 */
	function showSlideEdit(id) {
		// Create our ajax promise
		var promise = $.ajax({
			url: mobile_kiosk.template_url + '/fq/admin/mobile-kiosk/slide-edit',
			method: 'GET',
			data: {
				id: id
			}
		});
		promise.done(function(html) {
			removeSlideEditor();
			removeAddSlideUI();
			
			// Add the template
			$('#edit_slide_wrapper').html(html);
			
			// Initialize the featured image piece
			featuredImageInit();
			
			// Initialize functionality for removing featured image
			removeFeaturedImageInit();
			
			// Initialize the TinyMCE Editor
			tinymce.EditorManager.editors = [];
			tinymce.init({ selector: 'slide_content', theme:'modern',  skin:'lightgray', menubar: false }); tinyMCE.execCommand('mceAddEditor', true, 'slide_content');
		});
		
		$('body').on('click', '#fq_gallery_kiosk_cancel_slide', function(e) {
			removeEditSlideEditor();
		});
		
		$('body').on('click', '#fq_gallery_kiosk_update_slide', function(e) {
			updateSlide();
		});
	}
	
	
	/**
	 * Removes the edit existing slide editor
	 */
	function removeEditSlideEditor() {
		$('#edit_slide_wrapper').html('');
		$('li.editing').removeClass('editing');
	}
	
	
	/**
	 * Updates an existing slide
	 */
	function updateSlide() {
		var id = $('[name="edit_slide_id"]').val();
		
		// Save the tinymce content
		tinyMCE.get("slide_content").save();
		
		// Our promise for performing the update
		var promise = $.ajax({
			url: mobile_kiosk.template_url + '/fq/admin/mobile-kiosk/slide-update',
			method: 'POST',
			data: {
				slide_title: $('[name="slide_title"]').val(),
				slide_content: $('[name="slide_content"]').val(),
				featured_image_id: $('[name="featured_image_id"]').val(),
				id: id
			}
		});
		promise.done(function(data) {
			// Parse the data
			data = $.parseJSON(data);
			
			// Find the existing slide to update
			$('.slides li').each(function() {
				var row = $(this);
				
				// Is this the one we're looking for?
				if ($(row).data('id') == id) {
					// Update the slide
					$(row).find('img').attr('src', data.thumbnail);
					$(row).find('h3').html(data.post_title);
					
					// Break out of the loop
					return false;
				}
			});
			
			// Remove the editor
			removeEditSlideEditor();
			
			// Add a success message
			$('#slide_messages').html('<p class="alert alert-success">Slide updated!');
			fadeOutMessages();
		});
		
	}
	
	
	/**
	 * Fade out status messages
	 */
	function fadeOutMessages() {
		setTimeout(function() {
			$('#slide_messages p').fadeOut(250);
			setTimeout(function() {
				$('#slide_messages').html('');
			});
		}, 5000);
	}
	
	
	
	/**
	 * Upload / get gallery logo
	 */
	function galleryLogo() {
		$('body').on('click', '#remove_gallery_logo', function(e) {
			e.preventDefault();
			$('.gallery-logo-preview').hide();
			$('#gallery_logo').val('');
			$('#remove_gallery_logo').hide();
		});
		
		// Runs when the image button is clicked.
	    $('#upload_logo_trigger').click(function(e){
	        // Prevents the default action from occuring.
	        e.preventDefault();
	        
		    var button = $(this);
		    var fieldLabel = 'Add logo';
	
	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	            title: fieldLabel,
	            button: { text:  'Update logo' },
	            library: { type: 'image' }
	        });
	
	        // Runs when an image is selected.
	        meta_image_frame.on('select', function(){
	
	            // Grabs the attachment selection and creates a JSON representation of the model.
	            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	            
	            // Update the hidden field with the media id
	            $('#gallery_logo').val(media_attachment.id);
	            
	            // Update our preview image
	            $('.gallery-logo-preview').attr('src', media_attachment.sizes.thumbnail.uhisrl);
	            $('.gallery-logo-preview').fadeIn('fast');
	            $('#remove_gallery_logo').fadeIn('fast');
	            
	        });
	
	        // Opens the media library frame.
	        meta_image_frame.open();
	    });
	}
	
	
	
	
	/**
	 * Initializes our sources behavior
	 */
	function sourcesInit() {
		// Bind this to checking / unchecking the source checkboxes
		$('body').on('change', '.mobile-kiosk-sources', function(e) {
			var id = $(this).val();
			$('#' + id + '-options').toggle();
		});
	}
	
	
	/**
	 * Initializes our gallery options
	 */
	function galleryOptionsInit() {
		$('body').on('click', '.gallery-options-toggles button', function(e) {
			e.preventDefault();
			
			// Hide / show the appropriate item
			var toggle = $(this);
			var id = $(toggle).data('id');
			$('.gallery-options-toggles button').removeClass('active');
			$(toggle).addClass('active');
			
			$('.gallery-options').removeClass('active');
			$('#gallery_options_' + id).addClass('active');
		});
		
		$('body').on('change', '#slide_layout', function(e) {
			var layout = $(this).val();
			if (layout == "bar-bottom") {
				$('#bottom_bar_text_color_wrapper').show();
			}
			else {
				$('#bottom_bar_text_color_wrapper').hide();
			}
		});
		
		// The layout buttons
		$('body').on('click', '.mobile-kiosk-layout-button', function(e) {
			var button = $(this);
			var active = $(this).hasClass('active');
			var layout = $(this).data('layout');
			$('.mobile-kiosk-layout-button').removeClass('active');
			if (!active) {
				$(button).addClass('active');
				$('#slide_layout').val(layout);
				
				if (layout == 'bar-bottom') {
					$('#bottom_bar_text_color_wrapper').show();
				}
				else {
					$('#bottom_bar_text_color_wrapper').hide();
				}
			}
			else {
				$('#bottom_bar_text_color_wrapper').hide();
			}
		})
	}
	
})(jQuery);