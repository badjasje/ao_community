<?php
include('constants.php'); 
$declarer_ID = get_current_user_ID();
update_user_meta($declarer_ID, 'user_lock', 0);
$userData = get_user_meta($declarer_ID);
$nw_att = $userData['networth'][0];
$declarer_clan_ID = $userData['clan_id_user'][0];
$clan_id = get_the_ID();


$declarerClanData = get_post_meta($declarer_clan_ID); // Get all postmeta linked to declarer clan ID

$declarer_clanleader = $declarerClanData['clan_leader'][0];

	$clanData = get_post_meta($clan_id);
    $clanMembers = maybe_unserialize($clanData['clan_members'][0]);
    $membersCount = count($clanMembers);
    //Enemy clan avg nw is:
    $averageNw = $clanData['clan_networth'][0] / $membersCount;

    //Count the members in YOUR clan
    $declaringClanMembers = maybe_unserialize($declarerClanData['clan_members'][0]);
    $declaringMembersCount = count($declaringClanMembers);
    $declarerAverageNw = $declarerClanData['clan_networth'][0] / $declaringMembersCount;

$average_OK = "false";
if ($declarerAverageNw*$AVERAGE_DECLARE_NW_ALLOWED > $averageNw) {
  $average_OK = "true";
}
$cooldownlist = maybe_unserialize($declarerClanData['cooldown_list'][0]);

$decct_1 = $declarerClanData['ct_1'][0];
$decct_2 = $declarerClanData['ct_2'][0];
$decct_3 = $declarerClanData['ct_3'][0];
$decct_4 = $declarerClanData['ct_4'][0];

$allowed_to_declare = array($declarer_clanleader,$decct_1,$decct_2,$decct_3,$decct_4);
				
$warcount = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'      => 'publish',
	'meta_query'	=> array( 'relation' => 'AND',
			array(
                 'key' => 'declared_by',
                 'value' => $clan_id
               ),
            array(
                 'key' => 'declared_on',
                 'value' => $declarer_clan_ID
               ))
));



$warcount = count($warcount);

$timestamp = current_time('timestamp');

// calculating total NW for declaring clan. Not sure if still needed.
if($declarer_clan_ID != 0){

	$dec_tot_networth = 0;

		foreach ($declaringClanMembers as $dec_member) {
			$dec_networth = get_user_meta($dec_member, 'networth',true);
			$dec_tot_networth+=$dec_networth;
		}
	}

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'   => 'publish',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID
));
	$declared_on = array();
	$peaceID = 0;
foreach ($wars_on as $war) {
	$defClanID = get_post_meta($war->ID,'declared_on',true);
	$att_ClanID = get_post_meta($war->ID,'declared_by',true);
	
	
	if($defClanID == $clan_id){
		$peaceID = $war->ID;
	}
	$declared_on[] = $defClanID;
	}
$_member = false;

if(in_array($declarer_ID, $declaringClanMembers)){
	$_member = true;
	
}

