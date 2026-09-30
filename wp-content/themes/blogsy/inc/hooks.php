<?php
/**
 * Blogsy Theme Hooks
 *
 * @package Blogsy
 * @since 1.0.0
 */

use Blogsy\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replace author avatar with custom profile image
 *
 * Hook: 'pre_get_avatar'
 *
 * @param string                        $avatar The avatar to retrieve.
 * @param int|string|WP_User|WP_Comment $id_or_email The user ID, email address, WP_User object, or WP_Comment object.
 * @param array                         $args Arguments passed to get_avatar_data(), after processing.
 * @return string $avatar Modified avatar HTML.
 * @since 1.0.0
 */
function blogsy_replace_author_avatar( ?string $avatar, $id_or_email, array $args ): ?string {

	if ( isset( $args['force_default'] ) && $args['force_default'] ) {
		return $avatar;
	}

	// Get user data.
	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', (int) $id_or_email );
	} elseif ( is_object( $id_or_email ) ) {
		$comment = $id_or_email;
		if ( ! empty( $comment->user_id ) ) {
			$user = get_user_by( 'id', $comment->user_id );
		} else {
			$user = get_user_by( 'email', $comment->comment_author_email );
		}

		if ( ! $user ) {
			return $avatar;
		}
	} elseif ( is_string( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
	} else {
		return $avatar;
	}

	if ( ! $user ) {
		return $avatar;
	}

	$user_id           = $user->ID;
	$profile_image_url = get_the_author_meta( 'blogsy_author_profile_image', $user_id );
	if ( $profile_image_url ) {
		return '<img class="avatar avatar-' . (int) $args['size'] . ' photo" src="' . esc_url( $profile_image_url ) . '" alt="' . esc_attr( $user->display_name ) . '" loading="lazy" width="' . $args['width'] . '" height="' . $args['height'] . '">';
	}

	return $avatar;
}

add_filter( 'pre_get_avatar', 'blogsy_replace_author_avatar', 10, 3 );


/**
 * Add class to body tag
 *
 * Hook: 'body_class'
 *
 * @param array $classes Classes for the body element.
 * @return array $classes Modified classes for the body element.
 * @since 1.0.0
 */
function blogsy_body_class( array $classes ): array {
	global $post;
	// Menu accessibility support.
	$classes[] = 'blogsy-menu-accessibility';

	if ( Helper::get_option( 'smooth_scroll' ) ) {
		$classes[] = 'blogsy-smooth-scroll';
	}

	// Button shape style.
	$classes[] = 'pt-shape--' . sanitize_html_class( Helper::get_option( 'button_shape_style' ) );

	// Header related styles.
	// Singular Custom Header.
	$header_id                 = blogsy_get_layout_template_id( 'header' );
	$header                    = blogsy_template_section_render( $header_id );
	$header_layout             = Helper::get_option( 'header_layout' );
	$post_id                   = blogsy_get_the_id();
	$single_page_header_layout = '';

	if ( $post_id ) {
		$single_page_header_layout = get_post_meta(
			$post_id,
			'blogsy_page_header',
			true
		);
	}

	if ( $single_page_header_layout && '0' !== $single_page_header_layout && 'disable' !== $single_page_header_layout ) {
		$header_layout = $single_page_header_layout;
	}

	if ( blogsy_is_header_displayed() || ! $header ) {
		$classes[] = 'pt-header-' . sanitize_html_class( $header_layout );
	}

	// Disable page card style.
	if ( ( $post && get_post_meta( $post->ID, 'blogsy_disable_page_card_style', true ) ) ) {
		$classes[] = 'blogsy-disable-page-card-style';
	}

	return $classes;
}

add_filter( 'body_class', 'blogsy_body_class' );

/**
 * Add Theme Scheme to HTML tag
 *
 * Hook: 'language_attributes'
 *
 * @param string $output Language attributes output.
 * @return string $output Modified language attributes output.
 * @since 1.0.0
 */
function blogsy_theme_scheme( string $output ): string {

	if ( blogsy_dark_mode_enabled() ) {
		$output .= ' scheme="' . blogsy_current_theme_scheme() . '"';

		if ( Helper::get_option( 'always_dark_mode' ) ) {
			$output .= ' dark-theme=""';
		}
	}

	return $output;
}

add_filter( 'language_attributes', 'blogsy_theme_scheme' );

/**
 * Full Size for Gif image thumbnail
 *
 * Hook: 'wp_get_attachment_image_src
 *
 * @param array        $image Image data.
 * @param int          $attachment_id Attachment ID.
 * @param string|array $size Size of image.
 * @param bool         $icon Whether the image is an icon.
 * @return array $image Image data.
 * @since 1.0.0
 */
