<?php
/**
 * Template part for displaying single post Standard hero content - Layout 1
 *
 * @package Blogsy
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="single-hero-1">
		<?php if ( has_post_thumbnail() ) { ?>
		<div class="image-container
			<?php
			if ( Helper::get_option( 'single_hero_1_fit' ) ) {
				echo ' single-hero-fit';}
			?>
		">
			<?php
			$img_size = Helper::get_option( 'single_hero_1_full_img' ) ? 'full' : 'blogsy-large';
			the_post_thumbnail( $img_size, [ 'title' => get_the_title() ] );
			?>
		</div>
		<?php } ?>
	<?php get_template_part( 'template-parts/post/hero/title-section' ); ?>
</div>