get_header(); ?>
<div class="page normal-page">
	<div class="container containerNZ">
	<div class="row">
		<?php if(!empty($_SESSION['status'])):?>
				<?php echo alert_notification($_SESSION['status']);?>
		<?php endif; // End empty status check ?>

	<?php



		$ct_1 = $clanData['ct_1'][0];
		$ct_2 = $clanData['ct_2'][0];
		$ct_3 = $clanData['ct_3'][0];
		$ct_4 = $clanData['ct_4'][0];

		$clanleader = $clanData['clan_leader'][0];
		$clan_points = $clanData['clan_points'][0];
		$clantag = $clanData['clan_tag'][0];
		
		$tot_networth = 0;
		foreach ($clanMembers as $member) {

			count_all_stats($member);
			$networth = get_user_meta($member, 'networth',true);
			$tot_networth+=$networth;
		
		}
			
		update_post_meta($clan_id, 'clan_networth', ceil($tot_networth));
			
				
		while ( have_posts() ) : the_post(); ?>
		<?php if(!empty($clanData['clan_image'][0])):?>
		<div class="row profile_block">	
		
			<center>
			<div style="width:100%; height:300px;background: url('<?php echo $clanData['clan_image'][0]; ?>') center center;background-repeat: no-repeat;">
			</center>
		
		</div>
		<?php endif;?>
		
		<div class="row profile_block">
			<div class="row">
				
				<div class="row profile_row">
					<div class="col-xs-5">Name</div>
					<div class="col-xs-7"><?php echo get_the_title($clan_id);?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-5">Members</div>
					<div class="col-xs-7"><?php echo count($clanMembers);?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-5">Awards</div>
					<div class="col-xs-7">
                        <div id="awardlist" style="overflow: hidden;">
                          <?php include 'pages/clan/awardlist.php'; ?>
						</div>
                        <a id="awardlistExpandBtn" style="display: none">Show more</a>
					</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-5">Tag</div>
					<div class="col-xs-7"><?php echo $clantag;?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-5">Total networth</div>
					<div class="col-xs-7">$ <?php echo number_format($tot_networth, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-5">Average networth</div>
					<div class="col-xs-7">$ <?php echo number_format($averageNw, 0, ',', ' ');?></div>
				</div>
				<div class="row profile_row">
					<div class="col-xs-5">Points</div>
					<div class="col-xs-7">
						<?php if(!empty($clan_points)){
							echo number_format($clan_points, 0, ',', ' ');
							}
							else{
								echo '0';}?>pts <sup><?php echo $clanData['24h_pts'][0];?>pts today</sup>
					</div>
				</div>
				
				<div class="row profile_row_last">
					<div class="col-xs-5">Message</div>
					<div class="col-xs-7">

                        <div id="clanMessage"
                          style="line-height: 18px;
                                      height: 54px;
                                    overflow: hidden;">

                          <?php echo str_replace("\r", "<br />", get_the_content($clan_id));?>

                        </div>
                        <br />
                        <a id="clanMessageExpandBtn" style="display: none">Show more</a>

				    </div>

				</div>

			</div>

		</div>


<script type="text/javascript" src="/wp-content/themes/crystalskull/js/aoReadMore.js"></script>
<script type="text/javascript">
    initReadMore("clanMessage", "clanMessageExpandBtn", 54);
    initReadMore("awardlist",   "awardlistExpandBtn",   163);
</script>


<?php
	// range checker
	$inRange = 'no';
	$warText = 'war';
	if($tot_networth > $dec_tot_networth/1.4 && $tot_networth < $dec_tot_networth*1.4){
		$inRange = 'yes';
		$warText = 'war';
	}
	if($warcount == 1){
		$inRange = 'yes';
		$warText = 'mutual war';
	}
	// can peace clan? 
	$canPeace = false;
	if($peaceID != 0 && ($timestamp-get_the_title($peaceID) > 86400)){
		$canPeace = true;
	}
	
	?>		
		
		
		
		
		
		
		

		
<?php if(!in_array($declarer_ID, $clanMembers)):?>



<!-- Enemy clan block -->
<div class="storeDetails-heads button_block sortingHeadMob">
	<center>
	<strong>Sort:</strong> <a href="" class="sort" data-sort=".memberField">Name</a> - 
	<a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort sort-number" data-sort=".land">Land</a>
	</center>
</div>

<div class="row profile_block storeDetails-heads">	
<div class="row clan_header_row ">
	<div class="col-md-1"></div>
	<div class="col-md-4"><strong><a href="" class="sort" data-sort=".memberField">Name</a></strong></div>
	<div class="col-md-3"><strong><a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a></strong></div>
	<div class="col-md-2"><strong><a href="" class="sort sort-number" data-sort=".land">Land</a></strong></div>
	<div class="col-md-2"></div>
</div>
<div id="values">
<?php 
	$NRmembers = count($clanMembers);
	$counter = 0;
	foreach ($clanMembers as $key => $member) {
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = 'clan_profile_row_last';
			
		}
		$member_data = get_userdata($member);
		$memberMetaData = get_user_meta($member);
		$networth = $memberMetaData['networth'][0];
		$land = $memberMetaData['land'][0];
		$last_online = $memberMetaData['last_online'][0];
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
			?>

<div class="row clan_profile_row">
	<div class="col-md-1">
		<?php echo small_avatar($member,'');?>
		
		
	</div>
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		<div class="ctclField">
			<?php if($member == $clanleader ){
			echo '<strong>CL</strong>';
			} ?>
			<?php if($member == $ct_1 || $member == $ct_2 || $member == $ct_3 || $member == $ct_4 ){
				echo '<strong>CT</strong>';
			} ?>
			</div>
		<?php echo get_user_name($member);?>
					
			
			
	</div>
	<div class="col-md-3 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
		<?php echo networth_range($member);?>
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right land">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-2 clan_column center_clan_col">
		<a href="/attack/step-1/?id=<?php echo $member;?>"><i class="fa fa-crosshairs fa-lg" aria-hidden="true"></i></a> 
		<a href="/spy-reports/?id=<?php echo $member;?>"><i class="fa fa-binoculars" aria-hidden="true"></i></a>
	</div>
</div>

<?php }?>
</div>
<div id="result"></div>
</div>

