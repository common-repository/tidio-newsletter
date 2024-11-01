<?php

class TidioNewsletterWidget extends WP_Widget {

	function TidioNewsletterWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Tidio Newsletter' );
		
		add_action( 'wp_head', 'TidioNewsletterWidget::widgetInjectHead' );
	}

	public static function widgetInjectHead(){
				
		echo '<script src="'.plugin_dir_url(__FILE__).'../media/js/newsletter-widget.js"></script>';
		
		echo '<script> newsletterWidget.project_public_key = "'.get_option('tidio-newsletter-public-key').'"; </script>';
				
	}

	public function widget( $args, $instance ) {
		
		extract($args);
				
		//
		
		$title = 'Sign Up';		
		
		if(!empty($instance['title'])){
			$title = $instance['title'];			
		}
		
		echo $before_widget;
		
		echo $before_title . $title . $after_title; 
		
		require __DIR__.'/../views/newsletter-sign-up.php';
		
		echo $after_widget;
		
	}

	public function update( $new_instance, $old_instance ){
		
		$instance = array();
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['placeholder'] = ( ! empty( $new_instance['placeholder'] ) ) ? strip_tags( $new_instance['placeholder'] ) : '';

		$instance['submit'] = ( ! empty( $new_instance['submit'] ) ) ? strip_tags( $new_instance['submit'] ) : '';

		$instance['errorMessage'] = ( ! empty( $new_instance['errorMessage'] ) ) ? strip_tags( $new_instance['errorMessage'] ) : '';

		$instance['successMessage'] = ( ! empty( $new_instance['successMessage'] ) ) ? strip_tags( $new_instance['successMessage'] ) : '';

		return $instance;
	}

	public function form($instance) {
		
		extract($instance);
		
		//
		
		if(empty($title))		
			$title = 'Newsletter';
		
		if(empty($placeholder))	
			$placeholder = 'Your email address...';
		
		if(empty($submit))
			$submit = 'Sign Up';
		
		if(empty($errorMessage))
			$errorMessage = 'While adding an error occurred';
		
		if(empty($successMessage))
			$successMessage = 'Email address has been added!';
		
		?>
        <p style="margin: 0;"><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <p style="margin: 0;"><label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php echo __('Placeholder'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo attribute_escape($placeholder); ?>" /></label></p>
        <p style="margin: 0;"><label for="<?php echo $this->get_field_id('submit'); ?>"><?php echo __('Submit'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" type="text" value="<?php echo attribute_escape($submit); ?>" /></label></p>
        <p style="margin: 0;"><label for="<?php echo $this->get_field_id('errorMessage'); ?>"><?php echo __('Error Message'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('errorMessage'); ?>" name="<?php echo $this->get_field_name('errorMessage'); ?>" type="text" value="<?php echo attribute_escape($errorMessage); ?>" /></label></p>
        <p style="margin: 0;"><label for="<?php echo $this->get_field_id('successMessage'); ?>"><?php echo __('Success Message'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('successMessage'); ?>" name="<?php echo $this->get_field_name('successMessage'); ?>" type="text" value="<?php echo attribute_escape($successMessage); ?>" /></label></p>
		<?php
	}
}
