<?php
/**
 * Theme Settings
 *
 * @package Blogsy
 */

namespace Blogsy\Customizer;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 */
class Settings {


	/**
	 * Instance
	 *
	 * @var null|Settings $instance instance variable.
	 */
	protected static ?self $instance = null;

	/**
	 * Blogsy Customizer
	 *
	 * @var null|Customizer $blogsy_customize instance variable.
	 */
	protected static ?Customizer $blogsy_customize = null;

	/**
	 * Initiator
	 *
	 * @return object
	 * @since 1.0.0
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * The class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'blogsy_customizer_options', [ $this, 'customize_settings' ] );
		self::$blogsy_customize = Customizer::instance();
	}

	/**
	 * Get image sizes
	 *
	 * @since 1.0.0
	 */
	public function get_image_sizes(): array {
		$_image_sizes = blogsy_get_image_sizes();
		$size_choices = [];

		if ( ! empty( $_image_sizes ) ) {
			foreach ( $_image_sizes as $key => $value ) {
				$name = ucwords( str_replace( [ '-', '_' ], ' ', $key ) );

				$size_choices[ $key ] = $name;

				if ( $value['width'] || $value['height'] ) {
					$size_choices[ $key ] .= ' (' . $value['width'] . 'x' . $value['height'] . ')';
				}
			}
		}

		return $size_choices;
	}

	/**
	 * Define customizer panels.
	 */
	protected function get_panels(): array {
		return [
			'blogsy_general'     => [
				'priority' => 10,
				'title'    => esc_html__( 'General', 'blogsy' ),
			],
			'blogsy_single_post' => [
				'priority' => 35,
				'title'    => esc_html__( 'Single Post', 'blogsy' ),
			],
		];
	}

