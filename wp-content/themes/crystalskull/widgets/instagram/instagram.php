<?php
	/*
class sw_instagram extends WP_Widget {
	function sw_instagram() {
		$widget_ops = array( 'classname' => 'widget_instagram', 'description' => esc_html__('Displays Instagram photos', 'crystalskull') );
		parent::__construct( 'instagram', esc_html__('SW Instagram', 'crystalskull'), $widget_ops );


	}

	function widget( $args, $instance ) {

		$allowed_tags = array(
			'div' => array(
				'class' => array()
			),
			'h3' => array(
				'class' => array()
			),
		);

		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Photos on Instagram' : $instance['title'], $instance, $this->id_base );
		$user = $instance['user'];
		$num = (int)$instance['num'];
		if ( $num < 1 ) {
			$num = 1;
		}
		if ( !empty( $user ) ) {
			echo  wp_kses($before_widget, $allowed_tags);
			if($title) {
				echo  wp_kses($before_title.$title.$after_title, $allowed_tags);
			} else {
				?> <div class="widget clearfix"> <?php
			}

		$id = mt_rand(99, 999);
?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#ins_feed_<?php echo  esc_js($id); ?>").photostream_widget({
				  	user: "<?php echo esc_js($user); ?>",
				  	limit:<?php echo  esc_js($num); ?>,
					social_network: "instagram"
				});
			});
		</script>
		<div id="ins_feed_<?php echo esc_attr($id); ?>" class="ins_widget"></div>
		<?php
			echo  wp_kses($after_widget, $allowed_tags);
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['user'] = $new_instance['user'];
		$instance['num'] = (int) $new_instance['num'];

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$user = isset( $instance['user'] ) ? esc_attr( $instance['user'] ) : '';
		$num = isset( $instance['num'] ) ? absint( $instance['num'] ) : 5;

?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title :', 'crystalskull'); ?></label>
		<input class="widefat" id="<?php echo  esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo  esc_attr($title); ?>" /></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'user' )); ?>"><?php esc_html_e('Username :', 'crystalskull'); ?></label>
		<input class="widefat" id="<?php echo  esc_attr($this->get_field_id( 'user' )); ?>" name="<?php echo  esc_attr($this->get_field_name( 'user' )); ?>" type="text" value="<?php echo  esc_attr($user); ?>" /></p>

		<p><label for="<?php echo  esc_attr($this->get_field_id( 'num' )); ?>"><?php esc_html_e('Number of Photos :', 'crystalskull'); ?></label>
		<input size="6" id="<?php echo  esc_attr($this->get_field_id( 'num' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'num' )); ?>" type="text" value="<?php echo  esc_attr($num); ?>" /></p>


<?php
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("sw_instagram");'));
/***************************************************/