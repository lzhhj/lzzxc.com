<?php
/**
 * Block Editor functions
 *
 * @package Blogsy
 */

namespace Blogsy\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Block Editor
 */
class Block_Editor {
	/**
	 * Instance
	 *
	 * @var Block_Editor|null $instance
	 */
	protected static ?self $instance = null;

	/**
	 * Initiator
	 *
	 * @return Block_Editor
	 * @since 1.0.0
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof self ) ) {
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
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ] );
	}

	/**
	 * Enqueue editor styles for Gutenberg
	 *
	 * @since 1.0.0
	 */
	public function block_editor_styles(): void {
		wp_enqueue_style( 'blogsy-block-editor-style', get_template_directory_uri() . '/admin/dashboard/assets/css/editor.css', [], BLOGSY_THEME_VERSION );
		// Enqueue google fonts.
		\Blogsy\Helper::fonts()->enqueue_google_fonts();

		// Add dynamic CSS as inline style.
		wp_add_inline_style(
			'blogsy-block-editor-style',
			apply_filters( 'blogsy_block_editor_dynamic_css', blogsy_dynamic_styles()->get_block_editor_css() )
		);
	}
}
