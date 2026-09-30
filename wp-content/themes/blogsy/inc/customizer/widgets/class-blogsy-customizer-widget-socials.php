<?php
/**
 * Blogsy Customizer widgets class.
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

if ( ! class_exists( 'Blogsy_Customizer_Widget_Socials' ) ) :

	/**
	 * Blogsy Customizer widget class
	 */
	class Blogsy_Customizer_Widget_Socials extends Blogsy_Customizer_Widget_Nav {

		/**
		 * Sizes for this widget
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $sizes = [];

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( array $args = [] ) {

			$values = [
				'style'      => '',
				'size'       => '',
				'visibility' => 'all',
			];

			$args['values'] = isset( $args['values'] ) ? wp_parse_args( $args['values'], $values ) : $values;

			$args['values']['style'] = sanitize_text_field( $args['values']['style'] );
			$args['values']['size']  = sanitize_text_field( $args['values']['size'] );

			parent::__construct( $args );

			$this->name        = __( 'Social Links', 'blogsy' );
			$this->description = __( 'Links to your social media profiles.', 'blogsy' );
			$this->icon        = 'dashicons dashicons-twitter';
			$this->type        = 'socials';
			$this->styles      = $args['styles'] ?? [];
			$this->sizes       = $args['sizes'] ?? [];
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 */
		public function form(): void {

			parent::form();

			if ( ! empty( $this->styles ) ) { ?>
				<p class="blogsy-widget-socials-style">
					<label for="widget-socials-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style">
						<?php esc_html_e( 'Style', 'blogsy' ); ?>:
					</label>
					<select id="widget-socials-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style" name="widget-socials[<?php echo esc_attr( $this->number ); ?>][style]" data-option-name="style">
						<?php foreach ( (array) $this->styles as $key => $value ) { ?>
							<option
								value="<?php echo esc_attr( $key ); ?>"
								<?php selected( $key, $this->values['style'], true ); ?>>
								<?php echo esc_html( $value ); ?>
							</option>
						<?php } ?>
					</select>
				</p>
				<?php
			}

			if ( ! empty( $this->sizes ) ) {
				?>
				<p class="blogsy-widget-socials-size">
					<label for="widget-socials-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-size">
						<?php esc_html_e( 'Size', 'blogsy' ); ?>:
					</label>
					<select id="widget-socials-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-size" name="widget-socials[<?php echo esc_attr( $this->number ); ?>][size]" data-option-name="size">
						<?php foreach ( (array) $this->sizes as $key => $value ) { ?>
							<option
								value="<?php echo esc_attr( $key ); ?>"
								<?php selected( $key, $this->values['size'], true ); ?>>
								<?php echo esc_html( $value ); ?>
							</option>
						<?php } ?>
					</select>
				</p>
				<?php
			}
		}
	}
endif;
