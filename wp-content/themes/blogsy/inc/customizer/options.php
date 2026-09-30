<?php
/**
 * Blogsy Options Class.
 *
 * @package  Blogsy
 * @author   Peregrine Themes
 * @since    1.0.0
 */

namespace Blogsy\Customizer;

use Blogsy\Helper;

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Blogsy Options Class.
 */
class Options {


	/**
	 * Singleton instance of the class.
	 *
	 * @var Options|null $instance
	 * @since 1.0.0
	 */
	private static ?self $instance = null;

	/**
	 * Options variable.
	 *
	 * @since 1.0.0
	 * @var mixed $options
	 */
	private static array $options = [];

	/**
	 * Main Options Instance.
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
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Refresh options.
		add_action( 'after_setup_theme', [ $this, 'refresh' ] );
	}

	/**
	 * Set default option values.
	 *
	 * @since  1.0.0
	 * @return array Default values.
	 */
	public function get_defaults() {

		$defaults = [
			/**
			 * Logos & Site Title.
			 */
			'blogsy_logo_default_retina'                  => '',
			'blogsy_logo_max_height'                      => [
				'desktop' => 45,
			],
			'blogsy_logo_margin'                          => [
				'desktop' => [
					'top'    => '',
					'right'  => 10,
					'bottom' => '',
					'left'   => '',
				],
				'tablet'  => [
					'top'    => '',
					'right'  => 1,
					'bottom' => '',
					'left'   => '',
				],
				'mobile'  => [
					'top'    => '',
					'right'  => 1,
					'bottom' => '',
					'left'   => '',
				],
				'unit'    => 'px',
			],
			'blogsy_display_tagline'                      => false,
			'blogsy_logo_heading_site_identity'           => true,
			'blogsy_typography_logo_heading'              => false,
			'blogsy_logo_title_typography'                => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '3',
					'font-size-tablet'    => '2.7',
					'font-size-mobile'    => '2.2',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.2',
				]
			),
			'blogsy_logo_tagline_typography'              => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 400,
					'letter-spacing'      => '',
					'font-size-desktop'   => '1.5',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.2',
				]
			),

			/*
			General
			*/
			// layout.
			'blogsy_site_width'                           => 1400,

			// Misc.
			'blogsy_sticky_sidebar'                       => true,
			'blogsy_site_preloader'                       => false,
			'blogsy_smooth_scroll'                        => true,
			'blogsy_full_size_gif'                        => true,
			'blogsy_cursor_effect'                        => false,
			'blogsy_posts_animation'                      => false,

			// Styling.
			'blogsy_accent_color'                         => '',
			'blogsy_second_color'                         => '',
			'blogsy_body_bg'                              => '',
			'blogsy_body_color'                           => '',
			'blogsy_heading_color'                        => '',
			'blogsy_button_bg_hover'                      => '',
			'blogsy_button_shape_style'                   => 'circle',
			'blogsy_card_heading_design_options'          => false,
			'blogsy_card_widget_box_shadow'               => [
				'x'      => 0,
				'y'      => 2,
				'blur'   => 5,
				'spread' => 0,
				'color'  => 'rgba(14,14,19,0.05)',
				'type'   => 'outset',
			],
			'blogsy_card_widget_border'                   => Helper::design_options_defaults(
				[
					'border' => [
						'border-top-width'    => '',
						'border-right-width'  => '',
						'border-bottom-width' => '',
						'border-left-width'   => '',
						'border-style'        => '',
						'border-color'        => '',
					],
				]
			),
			'blogsy_card_widget_bg_color'                 => '#ffffff',

			// Top Bar.
			'blogsy_top_bar_enable'                       => true,
			'blogsy_top_bar_visibility'                   => 'hide-mobile',
			'blogsy_top_bar_heading_widgets'              => true,
			'blogsy_top_bar_widgets_align_center'         => false,
			'blogsy_top_bar_widgets'                      => [
				[
					'classname' => 'blogsy_customizer_widget_text',
					'type'      => 'text',
					'values'    => [
						'content'    => wp_kses( \Blogsy\Icon::get_svg( 'calendar' ) . '<strong><span id="blogsy-date"></span> - <span id="blogsy-time"></span></strong>', blogsy_get_allowed_html_tags() ),
						'location'   => 'left',
						'visibility' => 'all',
					],
				],
				[
					'classname' => 'blogsy_customizer_widget_text',
					'type'      => 'text',
					'values'    => [
						'content'    => wp_kses( \Blogsy\Icon::get_svg( 'location-arrow' ) . 'Subscribe to our newsletter & never miss our best posts. <a href="#"><strong>Subscribe Now!</strong></a>', blogsy_get_allowed_html_tags() ),
						'location'   => 'right',
						'visibility' => 'all',
					],
				],
			],
			'blogsy_top_bar_heading_design_options'       => false,
			'blogsy_top_bar_background'                   => Helper::design_options_defaults(
				[
					'background' => [
						'background-type' => 'gradient',
						'color'           => [
							'background-color' => '#216be9',
						],
						'gradient'        => [
							'gradient-color-1' => '#216be9',
							'gradient-color-2' => '#f84d57',
						],
					],
				]
			),
			'blogsy_top_bar_text_color'                   => Helper::design_options_defaults(
				[
					'color' => [
						'text-color'       => '#ffffff',
						'link-color'       => '#fafafa',
						'link-hover-color' => '#ffffff',
					],
				]
			),
			'blogsy_top_bar_border'                       => Helper::design_options_defaults(
				[
					'border' => [
						'border-top-width' => '',
						'border-style'     => 'solid',
						'border-color'     => '',
					],
				]
			),

			// Main Header.

			'blogsy_sticky_header_status'                 => false,

			'blogsy_header_layout'                        => 'layout-1',
			'blogsy_header_heading_widgets'               => true,
			'blogsy_header_widgets'                       => [
				[
					'classname' => 'blogsy_customizer_widget_socials',
					'type'      => 'socials',
					'values'    => [
						'style'      => 'rounded-border',
						'size'       => 'standard',
						'location'   => 'right',
						'visibility' => 'hide-mobile-tablet',
					],
				],
				[
					'classname' => 'blogsy_customizer_widget_darkmode',
					'type'      => 'darkmode',
					'values'    => [
						'location'   => 'right',
						'visibility' => 'all',
					],
				],
				[
					'classname' => 'blogsy_customizer_widget_search',
					'type'      => 'search',
					'values'    => [
						'style'      => 3,
						'location'   => 'right',
						'visibility' => 'all',
					],
				],
				[
					'classname' => 'blogsy_customizer_widget_button',
					'type'      => 'button',
					'values'    => [
						'text'       => \Blogsy\Icon::get_svg( 'bell' ) . ' Subscribe',
						'url'        => '#',
						'class'      => '',
						'target'     => '_self',
						'location'   => 'right',
						'visibility' => 'hide-mobile',
					],
				],
			],

			'blogsy_header_navigation_cutoff'             => true,
			'blogsy_header_navigation_cutoff_upto'        => 7,
			'blogsy_header_navigation_cutoff_text'        => '',

			'blogsy_header_trending_keywords_status'      => false,
			'blogsy_header_trending_keywords'             => '',

			'blogsy_header_heading_design_options'        => false,
			'blogsy_header_background'                    => Helper::design_options_defaults(
				[
					'background' => [
						'color'    => [
							'background-color' => '#ffffff',
						],
						'gradient' => [
							'gradient-color-1' => '#216be9',
							'gradient-color-2' => '#f84d57',
						],
					],
				]
			),
			'blogsy_header_text_color'                    => Helper::design_options_defaults(
				[
					'color' => [
						'text-color'        => '#29294b',
						'link-color'        => '#29294b',
						'link-hover-color'  => '#216be9',
						'link-active-color' => '#216be9',
					],
				]
			),
			'blogsy_header_border'                        => Helper::design_options_defaults(
				[
					'border' => [
						'border-top-width'    => '',
						'border-right-width'  => '',
						'border-bottom-width' => '',
						'border-left-width'   => '',
						'border-style'        => '',
						'border-color'        => '',
					],
				]
			),

			// Ad Widget.
			'blogsy_ad_widgets'                           => [
				[
					'classname' => 'blogsy_customizer_widget_advertisements',
					'type'      => 'advertisements',
				],
			],

			// Ticker Slider.
			'blogsy_ticker_enable'                        => true,
			'blogsy_ticker_title'                         => esc_html__( 'Daily News', 'blogsy' ),
			'blogsy_ticker_visibility'                    => 'all',
			'blogsy_ticker_enable_on'                     => [ 'home' ],
			'blogsy_ticker_post_number'                   => 50,
			'blogsy_ticker_speed'                         => [
				'desktop' => 120,
				'tablet'  => 180,
				'mobile'  => 280,
				'unit'    => 's',
			],
			'blogsy_ticker_category'                      => [],
			'blogsy_ticker_elements'                      => [
				'thumbnail'  => true,
				'meta'       => true,
				'play_pause' => false,
			],

			// Hero.
			'blogsy_hero_enable'                          => true,
			'blogsy_hero_type'                            => 'one',
			'blogsy_hero_visibility'                      => 'all',
			'blogsy_hero_enable_on'                       => [ 'home' ],
			'blogsy_hero_slider_style_heading'            => false,
			'blogsy_hero_slider_orderby'                  => 'date-desc',
			'blogsy_hero_slider_height'                   => [
				'desktop' => 514,
				'tablet'  => 418,
				'mobile'  => 350,
				'unit'    => 'px',
			],
			'blogsy_hero_slider_title_font_size'          => [
				'desktop' => 32,
				'tablet'  => 30,
				'mobile'  => 22,
				'unit'    => 'px',
			],
			'blogsy_hero_slider_elements'                 => [
				'category' => true,
				'excerpt'  => true,
				'meta'     => true,
			],
			'blogsy_hero_slider_excerpt_length'           => '120',
			'blogsy_hero_slider_settings_heading'         => false,
			'blogsy_hero_slider_post_number'              => 6,
			'blogsy_hero_slider_category'                 => [],
			'blogsy_hero_slider_tags'                     => [],
			'blogsy_hero_page_heading'                    => false,
			'blogsy_hero_page'                            => '',

			// Featured Category.
			'blogsy_featured_category_enable'             => false,
			'blogsy_featured_category_title'              => esc_html__( 'Featured Categories', 'blogsy' ),
			'blogsy_featured_category_visibility'         => 'all',
			'blogsy_featured_category_enable_on'          => [ 'home' ],
			'blogsy_featured_category_style'              => 'one',
			'blogsy_featured_category_column'             => '5',
			'blogsy_featured_category'                    => apply_filters(
				'blogsy_featured_category_default',
				[
					[
						'category' => '',
						'image'    => [],
						'color'    => '',
					],
					[
						'category' => '',
						'image'    => [],
						'color'    => '',
					],
					[
						'category' => '',
						'image'    => [],
						'color'    => '',
					],
					[
						'category' => '',
						'image'    => [],
						'color'    => '',
					],
					[
						'category' => '',
						'image'    => [],
						'color'    => '',
					],
				]
			),

			// Featured Links.
			'blogsy_featured_links_enable'                => false,
			'blogsy_featured_links_title'                 => esc_html__( 'Featured Links', 'blogsy' ),
			'blogsy_featured_links_visibility'            => 'all',
			'blogsy_featured_links_enable_on'             => [ 'home' ],
			'blogsy_featured_links_style'                 => 'one',
			'blogsy_featured_links_column'                => '5',
			'blogsy_featured_links'                       => apply_filters(
				'blogsy_featured_links_default',
				[
					[
						'link'  => '',
						'image' => [],
						'color' => '',
					],
					[
						'link'  => '',
						'image' => [],
						'color' => '',
					],
					[
						'link'  => '',
						'image' => [],
						'color' => '',
					],
					[
						'link'  => '',
						'image' => [],
						'color' => '',
					],
					[
						'link'  => '',
						'image' => [],
						'color' => '',
					],
				]
			),

			// Stories.
			'blogsy_stories_enable'                       => false,
			'blogsy_stories_title'                        => esc_html__( 'Top Stories', 'blogsy' ),
			'blogsy_stories_view_all'                     => esc_html__( 'View All Stories', 'blogsy' ),
			'blogsy_stories_style'                        => 'one',
			'blogsy_stories_visibility'                   => 'all',
			'blogsy_stories_enable_on'                    => [ 'home' ],
			'blogsy_stories_settings_heading'             => false,
			'blogsy_stories_orderby'                      => 'date-desc',
			'blogsy_stories_elements'                     => [
				'title'    => true,
				'category' => true,
				'meta'     => true,
			],
			'blogsy_stories_max_category'                 => 5,
			'blogsy_stories_max_inner_items'              => 4,
			'blogsy_stories_category'                     => [],

			// PYML.
			'blogsy_pyml_enable'                          => true,
			'blogsy_pyml_title'                           => esc_html__( 'You May Have Missed', 'blogsy' ),
			'blogsy_pyml_style'                           => 'one',
			'blogsy_pyml_visibility'                      => 'all',
			'blogsy_pyml_enable_on'                       => [ 'home' ],
			'blogsy_pyml_settings_heading'                => false,
			'blogsy_pyml_orderby'                         => 'date-desc',
			'blogsy_pyml_elements'                        => [
				'category' => true,
				'meta'     => true,
			],
			'blogsy_pyml_post_number'                     => 9,
			'blogsy_pyml_category'                        => [],
			'blogsy_pyml_tags'                            => [],

			// Footer.
			'blogsy_site_footer'                          => '0',
			'blogsy_footer_layout'                        => 'layout-1',
			'blogsy_footer_widgets_align_center'          => false,
			'blogsy_footer_copyright_textarea'            => wp_kses_post( 'Copyright {{the_year}} &mdash; <b>{{site_title}}</b>. All rights reserved. <b>{{theme_link}}</b>' ),
			'blogsy_back_to_top'                          => false,

			'blogsy_footer_widget_design_heading'         => false,
			'blogsy_footer_widget_background'             => Helper::design_options_defaults(
				[
					'background' => [
						'color'    => [
							'background-color' => '#ffffff',
						],
						'gradient' => [
							'gradient-color-1' => '#ddd6f3',
							'gradient-color-2' => '#faaca8',
						],
					],
				]
			),

			'blogsy_footer_widget_text_color'             => Helper::design_options_defaults(
				[
					'color' => [
						'text-color'       => '',
						'link-color'       => '',
						'link-hover-color' => '',
					],
				]
			),

			'blogsy_footer_widget_area_border'            => Helper::design_options_defaults(
				[
					'border' => [
						'border-top-width' => '',
						'border-style'     => '',
						'border-color'     => '',
						'separator-color'  => '',
					],
				]
			),

			// Sideabr.
			'blogsy_single_page_sidebar_position'         => 'right',
			'blogsy_single_page_sidebar_template'         => '0',
			'blogsy_single_post_sidebar_position'         => 'right',
			'blogsy_single_post_sidebar_template'         => '0',
			'blogsy_blog_sidebar_position'                => 'right',
			'blogsy_blog_sidebar_template'                => '0',
			'blogsy_woocommerce_shop_sidebar_position'    => 'right',
			'blogsy_woocommerce_archive_sidebar_position' => 'right',
			'blogsy_woocommerce_sidebar_template'         => '0',
			'blogsy_sidebar_heading_design_options'       => false,
			'blogsy_sidebar_widget_box_shadow'            => [
				'x'      => 0,
				'y'      => 2,
				'blur'   => 5,
				'spread' => 0,
				'color'  => 'rgba(14,14,19,0.05)',
				'type'   => 'outset',
			],
			'blogsy_sidebar_widget_border'                => Helper::design_options_defaults(
				[
					'border' => [
						'border-top-width'    => '',
						'border-right-width'  => '',
						'border-bottom-width' => '',
						'border-left-width'   => '',
						'border-style'        => '',
						'border-color'        => '',
					],
				]
			),
			'blogsy_sidebar_widget_bg_color'              => '#ffffff',

			/* Single Post */

			// General.
			'blogsy_single_post_top_content_template'     => '0',
			'blogsy_single_post_bottom_content_template'  => '0',
			'blogsy_compact_comments'                     => true,
			'blogsy_disable_comments'                     => false,
			'blogsy_disable_tags'                         => false,
			'blogsy_single_post_share_box'                => true,
			'blogsy_single_post_share_box_title'          => esc_html__( 'Share Article', 'blogsy' ),
			'blogsy_single_post_share_box_options'        => [ 'facebook', 'twitter', 'pinterest', 'email', 'whatsapp', 'link' ],
			'blogsy_single_post_author_box'               => true,
			'blogsy_single_post_next_prev_posts'          => true,
			'blogsy_single_next_prev_posts_title'         => esc_html__( 'Other Articles', 'blogsy' ),
			'blogsy_reading_time_words_per_minute'        => 255,

			// Hero layout.
			'blogsy_single_post_meta'                     => [
				'author-name',
				'author-avatar',
				'date',
				'category',
				'comments',
				'reading-time',
			],
			'blogsy_single_hero'                          => '1',
			'blogsy_single_hero_1_fit'                    => false,
			'blogsy_single_hero_1_full_img'               => false,
			'blogsy_single_hero_2_disable_img'            => false,
			'blogsy_single_hero_3_disable_img'            => false,
			'blogsy_single_hero_6_full_img'               => false,
			'blogsy_single_hero_7_full_img'               => false,
			'blogsy_single_hero_11_fit'                   => false,
			'blogsy_single_format_gallery_position'       => 'inside',

			// Blog archive.
			'blogsy_archive_template'                     => '0',
			'blogsy_search_post_types'                    => [],
			'blogsy_blog_heading'                         => '',
			'blogsy_blog_layout'                          => 'blog-horizontal',
			'blogsy_blog_masonry'                         => true,
			'blogsy_blog_align_center'                    => true,
			'blogsy_blog_layout_column'                   => '3',
			'blogsy_blog_title_font_size'                 => [
				'desktop' => 24,
				'tablet'  => 22,
				'mobile'  => 20,
				'unit'    => 'px',
			],
			'blogsy_blog_meta'                            => [
				'author-avatar',
				'author-name',
				'category',
				'comments',
				'date',
				'reading-time',
			],
			'blogsy_blog_read_more_enable'                => false,
			'blogsy_blog_read_more'                       => esc_html__( 'Read More', 'blogsy' ),
			'blogsy_post_feed_content_type'               => '1',
			'blogsy_excerpt_length'                       => 255,
			'blogsy_excerpt_more'                         => '&hellip;',

			// Dark Mode.
			'blogsy_dark_mode'                            => true,
			'blogsy_default_theme_scheme'                 => 'light',
			'blogsy_always_dark_mode'                     => false,

			// Breadcrumbs.
			'blogsy_breadcrumb'                           => false,
			'blogsy_breadcrumb_schema'                    => false,

			// Typography
			// Base Typography.
			'blogsy_html_base_font_size'                  => [
				'desktop' => 62.5,
				'tablet'  => 53,
				'mobile'  => 50,
			],
			'blogsy_typo_body_heading'                    => true,
			'blogsy_typo_body'                            => Helper::typography_defaults(
				[
					'font-family'         => 'Inter',
					'font-weight'         => 400,
					'letter-spacing'      => '',
					'font-size-desktop'   => '15',
					'font-size-unit'      => 'px',
					'line-height-desktop' => '1.55',
					'color'               => '',
				],
			),
			'blogsy_typo_h_heading'                       => true,
			'blogsy_typo_h1'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '4.2',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.2',
					'color'               => '',
				],
			),
			'blogsy_typo_h2'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '3.4',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
					'color'               => '',
				],
			),
			'blogsy_typo_h3'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '2.6',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
					'color'               => '',
				],
			),
			'blogsy_typo_h4'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '2.2',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
					'color'               => '',
				],
			),
			'blogsy_typo_h5'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '1.8',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
					'color'               => '',
				],
			),
			'blogsy_typo_h6'                              => Helper::typography_defaults(
				[
					'font-family'         => 'Poppins',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '1.6',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
					'color'               => '',
				],
			),
			'blogsy_typo_terms'                           => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 600,
					'letter-spacing'      => '0.25',
					'letter-spacing-unit' => 'px',
					'font-size-desktop'   => '1.4',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.2',
				]
			),
			'blogsy_typo_section_title'                   => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '2.2',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
				]
			),
			'blogsy_typo_widgets_title'                   => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 600,
					'letter-spacing'      => '',
					'font-size-desktop'   => '2.2',
					'font-size-unit'      => 'rem',
					'line-height-desktop' => '1.45',
				]
			),
			'blogsy_divider_style'                        => '0',
			'blogsy_typo_single_post_title'               => Helper::typography_defaults(
				[
					'font-size-desktop' => '2.8',
					'font-size-unit'    => 'rem',
					'font-weight'       => 'inherit',
				]
			),
			'blogsy_typo_single_post_content'             => Helper::typography_defaults(
				[
					'font-size-desktop' => '1.6',
					'font-size-unit'    => 'rem',
					'font-weight'       => 'inherit',
				]
			),
			'blogsy_typo_menu'                            => Helper::typography_defaults(
				[
					'font-family'         => 'inherit',
					'font-weight'         => 600,
					'letter-spacing'      => '-0.25',
					'letter-spacing-unit' => 'px',
					'font-size-desktop'   => '15',
					'font-size-unit'      => 'px',
					'line-height-desktop' => '1.625',
				],
			),
		];
		return apply_filters( 'blogsy_default_options_values', $defaults );
	}

	/**
	 * Get the options from static array()
	 *
	 * @since  1.0.0
	 * @return array    Return array of theme options.
	 */
	public function get_options(): array {
		return self::$options;
	}

	/**
	 * Get the options from static array().
	 *
	 * @param string $id Options jet to get.
	 * @return mixed Return array of theme options.
	 * @since  1.0.0
	 */
	public function get( string $id ) {
		$value = self::$options[ $id ] ?? self::get_default( $id ); // phpcs:ignore
		return get_theme_mod( $id, $value );
	}

	/**
	 * Set option.
	 *
	 * @param string $id Option key.
	 * @param mixed  $value Option value.
	 * @since  1.0.0
	 */
	public function set( string $id, $value ): void {
		set_theme_mod( $id, $value );
		self::$options[ $id ] = $value;
	}

	/**
	 * Refresh options.
	 *
	 * @since  1.0.0
	 */
	public function refresh(): void {
		self::$options = wp_parse_args(
			get_theme_mods(),
			self::get_defaults()
		);
	}

	/**
	 * Returns the default value for option.
	 *
	 * @param  string $id Option ID.
	 * @return mixed      Default option value.
	 * @since  1.0.0
	 */
	public function get_default( string $id ) {
		$defaults = self::get_defaults();
		return $defaults[ $id ] ?? false;
	}
}
