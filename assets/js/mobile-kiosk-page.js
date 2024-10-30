(function($) {

	// Load these items as soon as the page is ready
	$(document).ready(function () {
		sliderInit();
		refreshInit();
		
		// Show the takeover if there's a mailchimp alert to show
		if ($('.mc4wp-alert').length > 0) {
			$('.lead-takeover').addClass('open');
			$('.swiper-container').addClass('blurred');
		}
		
		// Hide the full screen button if it's a touch screen
		if (Modernizr.touchevents) {
			$('.fullscreen').remove();
		}
		
	    
	    // Trigger the takover
	    $('body').on('click', '.trigger-lead-form', function(e) {
		   e.preventDefault();
		   $('.lead-takeover').addClass('open');
		   $('.swiper-container').addClass('blurred'); 
	    });
	    
	    
	    // Trigger closing the takeover
	    $('body').on('click', '.lead-takeover .close', function(e) {
		   e.preventDefault();
		   $('.mc4wp-alert').remove();
		   $('.lead-takeover').removeClass('open');
		   $('.swiper-container').removeClass('blurred'); 
	    });
	    
	    
	    // Toggle fullscreen mode
	    $('body').on('click', '#goFullScreen', function(e) {
		    e.preventDefault();
		    $('#goFullScreen').hide();
		    $('#hideFullScreen').show();
		    launchIntoFullscreen(document.documentElement);
	    });
	    
	    $('body').on('click', '#hideFullScreen', function(e) {
		    e.preventDefault();
		    $('#goFullScreen').show();
		   $('#hideFullScreen').hide();
		   exitFullScreen();
	    });
	});
	
	
	// Don't do this until all the images have loaded
	$(window).on('load', function() {
		hidePreloader();
	});

	
	function hidePreloader() {
		$('.preloader').fadeOut('fast');
	}
	
	
	function sliderInit() {
		slide_duration = (slide_duration !== undefined) ? slide_duration : 10;
		
		// Init the swiper
		var swiper = new Swiper('.swiper-container', {
	        pagination: '.swiper-pagination',
	        paginationClickable: true,
	        autoplay: parseInt(slide_duration) * 1000, 
	        loop: true,
	        nextButton: '.swiper-button-next',
	        prevButton: '.swiper-button-prev',
	    });
	}
	
	
	// Find the right method, call on correct element
	function launchIntoFullscreen(element) {
		if(element.requestFullscreen) {
			element.requestFullscreen();
		} else if(element.mozRequestFullScreen) {
			element.mozRequestFullScreen();
		} else if(element.webkitRequestFullscreen) {
			element.webkitRequestFullscreen();
		} else if(element.msRequestFullscreen) {
			element.msRequestFullscreen();
		}
	}
	
	function exitFullScreen() {
		if(document.exitFullscreen) {
			document.exitFullscreen();
		} else if(document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if(document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}
	}
	
	
	function refreshInit() {
  	if (refreshMin !== undefined) {
    	setTimeout(function() {
      	location.reload();
    	}, refreshMin * 60000);
  	}
	}



})(jQuery)