	/**
	 * Define customizer sections.
	 */
	protected function get_sections(): array {
		$theme = wp_get_theme();

		$sections = [
			'blogsy_section_general_options_group' => [
				'class'    => 'Blogsy_Customizer_Control_Section_Group_Title',
				'title'    => esc_html__( 'General Options', 'blogsy' ),
				'priority' => 5,
			],
			'blogsy_layout_section'                => [
				'priority'   => 10,
				'title'      => esc_html__( 'Layout', 'blogsy' ),
				'panel'      => 'blogsy_general',
				'capability' => 'edit_theme_options',
			],
			'blogsy_styling_section'               => [
				'priority'   => 20,
				'title'      => esc_html__( 'Styling', 'blogsy' ),
				'panel'      => 'blogsy_general',
				'capability' => 'edit_theme_options',
			],
			'blogsy_category_color_section'        => [
				'priority'   => 20,
				'title'      => esc_html__( 'Category Color', 'blogsy' ),
				'panel'      => 'blogsy_general',
				'capability' => 'edit_theme_options',
			],
			'blogsy_typography_section'            => [
				'priority'   => 30,
				'title'      => esc_html__( 'Typography', 'blogsy' ),
				'panel'      => 'blogsy_general',
				'capability' => 'edit_theme_options',
			],
			'blogsy_misc_section'                  => [
				'priority'   => 40,
				'title'      => esc_html__( 'Misc Settings', 'blogsy' ),
				'panel'      => 'blogsy_general',
				'capability' => 'edit_theme_options',
			],

			'blogsy_topbar_section'                => [
				'priority'   => 15,
				'title'      => esc_html__( 'Top Bar', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_header_section'                => [
				'priority'   => 20,
				'title'      => esc_html__( 'Header', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_footer_section'                => [
				'priority'   => 25,
				'title'      => esc_html__( 'Footer', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_single_post_general_section'   => [
				'priority'   => 10,
				'title'      => esc_html__( 'General', 'blogsy' ),
				'panel'      => 'blogsy_single_post',
				'capability' => 'edit_theme_options',
			],
			'blogsy_single_post_hero_section'      => [
				'priority'   => 20,
				'title'      => esc_html__( 'Hero Layout', 'blogsy' ),
				'panel'      => 'blogsy_single_post',
				'capability' => 'edit_theme_options',
			],

			'blogsy_blog_archive_section'          => [
				'priority'   => 30,
				'title'      => esc_html__( 'Blog Page / Archive', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_sidebar_section'               => [
				'priority'   => 40,
				'title'      => esc_html__( 'Sidebar', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_breadcrumb_section'            => [
				'priority'   => 40,
				'title'      => esc_html__( 'Breadcrumb', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_dark_mode_section'             => [
				'priority'   => 40,
				'title'      => esc_html__( 'Dark Mode', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_section_home_sections_group'   => [
				'class'    => 'Blogsy_Customizer_Control_Section_Group_Title',
				'title'    => esc_html__( 'Home Sections', 'blogsy' ),
				'priority' => 45,
			],

			'blogsy_ticker_section'                => [
				'priority'   => 50,
				'title'      => esc_html__( 'Ticker', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_hero_section'                  => [
				'priority'   => 50,
				'title'      => esc_html__( 'Hero', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_stories_section'               => [
				'priority'   => 50,
				'title'      => esc_html__( 'Stories', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_featured_category_section'     => [
				'priority'   => 50,
				'title'      => esc_html__( 'Featured Category', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_featured_links_section'        => [
				'priority'   => 50,
				'title'      => esc_html__( 'Featured Links', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_pyml_section'                  => [
				'priority'   => 50,
				'title'      => esc_html__( 'Posts You Might Like', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_advertisement_section'         => [
				'priority'   => 50,
				'title'      => esc_html__( 'Advertisement', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_privacy_notice_section'        => [
				'priority'   => 60,
				'title'      => esc_html__( 'Privacy Notice', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_credentials_section'           => [
				'priority'   => 60,
				'title'      => esc_html__( 'Credentials', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],
			'blogsy_performance_section'           => [
				'priority'   => 60,
				'title'      => esc_html__( 'Performance', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_translate_section'             => [
				'priority'   => 60,
				'title'      => esc_html__( 'Translate', 'blogsy' ),
				'capability' => 'edit_theme_options',
			],

			'blogsy_section_core_group'            => [
				'class'    => 'Blogsy_Customizer_Control_Section_Group_Title',
				'title'    => esc_html__( 'Core', 'blogsy' ),
				'priority' => 70,
			],

			'blogsy_section_upsell_button'         => [
				'class'    => 'Blogsy_Customizer_Control_Section_Pro',
				'title'    => esc_html__( 'Need more features?', 'blogsy' ),
				'pro_url'  => sprintf( esc_url_raw( 'https://peregrine-themes.com/%s' ), strtolower( $theme->name ) ),
				'pro_text' => esc_html__( 'Upgrade to pro', 'blogsy' ),
				'priority' => 200,
			],
			'blogsy_section_docs_button'           => [
				'class'    => 'Blogsy_Customizer_Control_Section_Pro',
				'title'    => esc_html__( 'Need Help?', 'blogsy' ),
				'pro_url'  => esc_url_raw( 'http://docs.peregrine-themes.com/docs-category/blogsy-pro/' ),
				'pro_text' => esc_html__( 'See the docs', 'blogsy' ),
				'priority' => 200,
			],
		];

		if ( class_exists( 'Blogsy_Addons' ) ) {
			unset( $sections['blogsy_section_upsell_button'] );
		}

		return $sections;
	}

	/**
	 * Get customize settings
	 *
	 * @since 1.0.0
	 */
	public function customize_settings(): array {
		// Merge grouped settings from helper methods.
		$settings = [];

		$group_methods = [
			'get_layout_settings',
			'get_title_tagline_settings',
			'get_styling_settings',
			'get_category_color_settings',
			'get_typography_settings',
			'get_misc_settings',
			'get_topbar_settings',
			'get_header_settings',
			'get_blog_archive_settings',
			'get_ticker_settings',
			'get_hero_settings',
			'get_stories_settings',
			'get_featured_category_settings',
			'get_featured_links_settings',
			'get_pyml_settings',
			'get_advertisement_settings',
			'get_footer_settings',
			'get_sidebar_settings',
			'get_single_post_settings',
			'get_dark_mode_settings',
			'get_breadcrumb_settings',
			'get_upsell_docs_settings',
		];

		foreach ( $group_methods as $method ) {
			if ( method_exists( $this, $method ) ) {
				$group = $this->{$method}();
				if ( is_array( $group ) ) {
					$settings = array_merge( $settings, $group );
				}
			}
		}
		return [
			'panels'   => apply_filters( 'blogsy_customize_panels', $this->get_panels() ),
			'sections' => apply_filters( 'blogsy_customize_sections', $this->get_sections() ),
			'settings' => apply_filters( 'blogsy_customize_settings', $settings ),
		];
	}

	/**
	 * Layout settings
	 */
	protected function get_layout_settings(): array {
		return [
			'blogsy_layout_section' => [
				'blogsy_site_width' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Site Width', 'blogsy' ),
					'description'       => esc_html__( 'Choose the site width.', 'blogsy' ),
					'min'               => 1200,
					'step'              => 10,
					'max'               => 1800,
					'priority'          => 10,
				],
			],
		];
	}

	/**
	 * Title & tagline (logo) settings
	 */
	protected function get_title_tagline_settings(): array {

		return [
			'blogsy_title_tagline' => [
				'blogsy_logo_default_retina'        => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_background',
					'type'              => 'blogsy-background',
					'label'             => esc_html__( 'Retina Logo', 'blogsy' ),
					'description'       => esc_html__( 'Upload exactly 2x the size of your default logo to make your logo crisp on HiDPI screens. This options is not required if logo above is in SVG format.', 'blogsy' ),
					'priority'          => 20,
					'section'           => 'title_tagline',
					'advanced'          => false,
					'strings'           => [
						'select_image' => __( 'Select logo', 'blogsy' ),
						'use_image'    => __( 'Select', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '!=',
						],
					],
					'partial'           => [
						'selector'            => '#site-header .pt-logo',
						'render_callback'     => 'blogsy_logo',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				'blogsy_logo_dark'                  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_background',
					'type'              => 'blogsy-background',
					'label'             => esc_html__( 'Dark Mode Logo', 'blogsy' ),
					'description'       => esc_html__( 'Upload light logo for dark mode.', 'blogsy' ),
					'priority'          => 20,
					'section'           => 'title_tagline',
					'advanced'          => false,
					'strings'           => [
						'select_image' => __( 'Select logo', 'blogsy' ),
						'use_image'    => __( 'Select', 'blogsy' ),
					],
					'partial'           => [
						'selector'            => '#site-header .pt-logo',
						'render_callback'     => 'blogsy_logo',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				'blogsy_logo_dark_retina'           => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_background',
					'type'              => 'blogsy-background',
					'label'             => esc_html__( 'Dark Mode Retina Logo', 'blogsy' ),
					'description'       => esc_html__( 'Upload exactly 2x the size of your dark mode logo to make your logo crisp on HiDPI screens. This options is not required if logo above is in SVG format.', 'blogsy' ),
					'priority'          => 20,
					'section'           => 'title_tagline',
					'advanced'          => false,
					'strings'           => [
						'select_image' => __( 'Select logo', 'blogsy' ),
						'use_image'    => __( 'Select', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_logo_dark',
							'value'    => false,
							'operator' => '!=',
						],
					],
					'partial'           => [
						'selector'            => '#site-header .pt-logo',
						'render_callback'     => 'blogsy_logo',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				// Logo Max Height.
				'blogsy_logo_max_height'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Logo Height', 'blogsy' ),
					'description'       => esc_html__( 'Maximum logo image height.', 'blogsy' ),
					'priority'          => 30,
					'section'           => 'title_tagline',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 10,
					'unit'              => 'px',
					'responsive'        => true,
					'required'          => [
						[
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '!=',
						],
					],
				],

				// Logo margin.
				'blogsy_logo_margin'                => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-spacing',
					'label'             => esc_html__( 'Logo Margin', 'blogsy' ),
					'description'       => esc_html__( 'Specify spacing around logo. Negative values are allowed.', 'blogsy' ),
					'priority'          => 40,
					'section'           => 'title_tagline',
					'choices'           => [
						'top'    => esc_html__( 'Top', 'blogsy' ),
						'right'  => esc_html__( 'Right', 'blogsy' ),
						'bottom' => esc_html__( 'Bottom', 'blogsy' ),
						'left'   => esc_html__( 'Left', 'blogsy' ),
					],
					'responsive'        => true,
					'unit'              => [ 'px' ],
				],

				// Show tagline.
				'blogsy_display_tagline'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Display Tagline', 'blogsy' ),
					'priority'          => 80,
					'section'           => 'title_tagline',
					'partial'           => [
						'selector'            => '#site-header .pt-logo',
						'render_callback'     => 'blogsy_logo',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				// Site Identity heading.
				'blogsy_logo_heading_site_identity' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Site Identity', 'blogsy' ),
					'priority'          => 50,
					'section'           => 'title_tagline',
					'toggle'            => false,
				],

				// Logo typography heading.
				'blogsy_typography_logo_heading'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Typography', 'blogsy' ),
					'priority'          => 100,
					'section'           => 'title_tagline',
					'required'          => [
						[
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '==',
						],
					],
				],

				// Site title.
				'blogsy_logo_title_typography'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Site Title', 'blogsy' ),
					'priority'          => 100,
					'section'           => 'title_tagline',
					'required'          => [
						[
							'control'  => 'custom_logo',
							'value'    => false,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_typography_logo_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Site tagline.
				'blogsy_logo_tagline_typography'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Site Tagline', 'blogsy' ),
					'priority'          => 100,
					'section'           => 'title_tagline',
					'required'          => [
						[
							'control'  => 'blogsy_display_tagline',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_typography_logo_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Styling settings
	 */
	protected function get_styling_settings(): array {
		return [
			'blogsy_styling_section' => [
				'blogsy_accent_color'                => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Accent Color', 'blogsy' ),
				],
				'blogsy_second_color'                => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Second Color', 'blogsy' ),
				],
				'blogsy_body_bg'                     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Body Background Color', 'blogsy' ),
				],
				'blogsy_body_color'                  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Body Font Color', 'blogsy' ),
				],
				'blogsy_heading_color'               => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Heading Color', 'blogsy' ),
				],
				'blogsy_button_bg_hover'             => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'opacity'           => false,
					'label'             => esc_html__( 'Button Background Hover', 'blogsy' ),
				],
				'blogsy_button_shape_style'          => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_radio',
					'type'              => 'blogsy-radio-buttonset',
					'label'             => esc_html__( 'Button Shape', 'blogsy' ),
					'choices'           => [
						'circle' => esc_html__( 'Circle', 'blogsy' ),
						'round'  => esc_html__( 'Round', 'blogsy' ),
						'smooth' => esc_html__( 'Smooth', 'blogsy' ),
						'sharp'  => esc_html__( 'Sharp', 'blogsy' ),
					],
				],

				// Card box design options heading.
				'blogsy_card_heading_design_options' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Card Design Options', 'blogsy' ),
					'priority'          => 85,
				],

				// Card box shadow.
				'blogsy_card_widget_box_shadow'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'display'           => [
						'box-shadow' => [],
					],
					'label'             => esc_html__( 'Box Shadow', 'blogsy' ),
					'priority'          => 90,
					'required'          => [
						[
							'control'  => 'blogsy_card_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Card_box border.
				'blogsy_card_widget_border'          => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'display'           => [
						'border' => [
							'style'     => esc_html__( 'Style', 'blogsy' ),
							'color'     => esc_html__( 'Color', 'blogsy' ),
							'width'     => esc_html__( 'Width (px)', 'blogsy' ),
							'positions' => [
								'top'    => esc_html__( 'Top', 'blogsy' ),
								'right'  => esc_html__( 'Right', 'blogsy' ),
								'bottom' => esc_html__( 'Bottom', 'blogsy' ),
								'left'   => esc_html__( 'Left', 'blogsy' ),
							],
						],
					],
					'label'             => esc_html__( 'Border', 'blogsy' ),
					'priority'          => 95,
					'required'          => [
						[
							'control'  => 'blogsy_card_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Card widget background color.
				'blogsy_card_widget_bg_color'        => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'label'             => esc_html__( 'Background Color', 'blogsy' ),
					'priority'          => 100,
					'required'          => [
						[
							'control'  => 'blogsy_card_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Category colors - dynamic per-category
	 */
	protected function get_category_color_settings(): array {
		$categories              = get_categories( [ 'hide_empty' => 1 ] );
		$category_color_settings = [];
		foreach ( $categories as $category ) {
			$category_color_settings[ 'blogsy_category_color_' . esc_attr( $category->term_id ) ] = [
				'transport'         => 'refresh',
				'sanitize_callback' => 'blogsy_sanitize_color',
				'type'              => 'blogsy-color',
				'label'             => sprintf( '%s', esc_html( $category->name ) ),
				'priority'          => 10,
				'opacity'           => false,
			];
		}

		return [ 'blogsy_category_color_section' => $category_color_settings ];
	}

	/**
	 * Typography settings (body, headings, etc.)
	 */
	protected function get_typography_settings(): array {
		return [
			'blogsy_typography_section' => [
				// HTML base font size.
				'blogsy_html_base_font_size'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Base Font Size', 'blogsy' ),
					'description'       => esc_html__( 'REM base of the root (html) element. ( 62.5 Ã— 16 ) / 100  = 10px', 'blogsy' ),
					'min'               => 50,
					'max'               => 100,
					'step'              => 0.5,
					'unit'              => '%',
					'responsive'        => true,
				],

				'blogsy_typo_body_heading'        => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Body & Content', 'blogsy' ),
				],

				'blogsy_typo_body'                => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Body', 'blogsy' ),
					'description'       => esc_html__( 'Customize the body font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_body_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Headings.
				'blogsy_typo_h_heading'           => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'HEADINGS (H1 - H6)', 'blogsy' ),
				],
				'blogsy_typo_h1'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 1', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H1 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],

				],
				'blogsy_typo_h2'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 2', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H2 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_h3'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 3', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H3 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_h4'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 4', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H4 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_h5'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 5', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H5 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_h6'                  => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Heading 6', 'blogsy' ),
					'description'       => esc_html__( 'Customize the H6 font', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_typo_h_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Typo section heading.
				'blogsy_typo_section_heading'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Section/Widgets Heading', 'blogsy' ),
					'default'           => false,
				],
				'blogsy_typo_section_title'       => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Custom Section Heading', 'blogsy' ),
					'display'           => [
						'font-family'    => [],
						'font-size'      => [],
						'font-weight'    => [],
						'font-style'     => [],
						'text-transform' => [],
						'line-height'    => [],
						'letter-spacing' => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_section_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_widgets_title'       => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Default Footer/Sidebar Heading', 'blogsy' ),
					'display'           => [
						'font-family'    => [],
						'font-size'      => [],
						'font-weight'    => [],
						'font-style'     => [],
						'text-transform' => [],
						'line-height'    => [],
						'letter-spacing' => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_section_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_divider_style'            => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Section/Widgets Heading', 'blogsy' ),
					'choices'           => apply_filters(
						'blogsy_divider_style_options',
						[
							'0' => esc_html__( 'Default', 'blogsy' ),
							'1' => esc_html__( 'Style 1', 'blogsy' ),
							'4' => esc_html__( 'Style 2', 'blogsy' ),
						]
					),
					'required'          => [
						[
							'control'  => 'blogsy_typo_section_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Typography single post.
				'blogsy_typo_single_post_heading' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Single Post', 'blogsy' ),
					'default'           => false,
				],
				'blogsy_typo_single_post_title'   => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Single Post Title', 'blogsy' ),
					'display'           => [
						'font-size'   => [],
						'line-height' => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_single_post_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				'blogsy_typo_single_post_content' => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Single Post Content', 'blogsy' ),
					'display'           => [
						'font-size'   => [],
						'line-height' => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_single_post_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Typo terms.
				'blogsy_typo_terms_heading'       => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Terms', 'blogsy' ),
					'default'           => false,
				],
				'blogsy_typo_terms'               => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Terms', 'blogsy' ),
					'display'           => [
						'font-family'    => [],
						'font-size'      => [],
						'font-weight'    => [],
						'font-style'     => [],
						'text-transform' => [],
						'line-height'    => [],
						'letter-spacing' => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_terms_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// menu typography.
				'blogsy_typo_menu_heading'        => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Menu', 'blogsy' ),
					'default'           => false,
				],
				'blogsy_typo_menu'                => [
					'sanitize_callback' => 'blogsy_sanitize_typography',
					'transport'         => 'postMessage',
					'type'              => 'blogsy-typography',
					'label'             => esc_html__( 'Menu', 'blogsy' ),
					'display'           => [
						'font-family'     => [],
						'font-size'       => [],
						'font-weight'     => [],
						'font-style'      => [],
						'text-transform'  => [],
						'text-decoration' => [],
						'line-height'     => [],
						'letter-spacing'  => [],
					],
					'required'          => [
						[
							'control'  => 'blogsy_typo_menu_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Misc settings
	 */
	protected function get_misc_settings(): array {
		return [
			'blogsy_misc_section' => [
				'blogsy_sticky_sidebar' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Sticky Sidebar', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable sticky sidebar.', 'blogsy' ),
					'priority'          => 10,
				],
				'blogsy_site_preloader' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Site Preloader', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable site preloader.', 'blogsy' ),
					'priority'          => 20,
				],
				'blogsy_smooth_scroll'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Smooth Scroll', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable smooth scroll.', 'blogsy' ),
					'priority'          => 30,
				],
				'blogsy_full_size_gif'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Full Size Gif Images', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable full size gif images.', 'blogsy' ),
					'priority'          => 40,
				],
				'blogsy_cursor_effect'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Cursor Effect', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable cursor effect.', 'blogsy' ),
					'priority'          => 50,
				],
			],
		];
	}

	/**
	 * Top bar settings
	 */
	protected function get_topbar_settings(): array {
		return [
			'blogsy_topbar_section' => [
				'blogsy_top_bar_enable'                 => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Top Bar', 'blogsy' ),
					'description'       => esc_html__( 'Top Bar is a section with widgets located above Main Header area.', 'blogsy' ),
				],
				// Top Bar visibility.
				'blogsy_top_bar_visibility'             => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Top Bar is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Top Bar widgets heading.
				'blogsy_top_bar_heading_widgets'        => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Top Bar Widgets', 'blogsy' ),
					'description'       => esc_html__( 'Click the Add Widget button to add available widgets to your Top Bar.', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				'blogsy_top_bar_widgets_align_center'   => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Center Widget Content', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_heading_widgets',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#site-header .blogsy-topbar',
						'render_callback'     => 'blogsy_topbar_output',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				// Top Bar widgets.
				'blogsy_top_bar_widgets'                => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_widget',
					'type'              => 'blogsy-widget',
					'label'             => esc_html__( 'Top Bar Widgets', 'blogsy' ),
					'widgets'           => [
						'text'    => [
							'max_uses' => apply_filters( 'blogsy_top_bar_text_widget_max_uses', 4 ),
						],
						'nav'     => [
							'max_uses' => apply_filters( 'blogsy_top_bar_nav_widget_max_uses', 2 ),
						],
						'socials' => [
							'max_uses' => apply_filters( 'blogsy_top_bar_social_widget_max_uses', 2 ),
							'styles'   => [
								'minimal'        => esc_html__( 'Minimal', 'blogsy' ),
								'rounded'        => esc_html__( 'Rounded', 'blogsy' ),
								'minimal-fill'   => esc_html__( 'Minimal Fill', 'blogsy' ),
								'rounded-fill'   => esc_html__( 'Rounded Fill', 'blogsy' ),
								'rounded-border' => esc_html__( 'Rounded Border', 'blogsy' ),
							],
							'sizes'    => [
								'small'    => esc_html__( 'Small', 'blogsy' ),
								'standard' => esc_html__( 'Standard', 'blogsy' ),
								'large'    => esc_html__( 'Large', 'blogsy' ),
								'xlarge'   => esc_html__( 'Extra Large', 'blogsy' ),
							],
						],
					],
					'locations'         => [
						'left'  => esc_html__( 'Left', 'blogsy' ),
						'right' => esc_html__( 'Right', 'blogsy' ),
					],
					'visibility'        => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_heading_widgets',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '.blogsy-topbar',
						'render_callback'     => 'blogsy_topbar_output',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Top Bar design options heading.
				'blogsy_top_bar_heading_design_options' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Design Options', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Top Bar Background.
				'blogsy_top_bar_background'             => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Background', 'blogsy' ),
					'display'           => [
						'background' => [
							'color'    => esc_html__( 'Solid Color', 'blogsy' ),
							'gradient' => esc_html__( 'Gradient', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_top_bar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Top Bar Text Color.
				'blogsy_top_bar_text_color'             => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Font Color', 'blogsy' ),
					'display'           => [
						'color' => [
							'text-color'       => esc_html__( 'Text Color', 'blogsy' ),
							'link-color'       => esc_html__( 'Link Color', 'blogsy' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_top_bar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Top Bar Border.
				'blogsy_top_bar_border'                 => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Border', 'blogsy' ),
					'display'           => [
						'border' => [
							'style'     => esc_html__( 'Style', 'blogsy' ),
							'color'     => esc_html__( 'Color', 'blogsy' ),
							'width'     => esc_html__( 'Width (px)', 'blogsy' ),
							'positions' => [
								'top'    => esc_html__( 'Top', 'blogsy' ),
								'bottom' => esc_html__( 'Bottom', 'blogsy' ),
							],
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_top_bar_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_top_bar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Header settings
	 */
	protected function get_header_settings(): array {
		return [
			'blogsy_header_section' => [

				'blogsy_header_layout'                   => [
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Prebuilt Header', 'blogsy' ),
					'description'       => esc_html__( 'Select a prebuilt header present', 'blogsy' ),
					'choices'           => blogsy_get_header_layouts_prebuild(),
					'priority'          => 10,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
					],
				],

				'blogsy_sticky_header_status'            => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Sticky Header', 'blogsy' ),
					'description'       => esc_html__( 'Enable sticky header for your site.', 'blogsy' ),
					'priority'          => 30,
				],

				'blogsy_header_navigation_cutoff'        => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable menu cutoff', 'blogsy' ),
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
					],
				],

				'blogsy_header_navigation_cutoff_upto'   => [
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Menu cutoff up to', 'blogsy' ),
					'min'               => 2,
					'max'               => 15,
					'unit'              => '',
					'responsive'        => false,
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
						[
							'control'  => 'blogsy_header_navigation_cutoff',
							'operator' => '==',
							'value'    => true,
						],
					],
				],

				'blogsy_header_navigation_cutoff_text'   => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Menu cutoff text', 'blogsy' ),
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
						[
							'control'  => 'blogsy_header_navigation_cutoff',
							'operator' => '==',
							'value'    => true,
						],
					],
				],

				'blogsy_header_trending_keywords_status' => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Trending Keywords', 'blogsy' ),
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
					],
				],

				'blogsy_header_trending_keywords'        => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_tags_input',
					'type'              => 'blogsy-tags-input',
					'label'             => esc_html__( 'Trending Keywords', 'blogsy' ),
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
						[
							'control'  => 'blogsy_header_trending_keywords_status',
							'operator' => '==',
							'value'    => true,
						],
					],
				],

				'blogsy_header_heading_widgets'          => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Header Widgets', 'blogsy' ),
					'description'       => esc_html__( 'Click the "Add Widget" button to add available widgets to your Header. Click the down arrow icon to expand widget options.', 'blogsy' ),
					'space'             => true,
					'priority'          => 50,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
					],
				],

				'blogsy_header_widgets'                  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_widget',
					'type'              => 'blogsy-widget',
					'label'             => esc_html__( 'Header Widgets', 'blogsy' ),
					'priority'          => 50,
					'widgets'           => apply_filters(
						'blogsy_main_header_widgets',
						[
							'search'    => [
								'max_uses' => 1,
								'styles'   => apply_filters(
									'blogsy_header_search_widget_styles',
									[
										4 => esc_html__( 'Inline', 'blogsy' ),
										3 => esc_html__( 'Expand', 'blogsy' ),
									]
								),
							],
							'darkmode'  => [
								'max_uses' => 1,
							],
							'offcanvas' => [
								'max_uses' => 1,
							],
							'button'    => [
								'max_uses' => 4,
							],
							'socials'   => [
								'max_uses' => 2,
								'styles'   => [
									'minimal'        => esc_html__( 'Minimal', 'blogsy' ),
									'minimal-fill'   => esc_html__( 'Minimal Fill', 'blogsy' ),
									'rounded-border' => esc_html__( 'Rounded Border', 'blogsy' ),
									'rounded-fill'   => esc_html__( 'Rounded Fill', 'blogsy' ),
								],
								'sizes'    => [
									'small'    => esc_html__( 'Small', 'blogsy' ),
									'standard' => esc_html__( 'Standard', 'blogsy' ),
									'large'    => esc_html__( 'Large', 'blogsy' ),
									'xlarge'   => esc_html__( 'Extra Large', 'blogsy' ),
								],
							],
							'text'      => [
								'max_uses' => 4,
							],
						]
					),
					'locations'         => [
						'left'  => esc_html__( 'Left', 'blogsy' ),
						'right' => esc_html__( 'Right', 'blogsy' ),
					],
					'visibility'        => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'operator' => '==',
							'value'    => 'prebuild',
						],
						[
							'control'  => 'blogsy_header_heading_widgets',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_site_header',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_single_post_header',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_site_sticky_header',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_single_post_sticky_header',
							'value'    => '0',
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '.pt-header-inner',
						'render_callback'     => 'blogsy_header_content_output',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Design options heading.
				'blogsy_header_heading_design_options'   => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Design Options', 'blogsy' ),
					'priority'          => 60,
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'value'    => 'prebuild',
							'operator' => '==',
						],
					],
				],

				// Background.
				'blogsy_header_background'               => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Background', 'blogsy' ),
					'priority'          => 60,
					'display'           => [
						'background' => [
							'color'    => esc_html__( 'Solid Color', 'blogsy' ),
							'gradient' => esc_html__( 'Gradient', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'value'    => 'prebuild',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_header_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Text Color.
				'blogsy_header_text_color'               => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Font Color', 'blogsy' ),
					'priority'          => 60,
					'display'           => [
						'color' => [
							'text-color'        => esc_html__( 'Text Color', 'blogsy' ),
							'link-color'        => esc_html__( 'Link Color', 'blogsy' ),
							'link-hover-color'  => esc_html__( 'Link Hover Color', 'blogsy' ),
							'link-active-color' => esc_html__( 'Link Active Color', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'value'    => 'prebuild',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_header_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Border.
				'blogsy_header_border'                   => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Border', 'blogsy' ),
					'priority'          => 60,
					'display'           => [
						'border' => [
							'style'     => esc_html__( 'Style', 'blogsy' ),
							'color'     => esc_html__( 'Color', 'blogsy' ),
							'width'     => esc_html__( 'Width (px)', 'blogsy' ),
							'positions' => [
								'top'    => esc_html__( 'Top', 'blogsy' ),
								'right'  => esc_html__( 'Right', 'blogsy' ),
								'bottom' => esc_html__( 'Bottom', 'blogsy' ),
								'left'   => esc_html__( 'Left', 'blogsy' ),
							],
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_header_present',
							'value'    => 'prebuild',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_header_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Blog archive settings
	 */
	protected function get_blog_archive_settings(): array {
		return [
			'blogsy_blog_archive_section' => [
				// Layout.
				'blogsy_blog_layout'            => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Layout', 'blogsy' ),
					'description'       => esc_html__( 'Choose blog layout.', 'blogsy' ),
					'priority'          => 35,
					'choices'           => apply_filters(
						'blogsy_blog_layout_choices',
						[
							'blog-horizontal' => esc_html__( 'Horizontal', 'blogsy' ),
							'blog-vertical'   => esc_html__( 'Vertical', 'blogsy' ),
							'blog-cover'      => esc_html__( 'Cover', 'blogsy' ),
						]
					),
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
					],
				],

				'blogsy_blog_masonry'           => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Masonry blog', 'blogsy' ),
					'description'       => esc_html__( 'This will affect blog layout on archives, search results and posts page.', 'blogsy' ),
					'priority'          => 35,
					'required'          => [
						[
							'control'  => 'blogsy_blog_layout',
							'value'    => 'blog-vertical',
							'operator' => '==',
						],
					],
				],

				'blogsy_blog_align_center'      => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Align center', 'blogsy' ),
					'description'       => esc_html__( 'This will affect blog layout on archives, search results and posts page.', 'blogsy' ),
					'priority'          => 35,
					'required'          => [
						[
							'control'  => 'blogsy_blog_layout',
							'value'    => 'blog-vertical',
							'operator' => '==',
						],
					],
				],

				// Blog Layout Column.
				'blogsy_blog_layout_column'     => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Layout Column', 'blogsy' ),
					'description'       => esc_html__( 'Choose blog layout Column. This will affect blog layout on archives, search results and posts page.', 'blogsy' ),
					'priority'          => 35,
					'choices'           => [
						1 => esc_html__( 'Full width', 'blogsy' ),
						2 => esc_html__( '1/2', 'blogsy' ),
						3 => esc_html__( '1/3', 'blogsy' ),
						4 => esc_html__( '1/4', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_blog_layout',
							'value'    => 'blog-horizontal',
							'operator' => '!=',
						],
					],
				],

				'blogsy_blog_title_font_size'   => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Title Font Size', 'blogsy' ),
					'description'       => esc_html__( 'Specify post title font size.', 'blogsy' ),
					'responsive'        => true,
					'priority'          => 35,
					'unit'              => [
						[
							'id'   => 'px',
							'name' => 'px',
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
						],
						[
							'id'   => 'em',
							'name' => 'em',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						],
						[
							'id'   => 'rem',
							'name' => 'rem',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						],
					],
				],

				'blogsy_blog_meta'              => [
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Post Meta', 'blogsy' ),
					'description'       => esc_html__( 'Select meta for post.', 'blogsy' ),
					'priority'          => 35,
					'choices'           => apply_filters(
						'blogsy_blog_meta',
						[
							'author-name'   => [ 'title' => esc_html__( 'Author Name', 'blogsy' ) ],
							'author-avatar' => [ 'title' => esc_html__( 'Author Avatar', 'blogsy' ) ],
							'date'          => [ 'title' => esc_html__( 'Date', 'blogsy' ) ],
							'category'      => [ 'title' => esc_html__( 'Category', 'blogsy' ) ],
							'comments'      => [ 'title' => esc_html__( 'Comments', 'blogsy' ) ],
							'reading-time'  => [ 'title' => esc_html__( 'Reading Time', 'blogsy' ) ],
						]
					),
				],

				// Read More Button.
				'blogsy_blog_read_more_enable'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Show Read More Button', 'blogsy' ),
					'priority'          => 35,
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
					],
				],

				// Read More Button Text.
				'blogsy_blog_read_more'         => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Read More', 'blogsy' ),
					'description'       => esc_html__( 'Change Read More Text.', 'blogsy' ),
					'priority'          => 40,
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_blog_read_more_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// select control to choose full or excerpt for post contet.
				'blogsy_post_feed_content_type' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_radio',
					'type'              => 'radio',
					'label'             => esc_html__( 'For each post in a feed, include ', 'blogsy' ),
					'priority'          => 45,
					'choices'           => [
						'0' => __( 'Full text', 'blogsy' ),
						'1' => __( 'Excerpt', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
					],
				],

				// Excerpt Length.
				'blogsy_excerpt_length'         => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Excerpt Length', 'blogsy' ),
					'description'       => esc_html__( 'Number of words displayed in the excerpt.', 'blogsy' ),
					'min'               => 0,
					'max'               => 500,
					'step'              => 1,
					'unit'              => '',
					'responsive'        => false,
					'priority'          => 50,
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control' => 'blogsy_post_feed_content_type',
							'compare' => '==',
							'value'   => '1',
						],
					],
				],

				// Excerpt more.
				'blogsy_excerpt_more'           => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Excerpt More', 'blogsy' ),
					'description'       => esc_html__( 'What to append to excerpt if the text is cut.', 'blogsy' ),
					'priority'          => 60,
					'required'          => [
						[
							'control'  => 'blogsy_archive_template',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control' => 'blogsy_post_feed_content_type',
							'compare' => '==',
							'value'   => '1',
						],
					],
				],
			],
		];
	}

	/**
	 * Ticker settings
	 */
	protected function get_ticker_settings(): array {
		return [
			'blogsy_ticker_section' => [
				// Enable Ticker Section.
				'blogsy_ticker_enable'      => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Ticker Section', 'blogsy' ),
				],

				// Ticker Title.
				'blogsy_ticker_title'       => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Title', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Device Visibility.
				'blogsy_ticker_visibility'  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Ticker News is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Enable Ticker On Pages.
				'blogsy_ticker_enable_on'   => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Ticker News.', 'blogsy' ),
					'choices'           => [
						'home'       => [ 'title' => esc_html__( 'Home Page', 'blogsy' ) ],
						'posts_page' => [ 'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ) ],
						'archive'    => [ 'title' => esc_html__( 'Archive Page', 'blogsy' ) ],
						'search'     => [ 'title' => esc_html__( 'Search Page', 'blogsy' ) ],
						'post'       => [ 'title' => esc_html__( 'Single Post', 'blogsy' ) ],
					],
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post Number.
				'blogsy_ticker_post_number' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Post Number', 'blogsy' ),
					'description'       => esc_html__( 'Set the number of posts to show.', 'blogsy' ),
					'min'               => 1,
					'max'               => 500,
					'step'              => 1,
					'unit'              => '',
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-ticker',
						'render_callback'     => 'blogsy_blog_ticker',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Post Speed.
				'blogsy_ticker_speed'       => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Speed', 'blogsy' ),
					'description'       => esc_html__( 'Set the speed of the ticker.', 'blogsy' ),
					'min'               => 1,
					'max'               => 1000,
					'step'              => 1,
					'unit'              => 's',
					'responsive'        => true,
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post Category.
				'blogsy_ticker_category'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Category', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'category',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-ticker',
						'render_callback'     => 'blogsy_blog_ticker',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Ticker Elements.
				'blogsy_ticker_elements'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_sortable',
					'type'              => 'blogsy-sortable',
					'label'             => esc_html__( 'Post Elements', 'blogsy' ),
					'description'       => esc_html__( 'Set order and visibility for post elements.', 'blogsy' ),
					'choices'           => [
						'thumbnail'  => esc_html__( 'Thumbnail', 'blogsy' ),
						'meta'       => esc_html__( 'Post Details', 'blogsy' ),
						'play_pause' => esc_html__( 'Play / Pause', 'blogsy' ),
					],
					'sortable'          => false,
					'required'          => [
						[
							'control'  => 'blogsy_ticker_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-ticker',
						'render_callback'     => 'blogsy_blog_ticker',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],
			],
		];
	}

	/**
	 * Hero settings
	 */
	protected function get_hero_settings(): array {
		return [
			'blogsy_hero_section' => [
				// Hero enable.
				'blogsy_hero_enable'                  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Hero Section', 'blogsy' ),
				],

				// Visibility.
				'blogsy_hero_visibility'              => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Hero is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero display on.
				'blogsy_hero_enable_on'               => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Hero. ', 'blogsy' ),
					'choices'           => [
						'home'       => [
							'title' => esc_html__( 'Home Page', 'blogsy' ),
						],
						'posts_page' => [
							'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ),
						],
						'archive'    => [
							'title' => esc_html__( 'Archive Page', 'blogsy' ),
						],
						'search'     => [
							'title' => esc_html__( 'Search Page', 'blogsy' ),
						],
						'post'       => [
							'title' => esc_html__( 'Single Post', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				'blogsy_hero_slider_settings_heading' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Post Settings', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post count.
				'blogsy_hero_slider_post_number'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Post Number', 'blogsy' ),
					'description'       => esc_html__( 'Set the number of visible posts.', 'blogsy' ),
					'min'               => 1,
					'max'               => 50,
					'step'              => 1,
					'unit'              => '',
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-hero',
						'render_callback'     => 'blogsy_blog_hero',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Post category.
				'blogsy_hero_slider_category'         => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Category', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'category',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post tags.
				'blogsy_hero_slider_tags'             => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Tags', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected tags only.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'tags',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero Slider Orderby.
				'blogsy_hero_slider_orderby'          => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Orderby', 'blogsy' ),
					'description'       => esc_html__( 'Show post orderby DESC/ASC.', 'blogsy' ),
					'choices'           => [
						'date-desc'  => esc_html__( 'Newest - Oldest', 'blogsy' ),
						'date-asc'   => esc_html__( 'Oldest - Newest', 'blogsy' ),
						'title-asc'  => esc_html__( 'A - Z', 'blogsy' ),
						'title-desc' => esc_html__( 'Z - A', 'blogsy' ),
						'rand-desc'  => esc_html__( 'Random', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero Slider Style heading.
				'blogsy_hero_slider_style_heading'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Style', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero Slider height.
				'blogsy_hero_slider_height'           => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Height', 'blogsy' ),
					'description'       => esc_html__( 'Set the height of the container.', 'blogsy' ),
					'min'               => 350,
					'max'               => 1000,
					'step'              => 1,
					'unit'              => 'px',
					'responsive'        => true,
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_style_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero Slider Font Size.
				'blogsy_hero_slider_title_font_size'  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_responsive',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Title Font Size', 'blogsy' ),
					'description'       => esc_html__( 'Specify post title font size.', 'blogsy' ),
					'responsive'        => true,
					'unit'              => [
						[
							'id'   => 'px',
							'name' => 'px',
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
						],
						[
							'id'   => 'em',
							'name' => 'em',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						],
						[
							'id'   => 'rem',
							'name' => 'rem',
							'min'  => 0.5,
							'max'  => 5,
							'step' => 0.01,
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_style_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero Slider Elements.
				'blogsy_hero_slider_elements'         => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_sortable',
					'type'              => 'blogsy-sortable',
					'label'             => esc_html__( 'Post Elements', 'blogsy' ),
					'description'       => esc_html__( 'Set order and visibility for post elements.', 'blogsy' ),
					'sortable'          => false,
					'choices'           => [
						'category' => esc_html__( 'Categories', 'blogsy' ),
						'excerpt'  => esc_html__( 'Post Excerpt', 'blogsy' ),
						'meta'     => esc_html__( 'Post Meta', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_style_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-hero',
						'render_callback'     => 'blogsy_blog_hero',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Excerpt Length.
				'blogsy_hero_slider_excerpt_length'   => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Excerpt Length', 'blogsy' ),
					'description'       => esc_html__( 'Number of words displayed in the excerpt.', 'blogsy' ),
					'min'               => 0,
					'max'               => 500,
					'step'              => 1,
					'unit'              => '',
					'responsive'        => false,
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_hero_slider_style_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Hero page heading.
				'blogsy_hero_page_heading'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Hero page', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_hero_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				'blogsy_hero_page'                    => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'absint',
					'type'              => 'dropdown-pages',
					'allow_addition'    => true,
					'label'             => esc_html__( 'Select a page', 'blogsy' ),
					'description'       => esc_html__( 'Content of the selected page will be shown instead of the slider.', 'blogsy' ),
					'active_callback'   => function ( $control ) {
						$hero_enable       = $control->manager->get_setting( 'blogsy_hero_enable' )->value();
						$hero_page_heading = $control->manager->get_setting( 'blogsy_hero_page_heading' )->value();
						return true === $hero_enable || true === $hero_page_heading;
					},
				],
			],
		];
	}

	/**
	 * Stories settings
	 */
	protected function get_stories_settings(): array {
		return [
			'blogsy_stories_section' => [
				// STORIES enable.
				'blogsy_stories_enable'           => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Stories', 'blogsy' ),
				],

				// Title.
				'blogsy_stories_title'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Title', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Title.
				'blogsy_stories_view_all'         => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'View All Stories', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Visibility.
				'blogsy_stories_visibility'       => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Posts You Might Like is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Display on.
				'blogsy_stories_enable_on'        => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Posts You Might Like. ', 'blogsy' ),
					'choices'           => [
						'home'       => [
							'title' => esc_html__( 'Home Page', 'blogsy' ),
						],
						'posts_page' => [
							'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ),
						],
						'archive'    => [
							'title' => esc_html__( 'Archive Page', 'blogsy' ),
						],
						'search'     => [
							'title' => esc_html__( 'Search Page', 'blogsy' ),
						],
						'post'       => [
							'title' => esc_html__( 'Single Post', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Settings heading.
				'blogsy_stories_settings_heading' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Post Settings', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post category.
				'blogsy_stories_category'         => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Category', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'category',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_stories_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				'blogsy_stories_max_category'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Number of max category', 'blogsy' ),
					'min'               => 1,
					'max'               => 20,
					'step'              => 1,
					'unit'              => '',
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_stories_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-stories',
						'render_callback'     => 'blogsy_blog_stories',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				'blogsy_stories_max_inner_items'  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Max number of inner items', 'blogsy' ),
					'min'               => 1,
					'max'               => 10,
					'step'              => 1,
					'unit'              => '',
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_stories_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-stories',
						'render_callback'     => 'blogsy_blog_stories',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Post Orderby.
				'blogsy_stories_orderby'          => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Orderby', 'blogsy' ),
					'description'       => esc_html__( 'Show post orderby DESC/ASC.', 'blogsy' ),
					'choices'           => [
						'date-desc'  => esc_html__( 'Newest - Oldest', 'blogsy' ),
						'date-asc'   => esc_html__( 'Oldest - Newest', 'blogsy' ),
						'title-asc'  => esc_html__( 'A - Z', 'blogsy' ),
						'title-desc' => esc_html__( 'Z - A', 'blogsy' ),
						'rand-desc'  => esc_html__( 'Random', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_stories_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post Elements.
				'blogsy_stories_elements'         => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_sortable',
					'type'              => 'blogsy-sortable',
					'label'             => esc_html__( 'Post Elements', 'blogsy' ),
					'description'       => esc_html__( 'Set order and visibility for post elements.', 'blogsy' ),
					'sortable'          => false,
					'choices'           => [
						'title'    => esc_html__( 'Title', 'blogsy' ),
						'category' => esc_html__( 'Categories', 'blogsy' ),
						'meta'     => esc_html__( 'Post Meta', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_stories_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_stories_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-stories',
						'render_callback'     => 'blogsy_blog_stories',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],
			],
		];
	}

	/**
	 * Featured category settings
	 */
	protected function get_featured_category_settings(): array {
		return [
			'blogsy_featured_category_section' => [
				// Featured Category Enable.
				'blogsy_featured_category_enable'     => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable featured category', 'blogsy' ),
					'priority'          => 5,
				],

				// Title.
				'blogsy_featured_category_title'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Title', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Type.
				'blogsy_featured_category_style'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Type', 'blogsy' ),
					'description'       => esc_html__( 'Choose featured style type', 'blogsy' ),
					'choices'           => [
						'one' => esc_html__( 'Style One', 'blogsy' ),
						'two' => esc_html__( 'Style Two', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-category',
						'render_callback'     => 'blogsy_blog_featured_category',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Featured Category (Repeater).
				'blogsy_featured_category'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_repeater_sanitize',
					'type'              => 'blogsy-repeater',
					'label'             => esc_html__( 'Featured Category', 'blogsy' ),
					'item_name'         => esc_html__( 'Featured Category', 'blogsy' ),
					'title_format'      => esc_html__( '[live_title]', 'blogsy' ),
					'live_title_id'     => 'category',
					'add_text'          => esc_html__( 'Add new item', 'blogsy' ),
					'max_item'          => apply_filters( 'blogsy_featured_category_max_items', 5 ),
					'limited_msg'       => wp_kses( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/blogsy/">Blogsy Pro</a> to be able to add more items and unlock other premium features!', blogsy_get_allowed_html_tags() ),
					'fields'            => [
						'category' => [
							'title'       => esc_html__( 'Select category', 'blogsy' ),
							'type'        => 'select',
							'is_select2'  => true,
							'data_source' => 'category',
						],
						'image'    => [
							'title' => esc_html__( 'Image', 'blogsy' ),
							'type'  => 'media',
						],
						'color'    => [
							'title' => esc_html__( 'Accent Color', 'blogsy' ),
							'type'  => 'color',
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-category',
						'render_callback'     => 'blogsy_blog_featured_category',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Column.
				'blogsy_featured_category_column'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Column', 'blogsy' ),
					'description'       => esc_html__( 'Select column', 'blogsy' ),
					'choices'           => [
						1 => esc_html__( 'Full width', 'blogsy' ),
						2 => esc_html__( '1/2', 'blogsy' ),
						3 => esc_html__( '1/3', 'blogsy' ),
						4 => esc_html__( '1/4', 'blogsy' ),
						5 => esc_html__( '1/5', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_featured_category_style',
							'value'    => 'three',
							'operator' => '!=',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-category',
						'render_callback'     => 'blogsy_blog_featured_category',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Device Visibility.
				'blogsy_featured_category_visibility' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Posts You Might Like is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Display On Pages.
				'blogsy_featured_category_enable_on'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Featured Category.', 'blogsy' ),
					'choices'           => [
						'home'       => [ 'title' => esc_html__( 'Home Page', 'blogsy' ) ],
						'posts_page' => [ 'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ) ],
						'archive'    => [ 'title' => esc_html__( 'Archive Page', 'blogsy' ) ],
						'search'     => [ 'title' => esc_html__( 'Search Page', 'blogsy' ) ],
						'post'       => [ 'title' => esc_html__( 'Single Post', 'blogsy' ) ],
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_category_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Featured links settings
	 */
	protected function get_featured_links_settings(): array {
		return [
			'blogsy_featured_links_section' => [
				// Featured Links Enable.
				'blogsy_featured_links_enable'     => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable featured links', 'blogsy' ),
					'priority'          => 5,
				],

				// Title.
				'blogsy_featured_links_title'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Title', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Type.
				'blogsy_featured_links_style'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Type', 'blogsy' ),
					'description'       => esc_html__( 'Choose featured style type', 'blogsy' ),
					'choices'           => [
						'one'   => esc_html__( 'Style One', 'blogsy' ),
						'two'   => esc_html__( 'Style Two', 'blogsy' ),
						'three' => esc_html__( 'Style Three', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-links',
						'render_callback'     => 'blogsy_blog_featured_links',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Featured Links (Repeater).
				'blogsy_featured_links'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_repeater_sanitize',
					'type'              => 'blogsy-repeater',
					'label'             => esc_html__( 'Featured Links', 'blogsy' ),
					'item_name'         => esc_html__( 'Featured Link', 'blogsy' ),
					'title_format'      => esc_html__( '[live_title]', 'blogsy' ),
					'live_title_id'     => 'link',
					'add_text'          => esc_html__( 'Add new Feature', 'blogsy' ),
					'max_item'          => apply_filters( 'blogsy_featured_links_max_items', 5 ),
					'limited_msg'       => wp_kses( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/blogsy/">Blogsy</a> to be able to add more items and unlock other premium features!', blogsy_get_allowed_html_tags() ),
					'fields'            => [
						'link'  => [
							'title' => esc_html__( 'Select feature link', 'blogsy' ),
							'type'  => 'link',
						],
						'image' => [
							'title' => esc_html__( 'Image', 'blogsy' ),
							'type'  => 'media',
						],
						'color' => [
							'title' => esc_html__( 'Accent Color', 'blogsy' ),
							'type'  => 'color',
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-links',
						'render_callback'     => 'blogsy_blog_featured_links',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Column.
				'blogsy_featured_links_column'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Column', 'blogsy' ),
					'description'       => esc_html__( 'Select column', 'blogsy' ),
					'choices'           => [
						1 => esc_html__( 'Full width', 'blogsy' ),
						2 => esc_html__( '1/2', 'blogsy' ),
						3 => esc_html__( '1/3', 'blogsy' ),
						4 => esc_html__( '1/4', 'blogsy' ),
						5 => esc_html__( '1/5', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-featured-links',
						'render_callback'     => 'blogsy_blog_featured_links',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Device Visibility.
				'blogsy_featured_links_visibility' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Posts You Might Like is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Display On Pages.
				'blogsy_featured_links_enable_on'  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Featured links.', 'blogsy' ),
					'choices'           => [
						'home'       => [ 'title' => esc_html__( 'Home Page', 'blogsy' ) ],
						'posts_page' => [ 'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ) ],
						'archive'    => [ 'title' => esc_html__( 'Archive Page', 'blogsy' ) ],
						'search'     => [ 'title' => esc_html__( 'Search Page', 'blogsy' ) ],
						'post'       => [ 'title' => esc_html__( 'Single Post', 'blogsy' ) ],
					],
					'required'          => [
						[
							'control'  => 'blogsy_featured_links_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * PYML settings
	 */
	protected function get_pyml_settings(): array {
		return [
			'blogsy_pyml_section' => [
				// PYML enable.
				'blogsy_pyml_enable'           => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Enable Posts You Might Like Section', 'blogsy' ),
				],

				// Title.
				'blogsy_pyml_title'            => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'sanitize_text_field',
					'type'              => 'blogsy-text',
					'label'             => esc_html__( 'Title', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Visibility.
				'blogsy_pyml_visibility'       => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Device Visibility', 'blogsy' ),
					'description'       => esc_html__( 'Devices where the Posts You Might Like is displayed.', 'blogsy' ),
					'choices'           => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Display on.
				'blogsy_pyml_enable_on'        => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Enable On: ', 'blogsy' ),
					'description'       => esc_html__( 'Choose on which pages you want to enable Posts You Might Like. ', 'blogsy' ),
					'choices'           => [
						'home'       => [
							'title' => esc_html__( 'Home Page', 'blogsy' ),
						],
						'posts_page' => [
							'title' => esc_html__( 'Blog / Posts Page', 'blogsy' ),
						],
						'archive'    => [
							'title' => esc_html__( 'Archive Page', 'blogsy' ),
						],
						'search'     => [
							'title' => esc_html__( 'Search Page', 'blogsy' ),
						],
						'post'       => [
							'title' => esc_html__( 'Single Post', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Settings heading.
				'blogsy_pyml_settings_heading' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Post Settings', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post count.
				'blogsy_pyml_post_number'      => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_range',
					'type'              => 'blogsy-range',
					'label'             => esc_html__( 'Post Number', 'blogsy' ),
					'description'       => esc_html__( 'Set the number of visible posts.', 'blogsy' ),
					'min'               => 1,
					'max'               => 50,
					'step'              => 1,
					'unit'              => '',
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_pyml_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-pyml',
						'render_callback'     => 'blogsy_blog_pyml',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],

				// Post category.
				'blogsy_pyml_category'         => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Category', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected category only. Leave empty to include all.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'category',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_pyml_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post tags.
				'blogsy_pyml_tags'             => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Tags', 'blogsy' ),
					'description'       => esc_html__( 'Display posts from selected tags only.', 'blogsy' ),
					'is_select2'        => true,
					'data_source'       => 'tags',
					'multiple'          => true,
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_pyml_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post Orderby.
				'blogsy_pyml_orderby'          => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Orderby', 'blogsy' ),
					'description'       => esc_html__( 'Show post orderby DESC/ASC.', 'blogsy' ),
					'choices'           => [
						'date-desc'  => esc_html__( 'Newest - Oldest', 'blogsy' ),
						'date-asc'   => esc_html__( 'Oldest - Newest', 'blogsy' ),
						'title-asc'  => esc_html__( 'A - Z', 'blogsy' ),
						'title-desc' => esc_html__( 'Z - A', 'blogsy' ),
						'rand-desc'  => esc_html__( 'Random', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_pyml_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Post Elements.
				'blogsy_pyml_elements'         => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_sortable',
					'type'              => 'blogsy-sortable',
					'label'             => esc_html__( 'Post Elements', 'blogsy' ),
					'description'       => esc_html__( 'Set order and visibility for post elements.', 'blogsy' ),
					'sortable'          => false,
					'choices'           => [
						'category' => esc_html__( 'Categories', 'blogsy' ),
						'meta'     => esc_html__( 'Post Meta', 'blogsy' ),
					],
					'required'          => [
						[
							'control'  => 'blogsy_pyml_enable',
							'value'    => true,
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_pyml_settings_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-pyml',
						'render_callback'     => 'blogsy_blog_pyml',
						'container_inclusive' => true,
						'fallback_refresh'    => true,
					],
				],
			],
		];
	}

	/**
	 * Advertisement settings
	 */
	protected function get_advertisement_settings(): array {
		return [
			'blogsy_advertisement_section' => [
				'blogsy_ad_widgets' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_widget',
					'type'              => 'blogsy-widget',
					'label'             => esc_html__( 'Advertisement Widget', 'blogsy' ),
					'widgets'           => apply_filters(
						'blogsy_main_ad_widgets',
						[
							'advertisements' => [
								'max_uses'      => apply_filters( 'blogsy_advertisement_max_uses', 3 ),
								'display_areas' => [
									'before_header'        => esc_html__( 'Before Header', 'blogsy' ),
									'after_header'         => esc_html__( 'After Header', 'blogsy' ),
									'before_post_archive'  => esc_html__( 'Before post archive', 'blogsy' ),
									'random_post_archives' => esc_html__( 'Random post archives', 'blogsy' ),
									'before_post_content'  => esc_html__( 'Before post content', 'blogsy' ),
									'after_post_content'   => esc_html__( 'After post content', 'blogsy' ),
									'before_footer'        => esc_html__( 'Before footer', 'blogsy' ),
									'after_footer'         => esc_html__( 'After footer', 'blogsy' ),
								],
							],
						]
					),
					'visibility'        => [
						'all'                => esc_html__( 'Show on All Devices', 'blogsy' ),
						'hide-mobile'        => esc_html__( 'Hide on Mobile', 'blogsy' ),
						'hide-tablet'        => esc_html__( 'Hide on Tablet', 'blogsy' ),
						'hide-mobile-tablet' => esc_html__( 'Hide on Mobile and Tablet', 'blogsy' ),
					],
				],
			],
		];
	}

	/**
	 * Footer settings
	 */
	protected function get_footer_settings(): array {
		return [
			'blogsy_footer_section' => [
				'blogsy_footer_layout'                => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-radio-image',
					'label'             => esc_html__( 'Column Layout', 'blogsy' ),
					'description'       => esc_html__( 'Choose your site&rsquo;s footer column layout.', 'blogsy' ),
					'priority'          => 10,
					'choices'           => [
						'layout-1' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-1.svg',
							'title' => esc_html__( '1/4 + 1/4 + 1/4 + 1/4', 'blogsy' ),
						],
						'layout-2' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-2.svg',
							'title' => esc_html__( '1/3 + 1/3 + 1/3', 'blogsy' ),
						],
						'layout-3' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-3.svg',
							'title' => esc_html__( '2/3 + 1/3', 'blogsy' ),
						],
						'layout-4' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-4.svg',
							'title' => esc_html__( '1/3 + 2/3', 'blogsy' ),
						],
						'layout-5' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-5.svg',
							'title' => esc_html__( '2/3 + 1/4 + 1/4', 'blogsy' ),
						],
						'layout-6' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-6.svg',
							'title' => esc_html__( '1/4 + 1/4 + 2/3', 'blogsy' ),
						],
						'layout-7' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-7.svg',
							'title' => esc_html__( '1/2 + 1/2', 'blogsy' ),
						],
						'layout-8' => [
							'image' => BLOGSY_THEME_URI . '/inc/customizer/assets/images/footer-layout-8.svg',
							'title' => esc_html__( '1', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-footer-widgets',
						'render_callback'     => 'blogsy_footer_widgets',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				'blogsy_footer_widgets_align_center'  => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Center Widget Content', 'blogsy' ),
					'priority'          => 10,
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
					],
					'partial'           => [
						'selector'            => '#blogsy-footer-widgets',
						'render_callback'     => 'blogsy_footer_widgets',
						'container_inclusive' => false,
						'fallback_refresh'    => true,
					],
				],

				'blogsy_footer_copyright_textarea'    => [
					'sanitize_callback' => 'blogsy_sanitize_textarea',
					'type'              => 'blogsy-textarea',
					'label'             => esc_html__( 'Footer Copyright', 'blogsy' ),
					'priority'          => 10,
				],
				'blogsy_back_to_top'                  => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Back to Top Button', 'blogsy' ),
					'description'       => esc_html__( 'Enable or disable back to top button.', 'blogsy' ),
					'priority'          => 15,
				],

				// Footer widget design options heading.
				'blogsy_footer_widget_design_heading' => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Design Options', 'blogsy' ),
					'priority'          => 25,
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
					],
				],
				// Footer widget background.
				'blogsy_footer_widget_background'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Background', 'blogsy' ),
					'priority'          => 30,
					'display'           => [
						'background' => [
							'color'    => esc_html__( 'Solid Color', 'blogsy' ),
							'gradient' => esc_html__( 'Gradient', 'blogsy' ),
							'image'    => esc_html__( 'Image', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_footer_widget_design_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],

				// Footer widget Text Color.
				'blogsy_footer_widget_text_color'     => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Font Color', 'blogsy' ),
					'priority'          => 35,
					'display'           => [
						'color' => [
							'text-color'       => esc_html__( 'Text Color', 'blogsy' ),
							'link-color'       => esc_html__( 'Link Color', 'blogsy' ),
							'link-hover-color' => esc_html__( 'Link Hover Color', 'blogsy' ),
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_footer_widget_design_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Footer widget area border.
				'blogsy_footer_widget_area_border'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'label'             => esc_html__( 'Border', 'blogsy' ),
					'priority'          => 40,
					'display'           => [
						'border' => [
							'style'     => esc_html__( 'Style', 'blogsy' ),
							'color'     => esc_html__( 'Color', 'blogsy' ),
							'width'     => esc_html__( 'Width (px)', 'blogsy' ),
							'positions' => [
								'top'    => esc_html__( 'Top', 'blogsy' ),
								'bottom' => esc_html__( 'Bottom', 'blogsy' ),
							],
						],
					],
					'required'          => [
						[
							'control'  => 'blogsy_site_footer',
							'value'    => '0',
							'operator' => '==',
						],
						[
							'control'  => 'blogsy_footer_widget_design_heading',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Sidebar settings
	 */
	protected function get_sidebar_settings(): array {
		return [
			'blogsy_sidebar_section' => [
				'blogsy_single_page_sidebar_position'      => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Single Page Sidebar Position', 'blogsy' ),
					'choices'           => [
						'left'        => esc_html__( 'Left', 'blogsy' ),
						'right'       => esc_html__( 'Right', 'blogsy' ),
						'none'        => esc_html__( 'No Sidebar', 'blogsy' ),
						'none-narrow' => esc_html__( 'No Sidebar + Narrow Content', 'blogsy' ),
					],
					'priority'          => 10,
				],

				'blogsy_single_post_sidebar_position'      => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Single Post Sidebar Position', 'blogsy' ),
					'choices'           => [
						'left'        => esc_html__( 'Left', 'blogsy' ),
						'right'       => esc_html__( 'Right', 'blogsy' ),
						'none'        => esc_html__( 'No Sidebar', 'blogsy' ),
						'none-narrow' => esc_html__( 'No Sidebar + Narrow Content', 'blogsy' ),
					],
					'priority'          => 30,
				],

				'blogsy_blog_sidebar_position'             => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Blog Sidebar Position', 'blogsy' ),
					'choices'           => [
						'left'  => esc_html__( 'Left', 'blogsy' ),
						'right' => esc_html__( 'Right', 'blogsy' ),
						'none'  => esc_html__( 'No Sidebar', 'blogsy' ),
					],
					'priority'          => 50,
				],

				'blogsy_woocommerce_shop_sidebar_position' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'WooCommerce Shop Sidebar Position', 'blogsy' ),
					'choices'           => [
						'left'  => esc_html__( 'Left', 'blogsy' ),
						'right' => esc_html__( 'Right', 'blogsy' ),
						'none'  => esc_html__( 'No Sidebar', 'blogsy' ),
					],
					'priority'          => 70,
				],
				'blogsy_woocommerce_archive_sidebar_position' => [
					'transport'         => 'refresh',
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'WooCommerce Archive Sidebar Position', 'blogsy' ),
					'choices'           => [
						'left'  => esc_html__( 'Left', 'blogsy' ),
						'right' => esc_html__( 'Right', 'blogsy' ),
						'none'  => esc_html__( 'No Sidebar', 'blogsy' ),
					],
					'priority'          => 80,
				],

				// Top Bar design options heading.
				'blogsy_sidebar_heading_design_options'    => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-heading',
					'label'             => esc_html__( 'Design Options', 'blogsy' ),
					'priority'          => 85,
				],

				// Sidebar widget box shadow.
				'blogsy_sidebar_widget_box_shadow'         => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'display'           => [
						'box-shadow' => [],
					],
					'label'             => esc_html__( 'Box Shadow', 'blogsy' ),
					'priority'          => 90,
					'required'          => [
						[
							'control'  => 'blogsy_sidebar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Sidebar widget border.
				'blogsy_sidebar_widget_border'             => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_design_options',
					'type'              => 'blogsy-design-options',
					'display'           => [
						'border' => [
							'style'     => esc_html__( 'Style', 'blogsy' ),
							'color'     => esc_html__( 'Color', 'blogsy' ),
							'width'     => esc_html__( 'Width (px)', 'blogsy' ),
							'positions' => [
								'top'    => esc_html__( 'Top', 'blogsy' ),
								'right'  => esc_html__( 'Right', 'blogsy' ),
								'bottom' => esc_html__( 'Bottom', 'blogsy' ),
								'left'   => esc_html__( 'Left', 'blogsy' ),
							],
						],
					],
					'label'             => esc_html__( 'Border', 'blogsy' ),
					'priority'          => 95,
					'required'          => [
						[
							'control'  => 'blogsy_sidebar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
				// Sidebar widget background color.
				'blogsy_sidebar_widget_bg_color'           => [
					'transport'         => 'postMessage',
					'sanitize_callback' => 'blogsy_sanitize_color',
					'type'              => 'blogsy-color',
					'label'             => esc_html__( 'Background Color', 'blogsy' ),
					'priority'          => 100,
					'required'          => [
						[
							'control'  => 'blogsy_sidebar_heading_design_options',
							'value'    => true,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Single post settings
	 */
	protected function get_single_post_settings(): array {
		return [
			'blogsy_single_post_hero_section' => [
				'blogsy_single_post_meta'          => [
					'sanitize_callback' => 'blogsy_no_sanitize',
					'type'              => 'blogsy-checkbox-group',
					'label'             => esc_html__( 'Post Meta', 'blogsy' ),
					'description'       => esc_html__( 'Select meta for post.', 'blogsy' ),
					'priority'          => 5,
					'choices'           => apply_filters(
						'blogsy_single_post_meta',
						[
							'author-name'   => [ 'title' => esc_html__( 'Author Name', 'blogsy' ) ],
							'author-avatar' => [ 'title' => esc_html__( 'Author Avatar', 'blogsy' ) ],
							'date'          => [ 'title' => esc_html__( 'Date', 'blogsy' ) ],
							'date-updated'  => [ 'title' => esc_html__( 'Updated Date', 'blogsy' ) ],
							'category'      => [ 'title' => esc_html__( 'Category', 'blogsy' ) ],
							'comments'      => [ 'title' => esc_html__( 'Comments', 'blogsy' ) ],
							'reading-time'  => [ 'title' => esc_html__( 'Reading Time', 'blogsy' ) ],
						]
					),
				],

				'blogsy_single_hero'               => [
					'sanitize_callback' => 'blogsy_sanitize_select',
					'type'              => 'blogsy-select',
					'label'             => esc_html__( 'Standard Post Layout', 'blogsy' ),
					'subtitle'          => esc_html__( 'Select default layout for standard posts. You can customize settings for each layout below.', 'blogsy' ),
					'priority'          => 10,
					'choices'           => blogsy_get_single_post_hero_layouts(),
				],

				'blogsy_single_hero_1_fit'         => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Layout 1: Fit Image', 'blogsy' ),
					'priority'          => 15,
					'required'          => [
						[
							'control'  => 'blogsy_single_hero',
							'value'    => '1',
							'operator' => '==',
						],
					],
				],

				'blogsy_single_hero_1_full_img'    => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Layout 1: Full Height Image', 'blogsy' ),
					'priority'          => 20,
					'required'          => [
						[
							'control'  => 'blogsy_single_hero',
							'value'    => '1',
							'operator' => '==',
						],
					],
				],

				'blogsy_single_hero_2_disable_img' => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Layout 2: Disable Image', 'blogsy' ),
					'priority'          => 15,
					'required'          => [
						[
							'control'  => 'blogsy_single_hero',
							'value'    => '2',
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Dark mode settings
	 */
	protected function get_dark_mode_settings(): array {
		return [
			'blogsy_dark_mode_section' => [
				'blogsy_dark_mode'            => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Dark Mode', 'blogsy' ),
					'description'       => esc_html__( 'Enable dark mode for your site.', 'blogsy' ),
					'priority'          => 10,
				],

				'blogsy_default_theme_scheme' => [
					'sanitize_callback' => 'blogsy_sanitize_radio',
					'type'              => 'blogsy-radio-buttonset',
					'label'             => esc_html__( 'Default Scheme', 'blogsy' ),
					'choices'           => [
						'light'  => esc_html__( 'Light', 'blogsy' ),
						'dark'   => esc_html__( 'Dark', 'blogsy' ),
						'device' => esc_html__( 'Base on Device', 'blogsy' ),
					],
					'priority'          => 20,
					'required'          => [
						[
							'control'  => 'blogsy_dark_mode',
							'value'    => 1,
							'operator' => '==',
						],
					],
				],

				'blogsy_always_dark_mode'     => [
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Always Dark Mode', 'blogsy' ),
					'description'       => esc_html__( 'Always load site in dark style and disable the dark mode switcher.', 'blogsy' ),
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'priority'          => 30,
					'required'          => [
						[
							'control'  => 'blogsy_dark_mode',
							'value'    => 1,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Breadcrumb settings
	 */
	protected function get_breadcrumb_settings(): array {
		return [
			'blogsy_breadcrumb_section' => [
				'blogsy_breadcrumb'        => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Breadcrumb', 'blogsy' ),
					'description'       => esc_html__( 'Show breadcrumb bar.', 'blogsy' ),
				],

				'blogsy_breadcrumb_schema' => [
					'sanitize_callback' => 'blogsy_sanitize_toggle',
					'type'              => 'blogsy-toggle',
					'label'             => esc_html__( 'Breadcrumb Schema', 'blogsy' ),
					'description'       => esc_html__( 'Output breadcrumb structure data.', 'blogsy' ),
					'required'          => [
						[
							'control'  => 'blogsy_breadcrumb',
							'value'    => 1,
							'operator' => '==',
						],
					],
				],
			],
		];
	}

	/**
	 * Upsell & docs hidden settings
	 */
	protected function get_upsell_docs_settings(): array {
		return [
			'blogsy_section_upsell_button' => [
				'blogsy_section_upsell_heading' => [
					'type' => 'hidden',
				],
			],
			'blogsy_section_docs_button'   => [
				'blogsy_section_docs_heading' => [
					'type' => 'hidden',
				],
			],
		];
	}
}
