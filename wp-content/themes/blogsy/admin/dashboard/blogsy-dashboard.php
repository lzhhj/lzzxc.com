<?php
/**
 * Blogsy About page class.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blogsy_Dashboard' ) ) :
	/**
	 * Blogsy Dashboard page class.
	 */
	final class Blogsy_Dashboard {

		/**
		 * Singleton instance of the class.
		 *
		 * @var Blogsy_Dashboard|null
		 * @since 1.0.0
		 */
		private static ?self $instance = null;

		/**
		 * Main Blogsy Dashboard Instance.
		 *
		 * @return Blogsy_Dashboard
		 * @since 1.0.0
		 */
		public static function instance(): self {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Blogsy_Dashboard ) ) {
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
			 * Register admin menu item under Appearance menu item.
			 */
			add_action( 'admin_menu', [ $this, 'add_to_menu' ], 10 );
			add_filter( 'submenu_file', [ $this, 'highlight_submenu' ] );

			/**
			 * Ajax activate & deactivate plugins.
			 */
			add_action( 'wp_ajax_hester-plugin-activate', [ $this, 'activate_plugin' ] );
			add_action( 'wp_ajax_hester-plugin-deactivate', [ $this, 'deactivate_plugin' ] );
		}

		/**
		 * Register our custom admin menu item.
		 *
		 * @since 1.0.0
		 */
		public function add_to_menu(): void {

			/**
			 * Dashboard page.
			 */
			add_theme_page(
				esc_html__( 'Blogsy Theme', 'blogsy' ),
				'Blogsy Theme',
				apply_filters( 'blogsy_manage_cap', 'edit_theme_options' ),
				'blogsy-dashboard',
				[ $this, 'render_dashboard' ]
			);

			/**
			 * Plugins page.
			 */
			add_theme_page(
				esc_html__( 'Plugins', 'blogsy' ),
				'Plugins',
				apply_filters( 'blogsy_manage_cap', 'edit_theme_options' ),
				'blogsy-plugins',
				[ $this, 'render_plugins' ]
			);

			// Hide from admin navigation.
			remove_submenu_page( 'themes.php', 'blogsy-plugins' );

			/**
			 * Changelog page.
			 */
			add_theme_page(
				esc_html__( 'Changelog', 'blogsy' ),
				'Changelog',
				apply_filters( 'blogsy_manage_cap', 'edit_theme_options' ),
				'blogsy-changelog',
				[ $this, 'render_changelog' ]
			);

			// Hide from admin navigation.
			remove_submenu_page( 'themes.php', 'blogsy-changelog' );
		}

		/**
		 * Render dashboard page.
		 *
		 * @since 1.0.0
		 */
		public function render_dashboard(): void {

			// Render dashboard navigation.
			$this->render_navigation();

			?>
			<div class="hester-container">

				<div class="hester-section-title">
					<h2 class="hester-section-title"><?php esc_html_e( 'Getting Started', 'blogsy' ); ?></h2>
				</div><!-- END .hester-section-title -->

				<div class="hester-section hester-columns">

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-admin-plugins"></i><?php esc_html_e( 'Install Plugins', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Explore recommended plugins. These free plugins provide additional features and customization options.', 'blogsy' ); ?></p>

							<div class="hester-buttons">
								<a href="<?php echo esc_url( menu_page_url( 'blogsy-plugins', false ) ); ?>" class="hester-btn secondary" role="button"><?php esc_html_e( 'Install Plugins', 'blogsy' ); ?></a>
							</div><!-- END .hester-buttons -->
						</div>
					</div>

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-layout"></i><?php esc_html_e( 'Start with a Template', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Don&rsquo;t want to start from scratch? Import a pre-built demo website in 1-click and get a head start.', 'blogsy' ); ?></p>

							<div class="hester-buttons plugins">

								<?php
								if ( file_exists( WP_PLUGIN_DIR . '/hester-core/hester-core.php' ) && is_plugin_inactive( 'hester-core/hester-core.php' ) ) {
									$class       = 'hester-btn secondary';
									$button_text = __( 'Activate Hester Core', 'blogsy' );
									$link        = '#';
									$data        = ' data-plugin="hester-core" data-action="activate" data-redirect="' . esc_url( admin_url( 'admin.php?page=blogsy-demo-library' ) ) . '"';
								} elseif ( ! file_exists( WP_PLUGIN_DIR . '/hester-core/hester-core.php' ) ) {
									$class       = 'hester-btn secondary';
									$button_text = __( 'Install Hester Core', 'blogsy' );
									$link        = '#';
									$data        = ' data-plugin="hester-core" data-action="install" data-redirect="' . esc_url( admin_url( 'admin.php?page=blogsy-demo-library' ) ) . '"';
								} else {
									$class       = 'hester-btn secondary active';
									$button_text = __( 'Browse Demos', 'blogsy' );
									$link        = admin_url( 'admin.php?page=blogsy-demo-library' );
									$data        = '';
								}

								printf(
									'<a class="%1$s" %2$s %3$s role="button"> %4$s </a>',
									esc_attr( $class ),
									isset( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
									$data, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									esc_html( $button_text )
								);
								?>

							</div><!-- END .hester-buttons -->
						</div>
					</div>

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-palmtree"></i><?php esc_html_e( 'Upload Your Logo', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Kick off branding your new site by uploading your logo. Simply upload your logo and customize as you need.', 'blogsy' ); ?></p>

							<div class="hester-buttons">
								<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=custom_logo' ) ); ?>" class="hester-btn secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upload Logo', 'blogsy' ); ?></a>
							</div><!-- END .hester-buttons -->
						</div>
					</div>

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-welcome-widgets-menus"></i><?php esc_html_e( 'Change Menus', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Customize menu links and choose what&rsquo;s displayed in available theme menu locations.', 'blogsy' ); ?></p>

							<div class="hester-buttons">
								<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="hester-btn secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Go to Menus', 'blogsy' ); ?></a>
							</div><!-- END .hester-buttons -->
						</div>
					</div>

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-art"></i><?php esc_html_e( 'Change Colors', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Replace the default theme colors and make your website color scheme match your brand design.', 'blogsy' ); ?></p>

							<div class="hester-buttons">
								<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=blogsy_section_colors' ) ); ?>" class="hester-btn secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Change Colors', 'blogsy' ); ?></a>
							</div><!-- END .hester-buttons -->
						</div>
					</div>

					<div class="hester-column">
						<div class="hester-box">
							<h4><i class="dashicons dashicons-editor-help"></i><?php esc_html_e( 'Need Help?', 'blogsy' ); ?></h4>
							<p><?php esc_html_e( 'Head over to our site to learn more about the Blogsy theme, read help articles and get support.', 'blogsy' ); ?></p>

							<div class="hester-buttons">
								<a href="http://docs.peregrine-themes.com/" target="_blank" rel="noopener noreferrer" class="hester-btn secondary"><?php esc_html_e( 'Help Articles', 'blogsy' ); ?></a>
							</div><!-- END .hester-buttons -->
						</div>
					</div>
				</div><!-- END .hester-section -->

				<div class="hester-section large-section">
					<div class="hester-hero">
						<img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/blogsy-customize.svg' ); ?>" alt="<?php echo esc_html( 'Customize' ); ?>" />
					</div>

					<h2><?php esc_html_e( 'Letâ€˜s customize your website', 'blogsy' ); ?></h2>
					<p><?php esc_html_e( 'There are many changes you can make to customize your website. Explore Blogsy customization options and make it unique.', 'blogsy' ); ?></p>

					<div class="hester-buttons">
						<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="hester-btn primary large-button"><?php esc_html_e( 'Start Customizing', 'blogsy' ); ?></a>
					</div><!-- END .hester-buttons -->

				</div><!-- END .hester-section -->

				<?php do_action( 'blogsy_about_content_after' ); ?>

			</div><!-- END .hester-container -->

			<?php
		}

		/**
		 * Render the recommended plugins page.
		 *
		 * @since 1.0.0
		 */
		public function render_plugins(): void {

			// Render dashboard navigation.
			$this->render_navigation();

			$plugins = blogsy_plugin_utilities()->get_recommended_plugins();
			?>
			<div class="hester-container">

				<div class="hester-section-title">
					<h2 class="hester-section-title"><?php esc_html_e( 'Recommended Plugins', 'blogsy' ); ?></h2>
				</div><!-- END .hester-section-title -->

				<div class="hester-section hester-columns plugins">

					<?php if ( is_array( $plugins ) && [] !== $plugins ) { ?>
						<?php foreach ( $plugins as $plugin ) { ?>

							<?php
							// Check plugin status.
							if ( blogsy_plugin_utilities()->is_activated( $plugin['slug'] ) ) {
								$btn_class = 'hester-btn secondary';
								$btn_text  = esc_html__( 'Deactivate', 'blogsy' );
								$action    = 'deactivate';
								$notice    = '<span class="hester-active-plugin"><span class="dashicons dashicons-yes"></span>' . esc_html__( 'Plugin activated', 'blogsy' ) . '</span>';
							} elseif ( blogsy_plugin_utilities()->is_installed( $plugin['slug'] ) ) {
								$btn_class = 'hester-btn primary';
								$btn_text  = esc_html__( 'Activate', 'blogsy' );
								$action    = 'activate';
								$notice    = '';
							} else {
								$btn_class = 'hester-btn primary';
								$btn_text  = esc_html__( 'Install & Activate', 'blogsy' );
								$action    = 'install';
								$notice    = '';
							}
							?>

							<div class="hester-column column-6">
								<div class="hester-box">

									<div class="plugin-image">
										<img src="<?php echo esc_url( $plugin['thumb'] ); ?>" alt="<?php echo esc_html( $plugin['name'] ); ?>"/>
									</div>

									<div class="plugin-info">
										<h4><?php echo esc_html( $plugin['name'] ); ?></h4>
										<p><?php echo esc_html( $plugin['desc'] ); ?></p>
										<div class="hester-buttons">
											<?php echo ( wp_kses_post( $notice ) ); ?>
											<a href="#" class="<?php echo esc_attr( $btn_class ); ?>" data-plugin="<?php echo esc_attr( $plugin['slug'] ); ?>" data-action="<?php echo esc_attr( $action ); ?>"><?php echo esc_html( $btn_text ); ?></a>
										</div>
									</div>

								</div>
							</div>
						<?php } ?>
					<?php } ?>

				</div><!-- END .hester-section -->

				<?php do_action( 'blogsy_recommended_plugins_after' ); ?>

			</div><!-- END .hester-container -->

			<?php
		}

		/**
		 * Render the changelog page.
		 *
		 * @since 1.0.0
		 */
		public function render_changelog(): void {

			// Render dashboard navigation.
			$this->render_navigation();

			$changelog = BLOGSY_THEME_DIR . '/changelog.txt';

			if ( ! file_exists( $changelog ) ) {
				$changelog = esc_html__( 'Changelog file not found.', 'blogsy' );
			} elseif ( ! is_readable( $changelog ) ) {
				$changelog = esc_html__( 'Changelog file not readable.', 'blogsy' );
			} else {
				global $wp_filesystem;

				// Check if the the global filesystem isn't setup yet.
				if ( is_null( $wp_filesystem ) ) {
					WP_Filesystem();
				}

				$changelog = $wp_filesystem->get_contents( $changelog );
			}

			?>
			<div class="hester-container">

				<div class="hester-section-title">
					<h2 class="hester-section-title">
						<span><?php esc_html_e( 'Blogsy Theme Changelog', 'blogsy' ); ?></span>
						<span class="changelog-version"><?php echo esc_html( sprintf( 'v%1$s', BLOGSY_THEME_VERSION ) ); ?></span>
					</h2>

				</div><!-- END .hester-section-title -->

				<div class="hester-section hester-columns">

					<div class="hester-column column-12">
						<div class="hester-box hester-changelog">
							<pre><?php echo esc_html( $changelog ); ?></pre>
						</div>
					</div>
				</div><!-- END .hester-columns -->

				<?php do_action( 'blogsy_after_changelog' ); ?>

			</div><!-- END .hester-container -->
			<?php
		}

		/**
		 * Render admin page navigation tabs.
		 *
		 * @since 1.0.0
		 */
		public function render_navigation(): void {

			// Get navigation items.
			$menu_items = $this->get_navigation_items();

			?>
			<div class="hester-container">

				<div class="hester-tabs">
					<ul>
						<?php
						// Determine current tab.
						$base = $this->get_current_page();

						// Display menu items.
						foreach ( $menu_items as $item ) {

							// Check if we're on a current item.
							$current = false !== strpos( $base, (string) $item['id'] ) ? 'current-item' : '';
							?>

							<li class="<?php echo esc_attr( $current ); ?>">
								<a href="<?php echo esc_url( $item['url'] ); ?>">
									<?php echo esc_html( $item['name'] ); ?>

									<?php
									if ( isset( $item['icon'] ) && $item['icon'] ) {
										blogsy_print_admin_icon( $item['icon'] );
									}
									?>
								</a>
							</li>

						<?php } ?>
					</ul>
				</div><!-- END .hester-tabs -->

			</div><!-- END .hester-container -->
			<?php
		}

		/**
		 * Return the current Blogsy Dashboard page.
		 *
		 * @since 1.0.0
		 * @return string $page Current dashboard page slug.
		 */
		public function get_current_page(): string {

			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 'dashboard'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$page = str_replace( 'blogsy-', '', $page );
			$page = apply_filters( 'blogsy_dashboard_current_page', $page );

			return esc_html( $page );
		}

		/**
		 * Print admin page navigation items.
		 *
		 * @since 1.0.0
		 * @return array $items Array of navigation items.
		 */
		public function get_navigation_items(): array {

			$items = [
				'dashboard' => [
					'id'   => 'dashboard',
					'name' => esc_html__( 'About', 'blogsy' ),
					'icon' => '',
					'url'  => menu_page_url( 'blogsy-dashboard', false ),
				],
				'plugins'   => [
					'id'   => 'plugins',
					'name' => esc_html__( 'Recommended Plugins', 'blogsy' ),
					'icon' => '',
					'url'  => menu_page_url( 'blogsy-plugins', false ),
				],
				'changelog' => [
					'id'   => 'changelog',
					'name' => esc_html__( 'Changelog', 'blogsy' ),
					'icon' => '',
					'url'  => menu_page_url( 'blogsy-changelog', false ),
				],
			];

			return apply_filters( 'blogsy_dashboard_navigation_items', $items );
		}

		/**
		 * Activate plugin.
		 *
		 * @since 1.0.0
		 */
		public function activate_plugin(): void {

			// Security check.
			check_ajax_referer( 'blogsy_nonce' );

			// Plugin data.
			$plugin = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';

			if ( empty( $plugin ) ) {
				wp_send_json_error( esc_html__( 'Missing plugin data', 'blogsy' ) );
			}

			if ( $plugin ) {

				$response = blogsy_plugin_utilities()->activate_plugin( $plugin );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error( $response->get_error_message(), $response->get_error_code() );
				}

				wp_send_json_success();
			}

			wp_send_json_error( esc_html__( 'Failed to activate plugin. Missing plugin data.', 'blogsy' ) );
		}

		/**
		 * Deactivate plugin.
		 *
		 * @since 1.0.0
		 */
		public function deactivate_plugin(): void {

			// Security check.
			check_ajax_referer( 'blogsy_nonce' );

			// Plugin data.
			$plugin = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';

			if ( empty( $plugin ) ) {
				wp_send_json_error( esc_html__( 'Missing plugin data', 'blogsy' ) );
			}

			if ( $plugin ) {
				$response = blogsy_plugin_utilities()->deactivate_plugin( $plugin );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error( $response->get_error_message(), $response->get_error_code() );
				}

				wp_send_json_success();
			}

			wp_send_json_error( esc_html__( 'Failed to deactivate plugin. Missing plugin data.', 'blogsy' ) );
		}

		/**
		 * Highlight dashboard page for plugins page.
		 *
		 * @param ?string $submenu_file The submenu file.
		 * @since 1.0.0
		 */
		public function highlight_submenu( ?string $submenu_file ): ?string {

			global $pagenow;

			// Check if we're on blogsy plugins or changelog page.
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'themes.php' === $pagenow && isset( $_GET['page'] ) && ( 'blogsy-plugins' === $_GET['page'] || 'blogsy-changelog' === $_GET['page'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$submenu_file = 'blogsy-dashboard';
			}

			return $submenu_file;
		}
	}
endif;

/**
 * The function which returns the one Blogsy_Dashboard instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $blogsy_dashboard = blogsy_dashboard(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function blogsy_dashboard() {
	return Blogsy_Dashboard::instance();
}

blogsy_dashboard();
