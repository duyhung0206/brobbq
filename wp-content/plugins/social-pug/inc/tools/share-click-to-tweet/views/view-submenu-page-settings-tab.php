<!-- Click to Tweet -->
<div id="dpsp-section-click-to-tweet" class="dpsp-section">

	<!-- Preview -->
	<label><?php echo __( 'Preview', 'social-pug' ); ?></label>
	<div id="section-click-to-tweet-preview">
		<?php echo do_shortcode('[socialpug_tweet tweet="The only #WordPress social sharing plugin you will ever need. https://www.devpups.com/social-pug/" display_tweet="The only #WordPress social sharing plugin you will ever need." remove_url="yes" remove_username="yes"]'); ?>
	</div>

	<br /><br />

	<!-- Settings Fields -->
	<?php dpsp_settings_field( 'select', 'dpsp_settings[ctt_style]', ( isset( $settings['ctt_style'] ) ? $settings['ctt_style'] : '' ), __( 'Tweet Box Theme', 'social-pug' ), array( '1' => __( 'Simple', 'social-pug' ), '2' => __( 'Simple with a twist', 'social-pug' ), '3' => __( 'Simple border', 'social-pug' ), '4' => __( 'Double border', 'social-pug' ), '5' => __( 'Full background', 'social-pug' ) ) ); ?>
	<?php dpsp_settings_field( 'text', 'dpsp_settings[ctt_link_text]', ( isset( $settings['ctt_link_text']) ? $settings['ctt_link_text'] : '' ), __( 'Call to Action Text', 'social-pug' ) ); ?>
	<?php dpsp_settings_field( 'select', 'dpsp_settings[ctt_link_position]', ( isset( $settings['ctt_link_position'] ) ? $settings['ctt_link_position'] : '' ), __( 'Call to Action Position', 'social-pug' ), array( 'left' => __( 'Left', 'social-pug' ), 'right' => __( 'Right', 'social-pug' ) ) ); ?>
	<?php dpsp_settings_field( 'checkbox', 'dpsp_settings[ctt_link_icon_animation]', ( isset( $settings['ctt_link_icon_animation']) ? $settings['ctt_link_icon_animation'] : '' ), __( 'Show Icon Animation', 'social-pug' ), array('yes'), __( 'Will animate the call to action icon when the user hovers over the Click to Tweet box.', 'social-pug' ) ); ?>

</div>