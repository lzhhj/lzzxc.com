<?php
/**
 * Template part for displaying footer
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since 1.0.0
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php
// Hook for actions before footer.
do_action( 'blogsy_before_footer', 'before_footer' );
?>
<footer id="site-footer" class="<?php echo apply_filters( 'blogsy_footer_classes', 'blogsy-site-footer' ); ?>">
	<?php
	// Singular Custom Footer.
	$footer_id = blogsy_get_layout_template_id( 'footer' );
	$footer    = blogsy_template_section_render( $footer_id );

	$blogsy_footer_copyright_textarea = Helper::get_option( 'footer_copyright_textarea' );
	$is_elementor_page                = class_exists( 'Elementor\Plugin' ) && (bool) get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

	if ( $footer ) {
		echo apply_filters( 'blogsy_print_footer_template', $footer ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		?>
		<div class="site-default-footer">
			<div class="pt-container">
				<?php blogsy_footer_widgets(); ?>
				<?php if ( ! empty( $blogsy_footer_copyright_textarea ) ) { ?>
				<div class="pt-row">
					<div class="pt-col-12">
						<div class="default-footer-copyright">
							<?php echo wp_kses_post( apply_filters( 'blogsy_dynamic_strings', $blogsy_footer_copyright_textarea ) ); ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php
		if ( ! is_admin() && $is_elementor_page ) {
			// Load wp-block-library CSS on Elementor pages.
			wp_enqueue_style( 'wp-block-library' );
		}
	}
	?>
</footer>
<?php
do_action( 'blogsy_after_footer', 'after_footer' ); // Hook for actions after footer.
