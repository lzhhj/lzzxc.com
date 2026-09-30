<?php
/**
 * Plugin utilities class.
 *
 * This class has functions to install, activate & deactivate plugins.
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since   1.0.0
 */

namespace Blogsy\Admin\Utilities;

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin utilities.
 * Class that contains methods for changing plugin status.
 *
 * @since 1.0.0
 */
class Plugin_Utilities {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var Plugin_Utilities|null $instance The one true instance of the class.
	 */
	private static ?self $instance = null;

	/**
	 * Main Blogsy Plugin Utilities Instance.
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
	 * Activate array of plugins.
	 *
	 * @param array $plugins Plugins to be activated.
	 * @return array{activate: mixed}[]
	 * @since 1.0.0
	 */
	public function activate_plugins( array $plugins ): array {

		$status = [];

		wp_clean_plugins_cache( false );

		// Activate plugins.
		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['activate'] = $this->activate_plugin( $plugin['slug'] );
		}

		return $status;
	}

	/**
	 * Activate individual plugin.
	 *
	 * @param null|string $plugin Plugin to be activated.
	 * @return null|\WP_Error
	 * @since 1.0.0
	 */
	public function activate_plugin( ?string $plugin ): ?\WP_Error {

		// Check permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return new \WP_Error(
				'plugin_activation_failed',
				esc_html__( "Current user can't activate plugins", 'blogsy' )
			);
		}

		// Validate plugin data.
		if ( null === $plugin || '' === $plugin || '0' === $plugin ) {
			return new \WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Missing plugin data.', 'blogsy' )
			);
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$plugin_data = get_plugins( '/' . $plugin );

		if ( empty( $plugin_data ) ) {
			return new \WP_Error(
				'plugin_activation_failed',
				sprintf(
					// translators: %s is plugin name.
					esc_html__( 'Plugin %s is not installed.', 'blogsy' ),
					$plugin
				)
			);
		}

		$plugin_file_array  = array_keys( $plugin_data );
		$plugin_file        = $plugin_file_array[0];
		$plugin_to_activate = $plugin . '/' . $plugin_file;
		$activate           = activate_plugin( $plugin_to_activate );

		if ( is_wp_error( $activate ) ) {
			return $activate;
		}

		do_action( 'blogsy_plugin_activated_' . $plugin );
		return null;
	}

	/**
	 * Deactivate individual plugin
	 *
	 * @param null|array $plugin Plugin to be deactivated.
	 * @return null|\WP_Error
	 * @since 1.0.0
	 */
	public function deactivate_plugin( ?string $plugin ): ?\WP_Error {

		// Check permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return new \WP_Error(
				'plugin_activation_failed',
				esc_html__( "Current user can't activate plugins", 'blogsy' )
			);
		}

		// Validate plugin data.
		if ( null === $plugin || '' === $plugin || '0' === $plugin ) {
			return new \WP_Error(
				'plugin_activation_failed',
				esc_html__( 'Missing plugin data.', 'blogsy' )
			);
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$plugin_data = get_plugins( '/' . $plugin );

		if ( empty( $plugin_data ) ) {
			return new \WP_Error(
				'plugin_deactivation_failed',
				sprintf(
					// translators: %s is plugin name.
					esc_html__( 'Plugin %s is not active.', 'blogsy' ),
					$plugin
				)
			);
		}

		$plugin_file_array    = array_keys( $plugin_data );
		$plugin_file          = $plugin_file_array[0];
		$plugin_to_deactivate = $plugin . '/' . $plugin_file;

		deactivate_plugins( $plugin_to_deactivate );

		do_action( 'blogsy_plugin_deactivated_' . $plugin );
		return null;
	}

	/**
	 * Check if plugin has a pending update.
	 *
	 * @param array    $plugin Plugin to be updated.
	 * @param boolean, $strict Force plugin to update. Optional. Default is false.
	 * @since 1.0.0
	 */
	public function has_update( array $plugin, $strict = false ) {

		$installed_plugin = $this->is_installed( $plugin['slug'] );

		if ( is_array( $installed_plugin ) && [] !== $installed_plugin ) {

			$plugin_name = array_keys( $installed_plugin );
			$plugin_name = $plugin_name[0];

			$plugin_version = [] !== $installed_plugin ? $installed_plugin[ $plugin_name ]['Version'] : null;

			if ( $plugin_name && ! empty( $plugin_version ) ) {
				if ( isset( $plugin['version'] ) ) {
					return version_compare( $plugin_version, $plugin['version'], '<' );
				} elseif ( $strict ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if plugin is installed.
	 *
	 * @param string $plugin Check if plugin is installed.
	 * @since 1.0.0
	 */
	public function is_installed( string $plugin ): bool {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$installed = get_plugins( '/' . $plugin );
		return ! empty( $installed );
	}

	/**
	 * Check if plugin is activated.
	 *
	 * @param string $plugin Check if plugin is activated.
	 * @return bool Whether the plugin is activated.
	 * @since 1.0.0
	 */
	public function is_activated( string $plugin ): bool {
		// Prevent a suppressed error within `get_plugins()` when the plugin is not installed.
		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			return false;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore
		}

		$installed_plugin = get_plugins( '/' . $plugin );

		if ( $installed_plugin ) {
			$plugin_name = array_keys( $installed_plugin );
			return is_plugin_active( $plugin . '/' . $plugin_name[0] );
		}

		return false;
	}

	/**
	 * Recommended plugins.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_recommended_plugins(): array {

		$plugins = [
			'hester-core' => [
				'name'  => 'Hester Core',
				'slug'  => 'hester-core',
				'desc'  => 'The Hester Core plugin adds extra functionality to hester theme, such as Demo Library, widgets, custom blocks and more.',
				'thumb' => 'https://ps.w.org/hester-core/assets/icon-256x256.png',
			],
			'woocommerce' => [
				'name'     => 'WooCommerce',
				'slug'     => 'woocommerce',
				'desc'     => 'WooCommerce is the open-source ecommerce platform for WordPress.',
				'required' => true,
				'thumb'    => 'https://ps.w.org/woocommerce/assets/icon-256x256.gif',
			],
		];

		return apply_filters( 'blogsy_recommended_plugins', $plugins );
	}

	/**
	 * Get non activated plugins from an array.
	 *
	 * @param array $plugins Filter non active plugins.
	 * @since 1.0.0
	 */
	public function get_deactivated_plugins( array $plugins ): array {

		if ( is_array( $plugins ) && [] !== $plugins ) {
			foreach ( array_keys( $plugins ) as $slug ) {
				if ( $this->is_activated( $slug ) ) {
					unset( $plugins[ $slug ] );
				}
			}
		}

		return $plugins;
	}

	/**
	 * Get plugin object based on slug.
	 *
	 * @param string $slug Plugin slug.
	 * @param array  $plugins Array of available plugins.
	 * @since 1.0.0
	 */
	public function get_plugin_by_slug( string $slug, array $plugins ) {

		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin['slug'] === $slug ) {
					return $plugin;
				}
			}
		}

		return false;
	}
}
