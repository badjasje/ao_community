<?php
 /*
 * Template Name: Clan Member Information
*/
get_header();
global $userData;
global $userId;
$clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_ID);

$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94";

$list_pts_24h = maybe_unserialize($clanData['24h_pts_list'][0]);
$list_pts_24h = maybe_unserialize($list_pts_24h);
$list_nw_24h = maybe_unserialize($clanData['24h_nw_list'][0]);
$list_nw_24h = maybe_unserialize($list_nw_24h);

$clan_members = maybe_unserialize($clanData['clan_members'][0]);
$clanleader = $clanData['clan_leader'][0];

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];
include('count_functions.php');
include('research_array.php');
include('units_array.php');
include('building_array.php');
?>

<div class="row pageRow">




<?php
	$NRmembers = count($clan_members);
	$counter = 0;
	$count = 0;
	foreach ($clan_members as $key => $member):
		$timestamp = current_time('timestamp');
		$memberData = get_user_meta($member);
		$attacksMade = $memberData['in_war_attacks'][0];
		$pts = $memberData['user_clan_points'][0];
		$PPA = 0;
		if($pts > 0){
		$PPA = round($pts / $attacksMade,1);
		}
		$networth = $memberData['networth'][0];
		$land = $memberData['land'][0];
		$turns = $memberData['turns'][0];
		$money = $memberData['money'][0];
		$morale = $memberData['morale'][0];
		$pool = $memberData['morale_pool'][0];
		$last_online = $memberData['last_online'][0];
		$power = $memberData['power'][0];

		$totAidSent = $memberData['total_aid_sent'][0];
		$noAids = $memberData['number_of_aids'][0];
		$aidRec = $memberData['aid_received'][0];
		$inprogress = $memberData['research_in_progress'][0];
		$attMade = $memberData['attacks_made_current'][0];
		$attRec = $memberData['attacks_rec_current'][0];

		$highest_networth = number_format($memberData['highest_networth'][0], 0, ',', ' ');
		$freeLand = number_format($memberData['land'][0]-$memberData['builtland'][0], 0, ',', ' ');

		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';

		}
		$member_data = get_userdata($member);
		$last_online = $memberData['last_online'][0];
		$spiednr = intval($memberData['spied_current_clan'][0]);

		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
	?>


<div class="blockHeader">
	<?php echo get_user_name($member);?>
</div>

<!-- Row 1 -->
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Networth</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo networth_range($member);?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Land</span>
		<span class="dataVisibleRight land">
			<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Points per attack</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo number_format($PPA, 1, ',', ' '); ?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Targets spied</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo number_format($spiednr, 0, ',', ' '); ?>
		</span>
	</div>

</div> <!-- // Close Row 1 -->

<!-- Row 2 -->
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.4-($count/70);?>);">
	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Turns</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo $turns;?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Money</span>
		<span class="dataVisibleRight">
			$ <?php echo number_format($money, 0, ',', ' ');?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Morale</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo $morale;?>% <sup>(<?php echo $pool;?>%)</sup>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Last online</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo date('H:i | d-m-y', $last_online);?>
		</span>
	</div>
</div><!-- // Close Row 2 -->

<!-- Row 3 -->
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.45-($count/70);?>);">
	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Unit types</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php $typecount = count(unit_types($member));
				foreach (unit_types($member) as $type => $number) { $typecounter++;?>
			<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Owned: <?php echo $number;?>" data-placement="bottom">
			<?php echo $type;?></span><?php if($typecount > $typecounter){echo',';}?>
		<?php  }?>
			</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Can attack</span>
		<span class="dataVisibleRight">
			<?php  echo implode(', ', can_attack($member));?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Attacks made</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo $attMade;?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Attacks received</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo $attRec;?>
		</span>
	</div>
</div> <!-- // Close Row 3 -->

<!-- Row 4 -->

