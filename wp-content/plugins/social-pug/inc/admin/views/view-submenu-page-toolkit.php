<?php dpsp_admin_header(); ?>

<div class="dpsp-page-wrapper dpsp-page-toolkit wrap">

	<?php wp_nonce_field( 'dpsptkn', 'dpsptkn' ); ?>

	<!-- Share Tools -->
	<h1 class="dpsp-page-title"><?php echo __( 'Social Share Tools', 'social-pug' ); ?></h1>

	<div class="dpsp-row dpsp-m-padding">
	<?php 
		$tools = dpsp_get_tools('share_tool'); 

		foreach( $tools as $tool_slug => $tool )
			dpsp_output_tool_box( $tool_slug, $tool );
	?>
	</div><!-- End of Share Tools -->

	<?php do_action( 'dpsp_page_toolkit_after_share_tools' ); ?>

	<!-- Follow Tools -->
	<h1 class="dpsp-page-title"><?php echo __( 'Social Follow Tools', 'social-pug' ); ?></h1>

	<div class="dpsp-row dpsp-m-padding">
	<?php
		$tools = dpsp_get_tools('follow_tool'); 

		foreach( $tools as $tool_slug => $tool )
			dpsp_output_tool_box( $tool_slug, $tool );
	?>
	</div><!-- End of Follow Tools -->

	<?php do_action( 'dpsp_page_toolkit_after_follow_tools' ); ?>

	<!-- Misc Tools -->
	<h1 class="dpsp-page-title"><?php echo __( 'Misc Tools', 'social-pug' ); ?></h1>

	<div class="dpsp-row dpsp-m-padding">
	<?php
		$tools = dpsp_get_tools('misc_tool'); 

		foreach( $tools as $tool_slug => $tool )
			dpsp_output_tool_box( $tool_slug, $tool );
	?>
	</div><!-- End of Misc Tools -->

	<?php do_action( 'dpsp_page_toolkit_after_misc_tools' ); ?>

</div>