<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package dazzling
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function dazzling_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'dazzling_page_menu_args' );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function dazzling_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'dazzling_body_classes' );


/**
 * Mark Posts/Pages as Untitled when no title is used, so a post without one
 * still has a link to click in listings.
 *
 * Front end only, and translatable. It used to apply everywhere the_title
 * runs, so the admin's post lists said "Untitled" (in English, whatever the
 * site language) where WordPress says "(no title)".
 *
 * @param string $title Post title.
 * @return string
 */
function dazzling_title( $title ) {
	if ( '' === trim( (string) $title ) && ! is_admin() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return __( 'Untitled', 'dazzling' );
	}
	return $title;
}
add_filter( 'the_title', 'dazzling_title' );



if ( ! function_exists( 'dazzling_password_form' ) ) :
	/**
	 * Password protected post form using Bootstrap classes.
	 *
	 * @param string       $output           Core's form, replaced.
	 * @param WP_Post|null $post             Post being unlocked (WordPress 5.8+).
	 * @param string       $invalid_password Error for a wrong password (WordPress 6.8+).
	 * @return string
	 */
	function dazzling_password_form( $output = '', $post = null, $invalid_password = '' ) {
		$post  = get_post( $post );
		$label = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
		$error = '';
		$aria  = '';

		if ( '' !== $invalid_password ) {
			$error = '<div class="post-password-form-invalid-password" role="alert"><p id="error-' . esc_attr( $label ) . '">' . esc_html( $invalid_password ) . '</p></div>';
			$aria  = ' aria-describedby="error-' . esc_attr( $label ) . '"';
		}

		// Core sends the visitor back here after a wrong password; without it
		// they landed on the referring page instead.
		$redirect = empty( $post->ID ) ? '' : '<input type="hidden" name="redirect_to" value="' . esc_attr( get_permalink( $post->ID ) ) . '" />';

		return '<form class="protected-post-form post-password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">' . $redirect . $error . '
  <div class="row">
    <div class="col-lg-10">
        <p>' . esc_html__( 'This post is password protected. To view it please enter your password below:', 'dazzling' ) . '</p>
        <label for="' . esc_attr( $label ) . '">' . esc_html__( 'Password:', 'dazzling' ) . ' </label>
      <div class="input-group">
        <input class="form-control" name="post_password" id="' . esc_attr( $label ) . '" type="password" spellcheck="false" required' . $aria . '>
        <span class="input-group-btn"><button type="submit" class="btn btn-default" name="Submit">' . esc_html__( 'Submit', 'dazzling' ) . '</button>
        </span>
      </div>
    </div>
  </div>
</form>';
	}
endif;
add_filter( 'the_password_form', 'dazzling_password_form', 10, 3 );


/**
 * Add Bootstrap classes for table
 */
add_filter( 'the_content', 'dazzling_add_custom_table_class' );
function dazzling_add_custom_table_class( $content ) {
	return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'dazzling_get_layout_class' ) ) :
	/**
	 * The layout of the current view: side-pull-left (sidebar on the right),
	 * side-pull-right (sidebar on the left), no-sidebar or full-width.
	 *
	 * A single post or page uses its own "Select layout" choice when it has
	 * one, anything else the Customizer default. header.php used to read the
	 * meta of the global $post, which on the blog, archives and search is the
	 * first post of the list -- so one post's layout choice changed the layout
	 * of every listing it appeared first in.
	 *
	 * @return string One of the keys of dazzling_get_layouts(), or '' for the default.
	 */
	function dazzling_get_layout_class() {
		$layout = '';

		if ( is_singular() ) {
			$layout = get_post_meta( get_queried_object_id(), 'site_layout', true );
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			// The shop archive is a page; honour that page's layout choice.
			$layout = get_post_meta( wc_get_page_id( 'shop' ), 'site_layout', true );
		}

		if ( ! $layout ) {
			$layout = of_get_option( 'site_layout' );
		}

		$layout = array_key_exists( (string) $layout, dazzling_get_layouts() ) ? $layout : '';

		/**
		 * Filters the layout of the current view.
		 *
		 * @param string $layout Layout key, or '' for the default.
		 */
		return apply_filters( 'dazzling_layout_class', $layout );
	}
