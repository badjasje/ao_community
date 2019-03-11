<?php
if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )):
    global $userId;
    global $userData;
?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->


    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">

        <div class="container-fluid p-3 p-md-5">

            <div class="row no-gutters footer-nav">
                <div class="col-md-3">
                    <h4>Beginners</h4>
                    <ul class="footer-list">
                        <li><a href="http://bit.ly/2US8Dh0" target="_blank"><strong>Join discord channel!</strong></a></li>
                        <li><a href="<?php echo get_site_url(); ?>/getting-started">Getting started manual</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/manual">Complete Manual</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/rules">Rules</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4>Toplists</h4>
                    <ul class="footer-list">
                        <li><a href="<?php echo get_site_url(); ?>/toplists/?tab=provicenw">Highest nw</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/toplists/?tab=clanpoints">Clan points</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/toplists/?tab=clannw">Clan nw</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/toplists/?tab=clanpointstoday">Clan pts today</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4>Information</h4>
                    <ul class="footer-list">
                        <li><a href="<?php echo get_site_url(); ?>/forum">Forum</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/category/awards-medals/">Awards & Medals</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/all-clans">Clan list</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/users">User list</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4><?php echo $userData['nickname'][0]; ?></h4>
                    <ul class="footer-list">
                        <li><a href="<?php echo get_site_url(); ?>/users/profile/?id=<?php echo $userId; ?>">Profile</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/player-statistics">Statistics</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/users/profile/?id=<?php echo $userId; ?>">Set push notifications</a></li>
                    </ul>
                </div>
            </div>

            <hr size="1">

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