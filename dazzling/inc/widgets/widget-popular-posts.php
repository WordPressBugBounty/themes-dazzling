<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- the file has been loaded by this path since 1.x.
/**
 * Dazzling Popular Posts widget: popular posts, recent posts and recent
 * comments in three Bootstrap tabs.
 *
 * Popularity is the post_views_count meta that dazzling_track_post_views()
 * keeps.
 *
 * @package dazzling
 */

// phpcs:ignore PEAR.NamingConventions.ValidClassName.Invalid, PEAR.NamingConventions.ValidClassName.StartWithCapital -- the class name is how child themes and plugins unregister the widget; renaming it would break them.
class dazzling_popular_posts_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'dazzling_tabbed_widget',
			'description'                 => __( 'Displays tabbed list of popular posts, recent posts & comments', 'dazzling' ),
			'customize_selective_refresh' => true,
		);

		$control_ops = array(
			'width'   => 250,
			'height'  => 350,
			'id_base' => 'dazzling_tabbed_widget',
		);

		parent::__construct( 'dazzling_tabbed_widget', __( 'Dazzling Popular Posts Widget', 'dazzling' ), $widget_ops, $control_ops );
	}

	/**
	 * Print one post in a tab: thumbnail, title and date.
	 */
	protected function post_item() {
		?>
		<li>
			<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="tab-thumb thumbnail" rel="bookmark" tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'tab-small', array( 'alt' => '' ) ); ?>
			</a>
			<?php endif; ?>
			<div class="content">
				<a class="tab-entry" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				<i><?php the_time( 'M j, Y' ); ?></i>
			</div>
		</li>
		<?php
	}

	/**
	 * How to display the widget on the screen.
	 *
	 * The sidebar's before_widget/after_widget used to be skipped, so the
	 * widget lost the id and classes every other widget gets; and the tab
	 * panes had fixed ids (#popular-posts, #recent, #messages), so a second
	 * copy of the widget repeated them and its tabs switched the first copy's
	 * panes. The ids now derive from the widget's own id.
	 *
	 * @param array $args     Sidebar arguments.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
		$prefix = $this->id ? $this->id : 'dazzling-tabbed-' . wp_rand();
		$panes  = array(
			'popular'  => $prefix . '-popular',
			'recent'   => $prefix . '-recent',
			'comments' => $prefix . '-comments',
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup supplied by register_sidebar().
		?>
		<div class="tabbed">
			<div class="tabs-wrapper">
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#<?php echo esc_attr( $panes['popular'] ); ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_attr( $panes['popular'] ); ?>"><?php esc_html_e( 'Popular', 'dazzling' ); ?></a></li>
					<li role="presentation"><a href="#<?php echo esc_attr( $panes['recent'] ); ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_attr( $panes['recent'] ); ?>"><?php esc_html_e( 'Recent', 'dazzling' ); ?></a></li>
					<li role="presentation"><a href="#<?php echo esc_attr( $panes['comments'] ); ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_attr( $panes['comments'] ); ?>"><i class="fa fa-comments tab-comment" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Comments', 'dazzling' ); ?></span></a></li>
				</ul>

				<div class="tab-content">
					<ul id="<?php echo esc_attr( $panes['popular'] ); ?>" class="tab-pane tab-popular active">
						<?php
						$dazzling_popular = new WP_Query(
							array(
								'posts_per_page'      => $number,
								'ignore_sticky_posts' => true,
								'no_found_rows'       => true,
								'post_status'         => 'publish',
								'order'               => 'DESC',
								'meta_key'            => 'post_views_count', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- the popularity order is the point of this tab.
								'orderby'             => 'meta_value_num',
							)
						);
						while ( $dazzling_popular->have_posts() ) :
							$dazzling_popular->the_post();
							$this->post_item();
						endwhile;
						wp_reset_postdata();
						?>
					</ul>

					<ul id="<?php echo esc_attr( $panes['recent'] ); ?>" class="tab-pane tab-recent">
						<?php
						$dazzling_recent = new WP_Query(
							array(
								'posts_per_page'      => $number,
								'ignore_sticky_posts' => true,
								'no_found_rows'       => true,
								'post_status'         => 'publish',
							)
						);
						while ( $dazzling_recent->have_posts() ) :
							$dazzling_recent->the_post();
							$this->post_item();
						endwhile;
						wp_reset_postdata();
						?>
					</ul>

					<ul id="<?php echo esc_attr( $panes['comments'] ); ?>" class="tab-pane tab-comments">
						<?php
						/*
						 * Comments on password-protected posts are hidden until the
						 * post is unlocked, so they are left out here too; a few
						 * extra are fetched to make up for them.
						 */
						$dazzling_comments = get_comments(
							array(
								'number'      => $number + 10,
								'status'      => 'approve',
								'post_status' => 'publish',
								'type'        => 'comment',
							)
						);
						$dazzling_comments = array_slice(
							array_filter(
								$dazzling_comments,
								function ( $comment ) {
									return ! post_password_required( $comment->comment_post_ID );
								}
							),
							0,
							$number
						);

						foreach ( $dazzling_comments as $comment ) :
							$dazzling_author = get_comment_author( $comment );
							?>
						<li>
							<div class="content">
								<?php echo esc_html( $dazzling_author ? $dazzling_author : __( 'Anonymous', 'dazzling' ) ); ?> <?php esc_html_e( 'on', 'dazzling' ); ?>
								<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>"><?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?></a>
								<p><?php echo esc_html( wp_html_excerpt( $comment->comment_content, 60, '(...)' ) ); ?></p>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup supplied by register_sidebar().
	}

	/**
	 * Update the widget settings.
	 *
	 * @param array $new_instance Submitted settings.
	 * @param array $old_instance Previous settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* The number of posts is an integer; guard the key and coerce it. */
		$instance['number'] = isset( $new_instance['number'] ) ? max( 1, absint( $new_instance['number'] ) ) : 3;

		return $instance;
	}

	/**
	 * The settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'number' => 3 ) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show', 'dazzling' ); ?>:</label>
			<input class="tiny-text" type="number" min="1" step="1" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
		</p>
		<?php
	}
}
