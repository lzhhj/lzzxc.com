<?php
/**
 * Template part for displaying hero page content
 *
 * @package Blogsy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$args = wp_parse_args(
	$args,
	[
		'blogsy_hero_page_id' => 0,
	]
);
?>

<div id="hero-page">
	<div class="content-wrapper">
		<div class="pt-container">
			<?php
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo blogsy_template_section_render( $args['blogsy_hero_page_id'] );
			?>
		</div>
	</div>
</div>
