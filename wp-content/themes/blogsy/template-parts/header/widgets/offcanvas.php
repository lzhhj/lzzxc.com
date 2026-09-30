<?php
/**
 * Offcanvas sidebar template part.
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="blogsy-offcanvas">
	<div class="offcanvas-opener-wrapper">
		<span class="offcanvas-opener" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Open Offcanvas Sidebar', 'blogsy' ); ?>">
			<span class="hamburger">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</span>
	</div>
	<div class="offcanvas-wrapper position-left">
		<div class="offcanvas-container">
			<div class="offcanvas-container-inner">
				<div class="offcanvas-close" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'Close Offcanvas Sidebar', 'blogsy' ); ?>">
					<span class="cross-line top-left"></span>
					<span class="cross-line top-right"></span>
					<span class="cross-line bottom-left"></span>
					<span class="cross-line bottom-right"></span>
				</div>
				<div class="offcanvas-content">
					<?php
					if ( is_active_sidebar( 'offcanvas-sidebar' ) ) {
						dynamic_sidebar( 'offcanvas-sidebar' );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
