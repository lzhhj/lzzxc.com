<?php
/**
 * Title: Hero
 * Slug: blogsy/hero
 * Categories: blogsy
 * Description: A hero section with headline, text and featured categories.
 * Keywords: hero, intro, blogsy
 *
 * @package Blogsy
 */

?>

<!-- wp:group {"metadata":{"categories":["blogsy"],"patternName":"blogsy/hero","name":"Hero"},"align":"full","style":{"spacing":{"blockGap":"0px","padding":{"top":"7rem","bottom":"7rem"},"margin":{"top":"0px","bottom":"0px"}}},"backgroundColor":"light","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background" style="margin-top:0px;margin-bottom:0px;padding-top:7rem;padding-bottom:7rem"><!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|50"},"margin":{"bottom":"3.2rem"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group" style="margin-bottom:3.2rem;padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="font-style:normal;font-weight:700">
	<?php
	printf(
		wp_kses_post(
			/* translators: %1$s is the highlighted phrase (e.g. "Reflections"). */
			__( 'Heartfelt %1$s: Journeys of Love,<br>Loss &amp; Renewal', 'blogsy' )
		),
		'<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-accent-color">' . esc_html_x( 'Reflections', 'Pattern highlighted word.', 'blogsy' ) . '</mark>'
	);
	?>
</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">
	<?php
		echo wp_kses_post( _x( 'Discover thoughtful writing that speaks to the heart ❤️ stories that inspire growth, <br>celebrate connection, and remind us of the beauty in change.', 'Pattern hero description.', 'blogsy' ) );
	?>
</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-default","style":{"layout":{"selfStretch":"fixed","flexSize":"2px"},"spacing":{"margin":{"top":"2.2rem"}},"color":{"background":"#e1e1e8"}}} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-default" style="margin-top:2.2rem;background-color:#e1e1e8;color:#e1e1e8"/>
<!-- /wp:separator --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"align":"center","style":{"typography":{"letterSpacing":"1px"}},"fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size" style="letter-spacing:1px"><strong><?php esc_html_e( 'EXPLORE TRENDING TOPICS', 'blogsy' ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"margin":{"top":"0"}}},"fontSize":"small","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group has-small-font-size" style="margin-top:0;padding-top:var(--wp--preset--spacing--60)"><!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27837,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"},"layout":{"selfStretch":"fit","flexSize":null}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-01.webp' ); ?>" alt="" class="wp-image-27837" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{":hover":{"color":{"text":"var:preset|color|accent"}},"color":{"text":"var:preset|color|heading"}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Technology', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27838,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-02.webp' ); ?>" alt="" class="wp-image-27838" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Travel', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27839,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-03.webp' ); ?>" alt="" class="wp-image-27839" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Sports', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27837,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-01.webp' ); ?>" alt="" class="wp-image-27837" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Trends', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27838,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-02.webp' ); ?>" alt="" class="wp-image-27838" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Money', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27839,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-03.webp' ); ?>" alt="" class="wp-image-27839" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Creativity', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27837,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-01.webp' ); ?>" alt="" class="wp-image-27837" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Hot', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27838,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-02.webp' ); ?>" alt="" class="wp-image-27838" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'Business', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-drop-shadow-hover","style":{"border":{"radius":"100px","color":"#694de817","width":"1px"},"shadow":"var:preset|shadow|subtle","typography":{"fontSize":"0.76rem"},"spacing":{"blockGap":"6px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group is-style-drop-shadow-hover has-border-color has-white-background-color has-background" style="border-color:#694de817;border-width:1px;border-radius:100px;font-size:0.76rem;box-shadow:var(--wp--preset--shadow--subtle)"><!-- wp:image {"id":27839,"width":"24px","height":"24px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( BLOGSY_THEME_URI . '/assets/images/patterns/info-icon-03.webp' ); ?>" alt="" class="wp-image-27839" style="border-radius:0px;object-fit:cover;width:24px;height:24px"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"typography":{"fontSize":"2.27em"},"elements":{"link":{"color":{"text":"var:preset|color|heading"},":hover":{"color":{"text":"var:preset|color|accent"}}}}},"textColor":"heading"} -->
<p class="has-heading-color has-text-color has-link-color" style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-size:2.27em"><strong><a href="#"><?php esc_html_e( 'News', 'blogsy' ); ?></a></strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
