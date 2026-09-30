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
$blogsy_blog_layout     = Helper::get_option( 'blog_layout' );
$categories             = get_the_category();
$post_cls               = 'default-post-list-item post-wrapper card-layout';
if ( ! empty( $categories ) ) {
	$post_cls .= ' term-id-' . $categories[0]->term_id;
}
?>
<article <?php post_class( $post_cls ); ?> data-aos="fade-up">
	<div class="post-wrapper-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image-container<?php echo $blogsy_image_animation ? ' has-animation' : ''; ?>">
				<?php the_post_thumbnail( 'blogsy-small', [ 'title' => get_the_title() ] ); ?>
				<?php if ( $blogsy_image_animation ) : ?>
					<div class="image-animation">
						<?php the_post_thumbnail( 'blogsy-small', [ 'title' => get_the_title() ] ); ?>
					</div>
				<?php endif; ?>
				<a class="blogsy-position-cover" href="<?php the_permalink(); ?>"></a>
				<?php blogsy_post_format_icon(); ?>
			</div>
		<?php endif; ?>
		<div class="content-container">
			<h2 class="title"><span class="title-span"><a class="title-animation-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></h2>
			<?php if ( $show_author_avatar || $show_author_name || $show_comments || $show_reading_time ) : ?>
			<div class="meta-details">
				<?php if ( $show_author_avatar || $show_author_name ) : ?>
				<div class="author-name">
					<?php if ( $show_author_avatar ) : ?>
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), 60, '', get_the_author() ); ?>
					<?php endif; ?>
					<?php if ( $show_author_name ) : ?>
					<span class="author-by"><?php esc_html_e( 'By ', 'blogsy' ); ?></span>
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if ( $show_comments ) : ?>
				<div class="comments">
					<?php echo Icon::get_svg( 'chat', 'ui', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php comments_popup_link(); ?>
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
			<?php endif; ?>
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
				printf( '<a href="%1$s" class="blogsy-btn button"><span>%2$s</span></a>', esc_url( get_permalink() ), esc_html( $blogsy_read_more_text ) );
			}
			?>
		</div>
	</div>
	<?php if ( $show_category || $show_date ) : ?>
	<footer class="footer-wrap">
		<?php if ( $show_category ) : ?>
		<div class="terms-wrapper">
			<?php
			foreach ( $categories as $category ) {
				echo '<a class="term-item term-id-' . esc_attr( $category->term_id ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '"><span>' . esc_html( $category->name ) . '</span></a>';
			}
			?>
		</div>
		<?php endif; ?>
		<?php if ( $show_date ) : ?>
		<div class="footer-meta">
			<div class="date-wrapper">
				<?php echo Icon::get_svg( 'calendar', 'ui', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="date"><?php echo get_the_date(); ?></span>
			</div>
		</div>
		<?php endif; ?>
	</footer>
	<?php endif; ?>
</article>
