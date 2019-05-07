<?php
 /*
 * Template Name: Centered content page
*/

get_header(); ?>

<div class="row pageRow">	
<div class="col-md-3" style="padding:0px;"></div>
<div class="col-md-6" style="padding:0px;">
<?php while ( have_posts() ) : the_post(); ?>
<?php  the_content(); wp_reset_query(); ?>
<?php endwhile; // end of the loop. ?>
</div>
<div class="col-md-3" style="padding:0px;"></div>
</div> <!-- end .pageRow -->
<?php
get_footer();