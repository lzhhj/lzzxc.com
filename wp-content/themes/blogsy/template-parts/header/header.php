<?php
/**
 * Template part for displaying header
 *
 * @package Blogsy
 * @since 1.0.0
 */

?>

<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php do_action( 'blogsy_before_masthead', 'before_header' ); ?>
<header id="site-header">
<?php
// Singular Custom Header.
$header_id     = blogsy_get_layout_template_id( 'header' );
$header        = blogsy_template_section_render( $header_id );
$header_preset = \Blogsy\Helper::get_option( 'header_present' );
if ( $header && ( 'custom' === $header_preset ) ) {
	echo apply_filters( 'blogsy_print_header_template', $header ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
} else { // Default header.
	do_action( 'blogsy_header' );
}
?>
</header>
<?php get_template_part( 'template-parts/header/header-sticky' ); ?>
<?php do_action( 'blogsy_after_masthead', 'after_header' ); ?>
