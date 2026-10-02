<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package dazzling
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				echo wp_kses_post(
					sprintf(
						/* translators: 1: number of comments, 2: post title */
						_nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'dazzling' ), // phpcs:ignore WordPress.WP.I18n.MissingSingularPlaceholder -- unchanged string, so existing translations keep working.
						number_format_i18n( get_comments_number() ),
						'<span>' . esc_html( get_the_title() ) . '</span>'
					)
				);
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" aria-labelledby="comment-nav-above-title">
			<h2 class="screen-reader-text" id="comment-nav-above-title"><?php esc_html_e( 'Comment navigation', 'dazzling' ); ?></h2>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'dazzling' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'dazzling' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use dazzling_comment() to format the comments.
				 * If you want to override this in a child theme, then you can
				 * define dazzling_comment() and that will be used instead.
				 * See dazzling_comment() in inc/template-tags.php for more.
				 */
				wp_list_comments(
					array(
						'callback'    => 'dazzling_comment',
						'avatar_size' => 60,
					)
				);
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" aria-labelledby="comment-nav-below-title">
			<h2 class="screen-reader-text" id="comment-nav-below-title"><?php esc_html_e( 'Comment navigation', 'dazzling' ); ?></h2>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'dazzling' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'dazzling' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // End of have_comments(). ?>

	<?php
		// Comments are closed but some exist: say so.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'dazzling' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->