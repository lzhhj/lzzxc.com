<?php
/**
 * Common utility functions for the Blogsy WordPress theme.
 *
 * @package Blogsy
 * @since 1.0.0
 */

use Blogsy\Helper;

/**
 * Insert dynamic text into content.
 *
 * @param string $content Text to be modified.
 * @return string Modified text.
 * @since 1.0.0
 */
function blogsy_dynamic_strings( string $content ): string {
	$content = str_replace( '{{the_year}}', date_i18n( 'Y' ), $content );
	$content = str_replace( '{{the_date}}', date_i18n( get_option( 'date_format' ) ), $content );
	$content = str_replace( '{{site_title}}', get_bloginfo( 'name' ), $content );
	$content = str_replace( '{{theme_link}}', '<a href="https://wordpress.org/themes/blogsy/" class="imprint" target="_blank" rel="noopener noreferrer">Blogsy WordPress Theme</a>', $content );

	if ( false !== strpos( $content, '{{current_user}}' ) ) {
		$current_user = wp_get_current_user();
		$content      = str_replace( '{{current_user}}', apply_filters( 'blogsy_logged_out_user_name', $current_user->display_name ), $content );
	}

	return apply_filters( 'blogsy_parse_dynamic_strings', $content );
}

add_filter( 'blogsy_dynamic_strings', 'blogsy_dynamic_strings' );

/**
 * Determine whether a hex color is light.
 *
 * @param mixed $color Color.
 * @return bool True if a light color.
 */
function blogsy_is_light_color( $color ): bool {
	// If $color is an array, check each color and return true if any is light.
	if ( is_array( $color ) ) {
		foreach ( $color as $value ) {
			if ( ! empty( $value ) ) {
				$hex_color = false !== strpos( $value, 'rgb' ) ? blogsy_rgba2hex( $value ) : $value;
				if ( blogsy_is_light_color( $hex_color ) ) {
					return true; // At least one color is light.
				}
			}
		}

		return false; // No light colors found.
	}

	// If $color is a single string, process it as before.
	if ( false !== strpos( $color, 'rgb' ) ) {
		$color = blogsy_rgba2hex( $color );
	}

	$hex = str_replace( '#', '', $color );
	if ( strlen( $hex ) < 6 ) {
		return false; // Handle invalid hex.
	}

	$c_r        = hexdec( substr( $hex, 0, 2 ) );
	$c_g        = hexdec( substr( $hex, 2, 2 ) );
	$c_b        = hexdec( substr( $hex, 4, 2 ) );
	$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

	return $brightness > 155;
}

/**
 * Convert rgb(a) color string to hex string.
 *
 * @param  string $color rgb(a) color code.
 * @return string color in HEX format.
 * @since  1.0.0
 */
function blogsy_rgba2hex( string $color ) {

	preg_match( '/rgba?\(\s?([0-9]{1,3}),\s?([0-9]{1,3}),\s?([0-9]{1,3})/i', $color, $matches );

	if ( count( $matches ) < 3 ) {
		return false;
	}

	$hex = '';

	for ( $i = 1; $i <= 3; $i++ ) {
		$x = dechex( (int) $matches[ $i ] );

		$hex .= ( 1 === strlen( $x ) ) ? '0' . $x : $x;
	}

	if ( '' !== $hex && '0' !== $hex ) {
		return '#' . $hex;
	}

	return false;
}

/**
 * Lightens/darkens a given colour (in hex format), returning the altered color in hex format.
 *
 * @param string $hex_color Color as hexadecimal (with or without hash).
 * @param float  $percent Decimal ( 0.2 = lighten by 20%, -0.4 = darken by 40% ).
 * @return string Lightened/Darkend color as hexadecimal (with hash)
 * @since 1.0.0
 */
