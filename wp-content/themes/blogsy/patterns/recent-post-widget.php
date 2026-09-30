<?php
/**
 * Title: Recent Post Widget
 * Slug: blogsy/recent-post-widget
 * Categories: blogsy
 * Description: Recent Post Widget to show in sidebar.
 * Keywords: recent post, blogsy, widget
 *
 * @package Blogsy
 */

?>

<!-- wp:columns {"metadata":{"name":"Recent Posts"},"style":{"spacing":{"padding":{"right":"0px","left":"0px"},"blockGap":{"top":"0px","left":"0px"},"margin":{"top":"0px","bottom":"0px"}}}} -->
<div class="wp-block-columns" style="margin-top:0px;margin-bottom:0px;padding-right:0px;padding-left:0px"><!-- wp:column {"metadata":{"blockVisibility":{"viewport":{"tablet":false,"mobile":false}}}} -->
<div class="wp-block-column"><!-- wp:spacer -->
<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"324px"} -->
<div class="wp-block-column" style="flex-basis:324px"><!-- wp:group {"style":{"spacing":{"padding":{"left":"0px","right":"0px"},"blockGap":"1.6rem"}}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px"><!-- wp:heading {"level":4,"className":"is-style-core-style-1"} -->
<h4 class="wp-block-heading is-style-core-style-1"><?php echo esc_html_x( 'Recent Posts', 'widget title', 'blogsy' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"blockGap":"1.6rem","padding":{"right":"0px","left":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px"><!-- wp:latest-posts {"postsToShow":1,"displayAuthor":true,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageSizeSlug":"large","addLinkToFeaturedImage":true} /-->

<!-- wp:latest-posts {"postsToShow":3,"displayAuthor":true,"displayPostDate":true,"order":"asc","displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":75,"featuredImageSizeHeight":75,"addLinkToFeaturedImage":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"metadata":{"blockVisibility":{"viewport":{"tablet":false,"mobile":false}}}} -->
<div class="wp-block-column"><!-- wp:spacer -->
<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
