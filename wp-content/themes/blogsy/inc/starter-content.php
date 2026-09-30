<?php
/**
 * Starter Content
 *
 * @package Blogsy
 */

namespace Blogsy;

/**
 * Starter Content Class
 *
 * @since 1.0.0
 */
class Starter_Content {

	/**
	 * Get starter content array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get(): array {
		return [
			'widgets' => [
				'sidebar-1'         => [
					'text_about',
					'search',
					'recent-posts',
					'text_business_info',
				],
				'offcanvas-sidebar' => [
					'text_about',
					'recent-posts',
					'meta',
					'text_business_info',
				],
				'blogsy-footer-1'   => [
					'text_about',
				],
				'blogsy-footer-2'   => [
					'recent-posts',
				],
				'blogsy-footer-3'   => [
					'archives' => [
						'title' => __( 'Archives', 'blogsy' ),
						'count' => true,
						'type'  => 'monthly',
					],
				],
				'blogsy-footer-4'   => [
					'text_business_info',
				],
			],
		];
	}
}
