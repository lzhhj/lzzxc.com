<?php
/**
 * Display Primary Menu
 *
 * @package Blogsy
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="blogsy-header-nav-wrapper pt-header-element hover-style-2">
	<?php
	// Primary Menu.
	if ( has_nav_menu( 'primary_menu' ) ) {
		wp_nav_menu(
			[
				'theme_location' => 'primary_menu',
				'link_before'    => '<span>',
				'link_after'     => '</span>',
				'fallback_cb'    => false,
				'container'      => false,
				'menu_class'     => 'blogsy-header-nav',
			]
		);
	} else {
		wp_page_menu(
			[
				'menu_class'  => 'blogsy-header-nav',
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
<?php
if ( ! empty( $args ) ) :
	?>
	<div class="popup-search-wrapper style-<?php echo esc_attr( $args['style'] ); ?>">
		<?php get_template_part( 'template-parts/header/widgets/popup-search-content' ); ?>
	</div>
<?php endif; ?>
