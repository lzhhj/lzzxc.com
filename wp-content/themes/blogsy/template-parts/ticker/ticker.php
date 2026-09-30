<?php
/**
 * The template for displaying Ticker Slider.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Setup Ticker posts.
$blogsy_args = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => Helper::get_option( 'ticker_post_number' ), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
	'ignore_sticky_posts' => true,
];

$tax_query = [
	[
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => [ 'post-format-quote' ],
		'operator' => 'NOT IN',
	],
];

$blogsy_ticker_categories = array_filter( array_map( 'absint', (array) Helper::get_option( 'ticker_category' ) ) );


// If categories are specified
if ( ! empty( $blogsy_ticker_categories ) ) {
	$tax_query[] = [
		'taxonomy' => 'category',
		'field'    => 'term_id',
		'terms'    => $blogsy_ticker_categories,
		'operator' => 'IN',
	];
}

$blogsy_args['tax_query'] = $tax_query;

$blogsy_args = apply_filters( 'blogsy_ticker_query_args', $blogsy_args );

$blogsy_posts = new WP_Query( $blogsy_args );

// No posts found.
if ( ! $blogsy_posts->have_posts() ) {
	return;
}

$blogsy_ticker_items_html = '';

$blogsy_ticker_elements = (array) Helper::get_option( 'ticker_elements' );

$blogsy_ticker_type = Helper::get_option( 'ticker_type' );

while ( $blogsy_posts->have_posts() ) :
	$blogsy_posts->the_post();

	// Post items HTML markup.
	ob_start();
	?>
	<div class="blogsy-news-ticker-item">
		<?php if ( ( isset( $blogsy_ticker_elements['thumbnail'] ) && $blogsy_ticker_elements['thumbnail'] ) && has_post_thumbnail() ) : ?>
		<div class="item-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'thumbnail' ); ?>
			</a>
		</div><!-- END .item-thumbnail -->
		<?php endif; ?>

		<?php if ( isset( $blogsy_ticker_elements['meta'] ) && $blogsy_ticker_elements['meta'] ) : ?>
		<div class="item-date">
			<?php blogsy_entry_meta_date( [ 'tag' => false ] ); ?>
		</div>
		<?php endif; ?>

		<div class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	</div>
	<?php
	$blogsy_ticker_items_html .= ob_get_clean();
endwhile;

// Restore original Post Data.
wp_reset_postdata();

$blogsy_ticker_title = Helper::get_option( 'ticker_title' );

?>

<div class="blogsy-ticker <?php echo esc_attr( $blogsy_ticker_type ); ?>">
	<div class="pt-container">
		<div class="pt-row">
			<div class="pt-col-12">
				<div class="blogsy-card-items blogsy-news-ticker-wrapper">
					<?php if ( $blogsy_ticker_title ) : ?>
					<div class="blogsy-news-ticker-title-wrapper">
						<span class="blogsy-news-ticker-title-icon-wrapper"><span class="blogsy-news-ticker-title-pulse"></span></span>
						<span class="blogsy-news-ticker-title-text"><?php echo esc_html( $blogsy_ticker_title ); ?></span>
					</div>
					<?php endif; ?>
					<?php
						$blogsy_ticker_direction = 'left';
						$blogsy_ticker_dir       = 'ltr';
					if ( is_rtl() ) {
						$blogsy_ticker_direction = 'right';
						$blogsy_ticker_dir       = 'ltr';
					}
					?>
					<div class="blogsy-news-ticker-content-wrapper animation-marquee pause-hover">
						<div class="blogsy-news-ticker-content side-fading-both" direction="<?php echo esc_attr( $blogsy_ticker_direction ); ?>" dir="<?php echo esc_attr( $blogsy_ticker_dir ); ?>">
							<div class="blogsy-news-ticker-items">
								<?php echo wp_kses( $blogsy_ticker_items_html, blogsy_get_allowed_html_tags( 'post' ) ); ?>
							</div>
						</div>
					</div>
					<?php if ( isset( $blogsy_ticker_elements['play_pause'] ) && $blogsy_ticker_elements['play_pause'] ) : ?>
					<div class="blogsy-news-ticker-btn" tabindex="0" role="button">
						<?php
							$play  = \Blogsy\Icon::get_svg(
								'play',
								'ui',
								[
									'class' => 'pt-d-none',
								]
							);
							$pause = \Blogsy\Icon::get_svg( 'pause', 'ui' );

							echo \Blogsy\Icon::sanitize_svg( $play ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo \Blogsy\Icon::sanitize_svg( $pause ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div><!-- END .blogsy-ticker -->
