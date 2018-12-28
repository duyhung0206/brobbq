jQuery( function($) {

	tinymce.PluginManager.add( 'dpsp_click_to_tweet', function( editor, url ) {

		editor.addButton( 'dpsp_click_to_tweet', {
			icon 	: 'dpsp-icon-twitter',
			tooltip : 'Social Pug "Click to Tweet"',
			onclick	: function() {

				editor.windowManager.open({
					id		 : 'dpsp_click_to_tweet_dialog',
					title 	 : 'Add Click to Tweet Shortcode',
					minWidth : 750,
					buttons	 : [
						{
							text 		: 'Add Shortcode',
							classes 	: 'primary abs-layout-item',
							minWidth 	: 130,
							onclick		: 'submit'
						},
						{
							text 		: 'Cancel',
							onclick		: 'close'
						}
					],
					body  	 : [
						{
							type 		: 'textbox',
							id			: 'dpsp_tweet',
							name		: 'dpsp_tweet',
							label		: 'The Tweet that will be shared on Twitter',
							multiline	: true,
							minWidth	: 400,
							minHeight	: 100
						},
						{
							type 		: 'textbox',
							id			: 'dpsp_display_tweet',
							name		: 'dpsp_display_tweet',
							label		: 'The Tweet that will be displayed in your article',
							multiline	: true,
							minWidth	: 400,
							minHeight	: 100
						},
						{
							type		: 'checkbox',
							id			: 'dpsp_tweet_hide_url',
							name		: 'dpsp_tweet_hide_url',
							label		: 'Hide the URL',
							text 		: 'The URL of the post will not be added to the tweet'
						},
						{
							type		: 'checkbox',
							id			: 'dpsp_tweet_hide_via',
							name		: 'dpsp_tweet_hide_via',
							label		: 'Hide "via"',
							text 		: 'The Twitter username saved in the Settings page will not be added to the tweet.'
						},
						{
							type		: 'listbox',
							id			: 'dpsp_tweet_style',
							name		: 'dpsp_tweet_style',
							label		: 'Tweet Box Style',
							values		: [
								{
									text  : 'Default',
									value : '0'
								},
								{
									text  : 'Simple',
									value : '1'
								},
								{
									text  : 'Simple with a twist',
									value : '2'
								},
								{
									text  : 'Simple border',
									value : '3'
								},
								{
									text  : 'Double border',
									value : '4'
								},
								{
									text  : 'Full background',
									value : '5'
								}
							]
						}
					],
					onsubmit 	: function(e) {

						var shortcode = '';

						if( e.data.dpsp_tweet ) {

							// Open shortcode
							shortcode = '[socialpug_tweet';

							// Add the tweet
							shortcode += ' tweet="' + e.data.dpsp_tweet + '"';

							// Add display tweet
							shortcode += ' display_tweet="' + e.data.dpsp_display_tweet + '"';

							// Add style if it was set
							if( e.data.dpsp_tweet_style != 0 )
								shortcode += ' style="' + e.data.dpsp_tweet_style + '"';

							// Add the remove url
							if( e.data.dpsp_tweet_hide_url )
								shortcode += ' remove_url="yes"';

							// Add the remove via
							if( e.data.dpsp_tweet_hide_via )
								shortcode += ' remove_username="yes"';

							// Close shortcode
							shortcode += ']';

						}

						if( shortcode ) {
							editor.insertContent( shortcode );
						}

					}
				});

				$dpsp_tweet 		   = $('#dpsp_tweet');
				$dpsp_tweet_wrapper	   = $dpsp_tweet.closest('.mce-formitem');
				$dpsp_tweet_wrapper.height( $dpsp_tweet_wrapper.height() + 25 );
				$sample_permalink 	   = $('#sample-permalink');

				// Add a tweet char counter
				var initial_char_count = 280;
				var via_length 		   = 20;
				var url_length		   = $sample_permalink.text().length;

				
				$dpsp_tweet.after('<p id="dpsp_tweet_length"><em>Characters remaining: <span>' + get_char_count() + '</span></em></p>');

				$dpsp_tweet_wrapper.siblings('.mce-formitem').each( function() {
					$(this).css( 'top', parseInt( $(this).css('top'), 10) + 25 );
				});

				$('#dpsp_click_to_tweet_dialog-body').height( $('#dpsp_click_to_tweet_dialog-body').height() + 25 );

				// Calculate the remaining characters for the tweet
				$dpsp_tweet.keyup( function() {
					$('#dpsp_tweet_length span').html( get_char_count() );
				});

				$('#dpsp_tweet_hide_via').click( function() {
					if( $(this).attr('aria-checked') == "true" )
						via_length = 20;
					else
						via_length = 0;

					$dpsp_tweet.trigger('keyup');
				});

				$('#dpsp_tweet_hide_url').click( function() {
					if( $(this).attr('aria-checked') == "true" )
						url_length = $sample_permalink.text().length;
					else
						url_length = 0;

					$dpsp_tweet.trigger('keyup');
				});


				function get_char_count() {
					return initial_char_count - via_length - url_length - $dpsp_tweet.val().length;
				}

			}
		});
	});

});