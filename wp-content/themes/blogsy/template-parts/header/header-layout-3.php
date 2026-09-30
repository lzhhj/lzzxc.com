<?php
/**
 * The template for displaying header layout 1.
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since   1.0.0
 */

?>

<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="pt-container pt-header-container">
	<?php blogsy_header_logo_template(); ?>

	<div class="pt-header-element pt-mobile-nav"><?php get_template_part( 'template-parts/header/mobile', 'navigation' ); ?></div>
	<?php blogsy_main_navigation_template(); ?>

	<?php do_action( 'blogsy_header_widget_location', [ 'left', 'right' ] ); ?>
</div><!-- END .pt-header-container -->
