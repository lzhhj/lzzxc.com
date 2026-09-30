<?php
/**
 * Blogsy functions and definitions.
 *
 * @since 1.0.0
 * @package Blogsy
 */

use Blogsy\Admin\Utilities\Plugin_Utilities;
use Blogsy\Dynamic_Styles;
use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if a page is built using elementor.
 *
 * @since 1.0.0
 *
 * @param int $post_id post or page id.
 * @return boolean
 */
function blogsy_is_elementor_page( int $post_id ): bool {
	if ( did_action( 'elementor/loaded' ) ) {
		$document = \Elementor\Plugin::instance()->documents->get( $post_id );
		return ( $document && $document->is_built_with_elementor() );
	}
	return false; // Elementor not active.
}

/**
 * Get Elementor Content to Display
 *
 * @param  int $post_id Post ID.
 * @return string
 */
function blogsy_get_display_elementor_content( int $post_id ): string {

	if ( ! class_exists( 'Elementor\Plugin' ) ) {
		return '';
	}

	$plugin_elementor = \Elementor\Plugin::instance();

	return $plugin_elementor->frontend->get_builder_content_for_display( $post_id );
}

/**
 * Render custom section content before/after Blogsy sections.
 *
 * @since 1.0.0
 * @param int $template_id Blogsy template id.
 * @return null|string Rendered content or null if no template found.
 */
function blogsy_template_section_render( int $template_id ): ?string {
	if ( ! $template_id ) {
		return null;
	}
	// Get the blogsy-template post by id.
	$template = get_post( $template_id );
	if ( ! $template ) {
		return null;
	}
	// Check if built using Elementor.
	if ( blogsy_is_elementor_page( $template->ID ) ) {
		// Retrieve Elementor content.
		$content = blogsy_get_display_elementor_content( $template->ID );
	} else {
		// Retrieve normal post content.
		$content = do_shortcode( do_blocks( $template->post_content ) );
	}

	return $content;
}
/**
 * Get template id of layout
 *
 * @since 1.0.0
 * @param string $layout Layout name.
 * @return int Template ID.
 */
function blogsy_get_layout_template_id( string $layout ): int {

	$template_id = 0;
	if ( ! $layout || ! class_exists( '\Blogsy\Addons\Helper' ) ) {
		return $template_id;
	}
	$layout = strtolower( $layout );
	return \Blogsy\Addons\Helper::get_layout_template_id( $layout );
}


/**
 * Get Sidebar Position
 *
 * @since 1.0
 *
 * @param string $sidebar_for     The context for which the sidebar position is being retrieved (e.g., 'single_post', 'single_page').
 * @param string $default_pos The default sidebar position to return if no specific position is set.
 *
 * @return string The sidebar position ('left', 'right', 'none', 'none-narrow', 'elementor').
 */
function blogsy_get_sidebar_position( string $sidebar_for, string $default_pos ): string {

	// Default Sidebar Position for theme settings.
	$sidebar_position = Helper::get_option( $sidebar_for . '_sidebar_position' );
	switch ( $sidebar_position ) {
		case 'left':
		case 'right':
		case 'none':
		case 'none-narrow':
			break;
		default:
			$sidebar_position = 'right';
	}

	// Custom Sidebar Position for the post.
	if ( 'single_post' === $sidebar_for || 'single_page' === $sidebar_for ) {
		switch ( get_post_meta( get_the_ID(), 'blogsy_page_sidebar', true ) ) {
			case 'left':
				$sidebar_position = 'left';
				break;
			case 'right':
				$sidebar_position = 'right';
				break;
			case 'none':
				$sidebar_position = 'none';
				break;
			case 'none-narrow':
				$sidebar_position = 'none-narrow';
				break;
			case 'elementor':
				$sidebar_position = 'elementor';
				break;
		}
	}

	// Check Sidebar For Content.
	if ( in_array( $sidebar_position, [ 'left', 'right' ], true ) ) {
		$sidebar_template_id = blogsy_get_layout_template_id( 'sidebar' );
		if ( ! $sidebar_template_id && ! is_active_sidebar( 'sidebar-1' ) ) {
			$sidebar_position = $default_pos;
		}
	}

	return $sidebar_position;
}

