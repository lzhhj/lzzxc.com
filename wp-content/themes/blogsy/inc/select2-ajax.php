<?php
/**
 * Handles dynamic Select2 AJAX data loading for the Customizer.
 *
 * @package Blogsy
 * @since   1.0.16
 */

namespace Blogsy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Select2_Ajax
 *
 * Registers and handles the AJAX endpoint that feeds Select2 controls
 * in the Customizer with paginated, searchable data.
 *
 * @since 1.0.16
 */
class Select2_Ajax {

	/**
	 * Items to return per AJAX page.
	 *
	 * @since 1.0.16
	 * @var int
	 */
	private const PER_PAGE = 25;

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.16
	 * @var Select2_Ajax|null
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance.
	 *
	 * @since 1.0.16
	 * @return Select2_Ajax
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor. Registers the AJAX action hook.
	 *
	 * @since 1.0.16
	 */
	private function __construct() {
		add_action( 'wp_ajax_blogsy_load_select2_data', [ $this, 'handle' ] );
	}

	/**
	 * Main AJAX handler. Validates the request, dispatches to the correct
	 * data fetcher, and sends a JSON response.
	 *
	 * @since 1.0.16
	 * @return void
	 */
	public function handle() {
		check_ajax_referer( 'blogsy_customizer_nonce', 'nonce' );

		$page             = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$search           = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$data_source      = isset( $_POST['data_source'] ) ? sanitize_text_field( $_POST['data_source'] ) : '';
		$data_source_name = isset( $_POST['data_source_name'] ) ? sanitize_text_field( $_POST['data_source_name'] ) : null;

		if ( empty( $data_source ) ) {
			wp_send_json_error( 'Invalid data source' );
		}

		[ $results, $total ] = $this->fetch_data( $data_source, $data_source_name, $page, $search );

		wp_send_json_success(
			[
				'results'    => $results,
				'pagination' => [
					'more' => ( $page * self::PER_PAGE ) < $total,
				],
			]
		);
	}

	/**
	 * Dispatch to the appropriate data fetcher based on the data source type.
	 *
	 * @since 1.0.16
	 * @param string      $data_source      Data source type (category, tags, page, post, or a CPT slug).
	 * @param string|null $data_source_name Optional taxonomy or post type name override.
	 * @param int         $page             Current page number.
	 * @param string      $search           Search keyword.
	 * @return array { 0: array $results, 1: int $total }
	 */
	private function fetch_data( $data_source, $data_source_name, $page, $search ) {
		switch ( $data_source ) {
			case 'category':
				return $this->fetch_terms(
					$data_source_name ?: 'category',
					$page,
					$search
				);

			case 'tags':
				return $this->fetch_terms( 'post_tag', $page, $search );

			case 'page':
			case 'post':
				return $this->fetch_posts( $data_source, $page, $search );
			case 'post_types':
			case 'post_type':
				return $this->fetch_post_types( $search );

			default:
				return $this->fetch_posts( $data_source, $page, $search, true );
		}
	}

	/**
	 * Fetch taxonomy terms for Select2.
	 *
	 * @since 1.0.16
	 * @param string $taxonomy Taxonomy slug.
	 * @param int    $page     Current page.
	 * @param string $search   Search keyword.
	 * @return array { 0: array $results, 1: int $total }
	 */
	private function fetch_terms( $taxonomy, $page, $search ) {
		$args = [
			'hide_empty' => true,
			'taxonomy'   => $taxonomy,
			'search'     => $search,
			'number'     => self::PER_PAGE,
			'offset'     => ( $page - 1 ) * self::PER_PAGE,
		];

		$terms = get_terms( $args );
		$total = (int) wp_count_terms(
			[
				'taxonomy' => $taxonomy,
				'search'   => $search,
			]
		);

		$results = [];
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$results[] = [
					'id'   => $term->term_id,
					'text' => $term->name,
				];
			}
		}

		return [ $results, $total ];
	}

	/**
	 * Fetch posts (or custom post type entries) for Select2.
	 *
	 * @since 1.0.16
	 * @param string $post_type  Post type slug.
	 * @param int    $page       Current page.
	 * @param string $search     Search keyword.
	 * @param bool   $sort_alpha Whether to sort alphabetically by title (used for CPTs).
	 * @return array { 0: array $results, 1: int $total }
	 */
	private function fetch_posts( $post_type, $page, $search, $sort_alpha = false ) {
		$args = [
			'post_type'      => $post_type,
			'posts_per_page' => self::PER_PAGE,
			'paged'          => $page,
			'post_status'    => 'publish',
			's'              => $search,
		];

		if ( $sort_alpha ) {
			$args['orderby'] = 'title';
			$args['order']   = 'ASC';
		}

		$query   = new \WP_Query( $args );
		$total   = $query->found_posts;
		$results = [];

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$results[] = [
					'id'   => $post->ID,
					'text' => $post->post_title ?: __( 'No Title', 'blogsy' ),
				];
			}
		}

		wp_reset_postdata();

		return [ $results, $total ];
	}

	/**
	 * Fetch all public, searchable post types for Select2.
	 *
	 * Post type lists are small and static, so no pagination is needed.
	 * The $search parameter filters by label/slug client-side within the result set.
	 *
	 * @since  1.0.16
	 * @param  string $search  Optional search keyword to filter results.
	 * @return array { 0: array $results, 1: int $total }
	 */
	private function fetch_post_types( $search ) {
		$post_types =
			get_post_types(
				[
					'public'              => true,
					'exclude_from_search' => false,
				],
				'objects'
			);

		$results = [];

		foreach ( $post_types as $name => $post_type ) {
			if ( 'attachment' === $name ) {
				continue; // Skip attachments.
			}
			$label = $post_type->labels->name;

			if ( $search && stripos( $label, $search ) === false && stripos( $name, $search ) === false ) {
				continue;
			}

			$results[] = [
				'id'   => $name,
				'text' => $label,
			];
		}

		return [ $results, count( $results ) ];
	}
}
