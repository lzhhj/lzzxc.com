<?php
/**
 * Title: Call to action
 * Slug: blogsy/call-to-action
 * Categories: blogsy
 * Description: Call to action.
 * Keywords: call to action, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"name":"Call to Action","categories":["blogsy"],"patternName":"blogsy/hero-with-image"},"align":"full","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"},"padding":{"top":"2rem","bottom":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;margin-bottom:0px;padding-top:2rem;padding-bottom:2rem"><!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide" style="margin-top:0px;margin-bottom:0px"><!-- wp:group {"style":{"background":{"backgroundImage":{"url":"<?php echo esc_url_raw( BLOGSY_THEME_URI . '/assets/images/patterns/cta-bg.webp' ); ?>","id":27999,"source":"file","title":"StockSnap_TDAU1ERCD4"},"backgroundSize":"cover"},"spacing":{"margin":{"top":"0px","bottom":"0px"},"padding":{"right":"0px","left":"0px","top":"0px","bottom":"0px"},"blockGap":"0px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"dimRatio":70,"overlayColor":"black","isUserOverlayColor":true,"minHeight":200,"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"},"padding":{"top":"3rem","bottom":"3rem","left":"3rem","right":"3rem"}}},"layout":{"type":"default"}} -->
<div class="wp-block-cover" style="margin-top:0px;margin-bottom:0px;padding-top:3rem;padding-right:3rem;padding-bottom:3rem;padding-left:3rem;min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"layout":{"selfStretch":"fill","flexSize":null},"spacing":{"blockGap":"0px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"left","className":"pt-d-inline-block","style":{"elements":{"link":{"color":{"text":"#fbfbfb"}}},"spacing":{"padding":{"top":"5px","bottom":"5px","left":"12px","right":"12px"},"margin":{"bottom":"12px"}},"border":{"radius":{"topLeft":"3px","topRight":"3px","bottomLeft":"3px","bottomRight":"3px"}},"color":{"text":"#fbfbfb"}},"backgroundColor":"accent"} -->
<p class="has-text-align-left pt-d-inline-block has-accent-background-color has-text-color has-background has-link-color" style="border-top-left-radius:3px;border-top-right-radius:3px;border-bottom-left-radius:3px;border-bottom-right-radius:3px;color:#fbfbfb;margin-bottom:12px;padding-top:5px;padding-right:12px;padding-bottom:5px;padding-left:12px"><?php echo esc_html_x( 'News Banner', 'cta banner subtitle', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"elements":{"link":{"color":{"text":"#fbfbfb"}}},"spacing":{"margin":{"bottom":"5px"}},"color":{"text":"#fbfbfb"}}} -->
<h1 class="wp-block-heading has-text-align-left has-text-color has-link-color" style="color:#fbfbfb;margin-bottom:5px;font-style:normal;font-weight:700">
	<?php
	printf(
		wp_kses_post(
			/* translators: %1$s is the highlighted phrase (e.g. "Journey"). */
			__( 'Ready to Start Your %1$s with Us?', 'blogsy' )
		),
		'<mark style="background-color:rgba(0, 0, 0, 0);color:#7bdcb5" class="has-inline-color aos-zoom-in">' . esc_html_x( 'Journey', 'Pattern highlighted word.', 'blogsy' ) . '</mark>'
	);
	?>
</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"left","style":{"typography":{"fontSize":"1.8rem"},"elements":{"link":{"color":{"text":"#fbfbfb"}}},"spacing":{"margin":{"top":"0px","bottom":"0px"}},"color":{"text":"#fbfbfb"}}} -->
<p class="has-text-align-left has-text-color has-link-color" style="color:#fbfbfb;margin-top:0px;margin-bottom:0px;font-size:1.8rem"><?php echo esc_html_x( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Pattern dummy text', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline","style":{"elements":{"link":{"color":{"text":"#fbfbfb"}}},"color":{"text":"#fbfbfb"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-color has-link-color wp-element-button" href="#" style="color:#fbfbfb"><?php echo esc_html_x( 'Explore Now', 'pattern cta button', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"align":"left","style":{"typography":{"fontSize":"1.8rem","textTransform":"uppercase","letterSpacing":"2px"},"spacing":{"margin":{"top":"5px","bottom":"0px"}}}} -->
<p class="has-text-align-left" style="margin-top:5px;margin-bottom:0px;font-size:1.8rem;letter-spacing:2px;text-transform:uppercase"><?php echo esc_html_x( 'Advertisement', 'pattern cta text', 'blogsy' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
