<?php
/**
 * The header for our theme
 *
 * @package Blogsy
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-adsense-account" content="ca-pub-8421723631335894">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8421723631335894"
     crossorigin="anonymous"></script>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="site">
	<div id="site-inner">
		<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'blogsy' ); ?></a>
		<?php get_template_part( 'template-parts/header/site-preloader' ); ?>
		<?php get_template_part( 'template-parts/header/header' ); ?>
