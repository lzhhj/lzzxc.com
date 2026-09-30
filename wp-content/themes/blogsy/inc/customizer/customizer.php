<?php
/**
 * Blogsy Customizer class
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

namespace Blogsy\Customizer;

use WP_Customize_Manager;

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blogsy Customizer class
 */
class Customizer {

	/**
	 * Singleton instance of the class.
	 *
	 * @var ?Customizer|null $instance
	 * @since 1.0.0
	 */
	private static ?self $instance = null;

	/**
	 * Customizer options.
	 *
	 * @since 1.0.0
	 * @var array $options
	 */
	private static array $options = [];

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
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Loads our Customizer custom controls.
		add_action( 'customize_register', [ $this, 'load_custom_controls' ] );

		// Loads our Customizer helper functions.
		add_action( 'customize_register', [ $this, 'load_customizer_helpers' ] );

		// Loads our Customizer widgets classes.
		add_action( 'customize_register', [ $this, 'load_customizer_widgets' ] );

		// Tweak inbuilt sections.
		add_action( 'customize_register', [ $this, 'customizer_tweak' ], 11 );

		// Registers our Customizer options.
		add_action( 'customize_register', [ $this, 'register_options_new' ] );

		// Loads our Customizer controls assets.
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'load_assets' ], 20 );

		// Enqueues our Customizer preview assets.
		add_action( 'customize_preview_init', [ $this, 'load_preview_assets' ] );

		add_action( 'customize_controls_print_footer_scripts', [ $this, 'blogsy_customizer_widgets' ] );
		add_action( 'customize_controls_print_footer_scripts', [ 'Blogsy_Customizer_Control', 'template_units' ] );
	}

	/**
	 * Loads our Customizer custom controls.
	 *
	 * @param WP_Customize_Manager $customizer Instance of WP_Customize_Manager class.
	 * @since 1.0.0
	 */
	public function load_custom_controls( WP_Customize_Manager $customizer ): void {

		// Directory where each custom control is located.
		$path = __DIR__ . '/controls/';

		// Require base control class.
		require $path . '/class-blogsy-customizer-control.php'; // phpcs:ignore

		$controls = $this->get_custom_controls();

		// Load custom controls classes.
		foreach ( $controls as $control => $class ) {
			$control_path = $path . '/' . $control . '/class-blogsy-customizer-control-' . $control . '.php';
			if ( file_exists( $control_path ) ) {
				require_once $control_path; // phpcs:ignore
				$customizer->register_control_type( $class );
			}
		}
	}

	/**
	 * Loads Customizer helper functions and sanitization callbacks.
	 *
	 * @since 1.0.0
	 */
	public function load_customizer_helpers(): void {
		require_once __DIR__ . '/customizer-helpers.php'; // phpcs:ignore
		require_once __DIR__ . '/customizer-callbacks.php'; // phpcs:ignore
		require_once __DIR__ . '/customizer-partials.php'; // phpcs:ignore
	}

	/**
	 * Loads Customizer widgets classes.
	 *
	 * @since 1.0.0
	 */
	public function load_customizer_widgets(): void {

		$widgets = blogsy_get_customizer_widgets();

		require __DIR__ . '/widgets/class-blogsy-customizer-widget.php'; // phpcs:ignore

		foreach ( $widgets as $id => $class ) {

			$path = __DIR__ . '/widgets/class-blogsy-customizer-widget-' . $id . '.php';

			if ( file_exists( $path ) ) {
				require $path; // phpcs:ignore
			}
		}
	}

	/**
	 * Move inbuilt panels into our sections.
	 *
	 * @param WP_Customize_Manager $customizer Instance of WP_Customize_Manager class.
	 * @since 1.0.0
	 */
	public static function customizer_tweak( WP_Customize_Manager $customizer ): void {

		// Site Identity to Logo.
		$customizer->get_section( 'title_tagline' )->title = esc_html__( 'Logos &amp; Site Title', 'blogsy' );

		// Custom logo.
		if ( $customizer->get_control( 'custom_logo' ) ) {
			$customizer->get_control( 'custom_logo' )->description = esc_html__( 'Upload your logo image here.', 'blogsy' );
			$customizer->get_setting( 'custom_logo' )->transport   = 'postMessage';

			// Add selective refresh partial for Custom Logo.
			$customizer->selective_refresh->add_partial(
				'custom_logo',
				[
					'selector'            => '#site-header .pt-logo',
					'render_callback'     => 'blogsy_logo',
					'container_inclusive' => false,
					'fallback_refresh'    => true,
				]
			);
		}

		// Site title.
		if ( $customizer->get_control( 'blogname' ) ) {
			$customizer->get_setting( 'blogname' )->transport   = 'postMessage';
			$customizer->get_control( 'blogname' )->description = esc_html__( 'Enter the name of your site here.', 'blogsy' );
			$customizer->get_control( 'blogname' )->priority    = 60;
		}

		// Site description.
		if ( $customizer->get_control( 'blogdescription' ) ) {
			$customizer->get_setting( 'blogdescription' )->transport   = 'postMessage';
			$customizer->get_control( 'blogdescription' )->description = esc_html__( 'A tagline is a short phrase, or sentence, used to convey the essence of the site.', 'blogsy' );
			$customizer->get_control( 'blogdescription' )->priority    = 70;
		}

		// Site icon.
		$customizer->get_control( 'site_icon' )->priority = 90;

		$customizer->get_section( 'title_tagline' )->panel = 'blogsy_general';
	}


	/**
	 * Registers our Customizer options.
	 *
	 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
	 * @since 1.0.0
	 */
	public function register_options_new( WP_Customize_Manager $customizer ): void {

		$options = $this->get_customizer_options();

		if ( isset( $options['panels'] ) && ! empty( $options['panels'] ) ) {
			foreach ( $options['panels'] as $id => $args ) {
				$this->add_panel( $id, $args, $customizer );
			}
		}

		if ( isset( $options['sections'] ) && ! empty( $options['sections'] ) ) {
			foreach ( $options['sections'] as $id => $args ) {
				$this->add_section( $id, $args, $customizer );
			}
		}

		if ( isset( $options['settings'] ) && ! empty( $options['settings'] ) ) {
			foreach ( $options['settings'] as $section => $settings ) {
				foreach ( $settings as $id => $args ) {

					if ( false === strpos( $id, 'blogsy_' ) ) {
						$id = 'blogsy_' . $id;
					}

					if ( false === strpos( $section, 'blogsy_' ) ) {
						$section = 'blogsy_' . $section;
					}

					if ( empty( $args['section'] ) ) {
						$args['section'] = $section;
					}

					if ( empty( $args['settings'] ) ) {
						$args['settings'] = $id;
					}
					$this->add_setting( $id, $args, $customizer );
					$this->add_control( $id, $args, $customizer );
				}
			}
		}
	}

	/**
	 * Filter and return Customizer options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Customizer options for registering Sections/Panels/Controls.
	 */
	public function get_customizer_options(): array {
		if ( [] !== self::$options ) {
			return self::$options;
		}
		return apply_filters( 'blogsy_customizer_options', [] );
	}

	/**
	 * Register Customizer Panel
	 *
	 * @param string               $id Panel id.
	 * @param array                $args Panel settings.
	 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
	 */
	private function add_panel( string $id, array $args, WP_Customize_Manager $customizer ): void {
		$class = \Blogsy\Helper::get_prop( $args, 'class', 'WP_Customize_Panel' );

		$customizer->add_panel( new $class( $customizer, $id, $args ) );
	}

	/**
	 * Register Customizer Section.
	 *
	 * @since 1.0.0
	 *
	 * @param string               $id Section id.
	 * @param array                $args Section settings.
	 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
	 */
	private function add_section( string $id, array $args, WP_Customize_Manager $customizer ): void {
		$class = \Blogsy\Helper::get_prop( $args, 'class', 'WP_Customize_Section' );
		$customizer->add_section( new $class( $customizer, $id, $args ) );
	}

	/**
	 * Register Customizer Control.
	 *
	 * @param string               $id Control id.
	 * @param array                $args Control settings.
	 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
	 * @since 1.0.0
	 */
	private function add_control( string $id, array $args, WP_Customize_Manager $customizer ): void {

		$class           = $args['class'] ?? $this->get_control_class( \Blogsy\Helper::get_prop( $args, 'type' ) );
		$args['setting'] = $id;
		if ( false !== $class ) {
			$customizer->add_control( new $class( $customizer, $id, $args ) );
		} else {
			$customizer->add_control( $id, $args );
		}
	}

	/**
	 * Register Customizer Setting.
	 *
	 * @param string               $id Control setting id.
	 * @param array                $setting Settings.
	 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
	 * @since 1.0.0
	 */
	private function add_setting( string $id, array $setting, WP_Customize_Manager $customizer ): void {
		unset( $setting['type'] );
		$setting = wp_parse_args( $setting, $this->get_customizer_defaults( 'setting' ) );
		$default = \Blogsy\Helper::get_prop( $setting, 'default' );
		$customizer->add_setting(
			$id,
			[
				'default'           => null != $default ? $default : \Blogsy\Helper::get_option_default( $id ),
				'type'              => \Blogsy\Helper::get_prop( $setting, 'type' ),
				'transport'         => \Blogsy\Helper::get_prop( $setting, 'transport' ),
				'sanitize_callback' => \Blogsy\Helper::get_prop( $setting, 'sanitize_callback', 'blogsy_no_sanitize' ),
			]
		);

		$partial = \Blogsy\Helper::get_prop( $setting, 'partial', false );

		if ( $partial && isset( $customizer->selective_refresh ) ) {

			$customizer->selective_refresh->add_partial(
				$id,
				[
					'selector'            => \Blogsy\Helper::get_prop( $partial, 'selector' ),
					'container_inclusive' => \Blogsy\Helper::get_prop( $partial, 'container_inclusive' ),
					'render_callback'     => \Blogsy\Helper::get_prop( $partial, 'render_callback' ),
					'fallback_refresh'    => \Blogsy\Helper::get_prop( $partial, 'fallback_refresh' ),
				]
			);
		}
	}

	/**
	 * Return custom controls.
	 *
	 * @since 1.0.0
	 *
	 * @return array custom control slugs & classnames.
	 */
	private function get_custom_controls(): array {
		return apply_filters(
			'blogsy_custom_customizer_controls',
			[
				'toggle'              => 'Blogsy_Customizer_Control_Toggle',
				'select'              => 'Blogsy_Customizer_Control_Select',
				'tags-input'          => 'Blogsy_Customizer_Control_Tags_Input',
				'heading'             => 'Blogsy_Customizer_Control_Heading',
				'color'               => 'Blogsy_Customizer_Control_Color',
				'range'               => 'Blogsy_Customizer_Control_Range',
				'radio'               => 'Blogsy_Customizer_Control_Radio',
				'spacing'             => 'Blogsy_Customizer_Control_Spacing',
				'widget'              => 'Blogsy_Customizer_Control_Widget',
				'radio-buttonset'     => 'Blogsy_Customizer_Control_Radio_Buttonset',
				'radio-image'         => 'Blogsy_Customizer_Control_Radio_Image',
				'background'          => 'Blogsy_Customizer_Control_Background',
				'image'               => 'Blogsy_Customizer_Control_Image',
				'text'                => 'Blogsy_Customizer_Control_Text',
				'number'              => 'Blogsy_Customizer_Control_Number',
				'textarea'            => 'Blogsy_Customizer_Control_Textarea',
				'typography'          => 'Blogsy_Customizer_Control_Typography',
				'button'              => 'Blogsy_Customizer_Control_Button',
				'sortable'            => 'Blogsy_Customizer_Control_Sortable',
				'info'                => 'Blogsy_Customizer_Control_Info',
				'design-options'      => 'Blogsy_Customizer_Control_Design_Options',
				'alignment'           => 'Blogsy_Customizer_Control_Alignment',
				'checkbox-group'      => 'Blogsy_Customizer_Control_Checkbox_Group',
				'repeater'            => 'Blogsy_Customizer_Control_Repeater',
				'editor'              => 'Blogsy_Customizer_Control_Editor',
				'generic-notice'      => 'Blogsy_Customizer_Control_Generic_Notice',
				'gallery'             => 'Blogsy_Customizer_Control_Gallery',
				'datetime'            => 'Blogsy_Customizer_Control_Datetime',
				'section-group-title' => 'Blogsy_Customizer_Control_Section_Group_Title',
				'section-pro'         => 'Blogsy_Customizer_Control_Section_Pro',
			]
		);
	}

	/**
	 * Return default values for customizer parts.
	 *
	 * @param  String $type setting or control.
	 * @return array  default values for the Customizer Configurations.
	 */
	private function get_customizer_defaults( string $type ): array {

		$defaults = [];

		switch ( $type ) {
			case 'setting':
				$defaults = [
					'type'      => 'theme_mod',
					'transport' => 'refresh',
				];
				break;

			case 'control':
				$defaults = [];
				break;

			default:
				break;
		}

		return apply_filters(
			'blogsy_customizer_configuration_defaults',
			$defaults,
			$type
		);
	}

	/**
	 * Get custom control classname.
	 *
	 * @param string $type Control ID.
	 *
	 * @return string Control classname.
	 * @since 1.0.0
	 */
	private function get_control_class( string $type ) {

		if ( false !== strpos( $type, 'blogsy-' ) ) {

			$controls = $this->get_custom_controls();
			$type     = trim( str_replace( 'blogsy-', '', $type ) );
			if ( isset( $controls[ $type ] ) ) {
				return $controls[ $type ];
			}
		}

		return false;
	}

	/**
	 * Loads our own Customizer assets.
	 *
	 * @since 1.0.0
	 */
	public function load_assets(): void {

		// Script debug.
		$blogsy_dir    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dev/' : '';
		$blogsy_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		/**
		 * Enqueue our Customizer styles.
		 */
		wp_enqueue_style(
			'blogsy-customizer-styles',
			BLOGSY_THEME_URI . '/inc/customizer/assets/css/blogsy-customizer' . $blogsy_suffix . '.css',
			false,
			BLOGSY_THEME_VERSION
		);

		/**
		 * Enqueue our Customizer controls script.
		 */
		wp_enqueue_script(
			'blogsy-customizer-js',
			BLOGSY_THEME_URI . '/inc/customizer/assets/js/' . $blogsy_dir . 'customize-controls' . $blogsy_suffix . '.js',
			[ 'customize-controls', 'wp-color-picker', 'jquery' ],
			BLOGSY_THEME_VERSION,
			true
		);

		/**
		 * Enqueue Customizer controls dependency script.
		 */
		wp_enqueue_script(
			'blogsy-control-dependency-js',
			BLOGSY_THEME_URI . '/inc/customizer/assets/js/' . $blogsy_dir . 'customize-dependency' . $blogsy_suffix . '.js',
			[ 'jquery' ],
			BLOGSY_THEME_VERSION,
			true
		);

		/**
		 * Localize JS variables
		 */
		$blogsy_customizer_localized = [
			'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
			'wpnonce'                 => wp_create_nonce( 'blogsy_customizer' ),
			'color_palette'           => [ '#ffffff', '#000000', '#e4e7ec', '#0068c8', '#f7b40b', '#e04b43', '#30373e', '#8a63d4' ],
			'preview_url_for_section' => $this->get_preview_urls_for_section(),
			'strings'                 => [
				'select_category'    => esc_html__( 'Select a category', 'blogsy' ),
				'error_loading_data' => esc_html__( 'Error loading data', 'blogsy' ),
			],
		];

		/**
		 * Allow customizer localized vars to be filtered.
		 */
		$blogsy_customizer_localized = apply_filters( 'blogsy_customizer_localized', $blogsy_customizer_localized );

		wp_localize_script(
			'blogsy-customizer-js',
			'blogsy_customizer_localized',
			$blogsy_customizer_localized
		);
	}

	/**
	 * Loads customizer preview assets
	 *
	 * @since 1.0.0
	 */
	public function load_preview_assets(): void {
		// Script debug.
		$blogsy_dir    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dev/' : '';
		$blogsy_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$version       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : BLOGSY_THEME_VERSION;

		wp_enqueue_script(
			'blogsy-customizer-preview-js',
			BLOGSY_THEME_URI . '/inc/customizer/assets/js/' . $blogsy_dir . 'customize-preview' . $blogsy_suffix . '.js',
			[ 'customize-preview', 'customize-selective-refresh', 'jquery' ],
			$version,
			true
		);

		// Enqueue Customizer preview styles.
		wp_enqueue_style(
			'blogsy-customizer-preview-styles',
			BLOGSY_THEME_URI . '/inc/customizer/assets/css/blogsy-customizer-preview' . $blogsy_suffix . '.css',
			false,
			BLOGSY_THEME_VERSION
		);

		/**
		 * Localize JS variables.
		 */
		$blogsy_customizer_localized = [
			'default_system_font' => \Blogsy\Helper::fonts()->get_default_system_font(),
			'fonts'               => \Blogsy\Helper::fonts()->get_fonts(),
			'google_fonts_url'    => '//fonts.googleapis.com',
			'google_font_weights' => '100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i',
		];

		/**
		 * Allow customizer localized vars to be filtered.
		 */
		$blogsy_customizer_localized = apply_filters( 'blogsy_customize_preview_localized', $blogsy_customizer_localized );

		wp_localize_script(
			'blogsy-customizer-preview-js',
			'blogsy_customizer_preview',
			$blogsy_customizer_localized
		);
	}

	/**
	 * Print the html template used to render the add top bar widgets frame.
	 *
	 * @since 1.0.0
	 */
	public function blogsy_customizer_widgets(): void {

		// Get customizer widgets.
		$widgets = blogsy_get_customizer_widgets();

		// Check if any available widgets exist.
		if ( ! is_array( $widgets ) || [] === $widgets ) {
			return;
		}
		?>
		<div id="blogsy-available-widgets">

			<div class="blogsy-widget-caption">
				<h3></h3>
				<a href="#" class="blogsy-close-widgets-panel"></a>
			</div><!-- END #blogsy-available-widgets-caption -->

			<div id="blogsy-available-widgets-list">

			<?php foreach ( $widgets as $classname ) { ?>
					<?php $widget = new $classname(); ?>

					<div id="blogsy-widget-tpl-<?php echo esc_attr( $widget->id_base ); ?>" data-widget-id="<?php echo esc_attr( $widget->id_base ); ?>" class="blogsy-widget">
						<?php $widget->template(); ?>
					</div>

				<?php } ?>

			</div><!-- END #blogsy-available-widgets-list -->
		</div>
					<?php
	}

	/**
	 * Get preview URL for a section. The URL will load when the section is opened.
	 *
	 * @return string
	 */
	public function get_preview_urls_for_section(): array {

		$return = [];

		// Preview a random single post for Single Post section.
		$posts = get_posts(
			[
				'post_type'      => 'post',
				'posts_per_page' => 1,
				'orderby'        => 'rand',
			]
		);

		if ( count( $posts ) > 0 ) {
			$return['blogsy_section_blog_single_post'] = get_permalink( $posts[0] );
		}

		// Preview blog page.
		$return['blogsy_section_blog_page'] = \Blogsy\Helper::get_blog_url();

		return $return;
	}
}
