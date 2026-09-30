<?php
/**
 * Archive template part
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since 1.0.0
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_heading       = Helper::get_option( 'blog_heading' );
$blog_layout        = Helper::get_option( 'blog_layout' );
$blog_masonry       = Helper::get_option( 'blog_masonry' );
$blog_align_center  = Helper::get_option( 'blog_align_center' );
$blog_layout_column = Helper::get_option( 'blog_layout_column' );
$post_rw_cls        = $blog_layout;

// Setup layout classes.
if ( in_array( $blog_layout, [ 'blog-vertical', 'blog-cover' ], true ) ) {
	$post_rw_cls = sprintf(
		'column-layout-%s%s%s%s %s',
		esc_attr( $blog_layout_column ),
		$blog_masonry ? ' blog-masonry' : '',
		( 'blog-cover' === $blog_layout ) ? ' blogsy-post-nexo-widget' : '',
		( 'blog-vertical' === $blog_layout && $blog_align_center ) ? ' center' : '',
		$blog_layout
	);
}
?>
<div class="default-archive-container">
	<?php if ( ! empty( $blog_heading ) ) : ?>
		<div id="blogsy-blog-heading" class="pt-mb-2">
			<?php echo wp_kses( $blog_heading, blogsy_get_allowed_html_tags() ); ?>
		</div>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="default-archive-wrap <?php echo esc_attr( $post_rw_cls ); ?>">
			<?php
			$ads_info = blogsy_algorithm_to_push_ads_in_archive();
			$count    = 0;
			global $wp_query;

			while ( have_posts() ) :
				the_post();

				// Render ad if needed at the current post position.
				if (
					is_array( $ads_info )
					&& array_key_exists( 'ads_to_render', $ads_info )
					&& in_array( $wp_query->current_post, (array) $ads_info['random_numbers'], true )
				) {
					$ad_to_render = is_array( $ads_info['ads_to_render'] )
						? ( $ads_info['ads_to_render'][ $count ] ?? null )
						: $ads_info['ads_to_render'];

					if ( null !== $ad_to_render ) {
						echo '<article class="blogsy-banner-item">';
						blogsy_random_post_archive_advertisement_part( absint( $ad_to_render ) );
						echo '</article>';
						++$count;
					}
				}

				get_template_part( 'template-parts/post/content/content', $blog_layout );
			endwhile;
			?>
		</div>

		<?php
		// Pagination.
		if ( $wp_query->max_num_pages > 1 ) :
			$paginate_args = [
				'current'   => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
				'total'     => $wp_query->max_num_pages,
				'prev_next' => true,
			];
			?>
			<nav class="default-post-list-pagination">
				<?php echo wp_kses_post( paginate_links( $paginate_args ) ); ?>
			</nav>
		<?php endif; ?>

	<?php else : ?>
		<?php blogsy_query_not_found_msg(); ?>
	<?php endif; ?>
</div>
