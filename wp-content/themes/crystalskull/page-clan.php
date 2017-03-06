<?php
 /*
 * Template Name: Clan
 */
 $user_ID = get_current_user_ID();
 $clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_leader = get_post_meta($clan_id_user, 'clan_leader',true);
 $ct_1 = get_post_meta($clan_id_user,'ct_1',true);
 $ct_2 = get_post_meta($clan_id_user,'ct_2',true);
 $ct_3 = get_post_meta($clan_id_user,'ct_3',true);
 $ct_4 = get_post_meta($clan_id_user,'ct_4',true);
 
 $allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clan_leader);
get_header('clan'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice"></div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">This clan name already exists</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice">Your clan was deleted</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">You cannot do that</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice">You left your clan</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">New clan leader set</div>
			<?php endif;?><?php endif;?>
		<?php if($clan_id_user == 0):?>
		
		<div class="notice_message">You are currently not in a clan! Create a clan or join one.</div>
		<br/>
		
		
		<form action="<?php echo home_url() ?>/clan.php" name="" id="clan" method="post">
		
			<input class="small_input" type="text" name="clanname" minlength="3" maxlength="20" id="clanname" placeholder="Clan Name. Max 20 characters."><br/><br/>
			<input class="small_input" type="text" name="clantag" id="clantag" maxlength="5"placeholder="Clan Tag. Max 5 characters."><br/><br/>
			<textarea rows="5" class="small_input" type="text" name="clanmessage" id="clanmessager" placeholder="Clan Message"></textarea>
			
		<input type="submit"  value="Create your clan" class="">
		</form>
		<?php else:?>

		<table class="responsive-table">
			<tr>
				<td><center><a href="<?php echo get_the_permalink($clan_id_user);?>">Clan information</a></center></td>
			</tr>
			<?php if(in_array($user_ID, $allowed)):?>
			<tr>
				<td><center><a href="/edit-clan">Edit clan</a></center></td>
			</tr>
			<?php endif;?>
			<?php if(in_array($user_ID, $allowed)):?>
			<tr>
				<td><center><a href="/open-invites">Open invites</a></center></td>
			</tr>
			<?php endif;?>
			<tr>
				<td><center><a href="/clan-member-information">Clan member information</a></center></td>
			</tr>
			
			<tr>
				<td><center><a href="/clan-wars">Clan wars</a></center></td>
			</tr>
			<tr>
				<td><center><a href="/bonus-overview/">Bonus overview</a></center></td>
			</tr>
			<tr>
				<td><center><a href="/send-aid">Send aid</a></center></td>
			</tr>
			<?php if($clan_leader == $user_ID):?>
			<tr>
				<td><center><a style="color:#ff0000;font-weight:bold;" href="/delete.php/?clan=<?php echo $clan_id_user;?>" onclick="return confirm('Are you sure you want to delete your clan?')">Delete clan</a></center></td>
			</tr>
			<?php else: ?>
			<tr>
				<td><center><a href="/leave.php/?user=<?php echo $user_ID;?>" onclick="return confirm('Are you sure you want to leave your clan?')">Leave clan</a></center></td>
			</tr>
			<?php endif;?>
		</table>
		
		<?php endif;?>
			
			
			
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>