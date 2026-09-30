<?php
/**
 * The footer for our theme
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- #site-inner -->
</div><!-- #site -->

<?php get_template_part( 'template-parts/footer/back-to-top' ); ?>
<?php
if ( \Blogsy\Helper::get_option( 'cursor_effect' ) && empty( $_REQUEST['elementor-preview'] ) ) {
	echo '<div class="blogsy-mouse-cursor outer"></div><div class="blogsy-mouse-cursor inner"></div>';
}
?>
<?php wp_footer(); ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-X7PK553M7J"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-X7PK553M7J');
</script>
</body>
</html>
