<?php
/**
 * Blogsy Autoload init
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package blogsy
 */

namespace Blogsy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Blogsy AutoLoad.
 */
class AutoLoad {
	/**
	 * Instance
	 *
	 * @var AutoLoad|null $instance
	 */
	private static ?self $instance = null;

	/**
	 * Initiator
	 *
	 * @return AutoLoad
	 * @since 1.0.0
	 */
	public static function init(): self {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'load' ] );
	}

	/**
	 * Auto load widgets.
	 *
	 * @param string $class_name Class name to load.
	 * @since 1.0.0
	 */
	public function load( string $class_name ): void {
		if ( false === strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		// Strip namespace and convert to file path.
		$relative = preg_replace( '/^' . preg_quote( __NAMESPACE__ ) . '\\\/', '', $class_name );
		$relative = strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $relative ) );

		$file = get_template_directory() . '/inc/' . $relative . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}

AutoLoad::init();
