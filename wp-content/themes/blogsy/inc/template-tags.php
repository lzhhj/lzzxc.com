<?php
/**
 * Template tags used throughout the theme.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */

use Blogsy\Helper;
use Blogsy\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'blogsy_logo' ) ) :
	/**
	 * Outputs theme logo markup.
	 *
	 * @since 1.0.0
	 * @param bool $show_logo Print the logo or return as string.
	 * @return string|null
	 */
	function blogsy_logo( $show_logo = true ) {
		$display_site_description = Helper::get_option( 'display_tagline' );
		$site_title               = get_bloginfo( 'name' );
		$site_description         = get_bloginfo( 'description' );
		$site_url                 = home_url( '/' );

		$site_logo_output        = '';
		$site_title_output       = '';
		$site_description_output = '';

		// Check if a custom logo image has been uploaded.
		if ( has_custom_logo() ) {
			$default_logo     = (int) Helper::get_option( 'custom_logo', '' );
			$retina_logo      = Helper::get_option( 'logo_default_retina' );
			$dark_logo        = Helper::get_option( 'logo_dark' );
			$dark_retina_logo = Helper::get_option( 'logo_dark_retina' );

			$retina_logo      = isset( $retina_logo['background-image-id'] ) ? (int) $retina_logo['background-image-id'] : 0;
			$dark_logo        = isset( $dark_logo['background-image-id'] ) ? (int) $dark_logo['background-image-id'] : 0;
			$dark_retina_logo = isset( $dark_retina_logo['background-image-id'] ) ? (int) $dark_retina_logo['background-image-id'] : 0;

			$site_logo_output = blogsy_get_logo_img_output( $default_logo, $retina_logo, $dark_logo, $dark_retina_logo );

			// Allow logo output to be filtered.
			$site_logo_output = apply_filters( 'blogsy_logo_img_output', $site_logo_output );
		}

		// Set tag to H1 for home page, span for other pages.
		$site_title_tag = ( is_home() || is_front_page() ) ? 'h1' : 'span';
		$site_title_tag = apply_filters( 'blogsy_site_title_tag', $site_title_tag );

		$class = $site_logo_output ? ' screen-reader-text' : '';

		// Site Title HTML markup.
		$site_title_output = apply_filters(
			'blogsy_site_title_markup',
			sprintf(
				'<%1$s class="site-title%4$s" itemprop="name">
                    <a href="%2$s" rel="home" itemprop="url">
                        %3$s
                    </a>
                </%1$s>',
				tag_escape( $site_title_tag ),
				esc_url( $site_url ),
				esc_html( $site_title ),
				esc_attr( $class )
			)
		);

		// Output site description if enabled in Customizer.
		if ( ! empty( $site_description ) ) {
			$class = $display_site_description ? '' : ' screen-reader-text';

			$site_description_output = apply_filters(
				'blogsy_site_description_markup',
				sprintf(
					'<p class="site-description%2$s" itemprop="description">%1$s</p>',
					esc_html( $site_description ),
					esc_attr( $class )
				)
			);
		}

		$output = '<div class="logo-inner">' . $site_logo_output . $site_title_output . $site_description_output . '</div>';

		// Allow output to be filtered.
		$output = apply_filters( 'blogsy_logo_output', $output );

		// Echo or return the output.
		if ( $show_logo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return null;
		}

		return $output;
	}
endif;

