<?php
/**
 * Template part for displaying single post hero content - outside content
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_format              = get_post_format() ? : 'standard';
$selected_standard_layout = intval( get_post_meta( get_the_ID(), 'blogsy_single_post_layout', true ) );
$global_standard_layout   = intval( \Blogsy\Helper::get_option( 'single_hero' ) );
$standard_post_layout     = $selected_standard_layout ? $selected_standard_layout : $global_standard_layout; // Current post selected layout.
$standard_outside_layouts = [ 2, 3, 5, 6, 7, 9, 10 ]; // Standard layouts for outside place.

$position_to_content = \Blogsy\Helper::get_option( 'single_format_gallery_position' );
// Check for meta-box value and override if set.
$meta_position_to_content = get_post_meta( get_the_ID(), 'blogsy_single_gallery_position', true );
if ( ! empty( $meta_position_to_content ) ) {
	$position_to_content = $meta_position_to_content;
}

echo '<div class="single-hero-outside">';

if ( 'gallery' === $post_format && 'outside' === $position_to_content ) {
	do_action( 'blogsy_post_hero_content_outside', 'gallery' );
	get_template_part( 'template-parts/post/hero/content-gallery' );
} elseif ( 'standard' === $post_format && in_array( $standard_post_layout, $standard_outside_layouts, true ) ) {
	if ( 2 === $standard_post_layout || 3 === $standard_post_layout ) {
		get_template_part( 'template-parts/post/hero/content-' . $standard_post_layout );
	} else {
		do_action( 'blogsy_post_hero_content_outside', $standard_post_layout );
	}
}

echo '</div>';
