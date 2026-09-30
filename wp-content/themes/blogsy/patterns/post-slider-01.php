<?php
/**
 * Title: Post Slider 01
 * Slug: blogsy/post-slider-01
 * Categories: blogsy
 * Description: A post hero slider section with headline, text and meta.
 * Keywords: hero, slider, post, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"categories":["blogsy"],"patternName":"blogsy/post-slider-01","name":"Post Slider 01"},"align":"full","style":{"spacing":{"margin":{"bottom":"2rem"}}},"layout":{"type":"constrained","contentSize":""}} -->
<div class="wp-block-group alignfull" style="margin-bottom:2rem"><!-- wp:group {"metadata":{"name":"Guten Slider"},"align":"wide","className":"guten-slider","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide guten-slider"><!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false,"taxQuery":{"category":[]},"parents":[],"format":[]},"align":"wide","className":"blogsy-posts-carousel-wrapper swiper"} -->
<div class="wp-block-query alignwide blogsy-posts-carousel-wrapper swiper"><!-- wp:post-template {"className":"blogsy-featured-posts swiper-wrapper","layout":{"type":"grid","columnCount":1}} -->
<!-- wp:group {"metadata":{"name":"post-item"},"className":"blog-card is-style-2","style":{"dimensions":{"minHeight":""},"border":{"radius":"16px"}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group blog-card is-style-2" style="border-radius:16px"><!-- wp:group {"metadata":{"name":"post-image"},"align":"wide","className":"blog-card__featured-image","style":{"dimensions":{"minHeight":"530px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide blog-card__featured-image" style="min-height:530px"><!-- wp:post-featured-image {"isLink":true,"width":"100%","height":"530px","overlayColor":"black","dimRatio":60,"align":"full"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"blog-card__content","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center"}} -->
<div class="wp-block-group blog-card__content" style="margin-top:0px;margin-bottom:0px"><!-- wp:group {"metadata":{"name":"post-content"},"className":"blog-card__content-inner","style":{"spacing":{"blockGap":"15px","padding":{"top":"1.75rem","bottom":"1.75rem","left":"1.75rem","right":"1.75rem"}}},"layout":{"type":"constrained","contentSize":"700px"}} -->
<div class="wp-block-group blog-card__content-inner" style="padding-top:1.75rem;padding-right:1.75rem;padding-bottom:1.75rem;padding-left:1.75rem"><!-- wp:post-terms {"term":"category","textAlign":"center","style":{"typography":{"fontSize":"1.5rem"},"spacing":{"margin":{"bottom":"2.4rem"}},"border":{"radius":"100px"}}} /-->

<!-- wp:post-title {"textAlign":"center","isLink":true,"className":"blog-card__title","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"3.8rem","lineHeight":"1.2"},"spacing":{"margin":{"top":"0px"}}},"textColor":"white"} /-->

<!-- wp:group {"className":"blog-card__info","style":{"spacing":{"blockGap":"1.5rem"},"typography":{"fontSize":"1.4rem"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group blog-card__info has-white-color has-text-color has-link-color" style="font-size:1.4rem"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","margin":{"top":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:0"><!-- wp:avatar {"size":30,"style":{"border":{"radius":"50px"}}} /-->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"2px"}}}} -->
<p style="margin-top:0px;margin-bottom:0px;margin-left:2px"><?php echo esc_html_x( 'By', 'post author prefix', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"}}} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}},"layout":{"selfStretch":"fit","flexSize":null}}} -->
<p style="margin-top:0;margin-right:0;margin-bottom:0;margin-left:0">—</p>
<!-- /wp:paragraph -->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
