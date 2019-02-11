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
                    Your local date/time is <strong><script type="text/javascript">
                    var x = new Date().toString();
                    y = x.substr(1,4)+"-";
                    document.write(x);
                    </script></strong>.
                    <br/>Current server date/time is <strong><?php echo date("d-m-Y | G:i", strtotime('+1 hours')); ?></strong><br/>
                    <?php if(is_user_logged_in()):?>
                        <a href="<?php echo wp_logout_url( get_site_url()."/home/" ); ?>">Logout</a>
                    <?php endif;?>
                </center>
            </div><!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>