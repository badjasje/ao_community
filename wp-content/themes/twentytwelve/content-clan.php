<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 $user_ID = get_current_user_ID();
 $clan_id_user = get_user_meta($user_ID, 'clan_id_user');
$clan_leader = get_post_meta($clan_id_user[0], 'clan_leader');
 $ct_1 = get_post_meta($clan_id_user[0],'ct_1')[0];
 $ct_2 = get_post_meta($clan_id_user[0],'ct_2')[0];
 $ct_3 = get_post_meta($clan_id_user[0],'ct_3')[0];
 $ct_4 = get_post_meta($clan_id_user[0],'ct_4')[0];
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
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
				<div class="marketnotice">Units ordered</div>
			<?php endif;?><?php endif;?>
		<?php if($clan_id_user[0] == 0):?>
		<center><h1>Clan</h1></center>
		<center>
		<p>You are currently not in a clan! Create a clan or join one.</p></center><br/>
		
		
		
		<form action="<?php echo home_url() ?>/clan.php" name="" id="clan" method="post">
		<table>
			<tr>
				<td><center><strong>Clan name</strong></center><input class="small_input" type="text" name="clanname" id="clanname">
				</td>
			</tr>
			<tr>
				<td><center><strong>Clan tag</strong></center><input class="small_input" type="text" name="clantag" id="clantag">
				</td>
			</tr>
			<tr>
				<td><center><strong>Clan message</strong></center><textarea rows="5" class="small_input" type="text" name="clanmessage" id="clanmessager"></textarea>
				</td>
			</tr>
		</table>
		<center><input type="submit"  value="Create your clan" class=""></center>
		</form>
		<?php else:?>
		<center><h1><?php echo get_the_title($clan_id_user[0]).' (#'.$clan_id_user[0];?>)</h1></center>
		<table>
			<tr>
				<td><center><a href="<?php echo get_the_permalink($clan_id_user[0]);?>">Clan information</a></center></td>
			</tr>
			<?php if($clan_leader[0] == $user_ID):?>
			<tr>
				<td><center><a href="/edit-clan">Edit clan</a></center></td>
			</tr>
			<?php endif;?>
			<?php if($clan_leader[0] == $user_ID || $user_ID == $ct_1 || $user_ID == $ct_2 || $user_ID == $ct_3 || $user_ID == $ct_4):?>
			<tr>
				<td><center><a href="/open-invites">Open invites</a></center></td>
			</tr>
			<?php endif;?>
			<tr>
				<td><center><a href="/clan-wars">Clan wars</a></center></td>
			</tr>
			<?php if($clan_leader[0] == $user_ID):?>
			<tr>
				<td><center><a style="color:#ff0000;font-weight:bold;" href="/delete.php/?clan=<?php echo $clan_id_user[0];?>">Delete clan</a></center></td>
			</tr>
			<?php else: ?>
			<tr>
				<td><center><a href="/leave.php/?user=<?php echo $user_ID;?>">Leave clan</a></center></td>
			</tr>
			<?php endif;?>
		</table>
		
		<?php endif;?>
			
			
			
			<?php session_unset(); ?>
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