/**
 * Calculate Post Reading Time
 *
 * @param mixed $post Post ID or WP_Post object.
 * @return int|false Estimated reading time in minutes, or false if post is invalid.
 * @since 1.0.0
 */
function blogsy_get_reading_time( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$custom_reading_time = absint( get_post_meta( get_the_ID(), 'blogsy_single_reading_time', true ) );
	if ( $custom_reading_time ) {
		return $custom_reading_time;
	}

	$words_per_minute = absint( Helper::get_option( 'reading_time_words_per_minute' ) );
	$words_per_minute = $words_per_minute ?: 255;

	$content          = get_post_field( 'post_content', $post );
	$number_of_images = substr_count( strtolower( $content ), '<img ' );

	$content    = wp_strip_all_tags( $content );
	$word_count = count( preg_split( '/\s+/', $content ) );

	// Each image is like 25 words.
	$word_count += $number_of_images * 25;

	$reading_time = $word_count / $words_per_minute;

	return ceil( $reading_time );
}


/**
 * Print post format icon in default archive
 *
 * @since 1.0.0
 */
function blogsy_post_format_icon(): void {

	$post_format = get_post_format() ? : 'standard';

	if ( 'standard' === $post_format ) {
		return;
	}

	switch ( $post_format ) {
		case 'images':
			$post_format_icon = \Blogsy\Icon::get_svg( 'gallery', '', [ 'aria-hidden' => 'true' ] );
			break;
		case 'video':
			$post_format_icon = \Blogsy\Icon::get_svg( 'video', '', [ 'aria-hidden' => 'true' ] );
			break;
		case 'audio':
			$post_format_icon = \Blogsy\Icon::get_svg( 'audio', '', [ 'aria-hidden' => 'true' ] );
			break;
		case 'link':
			$post_format_icon = \Blogsy\Icon::get_svg( 'link', '', [ 'aria-hidden' => 'true' ] );
			break;
		case 'quote':
			$post_format_icon = \Blogsy\Icon::get_svg( 'quote', '', [ 'aria-hidden' => 'true' ] );
			break;
		default:
			$post_format_icon = \Blogsy\Icon::get_svg( 'gallery', '', [ 'aria-hidden' => 'true' ] );
	}

	?>
	<div class="post-format-icon blogsy-position-top-left">
		<?php echo \Blogsy\Icon::sanitize_svg( $post_format_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
}

if ( ! function_exists( 'blogsy_get_post_views' ) ) {
	/**
	 * Get post views
	 *
	 * @since 1.0.0
	 * @param int $post_ID Post ID.
	 * @return int Post views.
	 */
	function blogsy_get_post_views( $post_ID ) {
		$count_key = 'post_views';
		$count     = intval( get_post_meta( $post_ID, $count_key, true ) );
		if ( $count > 999 ) {
			$count = substr( $count, 0, -2 ) / 10 . 'K';
		}
		return $count;
	}
}


/**
 * Get the post excerpt with a specific length.
 *
 * @since 1.0.0
 *
 * @param int $length Length of excerpt.
 * @return string The post excerpt.
 */
function blogsy_get_the_excerpt( int $length = 100 ): string {

	// return empty string if length is 0.
	if ( 0 === $length ) {
		return '';
	}
	$excerpt = get_the_excerpt();
	if ( $excerpt ) {
		$excerpt_more = Helper::get_option( 'excerpt_more' );
		$excerpt      = wp_strip_all_tags( $excerpt );
		$excerpt      = str_replace( '[...]', '', $excerpt );
		$excerpt      = trim( $excerpt );

		if ( strlen( $excerpt ) > $length ) {
			$excerpt = function_exists( 'mb_substr' ) ? mb_substr( $excerpt, 0, $length ) : substr( $excerpt, 0, $length );
			if ( strpos( $excerpt, ' ' ) ) {
				$excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
			}
			$excerpt .= $excerpt_more;
		}
	}
	return $excerpt;
}


/**
 * Display no posts found message
 *
 * @since 1.0.0
 */
function blogsy_query_not_found_msg(): void {
	?>
	<div class="nothing-show">
		<h5><?php echo esc_html( Helper::get_option( 'translate_nothing_found' ) ) ?: esc_html__( 'Nothing found!', 'blogsy' ); ?></h5>
		<p><?php echo esc_html( Helper::get_option( 'translate_looks_like_nothing_found' ) ) ?: esc_html__( 'It looks like nothing was found here!', 'blogsy' ); ?></p>
	</div>
	<?php
}


/**
 * Check Dark mode enabled
 *
 * @since 1.0.0
 */
function blogsy_dark_mode_enabled(): bool {
	return boolval( Helper::get_option( 'dark_mode' ) );
}


/**
 * Check Dark mode current status
 *
 * @since 1.0.0
 * @return light|dark|device Current theme scheme.
 */
function blogsy_current_theme_scheme() {

	$status = 'light';
	if ( blogsy_dark_mode_enabled() ) {

		if ( Helper::get_option( 'always_dark_mode' ) ) {
			$status = 'dark';
		} elseif ( ! empty( $_COOKIE['blogsyDarkMode'] ) && 'enabled' === $_COOKIE['blogsyDarkMode'] ) {
			$status = 'dark';
		} elseif ( empty( $_COOKIE['blogsyDarkMode'] ) ) {
			$status = Helper::get_option( 'default_theme_scheme' );
		}
	}
	return $status;
}


/**
 * Check if the current request is for an AMP page.
 *
 * @since 1.0.0
 * @return boolean True if AMP page, false otherwise.
 */
function blogsy_is_amp(): bool {

	return function_exists( 'amp_is_request' ) && amp_is_request();
}

/**
 * Get post ID.
 *
 * @since  1.0.0
 * @return int Current post/page ID.
 */
function blogsy_get_the_id(): int {

	$post_id = 0;

	if ( is_home() && 'page' === get_option( 'show_on_front' ) ) {
		$post_id = get_option( 'page_for_posts' );
	} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
		$post_id = get_option( 'page_on_front' );
	} elseif ( is_singular() ) {
		$post_id = get_the_ID();
	}

	return apply_filters( 'blogsy_get_the_id', $post_id );
}



