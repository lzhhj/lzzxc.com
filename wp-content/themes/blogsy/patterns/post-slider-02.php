<?php
/**
 * Title: Post Slider 02
 * Slug: blogsy/post-slider-02
 * Categories: blogsy
 * Description: A post hero slider section with headline, text, and meta.
 * Keywords: hero, slider, post, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"categories":["blogsy"],"patternName":"blogsy/post-slider-02","name":"Post Slider 02"},"align":"full","style":{"spacing":{"margin":{"bottom":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-bottom:2rem"><!-- wp:group {"metadata":{"name":"Guten Slider"},"align":"wide","className":"guten-slider","layout":{"type":"constrained","contentSize":""}} -->
<div class="wp-block-group alignwide guten-slider"><!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"ignore","inherit":false,"taxQuery":{"category":[]},"parents":[],"format":[]},"align":"wide","className":"blogsy-posts-carousel-wrapper swiper","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide blogsy-posts-carousel-wrapper swiper"><!-- wp:post-template {"className":"blogsy-featured-posts swiper-wrapper","layout":{"type":"grid","columnCount":1}} -->
<!-- wp:group {"metadata":{"name":"post-item"},"className":"blog-card is-style-2","style":{"dimensions":{"minHeight":""},"border":{"radius":"16px"}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group blog-card is-style-2" style="border-radius:16px"><!-- wp:group {"metadata":{"name":"post-image"},"align":"wide","className":"blog-card__featured-image","style":{"dimensions":{"minHeight":"514px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide blog-card__featured-image" style="min-height:514px"><!-- wp:post-featured-image {"isLink":true,"width":"100%","height":"514px","overlayColor":"black","dimRatio":60,"align":"full"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"blog-card__content","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group alignwide blog-card__content" style="margin-top:0px;margin-bottom:0px"><!-- wp:group {"metadata":{"name":"post-content"},"align":"wide","className":"blog-card__content-inner","style":{"spacing":{"blockGap":"15px","padding":{"top":"2rem","bottom":"3.5rem","left":"3.5rem","right":"3rem"}}},"layout":{"type":"constrained","contentSize":"700px","justifyContent":"left"}} -->
<div class="wp-block-group alignwide blog-card__content-inner" style="padding-top:2rem;padding-right:3rem;padding-bottom:3.5rem;padding-left:3.5rem"><!-- wp:post-title {"textAlign":"left","className":"blog-card__title","style":{"typography":{"fontSize":"3.2rem","lineHeight":"1.2"},"spacing":{"margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->

<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":22,"style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"color":{"text":"#ffffffcc"}}} /-->

<!-- wp:group {"className":"blog-card__info","style":{"spacing":{"blockGap":"0.5rem","margin":{"bottom":"0px"}},"typography":{"fontSize":"1.4rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group blog-card__info" style="margin-bottom:0px;font-size:1.4rem"><!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem","margin":{"top":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group has-white-color has-text-color has-link-color" style="margin-top:0"><!-- wp:avatar {"size":30,"style":{"border":{"radius":"50px"}}} /-->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"2px"}}}} -->
<p style="margin-top:0px;margin-bottom:0px;margin-left:2px"><?php echo esc_html_x( 'By', 'post author prefix', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"capitalize"}}} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"right":"0","left":"0","top":"0","bottom":"0"}},"layout":{"selfStretch":"fit","flexSize":null},"elements":{"link":{"color":{"text":"#ffffffb3"}}},"color":{"text":"#ffffffb3"}}} -->
<p class="has-text-color has-link-color" style="color:#ffffffb3;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0"><strong><?php echo esc_html_x( 'on', 'post date prefix', 'blogsy' ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"#ffffffb3"}}},"color":{"text":"#ffffffb3"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:post-terms {"term":"category","textAlign":"left","className":"pt-absolute pt-top-30 pt-left-30 pt-z-index-9","style":{"typography":{"fontSize":"1.5rem"},"spacing":{"margin":{"bottom":"0px","top":"0px","left":"0px","right":"0px"}},"border":{"radius":"100px"}}} /-->

<!-- wp:read-more /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
