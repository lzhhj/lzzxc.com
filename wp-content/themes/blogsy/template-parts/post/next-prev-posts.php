<?php
/**
 * Template part for displaying single post next/prev posts
 *
 * @package Blogsy
 */

// Exit if accessed directly.
use Blogsy\Helper;
use Blogsy\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( Helper::get_option( 'single_post_next_prev_posts' ) ) {
	$blogsy_previous_post   = get_previous_post();
	$blogsy_next_post       = get_next_post();
	$blogsy_image_animation = Helper::get_option( 'posts_animation' );
	$divider_style          = Helper::get_option( 'divider_style' );

	if ( $blogsy_next_post || $blogsy_previous_post ) {
		?>
		<div class="single-next-prev-posts-container">
			<div class="single-next-prev-posts card-layout-w">
				<div class="single-next-prev-posts-title">
					<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
						<div class="divider divider-1"></div>
						<div class="divider divider-2"></div>
						<h5 class="title">
							<span class="title-inner">
								<span class="title-text"><?php echo esc_html( Helper::get_option( 'single_next_prev_posts_title' ) ); ?></span>
							</span>
						</h5>
						<div class="divider divider-3"></div>
						<div class="divider divider-4"></div>
					</div>
				</div>
				<div class="next-prev-row">
					<div class="next-prev-col">
						<?php
						if ( $blogsy_previous_post ) {
							?>
							<div class="post-wrapper prev-post">
								<?php if ( has_post_thumbnail( $blogsy_previous_post->ID ) ) : ?>
								<div class="image
									<?php
									if ( $blogsy_image_animation ) {
										echo ' has-animation';}
									?>
								">
									<a href="<?php echo esc_url( get_permalink( $blogsy_previous_post->ID ) ); ?>">
										<?php
										echo get_the_post_thumbnail( $blogsy_previous_post->ID, 'thumbnail', [ 'title' => get_the_title() ] );

										if ( $blogsy_image_animation ) {
											echo '<div class="image-animation">';
											echo get_the_post_thumbnail( $blogsy_previous_post->ID, 'thumbnail', [ 'title' => get_the_title() ] );
											echo '</div>';
										}
										?>
									</a>
								</div>
								<?php endif; ?>
								<div class="content">
									<div class="next-prev-label">
										<span class="icon"><?php echo Icon::get_svg( 'left', 'ui', [ 'aria-hidden' => 'true' ] );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										<span class="text"><?php echo esc_html( Helper::get_option( 'translate_previous' ) ) ?: esc_html__( 'Previous', 'blogsy' ); ?></span>
									</div>
									<h3 class="title">
										<a class="title-animation-underline" href="<?php echo esc_url( get_permalink( $blogsy_previous_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $blogsy_previous_post->ID ) ); ?></a>
									</h3>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="next-prev-col">
						<?php
						if ( $blogsy_next_post ) {
							?>
							<div class="post-wrapper next-post">
								<?php if ( has_post_thumbnail( $blogsy_next_post->ID ) ) : ?>
								<div class="image
									<?php
									if ( $blogsy_image_animation ) {
										echo ' has-animation';}
									?>
								">
									<a href="<?php echo esc_url( get_permalink( $blogsy_next_post->ID ) ); ?>">
										<?php
										echo get_the_post_thumbnail( $blogsy_next_post->ID, 'thumbnail', [ 'title' => get_the_title() ] );

										if ( $blogsy_image_animation ) {
											echo '<div class="image-animation">';
											echo get_the_post_thumbnail( $blogsy_next_post->ID, 'thumbnail', [ 'title' => get_the_title() ] );
											echo '</div>';
										}
										?>
									</a>
								</div>
								<?php endif; ?>
								<div class="content">
									<div class="next-prev-label">
										<span class="text"><?php echo esc_html( Helper::get_option( 'translate_next' ) ) ?: esc_html__( 'Next', 'blogsy' ); ?></span>
										<span class="icon"><?php echo Icon::get_svg( 'right', 'ui', [ 'aria-hidden' => 'true' ] );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									</div>
									<h3 class="title">
										<a class="title-animation-underline" href="<?php echo esc_url( get_permalink( $blogsy_next_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $blogsy_next_post->ID ) ); ?></a>
									</h3>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
