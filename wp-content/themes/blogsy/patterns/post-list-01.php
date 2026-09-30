<?php
/**
 * Title: Post List 01
 * Slug: blogsy/post-list-01
 * Categories: blogsy
 * Description: Post list with image, title, excerpt, author, date and category.
 * Keywords: blog, post list,blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:query {"queryId":3,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"metadata":{"categories":["blogsy"],"patternName":"blogsy/post-list-01","name":"Post List"},"align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"style":{"spacing":{"blockGap":"2rem"}},"layout":{"type":"grid","columnCount":1}} -->
<!-- wp:group {"className":"aos-fade-up","style":{"spacing":{"padding":{"right":"0px","left":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group aos-fade-up" style="padding-right:0px;padding-left:0px"><!-- wp:group {"className":"block-post-list-item","style":{"border":{"radius":"16px","width":"1px"},"spacing":{"padding":{"top":"2rem","bottom":"1.75rem","left":"2rem","right":"2rem"},"blockGap":"2.5rem"},"shadow":"var:preset|shadow|subtle"},"backgroundColor":"card-bg","borderColor":"border-color","layout":{"type":"constrained"}} -->
<div class="wp-block-group block-post-list-item has-border-color has-border-color-border-color has-card-bg-background-color has-background" style="border-width:1px;border-radius:16px;padding-top:2rem;padding-right:2rem;padding-bottom:1.75rem;padding-left:2rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"},"margin":{"top":"0","bottom":"0"},"padding":{"right":"0rem","left":"0rem","top":"0rem","bottom":"0rem"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center" style="margin-top:0;margin-bottom:0;padding-top:0rem;padding-right:0rem;padding-bottom:0rem;padding-left:0rem"><!-- wp:column {"verticalAlignment":"center","width":"","className":"image-container","style":{"border":{"radius":"16px"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-column is-vertically-aligned-center image-container" style="border-radius:16px"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"auto","width":"100%","height":"300px","align":"left","style":{"border":{"radius":"16px"},"spacing":{"margin":{"right":"0","top":"0","bottom":"0","left":"0"}}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"60%","layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":3,"isLink":true,"className":"block-title-animation-underline","style":{"typography":{"fontWeight":"600","lineHeight":"1.3","fontStyle":"normal"},"spacing":{"margin":{"top":"0"}}},"textColor":"contrast"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","margin":{"top":"0","bottom":"0.75rem"}},"typography":{"fontSize":"1.4rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0.75rem;font-size:1.4rem"><!-- wp:avatar {"size":30,"style":{"border":{"radius":"50px"}}} /-->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"2px"}}}} -->
<p style="margin-top:0px;margin-bottom:0px;margin-left:2px"><?php echo esc_html_x( 'By', 'post author label', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"}}} /--></div>
<!-- /wp:group -->

<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":25,"style":{"typography":{"fontSize":"1.6rem","lineHeight":"1.5"},"spacing":{"margin":{"top":"1.4rem"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"border":{"top":{"color":"var:preset|color|border-color","style":"dashed","width":"1px"},"right":[],"bottom":[],"left":[]},"spacing":{"blockGap":"0","padding":{"top":"1.5rem","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-style:dashed;border-top-width:1px;padding-top:1.5rem;padding-bottom:0"><!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"1.4rem","fontStyle":"normal","fontWeight":"600"}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"typography":{"fontSize":"15px","lineHeight":"1.2"},"spacing":{"padding":{"right":"6px"}}},"layout":{"type":"constrained","contentSize":"16px"}} -->
<div class="wp-block-group" style="padding-right:6px;font-size:15px;line-height:1.2"><!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}}} -->
<p style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px">📆	</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"fontSize":"1.4rem","fontStyle":"normal","fontWeight":"600"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"chevron","style":{"spacing":{"margin":{"top":"3rem"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous {"label":"Previous"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"Next Page"} /-->
<!-- /wp:query-pagination -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p class="has-text-align-center"><?php esc_html_e( 'No posts found.', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
