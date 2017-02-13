<div class="col-lg-12 col-md-12 ">
	<div class="bwrap <?php if(is_page_template('tmp-blog-isotope.php'))echo 'isoblog'; ?>">
		<?php
	        $showposts = get_option('posts_per_page');
			$author = get_query_var('author');
			$tag = get_query_var('tag' );
			$category = get_query_var('cat' );
			$s = get_search_query();
	        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	        $new_query = new WP_Query();
	        if ( is_search() ) {
	        	$new_query->query( 'cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged.'&s='.$s  );
			}else{
				$new_query->query( 'cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged);
			}
	    ?>
		<?php if ( $new_query->have_posts() ) : while ( $new_query->have_posts() ) : $new_query->the_post(); ?>

			<?php require(get_template_directory() .'/blog-roll.php'); ?>

			<?php endwhile; ?>
			<?php else : ?>
				<?php if(is_search()){ ?>
				<div class="psearch-content">
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'crystalskull' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'crystalskull' ); ?></p>
				</div><!-- .entry-content -->
				<?php }elseif(is_category()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'crystalskull' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts for this category yet.', 'crystalskull' ); ?></p>
				<?php }elseif(is_tag()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'crystalskull' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts  for this tag yet.', 'crystalskull' ); ?></p>
				<?php }elseif(is_author()){ ?>
					<h4 class="entry-title"><?php esc_html_e( 'Nothing Found', 'crystalskull' ); ?></h4>
					<p><?php esc_html_e( 'Sorry, but there are no posts for this author yet.', 'crystalskull' ); ?></p>
				<?php } ?>
			<?php endif; ?>
	</div><!-- /.bwrap -->
	<div class="pagination">
		<ul>
				<?php
				if ( is_search() ) {
						$additional_loop = new WP_Query('cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged.'&s='.$s  );
					}else {
						$additional_loop = new WP_Query('cat='.$category.'&tag='.$tag.'&showposts='.$showposts.'&author='.$author.'&paged='.$paged);
					}
				$page=$additional_loop->max_num_pages;
				echo crystalskull_kriesi_pagination($additional_loop->max_num_pages);	?>
				<?php wp_reset_query(); ?>
		</ul>
	</div>
	<div class="clear"></div>
</div><!-- /.span12 -->