function blogsy_luminance( string $hex_color, float $percent ): ?string {

	if ( empty( $hex_color ) ) {
		return null;
	}

	// Check if color is in RGB format and convert to HEX.
	if ( false !== strpos( $hex_color, 'rgb' ) ) {
		$hex_color = blogsy_rgba2hex( $hex_color );
	}

	if ( strlen( $hex_color ) < 6 ) {
		$hex_color = $hex_color[0] . $hex_color[0] . $hex_color[1] . $hex_color[1] . $hex_color[2] . $hex_color[2];
	}

	$hex_color = array_map( 'hexdec', str_split( str_pad( str_replace( '#', '', $hex_color ), 6, '0' ), 2 ) );

	foreach ( $hex_color as $i => $color ) {
		$from            = $percent < 0 ? 0 : $color;
		$to              = $percent < 0 ? $color : 255;
		$pvalue          = ceil( ( $to - $from ) * $percent );
		$hex_color[ $i ] = str_pad( dechex( $color + $pvalue ), 2, '0', STR_PAD_LEFT );
	}

	// Return hex color.
	return '#' . implode( '', $hex_color );
}

/**
 * Detect if we should use a light or dark color on a background color.
 *
 * @param string $color Color.
 * @param string $dark  Darkest reference. Defaults to '#000000'.
 * @param string $light Lightest reference. Defaults to '#FFFFFF'.
 * @return string
 */
function blogsy_light_or_dark( string $color, string $dark = '#000000', string $light = '#FFFFFF' ): string {
	return blogsy_is_light_color( $color ) ? $dark : $light;
}

/**
 * Common functions used in backend and frontend of the theme.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */
