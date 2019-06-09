<?php
/*
 * Template Name: Clan Wars
 */
get_header();
include('constants.php');

global $userData;
global $userId;
$declarer_ID = $userId;

$declarer_clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($declarer_clan_ID);
$clan_leader = $clanData['clan_leader'][0];
$timestamp = current_time('timestamp');
$war_array = maybe_unserialize(maybe_unserialize($clanData['war_array'][0]));

$cooldownlist = maybe_unserialize($clanData['cooldown_list'][0]);
if(!is_array($cooldownlist)) $cooldownlist = array();

$backColorDecOn = "45, 67, 81";
$backColorDecBy = "127, 82, 67";
$backColorStats = "86, 113, 61";
$buttonColor = "70, 118, 94";

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];

$clan_networth = $clanData['clan_networth'][0];

 //MEGA 20171106 Count the members in YOUR clan
 $declaringClanMembers = maybe_unserialize($clanData['clan_members'][0]);
 $declaringMembersCount = count($declaringClanMembers);
 $declarerAverageNw = ($declaringMembersCount>0 ? $clan_networth / $declaringMembersCount : 0);

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID
));
$wars_by = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_on',
	'meta_value'	=> $declarer_clan_ID
));
?>

<div class="row pageRow">

	<div class="blockHeader">You can target clans with a networth between
		$ <?=number_format(($clan_networth/1.4), 0, ',', ' ')?> and $ <?=number_format(($clan_networth*1.4), 0, ',', ' ');?>
	</div>
	<div class="blockHeader spaceNotice">After 24 hours you are able to declare peace with a clan. A war will auto peace after 72 hours.</div>

	<div class="pageSpacer"></div>

	<div class="blockHeader">War declared on</div>

	<div class="row unitRow fw-row headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorDecOn;?>, 0.75);">
		<div class="col-md-4 celBlock"><strong>Clan</strong></div>
		<div class="col-md-2 celBlock"><strong>Date</strong></div>
		<div class="col-md-2 celBlock"><strong>Duration</strong></div>
		<div class="col-md-4 celBlock"></div>
	</div>

	<?php
	$count = 0;
	foreach ($wars_on as $war){
		$declared_on_ID = get_post_meta($war->ID, 'declared_on',true);
		?>
		<div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColorDecOn;?>, <?php echo 0.6-($count/25);?>);">
			<div class="col-md-4 celBlock nameBlock sea_heading">
				<a href="<?php echo get_the_permalink($declared_on_ID);?>"><?php echo get_the_title($declared_on_ID).' (#'.$declared_on_ID;?>)</a>
			</div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Date</span>
				<span class="columnDataRight">
					<?php echo get_the_date('G:i | d-m-Y',$war->ID); ?>
				</span>
			</div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Duration</span>
				<span class="columnDataRight">
					<?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
				</span>
			</div>
			<div class="col-md-4 celBlock" style="padding:0px;">
				<a href="/spy-report-overview/?id=<?php echo $declared_on_ID;?>">
				<button class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);" type="submit">Spy report overview</button>
				</a>
			</div>
		</div>
		<?php $count++;
	}
	?>

	<div class="pageSpacer"></div>

	<div class="blockHeader">War declared by</div>

	<div class="row unitRow fw-row headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorDecBy;?>, 0.75);">
		<div class="col-md-4 celBlock"><strong>Clan</strong></div>
		<div class="col-md-2 celBlock"><strong>Date</strong></div>
		<div class="col-md-2 celBlock"><strong>Duration</strong></div>
		<div class="col-md-4 celBlock"></div>
	</div>

	<?php
	$count = 0;
	foreach ($wars_by as $war){
		$declared_on_ID = get_post_meta($war->ID, 'declared_by',true);
		?>
		<div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColorDecBy;?>, <?php echo 0.6-($count/25);?>);">
			<div class="col-md-4 celBlock nameBlock air_heading">
				<a href="<?php echo get_the_permalink($declared_on_ID);?>"><?php echo get_the_title($declared_on_ID).' (#'.$declared_on_ID;?>)</a>
			</div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Date</span>
				<span class="columnDataRight">
					<?php echo get_the_date('G:i | d-m-Y',$war->ID); ?>
				</span>
			</div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Duration</span>
				<span class="columnDataRight">
					<?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
				</span>
			</div>
			<div class="col-md-4 celBlock" style="padding:0px;">
				<a href="/spy-report-overview/?id=<?php echo $declared_on_ID;?>">
				<button class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);" type="submit">Spy report overview</button>
				</a>
			</div>
		</div>
		<?php $count++;
	}
	?>

	<div class="pageSpacer"></div>

	<div class="blockHeader">War statistics</div>

	<div class="row unitRow fw-row headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorStats;?>, 0.75);">
		<div class="col-md-3 celBlock">War against</div>
		<div class="col celBlock">Date</div>
		<div class="col celBlock">Cooldown</div>
		<div class="col-md-3 celBlock">First declared by</div>
		<div class="col celBlock">Mutual</div>
		<div class="col-md-2 celBlock"></div>

	</div>

	<?php
	foreach ($war_array as $key => $war) {
		if(!is_array($war) || !isset($war['receiver_id']) || empty($war['receiver_id'])) continue;
		$aDiff = array_diff(array($war['declarer_id'],$war['receiver_id']), array($declarer_clan_ID));
		$warred_clan =  array_shift($aDiff);
		?>
		<div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColorStats;?>, <?php echo 0.6-($count/25);?>);">
			<div class="col-md-3 celBlock nameBlock veh_heading">
				<span class="columnDataLeft">War against</span>
				<span class="columnDataRight">
					<a href="<?php echo get_the_permalink($warred_clan);?>"><?php echo get_the_title($warred_clan);?></a>
				</span>
			</div>

			<div class="col celBlock">
				<span class="columnDataLeft">Date</span>
				<span class="columnDataRight">
					<?php echo date('H:i | d-m', $war['date']);?>
				</span>
			</div>

			<div class="col celBlock">
				<span class="columnDataLeft">Cooldown</span>
				<span class="columnDataRight">
				<?php if(isset($cooldownlist[$warred_clan]) && $cooldownlist[$warred_clan] > $timestamp) {
			 		echo human_time_diff($timestamp, $cooldownlist[$warred_clan]);
				} ?>
				</span>
			</div>

			<div class="col-md-3 celBlock">
				<span class="columnDataLeft">First declared</span>
				<span class="columnDataRight">
					<a href="<?php echo get_the_permalink($war['declarer_id']);?>"><?php echo get_the_title($war['declarer_id']);?></a>
				</span>
			</div>

			<div class="col celBlock">
				<span class="columnDataLeft">Mutual?</span>
				<span class="columnDataRight">
					<?=($war['mutual_date']!=0?'Yes':'No')?>
				</span>
			</div>

			<div class="col-md-2 celBlock" style="padding:0px;">
				<a href="/war-statistics/?id=<?php echo $key;?>">
					<button class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);" type="submit"><i class="fa fa-chart-line" aria-hidden="true"></i> &nbsp;View statistics</button>
				</a>
			</div>
		</div>
		<?php
	}
	?>

</div> <!-- end .pageRow -->
<?php
get_footer();