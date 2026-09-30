<?php
/**
 * Title: Author Widget
 * Slug: blogsy/author-widget
 * Categories: blogsy
 * Description: Author widget to show in sidebar.
 * Keywords: author, blogsy, widget
 *
 * @package Blogsy
 */

?>

<!-- wp:columns {"metadata":{"name":"Author widget"},"style":{"spacing":{"blockGap":{"left":"0px"},"margin":{"top":"0px","bottom":"0px"}}}} -->
<div class="wp-block-columns" style="margin-top:0px;margin-bottom:0px"><!-- wp:column {"metadata":{"blockVisibility":{"viewport":{"tablet":false,"mobile":false}}}} -->
<div class="wp-block-column"><!-- wp:spacer -->
<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"324px"} -->
<div class="wp-block-column" style="flex-basis:324px"><!-- wp:group {"style":{"spacing":{"padding":{"right":"0px","left":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px"><!-- wp:group {"style":{"spacing":{"blockGap":"1.6rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:image {"id":538,"width":"72px","height":"72px","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default","style":{"border":{"radius":"12px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border is-style-default"><img src="<?php echo esc_url_raw( BLOGSY_THEME_URI . '/assets/images/patterns/alex.webp' ); ?>" alt="" class="wp-image-538" style="border-radius:12px;object-fit:cover;width:72px;height:72px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"bottom":"0px"}}},"fontSize":"medium"} -->
<h5 class="wp-block-heading has-medium-font-size" style="margin-bottom:0px"><?php echo esc_html_x( "Hey, I'm Alex. I build frontend experiences and dive into tech, business, and wellness.", 'author bio', 'blogsy' ); ?></h5>
<!-- /wp:heading -->

<!-- wp:social-links {"size":"has-small-icon-size","style":{"spacing":{"margin":{"bottom":"0px"},"blockGap":{"left":"1rem"}}}} -->
<ul class="wp-block-social-links has-small-icon-size" style="margin-bottom:0px"><!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"youtube"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:heading {"level":6,"className":"is-style-default","style":{"typography":{"fontSize":"14px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<h6 class="wp-block-heading is-style-default has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:14px"><?php echo esc_html_x( 'Work Experience', 'section heading', 'blogsy' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20)"><!-- wp:group {"style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"},"top":{"color":"var:preset|color|border-color","width":"1px"},"right":{},"left":{}},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"0.4rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-bottom:0px;font-size:14px"><strong><?php echo esc_html_x( 'Peregrine Themes', 'company name', 'blogsy' ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0px"}},"typography":{"fontSize":"12px"},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( 'Frontend Developer', 'job title', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( '2021-present', 'employment period', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"},"top":{},"right":{},"left":{}},"spacing":{"padding":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"0.4rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-bottom:0px;font-size:14px"><strong><?php echo esc_html_x( 'Quilvex Horizon', 'company name', 'blogsy' ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0px"}},"typography":{"fontSize":"12px"},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( 'Web Developer', 'job title', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( '2019-2021', 'employment period', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"},"top":{},"right":{},"left":{}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;padding-top:0px;padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"0.4rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-bottom:0px;font-size:14px"><strong><?php echo esc_html_x( 'Kryvion Pulse', 'company name', 'blogsy' ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0px"}},"typography":{"fontSize":"12px"},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( 'Support Specialist', 'job title', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px"},"spacing":{"margin":{"bottom":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|body-color"}}}},"textColor":"body-color"} -->
<p class="has-body-color-color has-text-color has-link-color" style="margin-bottom:0px;font-size:12px"><?php echo esc_html_x( '2017-2019', 'employment period', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0px","margin":{"top":"1.6rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-top:1.6rem"><!-- wp:heading {"textAlign":"center","level":6,"className":"is-style-core-style-3 current-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}},"spacing":{"margin":{"bottom":"0px"}}},"textColor":"secondary"} -->
<h6 class="wp-block-heading has-text-align-center is-style-core-style-3 current-color has-secondary-color has-text-color has-link-color" style="margin-bottom:0px"><?php esc_html_e( 'Available for Hire', 'blogsy' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"1.6rem"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:1.6rem"><!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button"><?php echo esc_html_x( 'Get In Touch', 'button label', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"metadata":{"blockVisibility":{"viewport":{"tablet":false,"mobile":false}}}} -->
<div class="wp-block-column"><!-- wp:spacer -->
<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
