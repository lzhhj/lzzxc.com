<?php
/**
 * Template part for displaying single post hero content - inside content
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
$standard_inside_layouts  = [ 1, 4, 8, 11 ]; // Standard layouts for inside place.
$standard_inside_title    = [ 2, 9 ]; // Title for inside place.

$position_to_content = \Blogsy\Helper::get_option( 'single_format_gallery_position' );
// Check for meta-box value and override if set.
$meta_position_to_content = get_post_meta( get_the_ID(), 'blogsy_single_gallery_position', true );
if ( ! empty( $meta_position_to_content ) ) {
	$position_to_content = $meta_position_to_content;
}

echo '<div class="single-hero-inside">';

if ( 'gallery' === $post_format ) {
	if ( 'inside' === $position_to_content ) {
		do_action( 'blogsy_post_hero_content_inside', 'gallery' );
	}

	get_template_part( 'template-parts/post/hero/title-section' );
} elseif ( 'video' === $post_format ) {
	do_action( 'blogsy_post_hero_content_inside', 'video' );
} elseif ( 'audio' === $post_format ) {
	do_action( 'blogsy_post_hero_content_inside', 'audio' );
} elseif ( 'link' === $post_format ) {
	do_action( 'blogsy_post_hero_content_inside', 'link' );
} elseif ( 'quote' === $post_format ) {
	do_action( 'blogsy_post_hero_content_inside', 'quote' );
} elseif ( 'standard' === $post_format && in_array( $standard_post_layout, $standard_inside_layouts ) ) {
	if ( 1 === $standard_post_layout ) {
		get_template_part( 'template-parts/post/hero/content-1' );
	} else {
		do_action( 'blogsy_post_hero_content_inside', $standard_post_layout );
	}
} elseif ( 'standard' === $post_format && in_array( $standard_post_layout, $standard_inside_title ) ) {
	get_template_part( 'template-parts/post/hero/title-section' );
} elseif ( 0 === $standard_post_layout ) {
	get_template_part( 'template-parts/post/hero/content-1' );
}

echo '</div>';
