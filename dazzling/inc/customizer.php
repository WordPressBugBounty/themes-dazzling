<?php
/**
 * Dazzling Theme Customizer.
 *
 * Every theme option is stored in the single 'dazzling' option, as an array
 * keyed by the names below (inherited from the Options Framework the theme
 * started with), and read with of_get_option(). The setting ids are
 * 'dazzling[<key>]', so they must never be renamed without a migration.
 *
 * @package dazzling
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dazzling_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'dazzling_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dazzling_customize_preview_js() {
	wp_enqueue_script( 'dazzling-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), DAZZLING_VERSION, true );
}
add_action( 'customize_preview_init', 'dazzling_customize_preview_js' );

/**
 * Register a colour setting and its control.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 * @param string               $key          Key inside the 'dazzling' option.
 * @param string               $section      Section id.
 * @param string               $label        Control label.
 * @param string               $description  Control description.
 */
function dazzling_customizer_add_color( $wp_customize, $key, $section, $label, $description = '' ) {
	$wp_customize->add_setting(
		'dazzling[' . $key . ']',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_hexcolor',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'dazzling[' . $key . ']',
			array(
				'label'       => $label,
				'description' => $description,
				'section'     => $section,
			)
		)
	);
}

/**
 * Whether the featured slider is switched on, for the slider's other controls.
 *
 * @param WP_Customize_Control $control Control being checked.
 * @return bool
 */
function dazzling_customizer_slider_enabled( $control ) {
	$setting = $control->manager->get_setting( 'dazzling[dazzling_slider_checkbox]' );
	return $setting && dazzling_sanitize_checkbox( $setting->value() );
}

