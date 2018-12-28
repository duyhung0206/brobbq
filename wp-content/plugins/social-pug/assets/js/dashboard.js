jQuery( function($) {

	/*
     * Strips one query argument from a given URL string
     *
     */
    function remove_query_arg( key, sourceURL ) {

        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }

            rtn = rtn + "?" + params_arr.join("&");

        }

        if(rtn.split("?")[1] == "") {
            rtn = rtn.split("?")[0];
        }

        return rtn;
    }


    /*
     * Adds a argument name, value pair to a given URL string
     *
     */
    function add_query_arg( key, value, sourceURL ) {

    	var separator = sourceURL.indexOf('?') !== -1 ? "&" : "?";

        return sourceURL + separator + key + '=' + value;

    }


	/*****************************************************/
	/* Toolkit page
	/*****************************************************/
	$(document).on( 'click', '.dpsp-tool-wrapper .dpsp-switch label', function(e) {
		
		var $actions_wrapper = $(this).closest('.dpsp-tool-actions');
		var $action_settings = $actions_wrapper.find('.dpsp-tool-settings');

		// Add Loading Overlay
		$action_settings.fadeOut(200);
		$actions_wrapper.append('<div class="dpsp-tool-actions-overlay"><div class="spinner"></div></div>');
		$actions_wrapper.find('.dpsp-tool-actions-overlay').animate({opacity: 1}, 200);

		// Get tool to activate/deactivate
		var tool 		 = $(this).siblings('input').data('tool');
		var tool_setting = $(this).siblings('input').data('tool-activation');

		if( tool != 'undefined' ) {

			if( $actions_wrapper.hasClass('dpsp-inactive') ) {

				activateTool( tool ).done( function( response ) {
					if( response == 1 ) {
						$actions_wrapper.find('.dpsp-tool-actions-overlay').animate({opacity: 0}, 200, function() { $(this).remove() });
						$actions_wrapper.removeClass('dpsp-inactive').addClass('dpsp-active');
						$actions_wrapper.find('span').text('Active');
						$action_settings.fadeIn(200);
					}
				});	

			} else {

				deactivateTool( tool ).done( function( response ) {
					if( response == 1 ) {
						$actions_wrapper.find('.dpsp-tool-actions-overlay').animate({opacity: 0}, 200, function() { $(this).remove() });
						$actions_wrapper.removeClass('dpsp-active').addClass('dpsp-inactive');
						$actions_wrapper.find('span').text('Inactive');

						hideMenuItem( tool );
					}
				});

			}
			
		}

	});

	
	/*
	 * Make an AJAX call to activate a tool
	 */
	function activateTool( tool ) {

		var data = {
			'action' 	: 'dpsp_activate_tool',
			'dpsptkn'	: $('#dpsptkn').val(),
			'tool'		: tool
		}

		return $.post( ajaxurl, data, function() {});

	}

	/*
	 * Make an AJAX call to deactivate a tool
	 */
	function deactivateTool( tool ) {

		var data = {
			'action' 	: 'dpsp_deactivate_tool',
			'dpsptkn'	: $('#dpsptkn').val(),
			'tool'		: tool
		}

		return $.post( ajaxurl, data, function() {});

	}


	/*
	 * Hides the menu item from the WP sidebar for a given location
	 */
	function hideMenuItem( location ) {

		location = location.replace('share_', '').replace('follow_', '').replace('misc_', '').replace( '_', '-' );

		var $menuItems = $('#toplevel_page_dpsp-social-pug .wp-submenu li a');

		$menuItems.each( function() {
			if( $(this).attr('href').indexOf( location ) != -1 )
				$(this).parent().hide();
		});

	}


	/*
	 * Initialize Color Picker
	 *
	 */
	if( $.fn.wpColorPicker )
		$('.dpsp-color-picker').wpColorPicker();


	/*
	 * Initialize jQuery select2
	 *
	 */
	if( $.fn.select2 ) {
		$('.dpsp-setting-field-select select').select2({
			minimumResultsForSearch : Infinity
		}).on('select2:open', function() {
	  		var container = $('.select2-container').last();
	  		container.addClass('dpsp-select2');
		});
	}
	
	/*
	 * Initialize jQuery sortable
	 *
	 */
	$( function() {
		$('.dpsp-social-platforms-sort-list').sortable({
			handle: '.dpsp-sort-handle',
			placeholder: 'dpsp-sort-placeholder',
			containment: '#wpwrap'
		});
	});


	/*
	 * Social newtworks checkboxes
	 *
	 */
	$(document).on( 'click', '#dpsp-networks-selector .dpsp-network-item', function() {
		$this = $(this);
		$checkbox = $this.children('.dpsp-network-item-checkbox');

		if( $this.attr('data-checked') )
			$this.removeAttr('data-checked');
		else
			$this.attr('data-checked', 'true');

	});


	$(document).on( 'click', '#dpsp-select-networks', function(e) {
		e.preventDefault();

		if( $('#dpsp-networks-selector-wrapper').hasClass('active') ) {
			$('#dpsp-networks-selector-wrapper').removeClass('active');
			$('#dpsp-networks-selector-wrapper').stop().slideUp(300);

			if( $('.dpsp-social-platforms-sort-list').find('li').length == 0 )
				showSortablePlaceholder();
		} else {
			$('#dpsp-networks-selector-wrapper').addClass('active');
			$('#dpsp-networks-selector-wrapper').stop().slideDown(300);
			hideSortablePlaceholder();
		}
			
		
	});


	$(document).on( 'click', '#dpsp-networks-selector-footer .button-primary', function(e) {
		e.preventDefault();

		// Hide selector
		$('#dpsp-networks-selector-wrapper').removeClass('active').stop().slideUp(300);

		// Parse each network from the selector panel
		$('#dpsp-networks-selector .dpsp-network-item').each( function() {
			$this = $(this);

			var dataNetwork = $this.attr('data-network');
			var dataNetworkName = $this.attr('data-network-name');

			if( !$this.attr('data-checked') ) {

				removeSortableNetworkItem( dataNetwork );

			} else {

				var alreadyInList = false;

				$('.dpsp-social-platforms-sort-list li').each( function() {
					if( $(this).attr('data-network') == dataNetwork )
						alreadyInList = true;
				});

				if( alreadyInList )
					return alreadyInList;

				addSortableNetworkItem( dataNetwork, dataNetworkName );
			}

		});

		// If there are no networks in the sortable list display the empty placeholder
		if( $('.dpsp-social-platforms-sort-list').find('li').length == 0 )
				showSortablePlaceholder();

	});


	/**
	 * Selects the edit label field from the sortable list when the admin
	 * clicks on the edit label button
	 *
	 */
	$(document).on( 'click', '.dpsp-list-edit-label', function(e) {

		e.preventDefault();

		$(this).closest('li').find( '.dpsp-list-input-wrapper input' ).focus().select();

	});


	/**
	 * Removes the social network from the sortable list when clicking
	 * on the .dpsp-list-remove class and also uncheckes the social 
	 * network from the selectable networks list
	 *
	 */
	$(document).on( 'click', '.dpsp-list-remove', function(e) {

		e.preventDefault();

		var dataNetwork = $(this).closest('li').attr('data-network');

		removeSortableNetworkItem( dataNetwork );

		$('#dpsp-networks-selector .dpsp-network-item[data-network="' + dataNetwork + '"]').removeAttr('data-checked');

		// If there are no networks in the sortable list display the empty placeholder
		if( $('.dpsp-social-platforms-sort-list').find('li').length == 0 ) {
			$('#dpsp-sortable-networks-empty').css('opacity', 0).stop().slideDown(200).animate({opacity: 1}, 300);
			$('#dpsp-sortable-networks-empty').addClass('active');
		}

	});


	/*
	 * Function that populates the sortable list with new data
	 *
	 */
	function addSortableNetworkItem( slug, name ) {
		
		if( slug == 'undefined')
			return false;

		if( name == 'undefined')
			return false;

		var html = '';

		var location = $('input[name="dpsp_buttons_location"]').val();

		console.log(location)

		html += '<li data-network="' + slug + '" style="display: none;">';
			html += '<div class="dpsp-sort-handle ui-sortable-handle"><!-- --></div>';
			html += '<div class="dpsp-list-icon dpsp-list-icon-social dpsp-icon-' + slug + '"><!-- --></div>';
			html += '<div class="dpsp-list-input-wrapper"><input type="text" placeholder="' + 'This button has no label text.' + '" name="' + location + '[networks][' + slug + '][label]" value="' + name + '"></div>';
			
			// List item actions
			html += '<div class="dpsp-list-actions">';
				html += '<a class="dpsp-list-edit-label dpsp-transition" href="#"><span class="dashicons dashicons-edit"></span>' + 'Edit Label' + '</a>';
				html += '<a class="dpsp-list-remove dpsp-transition" href="#"><span class="dashicons dashicons-no-alt"></span>' + 'Remove' + '</a>';
			html += '</div>';
		html += '</li>';

		$('.dpsp-social-platforms-sort-list').append( html );
		$('.dpsp-social-platforms-sort-list li:not(":visible")').fadeIn();
	}


	function removeSortableNetworkItem( slug ) {

		$('.dpsp-social-platforms-sort-list li[data-network="' + slug + '"]').remove();

	}

	function showSortablePlaceholder() {

		$('#dpsp-sortable-networks-empty').stop().slideDown(200);
		$('#dpsp-sortable-networks-empty').addClass('active');

	}

	function hideSortablePlaceholder() {

		$('#dpsp-sortable-networks-empty').stop().slideUp(200);
		$('#dpsp-sortable-networks-empty').removeClass('active');
		
	}


	$(document).ready( function() {
		$('.dpsp-network-btn').attr('href', '#');
	});

	$(document).on( 'click', '.dpsp-network-btn', function(e) {
		e.preventDefault();
		$(this).closest('label').click();
	});


	/*
	 * Disable inputs for certain networks sortable panels
	 *
	 */
	$(document).on( 'focus', '.dpsp-page-mobile .dpsp-list-input-wrapper input', function() {
		$(this).blur();
	});

	$(document).on( 'focus', '.dpsp-page-sticky-bar .dpsp-list-input-wrapper input', function() {
		$(this).blur();
	});

	/**
	 * Saves the placeholder of the input in a data attribute on focus and 
	 * removes the placeholder.
	 *
	 * It's for visual aspect only.
	 *
	 */
	$(document).on( 'focus', '.dpsp-list-input-wrapper input', function() {

		$(this).attr( 'data-placeholder', $(this).attr( 'placeholder' ) );
		$(this).attr( 'placeholder', ' ' );

	});

	/**
	 * Adds the saved placeholder data attribute back to the actual placeholder
	 *
	 * It's for visual aspect only.
	 *
	 */
	$(document).on( 'blur', '.dpsp-list-input-wrapper input', function() {

		$(this).attr( 'placeholder', $(this).attr( 'data-placeholder' ) );
		$(this).attr( 'data-placeholder', ' ' );

	});


	/*
	 * Set the shape of the network buttons on page load and dynamicly
	 *
	 */
	$(document).on( 'change', '.dpsp-setting-field-button-shape select', function() {
		$(this)
			.closest('.dpsp-page-wrapper')
			.find('.dpsp-networks-btns-wrapper')
			.parent()
			.removeClass('dpsp-shape-circle dpsp-shape-rounded dpsp-shape-rectangular')
			.addClass('dpsp-shape-' + $(this).val() );
	});


	/*
	 * Show and hide back-end settings tool-tips
	 *
	 */
	$(document).on( 'mouseenter', '.dpsp-setting-field-tooltip-icon', function() {
		$(this).siblings('div').css('opacity', 1).css('visibility', 'visible');
	});
	$(document).on( 'mouseleave', '.dpsp-setting-field-tooltip-icon', function() {
		$(this).siblings('div').css('opacity', 0).css('visibility', 'hidden');
	});

	$(document).on( 'mouseenter', '.dpsp-setting-field-tooltip-wrapper.dpsp-has-link', function() {
		$(this).find('div').css('opacity', 1).css('visibility', 'visible');
	});
	$(document).on( 'mouseleave', '.dpsp-setting-field-tooltip-wrapper.dpsp-has-link', function() {
		$(this).find('div').css('opacity', 0).css('visibility', 'hidden');
	});


	/*****************************************************/
	/* Settings Field: Image
	/*****************************************************/
	var frame;

	$('.dpsp-image-select').on('click', function(e) {
		
		e.preventDefault();

		$btn_select = $(this);
		$btn_remove = $btn_select.siblings('.dpsp-image-remove');
		$image_id 	= $btn_select.siblings('.dpsp-image-id');
		$image_src 	= $btn_select.siblings('.dpsp-image-src');
		$image 		= $btn_select.siblings('div').find('img');

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create a new media frame
		frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Use Image'
			},
			multiple: false
		});

		// Select image from media frame
		frame.on( 'select', function() {
      
			var attachment = frame.state().get('selection').first().toJSON();

			$image_id.val( attachment.id );
			$image_src.val( attachment.url );
			$image.attr('src', '');
			$image.attr('src', attachment.url );
			$btn_select.addClass('hidden');
			$btn_remove.removeClass('hidden');

			// Create event for extended functionality
			var event = new CustomEvent( 'dpsp_settings_field_image_select_image', {
				detail: {
					field 		: $btn_select.closest('.dpsp-setting-field-image'),
			    	attachment  : attachment
			  	}
			});

			document.dispatchEvent(event);

	    });

		frame.open();

	});

	$('.dpsp-image-remove').on('click', function(e) {

		e.preventDefault();

		$btn_remove = $(this);
		$btn_select = $btn_remove.siblings('.dpsp-image-select');
		$image_id 	= $btn_remove.siblings('.dpsp-image-id');
		$image_src	= $btn_remove.siblings('.dpsp-image-src');
		$image 		= $btn_remove.siblings('div').find('img');

		$btn_remove.addClass('hidden');
		$btn_select.removeClass('hidden')
		$image_id.val('');
		$image_src.val('');
		$image.attr( 'src', ( typeof $image.siblings('.dpsp-field-image-placeholder').data('src') != 'undefined' ? $image.siblings('.dpsp-field-image-placeholder').data('src') : '' ) );

		// Create event for extended functionality
		var event = new CustomEvent( 'dpsp_settings_field_image_remove_image', {
			detail: {
				field : $btn_select.closest('.dpsp-setting-field-image')
		  	}
		});

		document.dispatchEvent(event);

	});


	/*****************************************************/
	/* Disable / enable settings that depend on other settings
	/*****************************************************/
	$( function() {

		// Set settings options
		$checkbox_shares 	   = $('.dpsp-setting-field-show-share-count input[type=checkbox]');
		$checkbox_total_shares = $('.dpsp-setting-field-show-total-share-count input[type=checkbox]');
		$checkbox_count_round  = $('.dpsp-setting-field-share-count-round input[type=checkbox]');
		$text_minimum_count    = $('.dpsp-setting-field-minimum-share-count input[type=text]');

		$wrapper_checkbox_count_round = $checkbox_count_round.closest('.dpsp-setting-field-wrapper');
		$wrapper_text_minimum_count	  = $text_minimum_count.closest('.dpsp-setting-field-wrapper');

		$checkbox_after_scrolling = $('.dpsp-setting-field-show-after-user-scrolls input[type=checkbox]');
		$checkbox_scroll_distance_wrapper = $checkbox_after_scrolling.closest('.dpsp-setting-field-wrapper').next('.dpsp-setting-field-wrapper');
		$checkbox_scroll_distance = $checkbox_scroll_distance_wrapper.find('input');


		// Disable and enable total share count position
		if( !$checkbox_total_shares.is(':checked') ) {
			$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().addClass('disabled');
			$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().find('select').attr( 'disabled', true );
		}

		$checkbox_total_shares.change( function() {
			if( !$checkbox_total_shares.is(':checked') ) {
				$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().addClass('disabled');
				$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().find('select').attr( 'disabled', true );
			} else {
				$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().removeClass('disabled');
				$checkbox_total_shares.closest('.dpsp-setting-field-wrapper').next().find('select').attr( 'disabled', false );
			}
		});


		// Disable and enable share count round
		// Disable and enable minimum share count
		enable_disable_count_round();
		enable_disable_minimum_count();

		$checkbox_shares.change( function() {
			enable_disable_count_round();
			enable_disable_minimum_count();
		});

		$checkbox_total_shares.change( function() {
			enable_disable_count_round();
			enable_disable_minimum_count()
		});

		function enable_disable_count_round() {
			if( !$checkbox_total_shares.is(':checked') && !$checkbox_shares.is(':checked') ) {
				$wrapper_checkbox_count_round.addClass('disabled');
				$checkbox_count_round.attr( 'disabled', true );
			} else {
				$wrapper_checkbox_count_round.removeClass('disabled');
				$checkbox_count_round.attr( 'disabled', false );
			}
		}

		function enable_disable_minimum_count() {
			if( !$checkbox_total_shares.is(':checked') && !$checkbox_shares.is(':checked') ) {
				$wrapper_text_minimum_count.addClass('disabled');
				$text_minimum_count.attr( 'disabled', true );
			} else {
				$wrapper_text_minimum_count.removeClass('disabled');
				$text_minimum_count.attr( 'disabled', false );
			}
		}


		// Disable and enable total share count position
		if( !$checkbox_after_scrolling.is(':checked') ) {
			$checkbox_scroll_distance_wrapper.addClass('disabled');
			$checkbox_scroll_distance.attr( 'disabled', true );
		}

		$checkbox_after_scrolling.change( function() {
			if( !$checkbox_after_scrolling.is(':checked') ) {
				$checkbox_scroll_distance_wrapper.addClass('disabled');
				$checkbox_scroll_distance.attr( 'disabled', true );
			} else {
				$checkbox_scroll_distance_wrapper.removeClass('disabled');
				$checkbox_scroll_distance.attr( 'disabled', false );
			}
		});

	});


	/*****************************************************/
	/* Tab Navigation
	/*****************************************************/
	$('.dpsp-nav-tab').on( 'click', function(e) {
		e.preventDefault();

		// Change http referrer
		$_wp_http_referer = $('input[name=_wp_http_referer]');

		var _wp_http_referer = $_wp_http_referer.val();
		_wp_http_referer = remove_query_arg( 'dpsp-tab', _wp_http_referer );
		$_wp_http_referer.val( add_query_arg( 'dpsp-tab', $(this).attr('data-tab'), _wp_http_referer ) );

		// Nav Tab activation
		$('.dpsp-nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		// Show tab
		$('.dpsp-tab').removeClass('dpsp-tab-active');

		var nav_tab = $(this).attr('data-tab');
		$('#dpsp-tab-' + nav_tab).addClass('dpsp-tab-active');
	});


	/**********************************************************/
	/* Refresh statistics in "Share Statistics" meta-box
	/**********************************************************/
	$(document).on( 'click', '#dpsp-refresh-share-counts', function(e) {

		e.preventDefault();

		if( $(this).hasClass('disabled') )
			return false;

		$refresh_button = $(this);
		$spinner 		= $refresh_button.siblings('.spinner');

		$refresh_button.addClass( 'disabled' );
		$spinner.css( 'visibility', 'visible' );

		$('.dpsp-statistic-bar-wrapper-network').css( 'opacity', 0.6 );

		var data = {
			action 	: 'dpsp_refresh_share_counts',
			nonce  	: $refresh_button.siblings('[name="dpsp_refresh_share_counts"]').val(),
			post_id	: parseInt( $('#post_ID').val() )
		}

		$.post( ajaxurl, data, function( response ) {

			if( response ) {
				$('.dpsp-statistic-bars-wrapper').replaceWith( response );
			}

		});

	});


	/**********************************************************/
	/* Display the link shortening services settings subsection
	/* And change the service name in the purge button
	/**********************************************************/
	$(document).ready( function() {

		$('[name="dpsp_settings[shortening_service]"]').change( function() {
			$('.dpsp-subsection-link-shortening').hide();
			$('.dpsp-subsection-link-shortening[data-link-shortening-service=' + $(this).val() + ']').show();

			// Change service name in the purge button
			$('#dpsp-purge-shortened-links span').text( $(this).find('option:selected').text() );
		});

		$('[name="dpsp_settings[shortening_service]"]').trigger( 'change' );

	});

	/**********************************************************/
	/* Handle Purge shortened links action
	/**********************************************************/
	$(document).on( 'click', '#dpsp-purge-shortened-links', function(e) {

		e.preventDefault();

		if( ! dpsp_confirm_shorten_link_purge )
			return false;

		$button = $(this);
		$form   = $button.closest('form');

		// Set data to be passed
		var data = {
			action 			   : 'dpsp_purge_shortened_links',
			nonce			   : $('#_wpnonce').val(),
			shortening_service : $('[name="dpsp_settings[shortening_service]"]').val()
		}

		// Add Loading
		$form.find(':input').attr( 'disabled', true );
		$button.closest('.dpsp-setting-field-button').addClass('dpsp-loading');

		$.post( ajaxurl, data, function( response ) {

			response = JSON.parse( response );

			if( typeof response.success != 'undefined' ) {

				var url 	= window.location;
				var updated = 'dpsp_purge_shortened_links_fail';

				if( response.success == 1 )
					updated = 'dpsp_purge_shortened_links_success';

				url = add_query_arg( 'updated', updated, window.location.href );

				window.location = url;

			}

		});

	});


	/**********************************************************/
	/* Share Options Meta-box
	/**********************************************************/
	document.addEventListener( 'dpsp_settings_field_image_select_image', function(e) {

		if( e.detail.field.parents('#dpsp_share_options_content').length == 0 )
			return false;

		// Set the height of the image for a 200 width base display
		e.detail.field.find('img').height( parseInt( 200 * e.detail.attachment.height / e.detail.attachment.width ) );

		// Set wrapper section minimum height depending on the selected image
		e.detail.field.closest('.dpsp-section').css( 'min-height', e.detail.field.height() );
	});

	document.addEventListener( 'dpsp_settings_field_image_remove_image', function(e) {

		if( e.detail.field.parents('#dpsp_share_options_content').length == 0 )
			return false;

		if( e.detail.field.find('label[for="dpsp_share_options[custom_image]"]').length > 0 )
			e.detail.field.find('img').height( 105 );
		else
			e.detail.field.find('img').height( 300 );

		e.detail.field.closest('.dpsp-section').css( 'min-height', e.detail.field.height() );

	});

	$(window).load( function() {

		$('#dpsp_share_options_content .dpsp-section').each( function() {

			$(this).css( 'min-height', $(this).find('.dpsp-setting-field-image').height() );

		});

	});

	// Textarea fields with maximum counts
	$('.dpsp-setting-field-wrapper textarea').on( 'input', function() {

		var $textarea 		 = $(this);
		var $remaining_chars = $textarea.closest('.dpsp-setting-field-wrapper').find('.dpsp-textarea-characters-remaining');

		var max_chars = parseInt( $remaining_chars.parent().data('maximum-count') );
		var remaining_chars_count = parseInt( max_chars - $textarea.val().length );

		$remaining_chars.text( remaining_chars_count );

		if( remaining_chars_count < 0 )
			$remaining_chars.parent().addClass('dpsp-excedeed');
		else
			$remaining_chars.parent().removeClass('dpsp-excedeed');

	});

	// Twitter textarea
	$('.dpsp-setting-field-wrapper textarea[name="dpsp_share_options[custom_tweet]"]').on( 'input', function() {

		var $textarea 		 = $(this);
		var $remaining_chars = $textarea.closest('.dpsp-setting-field-wrapper').find('.dpsp-textarea-characters-remaining');

		var max_chars = parseInt( $remaining_chars.parent().data('maximum-count') );
		
		urls = $textarea.val().match( /(http(s?):\/\/[\S]*)/g );

		// Twitter considers a URL as being 23 characters long
		urls_char_counts = ( urls ? urls.length : 0 ) * 23;

		var remaining_chars_count = parseInt( max_chars - $textarea.val().replace( /(http(s?):\/\/[\S]*)/g, '' ).length - urls_char_counts );

		$remaining_chars.text( remaining_chars_count );

		if( remaining_chars_count < 0 )
			$remaining_chars.parent().addClass('dpsp-excedeed');
		else
			$remaining_chars.parent().removeClass('dpsp-excedeed');

	});


	/**********************************************************/
	/* Click to Tweet from Settings Page
	/**********************************************************/
	$(document).on( 'change', 'select[name="dpsp_settings[ctt_style]"]', function() {

		$('#section-click-to-tweet-preview > a').removeClass('dpsp-click-to-tweet').attr( 'class', function( i, c ) { return c.replace(/(^|\s)dpsp-style-\S+/g, ''); });
		$('#section-click-to-tweet-preview > a').addClass( 'dpsp-click-to-tweet' ).addClass( 'dpsp-style-' + $(this).val() );

	});

	$(document).on( 'keyup', 'input[name="dpsp_settings[ctt_link_text]"]', function() {

		$('#section-click-to-tweet-preview > a .dpsp-click-to-tweet-cta > span').html( $(this).val() );

	});

	$(document).on( 'change', 'select[name="dpsp_settings[ctt_link_position]"]', function() {

		$('#section-click-to-tweet-preview > a').removeClass( 'dpsp-click-to-tweet-cta-left dpsp-click-to-tweet-cta-right' ).addClass( 'dpsp-click-to-tweet-cta-' + $(this).val() );

	});

	$(document).on( 'click', 'input[name="dpsp_settings[ctt_link_icon_animation]"]', function() {

		if( $(this).is(':checked') )
			$('#section-click-to-tweet-preview > a').addClass( 'dpsp-click-to-tweet-cta-icon-animation' );
		else
			$('#section-click-to-tweet-preview > a').removeClass( 'dpsp-click-to-tweet-cta-icon-animation' );

	});

	$('select[name="dpsp_settings[ctt_style]"]').trigger( 'change' );
	$('input[name="dpsp_settings[ctt_link_text]"]').trigger( 'keyup' );
	$('select[name="dpsp_settings[ctt_link_position]"]').trigger( 'change' );
	$('select[name="dpsp_settings[ctt_link_icon_animation]"]').trigger( 'click' );

	$('#section-click-to-tweet-preview').show();

});