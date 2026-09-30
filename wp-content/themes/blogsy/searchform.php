<?php
/**
 * Search form template part
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="blogsy-search-form">
	<input type="text" name="s" value="" class="search-field"
			placeholder="<?php echo esc_attr( \Blogsy\Helper::get_option( 'translate_search_3dot' ) ) ?: esc_attr__( 'Search ...', 'blogsy' ); ?>"
			aria-label="Search" required>
	<button type="submit" class="submit" aria-label="Submit">
		<?php echo \Blogsy\Icon::get_svg( 'search', 'ui', [ 'aria-hidden' => 'true' ] );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'blogsy' ); ?></span>
	</button>
</form>
