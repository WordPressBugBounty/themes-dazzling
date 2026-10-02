<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package dazzling
 */

get_header(); ?>
		<section id="primary" class="content-area col-sm-12 col-md-8">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
					<?php
					/* translators: %s: search query */
					printf( esc_html__( 'Search Results for: %s', 'dazzling' ), '<span>' . esc_html( get_search_query( false ) ) . '</span>' );
					?>
					</h1>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<?php get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php dazzling_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</section><!-- #primary -->

<?php
if ( dazzling_show_sidebar() ) {
	get_sidebar();
}
?>
<?php get_footer(); ?>
