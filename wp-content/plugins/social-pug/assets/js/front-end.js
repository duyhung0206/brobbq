jQuery( function($) {

	/*
	 * Position sidebar icons vertically
	 *
	 */
	$(document).ready( function() {
		$('#dpsp-floating-sidebar').css('top', ( window.innerHeight - $('#dpsp-floating-sidebar').height() ) / 2 );
	});

	$(window).on( 'resize', function() {
		$('#dpsp-floating-sidebar').css('top', ( window.innerHeight - $('#dpsp-floating-sidebar').height() ) / 2 );
	});


	/**
	 * When entering and leaving a button, add a class of hover to the wrapping <li> element
	 *
	 */
	$(document).on( 'mouseenter', '.dpsp-networks-btns-wrapper li a', function(e) {

		$(this).closest('li').addClass('dpsp-hover');

	});

	$(document).on( 'mouseleave', '.dpsp-networks-btns-wrapper li a', function() {

		$(this).closest('li').removeClass('dpsp-hover');

	});


	/*
	 * Open Pinterest overlay to select which image to pin when
	 * clicking on a Pin button without media attached
	 *
	 */
	$(document).ready( function() {
		$('.dpsp-networks-btns-share .dpsp-network-btn.dpsp-pinterest').click( function(e) {

			if( $(this).data( 'href' ) == '#' ) {
				e.preventDefault();

				var elem = document.createElement('script');
				elem.setAttribute('type', 'text/javascript');
				elem.setAttribute('charset', 'UTF-8');
				elem.setAttribute('src', 'https://assets.pinterest.com/js/pinmarklet.js');
				document.body.appendChild(elem);
			}

		});
	});


	/*
	 * Print button action
	 *
	 */
	$(document).ready( function() {
		$('.dpsp-networks-btns-share .dpsp-network-btn.dpsp-print').click( function(e) {
			window.print();
		});
	});


	/*
	 * Open share links in a pop-up window
	 *
	 */
	$(document).on( 'click', '.dpsp-networks-btns-share .dpsp-network-btn, .dpsp-click-to-tweet, .dpsp-pin-it-button', function(e) {

		if( $(this).hasClass('dpsp-whatsapp') || $(this).hasClass('dpsp-email') )
			return;

		if( ( $(this).hasClass('dpsp-twitter') || $(this).hasClass('dpsp-click-to-tweet') ) && typeof window.twttr != 'undefined' )
			return;

		e.preventDefault();

		if( $(this).attr( 'href' ) == '#' || $(this).data( 'href' ) == '#' )
			return false;

		$(this).blur();

		var window_size = {
			width  : 700,
			height : 300
		}

		if( $(this).hasClass('dpsp-buffer') ) {
			window_size.width = 800;
			window_size.height = 575;
		}

		var url = ( typeof $(this).data( 'href' ) != 'undefined' ? $(this).data( 'href' ) : $(this).attr( 'href' ) );

		window.open( url,'targetWindow', "toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=" + window_size.width + ",height=" + window_size.height + ",top=200,left=" + ($(window).innerWidth() - window_size.width)/2 );

	});


	/*
	 * Calculate position of viewport when scrolling
	 *
	 */
	var dpsp_hasScrolled 	   = false;
	var dpsp_scrollTop 	   	   = 0;

	$(window).scroll( function() {

		// User has scrolled
		dpsp_hasScrolled = true;
			
		// Calculate scrollTop in percentages on user scroll
		dpsp_scrollTop = parseInt( $(window).scrollTop() / $(document).innerHeight() * 100 );

	});

	$(window).load( function() {

		// Calculate scrollTop in percentages on page load
		dpsp_scrollTop = parseInt( $(window).scrollTop() / $(document).innerHeight() * 100 );

	})


	/*
	 * Handle floating sidebar show events
	 *
	 */
	if( $('#dpsp-floating-sidebar').length > 0 ) {

		var dpsp_FloatingSidebarTriggerScroll = $('#dpsp-floating-sidebar').attr('data-trigger-scroll');

		/*
		 * Handle display of the mobile sticky while scrolling the page
		 *
		 */
		if( dpsp_FloatingSidebarTriggerScroll != 'false' ) {

			$(window).on( 'scroll load', function() {
			
				// Trigger for scroll position
				if( dpsp_scrollTop > parseInt(dpsp_FloatingSidebarTriggerScroll) ) 
					$('#dpsp-floating-sidebar').addClass('opened');
				else
					$('#dpsp-floating-sidebar').removeClass('opened');
				
			});

		// If there's no scroll trigger just display the buttons
		} else {

			$('#dpsp-floating-sidebar').addClass('opened');
				
		}

	}


	/*
	 * Handle sticky bar show events
	 *
	 */
	if( $('#dpsp-sticky-bar-wrapper').length > 0 ) {

		var dpsp_StickyBarTriggerScroll = $('#dpsp-sticky-bar-wrapper').attr('data-trigger-scroll');

		/**
		 * Remove the sticky bar if not on proper device
		 *
		 */
		if( $('#dpsp-sticky-bar-wrapper').hasClass( 'dpsp-device-mobile' ) && window.outerWidth > $('#dpsp-post-sticky-bar-markup').data('mobile-size') )
			$('#dpsp-sticky-bar-wrapper').remove();

		if( $('#dpsp-sticky-bar-wrapper').hasClass( 'dpsp-device-desktop' ) && window.outerWidth <= $('#dpsp-post-sticky-bar-markup').data('mobile-size') )
			$('#dpsp-sticky-bar-wrapper').remove();		

		/**
		 * Handle display of the sticky bar while scrolling the page
		 *
		 */
		$(window).scroll( function() {
			
			if( dpsp_hasScrolled == true ) {

				// Trigger for scroll position
				if( dpsp_StickyBarTriggerScroll != 'false' && dpsp_scrollTop > parseInt( dpsp_StickyBarTriggerScroll ) ) 
					$('#dpsp-sticky-bar-wrapper').addClass('opened');

			}
			
		});

		/**
		 * Handle display of the sticky bar on page load
		 *
		 */
		if( dpsp_StickyBarTriggerScroll == 'false' )
			$('#dpsp-sticky-bar-wrapper').addClass('opened');

		/**
		 * Handle the positioning of the buttons
		 *
		 */
		if( $('#dpsp-post-sticky-bar-markup').length > 0 ) {

			$(window).on( 'resize', function() {

				// Handle the position of the buttons
				$('#dpsp-sticky-bar')
					.outerWidth( $('#dpsp-post-sticky-bar-markup').parent().outerWidth() )
					.css( 'left', $('#dpsp-post-sticky-bar-markup').offset().left )
					.show();

				// Add and remove the is mobile class
				if( window.outerWidth <= $('#dpsp-post-sticky-bar-markup').data('mobile-size') )
					$('#dpsp-sticky-bar-wrapper').addClass( 'dpsp-is-mobile' );
				else
					$('#dpsp-sticky-bar-wrapper').removeClass( 'dpsp-is-mobile' );

			});

			$(window).trigger( 'resize' );

		}

		/**
		 * Add padding bottom to the body representing the tool's height
		 *
		 */
		$('body').css( 'padding-bottom', $('#dpsp-sticky-bar-wrapper').height() );

	}
	

	/*
	 * Handle share pop-up events for the Pop-Up tool
	 *
	 */
	if( $('#dpsp-pop-up').length > 0 ) {

		// Set defaults, like trigger values and scroll value
		var dpsp_PopUpTriggerScroll = $('#dpsp-pop-up').attr('data-trigger-scroll');
		var dpsp_TriggerPostBottom  = $('#dpsp-post-bottom').length > 0 ? parseInt( $('#dpsp-post-bottom').offset().top ) : false;
		var dpsp_TriggerExitIntent  = $('#dpsp-pop-up').attr('data-trigger-exit');
		var dpsp_TriggerTimeDelay   = parseInt( $('#dpsp-pop-up').attr('data-trigger-delay') );

		var pop_up_session		    = $('#dpsp-pop-up').data('session');

		/*
		 * Handle display of the pop-up when in a session 
		 *
		 */
		if( pop_up_session != 0 ) {

			if( getCookie('dpsp_pop_up') != '' ) {

				$('#dpsp-pop-up').remove();
				$('#dpsp-pop-up-overlay').remove();

			}

		} else
			setCookie( 'dpsp_pop_up', '', -1 );


		/*
		 * Handle display of the pop-up while scrolling the page
		 *
		 */
		$(window).scroll( function() {
			
			if( dpsp_hasScrolled == true ) {

				// Trigger for scroll position
				if( dpsp_PopUpTriggerScroll != 'false' && dpsp_scrollTop > parseInt(dpsp_PopUpTriggerScroll) )
					showPopUp();

				// Trigger for bottom of post
				if( dpsp_TriggerPostBottom != false && $(window).scrollTop() + window.innerHeight / 1.5 >= dpsp_TriggerPostBottom )
					showPopUp();

			}
			
		});


		/*
		 * Bind the document mouse leave with the show pop-up if the exit intent is set to true
		 *
		 */
		if( dpsp_TriggerExitIntent == 'true' ) {

			document.documentElement.addEventListener( 'mouseleave', documentMouseLeave );

			function documentMouseLeave(e) {
				if( e.clientY < 1 )
					showPopUp();
			}

		}


		/*
		 * Show pop-up after time delay
		 *
		 */
		if( !isNaN( dpsp_TriggerTimeDelay ) ) {

			setTimeout( showPopUp, dpsp_TriggerTimeDelay * 1000 );

		}


		/*
		 * Position the pop-up in the center of the viewport when resizing the window
		 *
		 */
		$(window).resize( function() {
			
			$popUp = $('#dpsp-pop-up');
			
			if( $popUp.hasClass( 'opened' ) )
				positionPopUp();

		});


		/*
		 * Hide pop-up when clicking the overlay
		 * Hide pop-up when clicking the close button
		 * Hide pop-up when clicking a network button
		 *
		 */
		$('#dpsp-pop-up-overlay, #dpsp-pop-up-close, .dpsp-network-btn').click( function() {
			hidePopUp();
		});


		/*
		 * Shows the pop-up
		 *
		 */
		function showPopUp() {
			positionPopUp();

			$('#dpsp-pop-up').addClass('opened');
			$('#dpsp-pop-up-overlay').addClass('opened');

			setCookie( 'dpsp_pop_up', '1', pop_up_session, '/' );
		}


		/*
		 * Hides the pop-up and removes it from the DOM
		 *
		 */
		function hidePopUp() {
			$('#dpsp-pop-up').removeClass('opened');
			$('#dpsp-pop-up-overlay').removeClass('opened');

			setTimeout( function() {
				$('#dpsp-pop-up').remove();
				$('#dpsp-pop-up-overlay').remove();
			}, 250 );
		}

		/*
		 * Function that positions the pop-up in the center of the page
		 *
		 */
		function positionPopUp() {

			$popUp = $('#dpsp-pop-up');

			var windowHeight = window.innerHeight;
			var windowWidth  = window.innerWidth;

			var popUpHeight  = $popUp.outerHeight();
			var popUpWidth   = $popUp.outerWidth();

			$popUp.css({
				top  : ( windowHeight - popUpHeight ) / 2,
				left : ( windowWidth - popUpWidth ) / 2
			});

		}

	}


	/*
	 * Set a cookie
	 *
	 */
	function setCookie( cname, cvalue, exdays, path ) {

	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();

	    if( path )
	    	path = "path=" + path;

	    document.cookie = cname + "=" + cvalue + "; " + expires + "; " + path;
	}

	/*
	 * Get a cookie
	 *
	 */
	 function getCookie( cname ) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	    }
	    return "";
	}


	/**
	 * Pinterest Image Hover
	 *
	 */
	if( typeof dpsp_pin_button_data != 'undefined' && $('#dpsp-post-content-markup').length > 0 ) {

		var pin_data = dpsp_pin_button_data;
		var page_url = document.URL;

		$content = $('#dpsp-post-content-markup').parent();
		$images  = $content.find('img');

		$images.each( function() {

			var $image = $( this );

			// Return if image is not visible
			if( $image.outerWidth() <= 0 || $image.outerHeight() <= 0 )
				return;

			// Return if defined explicitly not to be pinned
			if( $image.hasClass( 'dpsp_no_pin' ) || $image.hasClass( 'nopin' ) ) {

				$image.attr( 'nopin', 'nopin' );
				return;
				
			}

			// Return if image is smaller than the minimum sizes
			if( $image.outerWidth() < parseInt( pin_data.minimum_image_width ) || $image.outerHeight() < parseInt( pin_data.minimum_image_height ) )
				return;

			// Get the pinnable image
			pin_image = '';

			if ( $image.data( 'media' ) )
				pin_image = $image.data( 'media' );

			else if ( $image.data( 'lazy-src' ) )
			    pin_image = $image.data( 'lazy-src' );

			else if ( $image.attr( 'src' ) )
				pin_image = $image.attr( 'src' );
			
			if( pin_image == '' )
				return;

			// Get the pinnable image description
			pin_description = '';

			if( $image.data( 'pin-description' ) )
				pin_description = $image.data( 'pin-description' );

			else if( $image.attr( 'alt' ) )
				pin_description = $image.attr( 'alt' );

			else if ( typeof pin_data.pinterest_description != 'undefined' )
				pin_description = pin_data.pinterest_description;

			// Wrap the image
			var image_classes = $image.attr( 'class' );
			var image_styles  = $image.attr( 'style' );

			$image.wrap( '<span class="dpsp-pin-it-wrapper">' );

			$image.closest( '.dpsp-pin-it-wrapper' ).addClass( image_classes ).css( 'style', image_styles );
			$image.removeClass().css( 'style', '' );

			// Add the pin button
			var pin_button_href = 'http://pinterest.com/pin/create/bookmarklet/?media=' + encodeURI( pin_image ) + '&url=' + encodeURI( page_url ) + '&is_video=false' + '&description=' +  encodeURIComponent( pin_description );

			$image.after( '<a class="dpsp-pin-it-button dpsp-pin-it-button-' + pin_data.button_position.replace( '_', '-' ) + ' dpsp-pin-it-button-shape-' + pin_data.button_shape + ' ' + ( pin_data.show_button_text_label ? 'dpsp-pin-it-button-has-label' : '' ) + '" href="' + pin_button_href + '">' + ( pin_data.show_button_text_label ? pin_data.button_text_label : '' ) + '</a>' );

			// Add overlay if set in settings
			if( pin_data.show_image_overlay )
				$image.after( '<span class="dpsp-pin-it-overlay"></span>' );

			// Position the button right in the center of the image
			if( pin_data.button_position == 'center' ) {

				$pin_button = $image.siblings( '.dpsp-pin-it-button' );
				$pin_button.css( 'margin-left', -$pin_button.outerWidth() / 2 ).css( 'margin-top', -$pin_button.outerHeight() / 2 );

			}

		});

	}

});