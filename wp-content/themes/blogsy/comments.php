<?php
/**
 * Template part for displaying comments list and form
 *
 * @package Blogsy
 */

use Blogsy\Helper;

if ( Helper::get_option( 'disable_comments' ) ) {
	return;
}

$comments_count = get_comments_number();

// If comments are not open and there is no comment, return.
if ( ! comments_open() && ! $comments_count ) {
	return;
}


/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$compact_view  = Helper::get_option( 'compact_comments' ) && ( $comments_count > 4 );
$divider_style = Helper::get_option( 'divider_style' );
?>

<div class="comments-container" id="responds">
	<div class="comments-area card-layout-w">
		<div class="comments-title">
			<div class="blogsy-divider-heading divider-style-<?php echo esc_attr( $divider_style ); ?>">
				<div class="divider divider-1"></div>
				<div class="divider divider-2"></div>
				<h5 class="title">
					<span class="title-inner">
						<span class="title-text">
							<?php
							if ( 0 == $comments_count ) {
								echo esc_html( Helper::get_option( 'translate_no_comment_be_the_first' ) ) ?: esc_html__( 'No Comment! Be the first one.', 'blogsy' );
							} elseif ( 1 == $comments_count ) {
								echo esc_html( Helper::get_option( 'translate_one_comment' ) ) ?: esc_html__( 'One Comment', 'blogsy' );
							} else {
								echo intval( $comments_count ) . ' ';
								echo esc_html( Helper::get_option( 'translate_comments' ) ) ?: esc_html__( 'Comments', 'blogsy' );
							}
							?>
						</span>
					</span>
				</h5>
				<div class="divider divider-3"></div>
				<div class="divider divider-4"></div>
			</div>
		</div>
		<div class="comment-list-wrapper
		<?php
		if ( $compact_view ) {
			echo ' compact';}
		?>
		">
			<ol class="comment-list">
				<?php
				wp_list_comments(
					[
						'avatar_size' => 60,
						'style'       => 'ol',
					]
				);
				?>
			</ol><!-- .comment-list -->
			<?php
			the_comments_pagination(
				[
					'prev_text' => esc_html__( 'Older comments', 'blogsy' ),
					'next_text' => esc_html__( 'Newer comments', 'blogsy' ),
				]
			);
			?>
			<?php
			if ( $compact_view ) {
				$label = Helper::get_option( 'translate_show_comments' ) ?: esc_html__( 'Show Comments', 'blogsy' );
				echo '<span class="comments-compact-btn">' . esc_html( $label ) . '</span>';
			}
			?>
		</div>

		<?php if ( ! comments_open() ) : ?>
			<p class="comments-closed"><?php esc_html_e( 'Comments are closed.', 'blogsy' ); ?></p>
		<?php endif; ?>


		<?php
		$commenter = wp_get_current_commenter();

		$req                 = get_option( 'require_name_email' );
		$aria_req            = $req ? ' required' : '';
		$req_sign            = $req ? ' *' : '';
		$placeholder_name    = Helper::get_option( 'translate_name' ) ?: esc_attr__( 'Name', 'blogsy' );
		$placeholder_email   = Helper::get_option( 'translate_email' ) ?: esc_attr__( 'Email', 'blogsy' );
		$placeholder_website = Helper::get_option( 'translate_website' ) ?: esc_attr__( 'Website', 'blogsy' );
		$fields              = [
			'author' => '<input class="form-author" id="author" name="author" type="text" size="30" maxlength="245" placeholder="' . esc_attr( $placeholder_name ) . $req_sign . '" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' >',
			'email'  => '<input class="form-email" id="email" name="email" type="email" size="30" maxlength="200" placeholder="' . esc_attr( $placeholder_email ) . $req_sign . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" ' . $aria_req . ' >',
			'url'    => '<input class="form-website" id="url" name="url" type="url" size="30" maxlength="200" placeholder="' . esc_attr( $placeholder_website ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" >',
		];

		$fields              = apply_filters( 'comment_form_default_fields', $fields );
		$placeholder_comment = Helper::get_option( 'translate_comment' ) ?: esc_html__( 'Comment', 'blogsy' );

		$comments_args = [
			'fields'        => $fields,
			'comment_field' => '<textarea id="comment" name="comment" rows="8" class="form-textarea" placeholder="' . esc_attr( $placeholder_comment ) . '" required></textarea>',
			'format'        => 'html5',
		];

		comment_form( $comments_args );
		?>
	</div>
</div>
