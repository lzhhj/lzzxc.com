<?php
/**
 * The template for displaying Featured Category style "one".
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
$features               = $args['features'] ?? [];
$blogsy_image_animation = Helper::get_option( 'posts_animation' );
$divider_style          = Helper::get_option( 'divider_style' );

$column_class = ( 'three' !== $style && ! empty( $column ) ) ? ' pt-grid-col-' . intval( $column ) : '';
?>

<div class="blogsy-featured featured-category-<?php echo esc_attr( $style ); ?>">
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
						foreach ( $features as $key => $feature ) :
							if ( empty( $feature['category'] ) || ! is_numeric( $feature['category'] ) ) {
								continue;
							}
							$category_id = intval( $feature['category'] );
							$cat_info    = get_term( $category_id, 'category' );
							if ( is_wp_error( $cat_info ) || empty( $cat_info->term_id ) ) {
								continue;
							}
							$cat_link            = get_term_link( $category_id, 'category' );
							$feature_color_style = ! empty( $feature['color'] ) ? '--pt-accent-color: ' . esc_attr( $feature['color'] ) . ';' : '';
							$classes             = 'cat-item card-layout term-id-' . esc_attr( $category_id );
							?>
						<div id="blogsy-featured-category-<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="<?php echo esc_attr( $feature_color_style ); ?>">
							<div class="image-wrapper<?php echo $blogsy_image_animation ? ' has-animation' : ''; ?>">
								<?php
								if ( ! empty( $feature['image']['id'] ) ) :
									echo wp_get_attachment_image( $feature['image']['id'], 'blogsy-small-square' );
								endif;
								?>
								<?php if ( $blogsy_image_animation ) : ?>
								<div class="image-animation">
									<?php
									if ( ! empty( $feature['image']['id'] ) ) :
										echo wp_get_attachment_image( $feature['image']['id'], 'blogsy-small-square' );
									endif;
									?>
								</div>
								<?php endif; ?>
								<?php if ( ! empty( $cat_info->name ) ) : ?>
									<a href="<?php echo esc_url( $cat_link ); ?>" title="<?php echo esc_attr( $cat_info->name ); ?>" class="img-link blogsy-position-cover"></a>
								<?php endif; ?>
							</div>

							<div class="content-wrapper">
								<div class="title">
									<a href="<?php echo esc_url( $cat_link ); ?>" title="<?php echo esc_attr( $cat_info->name ); ?>">
										<?php echo esc_html( $cat_info->name ); ?>
									</a>
								</div>
								<div class="count-wrapper">
									<span class="count">
										<?php
										echo esc_html(
											sprintf(
												// translators: 1: Number of posts.
												_n( '%d Post', '%d Posts', $cat_info->count, 'blogsy' ),
												$cat_info->count
											)
										);
										?>
									</span>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
