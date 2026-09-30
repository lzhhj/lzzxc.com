<?php
/**
 * Template part for displaying single post
 *
 * @package Blogsy
 */

get_header();
?>
	<main id="main" class="main-wrapper">
		<div class="single-post-wrapper">
		<?php
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			$sidebar_position = blogsy_get_sidebar_position( 'single_post', 'none-narrow' );
			if ( 'elementor' === $sidebar_position ) {
				?>
				<div class="content-wrapper">
					<?php the_content(); ?>
				</div>
				<?php
			} else {
				?>
				<?php do_action( 'blogsy_single_top_content' ); ?>
				<?php get_template_part( 'template-parts/post/single-hero-outside' ); ?>
				<?php if ( \Blogsy\Helper::get_option( 'breadcrumb' ) ) : ?>
				<div class="pt-container">
					<div class="pt-row">
						<div class="pt-col-12 breadcrumb-wrapper <?php echo 'sidebar-' . esc_attr( $sidebar_position ); ?>">
							<?php blogsy_breadcrumb(); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="content-wrapper">
					<div class="pt-container">
						<div class="pt-row page-content-wrapper <?php echo 'sidebar-' . esc_attr( $sidebar_position ); ?>">
							<div class="content-container">
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-content card-layout-w' ); ?> >
									<?php get_template_part( 'template-parts/post/single-hero-inside' ); ?>
									<div class="single-content-inner">
										<?php
										if ( in_array( 'date-updated', (array) \Blogsy\Helper::get_option( 'single_post_meta' ), true ) ) {
											if ( get_the_date() !== get_the_modified_date() ) {
												$updated_title = \Blogsy\Helper::get_option( 'translate_updated_on' ) ?: esc_attr__( 'Updated on', 'blogsy' );
												echo '<div><span class="single-date-updated"><span class="badge"></span>' . esc_html( $updated_title ) . ' ' . get_the_modified_date() . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
										}
										?>
										<?php do_action( 'blogsy_before_single_content', 'before_post_content' ); ?>
										<?php the_content(); ?>
										<?php do_action( 'blogsy_after_single_content', 'after_post_content' ); ?>
										<?php wp_link_pages(); ?>
										<?php get_template_part( 'template-parts/post/tags' ); ?>
										<?php do_action( 'blogsy_single_post_share' ); ?>
									</div>
								</article>
								<?php get_template_part( 'template-parts/post/author-box' ); ?>
								<?php get_template_part( 'template-parts/post/next-prev-posts' ); ?>
								<?php comments_template(); ?>
							</div>
							<?php if ( 'left' === $sidebar_position || 'right' === $sidebar_position ) : ?>
								<aside class="sidebar-container
								<?php
								if ( \Blogsy\Helper::get_option( 'sticky_sidebar' ) ) {
									echo 'sticky';}
								?>
								">
									<?php get_sidebar(); ?>
								</aside>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php do_action( 'blogsy_single_bottom_content' ); ?>
				<?php
			}
		endwhile; // End of the loop.
		?>
		</div>
	</main>
<?php
get_footer();
