<?php
/**
 * Template part for displaying sidebar content
 *
 * @package Blogsy
 */

$cls = '';

$template_id = blogsy_get_layout_template_id( 'sidebar' );
$sidebar     = blogsy_template_section_render( $template_id );

if ( $sidebar ) {
	$cls = 'elementor-sidebar';
} elseif ( is_active_sidebar( 'sidebar-1' ) ) {
	$cls = 'wp-sidebar';
}
?>
<div class="sidebar-container-inner <?php echo esc_attr( $cls ); ?>">
	<?php
	if ( 'elementor-sidebar' === $cls ) {
		echo apply_filters( 'blogsy_print_sidebar_template', $sidebar ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} elseif ( 'wp-sidebar' === $cls ) {
		dynamic_sidebar( 'sidebar-1' );
	}
	?>
</div>
