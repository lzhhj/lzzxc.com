<?php
/**
 * Template for displaying the theme header search widget.
 *
 * @package Blogsy
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style = isset( $args['style'] ) ? (int) $args['style'] : 1;

if ( 4 !== $style ) :
	// For search popup styles 1, 2, or 3.
	if ( in_array( $style, [ 1, 2 ], true ) ) :
		?>
		<div class="popup-search-wrapper style-<?php echo esc_attr( $style ); ?>" role="dialog" aria-labelledby="search-title" aria-modal="true">
		<?php
	endif;
	?>

	<div class="popup-search-opener-wrapper">
		<span class="popup-search-opener" tabindex="0" role="button" aria-label="<?php echo esc_attr__( 'Open search popup', 'blogsy' ); ?>">
			<?php
			// Use theme's SVG helper safely.
			echo \Blogsy\Icon::get_svg( 'search', 'ui', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
	</div>

	<?php
	// For popup style 1 or 2 include inline popup content.
	if ( 3 !== $style ) {
		get_template_part( 'template-parts/header/widgets/popup-search-content', null, $args );
	}

	if ( in_array( $style, [ 1, 2 ], true ) ) :
		?>
		</div>
		<?php
	endif;

else :
	// For inline search form style (style 4).
	?>
	<div class="inline-search-form">
		<?php
		get_search_form();
		get_template_part( 'elementor/templates/search/popular-search', null, $args );
		?>
	</div>
	<?php
endif;