<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.5-($count/70);?>);">
	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Aid sent</span>
		<span class="dataVisibleRight store-pop-span2">
			$ <?php echo number_format($totAidSent, 0, ',', ' ');?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Aid received</span>
		<span class="dataVisibleRight">
			$ <?php echo number_format($aidRec, 0, ',', ' ');?>
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft">Power usage</span>
		<span class="dataVisibleRight store-pop-span2">
			<?php echo round($power);?>%
		</span>
	</div>

	<div class="col-md-3 celBlock">
		<span class="dataVisibleLeft"></span>
		<span class="dataVisibleRight store-pop-span2"></span>
	</div>
</div> <!-- // Close Row 4 -->

<!-- Button row -->
<div class="row fw-row no-gutters">
	<div class="col-md-4 celBlock" style="padding:0px">
		<button viewtype="research" member-id="<?php echo $member;?>" class="cancelButton hoverEffect viewmemberinfo" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);" type="submit">
			<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Research
			<?php if($inprogress != '0'):?>
				<span class="badge" data-toggle="tooltip" data-placement="top" title="Research currently in progress: <?php echo $researches[$inprogress]['name'];?>">
					<i class="fa fa-circle-o-notch fa-spin"></i>
				</span>
			<?php endif;?>
		</button>

		<div class="memberInfo research_<?php echo $member;?>">
		<?php

			foreach ($researches as $key => $research) {
			$level = $memberData['level_'.$key][0];?>
				<span class="dataVisibleLeft"><?php echo $research['name'];?></span>
				<span class="dataVisibleRight">Level: <?php echo $level;?></span>
					<br/>

						<?php }?>
					<?php if($inprogress != '0'):?>
						<br/>
						<strong>In progress: <?php echo $researches[$inprogress]['name'];?></strong>
					<?php endif;?>
		</div>

	</div>


	<div class="col-md-4 celBlock" style="padding:0px">
		<button viewtype="units" member-id="<?php echo $member;?>" class="cancelButton hoverEffect viewmemberinfo" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.95-($count/70);?>);" type="submit">
			<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units <?php echo count_tot_units($member);?>
		</button>

		<div class="memberInfo units_<?php echo $member;?>">
			<?php foreach($units as $key => $order){
				$units_owned = $memberData[$key.'_owned'][0];
				$units_ordered = $memberData[$key.'_ordered'][0];

				if($units_owned > 0 || $units_ordered > 0){?>
					<span class="dataVisibleLeft"><?php echo $order['normalname'];?></span>
					<span class="dataVisibleRight"><?php echo $units_owned;?> (<?php echo $units_ordered;?>)</span><br/>
			<?php }}?>
		</div>

	</div>


	<div class="col-md-4 celBlock" style="padding:0px">
		<button viewtype="buildings" member-id="<?php echo $member;?>" class="cancelButton hoverEffect viewmemberinfo" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.9-($count/70);?>);" type="submit">
			<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings <?php echo count_tot_buildings($member);?>
		</button>

		<div class="memberInfo buildings_<?php echo $member;?>">
			<?php foreach($buildings as $key => $order){
						$units_owned = $memberData[$key][0];
						if($units_owned > 0 || $units_ordered > 0){
				?>
				<span class="dataVisibleLeft"><?php echo $order['normalname'];?></span>
				<span class="dataVisibleRight"><?php echo $units_owned;?></span>
				<br/>

					<?php }}?>
		</div>

	</div>
</div>
<!-- // Button row -->

<div class="pageSpacer"></div>

<?php $count++; endforeach; // End clan member loop ?>

<script>


jQuery(".viewmemberinfo").toggle(
	function(){
		var member = jQuery(this).attr('member-id');
		var viewtype = jQuery(this).attr('viewtype');
		jQuery('.'+viewtype+'_'+member).show(150);
	},

	function(){
		var member = jQuery(this).attr('member-id');
		var viewtype = jQuery(this).attr('viewtype');
		jQuery('.'+viewtype+'_'+member).hide(150);;
	});


</script>



</div> <!-- end .pageRow -->
<?php
get_footer();