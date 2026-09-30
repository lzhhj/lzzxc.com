<?php
/**
 * Theme back compatibility functionality
 *
 * Migrates legacy slug-based selections to IDs for select controls in the Customizer.
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since   1.0.13
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register filters to provide runtime migration as a safety fallback.
 *
 * This ensures that any unconverted slug values are converted on-the-fly when retrieved.
 * The auto-migration should persist values, but this fallback catches edge cases.
 *
 * @since 1.0.28
 * @return void
 */
function blogsy_register_select_id_migration_filters() {
	$map = blogsy_get_select_id_migration_map();

	foreach ( $map as $setting_id => $config ) {
		add_filter(
			"theme_mod_{$setting_id}",
			function ( $value ) use ( $setting_id, $config ) {
				return blogsy_maybe_migrate_select_ids( $value, $setting_id, $config );
			},
			20
		);
	}
}
add_action( 'after_setup_theme', 'blogsy_register_select_id_migration_filters', 20 );


/**
 * Map select settings to migration rules.
 *
 * @since 1.0.28
 * @return array
 */
function blogsy_get_select_id_migration_map() {
	return [
		'blogsy_ticker_category'      => [
			'type'     => 'term',
			'taxonomy' => 'category',
		],
		'blogsy_hero_slider_category' => [
			'type'     => 'term',
			'taxonomy' => 'category',
		],
		'blogsy_hero_slider_tags'     => [
			'type'     => 'term',
			'taxonomy' => 'post_tag',
		],
		'blogsy_stories_category'     => [
			'type'     => 'term',
			'taxonomy' => 'category',
		],
		'blogsy_pyml_category'        => [
			'type'     => 'term',
			'taxonomy' => 'category',
		],
		'blogsy_pyml_tags'            => [
			'type'     => 'term',
			'taxonomy' => 'post_tag',
		],
	];
}

/**
 * Migrate legacy slug selections to IDs for select controls.
 *
 * This filter provides backward compatibility by converting slug-based values to IDs
 * during retrieval. It does NOT auto-save; the actual migration happens when users
 * interact with the customizer and save.
 *
 * @since 1.0.28
 * @param mixed  $value      Current setting value.
 * @param string $setting_id Setting ID.
 * @param array  $config     Migration config.
 * @return mixed
 */
function blogsy_maybe_migrate_select_ids( $value, $setting_id, $config ) {
	if ( empty( $value ) ) {
		return $value;
	}

	// Normalize to array for processing.
	if ( is_string( $value ) ) {
		$value_array = array_filter( array_map( 'trim', explode( ',', $value ) ) );
	} elseif ( ! is_array( $value ) ) {
		$value_array = [ $value ];
	} else {
		$value_array = $value;
	}

	// Check if all values are already numeric (already migrated).
	$all_numeric = true;
	foreach ( $value_array as $item ) {
		$item_string = is_scalar( $item ) ? (string) $item : '';
		if ( '' !== $item_string && ! ctype_digit( $item_string ) ) {
			$all_numeric = false;
			break;
		}
	}

	// If already all numeric, return as-is - no migration needed.
	if ( $all_numeric ) {
		return $value;
	}

	// Convert slug-based values to IDs.
	$converted = [];

	foreach ( $value_array as $item ) {
		$item_string = is_scalar( $item ) ? (string) $item : '';

		if ( '' !== $item_string && ctype_digit( $item_string ) ) {
			// Already numeric - use as-is.
			$converted[] = (int) $item_string;
			continue;
		}

		// Non-numeric value (slug) - attempt conversion.
		if ( 'term' === $config['type'] ) {
			// Try to find by slug first.
			$term = get_term_by( 'slug', $item_string, $config['taxonomy'] );
			if ( $term && ! is_wp_error( $term ) ) {
				$converted[] = (int) $term->term_id;
				continue;
			}

			// Fallback: try to find by name.
			$term = get_term_by( 'name', $item_string, $config['taxonomy'] );
			if ( $term && ! is_wp_error( $term ) ) {
				$converted[] = (int) $term->term_id;
				continue;
			}
		}
	}

	$converted = array_values( array_unique( array_filter( $converted ) ) );

	// Return empty array if conversion failed, otherwise return converted IDs.
	return ! empty( $converted ) ? $converted : [];
}
