<div class="blog-post"><!-- blog-post -->

	<div class="blog-info"><!-- blog-info -->
		<div class="post-pinfo">

			<a data-original-title="<?php esc_html_e("View all posts by", 'crystalskull'); ?> <?php echo esc_attr(get_the_author()); ?>" data-toggle="tooltip" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ))); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 60, '', 'author image', array('class' => 'authorimg') ); ?> by <?php echo esc_attr(get_the_author()); ?></a>
			<i>|</i>
			<?php $postcats = wp_get_post_categories($post->ID); if ($postcats) {?>  <?php foreach($postcats as $c) {$cat = get_category( $c ); ?>  <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"> <?php echo esc_attr($cat->cat_name) . ' '; ?> </a><?php } ?> 	<i>|</i> <?php } ?>


			<?php if ( is_plugin_active( 'disqus-comment-system/disqus.php' )){ ?>
	        <a  href="<?php echo the_permalink(); ?>#comments" >
	        <?php comments_number( esc_html__('0 Comments', 'crystalskull'), esc_html__('1 Comment', 'crystalskull'), esc_html__('% Comments', 'crystalskull') ) ?> </a>
	       <?php }else{ ?>
	        <a data-original-title="<?php comments_number( esc_html__('No comments in this post', 'crystalskull'), esc_html__('One comment in this post', 'crystalskull'), esc_html__('% comments in this post', 'crystalskull')); ?>" href="<?php echo the_permalink(); ?>#comments" data-toggle="tooltip">
	         <?php comments_number( esc_html__('0 Comments', 'crystalskull'), esc_html__('1 Comment', 'crystalskull'), esc_html__('% Comments', 'crystalskull') ) ?></a>

	       <?php } ?>

			<i>|</i>
	       <span class="date"> <?php the_time('d'); ?> <?php the_time('M'); ?> <?php the_time('Y'); ?></span>

		</div>
	<div class="clear"></div>
	</div><!-- blog-info -->


	<!-- post ratings -->
    <?php
    $overall_rating = get_post_meta($post->ID, 'overall_rating', true);
    $rating_one = get_post_meta($post->ID, 'creteria_1', true);
    $rating_two = get_post_meta($post->ID, 'creteria_2', true);
    $rating_three = get_post_meta($post->ID, 'creteria_3', true);
    $rating_four = get_post_meta($post->ID, 'creteria_4', true);
    $rating_five = get_post_meta($post->ID, 'creteria_5', true);

    if($overall_rating== NULL or $rating_one== NULL && $rating_two== NULL && $rating_three== NULL && $rating_four== NULL && $rating_five== NULL ){}else{ require(get_template_directory() .'/post-rating.php'); } ?><!-- /post ratings -->


	<div class="blog-content wcontainer"><!-- /.blog-content -->
		<?php the_content();?>
	</div><!-- /.blog-content -->

	<div class="clear"></div>
</div><!-- /.blog-post -->