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

if ( ! class_exists( 'Blogsy_Customizer_Widget_Darkmode' ) ) :

	/**
	 * Blogsy Customizer widget class
	 */
	class Blogsy_Customizer_Widget_Darkmode extends Blogsy_Customizer_Widget {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( array $args = [] ) {

			$values = [
				'style'      => '',
				'visibility' => 'all',
			];

			$args['values'] = isset( $args['values'] ) ? wp_parse_args( $args['values'], $values ) : $values;

			$args['values']['style'] = sanitize_text_field( $args['values']['style'] );

			parent::__construct( $args );

			$this->name        = __( 'Dark mode', 'blogsy' );
			$this->description = __( 'A dark mode for your site.', 'blogsy' );
			$this->icon        = 'dashicons dashicons-lightbulb';
			$this->type        = 'darkmode';

			$this->styles = $args['styles'] ?? [];
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 */
		public function form(): void {

			if ( ! empty( $this->styles ) ) { ?>
				<p class="blogsy-widget-darkmode-style">
					<label for="widget-darkmode-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style">
						<?php esc_html_e( 'Style', 'blogsy' ); ?>:
					</label>
					<select id="widget-darkmode-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style" name="widget-darkmode[<?php echo esc_attr( $this->number ); ?>][style]" data-option-name="style">
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
		}
	}
endif;
