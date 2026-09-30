<?php
/**
 * Template name: Stories
 *
 * @package Blogsy
 */

get_header();

$stories_args               = blogsy_get_stories_data();
$categories                 = get_categories(
	[
		'hide_empty' => true,
	]
);
$stories_args['categories'] = $categories;

?>
<div id="blogsy-stories" class="pt-mb-3 pt-pb-3">
	<?php get_template_part( 'template-parts/stories/stories', 'one', $stories_args ); ?>
</div>
<?php
get_footer();
