<?php
 /*
 * Template Name: Forum
 */
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			
			<?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); wp_reset_query(); ?>
                <?php endwhile; // end of the loop. ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>