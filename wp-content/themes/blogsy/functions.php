<?php
/**
 * Theme functions and definitions for Blogsy.
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Shorthand constants for theme */
define( 'BLOGSY_THEME_URI', get_template_directory_uri() );
define( 'BLOGSY_THEME_DIR', get_template_directory() );


/* Theme Setup */
require_once BLOGSY_THEME_DIR . '/inc/theme-setup.php';

// Initialize theme.
\Blogsy\Theme::instance()->init();
