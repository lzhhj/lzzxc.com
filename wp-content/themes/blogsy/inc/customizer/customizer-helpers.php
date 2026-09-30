<?php
/**
 * Blogsy Customizer helper functions.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns array of available widgets.
 *
 * @since 1.0.0
 * @return array, $widgets array of available widgets.
 */
function blogsy_get_customizer_widgets(): array {

	$widgets = [
		'text'           => 'Blogsy_Customizer_Widget_Text',
		'advertisements' => 'Blogsy_Customizer_Widget_Advertisements',
		'nav'            => 'Blogsy_Customizer_Widget_Nav',
		'socials'        => 'Blogsy_Customizer_Widget_Socials',
		'search'         => 'Blogsy_Customizer_Widget_Search',
		'darkmode'       => 'Blogsy_Customizer_Widget_Darkmode',
		'offcanvas'       => 'Blogsy_Customizer_Widget_Offcanvas',
		'button'         => 'Blogsy_Customizer_Widget_Button',
	];

	return apply_filters( 'blogsy_customizer_widgets', $widgets );
}

/**
 * Get choices for "Hide on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function blogsy_get_display_choices(): array {

	// Default options.
	$return = [
		'home'       => [
			'title' => esc_html__( 'Home Page', 'blogsy' ),
		],
		'posts_page' => [
			'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ),
		],
		'search'     => [
			'title' => esc_html__( 'Search', 'blogsy' ),
		],
		'archive'    => [
			'title' => esc_html__( 'Archive', 'blogsy' ),
			'desc'  => esc_html__( 'Dynamic pages such as categories, tags, custom taxonomies...', 'blogsy' ),
		],
		'post'       => [
			'title' => esc_html__( 'Single Post', 'blogsy' ),
		],
		'page'       => [
			'title' => esc_html__( 'Single Page', 'blogsy' ),
		],
	];

	// Get additionally registered post types.
	$post_types = get_post_types(
		[
			'public'   => true,
			'_builtin' => false,
		],
		'objects'
	);

	if ( is_array( $post_types ) && [] !== $post_types ) {
		foreach ( $post_types as $slug => $post_type ) {
			$return[ $slug ] = [
				'title' => $post_type->label,
			];
		}
	}

	return apply_filters( 'blogsy_display_choices', $return );
}

/**
 * Get device choices for "Display on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function blogsy_get_device_choices(): array {

	// Default options.
	$return = [
		'desktop' => [
			'title' => esc_html__( 'Hide On Desktop', 'blogsy' ),
		],
		'tablet'  => [
			'title' => esc_html__( 'Hide On Tablet', 'blogsy' ),
		],
		'mobile'  => [
			'title' => esc_html__( 'Hide On Mobile', 'blogsy' ),
		],
	];

	return apply_filters( 'blogsy_device_choices', $return );
}
