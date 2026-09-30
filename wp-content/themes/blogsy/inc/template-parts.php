<?php
/**
 * Template parts.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs the theme top bar area.
 *
 * @since 1.0.0
 */
function blogsy_topbar_output(): void {

	if ( ! blogsy_is_top_bar_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/header/topbar' );
}
add_action( 'blogsy_header', 'blogsy_topbar_output', 10 );

/**
 * Outputs the top bar widgets.
 *
 * @since 1.0.0
 * @param string $location Widget location in top bar.
 */
function blogsy_topbar_widgets_output( string $location ): void {

	do_action( 'blogsy_top_bar_widgets_before_' . $location );

	$blogsy_top_bar_widgets = Helper::get_option( 'top_bar_widgets' );

	if ( is_array( $blogsy_top_bar_widgets ) && [] !== $blogsy_top_bar_widgets ) {
		foreach ( $blogsy_top_bar_widgets as $widget ) {

			if ( ! isset( $widget['values'] ) ) {
				continue;
			}

			if ( $location !== $widget['values']['location'] ) {
				continue;
			}

			if ( function_exists( 'blogsy_top_bar_widget_' . $widget['type'] ) ) {

				$classes   = [];
				$classes[] = 'blogsy-topbar-widget__' . esc_attr( $widget['type'] );
				$classes[] = 'blogsy-topbar-widget';

				if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
					$classes[] = 'blogsy-' . esc_attr( $widget['values']['visibility'] );
				}

				$classes = apply_filters( 'blogsy_topbar_widget_classes', $classes, $widget );
				$classes = trim( implode( ' ', $classes ) );

				printf( '<div class="%s">', esc_attr( $classes ) );
				call_user_func( 'blogsy_top_bar_widget_' . $widget['type'], $widget['values'] );
				printf( '</div><!-- END .blogsy-topbar-widget -->' );
			}
		}
	}

	do_action( 'blogsy_top_bar_widgets_after_' . $location );
}
add_action( 'blogsy_topbar_widgets', 'blogsy_topbar_widgets_output' );

/**
 * Outputs the theme header area.
 *
 * @since 1.0.0
 */
