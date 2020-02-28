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
	<?/*<div class="blockHeader noticeBlock">
		<strong>Help & Fun?</strong>
		Join us on <a href="http://bit.ly/2US8Dh0" style="text-decoration:underline" target="_blank">discord</a> where the community really thrives!
	</div>*/?>
</div> <!-- end .pageRow forum -->

<?php
get_footer();