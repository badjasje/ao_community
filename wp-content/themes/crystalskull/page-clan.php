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
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
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
		
		
		<div class="list-group clan_buttons">
			
			<a href="<?php echo get_the_permalink($clan_id_user);?>" class="list-group-item">
			<h4 class="list-group-item-heading"><i style="color:#333 !important"class="fa fa-info-circle" aria-hidden="true"></i> Clan information</h4>
  			</a>
			
  			<?php if(in_array($user_ID, $allowed)):?>
  			<a href="/edit-clan" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-wrench" aria-hidden="true"></i> Edit clan</h4>
  			</a>
  			
  			<a href="/open-invites" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Open invites</h4>
  			</a>
  			<?php endif;?>
  			<a href="/clan-member-information" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-users" aria-hidden="true"></i> Clan member information</h4>
  			</a>
  			
  			<a href="/clan-wars" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-fire" aria-hidden="true"></i> Clan wars</h4>
  			</a>
  			
  			<a href="/bonus-overview/" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-bar-chart" aria-hidden="true"></i> Bonus overview</h4>
  			</a>
  			
  			<a href="/send-aid/" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-usd" aria-hidden="true"></i> Send aid</h4>
  			</a>
  			
  			<?php if($clan_leader == $user_ID):?>
  			
  			<a onclick="return confirm('Are you sure you want to delete your clan?')" href="/delete.php/?clan=<?php echo $clan_id_user;?>" class="list-group-item">
			<h4 class="list-group-item-heading"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete clan</h4>
  			</a>
  			
  			<?php endif;?>
  			
</div>
		
		
		
		
	
		
		<?php endif;?>
			
			
			
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>