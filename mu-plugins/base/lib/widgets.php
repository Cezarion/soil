<?php
/**
 * Custom widgets
 *
 * @link http://codex.wordpress.org/Widgets_API
 */

/* Activate shortcode for widgets */
add_filter('widget_text', 'do_shortcode');

class Block_Widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_block', 'description' => __( 'Arbitrary text or HTML wrapped with a custom class and/or id' ) );
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('block', __('Block'), $widget_ops, $control_ops);
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract($args);
		$title 	= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text 	= apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$class 	= apply_filters( 'widget_class', empty( $instance['class'] ) ? '' : $instance['class'], $instance );
		$id 	= apply_filters( 'widget_id', empty( $instance['id'] ) ? '' : $instance['id'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div <?php if ( !empty($id ) ) echo 'id="'.$id.'"'; ?> <?php if ( !empty($id ) ) echo 'class="'.$class.'"'; ?>>
				<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
			</div>
		<?php
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['class'] = strip_tags($new_instance['class']);
		$instance['id'] = strip_tags($new_instance['id']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}


 	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' , 'class' => '' , 'id' => '' ) );
		$title = strip_tags($instance['title']);
		$class = strip_tags($instance['class']);
		$id = strip_tags($instance['id']);
		$text = esc_textarea($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id('id'); ?>" style="width:80px; display: inline-block"><?php _e('Id:'); ?></label>
			<input id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('class'); ?>" style="width:80px; display: inline-block"><?php _e('Class css:'); ?></label>
			<input id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo esc_attr($class); ?>" />
		</p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
<?php
	}
}

// register Foo_Widget widget
function register_block_widget() {
    register_widget( 'Block_Widget' );
}
add_action( 'widgets_init', 'register_block_widget' );