function blogsy_header_output(): void {
	if ( ! blogsy_is_header_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/header/base' );
}
add_action( 'blogsy_header', 'blogsy_header_output', 20 );


/**
 * Outputs the header widgets in Header Widget Locations.
 *
 * @since 1.0.0
 * @param array $locations Widget locations.
 */
function blogsy_header_widgets( array $locations ): void {

	$all_widgets = (array) apply_filters( 'blogsy_main_header_selected_widgets', Helper::get_option( 'header_widgets' ) );
	blogsy_header_widget_output( $locations, $all_widgets );
}
add_action( 'blogsy_header_widget_location', 'blogsy_header_widgets', 1 );

/**
 * Outputs the content of theme header.
 *
 * @since 1.0.0
 */
function blogsy_header_content_output(): void {

	// Get the selected header layout from Customizer.
	$header_layout             = Helper::get_option( 'header_layout' );
	$post_id                   = blogsy_get_the_id();
	$single_page_header_layout = '';

	if ( $post_id ) {
		$single_page_header_layout = get_post_meta( blogsy_get_the_id(), 'blogsy_header_layout', true );
	}

	if ( 'disable' === $single_page_header_layout ) {
		return;
	}

	if ( $single_page_header_layout && '0' !== $single_page_header_layout ) {
		$header_layout = $single_page_header_layout;
	}

	?>
	<div class="pt-header-inner">
		<?php

		// Load header layout template.
		get_template_part( 'template-parts/header/header', $header_layout );

		?>
	</div><!-- END .pt-header-inner -->
	<?php
}
add_action( 'blogsy_header_content', 'blogsy_header_content_output' );

/**
 * Output the main navigation template.
 *
 * @since 1.0.0
 */
function blogsy_main_navigation_template(): void {
	$header_widgets = Helper::get_option( 'header_widgets' );
	$search_widget  = array_filter(
		$header_widgets,
		fn( array $widget ): bool => isset( $widget['type'] ) && 'search' === $widget['type'] && 3 === (int) $widget['values']['style']
	);
	$search_widget  = reset( $search_widget );
	get_template_part( 'template-parts/header/navigation', '', $search_widget ? $search_widget['values'] : [] );
}

/**
 * Output the Header logo template.
 *
 * @since 1.0.0
 */
function blogsy_header_logo_template(): void {
	get_template_part( 'template-parts/header/logo' );
}

/**
 * Outputs the theme Ticker News content.
 *
 * @since 1.0.0
 */
function blogsy_blog_ticker(): void {

	if ( ! blogsy_is_ticker_displayed() ) {
		return;
	}

	do_action( 'blogsy_before_ticker' );

	?>
	<div id="blogsy-ticker" <?php blogsy_ticker_classes(); ?>>
		<?php get_template_part( 'template-parts/ticker/ticker' ); ?>
	</div><!-- END #ticker -->
	<?php

	do_action( 'blogsy_after_ticker' );
}
add_action( 'blogsy_after_masthead', 'blogsy_blog_ticker', 30 );

/**
 * Outputs the theme blog hero content.
 *
 * @since 1.0.0
 */
function blogsy_blog_hero(): void {

	if ( ! blogsy_is_hero_displayed() ) {
		return;
	}

	// Get hero data prepared by business logic function.
	$hero_data = function_exists( 'blogsy_get_hero_data' ) ? blogsy_get_hero_data() : null;

	do_action( 'blogsy_before_hero' );

	?>
	<div id="blogsy-hero" <?php blogsy_hero_classes(); ?>>
		<?php
		if ( is_array( $hero_data ) && ! empty( $hero_data['type'] ) && 'page' === $hero_data['type'] && ! empty( $hero_data['page_id'] ) ) {
			get_template_part( 'template-parts/hero/hero', 'page', [ 'blogsy_hero_page_id' => $hero_data['page_id'] ] );
		} else {
			$hero_type = is_array( $hero_data ) && ! empty( $hero_data['type'] ) ? $hero_data['type'] : Helper::get_option( 'hero_type' );
			get_template_part( 'template-parts/hero/hero', $hero_type, is_array( $hero_data ) ? $hero_data : [] );
		}
		?>
	</div><!-- END #blogsy-hero -->
	<?php

	do_action( 'blogsy_after_hero' );
}
add_action( 'blogsy_after_masthead', 'blogsy_blog_hero', 30 );


/**
 * Outputs the theme Blog Stories content.
 *
 * @since 1.0.0
 */
function blogsy_blog_stories(): void {

	$data = blogsy_get_stories_data();
	if ( empty( $data['categories'] ) || is_page_template( 'stories.php' ) ) {
		return;
	}

	do_action( 'blogsy_before_stories' );

	?>
	<div id="blogsy-stories" <?php blogsy_stories_classes(); ?>>
		<?php get_template_part( 'template-parts/stories/stories', $data['style'], $data ); ?>
	</div><!-- END #blogsy-stories -->
	<?php

	do_action( 'blogsy_after_stories' );
}
add_action( 'blogsy_after_masthead', 'blogsy_blog_stories', 31 );


/**
 * Outputs the theme Blog Featured Category content.
 *
 * @since 1.0.0
 */
function blogsy_blog_featured_category(): void {

	$data = blogsy_get_featured_category_data();
	if ( empty( $data['features'] ) ) {
		return;
	}

	do_action( 'blogsy_before_featured_category' );

	?>
	<div id="blogsy-featured-category" <?php blogsy_featured_category_classes(); ?>>
		<?php if ( 'one' === $data['style'] || 'two' === $data['style'] ) : ?>
			<?php get_template_part( 'template-parts/featured-category/featured-category', 'one', $data ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/featured-category/featured-category', $data['style'], $data ); ?>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'blogsy_after_featured_category' );
}
add_action( 'blogsy_after_masthead', 'blogsy_blog_featured_category', 31 );


/**
 * Outputs the theme Blog Featured Links content.
 *
 * @since 1.0.0
 */
function blogsy_blog_featured_links(): void {

	$data = blogsy_get_featured_links_data();
	if ( empty( $data['features'] ) ) {
		return;
	}

	do_action( 'blogsy_before_featured_links' );

	?>
	<div id="blogsy-featured-links" <?php blogsy_featured_links_classes(); ?>>
		<?php if ( 'one' === $data['style'] || 'two' === $data['style'] || 'three' === $data['style'] ) : ?>
			<?php get_template_part( 'template-parts/featured-links/featured-links', 'one', $data ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/featured-links/featured-links', $data['style'], $data ); ?>
		<?php endif; ?>
	</div><!-- END #blogsy-featured-links -->
	<?php

	do_action( 'blogsy_after_featured_links' );
}
add_action( 'blogsy_after_masthead', 'blogsy_blog_featured_links', 31 );

/**
 * Outputs the theme Blog PYML content.
 *
 * @since 1.0.0
 */
function blogsy_blog_pyml(): void {

	$data = blogsy_get_pyml_data();
	if ( empty( $data['query_args'] ) ) {
		return;
	}

	do_action( 'blogsy_before_pyml' );

	?>
	<div id="blogsy-pyml" <?php blogsy_pyml_classes(); ?>>
		<?php get_template_part( 'template-parts/pyml/pyml', $data['style'], $data ); ?>
	</div><!-- END #blogsy-pyml -->
	<?php

	do_action( 'blogsy_after_pyml' );
}
add_action( 'blogsy_before_footer', 'blogsy_blog_pyml', 32 );

/**
 * Outputs the theme advertisement content.
 *
 * @since 1.0.0
 *
 * @param string $arg Advertisement location argument.
 */
function blogsy_advertisement_part( string $arg = '' ): void {

	if ( '' === $arg ) {
		return;
	}

	$ad_widgets = (array) Helper::get_option( 'ad_widgets' );

	// get all array elements from $ad_widgets in which 'display_area' key has value $arg = 'before_post_content'.
	$arr_widgets = array_filter(
		$ad_widgets,
		fn( array $widget ): bool => isset( $widget['values']['display_area'] ) && in_array( $arg, $widget['values']['display_area'] )
	);

	foreach ( $arr_widgets as $widget ) {
		if ( function_exists( 'blogsy_ad_widget_' . $widget['type'] ) ) {
			$classes   = [];
			$classes[] = 'blogsy-banner-widget__' . esc_attr( $widget['type'] );
			$classes[] = 'blogsy-banner-widget';

			if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
				$classes[] = 'blogsy-' . esc_attr( $widget['values']['visibility'] );
			}

			$classes = apply_filters( 'blogsy_ad_widget_classes', $classes, $widget );
			$classes = trim( implode( ' ', $classes ) );

			printf( '<div class="%s">', esc_attr( $classes ) );
			call_user_func( 'blogsy_ad_widget_' . $widget['type'], $widget['values'] );
			printf( '</div>' );
		}
	}
}
add_action( 'blogsy_before_single_content', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_after_single_content', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_before_masthead', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_after_masthead', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_before_footer', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_after_footer', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_header_4_ad', 'blogsy_advertisement_part', 10, 1 );
add_action( 'blogsy_before_content_area', 'blogsy_advertisement_part', 10, 1 );
