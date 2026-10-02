<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package dazzling
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open() ) : ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php endif; ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dazzling' ); ?></a>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header">
	<nav class="navbar navbar-default" aria-label="<?php esc_attr_e( 'Primary', 'dazzling' ); ?>">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">
				<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'dazzling' ); ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>

				<div id="logo">

					<?php echo is_home() ? '<h1 class="site-title">' : '<span class="site-title">'; ?>

						<?php if ( get_header_image() ) : ?>

							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/></a>

						<?php else : ?>

							<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>

						<?php endif; ?>

					<?php echo is_home() ? '</h1>' : '</span>'; ?><!-- end of .site-name -->

				</div><!-- end of #logo -->

				<?php
				if ( ! get_header_image() ) :
					$dazzling_description = get_bloginfo( 'description', 'display' );
					if ( $dazzling_description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo esc_html( $dazzling_description ); ?></p>
						<?php
					endif;
				endif;
				?>
			</div>
				<?php dazzling_header_menu(); ?>
		</div>
	</nav><!-- .site-navigation -->
	</header><!-- #masthead -->

		<div class="top-section">
		<?php dazzling_featured_slider(); ?>
		<?php dazzling_call_for_action(); ?>
		</div>
		<div id="content" class="site-content container">

			<div class="container main-content-area">
			<?php $dazzling_layout_class = dazzling_get_layout_class(); ?>
				<div class="row <?php echo esc_attr( $dazzling_layout_class ); ?>">
