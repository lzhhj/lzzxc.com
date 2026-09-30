<?php
/**
 * Blogsy helper functions and definitions.
 *
 * @package Blogsy
 */

namespace Blogsy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blogsy Helper initial
 */
class Helper {

	/**
	 * Get theme option
	 *
	 * @param  string $id Option ID.
	 * @param  string $prefix Option prefix.
	 * @param  string $type Option type (theme_mod|option).
	 * @return mixed
	 */
	public static function get_option( string $id, string $prefix = 'blogsy_', string $type = 'theme_mod' ) {
		if ( 'theme_mod' === $type ) {
			return \Blogsy\Customizer\Options::instance()->get( $prefix . $id );
		} else {
			return get_option( $prefix . $id, \Blogsy\Customizer\Options::instance()->get( $prefix . $id ) );
		}
	}

	/**
	 * Get theme option default
	 *
	 * @since 1.0.0
	 * @param  string $id Option ID.
	 * @return mixed
	 */
	public static function get_option_default( string $id ) {
		return \Blogsy\Customizer\Options::instance()->get_default( $id );
	}

	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * Provide a default value if you want to return a specific value if the property is not set.
	 *
	 * @param array  $data   Array from which the property's value should be retrieved.
	 * @param string $prop    Name of the property to be retrieved.
	 * @param mixed  $default_value Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 * @since 1.0.0
	 */
	public static function get_prop( array $data, string $prop, $default_value = null ) {

		if ( ! is_array( $data ) && ! $data instanceof \ArrayAccess ) {
			return $default_value;
		}

		$value = $data[ $prop ] ?? '';

		return empty( $value ) && null !== $default_value ? $default_value : $value;
	}

	/**
	 * Returns blog page URL.
	 *
	 * @since 1.0.0
	 * @return String, current page URL.
	 */
	public static function get_blog_url(): string {

		$blog_url = '';

		// If front page is set to display a static page, get the URL of the posts page.
		if ( 'page' === get_option( 'show_on_front' ) ) {

			$page_for_posts = get_option( 'page_for_posts' );

			if ( $page_for_posts ) {
				$blog_url = get_permalink( $page_for_posts );
			}
		} else {

			// The front page IS the posts page. Get its URL.
			$blog_url = home_url( '/' );
		}

		return apply_filters( 'blogsy_site_url', $blog_url );
	}

	/**
	 * Fonts
	 *
	 * @return Fonts
	 * @since 1.0.0
	 */
	public static function fonts(): Fonts {
		return Fonts::instance();
	}

	/**
	 * Returns array of default values for Design Options field.
	 *
	 * @since  1.0.0
	 * @param  array $options Default options.
	 * @return array $defaults array of default values.
	 */
	public static function design_options_defaults( array $options = [] ): array {

		$defaults = [];

		// Background options.
		if ( isset( $options['background'] ) ) {

			// Default background type.
			if ( isset( $options['background']['background-type'] ) && in_array( $options['background']['background-type'], [ 'color', 'image', 'gradient' ], true ) ) {
				$defaults['background-type'] = $options['background']['background-type'];
			} else {
				$defaults['background-type'] = 'color';
			}

			// Background color defaults.
			if ( isset( $options['background']['color'] ) ) {
				$defaults += wp_parse_args(
					(array) $options['background']['color'],
					[
						'background-color' => '',
					]
				);
			}

			// Background image defaults.
			if ( isset( $options['background']['image'] ) ) {
				$defaults += wp_parse_args(
					(array) $options['background']['image'],
					[
						'background-image'         => '',
						'background-repeat'        => 'no-repeat',
						'background-position-x'    => '50',
						'background-position-y'    => '50',
						'background-size'          => 'cover',
						'background-attachment'    => 'inherit',
						'background-image-id'      => '',
						'background-color-overlay' => 'rgba(0,0,0,0.5)',
					]
				);
			}

			// Background gradient defaults.
			if ( isset( $options['background']['gradient'] ) ) {
				$defaults += wp_parse_args(
					(array) $options['background']['gradient'],
					[
						'gradient-color-1'          => '#16222A',
						'gradient-color-1-location' => '0',
						'gradient-color-2'          => '#3A6073',
						'gradient-color-2-location' => '100',
						'gradient-type'             => 'linear',
						'gradient-linear-angle'     => '45',
						'gradient-position'         => 'center center',
					]
				);
			}
		}

		// Border default.
		if ( isset( $options['border'] ) ) {
			$defaults += wp_parse_args(
				(array) $options['border'],
				[
					'border-left-width'   => '',
					'border-top-width'    => '',
					'border-right-width'  => '',
					'border-bottom-width' => '',
					'border-color'        => '',
					'style'               => 'solid',
					'separator-color'     => '',
				]
			);
		}

		// Color default.
		if ( isset( $options['color'] ) ) {
			$defaults += wp_parse_args(
				(array) $options['color'],
				[
					'text-color'       => '',
					'link-color'       => '',
					'link-hover-color' => '',
				]
			);
		}

		return apply_filters( 'blogsy_design_options_defaults', $defaults, $options );
	}

	/**
	 * Returns array of default values for Typography field.
	 *
	 * @since  1.0.0
	 * @param  array $options Default options.
	 * @return array $defaults array of default values.
	 */
	public static function typography_defaults( $options = [] ): array {

		$defaults = apply_filters(
			'blogsy_typography_defaults',
			[
				'font-family'         => 'inherit',
				'font-subsets'        => [],
				'font-weight'         => '400',
				'font-style'          => 'inherit',
				'text-transform'      => 'inherit',
				'text-decoration'     => 'inherit',
				'font-size-desktop'   => '',
				'font-size-tablet'    => '',
				'font-size-mobile'    => '',
				'font-size-unit'      => 'px',
				'color'               => '',
				'letter-spacing'      => '0',
				'letter-spacing-unit' => 'px',
				'line-height-desktop' => '',
				'line-height-tablet'  => '',
				'line-height-mobile'  => '',
				'line-height-unit'    => '',
			]
		);

		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Returns the ordinal suffix for a number (1st, 2nd, 3rd, etc.).
	 *
	 * @since 1.0.0
	 *
	 * @param int $number The number.
	 * @return string The number with the ordinal suffix.
	 */
	public static function ordinal_suffix( int $number ): string {
		$ends = [ 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' ];

		// Special cases for 11, 12, 13.
		if ( ( $number % 100 ) >= 11 && ( $number % 100 ) <= 13 ) {
			return $number . 'th';
		} else {
			return $number . $ends[ $number % 10 ];
		}
	}
}
