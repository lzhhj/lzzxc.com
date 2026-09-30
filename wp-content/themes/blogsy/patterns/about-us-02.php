<?php
/**
 * Title: About Us 02
 * Slug: blogsy/about-us-02
 * Categories: blogsy
 * Description: About Us 02.
 * Keywords: about us, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"name":"About us 02"},"align":"wide","style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide" style="padding-top:3rem;padding-bottom:3rem"><!-- wp:heading {"textAlign":"center","level":1,"style":{"spacing":{"margin":{"top":"0px","bottom":"0rem"}}}} -->
<h1 class="wp-block-heading has-text-align-center" style="margin-top:0px;margin-bottom:0rem">
	<?php
	printf(
		wp_kses_post(
			/* translators: %1$s is the highlighted phrase (e.g. "Thrilled"). */
			__( 'What Makes Us %1$s', 'blogsy' )
		),
		'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . esc_html_x( 'Different', 'Pattern highlighted word.', 'blogsy' ) . '</mark>'
	);
	?>
</h1>
<!-- /wp:heading -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"10px","bottom":"4.5rem"}}},"backgroundColor":"border-color"} -->
<hr class="wp-block-separator has-text-color has-border-color-color has-alpha-channel-opacity has-border-color-background-color has-background" style="margin-top:10px;margin-bottom:4.5rem"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px","bottom":"5rem"},"blockGap":"2rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:5rem"><!-- wp:image {"id":28075,"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"1.6rem"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url_raw( BLOGSY_THEME_URI . '/assets/images/patterns/laptop.webp' ); ?>" alt="" class="wp-image-28075" style="border-radius:1.6rem;aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":28076,"aspectRatio":"2/3","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"1.6rem"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url_raw( BLOGSY_THEME_URI . '/assets/images/patterns/love-hearts.webp' ); ?>" alt="" class="wp-image-28076" style="border-radius:1.6rem;aspect-ratio:2/3;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":28074,"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"1.6rem"}}} -->
<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url_raw( BLOGSY_THEME_URI . '/assets/images/patterns/interior-decor.webp' ); ?>" alt="" class="wp-image-28074" style="border-radius:1.6rem;aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"right":"0px","left":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:0px;padding-left:0px"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading","fontSize":"medium"} -->
<p class="has-heading-color has-text-color has-link-color has-medium-font-size">
	<strong><?php echo esc_html_x( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum facilisis elit lorem. Phasellus at sollicitudin urna, quis convallis massa. Sed vitae tincidunt lorem. In bibendum neque ac dolor fermentum semper id nec nulla. Suspendisse risus urna, faucibus eget tortor at, placerat pellentesque felis. In facilisis rutrum massa quis porttitor. Proin ac massa in orci imperdiet ullamcorper. Nulla egestas cursus nunc vel aliquam.', 'pattern dummy text', 'blogsy' ); ?></strong>
</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"spacing":{"margin":{"top":"2.4rem"}}},"textColor":"heading","fontSize":"medium"} -->
<p class="has-heading-color has-text-color has-link-color has-medium-font-size" style="margin-top:2.4rem">
	<strong><?php echo esc_html_x( 'Sed fringilla odio et sem varius semper nec ultricies mauris. Pellentesque ut metus eget felis congue auctor. Duis eleifend neque non tellus tincidunt semper. Curabitur sodales augue faucibus ligula tincidunt, nec sodales erat blandit. Morbi et tempus nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum facilisis sit amet sem non imperdiet. Vivamus auctor mauris et ultrices blandit.', 'pattern dummy text', 'blogsy' ); ?></strong>
</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
