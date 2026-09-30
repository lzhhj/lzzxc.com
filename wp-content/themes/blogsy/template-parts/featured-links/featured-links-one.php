<?php
/**
 * The template for displaying Featured Item.
 *
 * @package    Blogsy
 * @author     Peregrine Themes
 * @since      1.0.0
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style                  = $args['style'] ?? '';
$column                 = $args['column'] ?? '';
$feature_title          = $args['title'] ?? '';
$feature_links          = $args['features'] ?? [];
$blogsy_image_animation = Helper::get_option( 'posts_animation' );
$divider_style          = Helper::get_option( 'divider_style' );

$column_class = ! empty( $column ) ? ' pt-grid-col-' . intval( $column ) : '';
?>

<div class="blogsy-featured featured-links-<?php echo esc_attr( $style ); ?>">
	<div class="pt-container">
		<div class="pt-row">
			<div class="pt-col-12">
				<div class="blogsy-card-items">
					<?php if ( $feature_title ) : ?>
					<div class="blogsy-section-heading pt-mb-2">
						<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
							<div class="divider divider-1"></div>
							<div class="divider divider-2"></div>
							<h4 class="title">
								<span class="title-inner">
									<span class="title-text"><?php echo esc_html( $feature_title ); ?></span>
								</span>
							</h4>
							<div class="divider divider-3"></div>
							<div class="divider divider-4"></div>
						</div>
					</div>
					<?php endif; ?>
					<div class="blogsy-categories-box<?php echo esc_attr( $column_class ); ?>">
						<?php
						foreach ( $feature_links as $key => $feature_link ) :
							$classes       = 'cat-item ' . ( 'one' === $style ? 'card-layout' : 'simple-layout' );
							$color         = ! empty( $feature_link['color'] ) ? '--pt-accent-color: ' . esc_attr( $feature_link['color'] ) . '; --pt-accent-40-color: ' . esc_attr( blogsy_luminance( $feature_link['color'], .40 ) ) . '; --pt-accent-80-color: ' . esc_attr( blogsy_luminance( $feature_link['color'], .80 ) ) . ';' : '';
							$a_link        = $feature_link['link']['url'] ?? '';
							$a_link_title  = $feature_link['link']['title'] ?? '';
							$a_link_target = $feature_link['link']['target'] ?? '_self';
							?>
						<div id="blogsy-featured-link-<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( $color ); ?>">
							<div class="image-wrapper<?php echo $blogsy_image_animation ? ' has-animation' : ''; ?>">
								<?php
								if ( ! empty( $feature_link['image']['id'] ) ) :
									echo wp_get_attachment_image( $feature_link['image']['id'], 'blogsy-small-square' );
								endif;
								?>
								<?php if ( $blogsy_image_animation ) : ?>
								<div class="image-animation">
									<?php
									if ( ! empty( $feature_link['image']['id'] ) ) :
										echo wp_get_attachment_image( $feature_link['image']['id'], 'blogsy-small-square' );
									endif;
									?>
								</div>
								<?php endif; ?>
								<?php if ( $a_link ) : ?>
									<a href="<?php echo esc_url( $a_link ); ?>" title="<?php echo esc_attr( $a_link_title ); ?>" target="<?php echo esc_attr( $a_link_target ); ?>" class="img-link blogsy-position-cover"></a>
								<?php endif; ?>
							</div>
							<?php if ( $a_link_title ) : ?>
							<div class="content-wrapper">
								<div class="title">
									<a href="<?php echo esc_url( $a_link ); ?>" title="<?php echo esc_attr( $a_link_title ); ?>" target="<?php echo esc_attr( $a_link_target ); ?>">
										<?php echo esc_html( $a_link_title ); ?>
									</a>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
