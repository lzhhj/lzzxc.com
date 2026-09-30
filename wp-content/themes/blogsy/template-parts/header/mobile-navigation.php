<?php
/**
 * Template part for displaying mobile navigation
 *
 * @package Blogsy
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="blogsy-offcanvas">
	<div class="offcanvas-opener-wrapper">
		<span class="offcanvas-opener" tabindex="0">
			<span class="hamburger">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</span>
	</div>
	<div class="offcanvas-wrapper position-left">
		<div class="offcanvas-container">
			<div class="offcanvas-container-inner">
				<div class="offcanvas-close" tabindex="0">
					<span class="cross-line top-left"></span>
					<span class="cross-line top-right"></span>
					<span class="cross-line bottom-left"></span>
					<span class="cross-line bottom-right"></span>
				</div>
				<div class="offcanvas-content">
					<?php blogsy_header_logo_template(); ?>
					<nav class="blogsy-header-v-nav-wrapper">
							<?php
							// Menu.
							if ( has_nav_menu( 'primary_menu' ) ) {
								wp_nav_menu(
									[
										'theme_location' => 'primary_menu',
										'link_before'    => '<span>',
										'link_after'     => '</span>',
										'fallback_cb'    => false,
										'container'      => false,
										'menu_class'     => 'blogsy-header-v-nav',
									]
								);
							} else {
								wp_page_menu(
									[
										'menu_class'  => 'blogsy-header-v-nav',
										'show_home'   => true,
										'container'   => 'ul',
										'before'      => '',
										'after'       => '',
										'link_before' => '<span>',
										'link_after'  => '</span>',
									]
								);
							}
							?>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
