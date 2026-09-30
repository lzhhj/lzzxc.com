<?php
/**
 * Blogsy Customizer custom typography control class.
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

if ( ! class_exists( 'Blogsy_Customizer_Control_Typography' ) ) :
	/**
	 * Blogsy Customizer custom typography control class.
	 */
	class Blogsy_Customizer_Control_Typography extends Blogsy_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'blogsy-typography';

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $display = [];

		/**
		 * Set the default typography options.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct( $manager, $id, $args = [] ) {

			$this->display = [
				'font-family'     => [],
				'font-subsets'    => [],
				'font-weight'     => [],
				'font-style'      => [],
				'text-transform'  => [],
				'letter-spacing'  => [],
				'text-decoration' => [],
				'font-size'       => [],
				'color'           => '',
				'line-height'     => [],
			];

			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {

			parent::enqueue();

			wp_localize_script(
				$this->type . '-js',
				'blogsy_typography_vars',
				[
					'fonts'   => \Blogsy\Helper::fonts()->get_fonts(),
					'default' => \Blogsy\Helper::fonts()->get_default_system_font(),
				]
			);

			// Script debug.
			$blogsy_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			/**
			 * Enqueue select2 stylesheet.
			 */
			wp_enqueue_style(
				'blogsy-select2-style',
				BLOGSY_THEME_URI . '/admin/dashboard/assets/css/select2' . $blogsy_suffix . '.css',
				false,
				BLOGSY_THEME_VERSION,
				'all'
			);

			/**
			 * Enqueue select2 script.
			 */
			wp_enqueue_script(
				'blogsy-select2-js',
				BLOGSY_THEME_URI . '/admin/dashboard/assets/js/libs/select2' . $blogsy_suffix . '.js',
				[ 'jquery' ],
				BLOGSY_THEME_VERSION,
				true
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['display'] = $this->display;

			$this->json['l10n'] = [
				'advanced'        => esc_html__( 'Advanced', 'blogsy' ),
				'font-family'     => esc_html__( 'Font Family', 'blogsy' ),
				'font-subsets'    => esc_html__( 'Languages', 'blogsy' ),
				'font-weight'     => esc_html__( 'Weight', 'blogsy' ),
				'font-size'       => esc_html__( 'Size', 'blogsy' ),
				'font-style'      => esc_html__( 'Style', 'blogsy' ),
				'text-transform'  => esc_html__( 'Transform', 'blogsy' ),
				'text-decoration' => esc_html__( 'Decoration', 'blogsy' ),
				'line-height'     => esc_html__( 'Line Height', 'blogsy' ),
				'letter-spacing'  => esc_html__( 'Letter Spacing', 'blogsy' ),
				'inherit'         => esc_html__( 'Inherit', 'blogsy' ),
				'default'         => esc_html__( 'Default System Font', 'blogsy' ),
				'color'           => esc_html__( 'Color', 'blogsy' ),
				'weights'         => [
					'inherit'   => esc_html__( 'Inherit', 'blogsy' ),
					'100'       => esc_html__( 'Thin 100', 'blogsy' ),
					'100italic' => esc_html__( 'Thin 100 Italic', 'blogsy' ),
					'200'       => esc_html__( 'Extra-Thin 200', 'blogsy' ),
					'200italic' => esc_html__( 'Extra-Thin 200 Italic', 'blogsy' ),
					'300'       => esc_html__( 'Light 300', 'blogsy' ),
					'300italic' => esc_html__( 'Light 300 Italic', 'blogsy' ),
					'400'       => esc_html__( 'Normal 400', 'blogsy' ),
					'400italic' => esc_html__( 'Normal 400 Italic', 'blogsy' ),
					'500'       => esc_html__( 'Medium 500', 'blogsy' ),
					'500italic' => esc_html__( 'Medium 500 Italic', 'blogsy' ),
					'600'       => esc_html__( 'Semi-Bold 600', 'blogsy' ),
					'600italic' => esc_html__( 'Semi-Bold 600 Italic', 'blogsy' ),
					'700'       => esc_html__( 'Bold 700', 'blogsy' ),
					'700italic' => esc_html__( 'Bold 700 Italic', 'blogsy' ),
					'800'       => esc_html__( 'Extra-Bold 800', 'blogsy' ),
					'800italic' => esc_html__( 'Extra-Bold 800 Italic', 'blogsy' ),
					'900'       => esc_html__( 'Black 900', 'blogsy' ),
					'900italic' => esc_html__( 'Black 900 Italic', 'blogsy' ),
				],
				'subsets'         => \Blogsy\Helper::fonts()->get_google_font_subsets(),
				'transforms'      => [
					'inherit'    => esc_html__( 'Inherit', 'blogsy' ),
					'uppercase'  => esc_html__( 'Uppercase', 'blogsy' ),
					'lowercase'  => esc_html__( 'Lowercase', 'blogsy' ),
					'capitalize' => esc_html__( 'Capitalize', 'blogsy' ),
					'none'       => esc_html__( 'None', 'blogsy' ),
				],
				'decorations'     => [
					'inherit'      => esc_html__( 'Inherit', 'blogsy' ),
					'underline'    => esc_html__( 'Underline', 'blogsy' ),
					'overline'     => esc_html__( 'Overline', 'blogsy' ),
					'line-through' => esc_html__( 'Line Through', 'blogsy' ),
					'none'         => esc_html__( 'None', 'blogsy' ),
				],
				'styles'          => [
					'inherit' => esc_html__( 'Inherit', 'blogsy' ),
					'normal'  => esc_html__( 'Normal', 'blogsy' ),
					'italic'  => esc_html__( 'Italic', 'blogsy' ),
					'oblique' => esc_html__( 'Oblique', 'blogsy' ),
				],
			];

			$default_units = [
				'font-size'      => [
					[
						'id'   => 'px',
						'name' => 'px',
						'min'  => 8,
						'max'  => 85,
						'step' => 1,
					],
					[
						'id'   => 'em',
						'name' => 'em',
						'min'  => 0.5,
						'max'  => 6.5,
						'step' => 0.01,
					],
					[
						'id'   => 'rem',
						'name' => 'rem',
						'min'  => 0.5,
						'max'  => 6.5,
						'step' => 0.01,
					],
				],
				'letter-spacing' => [
					[
						'id'   => 'px',
						'name' => 'px',
						'min'  => -10,
						'max'  => 10,
						'step' => 1,
					],
					[
						'id'   => 'em',
						'name' => 'em',
						'min'  => -0.5,
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'line-height'    => [
					[
						'id'   => '',
						'name' => '',
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					],
				],
			];

			$this->json['units'] = [];

			foreach ( [ 'font-size', 'letter-spacing', 'line-height' ] as $key ) {
				if ( isset( $this->display[ $key ] ) && isset( $this->display[ $key ]['unit'] ) ) {
					$this->json['units'][ $key ] = $this->display[ $key ]['unit'];
				}
			}

			$this->json['units'] = wp_parse_args( $this->json['units'], $default_units );

			$this->json['responsive'] = [
				'desktop' => [
					'title' => esc_html__( 'Desktop', 'blogsy' ),
					'icon'  => 'dashicons dashicons-desktop',
				],
				'tablet'  => [
					'title' => esc_html__( 'Tablet', 'blogsy' ),
					'icon'  => 'dashicons dashicons-tablet',
				],
				'mobile'  => [
					'title' => esc_html__( 'Mobile', 'blogsy' ),
					'icon'  => 'dashicons dashicons-smartphone',
				],
			];
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
			<div class="blogsy-typography-wrapper blogsy-popup-options blogsy-control-wrapper">

				<div class="blogsy-typography-heading">
					<# if ( data.label ) { #>
						<div class="customize-control-title">
							<span>{{{ data.label }}}</span>

							<# if ( data.description ) { #>
								<i class="blogsy-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
										<circle cx="12" cy="12" r="10"></circle>
										<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
										<line x1="12" y1="17" x2="12" y2="17"></line>
									</svg>
									<span class="blogsy-tooltip">{{{ data.description }}}</span>
								</i>
							<# } #>
					</div>
					<# } #>
				</div>

				<a href="#" class="reset-defaults">
					<span class="dashicons dashicons-image-rotate"></span>
				</a>

				<a href="#" class="popup-link">
					<span class="dashicons dashicons-edit"></span>
				</a>

				<div class="popup-content hidden">

					<# if ( 'font-family' in data.display ) { #>
						<!-- Font Family -->
						<div class="blogsy-select-wrapper blogsy-typography-font-family">
							<label for="font-family-{{ data.id }}">{{{ data.l10n['font-family'] }}}</label>
							<select data-option="font-family" id="font-family-{{ data.id }}" data-default="{{ data.default['font-family'] }}">
								<option value="{{ data.value['font-family'] }}" selected="selected">
									<# if ( 'default' === data.value['font-family'] ) { #>
										{{{ data.l10n['default'] }}}
									<# } else if ( 'inherit' === data.value['font-family'] ) { #>
										{{{ data.l10n['inherit'] }}}
									<# } else { #>
										{{{ data.value['font-family'] }}}
									<# } #>
								</option>
							</select>
						</div>
					<# } #>

					<# if ( 'font-subsets' in data.display ) { #>
						<!-- Font subsets -->
						<div class="blogsy-select-wrapper blogsy-typography-font-subsets">
							<label for="font-subsets-{{ data.id }}">{{{ data.l10n['font-subsets'] }}}</label>
							<select data-option="font-subsets" id="font-subsets-{{ data.id }}" multiple="multiple">
								<# _.each( data.value['font-subsets'], function( subsets ){ #>
									<option value="{{ subsets }}" selected="selected">{{{ data.l10n['subsets'][ subsets ] }}}</option>
								<# }); #>
							</select>
						</div>
					<# } #>

					<# if ( 'font-size' in data.display ) { #>
						<!-- Font Size -->
						<div class="blogsy-range-wrapper blogsy-typography-font-size blogsy-control-responsive" data-option-id="font-size">
							<label for="font-size-{{ data.id }}">
								<span>{{{ data.l10n['font-size'] }}}</span>
								<?php $this->responsive_devices(); ?>
							</label>

							<div class="blogsy-control-wrap" data-unit="{{ data.value['font-size-unit'] }}">
								<# _.each( data.responsive, function( settings, device ){ #>

									<div class="{{ device }} control-responsive">

										<input
											type="range"
											{{{ data.inputAttrs }}}
											value="{{ data.value[ 'font-size-' + device ] }}"
											min="{{ data.units['font-size']['min'] }}"
											max="{{ data.units['font-size']['max'] }}"
											step="{{ data.units['font-size']['step'] }}"
											data-device="{{ device }}" />

											<span
												class="blogsy-reset-range"
												data-reset_value="{{ data.default[ 'font-size-' + device ] }}"
												data-reset_unit="{{ data.default[ 'font-size-unit'] }}">
												<span class="dashicons dashicons-image-rotate"></span>
											</span>

										<input
											type="number"
											{{{ data.inputAttrs }}}
											class="blogsy-range-input"
											data-option="font-size-{{ device }}"
											value="{{ data.value[ 'font-size-' + device ] }}" />
									</div>

								<# } ); #>
							</div><!-- .blogsy-control-wrap -->
						</div><!-- .blogsy-range-wrapper -->
					<# } #>

					<# if( 'color' in data.display ) { #>
						<div class="blogsy-color-wrapper blogsy-control-wrapper">
							<label for="color-{{ data.id }}">{{{ data.l10n['color'] }}}</label>
							<div>
								<input id="color-{{ data.id }}" class="blogsy-font-color-control" type="text" data-option="color" value="{{ data.value['color'] }}" data-show-opacity="false" data-default-color="{{ data.default['color'] }}" />
							</div>

						</div><!-- END .blogsy-toggle-wrapper -->
					<# } #>

					<# if ( 'font-weight' in data.display ) { #>
						<!-- Font Weight -->
						<div class="blogsy-select-wrapper blogsy-typography-font-weight">
							<label for="font-weight-{{ data.id }}">{{{ data.l10n['font-weight'] }}}</label>
							<select data-option="font-weight" id="font-weight-{{ data.id }}" data-default="{{ data.default['font-weight'] }}">
								<option value="{{ data.value['font-weight'] }}" selected="selected">{{{ data.l10n.weights[ data.value['font-weight'] ] }}}</option>
							</select>
						</div>
					<# } #>

					<# if ( 'font-style' in data.display ) { #>
						<!-- Font Style -->
						<div class="blogsy-select-wrapper blogsy-typography-font-style">
							<label for="font-style-{{ data.id }}">{{{ data.l10n['font-style'] }}}</label>
							<select data-option="font-style" id="font-style-{{ data.id }}" data-default="{{ data.default['font-style'] }}">
								<# _.each( data.l10n['styles'], function( value, key ){ #>
									<option value="{{ key }}" <# if ( key === data.value['font-style'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
								<# }); #>
							</select>
						</div>
					<# } #>

					<# if ( 'text-transform' in data.display ) { #>
						<!-- Text Transform -->
						<div class="blogsy-select-wrapper blogsy-typography-text-transform">
							<label for="text-transform-{{ data.id }}">{{{ data.l10n['text-transform'] }}}</label>
							<select data-option="text-transform" id="text-transform-{{ data.id }}" data-default="{{ data.default['text-transform'] }}">
								<# _.each( data.l10n['transforms'], function( value, key ){ #>
									<option value="{{ key }}" <# if ( key === data.value['text-transform'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
								<# }); #>
							</select>
						</div>
					<# } #>

					<# if ( 'text-decoration' in data.display ) { #>
						<!-- Text Transform -->
						<div class="blogsy-select-wrapper blogsy-typography-text-decoration">
							<label for="text-decoration-{{ data.id }}">{{{ data.l10n['text-decoration'] }}}</label>
							<select data-option="text-decoration" id="text-decoration-{{ data.id }}" data-default="{{ data.default['text-decoration'] }}">
								<# _.each( data.l10n['decorations'], function( value, key ){ #>
									<option value="{{ key }}" <# if ( key === data.value['text-decoration'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
								<# }); #>
							</select>
						</div>
					<# } #>

					<# if ( 'line-height' in data.display ) { #>
						<!-- Line Height -->
						<div class="blogsy-range-wrapper blogsy-typography-line-height blogsy-control-responsive" data-option-id="line-height">
							<label for="line-height-{{ data.id }}">
								<span>{{{ data.l10n['line-height'] }}}</span>
								<?php $this->responsive_devices(); ?>
							</label>

							<div class="blogsy-control-wrap" data-unit="{{ data.value['line-height-unit'] }}">
								<# _.each( data.responsive, function( settings, device ){ #>

									<div class="{{ device }} control-responsive">

										<input
											type="range"
											{{{ data.inputAttrs }}}
											value="{{ data.value[ 'line-height-' + device ] }}"
											min="{{ data.units['line-height']['min'] }}"
											max="{{ data.units['line-height']['max'] }}"
											step="{{ data.units['line-height']['step'] }}"
											data-device="{{ device }}" />

											<span
												class="blogsy-reset-range"
												data-reset_value="{{ data.default[ 'line-height-' + device ] }}"
												data-reset_unit="{{ data.default[ 'line-height-unit'] }}">
												<span class="dashicons dashicons-image-rotate"></span>
											</span>

										<input
											type="number"
											{{{ data.inputAttrs }}}
											class="blogsy-range-input"
											data-option="line-height-{{ device }}"
											value="{{ data.value[ 'line-height-' + device ] }}" />
									</div>

								<# } ); #>
							</div><!-- .blogsy-control-wrap -->
						</div><!-- .blogsy-range-wrapper -->
					<# } #>

					<# if ( 'letter-spacing' in data.display ) { #>
						<!-- Letter Spacing -->
						<div class="blogsy-range-wrapper blogsy-typography-letter-spacing blogsy-control-responsive" data-option-id="letter-spacing">
							<label for="letter-spacing-{{ data.id }}">
								<span>{{{ data.l10n['letter-spacing'] }}}</span>
							</label>

							<div class="blogsy-control-wrap" data-unit="{{ data.value['letter-spacing-unit'] }}">
								<input
									type="range"
									{{{ data.inputAttrs }}}
									value="{{ data.value[ 'letter-spacing' ] }}"
									min="{{ data.units['letter-spacing']['min'] }}"
									max="{{ data.units['letter-spacing']['max'] }}"
									step="{{ data.units['letter-spacing']['step'] }}" />
								<span
									class="blogsy-reset-range"
									data-reset_value="{{ data.default[ 'letter-spacing' ] }}"
									data-reset_unit="{{ data.default[ 'letter-spacing-unit'] }}">
									<span class="dashicons dashicons-image-rotate"></span>
								</span>
								<input
									type="number"
									{{{ data.inputAttrs }}}
									class="blogsy-range-input"
									data-option="letter-spacing"
									value="{{ data.value[ 'letter-spacing' ] }}" />
							</div><!-- .blogsy-control-wrap -->
						</div><!-- .blogsy-range-wrapper -->
					<# } #>
				</div><!-- .blogsy-typography-advanced -->

			</div><!-- .blogsy-control-wrapper -->
			<?php
		}
	}
endif;
