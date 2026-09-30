<?php
/**
 * Template part for displaying default page 404
 *
 * @package Blogsy
 */

use Blogsy\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="pt-container">
	<div class="pt-row page-content-wrapper">
		<div class="content-container">
			<div class="page-404" >
				<h1><?php echo esc_html__( '404', 'blogsy' ); ?></h1>
				<p>
					<?php echo esc_html( Helper::get_option( 'translate_looks_like_nothing_found' ) ) ?: esc_html__( 'It looks like nothing was found here!', 'blogsy' ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( Helper::get_option( 'translate_return_home' ) ) ?: esc_html__( 'Return to Home Page', 'blogsy' ); ?></a>
				</p>
			</div>
		</div>
	</div>
</div>
