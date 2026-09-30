<?php
/**
 * Template part for displaying single post Standard hero content - Layout 3
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="single-hero-3">
	<div class="pt-container">
		<div class="pt-row">
			<div class="pt-col-12">
				<?php get_template_part( 'template-parts/post/hero/title-section' ); ?>
				<?php if ( ! \Blogsy\Helper::get_option( 'single_hero_3_disable_img' ) ) : ?>
				<div class="image-container">
					<?php the_post_thumbnail( 'blogsy-wide', [ 'title' => get_the_title() ] ); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
