<?php
/**
 * Dazzling functions and definitions
 *
 * @package dazzling
 */

/**
 * The theme version, read from the style.css header. Every theme-owned asset is
 * enqueued with it, so a release reaches returning visitors instead of being
 * served from a year-long browser cache.
 */
if ( ! defined( 'DAZZLING_VERSION' ) ) {
	define( 'DAZZLING_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 730; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 *
 * The Full-width template and the Full Width layout give the content the
 * container's whole content width, 1140px (it said 1110, and named a
 * front-page.php template the theme does not have).
 */
function dazzling_content_width() {
	if ( is_page_template( 'page-fullwidth.php' ) || 'full-width' === dazzling_get_layout_class() ) {
		$GLOBALS['content_width'] = 1140; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- $content_width is the core global this is meant to set.
	}
}
add_action( 'template_redirect', 'dazzling_content_width' );

if ( ! function_exists( 'dazzling_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dazzling_setup() {

		/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Dazzling, use a find and replace
		* to change 'dazzling' to the name of your theme in all the template files
		*/
		load_theme_textdomain( 'dazzling', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		*/
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'dazzling-featured', 730, 410, true );
		add_image_size( 'tab-small', 60, 60, true ); // Small Thumbnail

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary'      => __( 'Primary Menu', 'dazzling' ),
				'footer-links' => __( 'Footer Links', 'dazzling' ), // secondary menu in footer
			)
		);

		// Enable support for Post Formats.
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

		// Setup the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'dazzling_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		// Core markup for the search form, comment form and list, galleries and captions.
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

		// Widgets in the Customizer refresh in place instead of reloading the preview.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		* Block editor support. The theme predates the block editor: embeds kept
		* their fixed width, wide and full alignments were not offered, and the
		* editor canvas looked nothing like the published post.
		*/
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'inc/css/editor-style.css' );
	}