/**
 * The function which returns the one Blogsy_Plugin_Utilities instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $blogsy_plugin_utilities = blogsy_plugin_utilities(); ?>
 *
 * @since 1.0.0
 * @return Plugin_Utilities
 */
function blogsy_plugin_utilities(): Plugin_Utilities {
	return Plugin_Utilities::instance();
}

/**
 * The function which returns the one Dynamic_Styles instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $dynamic_styles = blogsy_dynamic_styles(); ?>
 *
 * @since 1.0.0
 * @return Dynamic_Styles
 */
function blogsy_dynamic_styles(): Dynamic_Styles {
	return Dynamic_Styles::instance();
}

/**
 * Add classes to Top Bar.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_top_bar_classes( array $classes = [] ): void {

	// Top Bar visibility.
	$top_bar_visibility = Helper::get_option( 'top_bar_visibility' );

	if ( 'all' !== $top_bar_visibility ) {
		$classes[] = 'blogsy-' . $top_bar_visibility;
	}

	$classes = apply_filters( 'blogsy_top_bar_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo esc_attr( $classes );
	}
}

/**
 * Add classes to Hero.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_hero_classes( array $classes = [] ): void {

	// Hero visibility.
	$visibility = Helper::get_option( 'hero_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_hero_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}


/**
 * Get Hero Data.
 *
 * Moves hero business logic out of templates so templates only render data.
 *
 * @since 1.0.0
 * @return array|null
 */
