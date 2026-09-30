<?php
/**
 * Title: Post List 02
 * Slug: blogsy/post-list-02
 * Categories: blogsy
 * Description: Post list with image, title, author, date and category.
 * Keywords: blog, post list,blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"categories":["blogsy"],"patternName":"blogsy/post-list-02","name":"Post List 02"},"align":"full","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"1.8rem"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;margin-bottom:0px;padding-top:2rem;padding-bottom:2rem"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"level":4,"className":"is-style-core-style-1","style":{"spacing":{"margin":{"bottom":"0px"}}}} -->
<h4 class="wp-block-heading is-style-core-style-1" style="margin-bottom:0px"><?php echo esc_html_x( "Editor's Pick", 'Pattern section title', 'blogsy' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill is-btn-arrow"} -->
<div class="wp-block-button is-style-fill is-btn-arrow"><a class="wp-block-button__link wp-element-button"><?php echo esc_html_x( 'See all', 'Pattern button label', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":12,"query":{"perPage":4,"pages":0,"offset":0,"postType":"post","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false,"taxQuery":{"category":[]},"parents":[],"format":[]},"align":"wide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"className":"blogsy-featured-posts","layout":{"type":"grid","columnCount":4}} -->
<!-- wp:group {"metadata":{"name":"post-item"},"className":"blog-card aos-fade-in","style":{"border":{"radius":"16px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group blog-card aos-fade-in" style="border-radius:16px"><!-- wp:group {"metadata":{"name":"post-image"},"className":"blog-card__featured-image","style":{"dimensions":{"minHeight":"300px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group blog-card__featured-image" style="min-height:300px"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"auto","width":"100%","height":"300px","overlayColor":"black","dimRatio":60} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"post-content"},"className":"blog-card__content","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group blog-card__content" style="margin-top:0px;margin-bottom:0px"><!-- wp:group {"className":"blog-card__content-inner","style":{"spacing":{"blockGap":"0","padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group blog-card__content-inner" style="padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:post-title {"className":"blog-card__title","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"2.2rem"}},"textColor":"white"} /-->

<!-- wp:group {"className":"blog-card__info","style":{"spacing":{"blockGap":"0.4rem"},"elements":{"link":{"color":{"text":"#dedede"}}},"color":{"text":"#dedede"},"typography":{"fontSize":"1.4rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group blog-card__info has-text-color has-link-color" style="color:#dedede;font-size:1.4rem"><!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize","fontStyle":"normal","fontWeight":"600"}}} /-->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}},"layout":{"selfStretch":"fit","flexSize":null}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><?php echo esc_html_x( 'on', 'post date prefix', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:read-more /-->

<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"1.4rem","fontStyle":"normal","fontWeight":"500"},"border":{"radius":"100px"}}} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
