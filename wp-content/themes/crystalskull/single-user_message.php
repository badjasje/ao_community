<?php 
	$user_ID = get_current_user_ID();
$author_id = $post->post_author; 
$mainmessage_ID = get_the_ID();
$receiver_main 	= get_post_meta($mainmessage_ID, 'receiver_id', true);
$sender_main	= get_post_meta($mainmessage_ID, 'sender_id', true);
$messagearray = array($receiver_main,$sender_main,1);
if(!in_array($user_ID, $messagearray)){
	wp_redirect(get_permalink(3656));exit;
}
	get_header(); ?>


<div class="page normal-page">
	<div class="container containerNZ">
	<div class="row">

		
			<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$invite_hash = get_post_meta(get_the_ID(),'invite_hash');
				
				if(empty($invite_hash[0])):?>
			<?php 
				
				
				update_post_meta($mainmessage_ID, 'general_status', 'Read');
				
				$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'sub_user_message',
			'meta_key'		=> 'parent_message_id',
			'meta_value'	=> $mainmessage_ID
			);
			$messages = get_posts($args);
			foreach ($messages as $message) {
			$sender_ID = get_post_meta($message->ID, 'sender_id');
			$sender = get_userdata( $sender_ID[0] );
			
			$receiver_id = get_post_meta($message->ID, 'receiver_id')[0];
		
			if($receiver_id == $user_ID){
				update_post_meta($message->ID, 'receiver_status', 'Read');
			}
			
			if($sender_ID != $user_ID){
				$sender_ID = $sender->ID;
			}
			
			
			
		
		?>
		
		
		
			
		
<div class="row">
<div class="col-md-2"></div>

<div class="col-md-8">
	<ul class="single_inbox_message media-list">
		<li class="media ">
			<div class="media-left">
				<div class="leftAvatar"><?php echo small_avatar($sender_ID,'');?></div>
			</div>
			
			<div class="media-body">
				<h4 class="media-heading"><?php echo LinkUtil::user_link($sender_ID); ?></h4>
				<?php echo str_replace("\r", "<br />", $message->post_content);?>
    		</div>
		</li>
	</ul>
</div>

<div class="col-md-2"></div>
</div> <!-- /row -->
	
			
<?php }?>


<div class="row">
<div class="col-md-2"></div>

<div class="col-md-8">
	<div class="message_field">
		<form class="form" action="<?php echo home_url() ?>/message.php" name="" id="message" method="post">
			<input type="hidden" name="main_message" value="<?php echo $mainmessage_ID;?>">
			​<textarea id="message" required rows="10" name="message" placeholder="Your message..."></textarea>
			
				<input class="submitBtn" type="submit" value="Send">
	
		</form>
	</div>
	
</div>

<div class="col-md-2"></div>
</div><!-- /row -->
		<script>
			jQuery(document).ready(function () {
			jQuery("#message").submit(function () {
	        jQuery(".submitBtn").attr("disabled", true);
	        return true;
	    	});
		});
		</script>


				
			<?php endif;?>
			<?php if(!empty($invite_hash[0])):
				$clan_ID = get_post_meta(get_the_ID(),'clan_id_invited');
				$invite_status = get_post_meta(get_the_ID(),'invite_status');
			?>
			<?php if($user_ID == $author_id):
				$receiver_id = get_post_meta(get_the_ID(),'receiver_id');
				$user = get_userdata($receiver_id[0]);
				
			?>
			<center>You sent this invite to <a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' #('.$user->ID;?>)</a></center>
			<?php else:?>
			<?php if(!empty($invite_status[0])):?>
			<?php if($invite_status[0] == 'accept'):?>
		
			<div class="notice_message">You have already used this clan invite.</div>
			<?php endif;?>
			<?php else:?>
			
			<div class="notice_message">You've been invited to join <?php echo get_the_title($clan_ID[0]);?> (# <?php echo $clan_ID[0];?>). If you wish to accept this invite, hit the accept button.</div><br/><center>
			<a class="btn-general btn"href="/accept.php/?invite=<?php echo $invite_hash[0];?>&clan=<?php echo $clan_ID[0]?>&id=<?php echo get_the_ID();?>">ACCEPT</a>
			<a class="btn-general btn"href="/decline.php/?invite=<?php echo $invite_hash[0];?>&clan=<?php echo $clan_ID[0]?>&id=<?php echo get_the_ID();?>">DECLINE</a></center>
			</p>
			</center>
			<?php endif;?>
			
			<?php endif;?><?php endif;?>
			<?php endwhile; // end of the loop. ?>
		
	

	</div><!-- /row -->
	</div>
</div><!-- /containerblog -->

<?php get_footer(); ?>