if ( ! function_exists( 'blogsy_get_allowed_html_tags' ) ) {
	/**
	 * Retrieve allowed HTML tags with enhanced security and flexibility.
	 *
	 * This function provides a secure and extensible way to define allowed HTML tags
	 * for different contexts in the WordPress theme. It helps prevent XSS attacks
	 * by strictly controlling which HTML tags and attributes are permitted.
	 *
	 * @param string $type Predefined HTML tags group name.
	 * @return array Sanitized and allowed HTML tags with their permitted attributes.
	 * @since 1.0.0
	 */
	function blogsy_get_allowed_html_tags( string $type = 'post' ): array {
		// Common attributes that can be applied to multiple tags.
		$common_attributes = [
			'class' => true,
			'id'    => true,
			'style' => true,
		];

		// Basic text-level semantic tags.
		$text_tags = [
			'strong' => $common_attributes,
			'em'     => $common_attributes,
			'b'      => $common_attributes,
			'i'      => $common_attributes,
			'br'     => [],
			'span'   => $common_attributes,
		];

		// Link and media tags.
		$link_media_tags = [
			'a'   => array_merge(
				$common_attributes,
				[
					'href'     => true,
					'rel'      => true,
					'target'   => true,
					'title'    => true,
					'download' => true,
					'role'     => true,
				],
				[
					// Nested tags allowed inside anchor.
					'strong' => $common_attributes,
					'em'     => $common_attributes,
					'span'   => array_merge(
						$common_attributes,
						[
							// Support for SVG icon spans.
							'svg'  => [
								'class'       => true,
								'xmlns'       => true,
								'width'       => true,
								'height'      => true,
								'viewbox'     => true,
								'aria-hidden' => true,
								'role'        => true,
								'focusable'   => true,
								'fill'        => true,
							],
							'path' => [
								'fill'         => true,
								'fill-rule'    => true,
								'd'            => true,
								'transform'    => true,
								'stroke'       => true,
								'stroke-width' => true,
							],
						]
					),
					'i'      => $common_attributes,
					'svg'    => [
						'class'       => true,
						'xmlns'       => true,
						'width'       => true,
						'height'      => true,
						'viewbox'     => true,
						'aria-hidden' => true,
						'role'        => true,
						'focusable'   => true,
						'fill'        => true,
					],
					'path'   => [
						'fill'         => true,
						'fill-rule'    => true,
						'd'            => true,
						'transform'    => true,
						'stroke'       => true,
						'stroke-width' => true,
					],
				]
			),
			'img' => array_merge(
				$common_attributes,
				[
					'src'     => true,
					'alt'     => true,
					'width'   => true,
					'height'  => true,
					'loading' => true,
					'srcset'  => true,
					'sizes'   => true,
				]
			),
		];

		// SVG and vector graphics tags.
		$svg_tags = [
			'svg'     => [
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
			],
			'path'    => [
				'fill'         => true,
				'fill-rule'    => true,
				'd'            => true,
				'transform'    => true,
				'stroke'       => true,
				'stroke-width' => true,
			],
			'polygon' => [
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			],
			'title'   => [],
		];

		// Embedded content tags.
		$embed_tags = [
			'iframe' => [
				'title'           => true,
				'src'             => true,
				'width'           => true,
				'height'          => true,
				'loading'         => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
				'sandbox'         => true,
			],
			'time'   => [
				'class'    => true,
				'datetime' => true,
			],
		];

		// Button and interactive tags.
		$button_tags = [
			'button' => [
				'type'     => true,
				'class'    => true,
				'disabled' => true,
				'id'       => true,
			],
		];

		// Semantic HTML5 tags.
		$semantic_tags = [
			'article' => $common_attributes,
			'section' => $common_attributes,
			'nav'     => $common_attributes,
		];

		// Determine tags based on type.
		switch ( $type ) {
			case 'basic':
				$tags = array_merge(
					$text_tags,
					$link_media_tags,
					$svg_tags,
					[ 'iframe' => $embed_tags['iframe'] ]
				);
				break;

			case 'button':
				$tags = array_merge(
					$text_tags,
					$button_tags
				);
				break;

			case 'post':
				$tags = wp_kses_allowed_html( 'post' );
				$tags = array_merge(
					$tags,
					$semantic_tags,
					$svg_tags,
					$embed_tags,
					[ 'data' => [ 'value' => true ] ]
				);
				break;

			default:
				$tags = array_merge(
					$text_tags,
					$link_media_tags
				);
				break;
		}

		/**
		 * Filter the allowed HTML tags and their attributes.
		 *
		 * This filter allows theme and plugin developers to modify the
		 * allowed HTML tags dynamically based on specific requirements.
		 *
		 * @param array  $tags Allowed HTML tags and attributes.
		 * @param string $type Context or group of HTML tags.
		 */
		return apply_filters( 'blogsy_allowed_html_tags', $tags, $type );
	}
}

/**
 * Checks to see if Top Bar is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean if Top Bar is enabled.
 * @since 1.0.0
 */
function blogsy_is_top_bar_displayed( int $post_id = 0 ): bool {

	if ( ! $post_id ) {
		$post_id = blogsy_get_the_id();
	}

	$top_bar_displayed = Helper::get_option( 'top_bar_enable' );

	if ( $post_id && $top_bar_displayed ) {
		$top_bar_displayed = ! get_post_meta( $post_id, 'blogsy_page_disable_top_bar', true );
	}

	// Do not show top bar on 404 page.
	if ( is_404() ) {
		$top_bar_displayed = false;
	}

	return apply_filters( 'blogsy_is_top_bar_displayed', $top_bar_displayed, $post_id );
}

/**
 * Checks to see if Header is displayed.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean true if Header is displayed.
 * @since 1.0.0
 */
function blogsy_is_header_displayed( int $post_id = 0 ): bool {

	if ( ! $post_id ) {
		$post_id = blogsy_get_the_id();
	}

	$displayed = true;

	if ( $post_id ) {
		$displayed = 'disable' !== get_post_meta( $post_id, 'blogsy_page_header', true ) ? true : false;
	}

	return apply_filters( 'blogsy_is_header_displayed', $displayed, $post_id );
}

