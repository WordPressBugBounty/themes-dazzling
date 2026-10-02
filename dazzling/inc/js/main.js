/**
 * Dazzling front-end behaviour.
 *
 * Shipped unminified: it is small, and WordPress.org asks for readable source.
 * Written against the jQuery API that jQuery 3 and 4 share -- no event
 * shorthands (.scroll(), .click(), .load()), which jQuery 4 deprecates or
 * removes; .on() throughout.
 */
( function( $ ) {
	'use strict';

	/**
	 * Give WordPress-generated markup the Bootstrap classes the theme styles.
	 */
	function initBootstrapClasses() {
		$( '#submit, .wpcf7-submit, .comment-reply-link, input[type="submit"]' ).addClass( 'btn btn-default' );
		$( '.wp-caption' ).addClass( 'thumbnail' );
		$( '.widget_rss ul' ).addClass( 'media-list' );
		$( 'table#wp-calendar' ).addClass( 'table table-striped' );
	}

	/**
	 * The "back to top" button: shown once the page has scrolled, and scrolls
	 * smoothly unless the visitor asked for reduced motion.
	 */
	function initScrollToTop() {
		var $button = $( '.scroll-to-top' );
		var visible = false;

		if ( ! $button.length ) {
			return;
		}

		function update() {
			var shouldShow = $( window ).scrollTop() > 100;
			if ( shouldShow !== visible ) {
				visible = shouldShow;
				if ( visible ) {
					$button.stop( true, true ).fadeIn();
				} else {
					$button.stop( true, true ).fadeOut();
				}
			}
		}

		$( window ).on( 'scroll', update );
		update();

		$button.on( 'click', function( event ) {
			var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
			event.preventDefault();
			window.scrollTo( { top: 0, behavior: reduce ? 'auto' : 'smooth' } );
			// Keyboard users land at the top of the page, not on a hidden button.
			$( '#page' ).attr( 'tabindex', '-1' ).trigger( 'focus' );
		} );
	}

	/**
	 * The featured slider on the front page. FlexSlider is only enqueued when
	 * the slider is switched on, so it is feature-detected here.
	 */
	function initSlider() {
		var $slider = $( '.flexslider' );

		if ( ! $slider.length || 'function' !== typeof $.fn.flexslider ) {
			return;
		}

		var l10n = window.dazzlingL10n || {};
		var label = function( text ) {
			return text ? '<span class="screen-reader-text">' + text + '</span>' : '';
		};

		$slider.flexslider( {
			animation: 'fade',
			controlNav: true,
			prevText: label( l10n.previous ),
			nextText: label( l10n.next ),
			smoothHeight: true,
		} );
	}

	$( function() {
		initBootstrapClasses();
		initScrollToTop();
	} );

	// The slider measures its images, so it waits for them to load.
	if ( 'complete' === document.readyState ) {
		initSlider();
	} else {
		$( window ).on( 'load', initSlider );
	}
}( jQuery ) );
