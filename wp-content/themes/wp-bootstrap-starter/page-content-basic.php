<?php
 /*
 * Template Name: Basic content page
*/

get_header(); ?>

<div class="row pageRow">	
<?php while ( have_posts() ) : the_post(); ?>
<?php  the_content(); wp_reset_query(); ?>
<?php endwhile; // end of the loop. ?>
</div> <!-- end .pageRow -->
<?php
get_footer();