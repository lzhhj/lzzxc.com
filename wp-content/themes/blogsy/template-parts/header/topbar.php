<?php
/**
 * The template for displaying theme top bar.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blogsy
 * @author Peregrine Themes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$blogsy_top_bar_widgets = Blogsy\Helper::get_option( 'top_bar_widgets' );

$is_empty     = false === $blogsy_top_bar_widgets || ! is_array( $blogsy_top_bar_widgets ) || [] === $blogsy_top_bar_widgets;
$align_center = Blogsy\Helper::get_option( 'top_bar_widgets_align_center' );

?>

<?php do_action( 'blogsy_before_topbar' ); ?>
<div class="blogsy-topbar <?php blogsy_top_bar_classes(); ?>">
	<div class="pt-container pt-header-container">
		<div class="pt-flex-row <?php echo $align_center ? 'center-text' : ''; ?>"><?php if ( ! $is_empty ) : ?>
			<div class="col-md flex-basis-auto start-sm"><?php do_action( 'blogsy_topbar_widgets', 'left' ); ?></div>
			<div class="col-md flex-basis-auto end-sm"><?php do_action( 'blogsy_topbar_widgets', 'right' ); ?></div>
			<?php endif; ?></div>
	</div>
</div><!-- END .blogsy-topbar -->
<?php do_action( 'blogsy_after_topbar' ); ?>
