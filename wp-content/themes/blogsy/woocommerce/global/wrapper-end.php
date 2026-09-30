<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Sidebar Position for woocommerce.
if ( is_shop() ) {
	$sidebar_position = blogsy_get_sidebar_position( 'woocommerce_shop', 'none' );
} elseif ( is_product_category() || is_product_tag() ) {
	$sidebar_position = blogsy_get_sidebar_position( 'woocommerce_archive', 'none' );
} else {
	$sidebar_position = 'none';
}
?>
			</div>
			<?php if ( 'left' === $sidebar_position || 'right' === $sidebar_position ) : ?>
				<aside class="sidebar-container
				<?php
				if ( \Blogsy\Helper::get_option( 'sticky_sidebar' ) ) {
					echo 'sticky';
				}
				?>
				">
					<?php get_sidebar(); ?>
				</aside>
			<?php endif; ?>
			</div>
		</div>
	</div>
</main>
