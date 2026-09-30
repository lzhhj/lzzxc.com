<?php
/**
 * Blogsy init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blogsy
 */

namespace Blogsy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } // Exit if accessed directly

/**
 * Sron theme init
 */
final class Theme {

	/**
	 * Instance
	 *
	 * @var null|self $instance
	 */
	private static ?self $instance = null;

	/**
	 *  Debug mode
	 *
	 * @var boolean
	 */
	private $is_debug;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return Theme
	 */
	public static function instance(): self {
		if ( ! isset( self::$instance ) || ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$this->constants();
		$this->include_files();
	}

	/**
	 * Initialize the theme.
	 *
	 * @since 1.0.0
	 */
	public function init(): void {
		// Before init action.
		do_action( 'before_blogsy_init' );

		add_action( 'init', [ $this, 'load' ] );
		add_action( 'init', [ $this, 'register_block_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
		add_action( 'after_setup_theme', [ $this, 'load_translation' ], 1 );
		add_action( 'after_switch_theme', [ $this, 'blogsy_theme_activated' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'elementor_styles' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_styles' ] );
		add_action( 'enqueue_block_assets', [ $this, 'block_styles' ] );

		add_action( 'widgets_init', [ $this, 'theme_sidebars' ], 11 );
		add_action( 'wp_print_footer_scripts', [ $this, 'blogsy_skip_link_focus_fix' ] );

		// Init action.
		do_action( 'after_blogsy_init' );
	}

	/**
	 * Define theme constants.
	 *
	 * @since 1.0.0
	 */
	public function constants(): void {
		if ( ! defined( 'BLOGSY_THEME_VERSION' ) ) {
			define( 'BLOGSY_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
		}
	}

	/**
	 * Include theme files.
	 *
	 * @since 1.0.0
	 */
	public function include_files(): void {
		require_once get_template_directory() . '/inc/autoload.php';

		/* Template parts */
		require_once BLOGSY_THEME_DIR . '/inc/template-parts.php';

		/* Template tags */
		require_once BLOGSY_THEME_DIR . '/inc/template-tags.php';

		/* Common */
		require_once BLOGSY_THEME_DIR . '/inc/common.php';

		/* Theme Functions */
		require_once BLOGSY_THEME_DIR . '/inc/functions.php';

		/* Theme Hooks */
		require_once BLOGSY_THEME_DIR . '/inc/hooks.php';

		/* Back Compatibility */
		require_once BLOGSY_THEME_DIR . '/inc/back-compat.php';

		/* Woocommerce */
		if ( class_exists( 'WooCommerce' ) ) {
			require_once BLOGSY_THEME_DIR . '/inc/woocommerce.php';
		}

		/* Breadcrumbs */
		require_once BLOGSY_THEME_DIR . '/inc/breadcrumbs.php';
	}


	/**
	 * Theme activated hook.
	 *
	 * @return void
	 */
	public function blogsy_theme_activated(): void {
		// Delete theme demos transient.
		delete_transient( 'hester_core_demo_templates' );
	}


	/**
	 * Theme setup.
	 *
	 * @since 1.0.0
	 */
	public function theme_setup(): void {

		// Add theme support for Custom Logo.
		add_theme_support(
			'custom-logo',
			apply_filters(
				'blogsy_custom_logo_args',
				[
					'width'       => 200,
					'height'      => 40,
					'flex-height' => true,
					'flex-width'  => true,
				]
			)
		);

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable woocommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Enable support for Post Thumbnail.
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'blogsy-small', 440, 310, true );
		add_image_size( 'blogsy-small-tall', 440, 520, true );
		add_image_size( 'blogsy-small-square', 440, 440, true );
		add_image_size( 'blogsy-small-masonry', 440 );

		add_image_size( 'blogsy-medium', 680, 450, true );
		add_image_size( 'blogsy-medium-masonry', 680 );

		add_image_size( 'blogsy-large', 960, 560, true );
		add_image_size( 'blogsy-wide', 1380, 640, true );

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);

		// Enable support for Post Formats.
		add_theme_support(
			'post-formats',
			apply_filters(
				'blogsy_post_formats',
				[
					'gallery',
					'video',
					'audio',
					'quote',
					'link',
				]
			)
		);

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		add_theme_support( 'align-full' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'starter-content', \Blogsy\Starter_Content::get() );

		register_nav_menus(
			apply_filters(
				'blogsy_register_menus',
				[
					'primary_menu' => esc_html__( 'Primary menu', 'blogsy' ),
				]
			)
		);

		if ( is_admin() ) {

			// Disable Default Colors.
			if ( 'yes' !== get_option( 'elementor_disable_color_schemes' ) ) {
				update_option( 'elementor_disable_color_schemes', 'yes' );
			}

			// Disable Default Fonts.
			if ( 'yes' !== get_option( 'elementor_disable_typography_schemes' ) ) {
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}
		}

		// Site width.
		$GLOBALS['content_width'] = 860;
	}


	/**
	 * Load theme translation.
	 *
	 * @since 1.0.0
	 */
	public function load_translation(): void {

		load_theme_textdomain( 'blogsy', BLOGSY_THEME_DIR . '/languages' );
	}

	/**
	 * Load theme files.
	 *
	 * @since 1.0.0
	 * */
	public function load(): void {
		\Blogsy\Customizer\Settings::instance();
		\Blogsy\Dynamic_Styles::instance();
		\Blogsy\Select2_Ajax::instance();
		/* Admin Setup */
		if ( is_admin() ) {
			// Initialize Admin.
			\Blogsy\Admin::instance();
		}
	}

	/**
	 * Register block styles.
	 *
	 * @since 1.0.0
	 */
	public function register_block_styles(): void {
		// Register block pattern categories.
		// Register Blogsy block pattern category.
		register_block_pattern_category(
			'blogsy',
			[ 'label' => __( 'Blogsy', 'blogsy' ) ]
		);

		// Register block styles.
		$block_styles = [
			// Image block: Morphing shape.
			[
				'block'  => 'core/image',
				'styles' => [
					[
						'name'  => 'morphing',
						'label' => __( 'Morphing Shape', 'blogsy' ),
					],
				],
			],
			// Heading block: Default and custom styles.
			[
				'block'  => 'core/heading',
				'styles' => [
					[
						'name'       => 'default',
						'label'      => __( 'Default', 'blogsy' ),
						'is_default' => true,
					],
					[
						'name'  => 'core-style-1',
						'label' => __( 'Style 1', 'blogsy' ),
					],
					[
						'name'  => 'core-style-2',
						'label' => __( 'Style 2', 'blogsy' ),
					],
					[
						'name'  => 'core-style-3',
						'label' => __( 'Style 3', 'blogsy' ),
					],
					[
						'name'  => 'core-style-4',
						'label' => __( 'Style 4', 'blogsy' ),
					],
				],
			],
			// List block: Checkmark and Arrow styles.
			[
				'block'  => 'core/list',
				'styles' => [
					[
						'name'  => 'checkmark-list',
						'label' => __( 'Checkmark', 'blogsy' ),
					],
					[
						'name'  => 'arrow-list',
						'label' => __( 'Arrow List', 'blogsy' ),
					],
				],
			],
			// Group block: Drop shadow hover.
			[
				'block'  => 'core/group',
				'styles' => [
					[
						'name'  => 'drop-shadow-hover',
						'label' => __( 'Drop Shadow Hover', 'blogsy' ),
					],
				],
			],
		];

		foreach ( $block_styles as $block_style ) {
			foreach ( $block_style['styles'] as $style ) {
				register_block_style(
					$block_style['block'],
					$style
				);
			}
		}
	}


	/**
	 * Enqueue theme styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function theme_scripts(): void {
		// Script debug.
		$blogsy_dir    = $this->is_debug ? 'dev/' : '';
		$blogsy_suffix = $this->is_debug ? '' : '.min';

		if ( blogsy_dark_mode_enabled() ) {
			wp_enqueue_style( 'dark-theme', BLOGSY_THEME_URI . '/assets/css/dark-theme' . $blogsy_suffix . '.css', [], BLOGSY_THEME_VERSION, 'all' );
		}

		wp_enqueue_style( 'blogsy-styles', BLOGSY_THEME_URI . '/assets/css/style' . $blogsy_suffix . '.css', [], BLOGSY_THEME_VERSION, 'all' );

		if ( is_single() ) {
			wp_enqueue_style( 'blogsy-single', BLOGSY_THEME_URI . '/assets/css/single-post' . $blogsy_suffix . '.css', [], BLOGSY_THEME_VERSION, 'all' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'blogsy-woocommerce', BLOGSY_THEME_URI . '/assets/css/woocommerce' . $blogsy_suffix . '.css', [ 'woocommerce-general' ], BLOGSY_THEME_VERSION, 'all' );
		}

		if ( ! wp_style_is( 'swiper' ) ) {
			wp_enqueue_style( 'swiper', BLOGSY_THEME_URI . '/assets/css/swiper' . $blogsy_suffix . '.css', [], '8.4.5', 'all' );
		}

		// aos scripts.
		wp_enqueue_style( 'aos', BLOGSY_THEME_URI . '/assets/css/aos.min.css', [], '2.1.1', 'all' );
		wp_enqueue_script( 'aos', BLOGSY_THEME_URI . '/assets/js/vendors/aos.min.js', [], '2.1.1', true );

		// Load Elementor Swiper CSS.
		if ( is_single() && 'gallery' === get_post_format() ) {
			wp_enqueue_style( 'swiper' );
			wp_enqueue_style( 'e-swiper' );
		}

		if ( \Blogsy\Helper::get_option( 'smooth_scroll' ) ) {
			wp_enqueue_script( 'smooth-scroll', BLOGSY_THEME_URI . '/assets/js/vendors/smooth-scroll.min.js', [], '1.5.1', true );
		}

		if ( ! wp_script_is( 'swiper' ) ) {
			wp_enqueue_script( 'swiper', BLOGSY_THEME_URI . '/assets/js/vendors/swiper.min.js', [], '8.4.5', true );
		}

		// Guten.
		wp_enqueue_script(
			'blogsy-guten',
			get_template_directory_uri() . '/assets/js/' . $blogsy_dir . 'guten' . $blogsy_suffix . '.js',
			[ 'jquery', 'swiper' ],
			BLOGSY_THEME_VERSION,
			true
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( blogsy_dark_mode_enabled() ) {
			wp_enqueue_script( 'blogsy-head', BLOGSY_THEME_URI . '/assets/js/' . $blogsy_dir . 'head' . $blogsy_suffix . '.js', [], BLOGSY_THEME_VERSION, true );
		}

		wp_enqueue_script( 'blogsy-main-script', BLOGSY_THEME_URI . '/assets/js/' . $blogsy_dir . 'main' . $blogsy_suffix . '.js', [ 'jquery' ], BLOGSY_THEME_VERSION, true );

		wp_localize_script(
			'blogsy-main-script',
			'blogsy_ajax_object',
			[
				'AjaxUrl'                => admin_url( 'admin-ajax.php' ),
				'navigation_cutoff'      => \Blogsy\Helper::get_option( 'header_navigation_cutoff' ),
				'navigation_cutoff_upto' => \Blogsy\Helper::get_option( 'header_navigation_cutoff_upto' ),
				'navigation_cutoff_text' => \Blogsy\Helper::get_option( 'header_navigation_cutoff_text' ),
				'header_breakpoint'      => \Blogsy\Helper::get_option( 'header_breakpoint' ),
			]
		);

		// Enqueue google fonts.
		\Blogsy\Helper::fonts()->enqueue_google_fonts();

		// Add additional theme styles.
		do_action( 'blogsy_enqueue_scripts' );
	}

	/**
	 * Enqueue block styles for frontend and editor.
	 *
	 * @since 1.0.0
	 */
	public function block_styles(): void {
		// Script debug.
		$blogsy_dir    = $this->is_debug ? 'dev/' : '';
		$blogsy_suffix = $this->is_debug ? '' : '.min';

		$elementor_page = (bool) get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

		if ( class_exists( 'Elementor\Plugin' ) && $elementor_page ) {
			return;
		}

		// Swiper dependency.
		wp_enqueue_style( 'swiper', BLOGSY_THEME_URI . '/assets/css/swiper.min.css', [], null, 'all' );
		wp_enqueue_script( 'swiper', BLOGSY_THEME_URI . '/assets/js/vendors/swiper.min.js', [], null, true );

		// Guten.
		wp_enqueue_script(
			'blogsy-guten',
			get_template_directory_uri() . '/assets/js/' . $blogsy_dir . 'guten' . $blogsy_suffix . '.js',
			[ 'jquery', 'swiper' ],
			BLOGSY_THEME_VERSION,
			true
		);
		wp_enqueue_style( 'block-styles', BLOGSY_THEME_URI . '/assets/css/patterns' . $blogsy_suffix . '.css', [], BLOGSY_THEME_VERSION, 'all' );
	}

	/**
	 * Skip link focus fix for IE11.
	 *
	 * @since 1.0.0
	 */
	public function blogsy_skip_link_focus_fix(): void {
		?>
		<script>
			! function() {
				var e = -1 < navigator.userAgent.toLowerCase().indexOf("webkit"),
					t = -1 < navigator.userAgent.toLowerCase().indexOf("opera"),
					n = -1 < navigator.userAgent.toLowerCase().indexOf("msie");
				(e || t || n) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
					var e, t = location.hash.substring(1);
					/^[A-z0-9_-]+$/.test(t) && (e = document.getElementById(t)) && (/^(?:a|select|input|button|textarea)$/i.test(e.tagName) || (e.tabIndex = -1), e.focus())
				}, !1)
			}();
		</script>
		<?php
	}


	/**
	 * Enqueues Elementor Styles. Pre Render
	 *
	 * @since 1.0.0
	 */
	public function elementor_styles(): void {

		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		$elementor = \Elementor\Plugin::instance();
		$elementor->frontend->enqueue_styles();

		$template_ids = [
			blogsy_get_layout_template_id( 'sidebar' ),
			blogsy_get_layout_template_id( 'footer' ),
			blogsy_get_layout_template_id( 'header' ),
			blogsy_get_layout_template_id( 'sticky_header' ),
			blogsy_get_layout_template_id( 'single_top_content' ),
			blogsy_get_layout_template_id( 'single_bottom_content' ),
			blogsy_get_layout_template_id( '404' ),
			blogsy_get_layout_template_id( 'archive' ),
		];

		// Add mega menu template ids to enable assets for them.
		global $wpdb;
		$mega_menu_templates = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s", 'blogsy_mega_menu_template' ) );

		foreach ( $mega_menu_templates as $nav_item ) {
			$nav_template_id = intval( $nav_item->meta_value );
			if ( $nav_template_id && 'publish' === get_post_status( $nav_template_id ) ) {
				$template_ids[] = $nav_template_id;
			}
		}

		$template_ids = array_filter( $template_ids );

		foreach ( $template_ids as $template_id ) {

			// Load Elementor Widget Assets.
			if ( ! $elementor->preview->is_preview_mode() ) {
				$page_assets = get_post_meta( $template_id, '_elementor_page_assets', true );
				if ( ! empty( $page_assets ) ) {
					$elementor->assets_loader->enable_assets( $page_assets );
				}
			}

			// Load Post CSS.
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$css_file = new \Elementor\Post_CSS_File( $template_id );
			}

			$css_file->enqueue();
		}
	}


	/**
	 * Theme sidebars.
	 *
	 * @since 1.0.0
	 */
	public function theme_sidebars(): void {
		$divider_style = Helper::get_option( 'divider_style' );
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'blogsy' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Default Sidebar Widgets', 'blogsy' ),
				'before_widget' => '<div id="%1$s" class="%2$s blogsy-sidebar-widget card-layout-w" data-aos="fade-up">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="blogsy-divider-heading divider-style-' . esc_attr( $divider_style ) . ' pt-mb-1">
										<div class="divider divider-1"></div>
											<div class="divider divider-2"></div>
											<h4 class="title">
												<span class="title-inner">
													<span class="title-text">',
				'after_title'   => '</span>
								</span>
							</h4>
							<div class="divider divider-3"></div>
							<div class="divider divider-4"></div>
						</div>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Offcanvas Sidebar', 'blogsy' ),
				'id'            => 'offcanvas-sidebar',
				'description'   => esc_html__( 'Offcanvas Sidebar Widgets', 'blogsy' ),
				'before_widget' => '<div id="%1$s" class="%2$s blogsy-sidebar-widget" data-aos="fade-up">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="blogsy-divider-heading divider-style-' . esc_attr( $divider_style ) . ' pt-mb-1">
										<div class="divider divider-1"></div>
											<div class="divider divider-2"></div>
											<h4 class="title">
												<span class="title-inner">
													<span class="title-text">',
				'after_title'   => '</span>
								</span>
							</h4>
							<div class="divider divider-3"></div>
							<div class="divider divider-4"></div>
						</div>',
			]
		);

		for ( $i = 1; $i <= 4; $i++ ) {
			register_sidebar(
				[
					// translators: 1, 2, 3, 4.
					'name'          => sprintf( esc_html__( 'Footer %d', 'blogsy' ), $i ),
					'id'            => 'blogsy-footer-' . $i,
					// translators: 1st, 2nd, 3rd, 4th.
					'description'   => sprintf( esc_html__( 'Widgets in this area are displayed in the %s footer column.', 'blogsy' ), \Blogsy\Helper::ordinal_suffix( $i ) ),
					'before_widget' => '<div id="%1$s" class="%2$s blogsy-sidebar-widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="blogsy-divider-heading divider-style-' . esc_attr( $divider_style ) . ' pt-mb-1">
										<div class="divider divider-1"></div>
											<div class="divider divider-2"></div>
											<h4 class="title">
												<span class="title-inner">
													<span class="title-text">',
					'after_title'   => '</span>
								</span>
							</h4>
							<div class="divider divider-3"></div>
							<div class="divider divider-4"></div>
						</div>',
				]
			);
		}
	}
}