/**
 * Options for Dazzling Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function dazzling_customizer( $wp_customize ) {
	$default_color = __( 'Default used if no color is selected', 'dazzling' );

	/* Main option Settings Panel */
	$wp_customize->add_panel(
		'dazzling_main_options',
		array(
			'capability' => 'edit_theme_options',
			'title'      => __( 'Dazzling Options', 'dazzling' ),
			'priority'   => 10,
		)
	);

	/* Slider */
	$wp_customize->add_section(
		'dazzling_slider_options',
		array(
			'title'       => __( 'Slider options', 'dazzling' ),
			'description' => __( 'A full-width slider of posts with a featured image, at the top of the front page.', 'dazzling' ),
			'priority'    => 31,
			'panel'       => 'dazzling_main_options',
		)
	);
	$wp_customize->add_setting(
		'dazzling[dazzling_slider_checkbox]',
		array(
			'default'           => 0,
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'dazzling[dazzling_slider_checkbox]',
		array(
			'label'    => esc_html__( 'Check if you want to enable slider', 'dazzling' ),
			'section'  => 'dazzling_slider_options',
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/*
	 * The category is optional (2.2.5): with none chosen the slider shows the
	 * latest posts. Without an empty choice the select showed the first
	 * category as selected while the site used all of them.
	 */
	$wp_customize->add_setting(
		'dazzling[dazzling_slide_categories]',
		array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'dazzling_sanitize_slidecat',
		)
	);
	$wp_customize->add_control(
		'dazzling[dazzling_slide_categories]',
		array(
			'label'           => __( 'Slider Category', 'dazzling' ),
			'section'         => 'dazzling_slider_options',
			'type'            => 'select',
			'description'     => __( 'Select a category for the featured post slider', 'dazzling' ),
			'choices'         => array( '' => __( 'All categories (latest posts)', 'dazzling' ) ) + dazzling_get_slider_categories(),
			'active_callback' => 'dazzling_customizer_slider_enabled',
		)
	);

	$wp_customize->add_setting(
		'dazzling[dazzling_slide_number]',
		array(
			'default'           => 3,
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'dazzling[dazzling_slide_number]',
		array(
			'label'           => __( 'Number of slide items', 'dazzling' ),
			'section'         => 'dazzling_slider_options',
			'description'     => __( 'Enter the number of slide items', 'dazzling' ),
			'type'            => 'number',
			'input_attrs'     => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
			'active_callback' => 'dazzling_customizer_slider_enabled',
		)
	);

	/* Layout */
	$wp_customize->add_section(
		'dazzling_layout_options',
		array(
			'title'    => __( 'Layout options', 'dazzling' ),
			'priority' => 31,
			'panel'    => 'dazzling_main_options',
		)
	);
	$wp_customize->add_setting(
		'dazzling[site_layout]',
		array(
			'default'           => 'side-pull-left',
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_layout',
		)
	);
	$wp_customize->add_control(
		'dazzling[site_layout]',
		array(
			'label'       => __( 'Website Layout Options', 'dazzling' ),
			'section'     => 'dazzling_layout_options',
			'type'        => 'select',
			'description' => __( 'Choose between different layout options to be used as default', 'dazzling' ),
			'choices'     => dazzling_get_layouts(),
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'element_color', 'dazzling_layout_options', __( 'Element Color', 'dazzling' ), __( 'Buttons, the active menu item, slider captions and post navigation.', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'element_color_hover', 'dazzling_layout_options', __( 'Element color on hover', 'dazzling' ), $default_color );

	/* Call for action */
	$wp_customize->add_section(
		'dazzling_action_options',
		array(
			'title'       => __( 'Action Button', 'dazzling' ),
			'description' => __( 'A band with a line of text and a button, shown on the front page when the text is filled in.', 'dazzling' ),
			'priority'    => 31,
			'panel'       => 'dazzling_main_options',
		)
	);
	$wp_customize->add_setting(
		'dazzling[w2f_cfa_text]',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'dazzling[w2f_cfa_text]',
		array(
			'label'       => __( 'Call For Action Text', 'dazzling' ),
			'description' => __( 'Enter the text for call for action section', 'dazzling' ),
			'section'     => 'dazzling_action_options',
			'type'        => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'dazzling[w2f_cfa_button]',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_nohtml',
		)
	);
	$wp_customize->add_control(
		'dazzling[w2f_cfa_button]',
		array(
			'label'       => __( 'Call For Action Button Title', 'dazzling' ),
			'section'     => 'dazzling_action_options',
			'description' => __( 'Enter the title for Call For Action button', 'dazzling' ),
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting(
		'dazzling[w2f_cfa_link]',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'dazzling[w2f_cfa_link]',
		array(
			'label'       => __( 'Call For Action Button Link', 'dazzling' ),
			'section'     => 'dazzling_action_options',
			'description' => __( 'Enter the link for Call For Action button', 'dazzling' ),
			'type'        => 'url',
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'cfa_color', 'dazzling_action_options', __( 'Call For Action Text Color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'cfa_bg_color', 'dazzling_action_options', __( 'Call For Action Background Color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'cfa_btn_color', 'dazzling_action_options', __( 'Call For Action Button Border Color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'cfa_btn_txt_color', 'dazzling_action_options', __( 'Call For Action Button Text Color', 'dazzling' ), $default_color );

	/* Typography */
	$wp_customize->add_section(
		'dazzling_typography_options',
		array(
			'title'    => __( 'Typography', 'dazzling' ),
			'priority' => 31,
			'panel'    => 'dazzling_main_options',
		)
	);
	$typography_defaults = dazzling_get_typography_defaults();
	$typography_options  = dazzling_get_typography_options();

	/*
	 * The font, weight and colour controls had no labels: three unnamed
	 * fields under "Main Body Text", announced by screen readers as just
	 * "combo box".
	 */
	$wp_customize->add_setting(
		'dazzling[main_body_typography][size]',
		array(
			'default'           => $typography_defaults['size'],
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_typo_size',
		)
	);
	$wp_customize->add_control(
		'dazzling[main_body_typography][size]',
		array(
			'label'       => __( 'Main Body Text', 'dazzling' ),
			'description' => __( 'Font size of post and page content.', 'dazzling' ),
			'section'     => 'dazzling_typography_options',
			'type'        => 'select',
			'choices'     => $typography_options['sizes'],
		)
	);
	$wp_customize->add_setting(
		'dazzling[main_body_typography][face]',
		array(
			'default'           => $typography_defaults['face'],
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_typo_face',
		)
	);
	$wp_customize->add_control(
		'dazzling[main_body_typography][face]',
		array(
			'label'   => __( 'Main Body Font', 'dazzling' ),
			'section' => 'dazzling_typography_options',
			'type'    => 'select',
			'choices' => $typography_options['faces'],
		)
	);
	$wp_customize->add_setting(
		'dazzling[main_body_typography][style]',
		array(
			'default'           => $typography_defaults['style'],
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_typo_style',
		)
	);
	$wp_customize->add_control(
		'dazzling[main_body_typography][style]',
		array(
			'label'   => __( 'Main Body Font Weight', 'dazzling' ),
			'section' => 'dazzling_typography_options',
			'type'    => 'select',
			'choices' => $typography_options['styles'],
		)
	);
	$wp_customize->add_setting(
		'dazzling[main_body_typography][color]',
		array(
			'default'           => $typography_defaults['color'],
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_hexcolor',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'dazzling[main_body_typography][color]',
			array(
				'label'   => __( 'Main Body Text Color', 'dazzling' ),
				'section' => 'dazzling_typography_options',
			)
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'heading_color', 'dazzling_typography_options', __( 'Heading Color', 'dazzling' ), __( 'Color for all headings (h1-h6)', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'link_color', 'dazzling_typography_options', __( 'Link Color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'link_hover_color', 'dazzling_typography_options', __( 'Link:hover Color', 'dazzling' ), $default_color );

	/* Header */
	$wp_customize->add_section(
		'dazzling_header_options',
		array(
			'title'    => __( 'Header', 'dazzling' ),
			'priority' => 31,
			'panel'    => 'dazzling_main_options',
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'top_nav_bg_color', 'dazzling_header_options', __( 'Top nav background color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'top_nav_link_color', 'dazzling_header_options', __( 'Top nav item color', 'dazzling' ), __( 'Link color', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'top_nav_dropdown_bg', 'dazzling_header_options', __( 'Top nav dropdown background color', 'dazzling' ), __( 'Background of the dropdown menus.', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'top_nav_dropdown_item', 'dazzling_header_options', __( 'Top nav dropdown item color', 'dazzling' ), __( 'Dropdown item color', 'dazzling' ) );

	/* Footer */
	$wp_customize->add_section(
		'dazzling_footer_options',
		array(
			'title'    => __( 'Footer', 'dazzling' ),
			'priority' => 31,
			'panel'    => 'dazzling_main_options',
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'footer_widget_bg_color', 'dazzling_footer_options', __( 'Footer widget area background color', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'footer_bg_color', 'dazzling_footer_options', __( 'Footer background color', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'footer_text_color', 'dazzling_footer_options', __( 'Footer text color', 'dazzling' ) );
	dazzling_customizer_add_color( $wp_customize, 'footer_link_color', 'dazzling_footer_options', __( 'Footer link color', 'dazzling' ) );
	$wp_customize->add_setting(
		'dazzling[custom_footer_text]',
		array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'dazzling[custom_footer_text]',
		array(
			'label'       => __( 'Footer information', 'dazzling' ),
			'description' => __( 'Copyright text in footer', 'dazzling' ),
			'section'     => 'dazzling_footer_options',
			'type'        => 'textarea',
		)
	);

	/* Social */
	$wp_customize->add_section(
		'dazzling_social_options',
		array(
			'title'       => __( 'Social', 'dazzling' ),
			'description' => __( 'The icons are the links of the menu assigned to the Social Menu location, under Menus.', 'dazzling' ),
			'priority'    => 31,
			'panel'       => 'dazzling_main_options',
		)
	);
	dazzling_customizer_add_color( $wp_customize, 'social_color', 'dazzling_social_options', __( 'Social icon color', 'dazzling' ), $default_color );
	dazzling_customizer_add_color( $wp_customize, 'social_hover_color', 'dazzling_social_options', __( 'Social Icon:hover Color', 'dazzling' ), $default_color );
	$wp_customize->add_setting(
		'dazzling[footer_social]',
		array(
			'default'           => 0,
			'type'              => 'option',
			'sanitize_callback' => 'dazzling_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'dazzling[footer_social]',
		array(
			'label'       => __( 'Footer Social Icons', 'dazzling' ),
			'description' => __( 'Check to show social icons in footer', 'dazzling' ),
			'section'     => 'dazzling_social_options',
			'type'        => 'checkbox',
		)
	);

	/*
	 * Support and documentation links. The section used to load Facebook's
	 * and Twitter's SDKs into the Customizer for a Like and a Follow button,
	 * sending every administrator who opened it to both. It is plain links
	 * now, and has no setting to save.
	 */
	$wp_customize->add_section(
		'dazzling_important_links',
		array(
			'priority' => 5,
			'title'    => __( 'Support and Documentation', 'dazzling' ),
		)
	);
	require_once get_parent_theme_file_path( '/inc/class-dazzling-important-links.php' );
	$wp_customize->add_control(
		new Dazzling_Important_Links(
			$wp_customize,
			'dazzling_important_links',
			array(
				'section'  => 'dazzling_important_links',
				'settings' => array(),
			)
		)
	);
}
add_action( 'customize_register', 'dazzling_customizer' );

/**
 * Sanitize checkbox for WordPress customizer.
 *
 * @param mixed $input Submitted value; the Customizer sends a boolean.
 * @return int|string 1 when checked, '' when not.
 */
function dazzling_sanitize_checkbox( $input ) {
	return ( true === $input || 1 === $input || '1' === $input || 'true' === $input ) ? 1 : '';
}

/**
 * Adds sanitization callback function: colors
 *
 * @param string $color Submitted colour.
 * @return string '#rrggbb', or '' when the value is not a hex colour.
 */
function dazzling_sanitize_hexcolor( $color ) {
	$unhashed = sanitize_hex_color_no_hash( $color );

	if ( $unhashed ) {
		return '#' . $unhashed;
	}

	/*
	 * Previously this returned $color unchanged when validation failed, which
	 * meant arbitrary text could be stored and later echoed straight into the
	 * <style> block emitted by get_dazzling_theme_options(). Reject instead.
	 */
	return '';
}

/**
 * Adds sanitization callback function: Nohtml
 *
 * This used wp_filter_nohtml_kses(), which returns its result slashed for
 * the database, so a button title such as "Don't wait" was saved and shown as
 * "Don\'t wait".
 *
 * @param string $input Submitted text.
 * @return string
 */
function dazzling_sanitize_nohtml( $input ) {
	return sanitize_text_field( $input );
}

/**
 * Adds sanitization callback function: Number
 *
 * Returned nothing for anything but a number, so clearing the field saved
 * null and the slider fell back without the Customizer showing why.
 *
 * @param mixed $input Submitted value.
 * @return int A positive whole number; 3 when the input is not one.
 */
function dazzling_sanitize_number( $input ) {
	$number = absint( $input );
	return $number ? $number : 3;
}

/**
 * Adds sanitization callback function: Strip Slashes
 *
 * No longer used by the theme's own settings; kept for child themes.
 *
 * @param string $input Submitted text.
 * @return string
 */
function dazzling_sanitize_strip_slashes( $input ) {
	return wp_kses_stripslashes( $input );
}

/**
 * Adds sanitization callback function: Slider Category
 *
 * @param mixed $input Submitted category id.
 * @return int|string The category id, or '' for all categories.
 */
function dazzling_sanitize_slidecat( $input ) {
	$input = absint( $input );
	return ( $input && array_key_exists( $input, dazzling_get_slider_categories() ) ) ? $input : '';
}

/**
 * Adds sanitization callback function: Sidebar Layout
 *
 * @param string $input Submitted layout.
 * @return string
 */
function dazzling_sanitize_layout( $input ) {
	if ( array_key_exists( $input, dazzling_get_layouts() ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: Typography Size
 *
 * @param string $input Submitted size.
 * @return string
 */
function dazzling_sanitize_typo_size( $input ) {
	$typography_options  = dazzling_get_typography_options();
	$typography_defaults = dazzling_get_typography_defaults();
	if ( array_key_exists( $input, $typography_options['sizes'] ) ) {
		return $input;
	} else {
		return $typography_defaults['size'];
	}
}

/**
 * Adds sanitization callback function: Typography Face
 *
 * @param string $input Submitted font.
 * @return string
 */
function dazzling_sanitize_typo_face( $input ) {
	$typography_options  = dazzling_get_typography_options();
	$typography_defaults = dazzling_get_typography_defaults();
	if ( array_key_exists( $input, $typography_options['faces'] ) ) {
		return $input;
	} else {
		return $typography_defaults['face'];
	}
}

/**
 * Adds sanitization callback function: Typography Style
 *
 * @param string $input Submitted weight.
 * @return string
 */
function dazzling_sanitize_typo_style( $input ) {
	$typography_options  = dazzling_get_typography_options();
	$typography_defaults = dazzling_get_typography_defaults();
	if ( array_key_exists( $input, $typography_options['styles'] ) ) {
		return $input;
	} else {
		return $typography_defaults['style'];
	}
}

/**
 * Styles for the theme's Customizer controls.
 */
function dazzling_customizer_custom_control_css() {
	?>
	<style>
		#customize-control-dazzling-main_body_typography-size select,
		#customize-control-dazzling-main_body_typography-face select,
		#customize-control-dazzling-main_body_typography-style select { width: 60%; }
		#accordion-section-dazzling_important_links .accordion-section-title,
		#accordion-section-dazzling_important_links .accordion-section-title button { background-color: #1fa67a; color: #fff; }
		#accordion-section-dazzling_important_links .accordion-section-title:hover,
		#accordion-section-dazzling_important_links .accordion-section-title button:hover,
		#accordion-section-dazzling_important_links .accordion-section-title button:focus { background-color: #1b926c; color: #fff; }
		#accordion-section-dazzling_important_links .accordion-section-title::after,
		#accordion-section-dazzling_important_links .accordion-section-title button::after { color: #fff; }
		.dazzling-important-links li { margin-bottom: 8px; }
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'dazzling_customizer_custom_control_css' );

/**
 * Move the retired Other > Custom CSS option into WordPress' Additional CSS.
 *
 * WordPress has had its own Additional CSS since 4.7, and WordPress.org asks
 * themes to use it instead of their own. The theme's copy is appended to the
 * site's Additional CSS once, under a comment, and removed from the theme
 * options. Until that has happened get_dazzling_theme_options() keeps
 * printing it, so no site loses its CSS in between.
 *
 * Runs in the admin and the Customizer only, never on a front-end request.
 */
function dazzling_migrate_custom_css() {
	if ( ! current_user_can( 'edit_css' ) || ! function_exists( 'wp_update_custom_css_post' ) ) {
		return;
	}

	$options = get_option( 'dazzling' );
	if ( ! is_array( $options ) || ! isset( $options['custom_css'] ) ) {
		return;
	}

	$css = trim( wp_strip_all_tags( (string) $options['custom_css'] ) );
	if ( '' !== $css ) {
		$core = wp_get_custom_css();
		if ( false === strpos( $core, $css ) ) {
			$heading = '/* ' . __( 'Moved from Dazzling Options > Other > Custom CSS', 'dazzling' ) . ' */';
			$result  = wp_update_custom_css_post( trim( $core . "\n\n" . $heading . "\n" . $css ) );
			if ( is_wp_error( $result ) ) {
				return;
			}
		}
	}

	unset( $options['custom_css'] );
	update_option( 'dazzling', $options );
}
add_action( 'admin_init', 'dazzling_migrate_custom_css' );
add_action( 'customize_register', 'dazzling_migrate_custom_css', 1 );
