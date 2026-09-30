<?php
/**
 * Template part for displaying archive
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sidebar_position = blogsy_get_sidebar_position( 'blog', 'none' );

?>
<main id="main" class="main-wrapper">
	<div class="content-wrapper">
		<?php get_template_part( 'template-parts/archive/archive-title' ); ?>
		<?php
		$template_id = blogsy_get_layout_template_id( 'archive' );
		$template    = blogsy_template_section_render( $template_id );


		if ( $template && 'none' === $sidebar_position ) {
			echo apply_filters( 'blogsy_print_archive_template', $template ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			?>
			<div class="pt-container">
				<?php do_action( 'blogsy_before_content_area', 'before_post_archive' ); ?>
				<div class="pt-row page-content-wrapper <?php echo 'sidebar-' . esc_attr( $sidebar_position ); ?>">
					<div class="content-container archive-content-container">
						<?php
						if ( $template ) {
							echo apply_filters( 'blogsy_print_archive_template', $template ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							get_template_part( 'template-parts/archive/archive-default' );
						}
						?>
					</div>
					<?php if ( 'left' === $sidebar_position || 'right' === $sidebar_position ) : ?>
						<aside class="sidebar-container
						<?php
						if ( \Blogsy\Helper::get_option( 'sticky_sidebar' ) ) {
							echo 'sticky';
						}
						?>
						">
							<?php get_sidebar(); ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</main>
<?php
get_footer();
