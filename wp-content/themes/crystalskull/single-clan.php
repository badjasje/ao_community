<?php 
$declarer_ID = get_current_user_ID();

$nw_att = get_user_meta($declarer_ID, 'networth',true);
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user');
$declarer_clanleader = get_post_meta($declarer_clan_ID[0],'clan_leader');

$cooldownlist = get_post_meta($declarer_clan_ID[0], 'cooldown_list', true);

$decct_1 = get_post_meta($declarer_clan_ID[0],'ct_1')[0];
$decct_2 = get_post_meta($declarer_clan_ID[0],'ct_2')[0];
$decct_3 = get_post_meta($declarer_clan_ID[0],'ct_3')[0];
$decct_4 = get_post_meta($declarer_clan_ID[0],'ct_4')[0];

$allowed_to_declare = array($declarer_clanleader[0],$decct_1,$decct_2,$decct_3,$decct_4);
				
$warcount = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'      => 'publish',
	'meta_query'	=> array( 'relation' => 'AND',
			array(
                 'key' => 'declared_by',
                 'value' => get_the_ID()
               ),
            array(
                 'key' => 'declared_on',
                 'value' => $declarer_clan_ID[0]
               ))
));

$warcount = count($warcount);
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
	'post_status'   => 'publish',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID[0]
));
	$declared_on = array();
foreach ($wars_on as $war) {
	$declared_on[] = get_post_meta($war->ID,'declared_on')[0];
	
	}
