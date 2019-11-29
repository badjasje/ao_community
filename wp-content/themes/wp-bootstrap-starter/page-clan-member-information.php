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

$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = $clanData['ct_'.$i][0];
}

include('count_functions.php');
$researches = Researches::get();
$units = Units::get();
$buildings = Buildings::get();
$rates = Bank::getAllRates();
?>

<div class="row pageRow">
	<?php
	$timestamp = current_time('timestamp');

	$NRmembers = count($clan_members);
	$counter = 0;
	$count = 0;
	foreach ($clan_members as $key => $member):

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
		$sat_morale = $memberData['sat_morale'][0];
		$last_online = $memberData['last_online'][0];
		$power = $memberData['power'][0];
		$startingbonus = $memberData['starting_bonus'][0];
		if(empty($startingbonus)) $startingbonus = '<em>none</em>';

		$totAidSent = (isset($memberData['total_aid_sent']) ? $memberData['total_aid_sent'][0] : 0);
		$noAids = (isset($memberData['number_of_aids']) ? $memberData['number_of_aids'][0] : 0);
		$aidRec = (isset($memberData['aid_received']) ? $memberData['aid_received'][0] : 0);
		$inprogress = $memberData['research_in_progress'][0];
		$attMade = $memberData['attacks_made_current'][0];
		$attRec = $memberData['attacks_rec_current'][0];
		$canAttack = implode(', ', can_attack($member));
		if(empty($canAttack)) $canAttack = '<em>none</em>';

		$totalUnits = count_tot_units($member); // array
		$totalBuildings = count_tot_buildings($member);

		// Unittypes with totals in popup
		$unitTypes = array();
		foreach (unit_types($member) as $type => $number) {
			$unitTypes[] = '<span class="hover-tip" data-toggle="tooltip" data-original-title="Owned: '.
				$number.'" data-placement="bottom">'. $type .'</span>';
		}
		$unitTypes = (count($unitTypes) ? implode(', ',$unitTypes) : '<em>none</em>');

		// Research time left
		$timeLeft ='';
		if(!empty($inprogress)) {
			$args = array('posts_per_page' => 1, 'author' => $member, 'post_type' => 'research');
			$researches_in_progress = get_posts( $args );
			$completionTime = $researches_in_progress[0]->post_title;
			$timeLeft = $completionTime - $timestamp;
		}

		// Check how many open bonusses you have
		$open_bonusses = $open_money = $open_turns = 0;
		$args = array('author' => $member, 'numberposts' => -1, 'post_type' => 'event_local',
			'meta_query' => array('relation' => 'AND', array('key' => 'attacktype', 'value' => array('bonus'), 'compare' => 'IN')),
		);
		foreach (get_posts($args) as $bonus) {
			$event_ID = $bonus->ID;
			$used = get_post_meta($event_ID, 'bonus_used', true);
			if($used != 'yes') {
				$open_bonusses++;
				$open_money += get_post_meta($event_ID, 'bonus_money', true);
				$open_turns += get_post_meta($event_ID, 'bonus_turns', true);
			}
		}
		$unused_bonusses = ($open_bonusses > 0 ? '<span class="hover-tip" data-toggle="tooltip" data-original-title="Money: $ '.
			number_format($open_money, 0, ',', ' ').', turns: '.$open_turns.'" data-placement="bottom">'. $open_bonusses .'</span>' : 0);

		// Find bank deposits
		$total_final = $unlocked = 0;
		$banklevel = $memberData['level_bank_management'][0];
		$extra_interest = 0;
		if($banklevel == 1) $extra_interest = 0.5;
		if($banklevel == 2) $extra_interest = 0.5;
		if($banklevel == 3) $extra_interest = 0.75;
		$args = array('posts_per_page' => -1, 'author' => $member, 'post_type' => 'deposit');
		$deposits = get_posts($args);
		foreach ($deposits as $deposit) {
			$depositId = $deposit->ID;
			$depositData = get_post_meta($depositId);
			$days = $depositData['days'][0];
			$deposited = $depositData['amount'][0];
			$incl_interest = $deposited*pow($rates[$days]['interest']+($extra_interest/100),$days);
			$total_final += $incl_interest;
			$release_stamp = $depositData['release_date'][0];
			$time_left = $release_stamp-$timestamp;
			$placedStamp = $depositData['deposit_placed'][0];
			if($time_left < 0) $unlocked += $incl_interest;
			if($banklevel >= 2 && $time_left > 0) {
				$early_penalty = ($banklevel == 2 ? 0.5 : 0.75);
				if($placedStamp+43200 <= $timestamp && $time_left > 0) $unlocked += ($deposited*$early_penalty);
			}
		}

		$highest_networth = number_format($memberData['highest_networth'][0], 0, ',', ' ');
		$freeLand = number_format($memberData['land'][0]-$memberData['builtland'][0], 0, ',', ' ');

		$extraClass = '';
		$counter++;
		if($counter == $NRmembers) $extraClass = '_last';

		$member_data = get_userdata($member);
		$last_online = $memberData['last_online'][0];
		$spiednr = (isset($memberData['spied_current_clan']) ? intval($memberData['spied_current_clan'][0]) : 0);

		if(!empty($last_online)) $last_seen = $timestamp - $last_online;
		?>
		<div class="blockHeader">
			<?php echo get_user_name($member);?>
		</div>

		<!-- Row 1 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Networth</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo networth_range($member);?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Land</span>
				<span class="dataVisibleRight land">
					<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Points per attack</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo number_format($PPA, 1, ',', ' '); ?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Targets spied</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo number_format($spiednr, 0, ',', ' '); ?>
				</span>
			</div>
		</div> <!-- // Close Row 1 -->

		<!-- Row 2 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.4-($count/70);?>);">
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Turns</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $turns;?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Money</span>
				<span class="dataVisibleRight">
					$ <?php echo number_format($money, 0, ',', ' ');?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Morale</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $morale;?>% <sup>(<?php echo $pool;?>%)</sup>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Last online</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo date('H:i | d-m-y', $last_online);?>
				</span>
			</div>
		</div><!-- // Close Row 2 -->

		<!-- Row 3 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.45-($count/70);?>);">
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Unit types</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $unitTypes; ?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Can attack</span>
				<span class="dataVisibleRight">
					<?php echo $canAttack;?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Attacks made</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $attMade;?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Attacks received</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $attRec;?>
				</span>
			</div>
		</div><!-- // Close Row 3 -->

		<!-- Row 4 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.5-($count/70);?>);">
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Aid sent</span>
				<span class="dataVisibleRight store-pop-span2">
					$ <?php echo number_format($totAidSent, 0, ',', ' ');?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Aid received</span>
				<span class="dataVisibleRight">
					$ <?php echo number_format($aidRec, 0, ',', ' ');?>
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Power usage</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo round($power);?>%
				</span>
			</div>

			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Satellite power</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo number_format($sat_morale, 0, ',', ' '); ?>%
				</span>
			</div>
		</div><!-- // Close Row 4 -->

		<!-- Row 5 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.55-($count/70);?>);">
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Bank total</span>
				<span class="dataVisibleRight">$ <?=number_format($total_final, 0, ',', ' ')?></span>
			</div>
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Bank available</span>
				<span class="dataVisibleRight">$ <?=number_format($unlocked, 0, ',', ' ')?></span>
			</div>
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Unused bonusses</span>
				<span class="dataVisibleRight"><?=$unused_bonusses?></span>
			</div>
			<div class="col-md-3 col-xs-6 celBlock">
				<span class="dataVisibleLeft">Start bonus</span>
				<span class="dataVisibleRight"><?=$startingbonus?></span>
			</div>
		</div><!-- // Close Row 5 -->

		<!-- Button row -->
		<div class="row fw-row no-gutters">

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="research" member-id="<?php echo $member;?>"
					class="cancelButton hoverEffect viewmemberinfo<?=(count($researches)||!empty($inprogres)?' active':'')?>" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Research
					<?php if(!empty($inprogress)) {?>
						<span class="badge" data-toggle="tooltip" data-placement="top" title="Research currently in progress: <?php echo $researches[$inprogress]['name'];?>">
							<i class="fa fa-circle-o-notch fa-spin"></i>
						</span>
					<?php } ?>
				</button>

				<div class="memberInfo research_<?php echo $member;?>">
				<?php
					foreach ($researches as $key => $research) {
						$level = $memberData['level_'.$key][0];?>
						<span class="dataVisibleLeft"><?php echo $research['name'];?></span>
						<span class="dataVisibleRight">Level: <?php echo $level;?></span>
						<br/>
					<?php } ?>
					<?php if(!empty($inprogress)) { ?>
						<br/>
						<strong>In progress: <?php echo $researches[$inprogress]['name'];?>, <span data-countdown="<?=$timeLeft?>"></span> left</strong>
					<?php } ?>
				</div>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="units" member-id="<?php echo $member;?>"
					class="cancelButton hoverEffect viewmemberinfo<?=($totalUnits['owned']+$totalUnits['ordered']>0?' active':'')?>" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.95-($count/70);?>);">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units <?php echo $totalUnits['owned'];?> (<?php echo $totalUnits['ordered'];?>)
				</button>
				<div class="memberInfo units_<?php echo $member;?>">
					<?php foreach($units as $key => $order){
						$units_owned = $memberData[$key.'_owned'][0];
						$units_ordered = $memberData[$key.'_ordered'][0];
						if($units_owned > 0 || $units_ordered > 0) { ?>
							<span class="dataVisibleLeft"><?php echo $order['normalname'];?></span>
							<span class="dataVisibleRight"><?php echo $units_owned;?> (<?php echo $units_ordered;?>)</span><br/>
						<?php }
					} ?>
				</div>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="buildings" member-id="<?php echo $member;?>"
					class="cancelButton hoverEffect viewmemberinfo<?=($totalBuildings['owned']>0?' active':'')?>" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.9-($count/70);?>);">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings <?php echo $totalBuildings['owned'];?>
				</button>
				<div class="memberInfo buildings_<?php echo $member;?>">
					<?php foreach($buildings as $key => $order){
						$units_owned = $memberData[$key][0];
						if($units_owned > 0){ ?>
							<span class="dataVisibleLeft"><?php echo $order['normalname'];?></span>
							<span class="dataVisibleRight"><?php echo $units_owned;?></span>
							<br/>
						<?php }
					} ?>
				</div>
			</div>

		</div>
		<!-- // Button row -->

		<div class="pageSpacer"></div>

	<?php $count++;
	endforeach; // End clan member loop ?>

	<script>
		(function($) {
			$(".viewmemberinfo.active").toggle(function(){
				var member = $(this).attr('member-id');
				var viewtype = $(this).attr('viewtype');
				$('.'+viewtype+'_'+member).show(150);
			}, function(){
				var member = $(this).attr('member-id');
				var viewtype = $(this).attr('viewtype');
				$('.'+viewtype+'_'+member).hide(150);;
			});
		})(jQuery);
	</script>

</div> <!-- end .pageRow -->
<?php
get_footer();