/**
 * Get registered sidebar name by sidebar ID.
 *
 * @param  string $sidebar_id Sidebar ID.
 * @return string Sidebar name.
 * @since  1.0.0
 */
function blogsy_get_sidebar_name_by_id( string $sidebar_id = '' ): ?string {

	if ( ! $sidebar_id ) {
		return null;
	}

	global $wp_registered_sidebars;
	$sidebar_name = '';

	if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		$sidebar_name = $wp_registered_sidebars[ $sidebar_id ]['name'];
	}

	return $sidebar_name;
}

/**
 * Get footer widgets column count.
 *
 * @param string $layout Footer layout.
 * @return array Classes array
 * @since 1.0.0
 */
function blogsy_get_footer_column_class( string $layout = 'layout-1' ): array {

	$classes = [
		'layout-1' => [
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-3',
		],
		'layout-2' => [
			'pt-col-12 pt-col-md-4',
			'pt-col-12 pt-col-md-4',
			'pt-col-12 pt-col-md-4',
		],
		'layout-3' => [
			'pt-col-12 pt-col-md-8',
			'pt-col-12 pt-col-md-4',
		],
		'layout-4' => [
			'pt-col-12 pt-col-md-4',
			'pt-col-12 pt-col-md-8',
		],
		'layout-5' => [
			'pt-col-12 pt-col-md-6',
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-3',
		],
		'layout-6' => [
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-3',
			'pt-col-12 pt-col-md-6',
		],
		'layout-7' => [
			'pt-col-12 pt-col-md-6',
			'pt-col-12 pt-col-md-6',
		],
		'layout-8' => [
			'pt-col-12 pt-col-md-12',
		],
	];

	$classes = apply_filters( 'blogsy_footer_column_classes', $classes, $layout );

	$classes = $classes[ $layout ] ?? [];

	$align_center = Helper::get_option( 'footer_widgets_align_center' );

	if ( $align_center && ! empty( $classes ) ) {
		foreach ( $classes as $key => $column_class ) {
			$classes[ $key ] = $column_class . ' center-text';
		}
	}

	return $classes;
}

/**
 * Retrieves an array of single post hero layouts.
 *
 * @param int  $number Optional. The number of layouts to retrieve. Default is 3.
 * @param bool $default_value Optional. Whether to include a default layout. Default is false.
 * @return array An associative array of layout labels indexed by their number.
 * @since 1.0.0
 */
function blogsy_get_single_post_hero_layouts( int $number = 2, bool $default_value = false ): array {
	$layouts = [];
	if ( $default_value ) {
		$layouts[0] = esc_html__( 'Default', 'blogsy' );
	}

	for ( $i = 1; $i <= $number; $i++ ) {
		// translators: %d is the layout number.
		$layouts[ $i ] = sprintf( esc_html__( 'Layout %d', 'blogsy' ), $i );
	}

	return apply_filters( 'blogsy_single_post_hero_layouts', $layouts );
}

/**
 * Retrieves an array of header layouts.
 *
 * @param int  $number Optional. The number of layouts to retrieve. Default is 3.
 * @param bool $default_value Optional. Whether to include a default layout. Default is false.
 * @param bool $add_disable Optional. Whether to include a disable option. Default is false.
 * @return array An associative array of layout labels indexed by their number.
 * @since 1.0.0
 */
function blogsy_get_header_layouts_prebuild( int $number = 3, bool $default_value = false, $add_disable = false ): array {
	$layouts = [];
	if ( $default_value ) {
		$layouts[0] = esc_html__( 'Default', 'blogsy' );
	}
	if ( $add_disable ) {
		$layouts['disable'] = esc_html__( 'Disable', 'blogsy' );
	}

	for ( $i = 1; $i <= $number; $i++ ) {
		// translators: %d is the layout number.
		$layouts[ 'layout-' . $i ] = sprintf( esc_html__( 'Header %d', 'blogsy' ), $i );
	}

	return apply_filters( 'blogsy_header_layouts_prebuild', $layouts );
}

