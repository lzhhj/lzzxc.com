<?php
/**
 * Blogsy Customizer info control class.
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

if ( ! class_exists( 'Blogsy_Customizer_Control_Info' ) ) :
	/**
	 * Blogsy Customizer info control class.
	 */
	class Blogsy_Customizer_Control_Info extends Blogsy_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'blogsy-info';

		/**
		 * Custom URL.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public $url = '';

		/**
		 * Link target.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public $target = '_blank';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {

			// Script debug.
			$blogsy_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Control type.
			$blogsy_type = str_replace( 'blogsy-', '', $this->type );

			/**
			 * Enqueue control stylesheet
			 */
			wp_enqueue_style(
				'blogsy-' . $blogsy_type . '-control-style',
				BLOGSY_THEME_URI . '/inc/customizer/controls/' . $blogsy_type . '/' . $blogsy_type . $blogsy_suffix . '.css',
				false,
				BLOGSY_THEME_VERSION,
				'all'
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['url']    = $this->url;
			$this->json['target'] = $this->target;
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template() {
			?>
			<div class="blogsy-info-wrapper blogsy-control-wrapper">

				<# if ( data.label ) { #>
					<span class="blogsy-control-heading customize-control-title blogsy-field">{{{ data.label }}}</span>
				<# } #>

				<# if ( data.description ) { #>
					<div class="description customize-control-description blogsy-field blogsy-info-description">{{{ data.description }}}</div>
				<# } #>

				<a href="{{ data.url }}" class="button button-primary" target="{{ data.target }}" rel="noopener noreferrer"><?php esc_html_e( 'Learn More', 'blogsy' ); ?></a>

			</div><!-- END .blogsy-control-wrapper -->
			<?php
		}
	}
endif;