<?php if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) && $inRange == 'yes' && $canPeace == false):?>

<div class="row button_block">
 	
 	<div class="col-md-6 buttoncol">
	 	<?php if (in_array($clan_id, $declared_on)):?>
	 	<center><span class="btn btn-disabled profilebutton">
		 	<i class="fa fa-fire" aria-hidden="true"></i> &nbsp;You are at war with this clan</span></center>
		 <?php else:?>
		 <center>
                   <?php if ($average_OK == "false" && $warcount != 1) {
                     ?>
                    <span class="btn btn-disabled profilebutton">
                        <i class="fa fa-fire" aria-hidden="true"></i> &nbsp;Your average NW is too low to declare on this clan</span> 
                     <?php 
                   }
                   else { ?>
                     <a class="btn btn-general profilebutton declarewar" onclick="return confirm('Are you sure you want to declare <?php echo $warText;?>?')" href="/declare_war.php?clan=<?php echo $clan_id;?>">
		 	<i class="fa fa-fire" aria-hidden="true"></i> &nbsp;Declare <?php echo $warText;?>
                     </a>
                   <?php } ?>
                 </center>
		 <?php endif;?>
	</div>
	
	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<i class="fa fa-bar-binoculars" aria-hidden="true"></i> &nbsp;View spyreports</a></center>
	</div>

</div>
<script> 
  jQuery(".declarewar").click(function (event) {
    if (jQuery(this).hasClass("disabled")) {
        event.preventDefault();
    }
    jQuery(this).addClass("disabled");
});
</script>

<?php endif;?>



<?php if(in_array($declarer_ID, $allowed_to_declare) && $canPeace == true):?>
<!-- Declare peace block -->
<div class="row button_block">
 	
 	<div class="col-md-6 buttoncol">	
		 <center><a class="btn btn-general profilebutton" onclick="return confirm('Are you sure you want to declare peace?')" href="/declare_peace.php/?war=<?php echo $peaceID;?>">
		 	<i class="fa fa-fire" aria-hidden="true"></i> &nbsp;Declare peace</a></center>
	</div>
	
	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<i class="fa fa-bar-binoculars" aria-hidden="true"></i> &nbsp;View spyreports</a></center>
	</div>

</div>
<?php endif;?>




<?php if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) &&  $inRange == 'no' && $canPeace == false):?>

<div class="row button_block">
 	
 	<div class="col-md-6 buttoncol">
	 	<center><span class="btn btn-disabled profilebutton">
		 	<i class="fa fa-fire" aria-hidden="true"></i> &nbsp;Currently not in range</span></center>
	</div>
	
	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports</a></center>
	</div>

</div>
<?php endif;?>


<?php if(!in_array($declarer_ID, $allowed_to_declare) || array_key_exists($clan_id, $cooldownlist)):?>

<div class="row button_block">
	<div class="col-md-3 buttoncol">
	</div>
	
	<div class="col-md-6 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
	</div>

</div>

<?php endif;?>




<!-- End enemy clan block -->

<?php endif;?>







<?php if(in_array($declarer_ID, $clanMembers)):?>
<div class="storeDetails-heads button_block sortingHeadMob">
	<center>
	<strong>Sort:</strong> <a href="" class="sort" data-sort=".memberField">Name</a> - 
	<a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort sort-number" data-sort=".land">Land</a> - 
	<a href="" class="sort sort-number" data-sort=".points">Points</a>
	</center>