function blogsy_get_hero_data(): ?array {

	if ( ! blogsy_is_hero_displayed() ) {
		return null;
	}

	$hero_type    = Helper::get_option( 'hero_type' );
	$hero_page_id = Helper::get_option( 'hero_page' );

	// If a page is selected for the hero, return only page id.
	if ( $hero_page_id ) {
		return [
			'type'    => 'page',
			'page_id' => (int) $hero_page_id,
		];
	}

	$hero_slider_orderby = Helper::get_option( 'hero_slider_orderby' );
	$hero_slider_order   = explode( '-', $hero_slider_orderby );

	$query_args = [
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => Helper::get_option( 'hero_slider_post_number' ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
		'order'               => $hero_slider_order[1] ?? 'DESC',
		'orderby'             => $hero_slider_order[0] ?? 'date',
		'ignore_sticky_posts' => true,
	];

	$hero_categories = Helper::get_option( 'hero_slider_category' );
	$hero_tags       = Helper::get_option( 'hero_slider_tags' );

	// Initialize the tax_query with 'OR' relation.
	$tax_query = [ 'relation' => 'OR' ];

	if ( ! empty( $hero_categories ) ) {
		$tax_query[] = [
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $hero_categories,
			'operator' => 'IN',
		];
	}

	if ( ! empty( $hero_tags ) ) {
		$tax_query[] = [
			'taxonomy' => 'post_tag',
			'field'    => 'term_id',
			'terms'    => $hero_tags,
			'operator' => 'IN',
		];
	}

	$query_args['tax_query'] = $tax_query;

	$query_args = apply_filters( 'blogsy_hero_slider_query_args', $query_args );

	// Elements and other rendering options.
	$elements = (array) Helper::get_option( 'hero_slider_elements' );

	// Default slider settings (can be filtered).
	$slider_settings = apply_filters(
		'blogsy_hero_slider_settings',
		[
			'autoplay'     => [ 'delay' => 4000 ],
			'loop'         => true,
			'speed'        => 1000,
			'effect'       => 'fade',
			'parallax'     => true,
			'loopedSlides' => 6,
			'touchRatio'   => 0.2,
			'navigation'   => [
				'nextEl' => '#blogsy-hero .carousel-nav-next',
				'prevEl' => '#blogsy-hero .carousel-nav-prev',
			],
			'pagination'   => [
				'el'             => '#blogsy-hero .carousel-pagination',
				'type'           => 'bullets',
				'clickable'      => true,
				'dynamicBullets' => false,
			],
			'a11y'         => [ 'enabled' => false ],
			'fadeEffect'   => [ 'crossFade' => true ],
		]
	);

	// Default thumbs slider settings (can be filtered).
	$thumbs_slider_settings = apply_filters(
		'blogsy_hero_thumbs_slider_settings',
		[
			'loop'                => true,
			'speed'               => 1000,
			'slideToClickedSlide' => true,
			'direction'           => 'horizontal',
			'touchRatio'          => 0.2,
			'spaceBetween'        => 10,
			'loopedSlides'        => 6,
			'slidesPerView'       => 3,
			'breakpoints'         => [
				'0'    => [
					'spaceBetween' => 7,
				],
				'1025' => [
					'spaceBetween' => 10,
				],
			],
		]
	);

	return apply_filters(
		'blogsy_hero_data',
		[
			'type'                   => $hero_type ?: 'one',
			'query_args'             => $query_args,
			'elements'               => $elements,
			'slider_settings'        => $slider_settings,
			'thumbs_slider_settings' => $thumbs_slider_settings,
		]
	);
}


/**
 * Add classes to Ticker News.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_ticker_classes( array $classes = [] ): void {

	// Ticker News visibility.
	$visibility = Helper::get_option( 'ticker_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_ticker_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Add classes to Featured category.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_featured_category_classes( array $classes = [] ): void {

	// Hero visibility.
	$visibility = Helper::get_option( 'featured_category_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_featured_category_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Get Featured Category Data.
 *
 * @since 1.0.0
 * @return array
 */
function blogsy_get_featured_category_data(): ?array {
	if ( ! blogsy_is_featured_category_displayed() ) {
		return null;
	}

	$style      = \Blogsy\Helper::get_option( 'featured_category_style' );
	$column     = \Blogsy\Helper::get_option( 'featured_category_column' );
	$title      = \Blogsy\Helper::get_option( 'featured_category_title' );
	$categories = \Blogsy\Helper::get_option( 'featured_category' );

	if ( empty( $categories ) ) {
		return null;
	}

	$features = [];
	foreach ( $categories as $cat ) {
		if ( empty( $cat['category'] ) ) {
			continue;
		}
		$features[] = [
			'category' => $cat['category'],
			'image'    => $cat['image'],
			'color'    => $cat['color'] ?? \Blogsy\Helper::get_option( 'accent_color' ),
		];
	}

	return [
		'style'    => $style,
		'column'   => $column,
		'title'    => $title,
		'features' => $features,
	];
}


/**
 * Add classes to Featured links.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_featured_links_classes( array $classes = [] ): void {

	// Hero visibility.
	$visibility = Helper::get_option( 'featured_links_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_featured_links_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Get Featured Links Data.
 *
 * @since 1.0.0
 */
function blogsy_get_featured_links_data(): ?array {

	if ( ! blogsy_is_featured_links_displayed() ) {
		return null;
	}

	// Featured links type.
	$style  = \Blogsy\Helper::get_option( 'featured_links_style' );
	$column = \Blogsy\Helper::get_option( 'featured_links_column' );
	$title  = \Blogsy\Helper::get_option( 'featured_links_title' );
	$links  = \Blogsy\Helper::get_option( 'featured_links' );

	// No items found.
	if ( ! $links ) {
		return null;
	}

	$features = [];

	foreach ( $links as $link ) {
		$features[] = [
			'link'  => $link['link'],
			'image' => $link['image'],
			'color' => $link['color'] ?? \Blogsy\Helper::get_option( 'accent_color' ),
		];
	}

	return [
		'style'    => $style,
		'column'   => $column,
		'title'    => $title,
		'features' => $features,
	];
}

/**
 * Add classes to Stories.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_stories_classes( array $classes = [] ): void {

	// Stories visibility.
	$visibility = Helper::get_option( 'stories_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_stories_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Get Stories Data.
 *
 * @since 1.0.0
 */
function blogsy_get_stories_data(): ?array {

	if ( ! blogsy_is_stories_displayed() && ! is_page_template( 'stories.php' ) ) {
		return null;
	}

	$stories_title    = Helper::get_option( 'stories_title' );
	$stories_view_all = Helper::get_option( 'stories_view_all' );
	$stories_style    = Helper::get_option( 'stories_style' );

	$stories_orderby         = explode( '-', Helper::get_option( 'stories_orderby' ) );
	$stories_max_category    = Helper::get_option( 'stories_max_category' );
	$stories_max_inner_items = Helper::get_option( 'stories_max_inner_items' );
	$stories_categories      = Helper::get_option( 'stories_category' );
	$stories_elements        = Helper::get_option( 'stories_elements' );

	$categories = get_categories(
		[
			'number'  => absint( $stories_max_category ),
			'include' => ( ! empty( $stories_categories ) ) ? $stories_categories : [],
		]
	);

	return [
		'title'           => $stories_title,
		'view_all'        => $stories_view_all,
		'style'           => $stories_style,
		'categories'      => $categories,
		'max_category'    => absint( $stories_max_category ),
		'max_inner_items' => absint( $stories_max_inner_items ),
		'orderby'         => $stories_orderby[0],
		'order'           => $stories_orderby[1],
		'elements'        => $stories_elements,
	];
}

/**
 * Add classes to PYML.
 *
 * @param array $classes Classes array.
 * @since 1.0.0
 */
function blogsy_pyml_classes( array $classes = [] ): void {

	// Pyml visibility.
	$visibility = Helper::get_option( 'pyml_visibility' );

	if ( 'all' !== $visibility ) {
		$classes[] = 'blogsy-' . $visibility;
	}

	$classes = apply_filters( 'blogsy_pyml_classes', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );
		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Get PYML Data.
 *
 * @since 1.0.0
 */
function blogsy_get_pyml_data(): ?array {

	if ( ! blogsy_is_pyml_displayed() ) {
		return null;
	}

	$pyml_title = Helper::get_option( 'pyml_title' );
	$pyml_style = Helper::get_option( 'pyml_style' );

	$pyml_orderby     = explode( '-', Helper::get_option( 'pyml_orderby' ) );
	$pyml_post_number = Helper::get_option( 'pyml_post_number' );
	$pyml_categories  = Helper::get_option( 'pyml_category' );
	$pyml_tags        = Helper::get_option( 'pyml_tags' );
	$pyml_elements    = Helper::get_option( 'pyml_elements' );

	$pyml_query_args = [
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $pyml_post_number,
		'order'               => $pyml_orderby[1],
		'orderby'             => $pyml_orderby[0],
		'ignore_sticky_posts' => true,
	];

	// Initialize the tax_query with 'OR' relation.
	$tax_query = [
		'relation' => 'OR', // This creates the "OR" condition.
	];

	// If categories are specified.
	if ( ! empty( $pyml_categories ) ) {
		$tax_query[] = [
			'taxonomy' => 'category',
			'field'    => 'term_id', // You can use 'name' or 'id' too.
			'terms'    => $pyml_categories,
			'operator' => 'IN',
		];
	}

	// If tags are specified.
	if ( ! empty( $pyml_tags ) ) {
		$tax_query[] = [
			'taxonomy' => 'post_tag',
			'field'    => 'term_id', // You can use 'name' or 'id' too.
			'terms'    => $pyml_tags,
			'operator' => 'IN',
		];
	}

	// Add the tax_query to the arguments.
	$pyml_query_args['tax_query'] = $tax_query;

	$pyml_query_args = apply_filters( 'blogsy_pyml_query_args', $pyml_query_args );

	return [
		'title'           => $pyml_title,
		'style'           => $pyml_style,
		'query_args'      => $pyml_query_args,
		'elements'        => $pyml_elements,
		'slider_settings' => apply_filters(
			'blogsy_pyml_slider_settings',
			[
				'autoplay'       => [ 'delay' => 4000 ],
				'loop'           => true,
				'speed'          => 500,
				'slidesPerView'  => 4,
				'slidesPerGroup' => 1,
				'spaceBetween'   => 25,
				'centeredSlides' => true,
				'effect'         => 'slide',
				'autoHeight'     => false,
				'direction'      => 'horizontal',
				'breakpoints'    => [
					'0'    => [
						'slidesPerView' => 1.4,
						'spaceBetween'  => 10,
					],
					'768'  => [
						'slidesPerView' => 2.4,
						'spaceBetween'  => 20,
					],
					'1025' => [
						'slidesPerView' => 3.4,
						'spaceBetween'  => 20,
					],
					'1200' => [
						'slidesPerView' => 4.2,
						'spaceBetween'  => 20,
					],
					'1400' => [ 'slidesPerView' => 5.4 ],
				],
				'navigation'     => [
					'nextEl' => '#blogsy-pyml .carousel-nav-next',
					'prevEl' => '#blogsy-pyml .carousel-nav-prev',
				],
				'pagination'     => [
					'el'             => '#blogsy-pyml .carousel-pagination',
					'type'           => 'fraction',
					'clickable'      => true,
					'dynamicBullets' => false,
				],
				'a11y'           => [ 'enabled' => false ],
				'fadeEffect'     => [ 'crossFade' => true ],
			]
		),
	];
}


/**
 * Get Post Categories.
 *
 * @since 1.0.0
 */
function blogsy_get_post_categories(): array {
	$categories = get_categories(
		[
			'hide_empty' => true,
		]
	);

	$cat_array = [];

	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		foreach ( $categories as $category ) {
			$cat_array[ $category->term_id ] = $category->name;
		}
	}

	return $cat_array;
}


/**
 * Algorithm to push ads into archive
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'blogsy_algorithm_to_push_ads_in_archive' ) ) :
	/**
	 * Algorithm to push ads into archive
	 *
	 * @since 1.0.0
	 */
	function blogsy_algorithm_to_push_ads_in_archive() {
		global $wp_query;

		$ad_widgets = array_filter(
			(array) Helper::get_option( 'ad_widgets' ),
			fn( array $widget ): bool => isset( $widget['values']['display_area'] ) && in_array( 'random_post_archives', $widget['values']['display_area'], true )
		);

		$archive_ads_number = count( $ad_widgets );

		if ( $archive_ads_number <= 0 || ! is_numeric( $archive_ads_number ) ) {
			return false;
		}

		$max_number_of_pages = absint( $wp_query->max_num_pages );
		$paged               = absint( ( 0 === get_query_var( 'paged' ) ) ? 0 : ( get_query_var( 'paged' ) - 1 ) );
		$count               = 1;
		$ads_id              = 0;
		$loop_var            = 0;
		for ( $i = $archive_ads_number; $i > 0; $i-- ) :
			if ( $count <= $max_number_of_pages ) :
				$ads_to_render_in_a_single_page = ceil( $i / $max_number_of_pages );
				$ads_to_render_by_page[]        = ceil( $i / $max_number_of_pages );
				$ads_to_render                  = [];
				if ( $ads_to_render_in_a_single_page > 1 ) :
					$to_loop = $ads_id + $ads_to_render_in_a_single_page;
					for ( $j = $ads_id; $j < $to_loop; $j++ ) :
						if ( ! in_array( $ads_id, $ads_to_render ) ) {
							$ads_to_render[] = $ads_id;
						}
						++$ads_id;
					endfor;
					$ads_to_render_in_current_page[ $loop_var ] = $ads_to_render;
				else :
					$ads_to_render_in_current_page[ $loop_var ] = $ads_id;
					++$ads_id;
				endif;
				++$count;
				++$loop_var;
			endif;
		endfor;
		$current_page_count  = absint( $wp_query->post_count );
		$ads_of_current_page = $ads_to_render_in_current_page[ $paged ] ?? null;
		$ads_count           = is_array( $ads_of_current_page ) ? count( $ads_of_current_page ) : 1;
		$random_numbers      = [];
		for ( $i = 0; $i < $ads_count; $i++ ) :
			$random_numbers[] = wp_rand( 0, ( $current_page_count - 1 ) );
		endfor;
		return [
			'random_numbers' => $random_numbers,
			'ads_to_render'  => $ads_of_current_page,
		];
	}


endif;


/**
 * Get all the registered image sizes along with their dimensions.
 *
 * @since 1.0.0
 * @return array $image_sizes The image sizes
 */
function blogsy_get_image_sizes(): array {
	global $_wp_additional_image_sizes;

	$default_image_sizes = get_intermediate_image_sizes();

	foreach ( $default_image_sizes as $size ) {
		$image_sizes[ $size ]['width']  = intval( get_option( $size . '_size_w' ) );
		$image_sizes[ $size ]['height'] = intval( get_option( $size . '_size_h' ) );
		$image_sizes[ $size ]['crop']   = get_option( $size . '_crop' ) ?: false;
	}

	if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
		$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	}

	$image_sizes['full'] = [
		'width'  => '',
		'height' => '',
		'crop'   => '',
	];

	return $image_sizes;
}

