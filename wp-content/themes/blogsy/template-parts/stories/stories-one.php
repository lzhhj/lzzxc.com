<?php
/**
 * Template for displaying Stories One.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

use Blogsy\Helper;
use Blogsy\Icon;

$categories       = $args['categories'] ?? [];
$stories_elements = $args['elements'] ?? [];
$items_html       = '';

if ( ! empty( $categories ) && is_array( $categories ) ) {
	ob_start();

	foreach ( $categories as $cat_value ) {
		$category_query_args = [
			'cat'                 => absint( $cat_value->term_id ),
			'ignore_sticky_posts' => true,
			'fields'              => 'ids',
			'no_found_rows'       => true,
			'posts_per_page'      => absint( $args['max_inner_items'] ?? 4 ),
			'orderby'             => $args['orderby'] ?? 'date',
			'order'               => $args['order'] ?? 'DESC',
		];

		$category_query    = new WP_Query( apply_filters( 'blogsy_query_args_filter', $category_query_args ) );
		$preview_title     = '';
		$thumbnail_id      = '';
		$first_post        = $category_query->have_posts() ? $category_query->posts[0] : null;
		$category_count    = $cat_value->count > $args['max_inner_items'] ? $args['max_inner_items'] : $cat_value->count;
		$animation_enabled = Helper::get_option( 'posts_animation' );
		$animation_cls     = $animation_enabled ? ' has-animation' : '';

		if ( $first_post ) {
			$thumbnail_id  = $first_post;
			$preview_title = get_the_title( $first_post );
			setup_postdata( get_post( $first_post ) );
		}
		?>
		<div class="stories-popup__slide post-item swiper-slide" data-id="<?php echo esc_attr( $cat_value->term_id ); ?>" data-count="<?php echo esc_attr( $category_count ); ?>">
			<article class="post-wrapper<?php echo esc_attr( $animation_cls ); ?>">
				<?php if ( $first_post ) : ?>
				<a class="item-link blogsy-position-cover" aria-label="<?php esc_attr_e( 'Item Link', 'blogsy' ); ?>" href="#"></a>
				<?php endif; ?>

				<div class="image-wrapper<?php echo esc_attr( $animation_cls ); ?>">
					<?php
					if ( $thumbnail_id ) {
						echo wp_kses_post( get_the_post_thumbnail( $thumbnail_id, 'blogsy-small-square' ) );
					}
					if ( $animation_enabled && $thumbnail_id ) :
						?>
						<div class="image-animation">
							<?php echo wp_kses_post( get_the_post_thumbnail( $thumbnail_id, 'blogsy-small-square' ) ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="content-wrapper blogsy-position-bottom style-1">
					<div class="content-wrapper-inner">
						<?php if ( ! empty( $stories_elements['category'] ) ) : ?>
							<div class="terms-wrapper">
								<a href="<?php echo esc_url( get_category_link( $cat_value->term_id ) ); ?>" class="term-item term-id-<?php echo esc_attr( $cat_value->term_id ); ?> story-count" rel="category">
									<span class="label"><?php echo esc_html( $category_count ); ?></span>
									<span class="text">
									<?php
										echo esc_html(
											_n(
												'Story',
												'Stories',
												$category_count,
												'blogsy'
											)
										);
									?>
									</span>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $stories_elements['title'] ) ) : ?>
							<h4 class="title"><?php echo esc_html( $preview_title ); ?></h4>
						<?php endif; ?>

						<?php if ( ! empty( $stories_elements['meta'] ) ) : ?>
							<div class="meta-wrapper">
								<?php blogsy_entry_meta_author(); ?>
								<?php blogsy_entry_meta_date(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="stories-popup__indicators">
					<?php
					if ( $category_count ) {
						for ( $i = 0; $i < $category_count; $i++ ) {
							echo '<span class="stories-popup__indicator"></span>';
						}
					}
					?>
				</div>
			</article>
		</div>
		<?php
		if ( $first_post ) {
			wp_reset_postdata();
		}
	}

	$items_html = ob_get_clean();
}

$stories_page = get_pages(
	[
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'stories.php',
		'number'     => 1,
	]
);
$archive_link = '#';
if ( ! empty( $stories_page ) ) {
	$archive_link = get_permalink( $stories_page[0]->ID );
}

$divider_style = Helper::get_option( 'divider_style' );
?>

<div class="blogsy-stories blogsy-post-nexo-widget ambient stories-<?php echo esc_attr( $args['style'] ?? 'default' ); ?>" data-max-inner-items=<?php echo esc_attr( $args['max_inner_items'] ?? 0 ); ?>>
	<div class="pt-container">
		<div class="pt-row">
			<div class="pt-col-12 blogsy-card-items">
				<?php if ( ! empty( $args['title'] ) ) : ?>
				<div class="blogsy-section-heading pt-mb-2">
					<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
						<div class="divider divider-1"></div>
						<div class="divider divider-2"></div>
						<h4 class="title">
							<span class="title-inner">
								<span class="title-text"><?php echo esc_html( $args['title'] ); ?></span>
							</span>
						</h4>
						<div class="divider divider-3"></div>
						<div class="divider divider-4"></div>
					</div>
					<?php if ( ! is_page_template( 'stories.php' ) && $args['view_all'] ) : ?>
					<a href="<?php echo esc_url( $archive_link ); ?>" class="pt-button-text title-animation-underline">
						<span class="text"><?php echo esc_html( $args['view_all'] ); ?></span>
						<?php echo Icon::get_svg( 'arrow-right-up', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<div class="blogsy-stories-box swiper">
					<div class="swiper-wrapper pt-grid-col-<?php echo esc_attr( $args['max_category'] ?? 4 ); ?>"><?php echo wp_kses( $items_html, blogsy_get_allowed_html_tags() ); ?></div>
				</div>
				<div class="stories-popup">
					<div class="stories-popup__inner"></div>
					<div class="stories-popup__backdrop"></div>
					<div class="stories-popup__actions">
						<button class="stories-popup__button close">
							<?php echo Icon::get_svg( 'close', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
						<button class="stories-popup__button pause">
							<?php echo Icon::get_svg( 'pause', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo Icon::get_svg( 'play', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					</div>
					<div class="stories-popup__arrows">
						<div class="stories-popup__arrow swiper-button-prev"></div>
						<div class="stories-popup__arrow swiper-button-next"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
