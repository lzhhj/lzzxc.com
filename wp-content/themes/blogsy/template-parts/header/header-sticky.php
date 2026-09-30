<?php
/**
 * Template part for displaying sticky header
 *
 * @package Blogsy
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$singular_sticky_disabled = is_singular() && ( 'disable' === get_post_meta( get_the_ID(), 'blogsy_page_sticky_header', true ) );
?>
<?php if ( \Blogsy\Helper::get_option( 'sticky_header_status' ) && ! $singular_sticky_disabled ) : ?>
<header id="site-sticky-header">
	<?php
	// Singular Custom Header.
	$header_id = blogsy_get_layout_template_id( 'sticky_header' );
	$header    = blogsy_template_section_render( $header_id );

	if ( $header ) {
		echo apply_filters( 'blogsy_print_sticky_header_template', $header ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else { // Default header.
		get_template_part( 'template-parts/header/header-sticky-default' );
	}
	?>
</header>
<?php endif; ?>