if ( ! function_exists( 'blogsy_get_logo_img_output' ) ) :
	/**
	 * Outputs logo image markup.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $logo Attachment ID of the logo image.
	 * @param int    $retina Attachment ID of the retina logo image.
	 * @param int    $dark_logo Attachment ID of the dark logo image.
	 * @param int    $dark_retina_logo Attachment ID of the dark retina logo image.
	 * @param string $class Additional CSS class.
	 * @return string
	 */
	function blogsy_get_logo_img_output( int $logo, int $retina = 0, int $dark_logo = 0, int $dark_retina_logo = 0, string $class = '' ): string {

		// Early return if no logo is set.
		if ( ! $logo && ! $dark_logo ) {
			return '';
		}

		$logo_attr      = blogsy_logo_dark_logo( $logo );
		$dark_logo_attr = blogsy_logo_dark_logo( $dark_logo );

		// Build srcset attribute.
		$srcset      = '';
		$srcset_dark = '';

		if ( $logo && $retina && $logo_attr ) {
			$retina_logo_image = wp_get_attachment_image_url( $retina, 'full' );

			if ( $retina_logo_image ) {
				$srcset = sprintf(
					' srcset="%s 1x, %s 2x"',
					esc_attr( $logo_attr['url'] ),
					esc_attr( $retina_logo_image )
				);
			}
		}

		if ( $dark_logo && $dark_retina_logo && $dark_logo_attr ) {
			$dark_retina_logo_image = wp_get_attachment_image_url( $dark_retina_logo, 'full' );

			if ( $dark_retina_logo_image ) {
				$srcset_dark = sprintf(
					' srcset="%s 1x, %s 2x"',
					esc_attr( $dark_logo_attr['url'] ),
					esc_attr( $dark_retina_logo_image )
				);
			}
		}

		// Build the conditional dark logo image tag.
		$dark_logo_img_tag = '';
		if ( ! empty( $dark_logo_attr ) ) {
			$dark_logo_img_tag = sprintf(
				'<img src="%1$s" alt="%2$s" width="%3$s" height="%4$s" class="%5$s blogsy-logo-image dark-logo" itemprop="logo"%6$s />',
				esc_url( $dark_logo_attr['url'] ),
				esc_attr( $dark_logo_attr['alt'] ),
				esc_attr( $dark_logo_attr['width'] ),
				esc_attr( $dark_logo_attr['height'] ),
				esc_attr( $dark_logo_attr['class'] ),
				$srcset_dark
			);
		}

		// Build the main logo output.
		if ( empty( $logo_attr ) ) {
			return $dark_logo_img_tag;
		}

		return sprintf(
			'<a href="%1$s" rel="home" class="%2$s" itemprop="url">
                <img src="%3$s" alt="%4$s" width="%5$s" height="%6$s" class="%7$s blogsy-logo-image" itemprop="logo"%8$s />
                %9$s
            </a>',
			esc_url( home_url( '/' ) ),
			esc_attr( trim( $class ) ),
			esc_url( $logo_attr['url'] ),
			esc_attr( $logo_attr['alt'] ),
			esc_attr( $logo_attr['width'] ),
			esc_attr( $logo_attr['height'] ),
			esc_attr( $logo_attr['class'] ),
			$srcset,
			$dark_logo_img_tag
		);
	}
endif;

if ( ! function_exists( 'blogsy_logo_dark_logo' ) ) :
	/**
	 * Get logo attributes for dark logo and default logo.
	 *
	 * @since 1.0.0
	 *
	 * @param int $logo Attachment ID of the logo image.
	 * @return array|false Array of logo attributes or false if no logo is set.
	 */
	function blogsy_logo_dark_logo( int $logo ) {
		if ( 0 === $logo ) {
			return false;
		}

		// Get default logo src, width & height.
		$default_logo_attachment_src = wp_get_attachment_image_src( $logo, 'full' );

		if ( ! $default_logo_attachment_src ) {
			return false;
		}

		// Logo attributes.
		$logo_attr = [
			'url'    => $default_logo_attachment_src[0],
			'width'  => $default_logo_attachment_src[1],
			'height' => $default_logo_attachment_src[2],
			'class'  => '',
			'alt'    => '',
		];

		// Check if uploaded logo is SVG.
		$mimes     = [ 'svg' => 'image/svg+xml' ];
		$file_type = wp_check_filetype( $logo_attr['url'], $mimes );

		if ( 'svg' === $file_type['ext'] ) {
			$logo_attr['width']  = '100%';
			$logo_attr['height'] = '100%';
			$logo_attr['class']  = 'blogsy-svg-logo';
		}

		// Get default logo alt.
		$default_logo_alt = get_post_meta( $logo, '_wp_attachment_image_alt', true );
		$logo_attr['alt'] = $default_logo_alt ?: get_bloginfo( 'name' );

		return $logo_attr;
	}
endif;