function blogsy_full_size_gif_images( $image, $attachment_id, $size, bool $icon ) {
	if ( ! $image ) {
		// No image found, return early.
		return $image;
	}

	if ( \Blogsy\Helper::get_option( 'full_size_gif' ) && ! empty( $image[0] ) ) {

		$format = wp_check_filetype( $image[0] );

		if ( ! empty( $format ) && 'gif' === $format['ext'] ) {
			// Only do replacement if $size is a string and not 'full'.
			if ( is_string( $size ) && 'full' !== $size ) {
				return wp_get_attachment_image_src( $attachment_id, 'full', $icon );
			}
		}
	}

	return $image;
}

add_filter( 'wp_get_attachment_image_src', 'blogsy_full_size_gif_images', 10, 4 );


/**
 * Limit search for custom post types
 *
 * Hook: 'pre_get_posts'
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 * @since 1.0.0
 */
function blogsy_limit_search_post_types( WP_Query $query ): WP_Query {

	$post_types = Helper::get_option( 'search_post_types' );

	if ( is_array( $post_types ) && count( $post_types ) && $query->is_search && $query->is_main_query() && ! is_admin() ) {
		$query->set( 'post_type', $post_types );
	}

	return $query;
}

add_filter( 'pre_get_posts', 'blogsy_limit_search_post_types', 100 );

/**
 * Filters the arguments for a single nav menu item to include dropdown indicators.
 *
 * Hook: 'nav_menu_item_args'
 *
 * @param stdClass      $args An object of wp_nav_menu() arguments.
 * @param WP_Post|mixed $item Menu item data object.
 * @since 1.0.0
 */
function blogsy_nav_menu_item_args( stdClass $args, $item ): stdClass {

	// Bail early if $item is not an object or has no classes.
	if ( ! is_object( $item ) || ! isset( $item->classes ) || ! is_array( $item->classes ) ) {
		return $args;
	}

	$dropdown_indicator = \Blogsy\Icon::get_svg( 'chevron-down', 'ui', [ 'size' => 17 ] );

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		if ( false === strpos( $args->link_after, $dropdown_indicator ) ) {
			$args->link_after .= $dropdown_indicator;
			$args->after       = '<button type="button" class="blogsy-mobile-toggle">' . $dropdown_indicator . '</button>';
		}
	} else {
		$args->link_after = str_replace( $dropdown_indicator, '', $args->link_after );
		$args->after      = '';
	}

	return $args;
}

add_filter( 'nav_menu_item_args', 'blogsy_nav_menu_item_args', 10, 3 );



/**
 * Filters the arguments for a single nav menu item to include dropdown indicators.
 *
 * Hook: 'wp_page_menu'
 *
 * @param string $output The menu output.
 * @since 1.0.0
 */
function blogsy_page_menu_output_modification( string $output ): string {
	$dropdown_indicator = \Blogsy\Icon::get_svg( 'chevron-down', 'ui', [ 'size' => 17 ] );

	// Get all pages with children.
	$all_pages = get_pages();

	foreach ( $all_pages as $page ) {
		$children = get_pages( [ 'child_of' => $page->ID ] );

		if ( ! empty( $children ) ) {
			// Find and replace the page link in output.
			$old_pattern = '/<a href="' . preg_quote( get_permalink( $page->ID ), '/' ) . '"[^>]*><span>' . preg_quote( $page->post_title, '/' ) . '<\/span><\/a>/';

			$new_content  = '<a href="' . get_permalink( $page->ID ) . '">';
			$new_content .= '<span>' . $page->post_title . '</span>';
			$new_content .= $dropdown_indicator;
			$new_content .= '</a>';
			$new_content .= '<button type="button" class="blogsy-mobile-toggle">' . $dropdown_indicator . '</button>';

			$output = preg_replace( $old_pattern, $new_content, $output );

			// Also add the has-children class to li element.
			$output = str_replace(
				'class="page_item page-item-' . $page->ID . '"',
				'class="page_item page-item-' . $page->ID . ' page_item_has_children"',
				$output
			);
		}
	}

	return $output;
}

add_filter( 'wp_page_menu', 'blogsy_page_menu_output_modification', 10, 2 );


/**
 * Display social icons in social links menu.
 *
 * Hook: 'walker_nav_menu_start_el'
 *
 * @param  string   $item_output The menu item output.
 * @param  WP_Post  $item        Menu item object.
 * @param  int      $depth       Depth of the menu.
 * @param  stdClass $args        wp_nav_menu() arguments.
 * @return string  $item_output The menu item output with social icon.
 * @since 1.0.0
 */
