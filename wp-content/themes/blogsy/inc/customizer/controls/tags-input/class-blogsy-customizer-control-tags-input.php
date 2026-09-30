<?php
/**
 * Blogsy Customizer Control - Tags Input
 *
 * A WordPress Customizer control that allows users to add tags on-the-fly
 * by typing and pressing comma or enter key. Tags are displayed as pills
 * with remove functionality, similar to multi-select style.
 *
 * @package Blogsy
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Blogsy_Customizer_Control_Tags_Input' ) ) {
	/**
	 * Tags Input Control Class
	 *
	 * @since 1.0.0
	 */
	class Blogsy_Customizer_Control_Tags_Input extends Blogsy_Customizer_Control {

		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'blogsy-tags-input';

		/**
		 * Control subtitle
		 *
		 * @var string
		 */
		public $subtitle = '';

		/**
		 * Placeholder text
		 *
		 * @var string
		 */
		public $placeholder = '';

		/**
		 * Maximum number of tags allowed
		 *
		 * @var int
		 */
		public $max_tags = 0; // 0 means unlimited

		/**
		 * Allow duplicate tags
		 *
		 * @var bool
		 */
		public $allow_duplicates = false;


		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['subtitle']         = $this->subtitle;
			$this->json['placeholder']      = $this->placeholder;
			$this->json['max_tags']         = $this->max_tags;
			$this->json['allow_duplicates'] = $this->allow_duplicates;

			// Convert value to array if it's a string.
			$value = $this->value();
			if ( is_string( $value ) && ! empty( $value ) ) {
				$value = array_map( 'trim', explode( ',', $value ) );
			} elseif ( empty( $value ) ) {
				$value = [];
			}

			$this->json['value'] = $value;
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
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>

			<# if ( data.subtitle ) { #>
				<span class="customize-control-subtitle">{{ data.subtitle }}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{ data.description }}</span>
			<# } #>

			<div class="blogsy-tags-input-wrapper">
				<div class="blogsy-tags-container" data-max-tags="{{ data.max_tags }}" data-allow-duplicates="{{ data.allow_duplicates }}">
					<# if ( data.value && data.value.length ) { #>
						<# _.each( data.value, function( tag ) { #>
							<span class="blogsy-tag">
								<span class="blogsy-tag-text">{{ tag }}</span>
								<span class="blogsy-tag-remove" role="button" aria-label="Remove tag">&times;</span>
							</span>
						<# }); #>
					<# } #>
					<input type="text"
							class="blogsy-tags-input"
							placeholder="{{ data.placeholder }}"
							autocomplete="off" />
				</div>
				<input type="hidden"
						class="blogsy-tags-value"
						value="{{ data.value.join(',') }}"
						{{{ data.link }}} />
			</div>
			<?php
		}

		/**
		 * Render the control's content (for PHP rendering instead of JS template).
		 *
		 * Note: This method is optional if you're using content_template()
		 */
		public function render_content() {
			// Use the JS template instead.
		}
	}
}
