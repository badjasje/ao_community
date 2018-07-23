<?php
 /*
 * Template Name: Profile page
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
$userData = get_user_meta($user__ID);
$user_NW = $userData['networth'][0];
$status = $userData['status'][0];
$user_land = $userData['land'][0];
$clan_id = $userData['clan_id_user'][0];
$timestamp = current_time('timestamp');
$clan_timestamp = $userData['new_clan_timestamp'][0];


include('country_array.php');
$user_country_code = $userData['user_country'][0];

$last_online = $userData['last_online'][0];
	if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
	}

$visiting_user = get_current_user_ID();

$visitorData = get_user_meta($visiting_user);

$savedUsers = $visitorData['saved_users'][0];
$savedUsers = json_decode($savedUsers);
$savedUsers = is_array($savedUsers) ? $savedUsers : [];

$clan_id_user = $visitorData['clan_id_user'][0];

$visitorClanData = get_post_meta($clan_id_user);

$previous_members = maybe_unserialize(get_post_meta($clan_id_user, 'previous_members', true));


$ct_1 = $visitorClanData['ct_1'][0];
$ct_2 = $visitorClanData['ct_2'][0];
$ct_3 = $visitorClanData['ct_3'][0];
$ct_4 = $visitorClanData['ct_4'][0];
$cl_1 = $visitorClanData['clan_leader'][0];

$CT_CL_array = array($ct_1,$ct_2,$ct_3,$ct_4,$cl_1);
$members = $visitorClanData['clan_members'][0];
get_header(); ?>

<div class="row pageRow">	
	
	
<div class="blockHeader">
	<?php echo get_user_name($user__ID); ?>
</div>
	
<div class="row row-no-padding fw-row">
	<div class="col-xs-2 col-no-padding eventImageCol" style="border-right: 1px solid #fff;">
		<?php echo small_avatar($user__ID,'profileAvatar');?>
	</div>
	
	<div class="col-xs-10 col-no-padding" style="flex:100">
		<div class="col-12 attackingRow statCol-1">
			<div class="profileColumn">ID</div> <?php echo $user__ID;?>
		</div>
			
		<div class="col-12 attackingRow statCol-2 elipOverflow">
			<div class="profileColumn">Player name</div> <?php echo $user->display_name;?>
		</div>
		
		<div class="col-12 attackingRow statCol-3">
			<?php if($status != 'banned'):?>
				<?php 
					$aw_args = array(
					'post_type'		=>	'medal',
					'numberposts' 	=> 	-1,
					'meta_key' 		=> 	'winning_user',
					'meta_value'    => 	$user__ID);
						
					$medals = get_posts($aw_args);
						
					if(count($medals) == 0):?>
						<div class="profileColumn">Medals</div> none
					<?php else:?>
						
						<h3>Medals</h3>
						<?php foreach ($medals as $medal){
					
							$round = get_post_meta($medal->ID, 'medal_round', true); ?>
						<i class="fa fa-star fa-lg" aria-hidden="true"></i> &nbsp;<?php echo $round;?>: <strong><?php echo $medal->post_title;?></strong><br/>
					<?php }?>
					<?php endif;?>
			<?php else:?>
				<br/>n.a
			<?php endif;?>
			
			</div>
			<div class="col-12 attackingRow statCol-4">
				<div class="profileColumn">Registered</div> <?php echo date( "d M Y", strtotime( $user->user_registered ));?>
			</div>
			<div class="col-12 attackingRow statCol-3">
				<div class="profileColumn">Networth</div> <?php echo networth_range($user__ID);?>
			</div>
			<div class="col-12 attackingRow statCol-2">
				<div class="profileColumn">Land</div> <?php echo number_format($user_land, 0, ',', ' ')?>m<sup>2</sup>
			</div>
			<div class="col-12 attackingRow statCol-1 elipOverflow">
				<div class="profileColumn">Clan</div> 
					<?php if($clan_id == 0):?>
						None
					<?php else:?>
						<a href="<?php echo get_the_permalink($clan_id);?>"><?php echo get_the_title($clan_id);?> (#<?php echo $clan_id;?>)</a>
					<?php endif;?>

			</div>
			
		</div>
	</div>
	
	
<?php $count = 0;?>
<?php if($visiting_user != $user__ID && ($clan_id != $clan_id_user || $clan_id == 0) && !in_array($visiting_user, $CT_CL_array)):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $user__ID;?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.9);"href="/spy-reports/?id=<?php echo $user__ID;?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $user__ID;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
	
<?php if(in_array($user__ID, $savedUsers)):?>
	
	<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
		<i class="fas fa-save"></i> &nbsp;User saved
	</a>
		
<?php else:?>

	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
		<i class="fas fa-save"></i> &nbsp;Save user
	</a>
	
<?php endif;?>
  
</div>
<?php endif;?>






<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && in_array($visiting_user, $CT_CL_array) && count($members) == 7):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $user__ID;?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.9);"href="/spy-reports/?id=<?php echo $user__ID;?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $user__ID;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
	
<?php if(in_array($user__ID, $savedUsers)):?>
	
	<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
		<i class="fas fa-save"></i> &nbsp;User saved
	</a>
		
<?php else:?>

	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
		<i class="fas fa-save"></i> &nbsp;Save user
	</a>
	
<?php endif;?>
  
</div>
<?php endif;?>



<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && $clan_id == 0 && in_array($visiting_user, $CT_CL_array) && count($members) < 6):?>
<?php $count = 1;?>




<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-2 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $user__ID;?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>
 	
 	<a class="col-md-2 profileButton" style="background-color: rgba(70, 118, 94, 0.9);"href="/spy-reports/?id=<?php echo $user__ID;?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $user__ID;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>


<?php if(in_array($user__ID, $previous_members)):?>
	
	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="#">
		<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Cannot invite
	</a>

<?php else:?>
	  		
	<a 	style="background-color: rgba(70, 118, 94, 0.7);"
		class="col-md-3 profileButton" 
		onclick="return confirm('Are you sure you want to invite <?php echo $user->display_name;?> (#<?php echo $user__ID;?>)?')"
		href="/invite.php?invite=<?php echo md5(uniqid(rand(), TRUE)) . "\n";?>&clan=<?php echo $clan_id_user;?>&user=<?php echo $user__ID;?>">
			  	<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Send clan invite
	</a>
	
<?php endif;?>

<?php if(in_array($user__ID, $savedUsers)):?>
	
	<a class="col-md-2 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
		<i class="fas fa-save"></i> &nbsp;User saved
	</a>
		
<?php else:?>

	<a class="col-md-2 profileButton" style="background-color: rgba(70, 118, 94, 0.6);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
		<i class="fas fa-save"></i> &nbsp;Save user
	</a>
	
<?php endif;?>
</div>
<?php endif;?>


<?php if($visiting_user != $user__ID && $clan_id != $clan_id_user && $count != 1 && in_array($visiting_user, $CT_CL_array)):?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $user__ID;?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.9);"href="/spy-reports/?id=<?php echo $user__ID;?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>
 	
 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $user__ID;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
	
<?php if(in_array($user__ID, $savedUsers)):?>
	
	<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
		<i class="fas fa-save"></i> &nbsp;User saved
	</a>
		
<?php else:?>

	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
		<i class="fas fa-save"></i> &nbsp;Save user
	</a>
	
<?php endif;?>
  
</div>
<?php endif;?>




<?php if($clan_id == $clan_id_user && $count != 1 && $visiting_user != $user__ID):?>
<!-- Visiting clanmember profile -->

<div class="row fw-row no-gutters profileButtonRow">
	
	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/military-overview/?id=<?php echo $user__ID;?>">
		<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview
	</a>
	
	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/send-message/?id=<?php echo $user__ID;?>">
		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
 	
 
	
<?php if(in_array($user__ID, $savedUsers)):?>
	
	<a class="col-md-4 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
		<i class="fas fa-save"></i> &nbsp;User saved
	</a>
		
<?php else:?>

	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
		<i class="fas fa-save"></i> &nbsp;Save user
	</a>
	
<?php endif;?>

</div>

<?php endif;?>





<?php if($visiting_user == $user__ID):?>
<!-- visiting own profile -->
<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/military-overview/?id=<?php echo $user__ID;?>">
		<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview
	</a>
 	
 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/users/profile/edit/">
 		<i class="fa fa-wrench" aria-hidden="true"></i> &nbsp;Edit your profile
 	</a>
 	
 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/player-statistics/">
 		<i class="fas fa-chart-line"></i> &nbsp;View statistics
	</a>
</div>


<?php endif;?>

	
	
	
	
<?php if(current_user_can('activate_plugins')){ ?>
<center><a target="_blank" href="/wp-admin/user-edit.php?user_id=<?php echo $user__ID;?>&wp_http_referer=%2Fwp-admin%2Fusers.php">Backend edit</a></center>
			
			
			<?php }?>
	
	
	
</div>
<?php
get_sidebar();
get_footer();