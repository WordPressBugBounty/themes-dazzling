<?php
/**
 * The template for displaying search forms in Dazzling
 *
 * The form can appear several times on one page (sidebar, 404, no results),
 * so nothing in it carries a fixed id: the label points at a unique one.
 *
 * @package dazzling
 */

$dazzling_search_id = wp_unique_id( 'search-field-' );
?>
<form method="get" class="form-search" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="form-group">
		<div class="input-group">
			<label class="screen-reader-text" for="<?php echo esc_attr( $dazzling_search_id ); ?>"><?php echo esc_html_x( 'Search for:', 'label', 'dazzling' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $dazzling_search_id ); ?>" class="form-control search-query" placeholder="<?php echo esc_attr__( 'Search...', 'dazzling' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
			<span class="input-group-btn">
				<button type="submit" class="btn btn-default"><span class="fa-solid fa-magnifying-glass" aria-hidden="true"></span><span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'dazzling' ); ?></span></button>
			</span>
		</div>
	</div>
</form>
