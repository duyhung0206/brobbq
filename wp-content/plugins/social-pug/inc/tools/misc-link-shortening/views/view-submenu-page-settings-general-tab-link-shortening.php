<!-- Link shortening -->
<div class="dpsp-section">

	<h3 class="dpsp-section-title">
		<?php _e( 'Link shortening', 'social-pug' ); ?>

		<div class="dpsp-switch">
			<input id="dpsp-link-shortening-active" name="dpsp_settings[shortening_active]" class="cmn-toggle cmn-toggle-round" type="checkbox" value="1" <?php echo ( isset( $settings['shortening_active'] ) ? 'checked' : '' ) ?> />
			<label for="dpsp-link-shortening-active"></label>
		</div>
	</h3>

	<?php do_action( 'dpsp_settings_section_link_shortening_before', $settings ); ?>

	<?php

		$services = dpsp_get_available_link_shortening_services();

		if( ! empty( $services ) ) {

			// Link shortening service selector
			if( count( $services ) == 1 ) {

				echo '<h4>' . $services[ key( $services ) ] . '</h4>';
				echo '<input type="hidden" name="dpsp_settings[shortening_service]" value="' . key( $services ) . '" />';

			} else {

				dpsp_settings_field( 'select', 'dpsp_settings[shortening_service]', ( isset( $settings['shortening_service'] ) ? $settings['shortening_service'] : '' ), __( 'Link Shortening Service', 'social-pug' ), $services );

			}

			// Add subsections for each link shortening service
			foreach( $services as $service_slug => $service_name ) {

				echo '<div class="dpsp-subsection dpsp-subsection-link-shortening" data-link-shortening-service="' . esc_attr( $service_slug ) . '">';

					/**
					 * Hook to add settings fields for the different link shortening services
					 *
					 * @param array $settings
					 *
					 */
					do_action( 'dpsp_settings_subsection_link_shortening_' . $service_slug, $settings );

				echo '</div>';
					
			}

		}
		
	?>

	<!-- Purge all shortened links -->
	<div class="dpsp-setting-field-wrapper dpsp-setting-field-button dpsp-has-field-label">

		<?php
			$service_name = ( ! empty( $settings['shortening_service'] ) && ! empty( $services[ $settings['shortening_service'] ] ) ? esc_attr( $services[ $settings['shortening_service'] ] ) : '' );
		?>

		<label class="dpsp-setting-field-label"><?php echo __( 'Purge Shortened Links', 'social-pug' ); ?></label>

		<div class="spinner"><!-- --></div>

		<button id="dpsp-purge-shortened-links" class="button" onclick="dpsp_confirm_shorten_link_purge = confirm( '<?php echo __( 'Are you sure you want to purge all shortened links?', 'social-pug' ); ?>' );"><?php echo sprintf( __( 'Purge %s Links', 'social-pug' ), '<span>' . $service_name . '</span>' ); ?></button>

		<?php dpsp_output_backend_tooltip( __( 'This will remove all shortened links that were previously created with the selected Link Shortening Service and saved for your posts.', 'social-pug' ) ); ?>

	</div>

	<?php do_action( 'dpsp_settings_section_link_shortening_after', $settings ); ?>

</div>