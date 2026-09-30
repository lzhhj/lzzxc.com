<?php
/**
 * Popular search content template part.
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="popup-search">
	<div class="popup-search-container">
		<div class="popup-search-close" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Close search', 'blogsy' ); ?>">
			<span class="cross-line top-left"></span>
			<span class="cross-line top-right"></span>
			<span class="cross-line bottom-left"></span>
			<span class="cross-line bottom-right"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'blogsy' ); ?></span>
		</div>
		<div class="popup-search-content">
			<h2 id="search-title" class="screen-reader-text"><?php esc_html_e( 'Search', 'blogsy' ); ?></h2>
			<div class="popup-search-form">
				<?php get_search_form(); ?>
			</div>
			<?php get_template_part( 'template-parts/header/widgets/popular-search' ); ?>
		</div>
	</div>
</div>
