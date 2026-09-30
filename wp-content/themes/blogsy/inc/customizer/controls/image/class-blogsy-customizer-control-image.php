<?php
/**
 * Blogsy Customizer custom image control class.
 *
 * @package     Blogsy
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blogsy_Customizer_Control_Image' ) ) :
	/**
	 * Blogsy Customizer custom image control class.
	 */
	class Blogsy_Customizer_Control_Image extends Blogsy_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'blogsy-image';

		/**
		 * Media upload strings.
		 *
		 * @since  1.0.0
		 * @var    boolean
		 */
		public $strings = [];

		/**
		 * Set the default typography options.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct( $manager, $id, $args = [] ) {

			parent::__construct( $manager, $id, $args );

			$default_strings = [
				'selectOrUploadImage' => __( 'Select or Upload Image', 'blogsy' ),
				'useThisImage'        => __( 'Use this image', 'blogsy' ),
				'changeImage'         => __( 'Change Image', 'blogsy' ),
				'selectImage'         => __( 'Select Image', 'blogsy' ),
				'remove'              => __( 'Remove', 'blogsy' ),
			];

			$strings = isset( $args['strings'] ) ? $args['strings'] : [];

			$this->strings = wp_parse_args( $strings, $default_strings );
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();
			$this->json['value'] = $this->value();
			$this->json['link']  = $this->get_link();
			$this->json['l10n']  = $this->strings;
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the data JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template() {
			?>
			<div class="blogsy-control-wrapper blogsy-image-wrapper">
				<# if ( data.label ) { #>
					<label class="customize-control-title">{{{ data.label }}}</label>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<div class="blogsy-image-preview">
					<# if ( data.value ) { #>
						<img src="{{ data.value }}" alt="" />
					<# } else { #>
						<img src="" alt="" style="display:none;" />
					<# } #>
				</div>
				<div class="blogsy-image-actions attachment-media-view">
					<button type="button" class="button blogsy-upload-button <# if ( ! data.value ) { #> button-add-media <# } #>">{{{ data.value ? '<?php esc_html_e( 'Change Image', 'blogsy' ); ?>' : '<?php esc_html_e( 'Select Image', 'blogsy' ); ?>' }}}</button>
					<button type="button" class="button blogsy-remove-button" <# if ( ! data.value ) { #> style="display:none;" <# } #>><?php esc_html_e( 'Remove', 'blogsy' ); ?></button>
				</div>
			</div>
			<?php
		}
	}
endif;
?>
