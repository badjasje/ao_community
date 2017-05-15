<?php get_header(); ?>
<?php if (class_exists('MultiPostThumbnails')) : wp_reset_postdata(); $custombck = MultiPostThumbnails::get_post_thumbnail_url(get_post_type(), 'header-image', $post->ID, 'full'); endif; ?>
<?php if(empty($custombck)){}else{ ?>
<style>
    body.page{
    background-image:url(<?php echo esc_url($custombck); ?>) !important;
    background-position:center top !important;
    background-repeat:  no-repeat !important;
}
</style>
<?php } ?>

<?php if ( is_plugin_active( 'buddypress/bp-loader.php' ) && bp_is_blog_page() ){?>
<div class="page normal-page ">
      <div class="container containerNZ">
        <div class="row">
            <?php if(is_active_sidebar( 'buddypress' )){ ?>
            <div class="col-lg-8 col-md-8">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); wp_reset_query(); ?>
                <?php endwhile; // end of the loop. ?>
            <div class="clear"></div>
            <?php if(comments_open()){ ?>
					<div id="comments"  class="block-divider"></div>
					<?php comments_template('/short-comments-blog.php'); ?>
				<?php } ?>
            </div><!-- /.col-lg-8 col-md-8 -->

              <div class="col-lg-4 col-md-4 ">
            <?php if ( function_exists('dynamic_sidebar')) : ?>
               <?php dynamic_sidebar('buddypress'); ?>
           <?php endif; ?>
    </div><!-- /.col-lg-4 col-md-4 -->

    <?php }else{ ?>

            <div class="col-lg-12 col-md-12">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); wp_reset_query(); ?>
                <?php endwhile; // end of the loop. ?>
            <div class="clear"></div>
            <?php if(comments_open()){ ?>
					<div id="comments"  class="block-divider"></div>
					<?php comments_template('/short-comments-blog.php'); ?>
				<?php } ?>
            </div><!-- /.col-lg-8 col-md-8 -->

        <?php } ?>
        </div>
     </div>
</div>

<?php }else{ ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php  the_content(); wp_reset_query(); ?>
                <?php endwhile; // end of the loop. ?>
            <div class="clear"></div>
            <?php if(comments_open()){ ?>
					<div id="comments"  class="block-divider"></div>
					<?php comments_template('/short-comments-blog.php'); ?>
				<?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php get_footer(); ?>