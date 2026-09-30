<?php
/**
 * Template part for displaying single page
 *
 * @package Blogsy
 */

get_header();
?>
<main id="main" class="main-wrapper">
	<?php
	$sidebar_position = blogsy_get_sidebar_position( 'single_page', 'none' );

	/* Start the Loop */
	while ( have_posts() ) :
		the_post();

		if ( 'elementor' === $sidebar_position ) {
			?>
			<div class="content-wrapper">
				<?php the_content(); ?>
			</div>
			<?php
		} else {
			?>
			<div class="single-page-outside"></div>
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
								<div class="single-content-inner">
									<h1 class="single-page-title <?php echo (bool) get_post_meta( get_the_ID(), 'blogsy_disable_page_title', true ) ? 'screen-reader-text' : ''; ?>"><?php the_title(); ?></h1>
									<?php the_content(); ?>
									<?php wp_link_pages(); ?>
								</div>
							</article>
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
			<?php
		}
	endwhile; // End of the loop.
	?>
</main>
<?php
get_footer();
