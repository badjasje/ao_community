<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">
		<div class="container-fluid p-3 p-md-5">
            <div class="site-info">
	            <center>
                &copy; <?php echo date('Y'); ?> <?php echo '<a href="'.home_url().'">'.get_bloginfo('name').'</a>'; ?>
                <span class="sep"> | </span>
                Your local date/time is <strong><?php echo '<script type="text/javascript">
                var x = new Date().toString();
                y = x.substr(1,4)+"-";
                document.write(x);
                </script>';?></strong>.
                <br/>Current server date/time is <strong><?php echo date("d-m-Y | G:i", strtotime('+1 hours')); ?></strong><br/>
                <a href="<?php echo wp_logout_url( get_permalink(3491) ); ?>">Logout</a></center>

            </div><!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
<script>
	(function($) {
$(document).ready(function() {
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
	
	$.getJSON('<?php echo get_site_url();?>/checkevents.php', function(data) {

			var globals = data.globals; 
			var locals = data.locals; 
			var messages = data.messages; 
			
			
		    if (globals > 1){ 
		    	$('.globalsBadge').text(globals);
				$('.globalsBadge').show(100);
				$('title').text(globals+' new global events');
			}
			if (locals > 1){ 
		    	$('.localsBadge').text(locals);
				$('.localsBadge').show(100);
			}
			if (messages > 1){ 
		    	$('.inboxBadge').text(messages);
				$('.inboxBadge').show(100);
			}

            
            
            });




});		
		
		

	function myFunction() {
		$.getJSON('<?php echo get_site_url();?>/checkevents.php', function(data) {

			var globals = data.globals; 
			var locals = data.locals; 
			var messages = data.messages; 
			
			
		    if (globals > 1){ 
		    	$('.globalsBadge').text(globals);
				$('.globalsBadge').show(100);
				$('title').text(globals+' new global events');
			}
			if (locals > 1){ 
		    	$('.localsBadge').text(locals);
				$('.localsBadge').show(100);
			}
			if (messages > 1){ 
		    	$('.inboxBadge').text(messages);
				$('.inboxBadge').show(100);
			}

            
            
            });

    }


var i = setInterval(function() { myFunction(); }, 10000);

})(jQuery);
</script>

</body>
</html>