/**
 * Exclude Hero Slider and PYML posts from main query
 *
 * @since 1.1.0
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function blogsy_exclude_slider_and_pyml_posts_from_index( WP_Query $query ): void {
	if (
		! is_admin() &&
		$query->is_main_query() &&
		( $query->is_home() || $query->is_archive() )
	) {
		$exclude_ids = [];

		// Check if Hero Slider is enabled and visible.
		if (
			( ! empty( Helper::get_option( 'hero_slider_category' ) ) || ! empty( Helper::get_option( 'hero_slider_tags' ) ) ) &&
			function_exists( 'blogsy_is_hero_displayed' ) &&
			blogsy_is_hero_displayed()
		) {
			$slider_post_ids = get_transient( 'blogsy_hero_slider_post_ids' );
			if ( ! empty( $slider_post_ids ) && is_array( $slider_post_ids ) ) {
				$exclude_ids = array_merge( $exclude_ids, $slider_post_ids );
			}
		}

		// Check if PYML is enabled and visible.
		if (
			( ! empty( Helper::get_option( 'pyml_category' ) ) || ! empty( Helper::get_option( 'pyml_tags' ) ) ) &&
			function_exists( 'blogsy_is_pyml_displayed' ) &&
			blogsy_is_pyml_displayed()
		) {
			$pyml_post_ids = get_transient( 'blogsy_pyml_post_ids' );
			if ( ! empty( $pyml_post_ids ) && is_array( $pyml_post_ids ) ) {
				$exclude_ids = array_merge( $exclude_ids, $pyml_post_ids );
			}
		}

		if ( [] !== $exclude_ids ) {
			// Remove duplicates just in case.
			$exclude_ids = array_unique( $exclude_ids );
			$query->set( 'post__not_in', $exclude_ids );
		}
	}
}
add_action( 'pre_get_posts', 'blogsy_exclude_slider_and_pyml_posts_from_index' );
