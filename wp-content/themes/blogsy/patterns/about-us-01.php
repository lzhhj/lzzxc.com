<?php
/**
 * Title: About Us 01
 * Slug: blogsy/about-us-01
 * Categories: blogsy
 * Description: About Us 01.
 * Keywords: about us, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"name":"About us 01","categories":["blogsy"],"patternName":"blogsy/about-us-01"},"align":"full","style":{"spacing":{"blockGap":"3.5rem","margin":{"top":"0px","bottom":"0px"},"padding":{"top":"5rem","bottom":"5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;margin-bottom:0px;padding-top:5rem;padding-bottom:5rem"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"3rem","left":"4.5rem"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"2rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group alignwide"><!-- wp:image {"id":2538,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-morphing"} -->
<figure class="wp-block-image size-full is-style-morphing"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/hero-img.webp' ); ?>" alt="" class="wp-image-2538" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%","style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"default"}} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:heading {"textAlign":"left","level":4,"className":"is-style-core-style-3","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent"}}},"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"accent"} -->
<h4 class="wp-block-heading has-text-align-left is-style-core-style-3 has-accent-color has-text-color has-link-color" style="margin-bottom:1rem"><?php esc_html_e( 'About Us', 'blogsy' ); ?></h4>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"left","style":{"spacing":{"margin":{"top":"0px"}}}} -->
<h2 class="wp-block-heading has-text-align-left" style="margin-top:0px">
	<?php
	printf(
		wp_kses_post(
			/* translators: %1$s is the highlighted phrase (e.g. "Thrilled"). */
			__( 'Hey %1$s to Meet You 👋', 'blogsy' )
		),
		'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . esc_html_x( 'Thrilled', 'Pattern highlighted word.', 'blogsy' ) . '</mark>'
	);
	?>
</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color">
	<?php echo esc_html_x( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur, quam id gravida rutrum, neque odio ultrices sem, at posuere lorem metus a augue. Vestibulum ac dui sed sem dignissim lobortis. Nullam suscipit aliquet erat ut efficitur. Nulla suscipit ullamcorper nulla ut bibendum. Suspendisse sed urna eget enim dictum cursus. Donec ultricies odio eget turpis rhoncus, quis pretium nisl vestibulum. Etiam nec pretium turpis, in consequat dui.', 'pattern dummy  text', 'blogsy' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-checkmark-list","style":{"typography":{"lineHeight":"2"},"spacing":{"padding":{"left":"1.2rem"}}}} -->
<ul style="padding-left:1.2rem;line-height:2" class="wp-block-list is-style-checkmark-list"><!-- wp:list-item -->
<li><?php echo esc_html_x( 'Vivamus id neque id turpis fermentum ornare in ac arcu.', 'pattern dummy  text', 'blogsy' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html_x( 'In sit amet urna congue, viverra nunc sed, tincidunt ligula.', 'pattern dummy  text', 'blogsy' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html_x( 'In sit amet urna congue, viverra nunc sed, tincidunt ligula.', 'pattern dummy  text', 'blogsy' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html_x( 'Morbi tempor metus nec felis lobortis.', 'pattern dummy  text', 'blogsy' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"left":"1.4rem"},"padding":{"top":"1rem"}}}} -->
<div class="wp-block-buttons" style="padding-top:1rem"><!-- wp:button {"className":"is-btn-arrow"} -->
<div class="wp-block-button is-btn-arrow"><a class="wp-block-button__link wp-element-button"><?php echo esc_html_x( 'Connect with us', 'Pattern button text', 'blogsy' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"accent","className":"is-btn-arrow"} -->
<div class="wp-block-button is-btn-arrow"><a class="wp-block-button__link has-accent-background-color has-background wp-element-button"><?php echo esc_html_x( 'Call Now', 'Pattern button text', 'blogsy' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
