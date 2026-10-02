<?php

/**
 * Bootstrap 3 nav walker for the primary menu.
 *
 * Based on wp_bootstrap_navwalker 2.0.4 by Edward McIntyre. The class carries
 * the theme's prefix: plugins bundle their own Bootstrap 4/5 walker under the
 * name WP_Bootstrap_Navwalker (class names are case-insensitive), and loading
 * the theme's unprefixed copy beside one was a fatal "cannot declare class"
 * error. wp_bootstrap_navwalker remains available as an alias when no plugin
 * has taken the name, for child themes that use it.
 *
 * @package dazzling
 *
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Dazzling_Bootstrap_Navwalker extends Walker_Nav_Menu {

	/**
	 * Open a sub-menu.
	 *
	 * The role="menu" it used to carry promised the arrow-key behaviour of an
	 * application menu, which a list of links does not have.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	/**
	 * Open one menu item.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/*
		 * Dividers, headers and disabled items are chosen by the Title
		 * Attribute (or, for dividers, the label), compared case-insensitively.
		 */
		if ( 1 === $depth && ( 0 === strcasecmp( $item->attr_title, 'divider' ) || 0 === strcasecmp( $item->title, 'divider' ) ) ) {
			$output .= $indent . '<li role="presentation" class="divider">';
			return;
		}
		if ( 1 === $depth && 0 === strcasecmp( $item->attr_title, 'dropdown-header' ) ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_html( $item->title );
			return;
		}
		if ( 0 === strcasecmp( $item->attr_title, 'disabled' ) ) {
			$output .= $indent . '<li class="disabled"><a href="#" aria-disabled="true">' . esc_html( $item->title ) . '</a>';
			return;
		}

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.

		if ( $args->has_children ) {
			$class_names .= ' dropdown';
		}

		// The current item, and the parent of the current item, are highlighted.
		if ( array_intersect( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ), $classes ) ) {
			$class_names .= ' active';
		}

		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$item_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
		$item_id = $item_id ? ' id="' . esc_attr( $item_id ) . '"' : '';

		$output .= $indent . '<li' . $item_id . $class_names . '>';

		/*
		 * No title attribute: it repeated the link text, so screen readers
		 * announced every item twice, and this walker uses the Title
		 * Attribute field for a glyphicon class, which then showed up as a
		 * tooltip such as "glyphicon-home".
		 */
		$atts           = array();
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

		if ( $args->has_children && 0 === $depth ) {
			$atts['href']          = '#';
			$atts['data-toggle']   = 'dropdown';
			$atts['class']         = 'dropdown-toggle';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		} else {
			$atts['href'] = ! empty( $item->url ) ? $item->url : '';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;

		// The Title Attribute, when set, is the class of a glyphicon shown before the label.
		if ( ! empty( $item->attr_title ) ) {
			$item_output .= '<a' . $attributes . '><span class="glyphicon ' . esc_attr( $item->attr_title ) . '" aria-hidden="true"></span>&nbsp;';
		} else {
			$item_output .= '<a' . $attributes . '>';
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
		$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret" aria-hidden="true"></span></a>' : '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
	}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];

		// Display this element.
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
	public static function fallback( $args ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$container       = isset( $args['container'] ) ? $args['container'] : '';
		$container_id    = isset( $args['container_id'] ) ? $args['container_id'] : '';
		$container_class = isset( $args['container_class'] ) ? $args['container_class'] : '';
		$menu_id         = isset( $args['menu_id'] ) ? $args['menu_id'] : '';
		$menu_class      = isset( $args['menu_class'] ) ? $args['menu_class'] : '';
		$container       = in_array( $container, array( 'div', 'nav' ), true ) ? $container : '';

		$fb_output = '';
		if ( $container ) {
			$fb_output .= '<' . $container;
			if ( $container_id ) {
				$fb_output .= ' id="' . esc_attr( $container_id ) . '"';
			}
			if ( $container_class ) {
				$fb_output .= ' class="' . esc_attr( $container_class ) . '"';
			}
			$fb_output .= '>';
		}

		$fb_output .= '<ul';
		if ( $menu_id ) {
			$fb_output .= ' id="' . esc_attr( $menu_id ) . '"';
		}
		if ( $menu_class ) {
			$fb_output .= ' class="' . esc_attr( $menu_class ) . '"';
		}
		$fb_output .= '>';
		$fb_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'dazzling' ) . '</a></li>';
		$fb_output .= '</ul>';

		if ( $container ) {
			$fb_output .= '</' . $container . '>';
		}

		echo $fb_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every part is escaped above.
	}
}

if ( ! class_exists( 'wp_bootstrap_navwalker' ) ) {
	class_alias( 'Dazzling_Bootstrap_Navwalker', 'wp_bootstrap_navwalker' );
}
