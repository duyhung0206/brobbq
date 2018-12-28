<?php

class DPSP_Social_Media_Follow_Buttons extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'dpsp_social_media_follow',
			__( 'Social Pug: Social Media Follow Buttons', 'social-pug' ),
			array( 'description' => __( 'Display social media follow buttons.', 'social-pug' ), )
		);
		
	}


	/*
	 * Outputs the content of the widget in the front-end
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 */
	public function widget( $args, $instance ) {

		$settings = dpsp_get_location_settings( 'follow_widget' );

		if( !empty( $settings['active'] ) && !empty( $settings['networks'] ) ) {
			echo ( isset( $args['before_widget'] ) ? $args['before_widget'] : '' );

			echo ( isset( $args['before_title'] ) ? $args['before_title'] : '' );
				echo ( isset( $instance['title'] ) ? $instance['title'] : '' );
			echo ( isset( $args['after_title'] ) ? $args['after_title'] : '' );

			echo ( !empty( $instance['description'] ) ? $instance['description'] : '' );

			echo do_shortcode( '[socialpug_follow]' );

			echo ( isset( $args['after_widget'] ) ? $args['after_widget'] : '' );
		}

	}


	/*
	 * Outputs the options form in the back-end
	 *
	 * @param array $instance The widget options
	 *
	 */
	public function form( $instance ) {

		// Set saved values
		$title 		 = ( !empty( $instance['title'] ) ? $instance['title'] : '' );
		$description = ( !empty( $instance['description'] ) ? $instance['description'] : '' );
		
		// Widget title
		echo '<p>';
			echo '<label class="dpsp-widget-section-title" for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title:', 'social-pug' ) . '</label>';
			echo '<input type="text" class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $title . '" />';
		echo '</p>';

		// Widget description
		echo '<p>';
			echo '<label class="dpsp-widget-section-title" for="' . $this->get_field_id( 'description' ) . '">' . __( 'Description:', 'social-pug' ) . '</label>';
			echo '<textarea class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '">' . $description . '</textarea>';
		echo '</p>';

		// Widget buttons settings
		echo '<p>';
			echo '<label class="dpsp-widget-section-title">' . __( 'Buttons Settings:', 'social-pug' ) . '</label>';
			echo '<span>' . __( 'You can edit the look and feel of the buttons by pressing the button below:', 'social-pug' ) . '</span><br /><br />';
			echo '<a class="button button-primary" href="' . admin_url( 'admin.php?page=dpsp-follow-widget' ) . '">' . __( 'Buttons Settings', 'social-pug' ) . '</a>';
		echo '</p>';

	}


	/*
	 * Processing widget options on save
	 *
	 * @param array $new_instance - The new options
	 * @param array $old_instance - The previous options
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] 		 = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? $new_instance['description'] : '';

		return $instance;

	}

}

register_widget("DPSP_Social_Media_Follow_Buttons");