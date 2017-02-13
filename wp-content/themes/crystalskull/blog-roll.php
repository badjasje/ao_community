<div class="blog-post">

	<div class="blog-twrapper"><!-- blog-twrapper -->

		<div class="blog-image right"><!-- blog-image -->

		<?php
		if (has_post_thumbnail()) { ?>
			<?php
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
				$image = crystalskull_aq_resize( $img_url, 817, 320, true, '', true ); //resize & crop img
			?>
				<a href="<?php the_permalink(); ?>"><img alt="img"  src="<?php echo esc_url($image[0]); ?>" /></a>
		<?php }else{ ?>
				<a href="<?php the_permalink(); ?>"><img alt="default" src="<?php echo  get_template_directory_uri().'/img/defaults/default.jpg'; ?> " /></a>
		<?php } ?>



		<!-- blog-rating -->
		<?php require(get_template_directory() .'/rating.php'); ?>
		<!-- blog-rating -->
		<span class="overlay-link"></span>
		<i class="fa fa-hand-pointer-o" style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>"></i>
			<span class="line_effect" style="background: <?php echo esc_attr($cat_data['catBG']); ?>"></span>
		</div><!-- blog-image -->
		<?php if(!isset($key_1_value))$key_1_value= ''; ?>
		<div class="blog-content <?php if ( has_post_thumbnail() or  $key_1_value != '') {  }else{?> blog-content-no-img <?php } ?>"><!-- blog-content -->
			<h2>
				<a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a>
			</h2>
			<div class="post-pinfo">

			<a data-original-title="<?php esc_html_e("View all posts by", 'crystalskull'); ?> <?php echo esc_attr(get_the_author()); ?>" data-toggle="tooltip" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ))); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 60, '', 'author image', array('class' => 'authorimg') ); ?> by <?php echo esc_attr(get_the_author()); ?></a>
			<i>|</i>
			<?php $postcats = wp_get_post_categories($post->ID); if ($postcats) {?>  <?php foreach($postcats as $c) {$cat = get_category( $c ); ?>  <a href="<?php echo esc_url(get_category_link($cat->cat_ID)); ?>"> <?php echo esc_attr($cat->cat_name) . ' '; ?> </a><?php } ?> <i>|</i> <?php } ?>


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
			<?php the_excerpt(10); ?>

			<a href="<?php the_permalink(); ?>" class="button-small"><?php esc_html_e("Read more", 'crystalskull'); ?></a>
		</div><!-- blog-content -->

	</div><!-- /blog-twrapper -->




	<div class="clear"></div>
	<div class="block-divider"></div>
</div><!-- /.blog-post -->