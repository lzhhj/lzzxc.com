<?php
/**
 * Admin functions and definitions.
 *
 * @package Blogsy
 * @since 1.0.0
 */

namespace Blogsy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin class
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
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		require_once BLOGSY_THEME_DIR . '/admin/utilities/plugin-utilities.php';
		require_once BLOGSY_THEME_DIR . '/admin/block-editor.php';
		require_once BLOGSY_THEME_DIR . '/admin/meta-boxes.php';
		require_once BLOGSY_THEME_DIR . '/admin/dashboard/admin.php';

		\Blogsy\Admin\Dashboard\Admin::instance();
		\Blogsy\Admin\Meta_Boxes::instance();
		\Blogsy\Admin\Block_Editor::instance();
	}
}
