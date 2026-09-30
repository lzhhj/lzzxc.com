<?php
/**
 * Template part for displaying page 404
 *
 * @package Blogsy
 */

get_header();
?>
	<main id="main" class="main-wrapper">
		<div class="content-wrapper">
			<?php
			$template_id = blogsy_get_layout_template_id( '404');
			$template    = blogsy_template_section_render( $template_id );

			if ( $template ) {
				echo apply_filters( 'blogsy_print_404_template', $template ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				get_template_part( 'template-parts/404-default' );
			}
			?>
		</div>
	</main>
<?php
get_footer();
