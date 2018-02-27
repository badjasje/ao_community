<footer>
	<center>
	<div class="container">
        <div class="datetime">Your local date/time is <strong><?php echo '<script type="text/javascript">
                var x = new Date().toString();
                y = x.substr(1,4)+"-";
                document.write(x);
                </script>';?></strong>.<br/>

            Current server date/time is <strong><?php echo date("d-m-Y | G:i", strtotime('+1 hours')); ?></strong>
		<?php if (is_user_logged_in() ) :?>
		<script>
	(function($) {
	function myFunction() {
		$.get('<?php echo get_site_url();?>/checkevents.php', function(data) {
			var newevents = data; 
			var onlynumbers = newevents.match(/\d+/); // 123456
			$('title').text(newevents);
		    if (onlynumbers > 1){ 
		    $('.globalNew').text(onlynumbers);
		    $( ".globalNew" ).addClass( "redNotify" );
			}

            
            
            });

    }

var i = setInterval(function() { myFunction(); }, 10000);

})(jQuery);
</script>

		<br/><a href="<?php echo wp_logout_url( get_permalink(3491) ); ?>">Logout</a>
		<?php endif;?>
		<br/><br/>
		<a target="_blank" href="https://www.facebook.com/assault.online/">
			<i class="fa fa-facebook-official" aria-hidden="true"></i>
		</a>
		</div>

	</div>
</center>
</footer>

<div class="copyright">
	<div class="container">

	<p>© <?php echo date("Y"); ?>&nbsp;

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
<script type="text/javascript">if(typeof wabtn4fg==="undefined"){wabtn4fg=1;h=document.head||document.getElementsByTagName("head")[0],s=document.createElement("script");s.type="text/javascript";s.src="/wp-content/themes/crystalskull/js/whatsapp-button.js";h.appendChild(s);}</script>
</body></html>