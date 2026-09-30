<?php
/**
 * Template part for displaying single post author box
 *
 * @package Blogsy
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( Helper::get_option( 'single_post_author_box' ) ) {
	$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
	?>
	<div class="single-author-box-container">
		<div class="single-author-box card-layout-w">
			<div class="single-author-box-avatar">
				<a href="<?php echo esc_url( $author_url ); ?>">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
				</a>
			</div>
			<div class="single-author-box-desc">
				<span class="written-by"><?php echo esc_html( Helper::get_option( 'translate_author' ) ) ?: esc_html__( 'Author', 'blogsy' ); ?></span>
				<h4 class="author-name"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( get_the_author() ); ?></a></h4>
				<p class="author-bio"><?php the_author_meta( 'description' ); ?></p>
				<div class="author-social-links">
					<?php
					if ( class_exists( '\Blogsy\Addons\Helper' ) && method_exists( '\Blogsy\Addons\Helper', 'author_social' ) ) {
						\Blogsy\Addons\Helper::author_social( get_the_author_meta( 'ID' ) );
					}
					?>
				</div>
				<a class="button more-articles" href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( Helper::get_option( 'translate_follow_me' ) ) ?: esc_html__( 'Follow Me', 'blogsy' ); ?></a>
			</div>
		</div>
	</div>
	<?php
}
