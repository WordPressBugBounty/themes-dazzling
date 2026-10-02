<?php
/**
 * The template for displaying image attachments.
 *
 * @package dazzling
 */

get_header();
?>
		<div id="primary" class="content-area image-attachment col-sm-12 col-md-8">
			<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>

						<div class="entry-meta">
							<?php dazzling_posted_on(); ?>
						</div><!-- .entry-meta -->

						<nav role="navigation" id="image-navigation" class="navigation-image nav-links">
							<div class="nav-previous"><?php previous_image_link( false, __( '<i class="fa fa-chevron-left"></i> Previous', 'dazzling' ) ); ?></div>
							<div class="nav-next"><?php next_image_link( false, __( 'Next <i class="fa fa-chevron-right"></i>', 'dazzling' ) ); ?></div>
						</nav><!-- #image-navigation -->
					</header><!-- .entry-header -->

					<div class="entry-content">

						<div class="entry-attachment">
							<div class="attachment">
								<?php
								/*
								 * The image links to the next image of the same gallery (the
								 * first after the last), or to the file itself. An unattached
								 * image used to link to the next of every unattached image on
								 * the site, which was a query of the whole media library.
								 */
								$dazzling_next_url = wp_get_attachment_url();
								if ( $post->post_parent ) {
									$dazzling_ids = wp_list_pluck(
										get_children(
											array(
												'post_parent' => $post->post_parent,
												'post_status' => 'inherit',
												'post_type' => 'attachment',
												'post_mime_type' => 'image',
												'order'   => 'ASC',
												'orderby' => 'menu_order ID',
											)
										),
										'ID'
									);
									$dazzling_ids = array_values( array_map( 'intval', $dazzling_ids ) );
									if ( count( $dazzling_ids ) > 1 ) {
										$dazzling_index    = array_search( (int) $post->ID, $dazzling_ids, true );
										$dazzling_next_id  = ( false !== $dazzling_index && isset( $dazzling_ids[ $dazzling_index + 1 ] ) ) ? $dazzling_ids[ $dazzling_index + 1 ] : $dazzling_ids[0];
										$dazzling_next_url = get_attachment_link( $dazzling_next_id );
									}
								}
								?>

								<a href="<?php echo esc_url( $dazzling_next_url ); ?>" rel="attachment">
								<?php
									$dazzling_attachment_size = apply_filters( 'dazzling_attachment_size', array( 1200, 1200 ) ); // Filterable image size.
									echo wp_get_attachment_image( $post->ID, $dazzling_attachment_size );
								?>
								</a>
							</div><!-- .attachment -->

							<?php if ( ! empty( $post->post_excerpt ) ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div><!-- .entry-caption -->
							<?php endif; ?>
						</div><!-- .entry-attachment -->

						<?php the_content(); ?>
						<?php
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . __( 'Pages:', 'dazzling' ),
									'after'  => '</div>',
								)
							);
						?>

					</div><!-- .entry-content -->

					<footer class="entry-meta">
					</footer><!-- .entry-meta -->
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php
					// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>

			<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->

<?php
if ( dazzling_show_sidebar() ) {
	get_sidebar();
}
?>
<?php get_footer(); ?>