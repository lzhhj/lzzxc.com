<?php
/**
 * WooCommerce hooks and filters.
 *
 * @package Blogsy
 * @author  Peregrine Themes
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Remove Sidebar.
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Remove breadcrumb.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Change single related products position.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