function blogsy_nav_menu_social_icons( string $item_output, WP_Post $item, int $depth, stdClass $args ): string {

	// Get supported social icons.
	$social_icons = blogsy_social_links_icons();

	// Change SVG icon inside social links menu if there is supported URL.
	if ( false !== strpos( $args->menu_class, 'blogsy-social-icons' ) ) {

		foreach ( $social_icons as $attr => $value ) {
			if ( false !== strpos( $item_output, (string) $attr ) ) {
				$item_output = str_replace(
					$args->link_after,
					'</span><span class="' . esc_attr( $value ) . '" title="' . esc_attr( ucfirst( $value ) ) . '">' . \Blogsy\Icon::get_svg( $value, 'social', [ 'aria-hidden' => 'true' ] ),
					$item_output
				);
			}
		}
	}

	return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'blogsy_nav_menu_social_icons', 10, 4 );

/**
 * Returns an array of supported social links (URL and icon name).
 *
 * @return array $social_links_icons
 * @since 1.0.0
 */
function blogsy_social_links_icons(): array {

	// Supported social links icons.
	$social_links_icons = [
		'500px.com'         => '500px',
		'amazon.com'        => 'amazon',
		'behance.net'       => 'behance',
		'digg.com'          => 'digg',
		'dribbble.com'      => 'dribbble',
		'deviantart'        => 'deviantart',
		'etsy.com'          => 'etsy',
		'facebook.com'      => 'facebook',
		'flipboard.com'     => 'flipboard',
		'flickr.com'        => 'flickr',
		'foursquare.com'    => 'foursquare',
		'github.com'        => 'github',
		'plus.google.com'   => 'google-plus',
		'instagram.com'     => 'instagram',
		'linkedin.com'      => 'linkedin',
		'mailto:'           => 'mail',
		'tel:'              => 'phone',
		'medium.com'        => 'medium',
		'pinterest.com'     => 'pinterest',
		'reddit.com'        => 'reddit',
		'skype.com'         => 'skype',
		'skype:'            => 'skype',
		'snapchat.com'      => 'snapchat',
		'soundcloud.com'    => 'soundcloud',
		'spotify.com'       => 'spotify',
		'tumblr.com'        => 'tumblr',
		'twitch.tv'         => 'twitch',
		'twitter.com'       => 'twitter',
		'x.com'             => 'twitter',
		'vimeo.com'         => 'vimeo',
		'xing.com'          => 'xing',
		'vk.com'            => 'vkontakte',
		'youtube.com'       => 'youtube',
		'yelp.com'          => 'yelp',
		'wa.me'             => 'whatsapp',
		'tiktok.com'        => 'tiktok',
		'stackoverflow.com' => 'stackoverflow',
		'rss.com'           => 'rss',
		't.me'              => 'telegram',
		'discord.com'       => 'discord',
		'wechat.com'        => 'wechat',
		'qq.com'            => 'qq',
		'bilibili.tv'       => 'bilibili',
		'threads.net'       => 'threads',
		'linktr.ee'         => 'linktree',
		'rumble.com'        => 'rumble',
		'mastodon.social'   => 'mastodon',
		'bsky.app'          => 'bluesky',
		'bluesky.app'       => 'bluesky',
		'bsky.social'       => 'bluesky',
		'gemspace.com'      => 'gemspace',
		'lifegroupchat.com' => 'lifegroupchat',
	];

	/**
	 * Filter Blogsy social links icons.
	 *
	 * @since 1.0.0
	 * @param array $social_links_icons Array of social links icons.
	 */
	return apply_filters( 'blogsy_social_links_icons', $social_links_icons );
}

/**
 * Remove dark mode widgets if dark mode is disabled.
 *
 * Hook: 'blogsy_main_header_widgets'
 *
 * @param array $widgets Header widgets.
 * @return array $widgets Filtered header widgets.
 * @since 1.0.0
 */
function blogsy_filter_main_header_widgets( array $widgets ): array {
	if ( ! Helper::get_option( 'dark_mode' ) ) {
		unset( $widgets['darkmode'] );
	}

	return $widgets;
}

add_filter( 'blogsy_main_header_widgets', 'blogsy_filter_main_header_widgets', 10, 1 );

/**
 * Remove dark mode widgets if dark mode is disabled from header.
 *
 * Hook: 'blogsy_main_header_selected_widgets'
 *
 * @param array $widgets Header widgets.
 * @return array $widgets Filtered header widgets.
 * @since 1.0.0
 */
function blogsy_filter_main_header_selected_widgets( array $widgets ): array {

	if ( ! Helper::get_option( 'dark_mode' ) ) {
		foreach ( $widgets as $key => $item ) {
			if ( isset( $item['type'] ) && 'darkmode' === $item['type'] ) {
				unset( $widgets[ $key ] );
				break;
			}
		}
	}

	return $widgets;
}

