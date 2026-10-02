<?php
/**
 * WooCommerce Functions for Dazzling theme
 *
 * Loaded from functions.php only when WooCommerce is active.
 *
 * @package dazzling
 */

if ( ! function_exists( 'dazzling_woo_setup' ) ) :
	/**
	 * Declare WooCommerce support, with the product image widths the theme's
	 * columns are designed around, and the product gallery features.
	 *
	 * The widths replace the shop_catalog_image_size / shop_single_image_size
	 * options the theme used to write when it was activated: WooCommerce 3.3
	 * stopped reading those options, so the writes did nothing.
	 */
	function dazzling_woo_setup() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 350,
				'single_image_width'    => 570,
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif; // dazzling_woo_setup
add_action( 'after_setup_theme', 'dazzling_woo_setup' );

/*
 * Add basic WooCommerce template support
 */

// First let's remove original WooCommerce wrappers.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Now we can add our own, the same used for theme Pages.
add_action( 'woocommerce_before_main_content', 'dazzling_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'dazzling_wrapper_end', 10 );

if ( ! function_exists( 'dazzling_wrapper_start' ) ) :
	/**
	 * Open the content column around WooCommerce templates.
	 */
	function dazzling_wrapper_start() {
		echo '<div id="primary" class="content-area col-sm-12 col-md-8">';
		echo '<main id="main" class="site-main">';
	}
endif;

if ( ! function_exists( 'dazzling_wrapper_end' ) ) :
	/**
	 * Close the content column around WooCommerce templates.
	 */
	function dazzling_wrapper_end() {
		echo '</main></div>';
	}
endif;

/*
 * WooCommerce prints the sidebar itself (woocommerce_sidebar); skip it on the
 * No Sidebar and Full Width layouts, like the theme's own templates.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', 'dazzling_woo_sidebar', 10 );

if ( ! function_exists( 'dazzling_woo_sidebar' ) ) :
	/**
	 * The sidebar beside WooCommerce pages, when the layout has one.
	 */
	function dazzling_woo_sidebar() {
		if ( dazzling_show_sidebar() ) {
			woocommerce_get_sidebar();
		}
	}
endif;

if ( ! function_exists( 'dazzling_loop_add_to_cart_args' ) ) :
	/**
	 * Style the shop's add-to-cart buttons as Bootstrap buttons.
	 *
	 * This used to str_replace() every "button" in the link's HTML, which also
	 * turned WooCommerce's add_to_cart_button class into add_to_cart_btn and
	 * role="button" into role="btn btn-default". WooCommerce binds its AJAX
	 * add-to-cart to that class, so every click reloaded the page instead. The
	 * class attribute alone is changed now: WooCommerce's own button class is
	 * swapped for Bootstrap's, as before, and every other class is kept.
	 *
	 * @param array $args Arguments for the loop's add-to-cart link.
	 * @return array
	 */
	function dazzling_loop_add_to_cart_args( $args ) {
		$classes       = isset( $args['class'] ) ? explode( ' ', $args['class'] ) : array();
		$classes       = array_diff( $classes, array( 'button' ) ); // WooCommerce's grey .button styles would override Bootstrap's.
		$classes[]     = 'btn';
		$classes[]     = 'btn-default';
		$args['class'] = implode( ' ', array_filter( $classes ) );
		return $args;
	}
endif;
add_filter( 'woocommerce_loop_add_to_cart_args', 'dazzling_loop_add_to_cart_args' );

if ( ! function_exists( 'dazzling_woo_cart_link' ) ) :
	/**
	 * The menu bar's cart link: item count and total, linking to the cart, or
	 * to the shop while the cart is empty.
	 *
	 * @return string Link markup, or '' when there is no cart (admin, REST).
	 */
	function dazzling_woo_cart_link() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return '';
		}

		$count = WC()->cart->get_cart_contents_count();

		if ( 0 === $count ) {
			$url   = wc_get_page_permalink( 'shop' );
			$title = __( 'Start shopping', 'dazzling' );
		} else {
			$url   = wc_get_cart_url();
			$title = __( 'View your shopping cart', 'dazzling' );
		}

		return sprintf(
			'<a class="woo-menu-cart" href="%1$s" title="%2$s"><i class="fa fa-shopping-cart" aria-hidden="true"></i> %3$s - %4$s</a>',
			esc_url( $url ),
			esc_attr( $title ),
			/* translators: %d: number of items in the cart. */
			esc_html( sprintf( _n( '%d item', '%d items', $count, 'dazzling' ), $count ) ),
			wp_kses_post( WC()->cart->get_cart_total() )
		);
	}
endif;

if ( ! function_exists( 'dazzling_woomenucart' ) ) :
	/**
	 * Place a cart icon with number of items and total cost in the menu bar.
	 *
	 * @param string   $menu The menu's list items.
	 * @param stdClass $args wp_nav_menu() arguments.
	 * @return string
	 */
	function dazzling_woomenucart( $menu, $args ) {
		if ( 'primary' !== $args->theme_location ) {
			return $menu;
		}

		$link = dazzling_woo_cart_link();

		return $link ? $menu . '<li class="menu-item menu-item-cart">' . $link . '</li>' : $menu;
	}
endif;
add_filter( 'wp_nav_menu_items', 'dazzling_woomenucart', 10, 2 );

if ( ! function_exists( 'dazzling_woo_cart_fragment' ) ) :
	/**
	 * Refresh the menu bar's cart link when a product is added over AJAX.
	 * Without it the menu kept saying "0 items" until the next page load.
	 *
	 * @param array $fragments Selector => replacement markup.
	 * @return array
	 */
	function dazzling_woo_cart_fragment( $fragments ) {
		$link = dazzling_woo_cart_link();
		if ( $link ) {
			$fragments['a.woo-menu-cart'] = $link;
		}
		return $fragments;
	}
endif;
add_filter( 'woocommerce_add_to_cart_fragments', 'dazzling_woo_cart_fragment' );
