<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- the file has been loaded by this path since 1.x.
/**
 * Dazzling Social widget: the Social Menu's links as icons.
 *
 * @package dazzling
 */

// phpcs:ignore PEAR.NamingConventions.ValidClassName.Invalid, PEAR.NamingConventions.ValidClassName.StartWithCapital -- the class name is how child themes and plugins unregister the widget; renaming it would break them.
class dazzling_social_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'dazzling-social',
			'description'                 => esc_html__( 'Dazzling Social Widget', 'dazzling' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'dazzling-social', esc_html__( 'Dazzling Social Widget', 'dazzling' ), $widget_ops );
	}

	/**
	 * Print the widget.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow us', 'dazzling' );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- before/after markup supplied by register_sidebar().
		echo $args['before_widget'];
		if ( '' !== $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}
		// phpcs:enable
		?>

		<!-- social icons -->
		<div class="social-icons sticky-sidebar-social">

			<?php dazzling_social_icons(); ?>

		</div><!-- end social icons -->
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup supplied by register_sidebar().
	}

	/**
	 * Sanitize the widget settings on save.
	 *
	 * Without this, WP_Widget::update() stores $new_instance verbatim.
	 *
	 * @param array $new_instance Submitted settings.
	 * @param array $old_instance Previous settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

	/**
	 * The settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = esc_html__( 'Follow us', 'dazzling' );
		}
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title ', 'dazzling' ); ?></label>

		<input type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
							id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
							class="widefat" />
		</p>
		<?php
	}
}