if ( ! function_exists( 'blogsy_top_bar_widget_text' ) ) :
	/**
	 * Outputs the top bar text widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_top_bar_widget_text( array $options ): void {
		$content = $options['content'] ?? '';
		$content = apply_filters( 'blogsy_dynamic_strings', $content );

		echo '<span>' . wp_kses( do_shortcode( $content ), blogsy_get_allowed_html_tags() ) . '</span>';
	}
endif;

if ( ! function_exists( 'blogsy_top_bar_widget_nav' ) ) :
	/**
	 * Outputs the top bar navigation widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of navigation widget options.
	 */
	function blogsy_top_bar_widget_nav( array $options ): void {
		$defaults = [
			'menu_id'     => 'blogsy-topbar-nav',
			'container'   => false,
			'menu_class'  => 'blogsy-header-nav',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'menu'        => '',
		];

		$options = wp_parse_args( $options, $defaults );
		$options = apply_filters( 'blogsy_top_bar_navigation_args', $options );

		if ( empty( $options['menu'] ) ) {
			if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
				?>
				<ul>
					<li class="blogsy-empty-nav">
						<?php
						if ( is_customize_preview() ) {
							esc_html_e( 'Menu not assigned', 'blogsy' );
						} else {
							?>
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=blogsy_top_bar_widgets' ) ); ?>">
								<?php esc_html_e( 'Assign a menu', 'blogsy' ); ?>
							</a>
						<?php } ?>
					</li>
				</ul>
				<?php
			}
			return;
		}

		$options['before_nav'] = '<nav class="blogsy-header-nav-wrapper" role="navigation" aria-label="' . esc_attr( $options['menu'] ) . '">';
		$options['after_nav']  = '</nav>';

		blogsy_navigation( $options );
	}
endif;

