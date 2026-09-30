<?php
/**
 * Helper class for font settings.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

namespace Blogsy;

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Blogsy helper class to handle fonts.
 *
 * @since 1.0.0
 */
class Fonts {

	/**
	 * System Fonts
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $system_fonts = [];

	/**
	 * Google Fonts
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $google_fonts = [];

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var self|null Singleton instance of the class.
	 */
	private static ?self $instance = null;

	/**
	 * Main Customizer Instance.
	 *
	 * @since 1.0.0
	 */
	public static function instance(): self {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get System Fonts.
	 *
	 * @since 1.0.0
	 *
	 * @return array All the system fonts in Blogsy
	 */
	public function get_system_fonts(): array {

		if ( [] === $this->system_fonts ) {
			$this->system_fonts = [
				'Helvetica' => [
					'fallback' => 'Verdana, Arial, sans-serif',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
				'Verdana'   => [
					'fallback' => 'Helvetica, Arial, sans-serif',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
				'Arial'     => [
					'fallback' => 'Helvetica, Verdana, sans-serif',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
				'Times'     => [
					'fallback' => 'Georgia, serif',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
				'Georgia'   => [
					'fallback' => 'Times, serif',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
				'Courier'   => [
					'fallback' => 'monospace',
					'variants' => [
						'300',
						'400',
						'700',
					],
				],
			];
		}

		return apply_filters( 'blogsy_system_fonts', $this->system_fonts );
	}

	/**
	 * Return an array of standard websafe fonts.
	 *
	 * @return array    Standard websafe fonts.
	 */
	public function get_standard_fonts(): array {

		$standard_fonts = [
			'Serif'      => [
				'fallback' => 'Georgia, Times, "Times New Roman", serif',
				'variants' => [
					'300',
					'400',
					'700',
				],
			],
			'Sans Serif' => [
				'fallback' => 'Helvetica, Arial, sans-serif',
				'variants' => [
					'300',
					'400',
					'700',
				],
			],
			'Monospace'  => [
				'fallback' => 'Monaco, "Lucida Sans Typewriter", "Lucida Typewriter", "Courier New", Courier, monospace',
				'variants' => [
					'300',
					'400',
					'700',
				],
			],
		];

		return apply_filters( 'blogsy_standard_fonts', $standard_fonts );
	}

	/**
	 * Default system font.
	 *
	 * @since  1.0.0
	 *
	 * @return string Default system font.
	 */
	public function get_default_system_font(): string {

		$font = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;';

		return apply_filters( 'blogsy_default_system_font', $font );
	}

	/**
	 * Google Fonts.
	 * Array is generated from the google-fonts.json file.
	 *
	 * @since  1.0.0
	 *
	 * @return array Array of Google Fonts.
	 */
	public function get_google_fonts(): array {

		if ( [] === $this->google_fonts ) {

			$google_fonts_file = apply_filters( 'blogsy_google_fonts_json_file', BLOGSY_THEME_DIR . '/assets/fonts/google-fonts.json' );

			if ( ! file_exists( $google_fonts_file ) ) {
				return [];
			}

			$file_contants     = file_get_contents( $google_fonts_file );
			$google_fonts_json = json_decode( $file_contants, 1 );
			foreach ( $google_fonts_json as $font ) {

				$name = key( $font );

				foreach ( $font[ $name ] as $font_key => $single_font ) {

					if ( 'variants' === $font_key ) {

						foreach ( $single_font as $variant_key => $variant ) {

							if ( 'regular' === $variant ) {
								$font[ $name ][ $font_key ][ $variant_key ] = '400';
							}

							if ( 'italic' === $variant ) {
								$font[ $name ][ $font_key ][ $variant_key ] = '400italic';
							}

							if ( strpos( $font[ $name ][ $font_key ][ $variant_key ], 'italic' ) ) {
								unset( $font[ $name ][ $font_key ][ $variant_key ] );
							}
						}
					}

					$this->google_fonts[ $name ] = $font[ $name ];
				}
			}
		}

		return apply_filters( 'blogsy_google_fonts', $this->google_fonts );
	}

	/**
	 * Google Font subsets.
	 *
	 * @since  1.0.0
	 *
	 * @return array Array of Google Fonts.
	 */
	public function get_google_font_subsets(): array {

		$subsets = [
			'arabic'              => 'Arabic',
			'bengali'             => 'Bengali',
			'chinese-hongkong'    => 'Chinese (Hong Kong)',
			'chinese-simplified'  => 'Chinese (Simplified)',
			'chinese-traditional' => 'Chinese (Traditional)',
			'cyrillic'            => 'Cyrillic',
			'cyrillic-ext'        => 'Cyrillic Extended',
			'devanagari'          => 'Devanagari',
			'greek'               => 'Greek',
			'greek-ext'           => 'Greek Extended',
			'gujarati'            => 'Gujarati',
			'gurmukhi'            => 'Gurmukhi',
			'hebrew'              => 'Hebrew',
			'japanese'            => 'Japanese',
			'kannada'             => 'Kannada',
			'khmer'               => 'Khmer',
			'korean'              => 'Korean',
			'latin'               => 'Latin',
			'latin-ext'           => 'Latin Extended',
			'malayalam'           => 'Malayalam',
			'myanmar'             => 'Myanmar',
			'oriya'               => 'Oriya',
			'sinhala'             => 'Sinhala',
			'tamil'               => 'Tamil',
			'telugu'              => 'Telugu',
			'thai'                => 'Thai',
			'vietnamese'          => 'Vietnamese',
		];

		return apply_filters( 'blogsy_google_font_subsets', $subsets );
	}

	/**
	 * Return an array of backup fonts based on the font-category.
	 *
	 * @return array
	 */
	public function get_backup_fonts(): array {

		$backup_fonts = [
			'sans-serif'  => 'Helvetica, Arial, sans-serif',
			'serif'       => 'Georgia, serif',
			'display'     => '"Comic Sans MS", cursive, sans-serif',
			'handwriting' => '"Comic Sans MS", cursive, sans-serif',
			'monospace'   => '"Lucida Console", Monaco, monospace',
		];

		return apply_filters( 'blogsy_backup_fonts', $backup_fonts );
	}

	/**
	 * Enqueue Google fonts.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $return_url If true, return the URL instead of enqueuing.
	 */
	public function enqueue_google_fonts( bool $return_url = false ): ?string {

		$fonts = get_transient( 'blogsy_google_fonts_enqueue' );

		if ( false === $fonts || empty( $fonts ) ) {
			return null;
		}

		$url     = '//fonts.googleapis.com/css';
		$family  = [];
		$subsets = [];

		foreach ( $fonts as $font_family => $font ) {

			$family[] = empty( $font['weight'] ) ? $font_family : $font_family . ':' . implode( ',', $font['weight'] );

			$subsets = array_unique( array_merge( $subsets, $font['subsets'] ) );
		}

		$family  = implode( '|', $family );
		$subsets = implode( ',', $subsets );

		$url = add_query_arg(
			[
				'family'  => $family,
				'display' => 'swap',
				'subsets' => $subsets,
			],
			$url
		);

		if ( $return_url ) {
			return $url;
		}

		// Enqueue.
		wp_enqueue_style(
			'blogsy-google-fonts',
			$url,
			false,
			BLOGSY_THEME_VERSION,
			false
		);
		return null;
	}

	/**
	 * Check if font family is a Google font.
	 *
	 * @since  1.0.0
	 * @param  string $font_family Font Family.
	 */
	public function is_google_font( string $font_family ): bool {

		$google_fonts = $this->get_google_fonts();

		return isset( $google_fonts[ $font_family ] );
	}

	/**
	 * Store list of Google fonts to enqueue.
	 *
	 * @since  1.0.0
	 * @param  string $family Font Family.
	 * @param  array  $args Array of font details.
	 */
	public function enqueue_google_font( string $family, array $args = [] ): void {

		$fonts = get_transient( 'blogsy_google_fonts_enqueue' );

		$fonts = $fonts ?: [];

		// Default args.
		$args = wp_parse_args(
			$args,
			[
				'weight'  => [ '400' ],
				'style'   => [ 'normal' ],
				'subsets' => [ 'latin' ],
			]
		);

		// Convert all args to arrays.
		foreach ( $args as $key => $value ) {
			if ( ! is_array( $args[ $key ] ) ) {
				$args[ $key ] = [ $value ];
			}
		}

		if ( in_array( 'italic', $args['style'], true ) ) {
			foreach ( $args['weight'] as $weight ) {
				$args['weight'][] = $weight . 'i';
			}
		}

		// Remove unnecessary info.
		unset( $args['style'] );

		// Sanitize key.
		$family = str_replace( ' ', '+', $family );

		// Check if we previously enqueued this font.
		if ( ! isset( $fonts[ $family ] ) ) {
			$fonts[ $family ] = $args;
		} else {
			foreach ( $args as $key => $value ) {
				$fonts[ $family ][ $key ] = array_unique(
					array_merge(
						$fonts[ $family ][ $key ],
						$value
					)
				);
			}
		}

		set_transient( 'blogsy_google_fonts_enqueue', $fonts );
	}

	/**
	 * Get All Fonts.
	 *
	 * @since 1.0.0
	 *
	 * @return array All the system fonts in Blogsy
	 */
	public function get_fonts(): array {

		$fonts = [];

		$fonts['standard_fonts'] = [
			'name'  => 'Standard',
			'fonts' => self::get_standard_fonts(),
		];

		$fonts['system_fonts'] = [
			'name'  => 'System Fonts',
			'fonts' => self::get_system_fonts(),
		];

		$fonts['google_fonts'] = [
			'name'  => 'Google Fonts',
			'fonts' => self::get_google_fonts(),
		];

		return apply_filters( 'blogsy_get_fonts', $fonts );
	}

	/**
	 * Get complete font family stack.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $font Font family.
	 * @return string Font family including backup families.
	 */
	public function get_font_family( string $font ): string {

		if ( 'default' === $font ) {
			$font = $this->get_default_system_font();
		} else {

			$fonts  = $this->get_fonts();
			$backup = '';

			if ( isset( $fonts['system_fonts']['fonts'][ $font ] ) ) {
				$backup = $fonts['system_fonts']['fonts'][ $font ]['fallback'];
			} elseif ( isset( $fonts['google_fonts']['fonts'][ $font ] ) ) {
				$backups  = $this->get_backup_fonts();
				$category = $fonts['google_fonts']['fonts'][ $font ]['category'];
				$backup   = $backups[ $category ] ?? '';
			} elseif ( isset( $fonts['standard_fonts']['fonts'][ $font ] ) ) {
				$backup = $fonts['standard_fonts']['fonts'][ $font ]['fallback'];
				$font   = '';
			}

			if ( false !== strpos( $font, ' ' ) ) {
				$font = '"' . $font . '"';
			}

			$font = $font . ', ' . $backup;
			$font = trim( $font, ', ' );
		}

		return apply_filters( 'blogsy_font_family', $font );
	}
}
