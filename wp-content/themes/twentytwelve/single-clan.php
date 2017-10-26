<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$declarer_ID = get_current_user_ID();
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user');
$declarer_clanleader = get_post_meta($declarer_clan_ID[0],'clan_leader');

$timestamp = strtotime(date('Y-m-d H:i:s'));

if($declarer_clan_ID != 0){
	$dec_clan_members = get_post_meta($declarer_clan_ID[0],'clan_members');

	$dec_tot_networth = 0;
					foreach ($dec_clan_members[0] as $dec_member) {
					$dec_networth = get_user_meta($dec_member, 'networth');
					$dec_tot_networth+=$dec_networth[0];}
}

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID[0]
));
	$declared_on = array();
foreach ($wars_on as $war) {
	$declared_on[] = get_post_meta($war->ID,'declared_on')[0];
	
	}
$_member = false;
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			
			<?php 
				$clan_id = get_the_ID();
				$clan_members = get_post_meta($clan_id,'clan_members');
				
				 $ct_1 = get_post_meta($clan_id,'ct_1')[0];
				 $ct_2 = get_post_meta($clan_id,'ct_2')[0];
				 $ct_3 = get_post_meta($clan_id,'ct_3')[0];
				 $ct_4 = get_post_meta($clan_id,'ct_4')[0];
				
				$clanleader = get_post_meta($clan_id,'clan_leader');
				$clan_points = get_post_meta($clan_id,'clan_points');
				while ( have_posts() ) : the_post(); ?>

			<center><h1><?php echo get_the_title($clan_id).' (#'.$clan_id;?>)</h1></center><br/>
			<?php if(!empty(get_post_meta($clan_id, 'clan_image', true))):?>
			<center><img style="border:3px solid #ededed;width:100%;" src="<?php echo get_post_meta($clan_id, 'clan_image', true); ?>"></center><br/><?php endif;?>
		<table>
			<tr>
				<td><strong>Name</strong>
				</td>
				<td><?php echo get_the_title($clan_id);?>
				</td>
			</tr>
			<tr>
				<td><strong>Members</strong>
				</td>
				<td><?php echo count($clan_members[0]);?>
				</td>
			</tr>
			<tr>
				<td><strong>Tag</strong>
				</td>
				<td><?php $clantag = get_post_meta($clan_id,'clan_tag'); echo $clantag[0];?>
				</td>
			</tr>
			<tr>
				<td><strong>Total networth</strong>
				</td>
				<td>$ <?php 
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					echo number_format($tot_networth, 0, ',', ' ');
				?>
				</td>
			</tr>
			<tr>
				<td><strong>Points</strong>
				</td>
				<td><?php if(!empty($clan_points)){echo number_format($clan_points[0], 0, ',', ' ');}else{echo '0';}?>
				</td>
			</tr>
			<tr>
				<td><strong>Message</strong>
				</td>
				<td><?php echo str_replace("\r", "<br />", get_the_content($clan_id)); ?>
				</td>
			</tr>
		</table><br/>
		<table class="sortable">
			<tr><td></td>
				<td><strong>Name</strong>
				</td>
				<td><strong>Networth</strong>
				</td>
				<?php if (in_array($userId, $clan_members[0])): $_member = true;?>
				<td><strong>Points</strong>
				</td>
				<?php endif;?>
				<td><strong>Land</strong>
				</td>
				<td>
				</td>
			</tr>
		<?php 
			
			//$key = array_search(223, $clan_members[0]);
			//unset($clan_members[0][$key]);
			
			//update_field('clan_members', $clan_members, $clan_id);
			
			foreach ($clan_members[0] as $key => $member) {
				$member_data = get_userdata($member);
				$networth = get_user_meta($member, 'networth');
				$land = get_user_meta($member, 'land');
				$last_online = get_user_meta($member, 'last_online');
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online[0];}
			?>
		<tr><td><?php if($member == $clanleader[0] ){echo '<center><strong>CL </strong></center>';} ?>
				<?php if($member == $ct_1 || $member == $ct_2 || $member == $ct_3 || $member == $ct_4 ){echo '<center><strong>CT </strong></center>';} ?>
			</td>
			<td><a href="/users/profile/?id=<?php echo $member;?>"><?php echo $member_data->display_name.' (#'.$member.')';?></a> <?php
						if(!empty($last_online)){
						if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?>

			</td>
			<td sorttable_customkey="<?php echo $networth[0];?>">$ <?php echo number_format($networth[0], 0, ',', ' '); ?>
			</td>
			<?php if($_member == true):?>
			<td>
				<?php 
					$pts = get_user_meta($member, 'user_clan_points')[0];
				
						echo $pts;
					
					 ?>
			</td>
			<?php endif;?>
			<td sorttable_customkey="<?php echo $land[0];?>"><?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup>
			</td>
			<td><?php if($userId == $clanleader[0] && $member != $userId){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>">Kick</a>
			<?php } ?>
			<?php if($member != $userId && $member != $clanleader[0] && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){?>
			<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>">Kick</a>
			<?php }} ?>
			
			
			
			</td>
		</tr>
		
		
		
		
		<?php }?>
		</table>
			<?php endwhile; // end of the loop. ?>
<?php 
	

	if($declarer_clan_ID[0] != get_the_id()):?>


	<?php if (!in_array(get_the_ID(), $declared_on)){ ?>
	
	
	<?php if (($tot_networth > $dec_tot_networth/1.4 && $tot_networth < $dec_tot_networth*1.4)){	?>	
	<?php 
		
				$decct_1 = get_post_meta($declarer_clan_ID[0],'ct_1')[0];
				$decct_2 = get_post_meta($declarer_clan_ID[0],'ct_2')[0];
				$decct_3 = get_post_meta($declarer_clan_ID[0],'ct_3')[0];
				$decct_4 = get_post_meta($declarer_clan_ID[0],'ct_4')[0];
		
		if($declarer_ID == $declarer_clanleader[0] || $declarer_ID == $decct_1 || $declarer_ID == $decct_2 || $declarer_ID == $decct_3 || $declarer_ID == $decct_4){?>
	<br/><center><a href="/declare_war.php?clan=<?php echo $clan_id;?>">Declare war on <?php echo get_the_title($clan_id).' (#'.$clan_id;?>)</a></center>
		
		<?php }?>
		<?php } else {?>
		<br/><center>Clan out of range. You cannot declare war.</center>
		<?php }?><?php } ?>
		<?php if (in_array(get_the_ID(), $declared_on)){ ?>
			<br/><center>You are at war with this clan</center>

	<?php }?>

<?php endif;?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
