<?php
/**
 * Template part for displaying footer
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since 1.0.0
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( Helper::get_option( 'back_to_top' ) ) :
	?>
	<button id="back-to-top" role="button" aria-label="Scroll to top button">
		<?php echo \Blogsy\Icon::get_svg( 'chevron-up', '', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="scroll-top-border"> <svg width="49" height="49" viewBox="0 0 49 49"> <path d="M24.5,2 a22.5,22.5 0 0,1 0,45 a22.5,22.5 0 0,1 0,-45"></path> </svg></div>
		<div class="scroll-top-progress"> <svg width="49" height="49" viewBox="0 0 49 49"> <path d="M24.5,2 a22.5,22.5 0 0,1 0,45 a22.5,22.5 0 0,1 0,-45"></path> </svg></div>
	</button>
	<?php
endif;
