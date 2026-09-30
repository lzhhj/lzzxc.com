<?php
/**
 * Template for displaying PYML One.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

use Blogsy\Helper;
use Blogsy\Icon;

$query_args = $args['query_args'] ?? [];
$pyml_posts = new WP_Query( $query_args );

// No posts found; exit early.
if ( ! $pyml_posts->have_posts() ) {
	return;
}

// Store post IDs for global access.
$pyml_post_ids = wp_list_pluck( $pyml_posts->posts, 'ID' );
set_transient( 'blogsy_pyml_post_ids', $pyml_post_ids );

$animation_enabled = Helper::get_option( 'posts_animation' );
$pyml_elements     = $args['elements'];

$items_html = '';

ob_start();
while ( $pyml_posts->have_posts() ) :
	$pyml_posts->the_post();
	get_the_title();
	$categories    = get_the_category();
	$first_cat     = $categories[0] ?? null;
	$term_class    = $first_cat ? ' term-id-' . esc_attr( $first_cat->term_id ) : '';
	$animation_cls = $animation_enabled ? ' has-animation' : '';

	?>
	<div class="post-item swiper-slide">
		<article class="post-wrapper<?php echo esc_attr( $animation_cls . $term_class ); ?>">
			<a class="item-link blogsy-position-cover" aria-label="<?php esc_attr_e( 'Item Link', 'blogsy' ); ?>" href="<?php the_permalink(); ?>"></a>

			<div class="image-wrapper<?php echo esc_attr( $animation_cls ); ?>">
				<?php the_post_thumbnail( 'blogsy-small-square' ); ?>
				<?php if ( $animation_enabled ) : ?>
					<div class="image-animation">
						<?php the_post_thumbnail( 'blogsy-small-square' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="content-wrapper blogsy-position-bottom style-2">
				<div class="content-wrapper-inner">
					<?php if ( ! empty( $pyml_elements['category'] ) ) : ?>
						<div class="terms-wrapper">
							<?php blogsy_entry_meta_category( ' ', false, apply_filters( 'blogsy_pyml_one_category_limit', 5 ) ); ?>
						</div>
					<?php endif; ?>

					<h4 class="title">
						<a class="title-animation-underline" href="<?php the_permalink(); ?>">
							<?php echo esc_html( get_the_title() ); ?>
						</a>
					</h4>

					<?php if ( ! empty( $pyml_elements['meta'] ) ) : ?>
						<div class="meta-wrapper">
							<?php blogsy_entry_meta_author(); ?>
							<?php blogsy_entry_meta_date(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</article>
	</div>
	<?php
endwhile;
$items_html = ob_get_clean();

// Restore the original post data.
wp_reset_postdata();

$divider_style = Helper::get_option( 'divider_style' );
?>

<div class="blogsy-pyml blogsy-post-nexo-widget pyml-<?php echo esc_attr( $args['style'] ?? 'default' ); ?>">
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
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="blogsy-posts-container">
		<div class="blogsy-posts-wrapper layout-carousel">
			<div id="blogsy-pyml-nexo-slider" class="blogsy-posts-carousel-wrapper"
				data-settings='<?php echo esc_attr( wp_json_encode( $args['slider_settings'] ) ); ?>'>
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php echo wp_kses( $items_html, blogsy_get_allowed_html_tags() ); ?>
					</div>
				</div>
				<div class="carousel-nav-wrapper blogsy-position-top-right blogsy-hide-mobile">
					<a href="javascript:void(0);" class="carousel-nav-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-pyml">
						<?php echo Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<a href="javascript:void(0);" class="carousel-nav-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-pyml">
						<?php echo Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>
				<div class="carousel-pagination-wrapper type-fraction blogsy-position-bottom-center">
					<div class="carousel-pagination"></div>
				</div>
			</div>
		</div>
	</div>
</div>
