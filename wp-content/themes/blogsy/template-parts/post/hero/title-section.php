<?php
/**
 * Template part for displaying single post Standard hero title section
 *
 * @package Blogsy
 */

use Blogsy\Helper;
use Blogsy\Icon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// We can pass $args or get meta from option panel.
$single_meta = Helper::get_option( 'single_post_meta' );

if ( ! $single_meta ) {
	$single_meta = [
		'category'      => true,
		'author-avatar' => true,
		'author-name'   => true,
		'date'          => true,
		'date-updated'  => false,
		'reading-time'  => false,
		'views'         => false,
		'comments'      => true,
	];
}

$show_category      = $args['category'] ?? in_array( 'category', $single_meta, true );
$show_author_avatar = $args['author-avatar'] ?? in_array( 'author-avatar', $single_meta, true );
$show_author_name   = $args['author-name'] ?? in_array( 'author-name', $single_meta, true );
$show_date          = $args['date'] ?? in_array( 'date', $single_meta, true );
$show_date_updated  = $args['date-updated'] ?? in_array( 'date-updated', $single_meta, true );
$show_reading_time  = $args['reading-time'] ?? in_array( 'reading-time', $single_meta, true );
$show_views         = $args['views'] ?? in_array( 'views', $single_meta, true );
$show_comments      = $args['comments'] ?? in_array( 'comments', $single_meta, true );
$show_title         = $args['title'] ?? true;
?>
<div class="single-hero-title">
	<?php if ( $show_category ) : ?>
		<div class="category">
			<?php
			$categories = get_the_category();
			foreach ( $categories as $category ) {
				echo '<a class="term-id-' . esc_attr( $category->term_id ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( $category->name ) . '"><span>' . esc_html( $category->name ) . '</span></a>';
			}
			?>
		</div>
	<?php endif; ?>

	<?php if ( $show_title ) : ?>
		<h1 class="title"><span class="title-span"><?php the_title(); ?></span></h1>
	<?php endif; ?>
	<div class="single-hero-meta">
		<?php if ( $show_author_avatar ) : ?>
			<div class="meta-1">
				<a target="_blank" class="author-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 45 ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="meta-2">
			<div class="top">
				<?php if ( $show_author_name ) : ?>
					<span class="author-by"><?php echo esc_html( Helper::get_option( 'translate_by' ) ) ?: esc_html__( 'By ', 'blogsy' ); ?></span>
					<a target="_blank" class="author-name" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="bottom">
				<?php if ( $show_date ) : ?>
					<span class="meta-item date">
						<?php echo get_the_date(); ?>
					</span>
				<?php endif; ?>

				<?php if ( $show_reading_time ) : ?>
					<span class="meta-item reading-time">
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
		</div>
		<div class="meta-3">
			<?php if ( $show_views ) : ?>
				<span class="meta-item views" title="<?php echo esc_attr( Helper::get_option( 'translate_views' ) ) ?: esc_attr__( 'Views', 'blogsy' ); ?>">
					<?php echo Icon::get_svg( 'fire', '', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="count"><?php echo esc_html( blogsy_get_post_views( get_the_ID() ) ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( $show_comments ) : ?>
				<span class="meta-item comments" title="<?php echo esc_attr( Helper::get_option( 'translate_comments' ) ) ?: esc_attr__( 'Comments', 'blogsy' ); ?>">
				<?php echo Icon::get_svg( 'comment', '', [ 'aria-hidden' => 'true' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
					comments_popup_link(
						'<span class="count">0</span>',
						'<span class="count">1</span>',
						'<span class="count">%</span>'
					);
				?>
			</span>

			<?php endif; ?>
		</div>
	</div>
</div>
