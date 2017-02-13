<?php
$el_news_horizontal_number_posts = $el_news_horizontal_categories =  $el_news_horizontal_title = $border_color = $el_class = '';
$posts = array();
extract( shortcode_atts( array(
    'el_news_horizontal_title' => '',
    'el_news_horizontal_number_posts' => '',
    'el_class' => '',
    'el_news_horizontal_categories' => '',
    'border_color' => '',
), $atts ) );
if(empty($css))$css = '';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>
<div class="<?php echo esc_attr($css_class); if(!empty($el_class)) echo esc_attr($el_class); ?>">
    <div class="wpb_wrapper">
        <div class="title-wrapper">
            <h3 class="widget-title" style="border-color: <?php echo esc_attr($border_color); ?>"><i class="fa fa-newspaper-o"></i> <?php if(!empty($el_news_horizontal_title)) echo esc_attr($el_news_horizontal_title); ?></h3>
            <div class="clear"></div>
        </div>
        <?php
                    // Categories
                    $ct = array();
                    if ( $el_news_horizontal_categories != '' ) {
                        $el_news_horizontal_categories = explode( ",", $el_news_horizontal_categories );
                        foreach ( $el_news_horizontal_categories as $category ) {
                            array_push( $ct, $category );
                        }
                    }

                    $posts = new WP_Query(array(
                        'showposts' => $el_news_horizontal_number_posts,
                        'category__in' => $ct
                    ));
        ?>
        <div class="wcontainer">
          <ul class="newsbv">
          		<?php $k = 0; ?>
               <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
				<?php global $post; $categories = wp_get_post_categories($post->ID);  ?>
                	<?php $cat_data = get_option("category_$categories[0]");  ?>
                <li class="<?php if( $posts->current_post == 0 && !is_paged() ) { ?>newsbv-item-first<?php }else{ echo 'newsbv-item'; }?>">

                <div class="newsb-thumbnail">
                	<?php if($posts->current_post == 0){ ?>
					<?php $overall_rating = get_post_meta($post -> ID, 'overall_rating', true); ?>
			                	<?php
					if($overall_rating != "0" && $overall_rating=="0.5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star-half-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "1"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "1.5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star-half-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "2"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "2.5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-half-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "3"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "3.5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-half-o"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "4"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "4.5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star-half-o"></i>
					</div>
					<?php } ?>

					<?php
					if($overall_rating != "0" && $overall_rating == "5"){ ?>
					<div class="carousel_rating" style="color: <?php echo esc_attr($cat_data["catBG"]); ?>">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
					</div>
					<?php } ?>
				<?php } ?>

                <a rel="bookmark" href="<?php the_permalink(); ?>">
                	<i class="fa fa-hand-pointer-o" style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>"></i>


                    <?php if(has_post_thumbnail()){


                            $thumb = get_post_thumbnail_id();
                            $img_url = wp_get_attachment_url( $thumb,'full'); //get img URL

                            if($posts->current_post == 0){
                            $image = crystalskull_aq_resize( $img_url, 305, 200, true, '', true ); //resize & crop img

                                }else{
                            $image = crystalskull_aq_resize( $img_url, 120, 95, true, '', true ); //resize & crop img
                                }
                            ?>
                            <img src="<?php echo esc_url($image[0]); ?>" alt="<?php the_title(); ?>" />

                    <?php } else{
                                if($posts->current_post == 0){ ?>
                                <img src="<?php echo get_template_directory_uri().'/img/defaults/default.jpg'?> " alt="<?php the_title(); ?>" />
                            <?php }else{ ?>
                                <img src="<?php echo get_template_directory_uri().'/img/defaults/default.jpg'?> " alt="<?php the_title(); ?>" />
                    <?php   }

                     }  ?>

                    <div class="clear"></div>
                    <span class="overlay-link"></span>
                    <span class="line_effect" style="background: <?php echo esc_attr($cat_data['catBG']); ?>"></span>
                </a>
                </div>
                <?php if($k == 0){ ?>
               	<?php global $post; $categories = wp_get_post_categories($post->ID);  ?>
                	<?php $cat_data = get_option("category_$categories[0]");  ?>
                	<a href="<?php echo esc_url(get_category_link($categories[0])); ?>" class="ncategory" style="background-color: <?php echo esc_attr($cat_data['catBG']); ?> !important" >
       					<?php echo get_cat_name($categories[0]); ?>
					</a>
				<?php } ?>
                <h4 class="newsb-title">
                	 <?php if($k != 0){ ?>

                	<a href="<?php echo esc_url(get_category_link($categories[0])); ?>" class="ncategory" style="background-color: <?php echo esc_attr($cat_data['catBG']); ?> !important" >
       					<?php echo get_cat_name($categories[0]); ?>
					</a>
					<?php } ?>
                	<a rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p class="post-meta">

                	<?php if($posts->current_post == 0){ ?>
                	<?php echo get_avatar( get_the_author_meta('ID'), 60, '', 'author image', array('class' => 'authorimg') ); ?>
                	<?php } ?>

    	 				by <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php echo get_the_author(); ?></a> <?php esc_html_e('on', 'crystalskull'); ?> <?php the_time('F j, Y'); ?> <?php esc_html_e('with', 'crystalskull'); ?>
                      <?php if ( is_plugin_active( 'disqus-comment-system/disqus.php' )){ ?>
                        <a  href="<?php echo the_permalink(); ?>#comments" >
                        <?php comments_number( esc_html__('0', 'crystalskull'), esc_html__('1', 'crystalskull'), esc_html__('%', 'crystalskull') ) ?> <i class="fa fa-comments-o"></i></a>
                       <?php }else{ ?>
                        <a data-original-title="<?php comments_number( esc_html__('No comments in this post', 'crystalskull'), esc_html__('One comment in this post', 'crystalskull'), esc_html__('% comments in this post', 'crystalskull')); ?>" href="<?php echo the_permalink(); ?>#comments" data-toggle="tooltip">
                         <?php comments_number( esc_html__('0', 'crystalskull'), esc_html__('1', 'crystalskull'), esc_html__('%', 'crystalskull') ) ?> <i class="fa fa-comments-o"></i></a>

                       <?php } ?>

                </p>
				<div class="postbcont">
                	<?php global $more; $more = 1;  if ($k == 0) {the_excerpt();}else{ echo substr(get_the_excerpt(),0,100); echo '...'; } ?>
				</div>
                <div class="clear"></div>
                </li>


               <?php $k++; endwhile; ?>
                </ul>
            <div class="clear"></div>
        </div>
    </div>
</div>