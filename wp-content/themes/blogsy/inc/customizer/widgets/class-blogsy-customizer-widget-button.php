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

if ( ! class_exists( 'Blogsy_Customizer_Widget_Button' ) ) :

	/**
	 * Blogsy Customizer widget class
	 */
	class Blogsy_Customizer_Widget_Button extends Blogsy_Customizer_Widget {

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( array $args = [] ) {

			$values = [
				'text'       => '',
				'url'        => '',
				'target'     => '_self',
				'class'      => '',
				'style'      => '',
				'visibility' => 'all',
			];

			$args['values'] = isset( $args['values'] ) ? wp_parse_args( $args['values'], $values ) : $values;

			$args['values']['text']       = wp_kses( $args['values']['text'], blogsy_get_allowed_html_tags() );
			$args['values']['url']        = esc_url_raw( $args['values']['url'] );
			$args['values']['target']     = sanitize_text_field( $args['values']['target'] );
			$args['values']['class']      = sanitize_text_field( $args['values']['class'] );
			$args['values']['style']      = sanitize_text_field( $args['values']['style'] );
			$args['values']['visibility'] = isset( $args['values']['visibility'] ) ? sanitize_text_field( $args['values']['visibility'] ) : 'hide-mobile-tablet';

			parent::__construct( $args );

			$this->name        = __( 'Button', 'blogsy' );
			$this->description = __( 'A button with custom link.', 'blogsy' );
			$this->icon        = 'dashicons dashicons-admin-links';
			$this->type        = 'button';
			$this->styles      = $args['styles'] ?? [];
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 */
		public function form(): void {
			?>
			<!-- Text -->
			<p>
				<label for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-text"><?php esc_html_e( 'Text', 'blogsy' ); ?>:</label>
				<input
					type="text"
					id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-text"
					name="widget-button[<?php echo esc_attr( $this->number ); ?>][text]"
					data-option-name="text"
					value="<?php echo esc_html( $this->values['text'] ); ?>"
					placeholder="<?php esc_attr_e( 'Button Text', 'blogsy' ); ?>"/>
			</p>

			<!-- URL -->
			<p>
				<label for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-url"><?php esc_html_e( 'URL', 'blogsy' ); ?>:</label>
				<input
					type="text"
					id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-url"
					name="widget-button[<?php echo esc_attr( $this->number ); ?>][url]"
					data-option-name="url"
					value="<?php echo esc_html( $this->values['url'] ); ?>"
					placeholder="<?php esc_attr_e( 'Button URL', 'blogsy' ); ?>" />
			</p>

			<!-- Target -->
			<p>
				<label for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target"><?php esc_html_e( 'Open link in', 'blogsy' ); ?>:</label>
				<span class="buttonset">
					<input
						class="switch-input screen-reader-text"
						type="radio"
						value="_self"
						name="widget-button[<?php echo esc_attr( $this->number ); ?>][target]"
						id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_self"
						<?php checked( '_self', $this->values['target'], true ); ?>
						data-option-name="target">
						<label
							class="switch-label"
							for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_self">
							<?php esc_html_e( 'Same Tab', 'blogsy' ); ?>
						</label>
					</input>
					<input
						class="switch-input screen-reader-text"
						type="radio"
						value="_blank"
						name="widget-button[<?php echo esc_attr( $this->number ); ?>][target]"
						id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_blank"
						<?php checked( '_blank', $this->values['target'], true ); ?>
						data-option-name="target">
						<label
							class="switch-label"
							for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_blank">
							<?php esc_html_e( 'New Tab', 'blogsy' ); ?>
						</label>
					</input>
				</span>
			</p>

			<?php if ( ! empty( $this->styles ) ) { ?>
				<!-- Styles -->
				<p class="blogsy-widget-button-style">
					<label for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style">
						<?php esc_html_e( 'Style', 'blogsy' ); ?>:
					</label>
					<select id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-style" name="widget-button[<?php echo esc_attr( $this->number ); ?>][style]" data-option-name="style">
						<?php foreach ( (array) $this->styles as $key => $value ) { ?>
							<option
								value="<?php echo esc_attr( $key ); ?>"
								<?php selected( $key, $this->values['style'], true ); ?>>
								<?php echo esc_html( $value ); ?>
							</option>
						<?php } ?>
					</select>
				</p>
			<?php } ?>

			<!-- Class -->
			<p>
				<label for="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-class"><?php esc_html_e( 'Additional class', 'blogsy' ); ?>:</label>
				<input
					type="text"
					id="widget-button-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-class"
					name="widget-button[<?php echo esc_attr( $this->number ); ?>][class]"
					data-option-name="class"
					value="<?php echo esc_html( $this->values['class'] ); ?>" />
			</p>
			<?php
		}
	}
endif;
