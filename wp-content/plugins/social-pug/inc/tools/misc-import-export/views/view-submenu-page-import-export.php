<?php dpsp_admin_header(); ?>

<form enctype="multipart/form-data" action="" method="post">
	<div class="dpsp-page-wrapper dpsp-page-import-export wrap">

		<h1><?php echo __( 'Import and Export', 'social-pug' ); ?></h1>

		<?php wp_nonce_field( 'dpsp_import_export' ); ?>

		<div class="dpsp-section">
			<h3><?php echo __( 'Import', 'social-pug' ); ?></h3>
			<hr />

			<p><?php _e( 'Select a settings file exported by this plugin. Importing the file will result in overwriting all settings saved by the plugin.', 'social-pug' ); ?></p>

			<input type="file" name="dpsp_import_file" />

			<?php submit_button( __( 'Import', 'social-pug' ), 'primary', 'dpsp_import_settings' ); ?>
		</div>

		<div class="dpsp-section">
			<h3><?php echo __( 'Export', 'social-pug' ); ?></h3>
			<hr />

			<p><?php _e( 'After clicking the button below a file with all settings from the plugin will be saved on your computer. The file will be having the ".json" extension.', 'social-pug' ); ?></p>

			<?php submit_button( __( 'Export', 'social-pug' ), 'primary', 'dpsp_export_settings' ); ?>
		</div>

	</div>
</form>