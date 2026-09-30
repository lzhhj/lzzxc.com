<?php
/**
 * The base template for displaying theme header area.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

?>

<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php do_action( 'blogsy_before_header' ); ?>
<div class="pt-header">
	<?php do_action( 'blogsy_header_content' ); ?>
</div><!-- END .pt-header -->
<?php do_action( 'blogsy_after_header' ); ?>
