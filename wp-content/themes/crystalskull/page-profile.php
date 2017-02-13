<?php
 /*
 * Template Name: Profile
 */
$user__ID = $_GET['id'];
if(empty($user__ID)){
	wp_redirect(get_permalink(3486));
}
$user = get_userdata($user__ID);
count_all_stats($user__ID);
$user_NW = get_user_meta($user__ID, 'networth');
$user_land = get_user_meta($user__ID, 'land');
$clan_id = get_user_meta($user__ID, 'clan_id_user');
$timestamp = strtotime(date('Y-m-d H:i:s'));
$clan_timestamp = get_user_meta($user__ID, 'new_clan_timestamp', true);


include('country_array.php');
$user_country_code = get_user_meta($user__ID, 'user_country');

$last_online = get_user_meta($user__ID, 'last_online');
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online[0];}

$visiting_user = get_current_user_ID();

$clan_id_user = get_user_meta($visiting_user, 'clan_id_user');

$previous_members = get_post_meta($clan_id_user[0],'previous_members');




 $ct_1 = get_post_meta($clan_id_user[0],'ct_1')[0];
 $ct_2 = get_post_meta($clan_id_user[0],'ct_2')[0];
 $ct_3 = get_post_meta($clan_id_user[0],'ct_3')[0];
 $ct_4 = get_post_meta($clan_id_user[0],'ct_4')[0];


$clan_leader_ID = get_post_meta($clan_id_user[0],'clan_leader');	
$members = get_post_meta($clan_id_user[0],'clan_members');
get_header('profile'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 1):?>
				<div class="marketnotice">Invite sent</div>
			<?php endif;?><?php endif;?>

			<div class="container2">
				<table class="responsive-table">
					
					<tr>
						<th scope="row" style="width: 105px; vertical-align: top;background-color:#fff;"rowspan='9'>
							<?php if(!empty(get_user_meta($user__ID, 'avatar_user', true))):?>
			<div style='height:90px;width:90px;background: url("<?php echo get_user_meta($user__ID, 'avatar_user', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='height:90px;width:90px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
			<?php endif;?>
						</th>
						<td>Province ID</td>
						<td>#<?php echo $user__ID;?></td>
  					</tr>
  					<tr>
						<td>Provincename</td>
						<td><?php echo $user->display_name;?></td>
  					</tr>
  					<tr>
						<td>Name</td>
						<td><?php echo $user->display_name;?></td>
  					</tr>
  					<tr>
						<td>Medals</td>
						<td>
						<?php 
				
						$aw_args = array(
							'post_type'		=>	'medal',
							'numberposts' => -1,
							'meta_key' 		=> 'winning_user',
							'meta_value'     	 => $user__ID);
						$medals = get_posts($aw_args);
				
						foreach ($medals as $medal){
				
							$round = get_post_meta($medal->ID, 'medal_round', true);
						?>
						<i class="fa fa-star fa-lg" aria-hidden="true"></i> &nbsp;<?php echo $round;?>: <strong><?php echo $medal->post_title;?></strong><br/>
						<?php } ?>
						
						
						</td>
  					</tr>
  					
  					
  					<tr>
						<td>Country</td>
						<td><?php 
							if($user_country_code[0]){
							if($user_country_code[0] != '0'){
							echo $countries[$user_country_code[0]];?> <img src="/flags/<?php echo strtolower($user_country_code[0]);?>.png">
							
							<?php }}?>
							</td>
  					</tr>
  					<tr>
						<td>Registered</td>
						<td><?php echo $user->user_registered;?></td>
  					</tr>
  					<tr>
						<td>Networth</td>
						<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
  					</tr>
  					<tr>
						<td>Land</td>
						<td><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>
  					</tr>
  					<tr>
						<td>Clan</td>
						<td><?php if($clan_id[0] == 0){
							echo 'none';}else{
							echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
							}?></td>
  					</tr>
			</table>
			</div>
			<br/>
			<?php if($clan_id != $visiting_user):?>
			<?php if($visiting_user != $user__ID && $clan_id[0] != get_user_meta($visiting_user, 'clan_id_user')[0] || $clan_id[0] == 0):?>
			<center><a class="btn btn-attack" href="/attack/step-1/?id=<?php echo $user__ID;?>"><i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
			<center><a class="btn btn-general" href="/spy-reports/?id=<?php echo $user__ID;?>"><i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports</a></center>
			
	
			<?php endif;?>
		<?php if($clan_id[0] == 0){ 
		if(count($members[0]) < 7){ 
			
		if($clan_leader_ID[0] == $visiting_user || $visiting_user == $ct_1 || $visiting_user == $ct_2 || $visiting_user == $ct_3 || $visiting_user == $ct_4){
		
			
		?>
		<?php if(get_field('game_status','option') == 'Live'):?>
		<?php //if($timestamp > $clan_timestamp && !in_array($user__ID, $previous_members[0])):?>
		<center><a onclick="return confirm('Are you sure you want to invite <?php echo $user->display_name;?> (#<?php echo $user__ID;?>)?')" class="btn btn-general" href="/invite.php?invite=<?php echo md5(uniqid(rand(), TRUE)) . "\n";?>&clan=<?php echo $clan_id_user[0];?>&user=<?php echo $user__ID;?>"><i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Send clan invite</a></center>
		<?php// endif;?><?php endif;?>
		
			
		<?php }}}?>
		<center><a class="btn btn-general" href="/send-message/?id=<?php echo $user__ID;?>"><i class="fa fa-envelope-o" aria-hidden="true"></i> &nbsp;Send message</a></center>
			<?php endif;?>
			
			
			<?php if($visiting_user == $user__ID):?>
			<center><a class="btn btn-general" href="/users/profile/edit/"><i class="fa fa-wrench" aria-hidden="true"></i> &nbsp;Edit your profile</a></center>
			<center><a class="btn btn-general" href="/player-statistics/"><i class="fa fa-bar-chart" aria-hidden="true"></i> &nbsp;View statistics</a></center>
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