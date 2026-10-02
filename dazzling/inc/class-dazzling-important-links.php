<?php
/**
 * Customizer control: support and documentation links.
 *
 * Loaded by dazzling_customizer(), once WP_Customize_Control exists.
 *
 * @package dazzling
 */

/**
 * Support and documentation links in the Customizer.
 */
class Dazzling_Important_Links extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'dazzling-important-links';

	/**
	 * Print the links.
	 */
	public function render_content() {
		?>
		<div class="inside dazzling-important-links">
			<p><strong><a href="<?php echo esc_url( 'https://colorlib.com/wp/support/dazzling/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Dazzling Documentation', 'dazzling' ); ?></a></strong></p>
			<p>
				<?php esc_html_e( 'The best way to contact us with support questions and bug reports is via', 'dazzling' ); ?>
				<a href="<?php echo esc_url( 'https://colorlibsupport.com/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Colorlib support forum', 'dazzling' ); ?></a>.
			</p>
			<p><?php esc_html_e( 'If you like this theme, I\'d appreciate any of the following:', 'dazzling' ); ?></p>
			<ul>
				<li><a class="button" href="<?php echo esc_url( 'https://wordpress.org/support/theme/dazzling/reviews/#new-post' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Rate this Theme', 'dazzling' ); ?></a></li>
				<li><a class="button" href="<?php echo esc_url( 'https://www.facebook.com/colorlib' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Like on Facebook', 'dazzling' ); ?></a></li>
				<li><a class="button" href="<?php echo esc_url( 'https://x.com/colorlib' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Follow on Twitter', 'dazzling' ); ?></a></li>
			</ul>
		</div>
		<?php
	}
}
