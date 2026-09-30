<?php
/**
 * Template name: Full width
 *
 * @package Blogsy
 * @since 1.0.0
 */

?>
<?php get_header(); ?>

<main id="main" class="main-wrapper">
	<div class="content-wrapper">
		<div class="pt-container">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();

			endwhile; // End of the loop.
			?>
		</div>
	</div>
</main><!-- #main -->

<?php get_footer(); ?>
