<footer>
	<div class="container">
		<center>Current date/time is <strong><?php echo date("d-m-Y | G:i", strtotime('+2 hours')); ?></strong>
		<?php if (is_user_logged_in() ) :?>
		<br/><a href="<?php echo wp_logout_url( get_permalink(3491) ); ?>">Logout</a>
		<?php endif;?>
		</center>
		<?php if ( function_exists( 'dynamic_sidebar' ) && is_active_sidebar( 'footer' ) ) : ?>

			<?php dynamic_sidebar( 'footer' ); ?>

		<?php endif; ?>

	</div>
</footer>

<div class="copyright">
	<div class="container">

	<p>© <?php echo date("Y"); ?>&nbsp;

		<?php if(of_get_option('copyright')!=""){
			 echo of_get_option('copyright'); ?>
		<?php } ?>

	<?php if(of_get_option('terms')!= "" or of_get_option('termsname') != ""){ ?> || <?php } ?>


	<?php if(of_get_option('terms') != ""){?> <a href="<?php echo esc_url(of_get_option('terms')); ?>"> <?php } ?>

	<?php if(of_get_option('termsname')!=""){ echo of_get_option('termsname');} ?>

	<?php if(of_get_option('terms')!=""){?> </a> <?php } ?>
	&nbsp;

	<div class="social">
	<?php if ( of_get_option('rss') ) { ?> <a class="rss" target="_blank" href="<?php  echo esc_url(of_get_option('rss_link'));  ?>"><i class="fa fa-rss"></i> </a><?php } ?>
	<?php if ( of_get_option('dribbble') ) { ?> <a class="dribbble" target="_blank" href="<?php  echo esc_url(of_get_option('dribbble_link'));  ?>"><i class="fa fa-dribbble"></i> </a><?php } ?>
	<?php if ( of_get_option('vimeo') ) { ?> <a class="vimeo" target="_blank" href="<?php echo esc_url(of_get_option('vimeo_link'));   ?>"><i class="fa fa-vimeo-square"></i> </a><?php } ?>
	<?php if ( of_get_option('youtube') ) { ?> <a class="youtube" target="_blank" href="<?php echo esc_url(of_get_option('youtube_link'));   ?>"><i class="fa fa-youtube"></i> </a><?php } ?>
	<?php if ( of_get_option('twitch') ) { ?> <a class="twitch" target="_blank" href="<?php echo esc_url(of_get_option('twitch_link'));   ?>"><i class="fa fa-twitch"></i></a><?php } ?>
	<?php if ( of_get_option('steam') ) { ?> <a class="steam" target="_blank" href="<?php echo esc_url(of_get_option('steam_link'));   ?>"><i class="fa fa-steam"></i></a><?php } ?>
	<?php if ( of_get_option('pinterest') ) { ?> <a class="pinterest" target="_blank" href="<?php  echo esc_url(of_get_option('pinterest_link'));   ?>"><i class="fa fa-pinterest"></i> </a><?php } ?>
	<?php if ( of_get_option('googleplus') ) { ?> <a class="google-plus" target="_blank" href="<?php echo esc_url(of_get_option('google_link'));   ?>"><i class="fa fa-google-plus"></i></a><?php } ?>
	<?php if ( of_get_option('twitter') ) { ?> <a class="twitter" target="_blank" href="<?php  echo esc_url(of_get_option('twitter_link'));   ?>"><i class="fa fa-twitter"></i></a><?php } ?>
	<?php if ( of_get_option('facebook') ) { ?> <a class="facebook" target="_blank" href="<?php echo esc_url(of_get_option('facebook_link'));   ?>"><i class="fa fa-facebook"></i></a><?php } ?>
	</div>

	</div>
</div>


</div> <!-- End of container -->
<?php 
	$user_ID = get_current_user_id();
	$new_events = get_user_meta($user_ID, 'new_events',true);
	$new_messages = get_user_meta($user_ID, 'new_messages',true);
	$new_globals = get_user_meta($user_ID, 'new_global_events',true);
	wp_footer(); ?>
	
<?php /* if(!empty($new_globals) and $new_globals != 0):?>
<script type="text/javascript">
jQuery( ".menu-item-7706" ).append( "<span class='bluepulse'><a class='bluepulse' href='/events/global/'><?php echo $new_globals;?> new globals</a></span>" );
</script>
<?php endif; */?>


<?php if(!empty($new_events) and $new_events != 0):?>
<script type="text/javascript">
jQuery( ".menu-item-7706" ).append( "<span class='redpulse'><?php echo $new_events;?></span>" );
</script>

<script type="text/javascript">
jQuery( ".shiftnav-toggle" ).append( "<span class='redpulse2'><?php echo $new_messages+$new_events;?></span>" );
</script>

<?php endif;?>
<?php if(!empty($new_messages) and $new_messages != 0):?>
<script type="text/javascript">
jQuery( ".menu-item-3658" ).append( "<span class='redpulse'><?php echo $new_messages;?></span>" );

</script>

<script type="text/javascript">
jQuery( ".shiftnav-toggle" ).append( "<span class='redpulse2'><?php echo $new_messages+$new_events;?></span>" );
</script>

<?php endif;?>
<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40825301-45', 'auto');
  ga('set', 'userId', <?php echo $user_ID;?>); // De gebruikers-ID instellen op basis van de ingelogde user_id.
  ga('send', 'pageview');

</script>
</body></html>