<?php
/**
 * Custom header support: the header image doubles as the site logo in the
 * navbar, and the header text colour sets the site title's colour.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package dazzling
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * The Appearance > Header screen these arguments used to style for has
 * redirected to the Customizer since WordPress 4.1, so only the front-end
 * callback remains.
 *
 * @uses dazzling_header_style()
 *
 * @package dazzling
 */
function dazzling_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'dazzling_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '1FA67A',
				'width'              => 300,
				'height'             => 66,
				'flex-height'        => true,
				'wp-head-callback'   => 'dazzling_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'dazzling_custom_header_setup' );

if ( ! function_exists( 'dazzling_header_style' ) ) :
	/**
	 * Print the site title colour, or hide the title and tagline, as set under
	 * Customize > Site Identity / Colors.
	 *
	 * Prints nothing for the default colour: style.css already sets it. In
	 * 2.1.x this early return was dropped while the default text colour was
	 * 000000, so every site that had never picked a colour got a black title
	 * instead of the theme's green, while the Customizer showed green.
	 *
	 * @see dazzling_custom_header_setup().
	 */
	function dazzling_header_style() {
		$header_text_color = get_header_textcolor();

		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		if ( 'blank' === $header_text_color ) {
			/*
			 * Hide the text only, and only visually. This used to hide the whole
			 * .site-title, which also holds the header image -- so unticking
			 * "Display Site Title and Tagline" removed the logo as well.
			 */
			$css = '.navbar-brand, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); width: 1px; height: 1px; margin: -1px; padding: 0; border: 0; overflow: hidden; }';
		} else {
			$hex = sanitize_hex_color_no_hash( $header_text_color );
			if ( ! $hex ) {
				return;
			}
			$css = '.navbar > .container .navbar-brand { color: #' . $hex . '; }';
		}

		echo '<style id="dazzling-header-text">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed CSS around a validated hex colour.
	}
endif; // dazzling_header_style
