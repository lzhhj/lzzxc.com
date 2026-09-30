<?php
/**
 * Title: Hero with Image
 * Slug: blogsy/hero-with-image
 * Categories: blogsy
 * Description: A hero section with headline, text, button and image.
 * Keywords: hero, intro, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"name":"Hero with Image","categories":["blogsy"],"patternName":"blogsy/hero-with-image"},"align":"full","className":"blogsy-hero-animated-bg","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"},"padding":{"top":"5rem","bottom":"5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull blogsy-hero-animated-bg" style="margin-top:0px;margin-bottom:0px;padding-top:5rem;padding-bottom:5rem"><!-- wp:columns {"verticalAlignment":"center","metadata":{"categories":["blogsy"],"patternName":"blogsy/hero-with-image"},"align":"wide","className":"pt-space-between","style":{"spacing":{"blockGap":{"left":"0rem"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center pt-space-between"><!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"bottom":"22px"}},"typography":{"fontStyle":"normal","fontWeight":"700"}}} -->
<h1 class="wp-block-heading" style="margin-bottom:22px;font-style:normal;font-weight:700">
	<?php
		printf(
			/* translators: %s is the person name, e.g., 'Alex'. */
			esc_html_x( 'Hi, I\'m %s 👋', 'Pattern placeholder text.', 'blogsy' ),
			'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . esc_html_x( 'Alex', 'Example person name.', 'blogsy' ) . '</mark>'
		);
		?>
</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"2rem"}}} -->
<p style="font-size:2rem">
	<?php
		printf(
			/* translators: %1$s is the designation name, e.g., 'web creator', %2$s is the city name, e.g., 'Austin'. */
			esc_html_x( 'I\'m a %1$s based in %2$s. I enjoy building interfaces that feel smooth, fast, and human. This little corner of the internet is where I write, share ideas, and experiment with code.', 'Pattern placeholder text.', 'blogsy' ),
			'<strong>' . esc_html_x( 'web creator', 'Example designation name.', 'blogsy' ) . '</strong>',
			'<strong>' . esc_html_x( 'Austin', 'Example city name.', 'blogsy' ) . '</strong>'
		);
		?>
</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">📙<?php echo esc_html_x( 'Explore Blog', 'Button text for hero pattern.', 'blogsy' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"1px"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#" style="border-width:1px">🔔<?php echo esc_html_x( 'Subscribe', 'Button text for hero pattern.', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":2538,"sizeSlug":"full","linkDestination":"none","className":"is-style-morphing"} -->
<figure class="wp-block-image size-full is-style-morphing"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/hero-img.webp' ); ?>" alt="" class="wp-image-2538"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
