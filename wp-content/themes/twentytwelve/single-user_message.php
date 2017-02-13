<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_ID();
$author_id = $post->post_author; 

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
		<br/>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$invite_hash = get_post_meta(get_the_ID(),'invite_hash');
				
				if(empty($invite_hash[0])):?>
				<center><h1>Conversation</h1></center><br/>
			<?php 
				$mainmessage_ID = get_the_ID();
				
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
			
			?>
			
			<table style="margin-bottom:10px;margin-left:auto;margin-right:auto;max-width:500px;">
			<tr>
				<td><strong><center><?php if($sender_ID != $user_ID){echo $sender->display_name;?> (#<?php echo $sender->ID.')';}?>
</center></strong>
				</td>
			</tr>
			<tr>
				<td><div style="line-height: 1.9;"><?php echo str_replace("\r", "<br />", $message->post_content);?></div>
				</td>
			</tr>
		
		
		
			</table>
			
			<?php }?><hr/>
			<center><h2>Reply</h2></center><br/>
				<form class="form" action="<?php echo home_url() ?>/message.php" name="" id="message" method="post">
				<table style="margin-left:auto;margin-right:auto;max-width:450px;">

					<tr><input type="hidden" name="main_message" value="<?php echo $mainmessage_ID;?>">
						<td><center>
				​<textarea style="width:95%;"id="message" rows="10" name="message"placeholder="Your message..."cols="70"></textarea>
						</td></center>
					</tr>
				</table><br/>
				<center><input type="submit" value="Send reply"></center>
			</form>
			<?php endif;?>
			<?php if(!empty($invite_hash[0])):
				$clan_ID = get_post_meta(get_the_ID(),'clan_id_invited');
				$invite_status = get_post_meta(get_the_ID(),'invite_status');
			?>
			<center><h1>Clan invite</h1></center><br/>
			<?php if($user_ID == $author_id):
				$receiver_id = get_post_meta(get_the_ID(),'receiver_id');
				$user = get_userdata($receiver_id[0]);
				
			?>
			<center>You sent this invite to <a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' #('.$user->ID;?>)</a></center>
			<?php else:?>
			<?php if(!empty($invite_status[0])):?>
			<?php if($invite_status[0] == 'accept'):?>
			<center>
			<p>
			You have already used this clan invite.
			</p>
			</center>
			<?php endif;?>
			<?php else:?>
			<center><p>
			You've been invited to join <?php echo get_the_title($clan_ID[0]);?> (# <?php echo $clan_ID[0];?>). If you wish to accept this invite, hit the accept button.<br/><br/>
			<a href="/accept.php/?invite=<?php echo $invite_hash[0];?>&clan=<?php echo $clan_ID[0]?>&id=<?php echo get_the_ID();?>">ACCEPT</a> / 
			<a href="/decline.php/?invite=<?php echo $invite_hash[0];?>&clan=<?php echo $clan_ID[0]?>&id=<?php echo get_the_ID();?>">DECLINE</a>
			</p>
			</center>
			<?php endif;?>
			
			<?php endif;?><?php endif;?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>