endif;

if ( ! function_exists( 'dazzling_show_sidebar' ) ) :
	/**
	 * Whether the current view shows the sidebar.
	 *
	 * The No Sidebar and Full Width layouts used to render the whole sidebar
	 * and hide it with CSS: every widget's queries ran for nothing, and its
	 * landmarks and links stayed in the page for assistive technology.
	 *
	 * @return bool
	 */
	function dazzling_show_sidebar() {
		return ! in_array( dazzling_get_layout_class(), array( 'no-sidebar', 'full-width' ), true );
	}
endif;

if ( ! function_exists( 'dazzling_social_icons' ) ) :
	/**
	 * Display social links in footer and widgets
	 */
	function dazzling_social_icons() {
		static $instance = 0;

		if ( has_nav_menu( 'social-menu' ) ) {
			/*
			 * The menu can be printed twice on a page (the Social widget and the
			 * footer), and used to repeat id="social" and id="menu-social-items"
			 * each time. The first keeps those ids; later copies are numbered.
			 * Styles target .social-icon as well as #social.
			 */
			++$instance;
			$suffix = 1 === $instance ? '' : '-' . $instance;

			wp_nav_menu(
				array(
					'theme_location'       => 'social-menu',
					'container'            => 'nav',
					'container_id'         => 'social' . $suffix,
					'container_class'      => 'social-icon',
					'container_aria_label' => __( 'Social links', 'dazzling' ),
					'menu_id'              => 'menu-social-items' . $suffix,
					'menu_class'           => 'social-menu',
					'depth'                => 1,
					'fallback_cb'          => '',
					'link_before'          => '<i class="social_icon fa"><span>',
					'link_after'           => '</span></i>',
				)
			);
		}
	}
endif;


if ( ! function_exists( 'dazzling_social' ) ) :
	/**
	 * Fallback function for the deprecated function dazzling_social
	 */
	function dazzling_social() {
		if ( of_get_option( 'footer_social' ) ) {
			dazzling_social_icons();
		}
	}
endif;

if ( ! function_exists( 'dazzling_header_menu' ) ) :
	/**
	 * header menu (should you choose to use one)
	 */
	function dazzling_header_menu() {
		// display the WordPress Custom Menu if available
		wp_nav_menu(
			array(
				'menu'            => 'primary',
				'theme_location'  => 'primary',
				'depth'           => 2,
				'container'       => 'div',
				'container_class' => 'collapse navbar-collapse navbar-ex1-collapse',
				'container_id'    => 'navbar',
				'menu_class'      => 'nav navbar-nav',
				'fallback_cb'     => 'Dazzling_Bootstrap_Navwalker::fallback',
				'walker'          => new Dazzling_Bootstrap_Navwalker(),
			)
		);
	} /* end header menu */
endif;

/**
 * footer menu (should you choose to use one)
 */
function dazzling_footer_links() {
	// display the WordPress Custom Menu if available
	wp_nav_menu(
		array(
			'container'       => '',                              // remove nav container
			'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
			'menu'            => __( 'Footer Links', 'dazzling' ),   // nav name
			'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
			'theme_location'  => 'footer-links',             // where it's located in the theme
			'before'          => '',                                 // before the menu
			'after'           => '',                                  // after the menu
			'link_before'     => '',                            // before each link
			'link_after'      => '',                             // after each link
			'depth'           => 0,                                   // limit the depth of the nav
			'fallback_cb'     => 'dazzling_footer_links_fallback',  // fallback function
		)
	);
} /* end dazzling footer link */


/**
 * Get Post Views - for Popular Posts widget.
 *
 * Reading no longer writes: an unviewed post used to get a 0 row added to
 * the database the first time this was called.
 *
 * @param int $postID Post ID.
 * @return string
 */