add_filter( 'blogsy_main_header_selected_widgets', 'blogsy_filter_main_header_selected_widgets' );


/**
 * Ajax handler to get nonces.
 *
 * @since 1.0.18
 */
function blogsy_get_nonces() {

	wp_send_json_success(
		[
			'load_more_posts'     => wp_create_nonce( 'blogsy_get_load_more_posts' ),
			'mailchimp_subscribe' => wp_create_nonce( 'blogsy_mailchimp_subscribe' ),
			'story'               => wp_create_nonce( 'blogsy_story' ),
		]
	);
}

add_action( 'wp_ajax_blogsy_get_nonces', 'blogsy_get_nonces' );
add_action( 'wp_ajax_nopriv_blogsy_get_nonces', 'blogsy_get_nonces' );

/**
 * Ajax handler to load stories.
 *
 * @since 1.0.0
 */
function blogsy_stories_ajax_call() {
	// Security check for the AJAX nonce.
	check_ajax_referer( 'blogsy_story', '_wpnonce' );

	// Get input data, always sanitize when handling input.
	$story_ids     = isset( $_POST['storyIds'] ) ? array_map( 'absint', (array) $_POST['storyIds'] ) : [];
	$post_per_page = $_POST['inner_count'] ?? Helper::get_option( 'stories_max_inner_items', 4 );
	$order         = explode( '-', Helper::get_option( 'stories_orderby' ) );

	// Always validate/sanitize input.
	if ( empty( $story_ids ) ) {
		wp_send_json_error( esc_html__( 'No stories found', 'blogsy' ) );
	}

	$query_args = [
		'post_type'           => 'post',
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
		'no_found_rows'       => true,
		'posts_per_page'      => absint( $post_per_page ),
		'orderby'             => $order[0] ?? 'date',
		'order'               => strtoupper( $order[1] ) ?? 'DESC',
	];

	$stories_elements = Helper::get_option( 'stories_elements' );

	$success_flag = false;

	ob_start();

	foreach ( $story_ids as $cat_id ) {
		if ( ! $cat_id ) {
			continue;
		}

		$query_args['cat'] = $cat_id;

		$query_instance = new WP_Query( apply_filters( 'blogsy_query_args_filter', $query_args ) );

		if ( $query_instance->have_posts() ) {
			$success_flag = true;
			?>
			<div class="stories-popup__story">
				<div class="stories-popup__story-wrap" data-id="<?php echo esc_attr( $cat_id ); ?>">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php
							while ( $query_instance->have_posts() ) :
								$query_instance->the_post();
								$has_thumb = has_post_thumbnail();
								?>
								<div class="stories-popup__slide post-item swiper-slide<?php echo ! $has_thumb ? ' no-thumb' : ''; ?>">
									<article class="post-wrapper">
										<a class="item-link blogsy-position-cover" aria-label="<?php esc_attr_e( 'Read More', 'blogsy' ); ?>" href="<?php echo esc_url( get_the_permalink() ); ?>"></a>
										<?php if ( $has_thumb ) : ?>
										<div class="image-wrapper">
											<?php the_post_thumbnail( 'full' ); ?>
										</div>
										<?php endif; ?>
										<div class="content-wrapper blogsy-position-bottom style-1">
											<div class="content-wrapper-inner">
												<?php if ( ! empty( $stories_elements['category'] ) ) : ?>
												<div class="terms-wrapper">
													<?php blogsy_entry_meta_category( ' ', false, apply_filters( 'blogsy_stories_category_limit', 5 ) ); ?>
												</div>
												<?php endif; ?>
												<?php if ( ! empty( $stories_elements['title'] ) ) : ?>
													<h4 class="title"><?php the_title(); ?></h4>
												<?php endif; ?>
												<?php if ( ! empty( $stories_elements['meta'] ) ) : ?>
													<div class="meta-wrapper">
														<?php blogsy_entry_meta_author(); ?>
														<?php blogsy_entry_meta_date(); ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</article>
								</div>
								<?php
							endwhile;
							?>
						</div>
						<div class="stories-popup__pagination swiper-pagination"></div>
						<div class="stories-popup__arrow swiper-button-prev"></div>
						<div class="stories-popup__arrow swiper-button-next"></div>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		}
	}

	$res = ob_get_clean();

	if ( $success_flag ) {
		wp_send_json_success( $res );
	} else {
		wp_send_json_error( esc_html__( 'No stories found', 'blogsy' ) );
	}
}
add_action( 'wp_ajax_blogsy_stories_ajax_call', 'blogsy_stories_ajax_call' );
add_action( 'wp_ajax_nopriv_blogsy_stories_ajax_call', 'blogsy_stories_ajax_call' );
