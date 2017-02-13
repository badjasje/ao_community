<?php
/*
 * Template name: Blog - Right Sidebar
*/
?>
<?php get_header();?>
<?php if (class_exists('MultiPostThumbnails')) : wp_reset_postdata(); $custombck = MultiPostThumbnails::get_post_thumbnail_url(get_post_type(), 'header-image', $post->ID, 'full'); endif; ?>
<?php if(empty($custombck)){}else{ ?>
<?php require_once(get_template_directory() .'/css/header-image-page.css.php'); ?>
<?php } ?>
<!-- Page content
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->
<div class="blog">

	<div class="container ">

		<div class="row">

			<?php require_once(get_template_directory() .'/blog-roll-right-tmp.php'); ?>

		</div><!-- /row -->

	</div><!-- /container -->

</div><!-- /blog -->

<?php get_footer(); ?>