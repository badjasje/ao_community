<?php 
/*
 * Template Name: Homepage2
 */	
get_header('loginhome'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); wp_reset_query(); ?>
                <?php endwhile; // end of the loop. ?>
            <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>