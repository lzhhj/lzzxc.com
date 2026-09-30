<?php
/**
 * Dynamically generate CSS code.
 * The code depends on options set in the Highend Options and Post/Page metaboxes.
 *
 * If possible, write the dynamically generated code into a .css file, otherwise return the code. The file is refreshed on each modification of metaboxes & theme options.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */

namespace Blogsy;

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Dynamic_Styles' ) ) :
	/**
	 * Dynamically generate CSS code.
	 */
	class Dynamic_Styles {

		/**
		 * Singleton instance of the class.
		 *
		 * @var Dynamic_Styles|null
		 * @since 1.0.0
		 */
		private static ?self $instance = null;

		/**
		 * URI for Dynamic CSS file.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private string $dynamic_css_uri;

		/**
		 * Path for Dynamic CSS file.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private string $dynamic_css_path;

		/**
		 * Main Dynamic_Styles Instance.
		 *
		 * @since 1.0.0
		 * @return Dynamic_Styles
		 */
		public static function instance(): ?self {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			self::load_file();
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$upload_dir = wp_upload_dir();

			$this->dynamic_css_uri  = trailingslashit( set_url_scheme( $upload_dir['baseurl'] ) ) . 'blogsy/';
			$this->dynamic_css_path = trailingslashit( set_url_scheme( $upload_dir['basedir'] ) ) . 'blogsy/';

			if ( ! is_customize_preview() && wp_is_writable( trailingslashit( $upload_dir['basedir'] ) ) ) {
				add_action( 'blogsy_enqueue_scripts', [ $this, 'enqueue_dynamic_style' ], 20 );
			} else {
				add_action( 'blogsy_enqueue_scripts', [ $this, 'print_dynamic_style' ], 99 );
			}

			// Remove Customizer Custom CSS from wp_head, we will include it in our dynamic file.
			if ( ! is_customize_preview() ) {
				remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
			}

			// Generate new styles on Customizer Save action.
			add_action( 'customize_save_after', [ $this, 'update_dynamic_file' ] );

			// Generate new styles on theme activation.
			add_action( 'after_switch_theme', [ $this, 'update_dynamic_file' ] );

			// Delete the css stye on theme deactivation.
			add_action( 'switch_theme', [ $this, 'delete_dynamic_file' ] );

			// Generate initial dynamic css.
			add_action( 'init', [ $this, 'init' ] );
		}

		/**
		 * Load required files.
		 *
		 * @since 1.0.0
		 */
		public static function load_file(): void {
			require_once BLOGSY_THEME_DIR . '/inc/customizer/customizer-callbacks.php';
		}

		/**
		 * Init.
		 *
		 * @since 1.0.0
		 */
		public function init(): void {

			// Ensure we have dynamic stylesheet generated.
			if ( false === get_transient( 'blogsy_has_dynamic_css' ) ) {
				$this->update_dynamic_file();
			}
		}

		/**
		 * Enqueues dynamic styles file.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_dynamic_style(): void {

			$exists = file_exists( $this->dynamic_css_path . 'dynamic-styles.css' );
			// Generate the file if it's missing.
			if ( ! $exists ) {
				$exists = $this->update_dynamic_file();
			}

			// Enqueue the file if available.
			if ( $exists ) {
				wp_enqueue_style(
					'blogsy-dynamic-styles',
					$this->dynamic_css_uri . 'dynamic-styles.css',
					false,
					filemtime( $this->dynamic_css_path . 'dynamic-styles.css' ),
					'all'
				);
			}
		}

		/**
		 * Prints inline dynamic styles if writing to file is not possible.
		 *
		 * @since 1.0.0
		 */
		public function print_dynamic_style(): void {
			$dynamic_css = $this->get_css();
			wp_add_inline_style( 'blogsy-styles', $dynamic_css );
		}

		/**
		 * Generates dynamic CSS code, minifies it and cleans cache.
		 *
		 * @param  boolean $css - should we include the wp_get_custom_css.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		public function get_css( $css = false ): string {

			// Refresh options.
			\Blogsy\Customizer\Options::instance()->refresh();

			// Delete google fonts enqueue transients.
			delete_transient( 'blogsy_google_fonts_enqueue' );

			// Add our theme custom CSS.
			$css = '';

			// Set Container Width.
			$site_width = Helper::get_option( 'site_width' );
			if ( $site_width ) {
				$css .= ':root { --pt-site-width: ' . absint( $site_width ) . 'px; }';
			}

			// Logo max height.
			$css .= $this->get_range_field_css( '#site-header .pt-logo img, #site-sticky-header .pt-logo img', 'max-height', 'logo_max_height' );
			$css .= $this->get_range_field_css( '#site-header .pt-logo img.blogsy-svg-logo, #site-sticky-header .pt-logo img.blogsy-svg-logo', 'height', 'logo_max_height' );

			// Logo margin.
			$css .= $this->get_spacing_field_css( '#site-header .pt-logo .logo-inner', 'margin', 'logo_margin' );

			// Accent Colors.
			if ( Helper::get_option( 'accent_color' ) ) {
				$css .= ':root {
					--pt-accent-color: ' . blogsy_sanitize_color( Helper::get_option( 'accent_color' ) ) . ';
					--pt-accent-40-color: ' . blogsy_sanitize_color( blogsy_luminance( Helper::get_option( 'accent_color' ), .40 ) ) . ';
					--pt-accent-80-color: ' . blogsy_sanitize_color( blogsy_luminance( Helper::get_option( 'accent_color' ), .80 ) ) . ';
				}';
			}

			if ( Helper::get_option( 'second_color' ) ) {
				$css .= ':root { --pt-second-color: ' . blogsy_sanitize_color( Helper::get_option( 'second_color' ) ) . ';}';
			}

			// Styling.
			if ( Helper::get_option( 'body_bg' ) ) {
				$css .= ':root { --pt-body-bg-color: ' . blogsy_sanitize_color( Helper::get_option( 'body_bg' ) ) . ';}';
			}

			if ( Helper::get_option( 'body_color' ) ) {
				$css .= ':root { --pt-body-color: ' . blogsy_sanitize_color( Helper::get_option( 'body_color' ) ) . ';}';
			}

			if ( Helper::get_option( 'heading_color' ) ) {
				$css .= ':root { --pt-headings-color: ' . blogsy_sanitize_color( Helper::get_option( 'heading_color' ) ) . ';}';
			}

			if ( Helper::get_option( 'button_bg_hover' ) ) {
				$css .= ':root { --pt-button-bg-hover: ' . blogsy_sanitize_color( Helper::get_option( 'button_bg_hover' ) ) . ';}';
			}

			/**
			 * Top Bar.
			 */

			// Background.
			$css .= $this->get_design_options_field_css( '.blogsy-topbar', 'top_bar_background', 'background' );

			// Border.
			$css .= $this->get_design_options_field_css( '.blogsy-topbar', 'top_bar_border', 'border' );

			// Top Bar colors.
			$topbar_color = Helper::get_option( 'top_bar_text_color' );

			// Top Bar text color.
			if ( isset( $topbar_color['text-color'] ) && $topbar_color['text-color'] ) {
				$css .= '.blogsy-topbar { color: ' . blogsy_sanitize_color( $topbar_color['text-color'] ) . '; }';
			}

			// Top Bar link color.
			if ( isset( $topbar_color['link-color'] ) && $topbar_color['link-color'] ) {
				$css .= '
					.blogsy-topbar-widget__text a,
					.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a,
					.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a {
						color: ' . blogsy_sanitize_color( $topbar_color['link-color'] ) . '; }
				';
			}

			// Top Bar link hover color.
			if ( isset( $topbar_color['link-hover-color'] ) && $topbar_color['link-hover-color'] ) {
				$css .= '
					.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a:hover,
					.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a:focus,
					.blogsy-topbar-widget .blogsy-header-nav > li.menu-item-has-children:hover > a,
					.blogsy-topbar-widget .blogsy-header-nav > li.current-menu-item > a,
					.blogsy-topbar-widget .blogsy-header-nav > li.current-menu-ancestor > a,
					.blogsy-topbar-widget__text a:focus,
					.blogsy-topbar-widget__text a:hover,
					.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a:focus,
					.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a:hover {
						color: ' . blogsy_sanitize_color( $topbar_color['link-hover-color'] ) . '; }
				';
			}

			/**
			 * Header Design Options
			 */
			// Background.
			$header_classes = 'html:not([scheme="dark"]) .pt-header-layout-1 .pt-header .pt-header-inner .pt-header-container::after,
							 html:not([scheme="dark"]) .pt-header-layout-2 .pt-header .pt-header-inner,
							 html:not([scheme="dark"]) .pt-header-layout-3 .pt-header .pt-header-inner > .pt-header-container';
			$css           .= $this->get_design_options_field_css( $header_classes, 'header_background', 'background' );

			// Border.
			$css .= $this->get_design_options_field_css( $header_classes, 'header_border', 'border' );

			// Header colors.
			$header_color = Helper::get_option( 'header_text_color' );

			// Header text color.
			if ( isset( $header_color['text-color'] ) && $header_color['text-color'] ) {
				$css .= 'html:not([scheme="dark"]) .pt-header { color: ' . blogsy_sanitize_color( $header_color['text-color'] ) . '; }';
			}

			// Header link color.
			if ( isset( $header_color['link-color'] ) && $header_color['link-color'] ) {
				$css .= '
					html:not([scheme="dark"]) .pt-header .blogsy-header-nav > li > a, html:not([scheme="dark"]) .pt-header .blogsy-header-v-nav > li > a,
					html:not([scheme="dark"]) .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a {
						color: ' . blogsy_sanitize_color( $header_color['link-color'] ) . '; }
				';
			}

			// Header link hover color.
			if ( isset( $header_color['link-hover-color'] ) && $header_color['link-hover-color'] ) {
				$css .= '
					html .pt-header .blogsy-header-nav > li > a:hover,
					html .pt-header .blogsy-header-nav > li.hovered > a,
					html .pt-header .blogsy-header-nav > li.current_page_item > a,
					html .pt-header .blogsy-header-nav > li.current-menu-item > a,
					html .pt-header .blogsy-header-nav > li.current-menu-ancestor > a,
					html .pt-header .blogsy-header-v-nav > li a:focus,
					html .pt-header .blogsy-header-v-nav > li a:hover,
					html .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:focus,
					html .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:hover {
						color: ' . blogsy_sanitize_color( $header_color['link-hover-color'] ) . '; }
				';
			}

			// Header link active color.
			if ( isset( $header_color['link-active-color'] ) && $header_color['link-active-color'] ) {
				$css .= '
					html .pt-header .blogsy-header-nav > li.menu-item > a {
						--menu-shape-color: ' . blogsy_sanitize_color( $header_color['link-active-color'] ) . ';
					}
				';
			}

			// Blog post title font size.
			$css .= $this->get_range_field_css( '.default-archive-container .post-wrapper .title', 'font-size', 'blog_title_font_size', true );

			// Hero Section.
			if ( Helper::get_option( 'hero_enable' ) ) {
				// Hero post title font size.
				$css .= $this->get_range_field_css( '#blogsy-hero .pt-hero-slider .post-wrapper .title', 'font-size', 'hero_slider_title_font_size', true );
				// Hero height.
				$css .= $this->get_range_field_css( '#blogsy-hero .pt-hero-slider .post-wrapper, #blogsy-hero .blogsy-post-elastic-widget .swiper-vertical', 'height', 'hero_slider_height' );
				$css .= $this->get_range_field_css( '#blogsy-hero:has(.pt-hero-slider) .blogsy-post-nexo-widget:not(.pt-hero-slider) .post-wrapper', 'height', 'hero_slider_height', true, 'px', true, 9.5 );
			}

			/**
			 * Ticker Speed.
			 */
			if ( Helper::get_option( 'ticker_enable' ) ) {
				$css .= $this->get_range_field_css( '#blogsy-ticker .blogsy-ticker .blogsy-news-ticker-content-wrapper.animation-marquee .blogsy-news-ticker-items', '--marquee-time', 'ticker_speed', true, 's' );
			}

			// Footer Elements.
			$footer_widget_background = Helper::get_option( 'footer_widget_background' );
			if ( 'color' === $footer_widget_background['background-type'] ) {
				$light_or_dark = '#ffffff' === $footer_widget_background['background-color'] ? -0.1 : 0.2;
			}

			$copyright_separator_color = blogsy_luminance( $footer_widget_background['background-color'], $light_or_dark ?? 0.2 );

			if ( '0' === Helper::get_option( 'site_footer' ) ) {
				if ( Helper::get_option( 'footer_widget_background' ) ) {
					$css .= $this->get_design_options_field_css( '.site-default-footer', 'footer_widget_background', 'background' );
				}

				if ( Helper::get_option( 'footer_widget_text_color' ) ) {
					$css .= '.site-default-footer .default-footer-copyright {
						border-top-color: ' . blogsy_sanitize_color( $copyright_separator_color ) . ';
					}';
				}

				if ( Helper::get_option( 'footer_widget_text_color' ) ) {
					$css .= $this->get_design_options_field_css( '.site-default-footer', 'footer_widget_text_color', 'color' );
				}
			}

			/* Category Color */
			$categories = get_categories( [ 'hide_empty' => 1 ] );
			foreach ( $categories as $category ) {
				$category_color = Helper::get_option( 'category_color_' . absint( $category->term_id ) );
				if ( $category_color ) {
					$css .= '.term-id-' . absint( $category->term_id ) . '{--term-color: ' . $category_color . '; --term-80-color: ' . blogsy_luminance( $category_color, .80 ) . ';}';
				}
			}

			/**
			 * Footer widget area.
			 */

			// Background.
			$css .= $this->get_design_options_field_css( '.site-default-footer', 'footer_widget_background', 'background' );

			// Border.
			$css .= $this->get_design_options_field_css( '.site-default-footer', 'footer_widget_area_border', 'border' );

			// Footer colors.
			$footer_color = Helper::get_option( 'footer_widget_text_color' );

			// Footer text color.
			if ( isset( $footer_color['text-color'] ) && $footer_color['text-color'] ) {
				$css .= '.site-default-footer, .site-default-footer .blogsy-divider-heading .title, .site-default-footer .wp-block-heading { color: ' . blogsy_sanitize_color( $footer_color['text-color'] ) . '; }';
			}

			// Footer link color.
			if ( isset( $footer_color['link-color'] ) && $footer_color['link-color'] ) {
				$css .= '.site-default-footer a {
						color: ' . blogsy_sanitize_color( $footer_color['link-color'] ) . '; }
				';
			}

			// Footer link hover color.
			if ( isset( $footer_color['link-hover-color'] ) && $footer_color['link-hover-color'] ) {
				$css .= '.site-default-footer a:hover,
					.site-default-footer a:focus {
						color: ' . blogsy_sanitize_color( $footer_color['link-hover-color'] ) . '; }
				';
			}

			// Base HTML font size.
			$css .= $this->get_range_field_css( 'html', 'font-size', 'html_base_font_size', true, '%' );

			// Card box shadow.
			$css .= $this->get_design_options_field_css( 'html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w', 'card_widget_box_shadow', 'box-shadow' );
			$css .= $this->get_design_options_field_css( 'html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w', 'card_widget_border', 'border' );
			if ( Helper::get_option( 'card_widget_bg_color' ) ) {
				$css .= 'html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w { background: ' . blogsy_sanitize_color( Helper::get_option( 'card_widget_bg_color' ) ) . ';}';
			}

			// Sidebar widget box shadow.
			$css .= $this->get_design_options_field_css( 'html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget', 'sidebar_widget_box_shadow', 'box-shadow' );
			$css .= $this->get_design_options_field_css( 'html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget', 'sidebar_widget_border', 'border' );
			if ( Helper::get_option( 'sidebar_widget_bg_color' ) ) {
				$css .= 'html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget { background: ' . blogsy_sanitize_color( Helper::get_option( 'sidebar_widget_bg_color' ) ) . ';}';
			}

			$css .= $this->typography_css();

			// Allow CSS to be filtered.
			$css = apply_filters( 'blogsy_dynamic_styles', $css );

			// Add user custom CSS.
			if ( $css || ! is_customize_preview() ) {
				$css .= wp_get_custom_css();
			}

			// Minify the CSS code.
			$css = $this->minify( $css );

			return $css;
		}

		/**
		 * Get typography CSS base on settings.
		 */
		protected function typography_css(): string {
			$settings = [
				'typo_body'                => 'body',
				'typo_h1'                  => 'h1, .h1',
				'typo_h2'                  => 'h2, .h2',
				'typo_h3'                  => 'h3, .h3',
				'typo_h4'                  => 'h4, .h4',
				'typo_h5'                  => 'h5, .h5',
				'typo_h6'                  => 'h6, .h6',
				'typo_section_title'       => '.blogsy-section-heading .blogsy-divider-heading, .blogsy-section-heading .blogsy-divider-heading .title',
				'typo_widgets_title'       => '.blogsy-sidebar-widget .blogsy-divider-heading, .blogsy-sidebar-widget .blogsy-divider-heading .title',
				'typo_terms'               => '.term-item, .single-hero-title .category a',
				'typo_menu'                => '.blogsy-header-nav > li a',
				'typo_single_post_title'   => '.single-hero-title .title',
				'typo_single_post_content' => '.single-content-inner',
				'logo_title_typography'    => '.pt-header-inner .pt-logo .site-title',
				'logo_tagline_typography'  => '.pt-header-inner .pt-logo .site-description',
			];

			$settings = apply_filters( 'blogsy_dynamic_typography_settings', $settings );
			return $this->get_typography_css( $settings );
		}

		/**
		 * Prints typography field CSS based on passed params.
		 *
		 * @since  1.0.0
		 * @param  array $settings Array of settings and selectors.
		 * @return string       Generated CSS.
		 */
		protected function get_typography_css( $settings ): string {

			if ( empty( $settings ) ) {
				return '';
			}

			// CSS buffer.
			$css_buffer = '';

			// Properties.
			$properties = [
				'font-weight',
				'font-style',
				'text-transform',
				'text-decoration',
				'color',
			];

			foreach ( $settings as $setting_id => $css_selector ) {
				if ( ! is_string( $setting_id ) ) {
					continue;
				}

				// Get the saved setting.
				$setting = Helper::get_option( $setting_id );

				// Setting has to be array.
				if ( ! is_array( $setting ) || [] === $setting ) {
					continue;
				}

				// Reset CSS buffer for this selector.
				$setting_css_buffer = '';

				foreach ( $properties as $property ) {
					if ( isset( $setting[ $property ] ) && 'inherit' !== $setting[ $property ] && ! empty( $setting[ $property ] ) ) {
						$setting_css_buffer .= $property . ':' . sanitize_text_field( $setting[ $property ] ) . ';';
					}
				}

				// Font family.
				if ( isset( $setting['font-family'] ) && ! empty( $setting['font-family'] ) && 'inherit' !== $setting['font-family'] ) {
					$font_family         = Helper::fonts()->get_font_family( $setting['font-family'] );
					$setting_css_buffer .= 'font-family: ' . sanitize_text_field( $font_family ) . ';';
				}

				// Letter spacing.
				if ( ! empty( $setting['letter-spacing'] ) ) {
					$setting_css_buffer .= 'letter-spacing:' . blogsy_sanitize_number( $setting['letter-spacing'] ) . sanitize_text_field( $setting['letter-spacing-unit'] ) . ';';
				}

				// Font size.
				if ( ! empty( $setting['font-size-desktop'] ) ) {
					$setting_css_buffer .= 'font-size:' . blogsy_sanitize_number( $setting['font-size-desktop'] ) . sanitize_text_field( $setting['font-size-unit'] ) . ';';
				}

				// Line Height.
				if ( ! empty( $setting['line-height-desktop'] ) ) {
					$setting_css_buffer .= 'line-height:' . blogsy_sanitize_number( $setting['line-height-desktop'] ) . ';';
				}

				$setting_css_buffer = '' !== $setting_css_buffer && '0' !== $setting_css_buffer ? $css_selector . '{' . $setting_css_buffer . '}' : '';

				// Responsive options - tablet.
				$tablet = '';

				if ( ! empty( $setting['font-size-tablet'] ) ) {
					$tablet .= 'font-size:' . blogsy_sanitize_number( $setting['font-size-tablet'] ) . sanitize_text_field( $setting['font-size-unit'] ) . ';';
				}

				if ( ! empty( $setting['line-height-tablet'] ) ) {
					$tablet .= 'line-height:' . blogsy_sanitize_number( $setting['line-height-tablet'] ) . ';';
				}

				$tablet = '' === $tablet || '0' === $tablet ? '' : '@media only screen and (max-width: 1024px) {' . $css_selector . '{' . $tablet . '} }';

				$setting_css_buffer .= $tablet;

				// Responsive options - mobile.
				$mobile = '';

				if ( ! empty( $setting['font-size-mobile'] ) ) {
					$mobile .= 'font-size:' . blogsy_sanitize_number( $setting['font-size-mobile'] ) . sanitize_text_field( $setting['font-size-unit'] ) . ';';
				}

				if ( ! empty( $setting['line-height-mobile'] ) ) {
					$mobile .= 'line-height:' . blogsy_sanitize_number( $setting['line-height-mobile'] ) . ';';
				}

				$mobile = '' === $mobile || '0' === $mobile ? '' : '@media only screen and (max-width: 600px) {' . $css_selector . '{' . $mobile . '} }';

				$setting_css_buffer .= $mobile;

				$css_buffer .= $setting_css_buffer;

				// Enqueue google fonts.
				if ( isset( $setting['font-family'] ) && ! empty( $setting['font-family'] ) && Helper::fonts()->is_google_font( $setting['font-family'] ) ) {

					$params = [];

					if ( isset( $setting['font-weight'] ) && ! empty( $setting['font-weight'] ) && 'inherit' !== $setting['font-weight'] ) {
						$params['weight'] = $setting['font-weight'];
					}

					if ( isset( $setting['font-style'] ) && ! empty( $setting['font-style'] ) && 'inherit' !== $setting['font-style'] ) {
						$params['style'] = $setting['font-style'];
					}

					if ( ! empty( $setting['font-subsets'] ) ) {
						$params['subsets'] = $setting['font-subsets'];
					}

					Helper::fonts()->enqueue_google_font(
						$setting['font-family'],
						$params
					);
				}
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Update dynamic css file with new CSS. Cleans caches after that.
		 *
		 * @return [Boolean] returns true if successfully updated the dynamic file.
		 */
		public function update_dynamic_file(): ?bool {

			$css = $this->get_css( true );

			if ( empty( $css ) || '' === trim( $css ) ) {
				return null;
			}

			// Load file.php file.
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php'; // phpcs:ignore

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$wp_filesystem->mkdir( $this->dynamic_css_path );

			if ( $wp_filesystem->put_contents( $this->dynamic_css_path . 'dynamic-styles.css', $css ) ) {
				$this->clean_cache();
				set_transient( 'blogsy_has_dynamic_css', true, 0 );
				return true;
			}

			return false;
		}

		/**
		 * Delete dynamic css file.
		 */
		public function delete_dynamic_file(): void {

			// Load file.php file.
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php'; // phpcs:ignore

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$wp_filesystem->delete( $this->dynamic_css_path . 'dynamic-styles.css' );

			delete_transient( 'blogsy_has_dynamic_css' );
		}

		/**
		 * Simple CSS code minification.
		 *
		 * @param  string $css code to be minified.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		private function minify( $css ): string {
			$css = preg_replace( '/\s+/', ' ', $css );
			$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
			$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			return trim( $css );
		}

		/**
		 * Cleans various caches. Compatible with cache plugins.
		 *
		 * @since 1.0.0
		 */
		private function clean_cache(): void {

			// If W3 Total Cache is being used, clear the cache.
			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
				w3tc_pgcache_flush();
			}

			// if WP Super Cache is being used, clear the cache.
			if ( function_exists( 'wp_cache_clean_cache' ) ) {
				global $file_prefix;
				wp_cache_clean_cache( $file_prefix );
			}

			// If SG CachePress is installed, reset its caches.
			if ( class_exists( 'SG_CachePress_Supercacher' ) && method_exists( 'SG_CachePress_Supercacher', 'purge_cache' ) ) {
				\SG_CachePress_Supercacher::purge_cache();
			}

			// Clear caches on WPEngine-hosted sites.
			if ( class_exists( 'WpeCommon' ) ) {

				if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
					\WpeCommon::purge_memcached();
				}

				if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
					\WpeCommon::clear_maxcdn_cache();
				}

				if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
					\WpeCommon::purge_varnish_cache();
				}
			}

			// Clean OpCache.
			if ( function_exists( 'opcache_reset' ) ) {
				opcache_reset(); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.opcache_resetFound
			}

			// Clean WordPress cache.
			if ( function_exists( 'wp_cache_flush' ) ) {
				wp_cache_flush();
			}
		}

		/**
		 * Prints spacing field CSS based on passed params.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $css_selector CSS selector.
		 * @param  string $css_property CSS property, such as 'margin', 'padding' or 'border'.
		 * @param  string $setting_id The ID of the customizer setting containing all information about the setting.
		 * @param  bool   $responsive Has responsive values.
		 * @return string  Generated CSS.
		 */
		public function get_spacing_field_css( string $css_selector, string $css_property, string $setting_id, $responsive = true ): ?string {

			// Get the saved setting.
			$setting = Helper::get_option( $setting_id );

			// If setting doesn't exist, return.
			if ( ! is_array( $setting ) ) {
				return null;
			}

			// Get the unit. Defaults to px.
			$unit = 'px';

			if ( isset( $setting['unit'] ) ) {
				if ( $setting['unit'] ) {
					$unit = $setting['unit'];
				}

				unset( $setting['unit'] );
			}

			// CSS buffer.
			$css_buffer = '';

			// Loop through options.
			foreach ( $setting as $key => $value ) {

				// Check if responsive options are available.
				if ( is_array( $value ) ) {

					if ( 'desktop' === $key ) {
						$mq_open  = '';
						$mq_close = '';
					} elseif ( 'tablet' === $key ) {
						$mq_open  = '@media only screen and (max-width: 1024px) {';
						$mq_close = '}';
					} elseif ( 'mobile' === $key ) {
						$mq_open  = '@media only screen and (max-width: 600px) {';
						$mq_close = '}';
					} else {
						$mq_open  = '';
						$mq_close = '';
					}

					// Add media query prefix.
					$css_buffer .= $mq_open . $css_selector . '{';

					// Loop through all choices.
					foreach ( $value as $pos => $val ) {

						if ( empty( $val ) ) {
							continue;
						}

						if ( 'border' === $css_property ) {
							$pos .= '-width';
						}

						$css_buffer .= $css_property . '-' . $pos . ': ' . intval( $val ) . $unit . ';';
					}

					$css_buffer .= '}' . $mq_close;
				} else {

					if ( 'border' === $css_property ) {
						$key .= '-width';
					}

					$css_buffer .= $css_property . '-' . $key . ': ' . intval( $value ) . $unit . ';';
				}
			}

			// Check if field is has responsive values.
			if ( ! $responsive ) {
				$css_buffer = $css_selector . '{' . $css_buffer . '}';
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Prints range field CSS based on passed params.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $css_selector CSS selector.
		 * @param  string $css_property CSS property, such as 'margin', 'padding' or 'border'.
		 * @param  string $setting_id The ID of the customizer setting containing all information about the setting.
		 * @param  bool   $responsive Has responsive values.
		 * @param  string $unit Unit.
		 * @param  bool   $half_divide Half divide.
		 * @param  float  $half_custom Custom half value.
		 * @return string  Generated CSS.
		 */
		public function get_range_field_css( string $css_selector, string $css_property, string $setting_id, $responsive = true, $unit = 'px', $half_divide = false, float $half_custom = 0.0 ): string {

			// Get the saved setting.
			$setting = Helper::get_option( $setting_id );

			// If just a single value option.
			if ( ! is_array( $setting ) ) {
				$value = floatval( $setting );

				if ( $half_divide ) {
					$value = $value / 2;
				}
				if ( $half_custom ) {
					$value -= $half_custom;
				}

				return $css_selector . ' { ' . $css_property . ': ' . $value . $unit . '; }';
			}

			// Resolve units.
			if ( isset( $setting['unit'] ) ) {
				if ( $setting['unit'] ) {
					$unit = $setting['unit'];
				}

				unset( $setting['unit'] );
			}

			// CSS buffer.
			$css_buffer = '';

			if ( [] !== $setting ) {

				// Media query syntax wrap.
				$mq_open  = '';
				$mq_close = '';

				// Loop through options.
				foreach ( $setting as $key => $value ) {

					if ( empty( $value ) ) {
						continue;
					}

					// Apply half divide if enabled.
					$value = floatval( $value );

					if ( $half_divide ) {
						$value = $value / 2;
					}
					if ( $half_custom ) {
						$value -= $half_custom;
					}

					// Media queries for responsive values.
					if ( 'desktop' === $key ) {
						$mq_open  = '';
						$mq_close = '';
					} elseif ( 'tablet' === $key ) {
						$mq_open  = '@media only screen and (max-width: 1024px) {';
						$mq_close = '}';
					} elseif ( 'mobile' === $key ) {
						$mq_open  = '@media only screen and (max-width: 600px) {';
						$mq_close = '}';
					} else {
						$mq_open  = '';
						$mq_close = '';
					}

					// Add media query prefix.
					$css_buffer .= $mq_open . $css_selector . '{';
					$css_buffer .= $css_property . ': ' . $value . $unit . ';';
					$css_buffer .= '}' . $mq_close;
				}
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}

		/**
		 * Prints design options field CSS based on passed params.
		 *
		 * @since 1.0.0
		 * @param string       $css_selector CSS selector.
		 * @param string|mixed $setting The ID of the customizer setting containing all information about the setting.
		 * @param string       $type Design options field type.
		 * @return string      Generated CSS.
		 */
		/**
		 * Prints design options field CSS based on passed params.
		 *
		 * @since 1.0.0
		 * @param string       $css_selector CSS selector.
		 * @param string|mixed $setting The ID of the customizer setting containing all information about the setting.
		 * @param string       $type Design options field type.
		 * @return string      Generated CSS.
		 */
		public function get_design_options_field_css( string $css_selector, $setting, $type ): ?string {

			if ( is_string( $setting ) ) {
				// Get the saved setting.
				$setting = Helper::get_option( $setting );
			}

			// Setting has to be an array.
			if ( ! is_array( $setting ) || [] === $setting ) {
				return null;
			}

			// CSS buffer.
			$css_buffer = '';

			// Background.
			if ( 'background' === $type ) {

				// Background type.
				$background_type = $setting['background-type'];

				if ( 'color' === $background_type ) {
					if ( isset( $setting['background-color'] ) && ! empty( $setting['background-color'] ) ) {
						$css_buffer .= 'background: ' . blogsy_sanitize_color( $setting['background-color'] ) . ';';
					}
				} elseif ( 'gradient' === $background_type ) {

					$css_buffer .= 'background: ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ';';

					if ( 'linear' === $setting['gradient-type'] ) {
						$css_buffer .= '
							background: -webkit-linear-gradient(' . intval( $setting['gradient-linear-angle'] ) . 'deg, ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);
							background: -o-linear-gradient(' . intval( $setting['gradient-linear-angle'] ) . 'deg, ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);
							background: linear-gradient(' . intval( $setting['gradient-linear-angle'] ) . 'deg, ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);

						';
					} elseif ( 'radial' === $setting['gradient-type'] ) {
						$css_buffer .= '
							background: -webkit-radial-gradient(' . sanitize_text_field( $setting['gradient-position'] ) . ', circle, ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);
							background: -o-radial-gradient(' . sanitize_text_field( $setting['gradient-position'] ) . ', circle, ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);
							background: radial-gradient(circle at ' . sanitize_text_field( $setting['gradient-position'] ) . ', ' . blogsy_sanitize_color( $setting['gradient-color-1'] ) . ' ' . intval( $setting['gradient-color-1-location'] ) . '%, ' . blogsy_sanitize_color( $setting['gradient-color-2'] ) . ' ' . intval( $setting['gradient-color-2-location'] ) . '%);
						';
					}
				} elseif ( 'image' === $background_type ) {
					if ( ! empty( $setting['background-image'] ) ) {
						$css_buffer .= 'background-image: url(' . esc_url( $setting['background-image'] ) . ');';
					}
					if ( ! empty( $setting['background-size'] ) ) {
						$css_buffer .= 'background-size: ' . sanitize_text_field( $setting['background-size'] ) . ';';
					}
					if ( ! empty( $setting['background-attachment'] ) ) {
						$css_buffer .= 'background-attachment: ' . sanitize_text_field( $setting['background-attachment'] ) . ';';
					}
					if ( isset( $setting['background-position-x'], $setting['background-position-y'] ) ) {
						$css_buffer .= 'background-position: ' . intval( $setting['background-position-x'] ) . '% ' . intval( $setting['background-position-y'] ) . '%;';
					}
					if ( ! empty( $setting['background-repeat'] ) ) {
						$css_buffer .= 'background-repeat: ' . sanitize_text_field( $setting['background-repeat'] ) . ';';
					}
				}

				$css_buffer = '' === $css_buffer || '0' === $css_buffer ? '' : $css_selector . '{' . $css_buffer . '}';

				if ( 'image' === $background_type && isset( $setting['background-color-overlay'] ) && $setting['background-color-overlay'] && isset( $setting['background-image'] ) && $setting['background-image'] ) {
					$css_buffer .= $css_selector . ' {
						position: relative;
						z-index: 0;
					}';
					$css_buffer .= $css_selector . '::before {
						content: "";
						position: absolute;
						inset: 0;
						z-index: -1;
						background-color: ' . blogsy_sanitize_color( $setting['background-color-overlay'] ) . ';
					}';
				}
			} elseif ( 'color' === $type ) {

				// Text color.
				if ( isset( $setting['text-color'] ) && ! empty( $setting['text-color'] ) ) {
					$css_buffer .= $css_selector . ' { color: ' . blogsy_sanitize_color( $setting['text-color'] ) . '; }';
				}

				// Link Color.
				if ( isset( $setting['link-color'] ) && ! empty( $setting['link-color'] ) ) {
					$css_buffer .= $css_selector . ' a { color: ' . blogsy_sanitize_color( $setting['link-color'] ) . '; }';
				}

				// Link Hover Color.
				if ( isset( $setting['link-hover-color'] ) && ! empty( $setting['link-hover-color'] ) ) {
					$css_buffer .= $css_selector . ' a:hover { color: ' . blogsy_sanitize_color( $setting['link-hover-color'] ) . ' !important; }';
				}
				// Link Active Color (for > --menu-shape-color).
				if ( isset( $setting['link-active-color'] ) && ! empty( $setting['link-active-color'] ) ) {
					$css_buffer .= $css_selector . ' { --link-active-color: ' . blogsy_sanitize_color( $setting['link-active-color'] ) . '; }';
				}
			} elseif ( 'border' === $type ) {

				// Color.
				if ( isset( $setting['border-color'] ) && ! empty( $setting['border-color'] ) ) {
					$css_buffer .= 'border-color:' . blogsy_sanitize_color( $setting['border-color'] ) . ';';
				}

				// Style.
				if ( isset( $setting['border-style'] ) && ! empty( $setting['border-style'] ) ) {
					$css_buffer .= 'border-style: ' . sanitize_text_field( $setting['border-style'] ) . ';';
				}

				// Width.
				$positions = [ 'top', 'right', 'bottom', 'left' ];

				foreach ( $positions as $position ) {
					if ( isset( $setting[ 'border-' . $position . '-width' ] ) && ! empty( $setting[ 'border-' . $position . '-width' ] ) ) {
						$css_buffer .= 'border-' . sanitize_text_field( $position ) . '-width: ' . $setting[ 'border-' . sanitize_text_field( $position ) . '-width' ] . 'px;';
					}
				}

				$css_buffer = '' === $css_buffer || '0' === $css_buffer ? '' : $css_selector . '{' . $css_buffer . '}';
			} elseif ( 'box-shadow' === $type ) {

				// Expected keys: x, y, blur, spread, color, type.
				$shadow = ( ( isset( $setting['type'] ) && 'inset' === $setting['type'] ) ? 'inset ' : '' ) .
					( isset( $setting['x'] ) ? intval( $setting['x'] ) : 0 ) . 'px ' .
					( isset( $setting['y'] ) ? intval( $setting['y'] ) : 0 ) . 'px ' .
					( isset( $setting['blur'] ) ? intval( $setting['blur'] ) : 0 ) . 'px ' .
					( isset( $setting['spread'] ) ? intval( $setting['spread'] ) : 0 ) . 'px ' .
					( isset( $setting['color'] ) ? blogsy_sanitize_color( $setting['color'] ) : 'rgba(0,0,0,0.05)' ) . ';';

				$css_buffer = $css_selector . '{ box-shadow: ' . $shadow . ' }';

			} elseif ( 'separator_color' === $type && isset( $setting['separator-color'] ) && ! empty( $setting['separator-color'] ) ) {

				// Separator Color.
				$css_buffer .= $css_selector . '::after { background-color:' . blogsy_sanitize_color( $setting['separator-color'] ) . '; }';
			}

			// Finally, return the generated CSS code.
			return $css_buffer;
		}


		/**
		 * Generate dynamic Block Editor styles.
		 *
		 * @since  1.0.0
		 */
		public function get_block_editor_css(): string {

			// CSS buffer.
			$css = '';

			// Accent Colors.
			if ( Helper::get_option( 'accent_color' ) ) {
				$css .= 'html .editor-styles-wrapper { --pt-accent-color: ' . Helper::get_option( 'accent_color' ) . ';}';
			}

			if ( Helper::get_option( 'second_color' ) ) {
				$css .= 'html .editor-styles-wrapper { --pt-second-color: ' . Helper::get_option( 'second_color' ) . ';}';
			}

			if ( Helper::get_option( 'body_bg' ) ) {
				$css .= 'html .editor-styles-wrapper { --pt-body-bg-color: ' . Helper::get_option( 'body_bg' ) . ';}';
			}

			if ( Helper::get_option( 'body_color' ) ) {
				$css .= 'html .editor-styles-wrapper { --pt-body-color: ' . Helper::get_option( 'body_color' ) . ';}';
			}

			if ( Helper::get_option( 'heading_color' ) ) {
				$css .= 'html .editor-styles-wrapper { --pt-headings-color: ' . Helper::get_option( 'heading_color' ) . ';}';
			}

			// Base HTML font size.
			$css .= $this->get_range_field_css( 'html', 'font-size', 'html_base_font_size', true, '%' );

			$settings = [
				'typo_body'                => 'html .editor-styles-wrapper',
				'typo_single_post_content' => 'html .editor-styles-wrapper',
				'typo_single_post_title'   => 'html .editor-styles-wrapper .editor-post-title__input',
				'typo_h1'                  => 'html .editor-styles-wrapper h1, html .editor-styles-wrapper .h1',
				'typo_h2'                  => 'html .editor-styles-wrapper h2, html .editor-styles-wrapper .h2',
				'typo_h3'                  => 'html .editor-styles-wrapper h3, html .editor-styles-wrapper .h3',
				'typo_h4'                  => 'html .editor-styles-wrapper h4, html .editor-styles-wrapper .h4',
				'typo_h5'                  => 'html .editor-styles-wrapper h5, html .editor-styles-wrapper .h5',
				'typo_h6'                  => 'html .editor-styles-wrapper h6, html .editor-styles-wrapper .h6',
			];

			$settings = apply_filters( 'blogsy_dynamic_editor_typography_settings', $settings );

			return $css .= $this->get_typography_css( $settings );
		}
	}
endif;


blogsy_dynamic_styles();
