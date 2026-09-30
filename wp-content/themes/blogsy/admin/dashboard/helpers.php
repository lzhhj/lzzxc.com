<?php
/**
 * Contains various functions that may be potentially used throughout
 * the theme.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if we're on a Blogsy admin page.
 *
 * @param boolean|string $base current screen base.
 * @param string         $slug page slug.
 * @since 1.0.0
 */
function blogsy_is_admin_page( $base = false, string $slug = 'blogsy' ): bool {

	if ( false === $base ) {
		$base = get_current_screen()->base;
	}

	return false !== strpos( $base, $slug );
}

/**
 * Print admin notice.
 *
 * @param array $args array of options.
 * @since 1.0.0
 */
function blogsy_print_notice( array $args ): ?bool {

	$defaults = [
		'type'           => 'success',
		'message'        => '',
		'is_dismissible' => true,
		'message_id'     => '',
		'expires'        => 0,
		'display_on'     => [],
		'action_link'    => '',
		'action_text'    => '',
		'dismiss_text'   => esc_html__( 'Dismiss', 'blogsy' ),
	];

	$args = wp_parse_args( $args, $defaults );

	if ( blogsy_is_notice_dismissed( $args['message_id'] ) ) {
		return false;
	}

	if ( ! empty( $args['display_on'] ) ) {

		$base    = get_current_screen()->base;
		$display = false;

		foreach ( $args['display_on'] as $page ) {
			if ( false !== strpos( $base, (string) $page ) ) {
				$display = true;
			}
		}

		if ( ! $display ) {
			return false;
		}
	}

	$blogsy_is_dismissible = $args['is_dismissible'] ? ' is-dismissible' : ''; ?>

	<div id="<?php echo esc_attr( $args['message_id'] ); ?>" class="notice blogsy-notice notice-<?php echo esc_attr( $args['type'] ); ?><?php echo esc_attr( $blogsy_is_dismissible ); ?>">
		<p><?php echo ( wp_kses_post( $args['message'] ) ); ?></p>

		<?php
		if ( $args['action_link'] && $args['action_text'] ) {
			?>
			<p class="blogsy-notice-action">
				<a href="<?php echo esc_url( $args['action_link'] ); ?>" class="blogsy-btn primary button button-primary" role="button"><?php echo esc_html( $args['action_text'] ); ?></a>

				<?php
				if ( $args['dismiss_text'] ) {
					?>
					<a href="#" class="blogsy-btn secondary button button-secondary blogsy-notice-dismiss" role="button"><?php echo esc_html( $args['dismiss_text'] ); ?></a>
					<?php
				}
				?>
			</p><!-- END .blogsy-notice-action -->
			<?php
		}
		?>
	</div>

	<script type="text/javascript">
		jQuery( document ).ready( function ( $ ) {

			var msgid = "<?php echo esc_attr( $args['message_id'] ); ?>";
			var $el   = $( '#' + msgid );

			$el.on( 'click', '.notice-dismiss, .blogsy-notice-dismiss', function ( event ) {

				var expires = "<?php echo esc_attr( $args['expires'] ); ?>";
				var nonce = "<?php echo esc_attr( wp_create_nonce( 'blogsy_dismiss_notice' ) ); ?>";

				$.post( ajaxurl, {
					action: 		'blogsy_dismiss_notice',
					msgid: 			msgid,
					expires: 		expires,
					_ajax_nonce: 	nonce,
				} );

				$el.fadeTo( 100, 0, function() {
					$el.slideUp( 100, function() {
						$el.remove();
					});
				});
			} );
		} );
	</script>

	<?php
		return null;
}

/**
 * Check if admin notice is dismissed.
 *
 * @param string $id Notice ID.
 * @since 1.0.0
 */
function blogsy_is_notice_dismissed( string $id ): bool {
	return false !== get_transient( 'blogsy_notice_' . $id );
}

/**
 * Ajax handler to dismiss admin notice.
 *
 * @since 1.0.0
 */
function blogsy_dismiss_notice(): void {

	check_ajax_referer( 'blogsy_dismiss_notice' );

	if ( ! isset( $_POST['msgid'] ) ) {
		die;
	}

	$message_id = sanitize_text_field( wp_unslash( $_POST['msgid'] ) );
	$expires    = isset( $post['expires'] ) ? intval( $post['expires'] ) : 0;

	$message              = (array) get_transient( 'blogsy_notice_' . $message_id );
	$message['time']      = time();
	$message['dismissed'] = true;

	set_transient( 'blogsy_notice_' . $message_id, $message, $expires );
	die;
}
add_action( 'wp_ajax_blogsy_dismiss_notice', 'blogsy_dismiss_notice' );

/**
 * Print admin icon.
 *
 * @param string $icon Icon name.
 * @param string $tooltip          Tooltip text.
 * @param string $tooltip_position Position of the tooltip.
 * @since 1.0.0
 */
function blogsy_print_admin_icon( string $icon = 'info', string $tooltip = '', string $tooltip_position = 'right-tooltip' ): void {

	$svg_icon = '<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"/></svg>';

	if ( '' !== $tooltip ) {
		$tooltip = '<span class="blogsy-tooltip ' . esc_attr( $tooltip_position ) . '">' . esc_html( $tooltip ) . '</span>';
	}

	if ( 'warning' === $icon ) {
		echo '<i class="blogsy-warning-icon">' . $svg_icon . $tooltip . '</i>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} elseif ( 'info' === $icon ) {
		echo '<i class="blogsy-info-icon">' . $svg_icon . $tooltip . '</i>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Check if currently using block editor page.
 *
 * @since 1.0.0
 * @return boolean
 */
function blogsy_is_block_editor(): bool {

	if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
		// The Gutenberg plugin is on.
		return true;
	}

	$current_screen = get_current_screen();
	// Gutenberg page on 5+.
	return method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();
}

/**
 * Print help icon with a link to documentation.
 *
 * @param array $args Optional parameters.
 * @param bool  $display Return or print the link.
 * @return string|null
 * @since  1.0.0
 */
function blogsy_help_link( array $args = [], bool $display = true ): ?string {

	if ( ! apply_filters( 'blogsy_display_help_links', true ) ) {
		return null;
	}

	$defaults = [
		'link'  => '',
		'class' => [],
	];

	$args = wp_parse_args( $args, $defaults );

	$args['class']   = (array) $args['class'];
	$args['class'][] = 'blogsy-help-link';

	$class = trim( implode( ' ', $args['class'] ) );

	$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>';

	$output = sprintf(
		'<a href="%1$s" rel="nofollow" target="_blank" class="%2$s"><span class="blogsy-help-icon">%4$s</span>%3$s</a>',
		esc_url( $args['link'] ),
		esc_attr( $class ),
		esc_html__( 'How to use', 'blogsy' ),
		$icon
	);

	if ( $display ) {
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $output;
	}
	return null;
}
