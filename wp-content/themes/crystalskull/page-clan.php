<?php
 /*
 * Template Name: Clan
 */
$user_ID = get_current_user_ID();

$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_leader = get_post_meta($clan_id_user, 'clan_leader',true);
$clanCreate = get_user_meta($user_ID,'clan_create_counter', true);

$ct_1 = get_post_meta($clan_id_user,'ct_1',true);
$ct_2 = get_post_meta($clan_id_user,'ct_2',true);
$ct_3 = get_post_meta($clan_id_user,'ct_3',true);
$ct_4 = get_post_meta($clan_id_user,'ct_4',true);
 
$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clan_leader);

$clans = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'clan',
	'meta_key'		=> 'autojoin_allowed',
	'meta_value'	=> 'yes'
));

$clanCount = 0;

foreach ($clans as $clan) { 
	
	$members = count(get_post_meta($clan->ID,'clan_members',true));
	
	if($members < 7){
		$clanCount++;
	}
}

get_header('clan'); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
	        		<?php if($clan_id_user == 0):?>
<?php if($clanCreate != 1):?>
<div class="notice_message">
	<span class="rdw-line">You are currently not in a clan! Create a clan or join one.</span>
	<span class="rdw-line">You can only create one clan per round</span>
</div>
<br/>
		
		
		<form action="<?php echo home_url() ?>/clan.php" name="" id="clan" method="post">
		
			<input required class="small_input" type="text" name="clanname" minlength="3" maxlength="20" id="clanname" placeholder="Clan Name. Max 20 characters."><br/><br/>
			<input required class="small_input" type="text" name="clantag" id="clantag" maxlength="5"placeholder="Clan Tag. Max 5 characters."><br/><br/>
			<textarea rows="5" class="small_input" type="text" name="clanmessage" id="clanmessager" placeholder="Clan Message"></textarea>
			
		<input type="submit"  class="submitBtn" value="Create your clan" class="">
		</form>
		<br/>
		<script>
			jQuery(document).ready(function () {
			jQuery("#clan").submit(function () {
	        jQuery(".submitBtn").attr("disabled", true);
	        return true;
	    	});
		});
		</script>
<?php else:?>
<div class="notice_message">
	<span class="rdw-line">You already created a clan this round.</span>
</div>
<br/>


<?php endif;?>

<div class="row textNotify">
	<div class="col-md-12">
 	<center><span class="rdw-line">Join a clan to get the full assault.online experience.</span> <span class="rdw-line">
 	<?php echo $clanCount;?> clan<?php if($clanCount == 0 || $clanCount > 1){ echo 's';}?> currently looking for players.</span></center><br/>
</div>
 	<div class="col-md-4">
	</div>
	
	<div class="col-md-4">
		<center><a class="btn btn-general profilebutton" href="/join-a-clan/">
			<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;View clans</a></center>
	</div>
	
	<div class="col-md-4">
	</div>
  
</div>

		<?php else:?>
		
		
		<div class="list-group clan_buttons">
			
			<a href="<?php echo get_the_permalink($clan_id_user);?>" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-info-circle clanpageitem" aria-hidden="true"></i> Clan information</h4>
  			</a>
			
  			<?php if(in_array($user_ID, $allowed)):?>
  			<a href="/edit-clan" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-wrench clanpageitem" aria-hidden="true"></i> Edit clan</h4>
  			</a>
  			
  			<a href="/open-invites" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-envelope-open-o clanpageitem" aria-hidden="true"></i> Open invites</h4>
  			</a>
  			<?php endif;?>
  			<a href="/clan-member-information" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-users clanpageitem" aria-hidden="true"></i> Clan member information</h4>
  			</a>
  			
  			<a href="/clan-wars" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-fire clanpageitem" aria-hidden="true"></i> Clan wars</h4>
  			</a>
  			
  			<a href="/bonus-overview/" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-bar-chart clanpageitem" aria-hidden="true"></i> Bonus overview</h4>
  			</a>
  			
  			<a href="/send-aid/" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-usd clanpageitem" aria-hidden="true"></i> Send aid</h4>
  			</a>
  			
  			<?php if($clan_leader != $user_ID):?>
  			
  			<a onclick="return confirm('Are you sure you want to leave your clan?')" href="/leave.php/?user=<?php echo $user_ID;?>" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-arrow-circle-o-down clanpageitem" aria-hidden="true"></i> Leave clan</h4>
  			</a>
  			
  			<?php endif;?>
  			
  			<?php if($clan_leader == $user_ID):?>
  			
  			<a onclick="return confirm('Are you sure you want to delete your clan?')" href="/delete.php/?clan=<?php echo $clan_id_user;?>" class="list-group-item">
			<h4 class="list-group-item-heading clanpageitem"><i class="fa fa-trash-o clanpageitem" aria-hidden="true"></i> Delete clan</h4>
  			</a>
  			
  			<?php endif;?>
  			
</div>

		
		<?php endif;?>
		
<?php if(in_array($user_ID, $allowed)):?>
<div class="row edit_clan_first">
	
	<div class="col-md-6 edit_clan_box">

		<h2 class="leftH2">Message all clan members</h2>
		<div class="message_field">
		<form class="form" action="<?php echo home_url() ?>/message_members.php" name="" id="message" method="post">
			<input style="margin-bottom:10px;"type="text" id="title" required placeholder="Subject" name="title"/>
			<input type="hidden" name="receiver" value="<?php echo $receiver_ID;?>">
			​<textarea id="message" required rows="10" name="message" placeholder="Your message..."></textarea>
			
				<input class="submitBtn" type="submit" value="Send">
	
		</form>
		<script>
			jQuery(document).ready(function () {
			jQuery("#message").submit(function () {
	        jQuery(".submitBtn").attr("disabled", true);
	        return true;
	    	});
		});
		</script>
	</div>
		
		
	</div>
	<div class="col-md-6 edit_clan_box">
		<h2 class="leftH2">Allow auto join</h2>
		<center>Using this function you can easily recruit new clan members</center><br/>
			<form class="form" action="<?php echo home_url() ?>/set_autojoin.php" name="autojoin" id="autojoin" method="post">
				<label>Allow players to automatically join your clan?</label>
				<select name="autojoin">
					<option <?php echo $autojoinNo;?> value="no">No</option>
					<option <?php echo $autojoinYes;?> value="yes">Yes</option>
				</select>
				<br/><br/><label>A short description of your clan</label>
				<input style="margin-top:10px;margin-bottom:10px;" type="text" name="description" id="description" value="<?php echo $autojoinDesc;?>" required placeholder="Short description. 'Sell' your clan!" name="description"/>
				<br/><br/><label>Playstyle or goal of your clan</label>
				<select name="playstyle">
					<option <?php echo $casual;?> value="Casual">Casual</option>
					<option <?php echo $points;?> value="Points">Points</option>
					<option <?php echo $networth;?> value="Networth">Networth</option>
					<option <?php echo $other;?> value="Other">Other</option>
				</select>
				
				<input class="submitBtn" type="submit" value="Save">
			</form>
	</div>
		
	
	
	
</div>
<?php endif;?>		
			
			
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>