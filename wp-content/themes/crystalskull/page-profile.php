<?php
 /*
 * Template Name: Profile
 */
$user__ID = $_GET['id'];
if(empty($user__ID)){
	wp_redirect(get_permalink(3486));
}
$user = get_userdata($user__ID);
if ( $user === false ) {
	wp_redirect(get_permalink(3486));
}
count_all_stats($user__ID);
$user_NW = get_user_meta($user__ID, 'networth',true);
$user_land = get_user_meta($user__ID, 'land',true);
$clan_id = get_user_meta($user__ID, 'clan_id_user',true);
$timestamp = strtotime(date('Y-m-d H:i:s'));
$clan_timestamp = get_user_meta($user__ID, 'new_clan_timestamp', true);


include('country_array.php');
$user_country_code = get_user_meta($user__ID, 'user_country',true);

$last_online = get_user_meta($user__ID, 'last_online',true);
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online;}

$visiting_user = get_current_user_ID();

$clan_id_user = get_user_meta($visiting_user, 'clan_id_user',true);

$previous_members = get_post_meta($clan_id_user,'previous_members');




$ct_1 = get_post_meta($clan_id_user,'ct_1',true);
$ct_2 = get_post_meta($clan_id_user,'ct_2',true);
$ct_3 = get_post_meta($clan_id_user,'ct_3',true);
$ct_4 = get_post_meta($clan_id_user,'ct_4',true);
$cl_1 = get_post_meta($clan_id_user,'clan_leader',true);	

$CT_CL_array = array($ct_1,$ct_2,$ct_3,$ct_4,$cl_1);



$members = get_post_meta($clan_id_user,'clan_members',true);
get_header('profile'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
       
       			<div class="container2">
				
				
				
				
<div class="row profile_block">
	<div class="col-md-2">
		<center>
		
			<div style='border-radius:100%;margin-bottom:20px;height:120px;width:120px;'>
				<?php echo small_avatar($user->ID,'largeAvatar');?>
			</div>	
		
		</center>
		
		
	</div>
	<div class="col-md-10">
		<div class="row">
			<div class="row profile_row">
				<div class="col-xs-5">Player ID</div>
				<div class="col-xs-7">#<?php echo $user__ID;?></div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Player name</div>
				<div class="col-xs-7"><?php echo $user->display_name;?></div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Medals</div>
				<div class="col-xs-7">
					<?php 
				
						$aw_args = array(
						'post_type'		=>	'medal',
						'numberposts' => -1,
						'meta_key' 		=> 'winning_user',
						'meta_value'     	 => $user__ID);
					
					$medals = get_posts($aw_args);
					if(count($medals) == 0){echo 'none';}
					foreach ($medals as $medal){
				
						$round = get_post_meta($medal->ID, 'medal_round', true); ?>
					<i class="fa fa-star fa-lg" aria-hidden="true"></i> &nbsp;<?php echo $round;?>: <strong><?php echo $medal->post_title;?></strong><br/>
						<?php } ?>
				</div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Registered</div>
				<div class="col-xs-7"><?php echo $user->user_registered;?></div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Networth</div>
				<div class="col-xs-7">$ <?php echo number_format($user_NW, 0, ',', ' ')?></div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Land</div>
				<div class="col-xs-7"><?php echo number_format($user_land, 0, ',', ' ')?>m<sup>2</sup></div>
			</div>
			<div class="row profile_row_last">
				<div class="col-xs-5">Clan</div>
				<div class="col-xs-7">
					<?php if($clan_id == 0){
						echo 'none';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
				</div>
			</div>
		</div>
	</div>
</div>		
				
<?php $count = 0;?>
<?php if($visiting_user != $user__ID && ($clan_id != $clan_id_user || $clan_id == 0) && !in_array($visiting_user, $CT_CL_array)):?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row button_block">
 	
 	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-attack profilebutton" href="/attack/step-1/?id=<?php echo $user__ID;?>">
		 	<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
		<center><a class="btn btn-general profilebutton" href="/spy-reports/?id=<?php echo $user__ID;?>">
			<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
	  <center><a class="btn btn-general profilebutton" href="/send-message/?id=<?php echo $user__ID;?>">
		  <i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
	</div>
  
</div>
<?php endif;?>






<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && in_array($visiting_user, $CT_CL_array) && count($members) == 7):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row button_block">
 	
 	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-attack profilebutton" href="/attack/step-1/?id=<?php echo $user__ID;?>">
		 	<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
		<center><a class="btn btn-general profilebutton" href="/spy-reports/?id=<?php echo $user__ID;?>">
			<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
	  <center><a class="btn btn-general profilebutton" href="/send-message/?id=<?php echo $user__ID;?>">
		  <i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
	</div>
  
</div>
<?php endif;?>



<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && $clan_id == 0 && in_array($visiting_user, $CT_CL_array) && count($members) < 7):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as CT/CL, members below 7 -->
<div class="row button_block">
 	
 	<div class="col-md-3 buttoncol">
	 	<center><a class="btn btn-attack profilebutton" href="/attack/step-1/?id=<?php echo $user__ID;?>">
		 	<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
		<center><a class="btn btn-general profilebutton" href="/spy-reports/?id=<?php echo $user__ID;?>">
			<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
	  	<center><a class="btn btn-general profilebutton" href="/send-message/?id=<?php echo $user__ID;?>">
		  <i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
	  	<center><a onclick="return confirm('Are you sure you want to invite <?php echo $user->display_name;?> (#<?php echo $user__ID;?>)?')" class="btn btn-general profilebutton" href="/invite.php?invite=<?php echo md5(uniqid(rand(), TRUE)) . "\n";?>&clan=<?php echo $clan_id_user;?>&user=<?php echo $user__ID;?>">
		  	<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Send clan invite</a></center>
	</div>
  
</div>
<?php endif;?>


<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && $count != 1 && in_array($visiting_user, $CT_CL_array)):?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row button_block">
 	
 	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-attack profilebutton" href="/attack/step-1/?id=<?php echo $user__ID;?>">
		 	<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
		<center><a class="btn btn-general profilebutton" href="/spy-reports/?id=<?php echo $user__ID;?>">
			<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
	  <center><a class="btn btn-general profilebutton" href="/send-message/?id=<?php echo $user__ID;?>">
		  <i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
	</div>
  
</div>
<?php endif;?>




<?php if($clan_id == $clan_id_user && $visiting_user != $user__ID):?>
<!-- Visiting clanmember profile -->
<div class="row button_block">
 	
	<div class="col-md-12 buttoncol">
	  	<center><a class="btn btn-general profilebutton" href="/send-message/?id=<?php echo $user__ID;?>">
		  <i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
	</div>
  
</div>
<?php endif;?>





<?php if($visiting_user == $user__ID):?>
<!-- visiting own profile -->
<div class="row button_block">
 	
 	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/users/profile/edit/">
		 	<i class="fa fa-wrench" aria-hidden="true"></i> &nbsp;Edit your profile</a></center>
	</div>
	
	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/player-statistics/">
		 	<i class="fa fa-bar-chart" aria-hidden="true"></i> &nbsp;View statistics</a></center>
	</div>

</div>


<?php endif;?>





			

			<?php if(current_user_can('activate_plugins')){ ?>
			<center><a href="/admin-tools/?user_id=<?php echo $user__ID;?>">Admin edit user</a></center>
			<?php }?>
	
<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>