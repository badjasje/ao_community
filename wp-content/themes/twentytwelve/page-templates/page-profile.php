<?php
/**
 * Template Name: Profile template
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user__ID = $_GET['id'];
$user = get_userdata($user__ID);
$user_NW = get_user_meta($user__ID, 'networth');
$user_land = get_user_meta($user__ID, 'land');
$clan_id = get_user_meta($user__ID, 'clan_id_user');
$timestamp = strtotime(date('Y-m-d H:i:s'));



include('country_array.php');
$user_country_code = get_user_meta($user__ID, 'user_country');

$last_online = get_user_meta($user__ID, 'last_online');
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online[0];}

$visiting_user = get_current_user_ID();

$clan_id_user = get_user_meta($visiting_user, 'clan_id_user');

 $ct_1 = get_post_meta($clan_id_user[0],'ct_1')[0];
 $ct_2 = get_post_meta($clan_id_user[0],'ct_2')[0];
 $ct_3 = get_post_meta($clan_id_user[0],'ct_3')[0];
 $ct_4 = get_post_meta($clan_id_user[0],'ct_4')[0];


$clan_leader_ID = get_post_meta($clan_id_user[0],'clan_leader');	
$members = get_post_meta($clan_id_user,'clan_members');

get_header();?>

	<div id="primary" class="site-content">
		<div id="content" role="main"><br/><br/><br/>
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 1):?>
				<div class="marketnotice">Invite sent</div>
			<?php endif;?><?php endif;?>
			
			
			
		<center><h1><?php echo $user->display_name;?> (#<?php echo $user__ID;?>) <?php
						if(!empty($last_online)){
						if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?></h1></center><br/>
			<div class="container2">
				<table class="responsive-table">
					
					<tr>
						<th scope="row" style="width: 105px; vertical-align: top;border-right: 1px solid #9F9F9F;"rowspan='8'><img src="<? echo get_avatar_url2(get_avatar( $user__ID, 87 )); ?>"  /></th>
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
			<center><a href="/attack/step-1/?id=<?php echo $user__ID;?>">Attack <?php echo $user->display_name;?> (#<?php echo $user__ID;?>)</a></center>
			<?php endif;?>
		<?php if($clan_id[0] == 0){
		if(count($members) < 16){ 
			
		if($clan_leader_ID[0] == $visiting_user || $visiting_user == $ct_1 || $visiting_user == $ct_2 || $visiting_user == $ct_3 || $visiting_user == $ct_4){
		
			
		?>
		<br/>
		<center><a href="/invite.php?invite=<?php echo md5(uniqid(rand(), TRUE)) . "\n";?>&clan=<?php echo $clan_id_user[0];?>&user=<?php echo $user__ID;?>">Invite <?php echo $user->display_name;?> (#<?php echo $user__ID;?>) to join your clan</a></center><br/>
		
			
		<?php }}}?>
		<center><a href="/send-message/?id=<?php echo $user__ID;?>">Send message to <?php echo $user->display_name;?> (#<?php echo $user__ID;?>)</a></center>
			<?php endif;?>
			
			
			<?php if($visiting_user == $user__ID):?>
			<center><a href="/users/profile/edit/">Edit your profile</a></center>
			<?php endif;?>
			
			<?php if(current_user_can('activate_plugins')){ ?>
			<center><a href="/admin-tools/?user_id=<?php echo $user__ID;?>">Admin edit user</a></center>
			<?php }?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php session_unset(); ?>
<?php get_footer(); ?>