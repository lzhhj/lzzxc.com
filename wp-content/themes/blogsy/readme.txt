=== Blogsy ===
Contributors: peregrinethemes
Tags: one-column, two-columns, right-sidebar, left-sidebar, grid-layout, footer-widgets, blog, news, e-commerce, custom-menu, custom-logo, post-formats, sticky-post, editor-style, threaded-comments, translation-ready, custom-colors, featured-images, full-width-template, rtl-language-support, theme-options, wide-blocks, block-styles, block-patterns
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 7.4
License: GNU General Public License v2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.0.20

A lightweight and highly customizable multi-purpose theme that makes it easy for anyone to create their perfect website.

== Description ==
Blogsy is a modern, high-performance WordPress theme designed for bloggers, news publishers, and content creators who demand style and flexibility. Effortlessly shape your site with built-in support for the powerful Gutenberg block editor, ready-made block patterns, and seamless Elementor integration. Choose from multiple header styles, flexible layouts (left, right, or no sidebar), and eye-catching grid or masonry blog designs to make your content shine. Enjoy dark mode, unique sidebar widget patterns, and an array of customization options to create a site that matches your vision. Fully compatible with WooCommerce for building online stores and lighting-fast for the best user experience. With Blogsy, launching a beautiful, professional website has never been easier, import demo content in just one click and start publishing today. Live preview: https://peregrine-themes.com/blogsy/#demos

== Frequently Asked Questions ==

= How to install Blogsy? =

1. Log into your WordPress Dashboard and go to Appearance » Themes and click the "Add New" button.
2. Type in "Blogsy" in the search field and press the "Enter" key on your keyboard.
3. Click the "Install" and then "Activate" button to activate Blogsy theme on your site.
4. Navigate to Appearance » Customize to access theme options.

== Copyright ==
Blogsy WordPress Theme, Copyright (c) 2025, Peregrinethemes
Blogsy is distributed under the terms of the GNU GPLs

== Changelog ==

= 1.0.20 - 29 July 2026 =
* Fixed: Stories are not clickable in popup modal.
* Fixed: Pattern issue in mobile scree.

= 1.0.19 - 30 June 2026 =
* Fixed: Fatal TypeError on the Updates (update-core.php) screen under PHP 8 when the `update_footer` filter passes a null value.
* Fixed Border issue in Blogsy patterns.

= 1.0.18 - 26 June 2026 =
* Fixed: Add RTL adjustments in rtl.css for improved layout handling.
* Fixed: Stories section, ajax request failed when using cache because nonce mismatch.

= 1.0.17 - 1 June 2026 =
* Stories issue fixed.
* Customizer settings filter updated.

= 1.0.16 - 1 June 2026 =
* Improved: Customizer select control now uses Ajax to load post/categories/tags etc.
* Hero slider thumbnail issue fixed when there is not featured images is set in posts.

= 1.0.15 - 22 May 2026 =
* Updated: WordPress 7.0 compatibility check.
* Added: Clearfix utility and update styles for blog horizontal layout.

= 1.0.14 - 15 May 2026 =
* Fixed: Remove Offcanvas from header default.
* Fixed: topbar  content z-index issue when offcanvas is open.

= 1.0.13 - 14 May 2026 =
* Migrate from slug based customizer settings to term_id to avoid issues in RTL.
* Added: New Offcanvas widget in the Customizer with visibility options and starter sidebar support.
* Updated: Dynamic styles for Offcanvas and Hero Slider compatibility, plus refined navigation markup.
* Improved: Excerpt handling for custom lengths and more consistent blog content output.

= 1.0.12 - 04 May 2026 =
* Improved: Starter content removed.
* Improved: Stories styles.
* Updated: Screenshot changed.
* Updated: The `blogsy_range_field_css` function to support additional parameters for half divide and custom values.
* Added: New header navigation background color and text color options in the customizer.
* Improved: Responsive styles for header navigation in different layouts.
* Improved: Dynamic styles for hero slider height and added support for Nexo card height adjustments.
* Updated: Icon set with new icons and improved existing ones.

= 1.0.11 - 21 March 2026 =
* Fixed: Hero slider thumbnail progress-bar issue.

= 1.0.10 - 13 March 2026 =
* Fixed: Add margin/padding classes to the blogsy stories container for improved spacing.
* Improved: News ticker JS functionality.
* Fixed: Patterns preview issue at wp.org.

= 1.0.9 - 5 February 2026 =
* Fixed: WP-6.9.1 notice when enqueue 'blogsy-woocommerce' style.
* Fixed: CSS & JS issue fixed and some other improvments.
* Improved: Single post meta display for updated date with translation support.

= 1.0.8 - 24 January 2026  =
* Fixed: Dark mode and  style issue.
* Updated: Refactor code structure for improved readability and maintainability.
* Updated: Refactor typography styles for menu links
* Updated: The CSS selectors for menu typography in both the customize preview and dynamic styles.
* Removed: The reference to the vertical navigation links to streamline the styling for the main header navigation links only.
* Fixed: Update topbar time display to refresh every second.
* Enhanced: Show smooth closing animation of search popup.

= 1.0.7 - 19 January 2026  =
* Fixed: Fatal error on wp theme preview.

