<?php
/**
 * Post template part
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since 1.0.0
 */

use Blogsy\Helper;
use Blogsy\Icon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_meta = Helper::get_option( 'blog_meta' );

if ( ! $blog_meta ) {
	$blog_meta = [
		'author-avatar' => true,
		'author-name'   => true,
		'category'      => true,
		'comments'      => true,
		'date'          => true,
		'reading-time'  => false,
	];
}

$show_author_avatar = $args['author-avatar'] ?? in_array( 'author-avatar', $blog_meta, true );
$show_author_name   = $args['author-name'] ?? in_array( 'author-name', $blog_meta, true );
$show_category      = $args['category'] ?? in_array( 'category', $blog_meta, true );
$show_comments      = $args['comments'] ?? in_array( 'comments', $blog_meta, true );
$show_date          = $args['date'] ?? in_array( 'date', $blog_meta, true );
$show_reading_time  = $args['reading-time'] ?? in_array( 'reading-time', $blog_meta, true );

$blogsy_image_animation = Helper::get_option( 'posts_animation' );
$categories             = get_the_category();
$post_cls               = 'post-wrapper overlay-2' . ( $blogsy_image_animation ? ' has-animation' : '' ) . '';
if ( ! empty( $categories ) ) {
	$post_cls .= ' term-id-' . $categories[0]->term_id;
}
?>
<div class="post-item" data-aos="fade-up">
	<article <?php post_class( $post_cls ); ?>>
		<a class="item-link blogsy-position-cover" aria-label="Item Link" href="<?php the_permalink(); ?>"></a>
		<div class="image-wrapper">
			<?php the_post_thumbnail( 'blogsy-small', [ 'title' => get_the_title() ] ); ?>
			<?php if ( $blogsy_image_animation ) : ?>
				<div class="image-animation">
					<?php the_post_thumbnail( 'blogsy-small', [ 'title' => get_the_title() ] ); ?>
				</div>
			<?php endif; ?>
			<?php blogsy_post_format_icon(); ?>
		</div>
		<div class="top-content-wrapper blogsy-position-top">
			<?php if ( $show_category ) : ?>
			<div class="terms-wrapper">
				<?php
				foreach ( $categories as $category ) {
					echo '<a class="term-item term-id-' . esc_attr( $category->term_id ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '"><span>' . esc_html( $category->name ) . '</span></a>';
				}
				?>
			</div>
			<?php endif; ?>
			<?php if ( $show_reading_time ) : ?>
				<span class="meta-item reading-time">
					<?php echo Icon::get_svg( 'clock', 'ui', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					$mins = blogsy_get_reading_time();
					if ( $mins > 1 ) {
						echo intval( $mins ) . ' ';
						echo esc_html( Helper::get_option( 'translate_min_read' ) ) ?: esc_html__( 'Min Read', 'blogsy' );
					} else {
						echo esc_html( Helper::get_option( 'translate_one_min_read' ) ) ?: esc_html__( '1 Min Read', 'blogsy' );
					}
					?>
				</span>
			<?php endif; ?>
		</div>
		<div class="content-wrapper blogsy-position-bottom style-0">
			<div class="content-wrapper-inner">
				<div class="meta-wrapper">
					<?php if ( $show_author_avatar || $show_author_name ) : ?>
					<div class="post-author-wrapper">
						<?php if ( $show_author_avatar ) : ?>
						<div class="author-image">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60, '', get_the_author() ); ?>
						</div>
						<?php endif; ?>
						<?php if ( $show_author_name ) : ?>
						<div class="author-wrapper">
							<div class="author-meta">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
									<?php echo esc_html( get_the_author() ); ?>
								</a>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if ( $show_date ) : ?>
					<div class="date-wrapper">
						<span class="date-on"><?php esc_html_e( 'on ', 'blogsy' ); ?></span>
						<span class="date"><?php echo get_the_date(); ?></span>
					</div>
					<?php endif; ?>
				</div>
				<h2 class="title"><span class="title-span"><a class="title-animation-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></h2>
				<?php
				$blogsy_excerpt_length = Helper::get_option( 'excerpt_length' );
				$blogsy_excerpt        = blogsy_get_the_excerpt( $blogsy_excerpt_length );
				$blogsy_feed_type      = Helper::get_option( 'post_feed_content_type' );
				$blogsy_excerpt_more   = Helper::get_option( 'excerpt_more' );
				if ( post_password_required() ) {
					esc_html_e( 'This content is password protected. To view it please go to the post page and enter the password.', 'blogsy' );
				} elseif ( '1' === $blogsy_feed_type ) {
					printf( '<p class="excerpt">%s</p>', esc_html( $blogsy_excerpt ) );
				} else {
					printf( '<div class="excerpt">%s</div>', get_the_content() );
				}
				?>
				<?php
				if ( Helper::get_option( 'blog_read_more_enable' ) ) {
					// Allow text to be filtered.
					$blogsy_read_more_text = Helper::get_option( 'blog_read_more' );
					printf( '<a href="%1$s" class="blogsy-btn button btn-white pt-mt-1"><span>%2$s</span></a>', esc_url( get_permalink() ), esc_html( $blogsy_read_more_text ) );
				}
				?>
			</div>
		</div>
	</article>
</div>
