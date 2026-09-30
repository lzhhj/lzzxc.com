<?php
/**
 * Template part for displaying archive title section
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
global $wp_query;
$post_count = $wp_query->found_posts;

?>

<?php if ( ! is_front_page() ) : // Start Archive Title. ?>
<div class="pt-container">
	<div class="pt-row">
		<div class="pt-col-12">
			<div class="blog-archive-title card-layout-w">
				<?php
				if ( is_category() ) {
					$archive_title = single_cat_title( '', false );
					$sub_title     = Helper::get_option( 'translate_browse_category' ) ?: esc_html__( 'Browse Category', 'blogsy' );
				} elseif ( is_tag() ) {
					$archive_title = single_tag_title( '', false );
					$sub_title     = Helper::get_option( 'translate_browse_tag' ) ?: esc_html__( 'Browse Tag', 'blogsy' );
				} elseif ( is_tax() ) {
					$archive_title = single_term_title( '', false );
					$sub_title     = Helper::get_option( 'translate_browse' ) ?: esc_html__( 'Browse', 'blogsy' );
				} elseif ( is_post_type_archive() ) {
					$archive_title = post_type_archive_title( '', false );
					$sub_title     = Helper::get_option( 'translate_browse' ) ?: esc_html__( 'Browse', 'blogsy' );
				} elseif ( is_search() ) {
					$archive_title = get_search_query();
					$sub_title     = Helper::get_option( 'translate_search_for' ) ?: esc_html__( 'Search Result for', 'blogsy' );
				} elseif ( is_author() ) {
					$archive_title = get_the_author();
					$sub_title     = Helper::get_option( 'translate_author' ) ?: esc_html__( 'Author', 'blogsy' );
				} elseif ( is_home() ) {
					$archive_title = Helper::get_option( 'translate_our_articles' ) ?: esc_html__( 'Our Articles', 'blogsy' );
					$sub_title     = Helper::get_option( 'translate_browse' ) ?: esc_html__( 'Browse', 'blogsy' );
				} else {
					$archive_title = get_the_archive_title();
					$sub_title     = Helper::get_option( 'translate_browse' ) ?: esc_html__( 'Browse', 'blogsy' );
				}


				if ( is_author() ) {
					?>
					<div class="avatar-wrap">
						<div class="avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
						</div>
						<div class="title-wrap">
							<span class="sub-title"><?php echo esc_html( $sub_title ); ?></span>
							<h1 class="title"><?php echo esc_html( $archive_title ); ?></h1>
						</div>
					</div>
					<div class="description"><p><?php the_author_meta( 'description' ); ?></p></div>
					<div class="author-social-links">
						<?php
						if ( class_exists( '\Blogsy\Addons\Helper' ) && method_exists( '\Blogsy\Addons\Helper', 'author_social' ) ) {
							\Blogsy\Addons\Helper::author_social( get_the_author_meta( 'ID' ) );
						}
						?>
					</div>
							<?php
				} elseif ( is_search() ) {
					?>
					<div class="title-wrap">
						<span class="sub-title"><?php echo esc_html( $sub_title ); ?></span>
						<h1 class="title"><?php echo wp_kses_post( $archive_title ); ?></h1>
					</div>
					<?php get_search_form(); ?>
					<?php
				} else {
					$archive_title_cls = 'title';
					if ( is_category() ) {
						$archive_title_cls .= ' term-id-' . get_queried_object_id();
					}
					?>
					<div class="title-wrap">
						<span class="sub-title"><?php echo esc_html( $sub_title ); ?></span>
						<h1 class="<?php echo esc_attr( $archive_title_cls ); ?>"><?php echo wp_kses_post( $archive_title ); ?></h1>
					</div>
							<?php the_archive_description( '<div class="description">', '</div>' ); ?>
							<?php
				}
				?>
				<div class="post-count"><span>
					<?php
					if ( $post_count > 1 ) {
						$post_count_title = Helper::get_option( 'translate_articles' ) ?: esc_html__( 'Articles', 'blogsy' );
					} else {
						$post_count_title = Helper::get_option( 'translate_article' ) ?: esc_html__( 'Article', 'blogsy' );
					}
					echo intval( $post_count ) . ' ' . esc_html( $post_count_title );
					?>
				</span></div>
			</div>
		</div>
	</div>
</div>
<?php endif; // End Archive Title. ?>