endif; // dazzling_setup
add_action( 'after_setup_theme', 'dazzling_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function dazzling_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'dazzling' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'id'            => 'home-widget-1',
			'name'          => __( 'Homepage Widget 1', 'dazzling' ),
			'description'   => __( 'Displays on the Home Page', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'home-widget-2',
			'name'          => __( 'Homepage Widget 2', 'dazzling' ),
			'description'   => __( 'Displays on the Home Page', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'home-widget-3',
			'name'          => __( 'Homepage Widget 3', 'dazzling' ),
			'description'   => __( 'Displays on the Home Page', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'footer-widget-1',
			'name'          => __( 'Footer Widget 1', 'dazzling' ),
			'description'   => __( 'Used for footer widget area', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'footer-widget-2',
			'name'          => __( 'Footer Widget 2', 'dazzling' ),
			'description'   => __( 'Used for footer widget area', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'id'            => 'footer-widget-3',
			'name'          => __( 'Footer Widget 3', 'dazzling' ),
			'description'   => __( 'Used for footer widget area', 'dazzling' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		)
	);

	register_widget( 'dazzling_social_widget' );
	register_widget( 'dazzling_popular_posts_widget' );
}
add_action( 'widgets_init', 'dazzling_widgets_init' );

require get_parent_theme_file_path( '/inc/widgets/widget-popular-posts.php' );
require get_parent_theme_file_path( '/inc/widgets/widget-social.php' );


/**
 * Enqueue scripts and styles.
 */
function dazzling_scripts() {

	wp_enqueue_style( 'dazzling-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css', array(), '3.4.1' );

	/*
	* Every handle carries the theme's prefix: handles are global, and a plugin
	* that registers 'flexslider' or 'flexslider-css' first would otherwise have
	* silently replaced the theme's copy. Theme-owned files are versioned with
	* the theme, libraries with their own version.
	*/

	/*
	* Font Awesome 7, self-hosted and split by style: the core file carries the
	* icon name map, each style file adds one @font-face. No v4 or v5 shim is
	* loaded -- the theme's own markup uses native Font Awesome 7 class names --
	* and only woff2 is shipped, which every browser that can run a current
	* WordPress supports.
	*/
	$fa_uri = get_template_directory_uri() . '/inc/css/fontawesome/';
	/*
	* The bundled Font Awesome is subsetted to the glyphs this theme renders, a few
	* kilobytes rather than a few hundred. A site that uses Font Awesome classes in
	* its own content -- a widget, a page builder, a child theme -- can load the
	* complete set instead:
	*
	*     add_filter( 'dazzling_full_fontawesome', '__return_true' );
	*/
	if ( apply_filters( 'dazzling_full_fontawesome', false ) ) {
		wp_enqueue_style( 'dazzling-icons', $fa_uri . 'fontawesome.min.css', array(), '7.3.1' );
		wp_enqueue_style( 'dazzling-icons-solid', $fa_uri . 'solid.min.css', array( 'dazzling-icons' ), '7.3.1' );
		wp_enqueue_style( 'dazzling-icons-regular', $fa_uri . 'regular.min.css', array( 'dazzling-icons' ), '7.3.1' );
		wp_enqueue_style( 'dazzling-icons-brands', $fa_uri . 'brands.min.css', array( 'dazzling-icons' ), '7.3.1' );
	} else {
		wp_enqueue_style( 'dazzling-icons', $fa_uri . 'subset/fontawesome-subset.min.css', array(), '7.3.1' );
	}

	/*
	 * The slider renders on the front page only (dazzling_featured_slider());
	 * its files were also loaded on the posts page when that is a separate page.
	 */
	$dazzling_slider_active = is_front_page() && dazzling_sanitize_checkbox( of_get_option( 'dazzling_slider_checkbox' ) );

	if ( $dazzling_slider_active ) {
		wp_enqueue_style( 'dazzling-flexslider', get_template_directory_uri() . '/inc/css/flexslider.css', array(), DAZZLING_VERSION );
	}

	/*
	* Open Sans is one of the body fonts offered under Typography, and the only
	* one that is not a system font. It is bundled with the theme and loaded
	* only when chosen, so no request goes to Google.
	*/
	$typography = of_get_option( 'main_body_typography' );
	if ( is_array( $typography ) && isset( $typography['face'] ) && 'Open Sans' === $typography['face'] ) {
		wp_enqueue_style( 'dazzling-fonts', get_template_directory_uri() . '/inc/css/google-fonts.css', array(), DAZZLING_VERSION );
	}

	if ( class_exists( 'jigoshop' ) ) { // Jigoshop specific styles loaded only when plugin is installed
		wp_enqueue_style( 'dazzling-jigoshop', get_template_directory_uri() . '/inc/css/jigoshop.css', array(), DAZZLING_VERSION );
	}

	/*
	* get_stylesheet_uri() is the child theme's style.css when one is active, so
	* the version comes from that theme. With no version WordPress appended its
	* own (?ver=7.1.2), so a theme update never reached a cached copy.
	*/
	wp_enqueue_style( 'dazzling-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	/*
	 * Restores the jQuery utilities that jQuery 4 removed and Bootstrap 3 and
	 * FlexSlider still call. A guarded no-op on jQuery 3.
	 */
	wp_register_script( 'dazzling-jquery-compat', get_template_directory_uri() . '/inc/js/jquery-compat.js', array( 'jquery' ), DAZZLING_VERSION, true );

	// Bootstrap 3.4.1 with its jQuery version guard patched to accept jQuery 4.
	wp_enqueue_script( 'dazzling-bootstrapjs', get_template_directory_uri() . '/inc/js/bootstrap.min.js', array( 'jquery', 'dazzling-jquery-compat' ), '3.4.1-dazzling.1', true );

	if ( $dazzling_slider_active ) {
		wp_enqueue_script( 'dazzling-flexslider', get_template_directory_uri() . '/inc/js/flexslider.min.js', array( 'jquery', 'dazzling-jquery-compat' ), '2.7.2', true );
	}

	wp_enqueue_script( 'dazzling-main', get_template_directory_uri() . '/inc/js/main.js', array( 'jquery' ), DAZZLING_VERSION, true );
	wp_localize_script(
		'dazzling-main',
		'dazzlingL10n',
		array(
			// Names for the slider's arrow links, which were empty.
			'previous' => esc_html__( 'Previous slide', 'dazzling' ),
			'next'     => esc_html__( 'Next slide', 'dazzling' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dazzling_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_parent_theme_file_path( '/inc/extras.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * Load Jetpack compatibility file.
 */
require get_parent_theme_file_path( '/inc/jetpack.php' );

/**
 * Load custom nav walker
 */
require get_parent_theme_file_path( '/inc/class-dazzling-bootstrap-navwalker.php' );

if ( class_exists( 'woocommerce' ) ) {
	/**
	 * WooCommerce related functions
	 */
	require get_parent_theme_file_path( '/inc/woo-setup.php' );
}

if ( class_exists( 'jigoshop' ) ) {
	/**
	 * Jigoshop related functions
	 */
	require get_parent_theme_file_path( '/inc/jigoshop-setup.php' );
}

/**
 * Metabox file load
 */
require get_parent_theme_file_path( '/inc/metaboxes.php' );

/**
 * TGMPA
 */
require get_parent_theme_file_path( '/inc/tgmpa/tgm-plugin-activation.php' );

/**
 * Register Social Icon menu
 */
function dazzling_register_social_menu() {
	register_nav_menu( 'social-menu', _x( 'Social Menu', 'nav menu location', 'dazzling' ) );
}
add_action( 'init', 'dazzling_register_social_menu' );

if ( ! function_exists( 'dazzling_get_layouts' ) ) :
	/**
	 * The site layouts the theme offers, keyed by the class each one adds.
	 *
	 * @return array<string, string>
	 */
	function dazzling_get_layouts() {
		return array(
			'side-pull-left'  => esc_html__( 'Right Sidebar', 'dazzling' ),
			'side-pull-right' => esc_html__( 'Left Sidebar', 'dazzling' ),
			'no-sidebar'      => esc_html__( 'No Sidebar', 'dazzling' ),
			'full-width'      => esc_html__( 'Full Width', 'dazzling' ),
		);
	}
endif;

if ( ! function_exists( 'dazzling_get_typography_options' ) ) :
	/**
	 * Choices for the Typography section: sizes, font stacks and weights.
	 *
	 * @return array
	 */
	function dazzling_get_typography_options() {
		return array(
			'sizes'  => array(
				'6px'  => '6px',
				'10px' => '10px',
				'12px' => '12px',
				'14px' => '14px',
				'15px' => '15px',
				'16px' => '16px',
				'18px' => '18px',
				'20px' => '20px',
				'24px' => '24px',
				'28px' => '28px',
				'32px' => '32px',
				'36px' => '36px',
				'42px' => '42px',
				'48px' => '48px',
			),
			'faces'  => array(
				'arial'          => 'Arial,Helvetica,sans-serif',
				'verdana'        => 'Verdana,Geneva,sans-serif',
				'trebuchet'      => 'Trebuchet,Helvetica,sans-serif',
				'georgia'        => 'Georgia,serif',
				'times'          => 'Times New Roman,Times, serif',
				'tahoma'         => 'Tahoma,Geneva,sans-serif',
				'Open Sans'      => 'Open Sans,sans-serif',
				'palatino'       => 'Palatino,serif',
				'helvetica'      => 'Helvetica,Arial,sans-serif',
				'helvetica-neue' => 'Helvetica Neue,Helvetica,Arial,sans-serif',
			),
			'styles' => array(
				'normal' => esc_html__( 'Normal', 'dazzling' ),
				'bold'   => esc_html__( 'Bold', 'dazzling' ),
			),
			'color'  => true,
		);
	}
endif;

if ( ! function_exists( 'dazzling_get_typography_defaults' ) ) :
	/**
	 * Default body typography.
	 *
	 * @return array<string, string>
	 */
	function dazzling_get_typography_defaults() {
		return array(
			'size'  => '14px',
			'face'  => 'helvetica-neue',
			'style' => 'normal',
			'color' => '#6B6B6B',
		);
	}
endif;

if ( ! function_exists( 'dazzling_get_slider_categories' ) ) :
	/**
	 * Categories the featured slider can draw from, as ID => name.
	 *
	 * Only the Customizer needs this list. It used to be built at load time on
	 * every request, front end included -- a category query nothing used.
	 *
	 * @return array<int, string>
	 */
	function dazzling_get_slider_categories() {
		$categories = array();
		foreach ( get_categories() as $category ) {
			$categories[ $category->cat_ID ] = $category->cat_name;
		}
		return $categories;
	}
endif;

/**
 * Fill the globals earlier versions declared, for child themes that read them.
 *
 * They used to be built while functions.php loaded, which translated the
 * layout names before WordPress 6.7+ allows (a "translation loading was
 * triggered too early" notice on every request) and queried every category on
 * every page. They are filled on init now, after translations are available;
 * the category list only where the Customizer builds it.
 */
function dazzling_legacy_globals() {
	// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- unprefixed names kept for child themes written against 2.2 and earlier.
	$GLOBALS['site_layout']         = dazzling_get_layouts();
	$GLOBALS['typography_options']  = dazzling_get_typography_options();
	$GLOBALS['typography_defaults'] = dazzling_get_typography_defaults();
	if ( ! isset( $GLOBALS['options_categories'] ) ) {
		$GLOBALS['options_categories'] = array();
	}
	// phpcs:enable
}
add_action( 'init', 'dazzling_legacy_globals' );

if ( ! function_exists( 'of_get_option' ) ) :
	/**
	 * Helper function to return the theme option value.
	 * If no value has been saved, it returns $default_value.
	 *
	 * The name comes from the Options Framework the theme was built on, and
	 * child themes call it, so it keeps its unprefixed name.
	 *
	 * @param string $name          Key inside the 'dazzling' option.
	 * @param mixed  $default_value Returned when the key is not set.
	 * @return mixed
	 */
	function of_get_option( $name, $default_value = false ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- public API since 1.x, see above.
		$options = get_option( 'dazzling' );

		if ( is_array( $options ) && isset( $options[ $name ] ) ) {
			return $options[ $name ];
		}

		return $default_value;
	}
endif;
