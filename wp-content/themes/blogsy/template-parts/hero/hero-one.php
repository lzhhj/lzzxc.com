<?php
/**
 * The template for displaying Hero One Slider.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

use Blogsy\Helper;

// Business logic is handled in inc/functions.php and passed as $args.
$blogsy_args          = $args['query_args'] ?? [];
$blogsy_hero_elements = $args['elements'] ?? [];

if ( empty( $blogsy_args ) ) {
	return;
}

$blogsy_posts = new WP_Query( $blogsy_args );

// No posts found.
if ( ! $blogsy_posts->have_posts() ) {
	return;
}

// Store the IDs of the selected posts so they can be excluded from main loop.
$slider_post_ids = wp_list_pluck( $blogsy_posts->posts, 'ID' );
set_transient( 'blogsy_hero_slider_post_ids', $slider_post_ids );

$blogsy_hero_items_html = '';

while ( $blogsy_posts->have_posts() ) :
	$blogsy_posts->the_post();

	// Post items HTML markup.
	ob_start();
	?>
	<div class="post-item swiper-slide">
		<article class="post-wrapper">
			<div class="image-wrapper" data-swiper-parallax="" data-swiper-parallax-scale="1">
				<?php the_post_thumbnail( 'blogsy-wide' ); ?>
			</div>
			<div class="content-wrapper-outer" data-swiper-parallax="5%">
				<div class="content-wrapper">
					<div class="content-wrapper-inner">
						<?php if ( ! empty( $blogsy_hero_elements['category'] ) ) { ?>
						<div class="terms-wrapper">
							<?php blogsy_entry_meta_category( ' ', false, apply_filters( 'blogsy_hero_one_category_limit', 5 ) ); ?>
						</div>
						<?php } ?>
						<h2 class="title"><a class="title-animation-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php if ( ! empty( $blogsy_hero_elements['excerpt'] ) ) { ?>
						<p class="excerpt">
							<?php
							$blogsy_excerpt_length = Helper::get_option( 'hero_slider_excerpt_length' );
							$blogsy_excerpt        = blogsy_get_the_excerpt( $blogsy_excerpt_length );
							if ( post_password_required() ) {
								esc_html_e( 'This content is password protected. To view it please go to the post page and enter the password.', 'blogsy' );
							} elseif ( $blogsy_excerpt ) {
								echo esc_html( $blogsy_excerpt );
							}
							?>
						</p>
						<?php } ?>
						<?php if ( ! empty( $blogsy_hero_elements['meta'] ) ) { ?>
						<div class="meta-wrapper">
							<?php
								// Translators: 1: Author name, 2: Post date.
								printf( wp_kses_post( _x( '%1$sOn%2$s', 'post date prefix', 'blogsy' ) ), blogsy_entry_meta_author( true ), blogsy_entry_meta_date( [], true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</article>
	</div>
	<?php
	$blogsy_hero_items_html .= ob_get_clean();
endwhile;

// Rewind the custom query so we can iterate again for thumbs.
$blogsy_posts->rewind_posts();

?>
<div class="pt-container">
	<div class="pt-row">
		<div class="pt-col-12">
			<div class="pt-hero-slider peony-slider horizontal pt-hero-type-one">
				<div class="blogsy-posts-wrapper">
					<div id="blogsy-hero-peony-slider" class="blogsy-posts-carousel-wrapper" data-settings='<?php echo esc_attr( wp_json_encode( $args['slider_settings'] ?? [] ) ); ?>'>
						<div class="swiper main-slider">
							<div class="swiper-wrapper">
								<?php echo wp_kses( $blogsy_hero_items_html, blogsy_get_allowed_html_tags() ); ?>
							</div>
						</div>
						<div class="carousel-nav-wrapper blogsy-position-center nav-style-1 blogsy-hide-mobile-tablet">
							<a href="javascript:void(0);" class="carousel-nav-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<a href="javascript:void(0);" class="carousel-nav-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-hero">
								<?php echo \Blogsy\Icon::get_svg( 'arrow-right-long', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
						<div class="carousel-pagination-wrapper type-bullets blogsy-position-bottom-center style-3 blogsy-hide-all">
							<div class="carousel-pagination"></div>
						</div>
					</div>
					<div id="blogsy-thumbs" class="thumbs-slider-wrapper" data-settings='<?php echo esc_attr( wp_json_encode( $args['thumbs_slider_settings'] ?? [] ) ); ?>' style="--bar-time: 4000ms;">
						<div class="thumbs-slider-inner">
							<div class="swiper thumbs-slider thumb-style-3">
								<div class="swiper-wrapper">
									<?php
									if ( $blogsy_posts->have_posts() ) :
										while ( $blogsy_posts->have_posts() ) :
											$blogsy_posts->the_post();
											?>
											<div class="thumb-wrapper swiper-slide">
												<div class="image-wrapper">
													<svg><rect x="0" y="0" width="100%" height="100%" rx="12" ry="12" /></svg>
													<span class="image"><?php the_post_thumbnail( 'thumbnail', [ 'title' => get_the_title() ] ); ?></span>
												</div>
											</div>
											<?php
										endwhile;
									endif;
									wp_reset_postdata();
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- END .peony-slider -->
		</div>
	</div>
</div>
