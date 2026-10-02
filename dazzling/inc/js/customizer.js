/**
 * Live preview for the Site Identity settings that use postMessage: the site
 * title, tagline and header text colour.
 *
 * Mirrors what dazzling_header_style() prints, so the preview and the
 * published site agree: the colour applies to the site title only, and
 * hiding the header text hides the title text and tagline but not a header
 * image used as the logo.
 */
( function( $, api ) {
	'use strict';

	var hidden = {
		position: 'absolute',
		clip: 'rect(1px, 1px, 1px, 1px)',
		'clip-path': 'inset(50%)',
		width: '1px',
		height: '1px',
		margin: '-1px',
		padding: '0',
		border: '0',
		overflow: 'hidden',
	};
	var shown = {
		position: '',
		clip: '',
		'clip-path': '',
		width: '',
		height: '',
		margin: '',
		padding: '',
		border: '',
		overflow: '',
	};

	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.navbar-brand' ).text( to );
		} );
	} );

	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			// The printed rule would otherwise win over the inline styles below.
			$( '#dazzling-header-text' ).remove();

			if ( 'blank' === to ) {
				$( '.navbar-brand, .site-description' ).css( hidden );
			} else {
				$( '.navbar-brand, .site-description' ).css( shown );
				$( '.navbar > .container .navbar-brand' ).css( 'color', to );
			}
		} );
	} );
}( jQuery, wp.customize ) );
