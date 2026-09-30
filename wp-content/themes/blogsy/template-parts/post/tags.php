<?php
/**
 * Template part for displaying single post tags
 *
 * @package Blogsy
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! Helper::get_option( 'disable_tags' ) && get_the_tags( $post->ID ) ) {
	?>
	<div class="clear"></div>
	<div class="single-post-tags">
	<h4><?php echo esc_html( Helper::get_option( 'translate_tags' ) ) ?: esc_html__( 'Tags:', 'blogsy' ); ?></h4>
	<?php the_tags( '', '', '' ); ?>
	</div>
	<?php
}
