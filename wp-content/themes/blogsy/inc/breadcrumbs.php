<?php
/**
 * Breadcrumb function
 *
 * @package Blogsy
 * @since 1.0.0
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } // Exit if accessed directly
/**
 * Blogsy breadcrumb
 */
function blogsy_breadcrumb(): void {

	// If the breadcrumb is disabled OR is hidden on mobiles.
	if ( ! Helper::get_option( 'breadcrumb' ) || is_home() || is_front_page() ) {
		return;
	}

	$delimiter  = '<em class="delimiter">&#47;</em>';
	$home_text  = Helper::get_option( 'translate_home' ) ?: esc_html__( 'Home', 'blogsy' );
	$breadcrumb = [];

	$post     = get_post();
	$home_url = esc_url( home_url( '/' ) );

	// Home.
	$breadcrumb[] = [
		'url'  => $home_url,
		'name' => $home_text,
	];

	// Category.
	if ( is_category() ) {

		$category = get_query_var( 'cat' );
		$category = get_category( $category );

		if ( 0 !== $category->parent ) {

			$parent_categories = array_reverse( get_ancestors( $category->cat_ID, 'category' ) );

			foreach ( $parent_categories as $parent_category ) {
				$breadcrumb[] = [
					'url'  => get_term_link( $parent_category, 'category' ),
					'name' => get_cat_name( $parent_category ),
				];
			}
		}

		$breadcrumb[] = [
			'name' => get_cat_name( $category->cat_ID ),
		];
	} elseif ( is_day() ) { // Day.

		$breadcrumb[] = [
			'url'  => get_year_link( get_the_time( 'Y' ) ),
			'name' => get_the_time( 'Y' ),
		];

		$breadcrumb[] = [
			'url'  => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
			'name' => get_the_time( 'F' ),
		];

		$breadcrumb[] = [
			'name' => get_the_time( 'd' ),
		];
	} elseif ( is_month() ) { // Month.

		$breadcrumb[] = [
			'url'  => get_year_link( get_the_time( 'Y' ) ),
			'name' => get_the_time( 'Y' ),
		];

		$breadcrumb[] = [
			'name' => get_the_time( 'F' ),
		];
	} elseif ( is_year() ) { // Year.

		$breadcrumb[] = [
			'name' => get_the_time( 'Y' ),
		];
	} elseif ( is_tag() ) { // Tag.

		$breadcrumb[] = [
			'name' => get_the_archive_title(),
		];
	} elseif ( is_author() ) { // Author.

		$author = get_queried_object();

		$breadcrumb[] = [
			'name' => $author->display_name,
		];
	} elseif ( is_search() ) { // Search.

		$breadcrumb[] = [
			'name' => ( Helper::get_option( 'translate_search_for' ) ?: esc_html__( 'Search Results for', 'blogsy' ) ) . ' ' . get_search_query(),
		];
	} elseif ( is_404() ) { // 404.

		$breadcrumb[] = [
			'name' => Helper::get_option( 'translate_nothing_found' ) ?: esc_html__( 'Nothing Found!', 'blogsy' ),
		];
	} elseif ( is_page() ) { // Pages.

		$breadcrumb[] = [
			'name' => get_the_title(),
		];
	} elseif ( is_attachment() ) { // Attachment.

		$breadcrumb[] = [
			'name' => get_the_title(),
		];
	} elseif ( is_singular() ) { // Single Posts.

		// Single Post.
		if ( 'post' === get_post_type() ) {

			$category = get_the_category()[0];

			if ( 0 !== $category->parent ) {

				$parent_categories = array_reverse( get_ancestors( $category->cat_ID, 'category' ) );

				foreach ( $parent_categories as $parent_category ) {
					$breadcrumb[] = [
						'url'  => get_term_link( $parent_category, 'category' ),
						'name' => get_cat_name( $parent_category ),
					];
				}
			}

			$breadcrumb[] = [
				'url'  => get_term_link( $category->cat_ID, 'category' ),
				'name' => get_cat_name( $category->cat_ID ),
			];

		} else {
			// Custom Post Type.
			$archive_link = get_post_type_archive_link( get_post_type() );
			// Get the main Post type archive link.
			if ( $archive_link ) {

				$post_type = get_post_type_object( get_post_type() );

				$breadcrumb[] = [
					'url'  => $archive_link,
					'name' => $post_type->labels->singular_name,
				];
			}

			// Get custom Post Types taxonomies.
			$taxonomies = get_object_taxonomies( $post, 'objects' );

			if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					if ( $taxonomy->hierarchical ) {
						$taxonomy_name = $taxonomy->name;
						break;
					}
				}
			}

			if ( ! empty( $taxonomy_name ) ) {
				$custom_terms = get_the_terms( $post, $taxonomy_name );

				if ( ! empty( $custom_terms ) && ! is_wp_error( $custom_terms ) ) {

					foreach ( $custom_terms as $term ) {

						$breadcrumb[] = [
							'url'  => get_term_link( $term ),
							'name' => $term->name,
						];

						break;
					}
				}
			}
		}

		$breadcrumb[] = [
			'name' => get_the_title(),
		];
	} elseif ( is_archive() ) {
		$breadcrumb[] = [
			'name' => get_the_archive_title(),
		];
	}

	// Print the BreadCrumb.
	$counter            = 0;
	$item_list_elements = [];
	$breadcrumb_schema  = [
		'@context' => 'http://schema.org',
		'@type'    => 'BreadcrumbList',
		'@id'      => '#Breadcrumb',
	];
	echo '<nav class="blogsy-breadcrumb" id="breadcrumb">';
	foreach ( $breadcrumb as $item ) {

		if ( ! empty( $item['name'] ) ) {
			++$counter;

			if ( ! empty( $item['url'] ) ) {
				echo '<a href="' . esc_url( $item['url'] ) . '">' . wp_strip_all_tags( $item['name'] ) . '</a>' . $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<span class="current">' . wp_strip_all_tags( $item['name'] ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				global $wp;
				$item['url'] = esc_url( home_url( add_query_arg( [], $wp->request ) ) );
			}

			$item_list_elements[] = [
				'@type'    => 'ListItem',
				'position' => $counter,
				'item'     => [
					'name' => $item['name'],
					'@id'  => $item['url'],
				],
			];
		}
	}

	echo '</nav>';
	if ( Helper::get_option( 'breadcrumb_schema' ) ) {

			// To remove the latest current element.
			$latest_element = array_pop( $item_list_elements );

		if ( [] !== $item_list_elements && is_array( $item_list_elements ) ) {

			$breadcrumb_schema['itemListElement'] = $item_list_elements;
			echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema ) . '</script>';
		}
	}

	wp_reset_postdata();
}
