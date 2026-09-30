<?php
/**
 * Template part for displaying site preloader
 *
 * @package Blogsy
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( \Blogsy\Helper::get_option( 'site_preloader' ) ) {
	?>
	<div class="site-preloader">
		<div class="preloader-inner">
			<div class="preloader-cssload cssload-one"></div>
			<div class="preloader-cssload cssload-two"></div>
			<div class="preloader-cssload cssload-three"></div>
		</div>
	</div>
	<?php
}
