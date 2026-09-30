<?php
/**
 * Admin class.
 *
 * This class ties together all admin classes.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

namespace Blogsy\Admin\Dashboard;

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Admin' ) ) :

	/**
	 * Admin Class
	 */
	class Admin {

		/**
		 * Singleton instance of the class.
		 *
		 * @var Admin|null
		 * @since 1.0.0
		 */
		private static ?self $instance = null;

		/**
		 * Main Admin Instance.
		 *
		 * @return Admin
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

			/**
			 * Include admin files.
			 */
			$this->includes();

			/**
			 * Load admin assets.
			 */
			add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

			/**
			 * Add filters for WordPress header and footer text.
			 */
			add_filter( 'update_footer', [ $this, 'filter_update_footer' ], 50 );
			add_filter( 'admin_footer_text', [ $this, 'filter_admin_footer_text' ], 50 );

			/**
			 * Admin page header.
			 */
			add_action( 'in_admin_header', [ $this, 'admin_header' ], 100 );

			/**
			 * Admin page footer.
			 */
			add_action( 'in_admin_footer', [ $this, 'admin_footer' ], 100 );

			/**
			 * Add notices.
			 */
			add_action( 'admin_notices', [ $this, 'admin_notices' ] );

			/**
			 * After admin loaded
			 */
			do_action( 'blogsy_admin_loaded' );
		}

		/**
		 * Includes files.
		 *
		 * @since 1.0.0
		 */
		private function includes(): void {

			/**
			 * Include helper functions.
			 */
			require_once BLOGSY_THEME_DIR . '/admin/dashboard/helpers.php'; // phpcs:ignore

			/**
			 * Include Blogsy welcome page.
			 */
			require_once BLOGSY_THEME_DIR . '/admin/dashboard/blogsy-dashboard.php'; // phpcs:ignore
		}

		/**
		 * Load our required assets on admin pages.
		 *
		 * @param string $hook it holds the information about the current page.
		 * @since 1.0.0
		 */
		public function load_assets( string $hook ): void {

			/**
			 * Do not enqueue if we are not on one of our pages.
			 */
			if ( ! blogsy_is_admin_page( $hook ) ) {
				return;
			}
			// Script debug.
			$prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'dev/' : '';
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			/**
			 * Enqueue admin pages stylesheet.
			 */
			wp_enqueue_style(
				'blogsy-admin-styles',
				BLOGSY_THEME_URI . '/admin/dashboard/assets/css/blogsy-admin' . $suffix . '.css',
				false,
				BLOGSY_THEME_VERSION
			);

			/**
			 * Enqueue admin pages script.
			 */
			wp_enqueue_script(
				'blogsy-admin-script',
				BLOGSY_THEME_URI . '/admin/dashboard/assets/js/' . $prefix . 'blogsy-admin' . $suffix . '.js',
				[ 'jquery', 'wp-util', 'updates' ],
				BLOGSY_THEME_VERSION,
				true
			);

			/**
			 * Localize admin strings.
			 */
			$texts = [
				'install'               => esc_html__( 'Install', 'blogsy' ),
				'install-inprogress'    => esc_html__( 'Installing...', 'blogsy' ),
				'activate-inprogress'   => esc_html__( 'Activating...', 'blogsy' ),
				'deactivate-inprogress' => esc_html__( 'Deactivating...', 'blogsy' ),
				'active'                => esc_html__( 'Active', 'blogsy' ),
				'retry'                 => esc_html__( 'Retry', 'blogsy' ),
				'please_wait'           => esc_html__( 'Please Wait...', 'blogsy' ),
				'importing'             => esc_html__( 'Importing... Please Wait...', 'blogsy' ),
				'currently_processing'  => esc_html__( 'Currently processing: ', 'blogsy' ),
				'import'                => esc_html__( 'Import', 'blogsy' ),
				'import_demo'           => esc_html__( 'Import Demo', 'blogsy' ),
				'importing_notice'      => esc_html__( 'The demo importer is still working. Closing this window may result in failed import.', 'blogsy' ),
				'import_complete'       => esc_html__( 'Import Complete!', 'blogsy' ),
				'import_complete_desc'  => esc_html__( 'The demo has been imported.', 'blogsy' ) . ' <a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Visit site.', 'blogsy' ) . '</a>',
			];

			$strings = [
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'wpnonce'       => wp_create_nonce( 'blogsy_nonce' ),
				'texts'         => $texts,
				'color_pallete' => [ '#0554f2', '#06cca6', '#2c2e3a', '#e4e7ec', '#f0b849', '#ffffff', '#000000' ],
			];

			$strings = apply_filters( 'blogsy_admin_strings', $strings );

			wp_localize_script( 'blogsy-admin-script', 'hester_strings', $strings );
		}

		/**
		 * Filters WordPress footer right text to hide all text.
		 *
		 * @param string $text Text that we're going to replace.
		 * @since 1.0.0
		 */
		public function filter_update_footer( $text = '' ): string {

			$screen = get_current_screen();

			if ( ! $screen ) {
				return (string) $text;
			}

			$base = $screen->base;

			/**
			 * Only do this if we are on one of our plugin pages.
			 */
			if ( blogsy_is_admin_page( $base ) ) {
				return apply_filters( 'blogsy_footer_version', esc_html__( 'Blogsy Theme', 'blogsy' ) . ' ' . BLOGSY_THEME_VERSION . '<br/><a href="' . esc_url( 'https://twitter.com/peregrine-themes' ) . '" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a><a href="' . esc_url( 'https://www.facebook.com/peregrinethemes/' ) . '" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a>' );
			} else {
				return (string) $text;
			}
		}

		/**
		 * Filter WordPress footer left text to display our text.
		 *
		 * @since 1.0.0
		 * @param string $text Text that we're going to replace.
		 */
		public function filter_admin_footer_text( $text ): ?string {

			if ( blogsy_is_admin_page() ) {
				return null;
			}

			return $text;
		}

		/**
		 * Outputs the page admin header.
		 *
		 * @since 1.0.0
		 */
		public function admin_header(): void {

			$base = get_current_screen()->base;

			if ( ! blogsy_is_admin_page( $base ) ) {
				return;
			}
			?>

			<div id="hester-header">
				<div class="hester-container">

					<a href="<?php echo esc_url( admin_url( 'admin.php?page=blogsy-dashboard' ) ); ?>" class="hester-logo">
						<img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/blogsy-logo.svg' ); ?>" alt="<?php echo esc_html( 'Blogsy' ); ?>" />
					</a>

					<span class="hester-header-action">
						<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'blogsy' ); ?></a>
						<a href="<?php echo esc_url( 'http://docs.peregrine-themes.com/' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Help Articles', 'blogsy' ); ?></a>
					</span>

				</div>
			</div><!-- END #hester-header -->
			<?php
		}

		/**
		 * Outputs the page admin footer.
		 *
		 * @since 1.0.0
		 */
		public function admin_footer(): void {

			$base = get_current_screen()->base;

			if ( ! blogsy_is_admin_page( $base ) || blogsy_is_admin_page( $base, 'hester_wizard' ) ) {
				return;
			}
			?>
			<div id="hester-footer">
			<ul>
				<li><a href="<?php echo esc_url( 'http://docs.peregrine-themes.com/' ); ?>" target="_blank" rel="noopener noreferrer"><span><?php esc_html_e( 'Help Articles', 'blogsy' ); ?></span></span></a></li>
				<li><a href="<?php echo esc_url( 'https://www.facebook.com/peregrinethemes/' ); ?>" target="_blank" rel="noopener noreferrer"><span><?php esc_html_e( 'Follow on Facebook', 'blogsy' ); ?></span></span></a></li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/support/theme/blogsy/reviews/#new-post' ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-heart" aria-hidden="true"></span><span><?php esc_html_e( 'Leave a Review', 'blogsy' ); ?></span></a></li>
			</ul>
			</div><!-- END #hester-footer -->

			<?php
		}

		/**
		 * Admin Notices
		 *
		 * @since 1.0.0
		 */
		public function admin_notices(): void {

			$screen = get_current_screen();

			// Display on Dashboard, Themes and Blogsy admin pages.
			if ( ! in_array( $screen->base, [ 'dashboard', 'themes' ], true ) && ! blogsy_is_admin_page() ) {
				return;
			}

			// Display if not dismissed and not on Blogsy plugins page.
			if ( ! blogsy_is_notice_dismissed( 'blogsy_notice_recommended-plugins' ) && ! blogsy_is_admin_page( false, 'blogsy-plugins' ) ) {

				$plugins = blogsy_plugin_utilities()->get_recommended_plugins();
				$plugins = blogsy_plugin_utilities()->get_deactivated_plugins( $plugins );

				$plugin_list = '';

				if ( is_array( $plugins ) && [] !== $plugins ) {

					foreach ( $plugins as $slug => $plugin ) {

						$url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . esc_attr( $slug ) . '&TB_iframe=true&width=990&height=500' );

						$plugin_list .= '<a href="' . esc_url( $url ) . '" class="thickbox">' . esc_html( $plugin['name'] ) . '</a>, ';
					}

					wp_enqueue_script( 'plugin-install' );
					add_thickbox();

					$plugin_list = trim( $plugin_list, ', ' );

					/* translators: %1$s <strong> tag, %2$s </strong> tag */
					$message = sprintf( wp_kses_post( __( 'Blogsy theme recommends the following plugins: %1$s.', 'blogsy' ) ), $plugin_list );

					$navigation_items = blogsy_dashboard()->get_navigation_items();

					blogsy_print_notice(
						[
							'type'        => 'info',
							'message'     => $message,
							'message_id'  => 'recommended-plugins',
							'expires'     => 7 * 24 * 60 * 60,
							'action_link' => $navigation_items['plugins']['url'],
							'action_text' => esc_html__( 'Install Now', 'blogsy' ),
						]
					);
				}
			}
		}
	}
endif;
