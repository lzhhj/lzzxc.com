<?php
/**
 * Title: Post List 03
 * Slug: blogsy/post-list-03
 * Categories: blogsy
 * Description: Post list with image, title, author, date and category.
 * Keywords: blog, post list,blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"categories":["blogsy"],"patternName":"blogsy/post-list-03","name":"Post List 03"},"align":"full","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"1.8rem"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;margin-bottom:0px;padding-top:2rem;padding-bottom:2rem"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0","padding":{"right":"0","left":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide" style="padding-right:0;padding-left:0"><!-- wp:heading {"level":4,"className":"is-style-core-style-1","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} -->
<h4 class="wp-block-heading is-style-core-style-1" style="margin-top:0px;margin-bottom:0px"><?php echo esc_html_x( 'Latest Articles', 'Pattern heading', 'blogsy' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill is-btn-arrow"} -->
<div class="wp-block-button is-style-fill is-btn-arrow"><a class="wp-block-button__link wp-element-button"><?php echo esc_html_x( 'See all', 'Button text', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false,"taxQuery":{"category":[]},"parents":[],"format":[]},"align":"wide", "layout":{"type":"constrained"}} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"align":"wide","className":"blogsy-featured-posts","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|70","right":"0","left":"0","top":"0"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"block-post-list-item aos-fade-up","style":{"shadow":"var:preset|shadow|subtle","border":{"radius":"12px","width":"1px"},"spacing":{"padding":{"top":"1.6rem","bottom":"1.6rem","left":"1.6rem","right":"1.6rem"}}},"backgroundColor":"card-bg","borderColor":"border-color","layout":{"type":"default"}} -->
<div class="wp-block-group block-post-list-item aos-fade-up has-border-color has-border-color-border-color has-card-bg-background-color has-background" style="border-width:1px;border-radius:12px;padding-top:1.6rem;padding-right:1.6rem;padding-bottom:1.6rem;padding-left:1.6rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:group {"className":"image-container","style":{"spacing":{"margin":{"bottom":"2.5rem"}},"border":{"radius":{"topLeft":"16px","topRight":"16px","bottomLeft":"16px","bottomRight":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group image-container" style="border-top-left-radius:16px;border-top-right-radius:16px;border-bottom-left-radius:16px;border-bottom-right-radius:16px;margin-bottom:2.5rem"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"auto","width":"100%","height":"250px","style":{"border":{"radius":"12px"},"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} /--></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"fontSize":"2.2rem","fontStyle":"normal","fontWeight":"600"}}} /-->

<!-- wp:post-excerpt {"moreText":"","excerptLength":30,"style":{"spacing":{"margin":{"top":"0px"}},"typography":{"fontSize":"1.5rem"}}} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"border":{"top":{"color":"var:preset|color|border-color","style":"dashed","width":"1px"},"right":[],"bottom":[],"left":[]},"spacing":{"blockGap":"0","padding":{"top":"1.5rem","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-style:dashed;border-top-width:1px;padding-top:1.5rem;padding-bottom:0"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","margin":{"top":"0","bottom":"0.75rem"}},"typography":{"fontSize":"1.4rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0.75rem;font-size:1.4rem"><!-- wp:avatar {"size":30,"style":{"border":{"radius":"50px"}}} /-->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"2px"}}}} -->
<p style="margin-top:0px;margin-bottom:0px;margin-left:2px"><?php echo esc_html_x( 'By', 'post author label', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"}}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"blogsy-svg","style":{"typography":{"fontSize":"15px","lineHeight":"1.2"},"spacing":{"padding":{"right":"6px","top":"0px","bottom":"0px"},"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"constrained","contentSize":"16px"}} -->
<div class="wp-block-group blogsy-svg" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:6px;padding-bottom:0px;font-size:15px;line-height:1.2"><!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}}} -->
<p style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px">📆	</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"wp-block-post-date__modified-date","style":{"typography":{"fontSize":"1.4rem","fontStyle":"normal","fontWeight":"600"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