$_member = false;
get_header(); ?>
<div class="blog blog-ind">
	<div class="container ">
	<div class="row">

		<div class="col-lg-12 col-md-12">
			
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
			<?php if(!empty(get_post_meta($clan_id, 'clan_image', true))):?>
			<center><div style="width:100%; height:300px;background: url('<?php echo get_post_meta($clan_id, 'clan_image', true); ?>') center center;background-repeat: no-repeat;"></center><br/><?php endif;?>
		<table class="responsive-table">
			<tr>
				<td class="report_content"><strong>Name</strong></td>
				<td class="report_content"><?php echo get_the_title($clan_id);?></td>
			</tr>
			<tr>
				<td class="report_content"><strong>Members</strong></td>
				<td class="report_content"><?php echo count($clan_members[0]);?></td>
			</tr>
			<tr>
				<td class="report_content"><strong>Awards</strong></td>
				<td class="report_content">
					
					<?php 
				
						$aw_args = array(
							'post_type'		=>	'award',
							'numberposts' => -1,
							'meta_key' 		=> 'winning_clan',
							'meta_value'     	 => $clan_id);
						$awards = get_posts($aw_args);
				
						foreach ($awards as $award){
							$position = get_post_meta($award->ID, 'position_clan', true);
							$round = get_post_meta($award->ID, 'round', true);
						?>
						<i class="fa fa-trophy fa-lg" aria-hidden="true"></i> &nbsp;<?php echo $round;?>: <?php echo $award->post_title;?> - <strong><?php echo strtoupper($position);?></strong><br/>
						<?php } ?>
					
					
				</td>
			</tr> 
			<tr>
				<td class="report_content"><strong>Tag</strong></td>
				<td class="report_content"><?php $clantag = get_post_meta($clan_id,'clan_tag'); echo $clantag[0];?></td>
			</tr>
			<tr>
				<td class="report_content"><strong>Total networth</strong></td>
				<td class="report_content">$ <?php 
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					
					count_all_stats($member);
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					echo number_format($tot_networth, 0, ',', ' ');
					update_post_meta($clan_id, 'clan_networth', ceil($tot_networth));
				?>
				</td>
			</tr>
			<tr>
				<td class="report_content"><strong>Points</strong></td>
				<td class="report_content"><?php if(!empty($clan_points)){echo number_format($clan_points[0], 0, ',', ' ');}else{echo '0';}?>pts <sup><?php echo get_post_meta($clan_id, '24h_pts', true);?>pts today</sup></td>
			</tr>
			<tr>
				<td class="report_content"><strong>Message</strong></td>
				<td class="report_content"><?php echo str_replace("\r", "<br />", get_the_content($clan_id)); ?></td>
			</tr>
		</table><br/>
		<div class="clan_sorter">
		<center>Sort by
		<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('landsort'), [])">Land</a>
		<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('nwsort'), [])">Networth</a>
		<?php if (in_array($user_ID, $clan_members[0])):?>
		<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('ptssort'), [])">Points</a>
		<?php endif;?>
		</center>
		<br/>
		</div>
		<table class="responsive-table sortable">
			<thead>
			<tr style="text-align:center;">
				
				<td></td>
				<td></td>
				<td><strong>Name</strong>
				</td>
				<td id="nwsort"><strong>Networth</strong>
				</td>
				<?php if (in_array($user_ID, $clan_members[0])): $_member = true;?>
				<td id="ptssort"><strong>Points</strong>
				</td>
				<?php endif;?>
				<td id="landsort"><strong>Land</strong>
				</td>
				<td>
				</td>
				<?php if($declarer_clan_ID[0] != get_the_id()):?>
				<td>
				</td>
				<?php endif;?>
				</thead>
			</tr>
			<tbody>
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
			<td>
				<?php if(!empty(get_user_meta($member, 'avatar_user', true))):?>
                    
			<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($member, 'avatar_user', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
                    
			<?php endif;?>
			</td>
			<td data-title="User"><a class="<?php echo get_user_meta($member,'status',true);?>" href="/users/profile/?id=<?php echo $member;?>"><?php echo $member_data->display_name.' (#'.$member.')';?></a> <?php
						if(!empty($last_online)){
						if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?>

			</td>
			<td sorttable_customkey="<?php echo $networth[0];?>" data-title="Networth">
				<?php if($_member == true):?>
				$ <?php echo number_format($networth[0], 0, ',', ' '); ?>
				<?php else:?>
				<?php if(($nw_att/1.4 <= $networth[0]) && ($networth[0] <= $nw_att*1.4)):?>
				<strong>$ <?php echo number_format($networth[0], 0, ',', ' '); ?></strong>
				<?php else:?>
				$ <?php echo number_format($networth[0], 0, ',', ' '); ?>
				<?php endif;?><?php endif;?>
			</td>
			<?php if($_member == true):
				$pts = get_user_meta($member, 'user_clan_points',true);
			?>
			<td sorttable_customkey="<?php echo $pts;?>" data-title="Points">
				<?php 
					
					if(empty($pts)){$pts = 0;}
						echo $pts;
					
					 ?>
			</td>
			<?php endif;?>
			<td sorttable_customkey="<?php echo $land[0];?>" data-title="Land" sorttable_customkey="<?php echo $land[0];?>"><?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup>
			</td>
			<td><?php if($user_ID == $clanleader[0] && $member != $user_ID){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>">Kick</a>
			<?php } ?>
			<?php if($member != $user_ID && $member != $clanleader[0] && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){?>
			<?php if($user_ID == $ct_1 || $user_ID == $ct_2 || $user_ID == $ct_3 || $user_ID == $ct_4){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>">Kick</a>
			<?php }} ?>
			<?php if($_member == false):?>
			<td data-title="Actions">
				<a href="/attack/step-1/?id=<?php echo $member;?>"><i class="fa fa-crosshairs fa-lg" aria-hidden="true"></i></a> <a href="/spy-reports/?id=<?php echo $member;?>"><i class="fa fa-binoculars" aria-hidden="true"></i></a>
			</td>
			<?php endif;?>
			
			
			</td>
		</tr>
		
		
		
		
		<?php }?>
			</tbody>
		</table>
			<?php endwhile; // end of the loop. ?>

<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
<?php 
	/* check to not be able to declare on own clan */
	if($declarer_clan_ID[0] != get_the_id()):?> 


<?php if (!in_array(get_the_ID(), $declared_on)){ ?>
	
	
	<?php 
		$candeclare = false;
		if (($tot_networth > $dec_tot_networth/1.4 && $tot_networth < $dec_tot_networth*1.4)){	
			$candeclare = true;
		?>	
		
		<?php if(in_array($declarer_ID, $allowed_to_declare)){?>
		<?php if(!array_key_exists(get_the_id(), $cooldownlist)):?>
	<center><a onclick="return confirm('Are you sure you want to declare war?')" class="btn btn-general" href="/declare_war.php?clan=<?php echo $clan_id;?>"><i class="fa fa-fire" aria-hidden="true"></i> Declare war on <?php echo get_the_title($clan_id).' (#'.$clan_id;?>)</a></center>
		<?php else:?>
		<div class="notice_message"><span class="rdw-line">
			<?php 
				
				$timeleft = $cooldownlist[$clan_id]-$timestamp;
		
				
	
				//$timeleft = date('d:H:i:s', $timeleft);
				
				echo human_time_diff( $cooldownlist[$clan_id],$timestamp);?> left before you can declare war</span></div>
		<?php endif;?>
		<?php }?>
		
		

		<?php } elseif($candeclare == false && $warcount != 1) {?>
		<div class="notice_message"><span class="rdw-line">Clan out of range. You cannot declare war.</span></div>
		<?php } if($warcount == 1 && in_array($declarer_ID, $allowed_to_declare)) {?>
		<center><a onclick="return confirm('Are you sure you want to declare mutual war?')" class="btn btn-general" href="/declare_war.php?clan=<?php echo $clan_id;?>"><i class="fa fa-fire" aria-hidden="true"></i> Declare mutual war on <?php echo get_the_title($clan_id).' (#'.$clan_id;?>)</a></center>
		<?php }?><?php } ?>


<?php endif;?>

<?php if (in_array(get_the_ID(), $declared_on)){ ?>
	<div class="notice_message"><span class="rdw-line">You are at war with this clan</span></div>
<?php }?><br/>
<center><a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $clan_id;?>"><i class="fa fa-binoculars" aria-hidden="true"></i> View spy report overview</a></center>
		<?php endif;?>
		
		</div><!-- /.span12 -->

	</div><!-- /row -->
	</div>
</div><!-- /containerblog -->

<?php get_footer(); ?>