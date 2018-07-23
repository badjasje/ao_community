<?php
 /*
 * Template Name: Forum
*/

get_header(); ?>

<div class="row pageRow">
	<?php
		while ( have_posts() ) : the_post();

		the_content();

		endwhile; // End of the loop.
		?>
</div> <!-- end .pageRow -->
<?php
get_footer();