/**
 * Check if a section is disabled.
 *
 * @param  array $disabled_on Array of pages where the section is disabled.
 * @param int   $post_id     Current page ID.
 * @return bool               Section is displayed.
 * @since 1.0.0
 */
function blogsy_is_section_disabled( array $disabled_on = [], int $post_id = 0 ): bool {

	$disabled = false;

	if ( is_front_page() && in_array( 'home', $disabled_on, true ) ) {
		$disabled = true;
	} elseif ( is_home() && in_array( 'posts_page', $disabled_on, true ) ) {
		$disabled = true;
	} elseif ( is_search() && in_array( 'search', $disabled_on, true ) ) {
		$disabled = true;
	} elseif ( is_archive() && in_array( 'archive', $disabled_on, true ) ) {
		$disabled = true;
	} elseif ( is_404() && in_array( 404, $disabled_on, true ) ) {
		$disabled = true;
	} elseif ( ( is_singular() || ! empty( $post_id ) ) && ! is_front_page() ) {

		if ( empty( $post_id ) ) {
			$post_id = blogsy_get_the_id();
		}

		if ( in_array( get_post_type( $post_id ), $disabled_on, true ) ) {
			$disabled = true;
		}
	}

	return $disabled;
}

/**
 * Checks to see if Ticker News section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Ticker News is enabled.
 * @since 1.0.0
 */
function blogsy_is_ticker_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'ticker_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'ticker_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_ticker_displayed', $displayed, $post_id );
}

/**
 * Checks to see if Hero section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Hero is enabled.
 * @since 1.0.0
 */
function blogsy_is_hero_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'hero_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'hero_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_hero_displayed', $displayed, $post_id );
}

/**
 * Checks to see if Featured Category section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Featured Category is enabled.
 * @since 1.0.0
 */
function blogsy_is_featured_category_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'featured_category_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'featured_category_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_featured_category_displayed', $displayed, $post_id );
}

/**
 * Checks to see if Featured Links section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Featured Links is enabled.
 * @since 1.0.0
 */
function blogsy_is_featured_links_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'featured_links_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'featured_links_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_featured_links_displayed', $displayed, $post_id );
}

/**
 * Checks to see if Stories section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Stories is enabled.
 * @since 1.0.0
 */
function blogsy_is_stories_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'stories_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'stories_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_stories_displayed', $displayed, $post_id );
}

/**
 * Checks to see if PYML section is enabled.
 *
 * @param  int $post_id Optional. The post ID to check.
 * @return boolean True if Pyml is enabled.
 * @since 1.0.0
 */
function blogsy_is_pyml_displayed( int $post_id = 0 ): bool {

	$displayed = true;

	if ( ! Helper::get_option( 'pyml_enable' ) ) {
		$displayed = false;
	}

	if ( $displayed && ! blogsy_is_section_disabled( Helper::get_option( 'pyml_enable_on' ), $post_id ) ) {
		$displayed = false;
	}

	return apply_filters( 'blogsy_is_pyml_displayed', $displayed, $post_id );
}

/**
 * Insert into array before specified key.
 *
 * @since 1.0.0
 * @param array  $array     Array to be modified.
 * @param array  $pairs     Array of key => value pairs to insert.
 * @param mixed  $key       Key of $array to insert before or after.
 * @param string $position  Before or after $key.
 * @return array $result    Array with inserted $new value.
 */
function blogsy_array_insert( $array, $pairs, $key, $position = 'after' ) {

	$key_pos = array_search( $key, array_keys( $array ), true );

	if ( 'after' === $position ) {
		++$key_pos;
	}

	if ( false !== $key_pos ) {
		$result = array_slice( $array, 0, $key_pos );
		$result = array_merge( $result, $pairs );
		$result = array_merge( $result, array_slice( $array, $key_pos ) );
	} else {
		$result = array_merge( $array, $pairs );
	}

	return $result;
}
