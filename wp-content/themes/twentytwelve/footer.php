<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<center>Current date/time is <strong><?php echo date("d-m-Y | G:i:s"); ?></strong>
		<?php if (is_user_logged_in() ) :?>
		<br/><a href="<?php echo wp_logout_url(); ?>">Logout</a></center>
		<?php endif;?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php 
	$user_ID = get_current_user_id();
	$new_events = get_user_meta($user_ID, 'new_events',true);
	$new_messages = get_user_meta($user_ID, 'new_messages',true);
	$new_globals = get_user_meta($user_ID, 'new_global_events',true);
	wp_footer(); ?>
	
<?php if(!empty($new_events) and $new_events != 0):?>
<script type="text/javascript">
jQuery( ".menu-item-7706" ).append( "<span class='redpulse'><?php echo $new_events[0];?></span>" );
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





</body>
</html>