if ( ! function_exists( 'blogsy_top_bar_widget_socials' ) ) :
	/**
	 * Outputs the top bar social links widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_top_bar_widget_socials( array $options ): void {
		blogsy_social_links( $options );
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_text' ) ) :
	/**
	 * Outputs the header text widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_text( array $options ): void {
		blogsy_top_bar_widget_text( $options );
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_advertisements' ) ) :
	/**
	 * Outputs the header advertisements widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_advertisements( array $options ): void {
		$content = $options['content'] ?? '';
		$content = apply_filters( 'blogsy_dynamic_strings', $content );

		echo '<span>' . wp_kses( do_shortcode( $content ), blogsy_get_allowed_html_tags() ) . '</span>';
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_socials' ) ) :
	/**
	 * Outputs the header social links widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_socials( array $options ): void {
		blogsy_social_links( $options );
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_darkmode' ) ) :
	/**
	 * Outputs the header dark mode widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_darkmode( array $options ): void {
		?>
		<div class="dark-mode-switcher" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'blogsy' ); ?>">
			<span class="switcher-wrap">
				<span class="light-icon">
					<?php echo Icon::get_svg( 'sun', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<span class="dark-icon">
					<?php echo Icon::get_svg( 'moon', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</span>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_offcanvas' ) ) :
	/**
	 * Outputs the header offcanvas widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_offcanvas( array $options ): void {
		get_template_part( 'template-parts/header/widgets/offcanvas', '', $options );
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_search' ) ) :
	/**
	 * Outputs the header search widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_search( array $options ): void {
		get_template_part( 'template-parts/header/widgets/search', '', $options );
	}
endif;

if ( ! function_exists( 'blogsy_header_widget_button' ) ) :
	/**
	 * Outputs the header button widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_header_widget_button( array $options ): void {
		$class = [ $options['class'] ?? '' ];

		if ( isset( $options['style'] ) ) {
			$class[] = $options['style'];
		}

		$class[] = 'blogsy-btn button';

		$class = apply_filters( 'blogsy_header_widget_button_class', $class );
		$class = trim( implode( ' ', array_filter( $class ) ) );

		$text   = empty( $options['text'] ) ? __( 'Add Button Text', 'blogsy' ) : $options['text'];
		$target = isset( $options['target'] ) && '_blank' === $options['target']
			? 'target="_blank" rel="noopener noreferrer"'
			: 'target="_self"';

		echo wp_kses(
			sprintf(
				'<a href="%1$s" class="%2$s" %3$s role="button"><span>%4$s</span></a>',
				esc_url( $options['url'] ?? '#' ),
				esc_attr( $class ),
				$target,
				wp_kses( $text, blogsy_get_allowed_html_tags( 'post' ) )
			),
			blogsy_get_allowed_html_tags()
		);
	}
endif;

if ( ! function_exists( 'blogsy_copyright_widget_text' ) ) :
	/**
	 * Outputs the copyright text widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_copyright_widget_text( array $options ): void {
		blogsy_top_bar_widget_text( $options );
	}
endif;

if ( ! function_exists( 'blogsy_copyright_widget_nav' ) ) :
	/**
	 * Outputs the copyright navigation widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_copyright_widget_nav( array $options ): void {
		$defaults = [
			'menu_id'     => 'blogsy-footer-nav',
			'container'   => false,
			'menu_class'  => 'blogsy-header-nav',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'menu'        => '',
		];

		$options = wp_parse_args( $options, $defaults );
		$options = apply_filters( 'blogsy_copyright_navigation_args', $options );

		if ( empty( $options['menu'] ) ) {
			if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
				?>
				<ul>
					<li class="blogsy-empty-nav">
						<?php
						if ( is_customize_preview() ) {
							esc_html_e( 'Menu not assigned', 'blogsy' );
						} else {
							?>
							<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[control]=blogsy_copyright_widgets' ) ); ?>">
								<?php esc_html_e( 'Assign a menu', 'blogsy' ); ?>
							</a>
						<?php } ?>
					</li>
				</ul>
				<?php
			}
			return;
		}

		$options['before_nav'] = '<nav role="navigation" class="blogsy-header-nav-wrapper">';
		$options['after_nav']  = '</nav>';

		blogsy_navigation( $options );
	}
endif;

if ( ! function_exists( 'blogsy_copyright_widget_socials' ) ) :
	/**
	 * Outputs the copyright social links widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_copyright_widget_socials( array $options ): void {
		blogsy_social_links( $options );
	}
endif;

if ( ! function_exists( 'blogsy_footer_widgets' ) ) :
	/**
	 * Outputs the footer widgets.
	 *
	 * @since 1.0.0
	 */
	function blogsy_footer_widgets(): void {
		$footer_layout  = Helper::get_option( 'footer_layout' );
		$column_classes = blogsy_get_footer_column_class( $footer_layout );
		$divider_style  = Helper::get_option( 'divider_style' );
		?>
		<div id="blogsy-footer-widgets" class="pt-row default-footer-main">
			<?php
			if ( is_array( $column_classes ) && [] !== $column_classes ) {
				foreach ( $column_classes as $i => $column_class ) {
					$sidebar_id = 'blogsy-footer-' . ( $i + 1 );
					?>
					<div class="blogsy-footer-column <?php echo esc_attr( $column_class ); ?>">
						<?php
						if ( is_active_sidebar( $sidebar_id ) ) {
							dynamic_sidebar( $sidebar_id );
						} elseif ( current_user_can( 'edit_theme_options' ) ) {
							$sidebar_name = blogsy_get_sidebar_name_by_id( $sidebar_id );
							?>
							<div class="widget_block blogsy-no-widget">
								<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
									<div class="divider divider-1"></div>
									<div class="divider divider-2"></div>
									<h4 class="title">
										<span class="title-inner">
											<span class="title-text"><?php echo esc_html( $sidebar_name ); ?></span>
										</span>
									</h4>
									<div class="divider divider-3"></div>
									<div class="divider divider-4"></div>
								</div>
								<p class='no-widget-text'>
									<?php if ( is_customize_preview() ) { ?>
										<a href='#' class="blogsy-set-widget" data-sidebar-id="<?php echo esc_attr( $sidebar_id ); ?>">
									<?php } else { ?>
										<a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>'>
									<?php } ?>
										<?php esc_html_e( 'Click here to assign a widget.', 'blogsy' ); ?>
									</a>
								</p>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'blogsy_ad_widget_advertisements' ) ) :
	/**
	 * Outputs the advertisement widget.
	 *
	 * @since 1.0.0
	 * @param array $options Array of widget options.
	 */
	function blogsy_ad_widget_advertisements( array $options ): void {
		?>
		<div class="blogsy-banner">
			<?php
			if ( isset( $options['ad_type'] ) && 'banner' === $options['ad_type'] ) {
				$image_html = wp_get_attachment_image( $options['image_id'], 'full' );

				if ( isset( $options['url'] ) && '' !== $options['url'] ) {
					printf(
						'<a href="%s" target="%s" rel="noopener noreferrer">%s</a>',
						esc_url( $options['url'] ),
						esc_attr( $options['target'] ?? '_self' ),
						wp_kses_post( $image_html )
					);
				} else {
					echo wp_kses_post( $image_html );
				}
			} elseif ( isset( $options['content'] ) ) {
				?>
				<div class="blogsy-adsense"><?php echo wp_kses_post( do_shortcode( $options['content'] ) ); ?></div>
				<?php
			}
			?>
		</div><!-- .blogsy-banner -->
		<?php
	}
endif;

if ( ! function_exists( 'blogsy_random_post_archive_advertisement_part' ) ) :
	/**
	 * The template tag for displaying advertisement in random post archives.
	 *
	 * @since 1.0.0
	 * @param int|null $ad_to_rendered Index of the ad to be rendered.
	 */
	function blogsy_random_post_archive_advertisement_part( ?int $ad_to_rendered ): void {
		if ( null === $ad_to_rendered ) {
			return;
		}

		$ad_widgets = array_values(
			array_filter(
				(array) Helper::get_option( 'ad_widgets' ),
				fn( array $widget ): bool => isset( $widget['values']['display_area'] )
					&& in_array( 'random_post_archives', $widget['values']['display_area'], true )
			)
		);

		if ( [] === $ad_widgets || ! isset( $ad_widgets[ $ad_to_rendered ] ) ) {
			return;
		}

		$classes   = [];
		$classes[] = 'blogsy-banner-widget__' . esc_attr( $ad_widgets[ $ad_to_rendered ]['type'] );
		$classes[] = 'blogsy-banner-widget';

		if ( isset( $ad_widgets[ $ad_to_rendered ]['values']['visibility'] ) && $ad_widgets[ $ad_to_rendered ]['values']['visibility'] ) {
			$classes[] = 'blogsy-' . esc_attr( $ad_widgets[ $ad_to_rendered ]['values']['visibility'] );
		}

		$classes = apply_filters( 'blogsy_ad_widget_advertisements_classes', $classes, $ad_widgets[ $ad_to_rendered ] );
		$classes = trim( implode( ' ', $classes ) );

		printf( '<div class="%s">', esc_attr( $classes ) );
		blogsy_ad_widget_advertisements( $ad_widgets[ $ad_to_rendered ]['values'] );
		echo '</div>';
	}
endif;

if ( ! function_exists( 'blogsy_social_links' ) ) :
	/**
	 * The template tag for displaying social icons.
	 *
	 * @param array $args Args for wp_nav_menu function.
	 * @since 1.0.0
	 */
	function blogsy_social_links( array $args = [] ): void {
		$defaults = [
			'fallback_cb'     => '',
			'menu'            => '',
			'container'       => 'nav',
			'container_class' => 'blogsy-social-icons-widget',
			'menu_class'      => 'blogsy-social-icons',
			'depth'           => 1,
			'link_before'     => '<span class="screen-reader-text">',
			'link_after'      => '</span>' . Icon::get_svg( 'external-link', 'social', [ 'aria-hidden' => 'true' ] ),
			'style'           => '',
			'size'            => '',
			'align'           => '',
		];

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'blogsy_social_links_args', $args );

		// Add style class to container_class.
		if ( ! empty( $args['style'] ) ) {
			$args['container_class'] .= ' ' . esc_attr( $args['style'] );
		}

		// Add alignment class to menu_class.
		if ( ! empty( $args['align'] ) ) {
			$args['menu_class'] .= ' ' . esc_attr( $args['align'] );
		}

		// Add size class to container_class.
		if ( ! empty( $args['size'] ) ) {
			$args['container_class'] .= ' blogsy-' . esc_attr( $args['size'] );
		}

		if ( ! empty( $args['menu'] ) && is_nav_menu( $args['menu'] ) ) {
			wp_nav_menu( $args );
			return;
		}

		// Default social menu fallback.
		$container_class = 'blogsy-social-icons-widget ' . esc_attr( $args['style'] ) . ' blogsy-' . esc_attr( $args['size'] );
		$social_links    = [
			'facebook'  => 'https://www.facebook.com/',
			'twitter'   => 'https://twitter.com/',
			'telegram'  => 'https://t.me/',
			'instagram' => 'https://www.instagram.com/',
			'youtube'   => 'https://youtube.com/',
		];
		?>
		<nav class="<?php echo esc_attr( trim( $container_class ) ); ?>">
			<ul id="menu-social-menu-default" class="blogsy-social-icons">
				<?php foreach ( $social_links as $network => $url ) : ?>
					<li class="menu-item">
						<a href="<?php echo esc_url( $url ); ?>">
							<span class="screen-reader-text"><?php echo esc_html( $url ); ?></span>
							<span class="<?php echo esc_attr( $network ); ?>">
								<?php echo Icon::get_svg( $network, 'social', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
	}
endif;

if ( ! function_exists( 'blogsy_navigation' ) ) :
	/**
	 * The template tag for displaying navigation menus.
	 *
	 * @since 1.0.0
	 * @param array $args Args for wp_nav_menu function.
	 */
	function blogsy_navigation( array $args = [] ): void {
		$defaults = [
			'before_nav' => '',
			'after_nav'  => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$args['items_wrap'] = $args['items_wrap'] ?? '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$args['items_wrap'] = $args['before_nav'] . $args['items_wrap'] . $args['after_nav'];

		$args = apply_filters( 'blogsy_navigation_args', $args );

		if ( ! empty( $args['menu'] ) && is_nav_menu( $args['menu'] ) ) {
			wp_nav_menu( $args );
		}
	}
endif;

/**
 * Outputs the header widgets in Header Widget Locations.
 *
 * @since 1.0.0
 * @param array $locations Array of widget locations.
 * @param array $all_widgets Array of all registered widgets.
 */
function blogsy_header_widget_output( array $locations, array $all_widgets ): void {
	$header_widgets = $all_widgets;
	$header_class   = '';

	if ( [] !== $locations ) {
		$header_widgets = [];

		foreach ( $locations as $location ) {
			$header_class                = ' blogsy-widget-location-' . $location;
			$header_widgets[ $location ] = [];

			foreach ( $all_widgets as $widget ) {
				if ( $location === $widget['values']['location'] ) {
					$header_widgets[ $location ][] = $widget;
				}
			}
		}
	}

	echo '<div class="pt-header-widgets pt-header-element' . esc_attr( $header_class ) . '">';

	foreach ( $header_widgets as $location => $widgets ) {
		do_action( 'blogsy_header_widgets_before_' . $location );

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				if ( function_exists( 'blogsy_header_widget_' . $widget['type'] ) ) {
					$classes   = [];
					$classes[] = 'pt-header-widget__' . esc_attr( $widget['type'] );
					$classes[] = 'pt-header-widget';

					if ( isset( $widget['values']['visibility'] ) && $widget['values']['visibility'] ) {
						$classes[] = 'blogsy-' . esc_attr( $widget['values']['visibility'] );
					}

					$classes = apply_filters( 'blogsy_header_widget_classes', $classes, $widget );
					$classes = trim( implode( ' ', $classes ) );

					printf( '<div class="%s"><div class="blogsy-widget-wrapper">', esc_attr( $classes ) );
					call_user_func( 'blogsy_header_widget_' . $widget['type'], $widget['values'] );
					echo '</div></div><!-- END .pt-header-widget -->';
				}
			}
		}

		do_action( 'blogsy_header_widgets_after_' . $location );
	}

	echo '</div><!-- END .pt-header-widgets -->';
}

if ( ! function_exists( 'blogsy_entry_meta_category' ) ) :
	/**
	 * Prints HTML with meta information for the categories.
	 *
	 * @since 1.0.0
	 * @param string $sep Category separator.
	 * @param bool   $show_icon Show an icon for the meta detail.
	 * @param int    $limit_categories Limit the number of categories to display. -1 for all.
	 * @param bool   $return Return or output.
	 * @return string|null
	 */
	function blogsy_entry_meta_category( string $sep = ', ', bool $show_icon = true, int $limit_categories = -1, bool $return = false ) {
		$categories = get_the_category();

		if ( ! $categories ) {
			return null;
		}

		// Limit the number of categories if $limit_categories is provided.
		if ( $limit_categories > 0 ) {
			$categories = array_slice( $categories, 0, $limit_categories );
		}

		$category_links = [];
		foreach ( $categories as $category ) {
			$category_links[] = sprintf(
				'<a href="%s" class="term-item term-id-%d" rel="category"><span>%s</span></a>',
				esc_url( get_category_link( $category->term_id ) ),
				absint( $category->term_id ),
				esc_html( $category->name )
			);
		}

		$categories_list = implode( $sep, $category_links );

		// Icon.
		$icon = $show_icon ? Icon::get_svg( 'categories', 'ui', [ 'aria-hidden' => 'true' ] ) : '';

		$output = wp_kses(
			apply_filters(
				'blogsy_entry_meta_category',
				$icon . $categories_list
			),
			blogsy_get_allowed_html_tags()
		);

		if ( $return ) {
			return $output;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return null;
	}
endif;

if ( ! function_exists( 'blogsy_entry_meta_author' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 *
	 * @since 1.0.0
	 * @param bool $return Return or output.
	 * @return string|null
	 */
	function blogsy_entry_meta_author( bool $return = false ) {
		$author_id    = get_the_author_meta( 'ID' );
		$author_url   = get_author_posts_url( $author_id );
		$author_name  = get_the_author();
		$author_email = get_the_author_meta( 'user_email' );
		$avatar       = get_avatar( $author_email, 60, '', $author_name );

		ob_start();
		?>
		<div class="post-author-wrapper">
			<div class="author-image">
				<?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="author-wrapper">
				<div class="author-meta">
					<span class="by"><?php esc_html_e( 'By', 'blogsy' ); ?></span>
					<a href="<?php echo esc_url( $author_url ); ?>">
						<?php echo esc_html( $author_name ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
		$output = ob_get_clean();

		if ( $return ) {
			return $output;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return null;
	}
endif;

if ( ! function_exists( 'blogsy_entry_meta_date' ) ) :
	/**
	 * Displays the post date with optional icon, human-readable time, and clock time.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Array of arguments to control date display.
	 *
	 *     @type bool   $show_date         Whether to show the date. Default true.
	 *     @type bool   $show_date_icon    Whether to show the calendar icon. Default false.
	 *     @type bool   $show_time         Whether to show the post time. Default false.
	 *     @type string $human_diff_time   Use 'yes' for human time diff, anything else for standard date. Default 'no'.
	 *     @type bool   $tag               Whether to include full HTML tags. Default true.
	 * }
	 * @param bool  $return Whether to return or echo the output.
	 * @return string|null
	 */
	function blogsy_entry_meta_date( array $args = [], bool $return = false ) {
		$defaults = apply_filters(
			'blogsy_entry_meta_date',
			[
				'show_date'       => true,
				'show_date_icon'  => false,
				'show_time'       => false,
				'human_diff_time' => 'no',
				'tag'             => true,
			]
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! $args['show_date'] ) {
			return null;
		}

		$date_display = ( 'yes' === $args['human_diff_time'] )
			// translators: %s: human time difference.
			? sprintf( esc_html__( '%s ago', 'blogsy' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )
			: get_the_date();

		$time_display = $args['show_time'] ? ' - ' . get_the_time() : '';

		// If no tags wanted, return plain string.
		if ( ! $args['tag'] ) {
			$output = '';

			if ( $args['show_date_icon'] ) {
				$output .= Icon::get_svg( 'calendar', '', [ 'aria-hidden' => 'true' ] );
			}

			$output .= $date_display . $time_display;

			if ( $return ) {
				return $output;
			}

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return null;
		}

		// Full markup.
		ob_start();
		?>
		<div class="date-wrapper">
			<?php if ( $args['show_date_icon'] ) : ?>
				<?php echo Icon::get_svg( 'calendar', '', [ 'aria-hidden' => 'true' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
			<span class="date">
				<?php echo esc_html( $date_display ); ?>
				<?php if ( $args['show_time'] ) : ?>
					<span class="time"><?php echo esc_html( $time_display ); ?></span>
				<?php endif; ?>
			</span>
		</div>
		<?php
		$output = ob_get_clean();

		if ( $return ) {
			return $output;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return null;
	}
endif;
