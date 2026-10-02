<?php
/**
 * @package dazzling
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header page-header">

		<?php the_post_thumbnail( 'dazzling-featured', array( 'class' => 'thumbnail' ) ); ?>

		<h1 class="entry-title "><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php dazzling_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . __( 'Pages:', 'dazzling' ),
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '%',
					'echo'        => 1,
				)
			);
			?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$dazzling_category_list = get_the_category_list( __( ', ', 'dazzling' ) );

		/* translators: used between list items, there is a space after the comma */
		$dazzling_tag_list = get_the_tag_list( '', __( ', ', 'dazzling' ) );

		// Categories only when the blog has more than one; tags when the post has any.
		if ( $dazzling_category_list && dazzling_categorized_blog() ) {
			echo '<i class="fa-regular fa-folder-open" aria-hidden="true"></i> ' . wp_kses_post( $dazzling_category_list ) . ( $dazzling_tag_list ? ' ' : '. ' );
		}
		if ( $dazzling_tag_list && ! is_wp_error( $dazzling_tag_list ) ) {
			// The tags used to be shown with the folder icon on a blog with one category.
			echo '<i class="fa fa-tags" aria-hidden="true"></i> ' . wp_kses_post( $dazzling_tag_list ) . '. ';
		}
		echo '<i class="fa fa-link" aria-hidden="true"></i> <a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html__( 'permalink', 'dazzling' ) . '</a>.';
		?>

		<?php edit_post_link( esc_html__( 'Edit', 'dazzling' ), '<i class="fa-regular fa-pen-to-square" aria-hidden="true"></i><span class="edit-link">', '</span>' ); ?>
		<hr class="section-divider">
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
