<?php
/**
 * Plugin install helper.
 *
 * @package Blogsy
 * @since Blogsy 1.0.0
 */

/**
 * Class Blogsy_Customizer_Plugin_Install_Helper
 */
class Blogsy_Customizer_Plugin_Install_Helper {
	/**
	 * Instance of class.
	 *
	 * @var null|self $instance instance variable.
	 */
	private static ?self $instance = null;


	/**
	 * Check if instance already exists.
	 */
	public static function instance(): self {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get plugin path based on plugin slug.
	 *
	 * @param string $slug Plugin slug.
	 */
	public static function get_plugin_path( string $slug ): string {

		switch ( $slug ) {
			case 'mailin':
				return $slug . '/sendinblue.php';
			case 'wpforms-lite':
				return $slug . '/wpforms.php';
			case 'intergeo-maps':
			case 'visualizer':
			case 'translatepress-multilingual':
				return $slug . '/index.php';
			case 'beaver-builder-lite-version':
				return $slug . '/fl-builder.php';
			case 'adblock-notify-by-bweb':
				return $slug . '/adblock-notify.php';
			default:
				return $slug . '/' . $slug . '.php';
		}
	}

	/**
	 * Generate action button html.
	 *
	 * @param string $slug plugin slug.
	 * @param array  $settings additional settings.
	 */
	public function get_button_html( string $slug, array $settings = [] ): string {
		$button   = '';
		$redirect = '';
		if ( ! empty( $settings ) && array_key_exists( 'redirect', $settings ) ) {
			$redirect = $settings['redirect'];
		}

		$state = $this->check_plugin_state( $slug );
		if ( empty( $slug ) ) {
			return '';
		}

		$plugin_name = '';
		if ( ! empty( $settings ) && array_key_exists( 'plugin_name', $settings ) ) {
			$plugin_name = $settings['plugin_name'];
		}

		$additional = '';

		if ( 'deactivate' === $state ) {
			$additional = ' action_button active';
		}

		$button .= '<div class=" plugin-card-' . esc_attr( $slug ) . esc_attr( $additional ) . '" style="padding: 8px 0 5px;">';

		$plugin_link_suffix = self::get_plugin_path( $slug );

		$nonce = add_query_arg(
			[
				'action'        => 'activate',
				'plugin'        => rawurlencode( $plugin_link_suffix ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
			],
			network_admin_url( 'plugins.php' )
		);
		switch ( $state ) {
			case 'install':
				$button .= '<a data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="install-now blogsy-install-plugin button  " href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="Install ' . esc_attr( $slug ) . '">' . __( 'Install and activate', 'blogsy' ) . ' ' . esc_html( $plugin_name ) . '</a>';
				break;

			case 'activate':
				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="activate-now button button-primary" href="' . esc_url( $nonce ) . '" aria-label="Activate ' . esc_attr( $slug ) . '">' . esc_html__( 'Activate', 'blogsy' ) . ' ' . esc_html( $plugin_name ) . '</a>';
				break;

			case 'deactivate':
				$nonce = add_query_arg(
					[
						'action'        => 'deactivate',
						'plugin'        => rawurlencode( $plugin_link_suffix ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $plugin_link_suffix ),
					],
					network_admin_url( 'plugins.php' )
				);

				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" data-slug="' . esc_attr( $slug ) . '" class="deactivate-now button" href="' . esc_url( $nonce ) . '" data-name="' . esc_attr( $slug ) . '" aria-label="Deactivate ' . esc_attr( $slug ) . '">' . esc_html__( 'Deactivate', 'blogsy' ) . ' ' . esc_html( $plugin_name ) . '</a>';
				break;

			case 'enable_cpt':
				$url     = admin_url( 'admin.php?page=jetpack#/settings' );
				$button .= '<a  data-redirect="' . esc_url( $redirect ) . '" class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Activate', 'blogsy' ) . ' ' . esc_html__( 'Jetpack Portfolio', 'blogsy' ) . '</a>';
				break;
		}

		return $button . '</div>';
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $slug plugin slug.
	 */
	public function check_plugin_state( string $slug ): string {

		$plugin_link_suffix = self::get_plugin_path( $slug );

		if ( is_file( ABSPATH . 'wp-content/plugins/' . $plugin_link_suffix ) ) {
			$needs = is_plugin_active( $plugin_link_suffix ) ? 'deactivate' : 'activate';
			if ( 'deactivate' === $needs && ! post_type_exists( 'portfolio' ) && 'jetpack' === $slug ) {
				return 'enable_cpt';
			}

			return $needs;
		} else {
			return 'install';
		}
	}

	/**
	 * Enqueue Function.
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script( 'plugin-install' );
		wp_enqueue_script( 'updates' );
		wp_enqueue_script( 'blogsy-plugin-install-helper', get_template_directory_uri() . '/inc/customizer/ui/plugin-install-helper/helper-script.js', [ 'jquery' ], BLOGSY_THEME_VERSION, true );
		wp_localize_script(
			'blogsy-plugin-install-helper',
			'blogsy_plugin_helper',
			[
				'activating' => esc_html__( 'Activating ', 'blogsy' ),
			]
		);
		wp_localize_script(
			'blogsy-plugin-install-helper',
			'pagenow',
			[ 'import' ]
		);
	}
}