</div>

<!-- Own clan block -->


<div class="row profile_block storeDetails-heads">	
<div class="row clan_header_row ">
	<div class="col-md-1"></div>
	<div class="col-md-4"><strong><a href="" class="sort" data-sort=".memberField">Name</a></strong></div>
	<div class="col-md-2"><strong><a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a></strong></div>
	<div class="col-md-2"><strong><a href="" class="sort sort-number" data-sort=".land">Land</a></strong></div>
	<div class="col-md-2"><strong><a href="" class="sort sort-number" data-sort=".points">Points</a></strong></div>
	<div class="col-md-2"></div>
</div>
<div id="values">
<?php 
	$NRmembers = count($clanMembers);
	$counter = 0;
	foreach ($clanMembers as $key => $member) {
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		$member_data = get_userdata($member);
		$networth = get_user_meta($member, 'networth',true);
		$land = get_user_meta($member, 'land',true);
		$last_online = get_user_meta($member, 'last_online',true);
		$pts = get_user_meta($member, 'current_clan_points',true);
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
			?>
<div class="row clan_profile_row">
	<div class="col-md-1">
		<?php echo small_avatar($member,'');?>
		
		
		
	</div>
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		<div class="ctclField">
			<?php if($member == $clanleader ){
			echo '<strong>CL</strong>';
			} ?>
			<?php if($member == $ct_1 || $member == $ct_2 || $member == $ct_3 || $member == $ct_4 ){
				echo '<strong>CT</strong>';
			} ?>
			</div>
		<a class="memberField <?php echo get_user_meta($member,'status',true);?>" href="/users/profile/?id=<?php echo $member;?>">
			
			<?php echo $member_data->display_name.' (#'.$member.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?>
					
		
			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
		<?php echo networth_range($member);?>
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right land">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Points</span>
		<span class="clan_data_right points">
		<?php echo number_format($pts, 0, ',', ' '); ?>pts
		</span>
	</div>
	
	<div class="col-md-1 clan_column center_clan_col">
		<?php if($userId == $clanleader && $member != $userId){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>" onclick="return confirm('Are you sure you want to kick <?php echo $member_data->display_name.' (#'.$member.')';?> from your clan? Your clan will lose <?php echo round($pts*0.25);?> clan points.')">Kick</a>
			<?php } ?>
			<?php if($member != $userId && $member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){?>
			<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4){?>
			<a href="/kick.php/?id=<?php echo $member;?>&clan=<?php echo $clan_id;?>" onclick="return confirm('Are you sure you want to kick <?php echo $member_data->display_name.' (#'.$member.')';?> from your clan? Your clan will lose <?php echo round($pts*0.25);?> clan points.)')">Kick</a>
			<?php }} ?>

	</div>
</div>

<?php }?>
</div>
<div id="result"></div>
</div>
<!-- End own clan block -->
<?php endif;?>






















<?php endwhile; // end of the post loop. ?>

<?php if(get_field('game_status','option') != 'Live'):?>
	<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
		<?php else:?>

	
		<?php if(array_key_exists($clan_id, $cooldownlist)):?>

		<div class="notice_message"><span class="rdw-line">
			<?php 
				$timeleft = $cooldownlist[$clan_id]-$timestamp;
				echo human_time_diff( $cooldownlist[$clan_id],$timestamp);?> left before you can declare a new war on this clan</span>
				<?php if($warcount == 1 && in_array($declarer_ID, $allowed_to_declare)):?>
				<a href="/resumewar.php/?declaredon=<?php echo $clan_id;?>&declaredby=<?php echo $declarer_clan_ID;?>">
					<div style="margin-top:10px;padding:10px;background-color:#fff;color:#5f5d5d;">
						However, you can still choose to resume your previous war
					</div>
				</a>
				<?php endif;?>
		</div>
		<?php endif;?>
		
		


<?php if (in_array($clan_id, $declared_on)){ ?>
	<div class="notice_message"><span class="rdw-line">You are at war with this clan</span></div>
<?php }?><br/>
		<?php endif;?>
		
		</div><!-- /.span12 -->

	</div><!-- /row -->
	</div>
</div><!-- /containerblog -->
<?php session_unset(); ?>
<?php get_footer(); ?>
