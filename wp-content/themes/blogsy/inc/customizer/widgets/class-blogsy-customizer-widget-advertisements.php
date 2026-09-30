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

if ( ! class_exists( 'Blogsy_Customizer_Widget_Advertisements' ) ) :

	/**
	 * Blogsy Customizer widget class
	 */
	class Blogsy_Customizer_Widget_Advertisements extends Blogsy_Customizer_Widget {

		/**
		 * Display areas
		 *
		 * @var array
		 */
		public $display_areas = [];

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct( array $args = [] ) {
			$args['name']        = __( 'Advertisements', 'blogsy' );
			$args['description'] = '';
			$args['icon']        = 'dashicons dashicons-format-image';
			$args['type']        = 'advertisements';
			$values              = [
				'ad_type'      => 'banner',
				'content'      => esc_html__( 'Text widget content goes here...', 'blogsy' ),
				'image_id'     => '',
				'url'          => '',
				'target'       => '_self',
				'display_area' => [],
				'visibility'   => 'all',
			];

			$args['values'] = isset( $args['values'] ) ? wp_parse_args( $args['values'], $values ) : $values;

			$args['values']['ad_type']  = sanitize_text_field( $args['values']['ad_type'] );
			$args['values']['content']  = wp_kses( $args['values']['content'], blogsy_get_allowed_html_tags() );
			$args['values']['image_id'] = absint( $args['values']['image_id'] );

			$args['values']['url']    = esc_url_raw( $args['values']['url'] );
			$args['values']['target'] = sanitize_text_field( $args['values']['target'] );

			$args['values']['display_area'] = array_map( 'sanitize_text_field', $args['values']['display_area'] );
			$args['values']['visibility']   = isset( $args['values']['visibility'] ) ? sanitize_text_field( $args['values']['visibility'] ) : 'hide-mobile-tablet';

			parent::__construct( $args );

			$this->display_areas = $args['display_areas'] ?? [];
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 */
		public function form(): void {
			?>

			<p id="bh-ad_type-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>">
				<label for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-ad_type" for="blogsy-widget-ad_type"><?php esc_html_e( 'Ad type', 'blogsy' ); ?></label>

				<select name="widget-ad[<?php echo esc_attr( $this->number ); ?>][ad_type]"
					id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-ad_type"
					data-option-name="ad_type"
					>
					<option value="banner" <?php selected( 'banner', $this->values['ad_type'], true ); ?>>
						<?php echo esc_html__( 'Banner', 'blogsy' ); ?>
					</option>
					<option value="adsense" <?php selected( 'adsense', $this->values['ad_type'], true ); ?>>
						<?php echo esc_html__( 'AdSense', 'blogsy' ); ?>
					</option>
				</select>

			</p>

			<div class="adsense-wrapper" id="bh-adsense-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>">
				<p>
					<label for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-content"><?php esc_html_e( 'Content', 'blogsy' ); ?>:</label>
					<textarea class="widefat" id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-content" name="widget-ad[<?php echo esc_attr( $this->number ); ?>][content]" data-option-name="content" rows="4"><?php echo wp_kses( $this->values['content'], blogsy_get_allowed_html_tags() ); ?></textarea>
					<span class="description">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %1$s is opening anchor tag, %2$s is a closing anchor tag. */
								__( 'Shortcodes and basic html elements allowed.', 'blogsy' ),
								'<a href="' . esc_url( 'http://docs.peregrine-themes.com/blogsy-dynamic-strings/' ) . '" target="_blank" rel="noopener noreferrer">',
								'</a>'
							)
						);
						?>

					</span>
				</p>
			</div>

			<div class="banner-controls" id="bh-banner-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>">

				<div class="banner-wrapper">
					<p>
						<input
						type="hidden"
						id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-image_id"
						name="widget-ad[<?php echo esc_attr( $this->number ); ?>][image_id]"
						data-option-name="image_id"
						value="<?php echo esc_attr( json_encode( $this->values['image_id'] ) ); ?>" />

						<button class="button button-primary widget-media-upload <?php echo 0 == $this->values['image_id'] ? 'show' : 'hide'; ?>" data-widget-id="<?php echo esc_attr( $this->id ); ?>" data-widget-number="<?php echo esc_attr( $this->number ); ?>"><?php esc_html_e( 'Upload Image', 'blogsy' ); ?></button>

						<span class="banner-preview">
							<?php
							if ( 0 !== $this->values['image_id'] ) {
								echo wp_get_attachment_image( $this->values['image_id'], 'large' );
							}
							?>
						</span>
						<button class="button button-secondary remove-image <?php echo 0 !== $this->values['image_id'] ? 'show' : 'hide'; ?>">&times;</button>
					</p>
				</div>

				<!-- URL -->
				<p>
					<label for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-url"><?php esc_html_e( 'URL', 'blogsy' ); ?>:</label>
					<input
						type="text"
						id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-url"
						name="widget-ad[<?php echo esc_attr( $this->number ); ?>][url]"
						data-option-name="url"
						value="<?php echo esc_attr( $this->values['url'] ); ?>"
						placeholder="<?php esc_attr_e( 'Banner URL', 'blogsy' ); ?>" />
				</p>

				<!-- Target -->
				<p>
					<label for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target"><?php esc_html_e( 'Open link in', 'blogsy' ); ?>:</label>
					<span class="buttonset">
						<input
							class="switch-input screen-reader-text"
							type="radio"
							value="_self"
							name="widget-ad[<?php echo esc_attr( $this->number ); ?>][target]"
							id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_self"
							<?php checked( '_self', $this->values['target'], true ); ?>
							data-option-name="target">
							<label
								class="switch-label"
								for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_self">
								<?php esc_html_e( 'Same Tab', 'blogsy' ); ?>
							</label>
						</input>
						<input
							class="switch-input screen-reader-text"
							type="radio"
							value="_blank"
							name="widget-ad[<?php echo esc_attr( $this->number ); ?>][target]"
							id="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_blank"
							<?php checked( '_blank', $this->values['target'], true ); ?>
							data-option-name="target">
							<label
								class="switch-label"
								for="widget-ad-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>-target-_blank">
								<?php esc_html_e( 'New Tab', 'blogsy' ); ?>
							</label>
						</input>
					</span>
				</p>
			</div>

			<div id="bh-da-<?php echo esc_attr( $this->id ); ?>-<?php echo esc_attr( $this->number ); ?>" class="blogsy-checkbox-group-control blogsy-ad-display-area">
				<div class="blogsy-control-heading customize-control-title blogsy-field">
					<span><?php esc_html_e( 'Show on:', 'blogsy' ); ?> </span>
				</div>
				<?php
				foreach ( $this->display_areas as $key => $value ) {
					$is_match = in_array( $key, (array) $this->values['display_area'] );
					?>
					<p>
						<label class="blogsy-checkbox">
							<input <?php echo $is_match ? 'checked' : ''; ?>
									type="checkbox"
									data-input-type="multiple"
									data-option-name="display_area"
									name="widget-ad[<?php echo esc_attr( $this->number ); ?>][display_area]"
									value="<?php echo esc_attr( $key ); ?>">
							<span class="blogsy-label"><?php echo esc_html( $value ); ?></span>
						</label>
					</p>
				<?php } ?>

			</div>
			<?php
		}
	}
endif;
