<?php dpsp_admin_header(); ?>

<div class="dpsp-page-wrapper dpsp-page-settings wrap">

	<h1><?php echo __( 'Settings', 'social-pug' ); ?></h1>

	<form method="post" action="options.php">
		
		<?php
		 	$dpsp_settings = get_option( 'dpsp_settings', 'not_set' );
			settings_fields( 'dpsp_settings' );
			settings_errors( 'dpsp_settings' );

			$active_tab = ( !empty( $_GET['dpsp-tab'] ) ? $_GET['dpsp-tab'] : 'general-settings' );
		?>

		<!-- Navigation Tabs -->
		<h2 class="nav-tab-wrapper">
			<?php foreach( $tabs as $tab_slug => $tab_name ): ?>
				<a href="#" data-tab="<?php echo esc_attr( $tab_slug ); ?>" class="dpsp-nav-tab nav-tab <?php echo ( $active_tab == $tab_slug ? 'nav-tab-active' : '' ); ?>"><?php echo esc_attr( $tab_name ); ?></a>
			<?php endforeach; ?>
		</h2>

		<!-- General Settings Tab Content -->
		<div id="dpsp-tab-general-settings" class="dpsp-tab <?php echo ( $active_tab == 'general-settings' ? 'dpsp-tab-active' : '' ); ?>">
		
			<!-- Tab Top Do Action -->
			<?php do_action( 'dpsp_settings_page_tab_general_settings_top', $dpsp_settings ); ?>

			<?php 

				/**
				 * This hook is temporary. It does what the dynamic hook at the bottom of
				 * the page does, just for the general-settings tab
				 *
				 */
				do_action( 'dpsp_submenu_page_settings_tab_general-settings', $dpsp_settings );

			?>

			<!-- Google UTM Tracking -->
			<div class="dpsp-section">
				<h3 class="dpsp-section-title">
					<?php _e( 'Google Analytics UTM Tracking', 'social-pug' ); ?>

					<div class="dpsp-switch">
						<input id="dpsp-utm-tracking-active" name="dpsp_settings[utm_tracking]" class="cmn-toggle cmn-toggle-round" type="checkbox" value="1" <?php echo ( isset( $dpsp_settings['utm_tracking'] ) ? 'checked' : '' ) ?> />
						<label for="dpsp-utm-tracking-active"></label>
					</div>
				</h3>

				<?php dpsp_settings_field( 'text', 'dpsp_settings[utm_source]', ( isset($dpsp_settings['utm_source']) ? $dpsp_settings['utm_source'] : '' ), __( 'Campaign Source', 'social-pug' ), '', __( 'Use utm_source to identify a search engine, newsletter name, or other source. Using "{{network_name}}" as value here will add the name of the social network button as the value of the utm_source parameter.', 'social-pug' ) ); ?>
				<?php dpsp_settings_field( 'text', 'dpsp_settings[utm_medium]', ( isset($dpsp_settings['utm_medium']) ? $dpsp_settings['utm_medium'] : '' ), __( 'Campaign Medium', 'social-pug' ), '', __( 'Use utm_medium to identify a medium such as email or social.', 'social-pug' ) ); ?>
				<?php dpsp_settings_field( 'text', 'dpsp_settings[utm_campaign]', ( isset($dpsp_settings['utm_campaign']) ? $dpsp_settings['utm_campaign'] : '' ), __( 'Campaign Name', 'social-pug' ), '', __( 'Use utm_campaign to identify a specific product promotion or strategic campaign.', 'social-pug' ) ); ?>
			</div>

			<!-- Misc -->
			<div class="dpsp-section">
				<h3 class="dpsp-section-title"><?php _e( 'Misc', 'social-pug' ); ?></h3>

				<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[disable_meta_tags]', ( isset($dpsp_settings['disable_meta_tags']) ? $dpsp_settings['disable_meta_tags'] : '' ), __( 'Disable Open Graph Meta Tags', 'social-pug' ), array('yes') ); ?>
				<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[twitter_share_counts]', ( isset($dpsp_settings['twitter_share_counts']) ? $dpsp_settings['twitter_share_counts'] : '' ), __( 'Enable Twitter Tweet Counts', 'social-pug' ), array('yes'), sprintf( __( 'We have partenered with %1$sOpenShareCount%2$s to bring back Twitter Share Counts. You will need to register your website on %1$stheir website%2$s in order for Social Pug to be able to return the share counts.', 'social-pug' ), '<a href="http://opensharecount.com/" target="_blank">', '</a>' ) ); ?>
				<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[whatsapp_display_only_mobile]', ( isset($dpsp_settings['whatsapp_display_only_mobile']) ? $dpsp_settings['whatsapp_display_only_mobile'] : '' ), __( 'WhatsApp only on mobile devices', 'social-pug' ), array('yes'), __( 'The WhatsApp share button will be displayed on all devices by default. By checking this option the WhatsApp button will only be shown on mobile devices.', 'social-pug' ) ); ?>
				<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[http_and_https_share_counts]', ( isset($dpsp_settings['http_and_https_share_counts']) ? $dpsp_settings['http_and_https_share_counts'] : '' ), __( 'Grab HTTP and HTTPS share count', 'social-pug' ), array('yes'), __( 'Check this option if your website was moved from HTTP to HTTPS and you wish to count the shares for both versions of the URLs.', 'social-pug' ) ); ?>
				<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[debugger_enabled]', ( isset($dpsp_settings['debugger_enabled']) ? $dpsp_settings['debugger_enabled'] : '' ), __( 'Enable System Debugger', 'social-pug' ), array('yes') ); ?>
			</div>

			<!-- Tab Bottom Do Action -->
			<?php do_action( 'dpsp_settings_page_tab_general_settings_bottom', $dpsp_settings ); ?>

			<!-- Register Version -->
			<div class="dpsp-section">
				<h3 class="dpsp-section-title"><?php _e( 'Register Version', 'social-pug' ); ?></h3>

				<?php dpsp_settings_field( 'text', 'dpsp_settings[product_serial]', ( isset($dpsp_settings['product_serial']) ? $dpsp_settings['product_serial'] : '' ), __( 'Serial Key', 'social-pug' ) ); ?>
			</div>

		</div><!-- End of General Settings Tab Content -->


		<!-- Social Identity Tab Content -->
		<div id="dpsp-tab-social-identity" class="dpsp-tab <?php echo ( $active_tab == 'social-identity' ? 'dpsp-tab-active' : '' ); ?>">

			<!-- Tab Top Do Action -->
			<?php do_action( 'dpsp_settings_page_tab_social_identity_top', $dpsp_settings ); ?>

			<?php dpsp_settings_field( 'text', 'dpsp_settings[facebook_username]', ( isset($dpsp_settings['facebook_username']) ? $dpsp_settings['facebook_username'] : '' ), __( 'Facebook Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[facebook_app_id]', ( isset($dpsp_settings['facebook_app_id']) ? $dpsp_settings['facebook_app_id'] : '' ), __( 'Facebook App ID', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[facebook_app_secret]', ( isset($dpsp_settings['facebook_app_secret']) ? $dpsp_settings['facebook_app_secret'] : '' ), __( 'Facebook App Secret', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[twitter_username]', ( isset($dpsp_settings['twitter_username']) ? $dpsp_settings['twitter_username'] : '' ), __( 'Twitter Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[tweets_have_username]', ( isset( $dpsp_settings['tweets_have_username']) ? $dpsp_settings['tweets_have_username'] : '' ), __( 'Add Twitter Username to all tweets', 'social-pug' ), array('yes') ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[google-plus_username]', ( isset($dpsp_settings['google-plus_username']) ? $dpsp_settings['google-plus_username'] : '' ), __( 'Google+ Username / ID', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[pinterest_username]', ( isset($dpsp_settings['pinterest_username']) ? $dpsp_settings['pinterest_username'] : '' ), __( 'Pinterest Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[linkedin_username]', ( isset($dpsp_settings['linkedin_username']) ? $dpsp_settings['linkedin_username'] : '' ), __( 'LinkedIn Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[reddit_username]', ( isset($dpsp_settings['reddit_username']) ? $dpsp_settings['reddit_username'] : '' ), __( 'Reddit Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[vkontakte_username]', ( isset($dpsp_settings['vkontakte_username']) ? $dpsp_settings['vkontakte_username'] : '' ), __( 'VK Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[instagram_username]', ( isset($dpsp_settings['instagram_username']) ? $dpsp_settings['instagram_username'] : '' ), __( 'Instagram Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[youtube_username]', ( isset($dpsp_settings['youtube_username']) ? $dpsp_settings['youtube_username'] : '' ), __( 'YouTube Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[vimeo_username]', ( isset($dpsp_settings['vimeo_username']) ? $dpsp_settings['vimeo_username'] : '' ), __( 'Vimeo Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[soundcloud_username]', ( isset($dpsp_settings['soundcloud_username']) ? $dpsp_settings['soundcloud_username'] : '' ), __( 'SoundCloud Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[twitch_username]', ( isset($dpsp_settings['twitch_username']) ? $dpsp_settings['twitch_username'] : '' ), __( 'Twitch Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[yummly_username]', ( isset($dpsp_settings['yummly_username']) ? $dpsp_settings['yummly_username'] : '' ), __( 'Yummly Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[behance_username]', ( isset($dpsp_settings['behance_username']) ? $dpsp_settings['behance_username'] : '' ), __( 'Behance Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[xing_username]', ( isset($dpsp_settings['xing_username']) ? $dpsp_settings['xing_username'] : '' ), __( 'Xing Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[github_username]', ( isset($dpsp_settings['github_username']) ? $dpsp_settings['github_username'] : '' ), __( 'Github Username', 'social-pug' ), '' ); ?>
			<?php dpsp_settings_field( 'text', 'dpsp_settings[telegram_username]', ( isset($dpsp_settings['telegram_username']) ? $dpsp_settings['telegram_username'] : '' ), __( 'Telegram Username/Channel', 'social-pug' ), '' ); ?>

			<!-- Tab Bottom Do Action -->
			<?php do_action( 'dpsp_settings_page_tab_social_identity_bottom', $dpsp_settings ); ?>

		</div>

		<?php 
			foreach( $tabs as $tab_slug => $tab_title ) {

				if( $tab_slug == 'general-settings' || $tab_slug == 'social-identity' )
					continue;

				echo '<div id="dpsp-tab-' . esc_attr( $tab_slug ) . '" class="dpsp-tab ' . ( $active_tab == $tab_slug ? 'dpsp-tab-active' : '' ) . '">';

				/**
				 * Action to dynamically add content for each tab
				 *
				 */
				do_action( 'dpsp_submenu_page_settings_tab_' . $tab_slug, $dpsp_settings );

				echo '</div>';

			}
		?>

		<input type="hidden" name="active_tab" value="<?php echo $active_tab; ?>" />
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="dpsp_settings[always_update]" value="<?php echo ( isset( $dpsp_settings['always_update'] ) && $dpsp_settings['always_update'] == 1 ? 0 : 1 ); ?>" />
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
	</form>
</div>