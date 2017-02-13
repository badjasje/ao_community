<?php
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
			<?php echo get_the_content();
				echo '<pre>';
				print_r(spy_array);
				echo '</pre>';
				
			?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>