function dazzling_getPostViews( $postID ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid, WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- public name since 1.x, called from child themes.
	$count = absint( get_post_meta( $postID, 'post_views_count', true ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	/* translators: %s: number of views. */
	return sprintf( _n( '%s View', '%s Views', $count, 'dazzling' ), number_format_i18n( $count ) );
}

/**
 * Count a view of a post, for the Popular Posts widget.
 *
 * Counts each post at most once per request, so a child theme that still
 * calls this from its own content-single.php does not double the count now
 * that dazzling_track_post_views() calls it too.
 *
 * @param int $postID Post ID.
 */
function dazzling_setPostViews( $postID ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid, WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- public name since 1.x, called from child themes.
	static $counted = array();

	$post_id = absint( $postID ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	if ( ! $post_id || isset( $counted[ $post_id ] ) ) {
		return;
	}
	$counted[ $post_id ] = true;

	update_post_meta( $post_id, 'post_views_count', absint( get_post_meta( $post_id, 'post_views_count', true ) ) + 1 );
}

/**
 * Count real views of single posts.
 *
 * This used to be a call inside content-single.php, so it also counted
 * post previews, every Customizer preview refresh and prefetches, and a
 * child theme overriding the template silently stopped counting.
 */
function dazzling_track_post_views() {
	if ( ! is_singular( 'post' ) || is_preview() || is_customize_preview() || is_feed() || is_robots() || is_trackback() ) {
		return;
	}

	// Speculative prefetches and prerenders are not views.
	$purpose = isset( $_SERVER['HTTP_SEC_PURPOSE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_SEC_PURPOSE'] ) ) : '';
	if ( '' !== $purpose && false !== strpos( $purpose, 'prefetch' ) ) {
		return;
	}

	dazzling_setPostViews( get_queried_object_id() );
}
add_action( 'template_redirect', 'dazzling_track_post_views' );


if ( ! function_exists( 'dazzling_call_for_action' ) ) :
	/**
	 * Call for action button & text area
	 */
	function dazzling_call_for_action() {
		if ( is_front_page() && '' !== (string) of_get_option( 'w2f_cfa_text', '' ) ) {
			echo '<div class="cfa">';
			echo '<div class="container">';
			echo '<div class="col-md-8">';
			echo '<span class="cfa-text">' . esc_html( of_get_option( 'w2f_cfa_text' ) ) . '</span>';
			echo '</div>';
			echo '<div class="col-md-4">';
			// No button without a title: it printed an empty link.
			if ( '' !== (string) of_get_option( 'w2f_cfa_button', '' ) ) {
				echo '<a class="btn btn-lg cfa-button" href="' . esc_url( of_get_option( 'w2f_cfa_link' ) ) . '">' . esc_html( of_get_option( 'w2f_cfa_button' ) ) . '</a>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
endif;


if ( ! function_exists( 'dazzling_featured_slider' ) ) :
	/**
	 * Featured image slider
	 */
	function dazzling_featured_slider() {
		if ( ! is_front_page() || ! dazzling_sanitize_checkbox( of_get_option( 'dazzling_slider_checkbox' ) ) ) {
			return;
		}

		$count = absint( of_get_option( 'dazzling_slide_number', 3 ) );
		if ( ! $count ) {
			$count = 3;
		}

		$args = array(
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			/*
			 * Only posts that can actually fill a slide. Without this a post with no
			 * featured image produced a slide with no image, and the slider cycled
			 * through a blank pane.
			 */
			'meta_query'          => array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		);

		/*
		 * The category is optional. It used to be required -- the slider rendered only
		 * when a category AND a count were both set -- so ticking "enable slider"
		 * without choosing one printed "Slider is not properly configured" to visitors
		 * rather than showing anything. With no category chosen, show latest posts.
		 */
		$slidecat = of_get_option( 'dazzling_slide_categories' );
		if ( $slidecat ) {
			$args['cat'] = $slidecat;
		}

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			return;
		}

		echo '<div class="flexslider">';
		echo '<ul class="slides">';

		while ( $query->have_posts() ) :
			$query->the_post();

			echo '<li>';
			/*
			 * An explicit size. the_post_thumbnail() with no argument serves the
			 * default thumbnail, which the slider then stretched to full width --
			 * the cause of the blurry slides.
			 */
			the_post_thumbnail( 'full' );

			echo '<div class="flex-caption">';
			echo '<a href="' . esc_url( get_permalink() ) . '">';
			if ( '' !== get_the_title() ) {
				echo '<h2 class="entry-title">' . esc_html( get_the_title() ) . '</h2>';
			}
			if ( '' !== get_the_excerpt() ) {
				echo '<div class="excerpt">' . esc_html( get_the_excerpt() ) . '</div>';
			}
			echo '</a>';
			echo '</div>';
			echo '</li>';

		endwhile;

		echo '</ul>';
		echo '</div>';

		wp_reset_postdata();
	}
endif;


if ( ! function_exists( 'dazzling_footer_info' ) ) :
	/**
	 * function to show the footer info, copyright information
	 */
	function dazzling_footer_info() {
		printf(
			/* translators: 1: link to Colorlib, 2: link to WordPress.org */
			esc_html__( 'Theme by %1$s Powered by %2$s', 'dazzling' ),
			'<a href="https://colorlib.com/wp/" target="_blank" rel="noopener">Colorlib</a>',
			'<a href="https://wordpress.org/" target="_blank" rel="noopener">WordPress</a>'
		);
	}
endif;

/**
 * Get custom CSS from Theme Options panel and output in header
 */
if ( ! function_exists( 'get_dazzling_theme_options' ) ) {

	if ( ! function_exists( 'dazzling_sanitize_css_color' ) ) {
		/**
		 * Return a value that is safe to print as a CSS colour, or ''.
		 *
		 * @param mixed $value Raw stored value.
		 * @return string Validated colour, or '' when the value is not one.
		 */
		function dazzling_sanitize_css_color( $value ) {
			if ( ! is_string( $value ) ) {
				return '';
			}

			$value = trim( $value );

			if ( '' === $value ) {
				return '';
			}

			// #rgb / #rrggbb
			if ( preg_match( '/^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i', $value ) ) {
				return $value;
			}

			// Functional notation: rgb() and rgba().
			if ( preg_match( '/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/', $value ) ) {
				return $value;
			}

			// Bare CSS colour keyword, e.g. "transparent" or "red".
			if ( preg_match( '/^[a-z]{3,20}$/i', $value ) ) {
				return $value;
			}

			return '';
		}
	}

	if ( ! function_exists( 'dazzling_css_color' ) ) {
		/**
		 * Return a theme option as a CSS colour that is safe to print, or ''.
		 *
		 * Every colour printed into the inline <style> block goes through this. Options
		 * saved before the Customizer sanitiser was tightened may still hold arbitrary
		 * text, so the value is re-validated at output time rather than trusted from
		 * storage.
		 *
		 * @param string $name Option name.
		 * @return string Validated colour, or '' when the stored value is not one.
		 */
		function dazzling_css_color( $name ) {
			return dazzling_sanitize_css_color( of_get_option( $name ) );
		}
	}

	/**
	 * Print the CSS for the colours and typography set in the Customizer.
	 *
	 * Every colour is re-validated here (dazzling_css_color()), the font
	 * values are taken from the theme's own lists, and the legacy custom CSS
	 * has its tags stripped, so nothing stored can break out of the <style>.
	 */
	function get_dazzling_theme_options() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- public name since 1.x; child themes unhook it by this name.
		$rules = array(
			'link_color'             => 'a, #infinite-handle span {color: %s;}',
			'link_hover_color'       => 'a:hover, a:focus {color: %s;}',
			'link_active_color'      => 'a:active {color: %s;}',
			'element_color'          => '.btn-default, .label-default, .flex-caption h2, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus, .dropdown-menu > .active > a, .navbar-default .navbar-nav .open .dropdown-menu > .active > a {background-color: %1$s; border-color: %1$s;} .btn.btn-default.read-more, .entry-meta .fa, .site-main [class*="navigation"] a, .more-link { color: %1$s;}',
			'element_color_hover'    => '.btn-default:hover, .btn-default:focus, .label-default[href]:hover, .label-default[href]:focus, #infinite-handle span:hover, #infinite-handle span:focus-within, .btn.btn-default.read-more:hover, .btn.btn-default.read-more:focus, .scroll-to-top:hover, .scroll-to-top:focus, .btn-default:active, .btn-default.active, .site-main [class*="navigation"] a:hover, .site-main [class*="navigation"] a:focus, .more-link:hover, .more-link:focus, #image-navigation .nav-previous a:hover, #image-navigation .nav-previous a:focus, #image-navigation .nav-next a:hover, #image-navigation .nav-next a:focus { background-color: %1$s; border-color: %1$s; }',
			'cfa_bg_color'           => '.cfa { background-color: %1$s; } .cfa-button:hover {color: %1$s;}',
			'cfa_color'              => '.cfa-text { color: %s;}',
			'cfa_btn_color'          => '.cfa-button {border-color: %s;}',
			'cfa_btn_txt_color'      => '.cfa-button {color: %s;}',
			'heading_color'          => 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .entry-title {color: %s;}',
			'top_nav_bg_color'       => '.navbar.navbar-default {background-color: %s;}',
			'top_nav_link_color'     => '.navbar-default .navbar-nav > li > a { color: %s;}',
			'top_nav_dropdown_bg'    => '.dropdown-menu, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {background-color: %s;}',
			'top_nav_dropdown_item'  => '.navbar-default .navbar-nav .open .dropdown-menu > li > a { color: %s;}',
			'footer_bg_color'        => '#colophon {background-color: %s;}',
			'footer_text_color'      => '#footer-area, .site-info {color: %s;}',
			'footer_widget_bg_color' => '#footer-area {background-color: %s;}',
			'footer_link_color'      => '.site-info a, #footer-area a {color: %s;}',
			'social_color'           => ':is(#social, .social-icon) a {color: %s !important;}',
			'social_hover_color'     => ':is(#social, .social-icon) a:hover, :is(#social, .social-icon) a:focus {color: %s !important;}',
		);

		$css = '';
		foreach ( $rules as $option => $rule ) {
			$color = dazzling_css_color( $option );
			if ( $color ) {
				$css .= sprintf( $rule, $color );
			}
		}

		/*
		 * The Customizer saves only the typography fields that were changed,
		 * so a stored value can lack some keys: reading $typography['face']
		 * from such a value warned "Undefined array key" on every page.
		 */
		$typography_options  = dazzling_get_typography_options();
		$typography_defaults = dazzling_get_typography_defaults();
		$typography          = of_get_option( 'main_body_typography', array() );
		$typography          = array_merge( $typography_defaults, is_array( $typography ) ? $typography : array() );

		$font_family = isset( $typography_options['faces'][ $typography['face'] ] ) ? $typography_options['faces'][ $typography['face'] ] : $typography_options['faces'][ $typography_defaults['face'] ];
		$font_size   = isset( $typography_options['sizes'][ $typography['size'] ] ) ? $typography['size'] : $typography_defaults['size'];
		$font_style  = isset( $typography_options['styles'][ $typography['style'] ] ) ? $typography['style'] : $typography_defaults['style'];
		$font_color  = dazzling_sanitize_css_color( $typography['color'] );
		$css        .= '.entry-content {font-family: ' . $font_family . '; font-size: ' . $font_size . '; font-weight: ' . $font_style . ';' . ( $font_color ? ' color: ' . $font_color . ';' : '' ) . '}';

		/*
		 * The retired Other > Custom CSS option, until dazzling_migrate_custom_css()
		 * has moved it into Additional CSS. html_entity_decode() used to run here,
		 * which turned an escaped "</style><script>" back into live markup.
		 */
		$custom_css = of_get_option( 'custom_css' );
		if ( $custom_css ) {
			$css .= wp_strip_all_tags( $custom_css );
		}

		echo '<style id="dazzling-theme-options">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- validated colours and the theme's own font lists, see above.
	}
}
add_action( 'wp_head', 'get_dazzling_theme_options', 10 );
