<?php
/**
 * Popular search keywords template part.
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if trending keywords feature is enabled.
$trending_keywords_status = \Blogsy\Helper::get_option( 'header_trending_keywords_status' );

if ( ! $trending_keywords_status ) {
	return;
}

// Get trending keywords from customizer.
$trending_keywords = \Blogsy\Helper::get_option( 'header_trending_keywords' );

// If no keywords, don't display anything.
if ( empty( $trending_keywords ) ) {
	return;
}

// Convert comma-separated string to array.
$keywords = array_map( 'trim', explode( ',', $trending_keywords ) );

// Remove empty values.
$keywords = array_filter( $keywords );

// If no keywords after filtering, return.
if ( empty( $keywords ) ) {
	return;
}
?>
<div class="popular-search-wrap">
	<div class="popular-search">
		<div class="popular-search-title"><?php echo esc_html__( 'Trending Now:', 'blogsy' ); ?></div>
		<div class="popular-search-keywords">
			<?php foreach ( $keywords as $keyword ) : ?>
				<a href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $keyword ) ) ); ?>"
					class="popular-search-keyword"
					title="<?php echo esc_attr( $keyword ); ?>">
					<?php echo esc_html( $keyword ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
