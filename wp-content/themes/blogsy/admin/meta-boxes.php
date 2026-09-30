<?php
/**
 * Meta boxes functions
 *
 * @package Blogsy
 */

namespace Blogsy\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Meta boxes initial
 */
class Meta_Boxes {
	/**
	 * Instance
	 *
	 * @var Meta_Boxes|null $instance
	 */
	protected static ?self $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
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
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_meta_boxes' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
	}

	/**
	 * Enqueue admin scripts for media uploader.
	 *
	 * @param string $hook Current admin page hook.
	 * @since 1.0.0
	 */
	public function enqueue_admin_scripts( string $hook ): void {
		if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'blogsy-metabox-media', get_template_directory_uri() . '/admin/assets/css/metabox.css', [], BLOGSY_THEME_VERSION );
		wp_enqueue_script( 'blogsy-metabox-media', get_template_directory_uri() . '/admin/assets/js/metabox.js', [ 'jquery' ], BLOGSY_THEME_VERSION, true );
	}

	/**
	 * Get all metaboxes via filter
	 *
	 * @since 1.0.0
	 */
	public function get_meta_boxes(): array {
		$meta_boxes = [];

		if ( ! class_exists( '\Blogsy\Addons\Metaboxes\Meta_Boxes' ) ) {
			$meta_boxes[] = $this->page_layout_aside_meta_box();
			$meta_boxes[] = $this->post_meta_box();
		}

		$meta_boxes[] = $this->disable_page_card_style_meta_box();
		return apply_filters( 'blogsy_metaboxes_settings', $meta_boxes );
	}

	/**
	 * Register metaboxes natively.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes(): void {
		foreach ( $this->get_meta_boxes() as $metabox ) {
			if ( empty( $metabox['id'] ) || empty( $metabox['title'] ) || empty( $metabox['pages'] ) ) {
					continue;
			}

			foreach ( (array) $metabox['pages'] as $post_type ) {
				add_meta_box(
					$metabox['id'],
					$metabox['title'],
					function ( $post ) use ( $metabox ): void {
						$this->render_meta_box_fields( $metabox, $post );
					},
					$post_type,
					$metabox['context'] ?? 'advanced',
					$metabox['priority'] ?? 'default'
				);
			}
		}
	}

	/**
	 * Render metabox fields.
	 *
	 * @param array    $metabox Metabox settings.
	 * @param \WP_Post $post Current post object.
	 * @since 1.0.0
	 */
	public function render_meta_box_fields( array $metabox, \WP_Post $post ): void {
		wp_nonce_field( 'blogsy_save_metabox_' . $metabox['id'], 'blogsy_metabox_nonce_' . $metabox['id'] );
		if ( empty( $metabox['fields'] ) ) {
			return;
		}

		echo '<div class="blogsy-metabox-fields">';
		foreach ( $metabox['fields'] as $field ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
			$this->render_field( $field, $value );
		}

		echo '</div>';
	}

	/**
	 * Render a single field.
	 *
	 * @param array $field Field settings.
	 * @param mixed $value Current value of the field.
	 * @since 1.0.0
	 * @todo Add more field types and validation.
	 */
	public function render_field( array $field, $value ): void {
		$id    = esc_attr( $field['id'] );
		$name  = esc_attr( $field['id'] );
		$label = empty( $field['name'] ) ? '' : $field['name'];
		$desc  = empty( $field['desc'] ) ? '' : '<p class="description">' . $field['desc'] . '</p>';
		$type  = empty( $field['type'] ) ? 'text' : $field['type'];

		// Add data attributes for conditional display.
		$data_attrs = '';
		if ( ! empty( $field['require'] ) && is_array( $field['require'] ) ) {
			foreach ( $field['require'] as $cond_key => $cond_val ) {
				$data_attrs .= ' data-require-' . esc_attr( $cond_key ) . '="' . esc_attr( $cond_val ) . '"';
			}
		}

		echo '<div class="blogsy-metabox-field blogsy-metabox-field-' . esc_attr( $type ) . '"' . $data_attrs . '>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( 'checkbox' !== $type && 'heading' !== $type && $label ) {
			echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br />';
		}

		switch ( $type ) {
			case 'text':
			default:
					echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
				break;
			case 'textarea':
				echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="widefat" rows="4">' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'checkbox':
						echo '<label><input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1"' . checked( $value, '1', false ) . ' /> ' . esc_html( $label ) . '</label>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="widefat">';
				if ( ! empty( $field['options'] ) && is_array( $field['options'] ) ) {
					foreach ( $field['options'] as $opt_value => $opt_label ) {
						echo '<option value="' . esc_attr( $opt_value ) . '"' . selected( $value, $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
					}
				}

							echo '</select>';
				break;
			case 'heading':
				echo '<h4 style="margin:1em 0 0.5em 0;">' . esc_html( $label ) . '</h4>';
				break;
			case 'image':
				// Simple image upload (single image).
				$img = $value ? wp_get_attachment_image( $value, 'thumbnail' ) : '';
				echo '<div class="blogsy-image-preview">' . wp_kses( $img, [ 'img' => [ 'src' => [] ] ] ) . '</div>';
				echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
				echo '<button type="button" class="button blogsy-upload-image" data-target="' . esc_attr( $id ) . '">' . esc_html__( 'Select Image', 'blogsy' ) . '</button>';
				break;
			case 'gallery':
			case 'image_advanced':
				// Multiple images.
				$ids = is_array( $value ) ? $value : ( $value ? explode( ',', $value ) : [] );
				echo '<div class="blogsy-gallery-preview">';
				foreach ( $ids as $img_id ) {
						echo wp_get_attachment_image( $img_id, 'thumbnail' );
				}

				echo '</div>';
				echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( is_array( $ids ) ? implode( ',', $ids ) : '' ) . '" />';
				echo '<button type="button" class="button blogsy-upload-gallery" data-target="' . esc_attr( $id ) . '">' . esc_html__( 'Select Images', 'blogsy' ) . '</button>';
				break;
		}

			echo wp_kses( $desc, [ 'p' => [ 'class' => [] ] ] );
			echo '</div>';
	}

	/**
	 * Save metabox fields.
	 *
	 * @param int $post_id Post ID.
	 * @since 1.0.0
	 */
	public function save_meta_boxes( int $post_id ): void {
		// Check autosave, revision, permissions.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		foreach ( $this->get_meta_boxes() as $metabox ) {
			if ( ! isset( $_POST[ 'blogsy_metabox_nonce_' . $metabox['id'] ] ) ) {
				continue;
			}

			if ( ! wp_verify_nonce( $_POST[ 'blogsy_metabox_nonce_' . $metabox['id'] ], 'blogsy_save_metabox_' . $metabox['id'] ) ) {
				continue;
			}

			if ( empty( $metabox['fields'] ) ) {
				continue;
			}

			foreach ( $metabox['fields'] as $field ) {
				$field_id = $field['id'];
				$type     = empty( $field['type'] ) ? 'text' : $field['type'];
				if ( ! isset( $_POST[ $field_id ] ) ) {
					if ( 'checkbox' === $type ) {
						update_post_meta( $post_id, $field_id, '0' );
					}

					continue;
				}

				$new_value = $_POST[ $field_id ];
				if ( 'gallery' === $type || 'image_advanced' === $type ) {
					// Save as comma separated IDs.
					$new_value = is_array( $new_value ) ? implode( ',', $new_value ) : sanitize_text_field( $new_value );
				} elseif ( 'checkbox' === $type ) {
					$new_value = '1';
				} else {
					$new_value = sanitize_text_field( $new_value );
				}

				update_post_meta( $post_id, $field_id, $new_value );
			}
		}
	}

	/**
	 * Page Layout Aside Meta Box.
	 *
	 * @since 1.0.0
	 */
	public function page_layout_aside_meta_box(): array {

		$header_preset  = \Blogsy\Helper::get_option( 'header_present' );
		$header_layouts = [];
		if ( 'custom' === $header_preset && class_exists( '\Blogsy\Addons\Helper' ) ) {
			$header_layouts = \Blogsy\Addons\Helper::get_templates_list( true, 'header' );
		} else {
			$header_layouts = blogsy_get_header_layouts_prebuild( 3, true, true );
		}

		return [
			'id'       => 'blogsy_page_layout_meta_box',
			'title'    => esc_html__( 'Page Layout', 'blogsy' ),
			'pages'    => [ 'post', 'page' ],
			'context'  => 'side',
			'priority' => 'high',
			'fields'   => [

				[
					'name' => esc_html__( 'Disable Top Bar', 'blogsy' ),
					'id'   => 'blogsy_page_disable_top_bar',
					'type' => 'checkbox',
				],
				[
					'name'    => esc_html__( 'Header Layout', 'blogsy' ),
					'id'      => 'blogsy_page_header',
					'type'    => 'select',
					'options' => $header_layouts,
				],
				[
					'name'    => esc_html__( 'Page Layout', 'blogsy' ),
					'id'      => 'blogsy_page_sidebar',
					'type'    => 'select',
					'options' => [
						'0'           => esc_html__( 'Default', 'blogsy' ),
						'left'        => esc_html__( 'Left Sidebar', 'blogsy' ),
						'right'       => esc_html__( 'Right Sidebar', 'blogsy' ),
						'none'        => esc_html__( 'No Sidebar', 'blogsy' ),
						'none-narrow' => esc_html__( 'No Sidebar + Narrow Content', 'blogsy' ),
					],
				],
			],
		];
	}

	/**
	 * Disable Page Card Style Meta Box.
	 *
	 * @since 1.0.0
	 */
	public function disable_page_card_style_meta_box(): array {

		return [
			'id'       => 'blogsy_disable_page_card_style_meta_box',
			'title'    => esc_html__( 'Page Card Style', 'blogsy' ),
			'pages'    => [ 'page' ],
			'context'  => 'side',
			'priority' => 'high',
			'fields'   => [

				[
					'name' => esc_html__( 'Disable Page Card Style', 'blogsy' ),
					'id'   => 'blogsy_disable_page_card_style',
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check to disable the page card style for this page.', 'blogsy' ),
				],
				[
					'name' => esc_html__( 'Disable Page Title', 'blogsy' ),
					'id'   => 'blogsy_disable_page_title',
					'type' => 'checkbox',
					'desc' => esc_html__( 'Check to disable the page title for this page.', 'blogsy' ),
				],
			],
		];
	}

	/**
	 * Post Meta Box.
	 *
	 * @since 1.0.0
	 */
	public function post_meta_box(): array {

		return [
			'id'       => 'blogsy_post_meta_box',
			'title'    => esc_html__( 'Post Hero Section Settings', 'blogsy' ),
			'pages'    => [ 'post' ],
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => [

				[
					'name'    => esc_html__( 'Standard Post Format:', 'blogsy' ),
					'id'      => 'blogsy_title_standard_format',
					'type'    => 'heading',
					'desc'    => esc_html__( 'Settings for standard post format', 'blogsy' ),
					'require' => [ 'post_format' => 'standard' ],
				],
				[
					'name'    => esc_html__( 'Post Hero Layout', 'blogsy' ),
					'id'      => 'blogsy_single_post_layout',
					'type'    => 'select',
					'options' => blogsy_get_single_post_hero_layouts( 2, true ),
					'require' => [ 'post_format' => 'standard' ],
				],
			],
		];
	}
}
