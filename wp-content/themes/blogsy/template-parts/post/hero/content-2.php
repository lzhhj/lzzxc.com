<?php
/**
 * Template part for displaying single post Standard hero content - Layout 2
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! \Blogsy\Helper::get_option( 'single_hero_2_disable_img' ) ) :
	?>
	<div class="single-hero-2">
		<div class="pt-container">
			<div class="pt-row">
				<div class="pt-col-12">
					<div class="image-container">
						<?php the_post_thumbnail( 'blogsy-wide', [ 'title' => get_the_title() ] ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