= 1.0.6 - 19 January 2026  =
* Fixed: Fatal error on wp theme preview.
* Fixed: theme tag.
* Fixed: Some minor isses.

= 1.0.5 - 08 January 2026  =
* Fixed: Header search popup shrink issue.
* Improved: Pattern post meta category style.
* Updated: The JavaScript to bind typography settings for custom section headings and footer/sidebar widget titles.
* Added: Default typography options for footer/sidebar widget titles in the Customizer.
* Modified: The Customizer settings to include a new label for custom section headings and added display options.
* Adjusted: Dynamic styles to accommodate new typography settings for section and widget titles.
* Updated: Refactored HTML structure in various template parts to use the new section heading class.
* Updated: Corrected button text class in stories template for consistency.
* Updated: RTL styles to reflect changes in class names.
* Updated: Screenshot to reflect the latest design changes.

= 1.0.4 - 31 December 2025  =
* Fixed: Adjusted logo max height and height CSS rules to apply to both the main and sticky headers.
* Updated: Changed default value for 'blogsy_featured_category_style' from 'one' to 'three'.
* Updated: Removed the option for '1/6' from the featured category settings.
* Updated: Column class logic in featured category template to accommodate the new default style.
* Updated: Enhanced top bar and header display logic with new meta keys.
* Updated: Refactored header display logic and improved customizer options.
* Added: New CSS rules for customizer preview to hide additional shortcuts.

= 1.0.3 - 20 December 2025  =
* New: Added a new feature to the ticker template to include a play/pause button, enhancing user interaction with the ticker.
* Updated: The settings labels for clarity, changing "Enable Ticker News Section" to "Enable Ticker Section".
* Improved: Enhanced dynamic styles to conditionally apply background colors for card and sidebar widgets based on user settings.
* Updated: Language files to reflect changes in settings and options.
* Fixed: Card background control sanitization issue.
* Updated: Minimum WP required version to 6.6.

= 1.0.2 - 15 December 2025  =
* Fixed: Missing text domain on string in Info Items pattern.
* Fixed: Escape issue in search widget.

= 1.0.1 - 15 December 2025  =
* Improved: Performance improvements.
* Changed: Minor UI adjustments.

= 1.0.0 - 14 December 2025  =
* Initial release 🚀

== Resources ==

Simple Icons Library, https://github.com/simple-icons/simple-icons, https://simpleicons.org/
License: CC0 1.0 Universal , https://github.com/simple-icons/simple-icons?tab=CC0-1.0-1-ov-file

Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com
Copyright 2024 Fonticons, Inc., License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)

Feather Icons, https://feathericons.com/
Copyright (c) 2013-2017 Cole Bemis, MIT License, http://www.opensource.org/licenses/mit-license.php

SmoothScroll, https://github.com/gblazex/smoothscroll-for-websites
Copyright (c) 2010-2024 Balazs Galambosi, MIT License, https://github.com/gblazex/smoothscroll-for-websites?tab=License-1-ov-file

Swiper, https://github.com/nolimits4web/swiper
Copyright (c) Vladimir Kharlampidi, MIT License, https://github.com/nolimits4web/swiper?tab=MIT-1-ov-file

Select2, https://select2.org/
Copyright (c) 2012-2017 Kevin Brown, Igor Vaynberg, and Select2 contributors, MIT License, http://www.opensource.org/licenses/mit-license.php

AOS, https://michalsnik.github.io/aos/
Copyright (c) 2015 Michał Sajnóg, MIT License, https://github.com/michalsnik/aos/tree/v2?tab=License-1-ov-file

== Screenshot Images ==

= Ticker Images =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* Sources:
	https://pxhere.com/en/photo/1368697
	https://pxhere.com/en/photo/1412380

= Hero Images =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* License Url: https://stocksnap.io/license
* Sources:
	https://pxhere.com/en/photo/1368697
	https://pxhere.com/en/photo/1364216
	https://pxhere.com/en/photo/1412380

= Post Featured Image =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* Sources: https://pxhere.com/en/photo/1412380

= Author Sidebar Widget =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* Sources: https://pxhere.com/en/photo/1709248

== Patterns Images ==

= Hero with Image =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://stocksnap.io/license
* Sources: https://stocksnap.io/photo/business-man-IVZBYWKEFM

= Featured Items =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* License Url: https://stocksnap.io/license
* Sources:
	https://stocksnap.io/photo/minimal-white-BR6P37ANZL
	https://stocksnap.io/photo/isometric-island-H3INUP7VDZ
	https://stocksnap.io/photo/happy-birthday-ITDXH9PPW6
	https://stocksnap.io/photo/clock-time-SKT4GSYZMJ
	https://pxhere.com/en/photo/1611894

= Call to Action =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://stocksnap.io/license
* Sources: https://stocksnap.io/photo/plant-home-TDAU1ERCD4

= About Us 01 =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://stocksnap.io/license
* Sources: https://stocksnap.io/photo/business-man-IVZBYWKEFM

= About Us 02 =

* License: Creative Commons Zero (CC0) Public Domain
* License Url: https://pxhere.com/en/license
* License Url: https://stocksnap.io/license
* Sources:
	https://pxhere.com/en/photo/45505
	https://stocksnap.io/photo/love-hearts-FLNLZ8KDOC
	https://stocksnap.io/photo/patio-furniture-69HGCEWOIR


