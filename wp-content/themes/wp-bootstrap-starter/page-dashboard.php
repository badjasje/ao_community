<?php
 /*
 * Template Name: Dashboard
*/
get_header();
include('startingbonus_array.php');

$startingDate = get_field('starting_date','options');
$endDate = get_field('end_date','options');
$gameType = get_field('game_type','option');

global $userData;
global $userId;

$pageId 					= get_the_id();

update_user_meta($userId, 'user_lock', 0);
$new_events 				= $userData['new_events'][0];
$new_messages 				= $userData['new_messages'][0];
$user_status 				= $userData['status'][0];
$nuke_protection_timestamp 	= $userData['nuke_protection_timestamp'][0];
$clanId 					= $userData['clan_id_user'][0];
$PtsRank 					= $userData['moh_position'][0];
$NwRank 					= $userData['mog_position'][0];
$PwrUsage 					= $userData['power'][0];
$AMS 						= $userData['antimissile'][0];
$def_land 					= $userData['builtland'][0];

$level_money_production 	= $userData['level_money_production'][0];
$sat_level 					= $userData['level_satellite_construction'][0];
$sat_morale 				= $userData['sat_morale'][0];

$morale 					= $userData['morale'][0];
$moralepool 				= $userData['morale_pool'][0];

$startingbonus 				= $userData['starting_bonus'][0];
$boni 						= array('offensive','defensive','finance','shipping');

$finance_multi = 1;

if($startingbonus == 'finance'){
	$finance_multi = 1.1;
}

$shootdown_chance = 0;
if($AMS > 0){
    $shootdown_chance = (($AMS*100)/$def_land)*100;
    if($shootdown_chance >= 75){
        $shootdown_chance = 75;
    }
}

if ($level_money_production == 0){
    $income = 15000*$finance_multi;
}elseif($level_money_production == 1){
    $income = 25000*$finance_multi;
}elseif($level_money_production == 2){
    $income = 35000*$finance_multi;
}

if($user_status == 'dead'){
    after_death($userId);
}
$user = get_userdata($userId);
?>

<div class="row pageRow">

<?php if($userData['networth'][0] <= 3499):?>
<div style="background-color:#A00000" class="blockHeader">WARNING: Your networth is below $3500</div>
<div class="blockHeader spaceNotice">You will not receive resources when below the $3500 treshold.</div>
<div class="pageSpacer"></div>
<?php endif;?>

<?php if(get_field('game_status','option') == 'Live' && $gameType == 'Test') { ?>
	<div class="blockHeader">Welcome to test.assault.online.</div>
	<div class="pageSpacer statCol-4">
		<strong>WARNING: this is NOT a sandbox!</strong>
		Some people are setting up testcases to test the game-engine, so please do not attack out of the blue.<br>
		Ask on <a href="http://bit.ly/2US8Dh0" target="_blank">discord</a> for a volunteer, or attack <a href="/clan/target-practice/">us</a>
	</div>
<?php } ?>
<?php if(get_field('game_status','option') == 'Live' && $gameType == 'Development') { ?>
	<div class="blockHeader">Welcome to dev.assault.online.</div>
<?php } ?>
<?php if(get_field('game_status','option') == 'Live' && in_array($gameType, array('Development','Test'))) { ?>
	<div class="blockHeader spaceNotice">
		To receive turns/money/morale/research/orders, hit the button below!<br>
		If you are dead or under protection, hitting this button will revive you as well.
	</div>
	<button style="background-color:#A00000;border:0px;" class="mainSubmit receiveFunds">Receive all</button>
	<div class="pageSpacer"></div>
	<script>
		(function($) {
			var devfunding;
			$(document).on('click','.receiveFunds',function(){
				$('.pageLoader, #page-cover').show();
				var target = $(this).attr('data-target');
				devfunding = $.ajax({url: "/devfunds.php",type: "post",data: ''});
				devfunding.done(function (response, textStatus, jqXHR){
					$('.pageLoader, #page-cover').fadeOut( "fast");
					var response = $.parseJSON(response);
					updateHeaderData();
					$.notify({message: response.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
				});
			});
		})(jQuery);
	</script>
<?php } ?>

<?php if(get_field('game_status','option') == 'Live') { ?>
	<?php include('pages/dashboard/pick-startingbonus.php'); ?>
	<?php include('pages/dashboard/bonus-receive.php'); ?>
	<?php include('pages/dashboard/toplists.php'); ?>
<?php } ?>

<?php if($clanId != 0) {?>
	<?php
	$clanData = get_post_meta($clanId);

	$ct_1 = $clanData['ct_1'][0];
	$ct_2 = $clanData['ct_2'][0];
	$ct_3 = $clanData['ct_3'][0];
	$ct_4 = $clanData['ct_4'][0];
	$clanleader = $clanData['clan_leader'][0];

	$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);
	$clanMessage = $clanData['clan_message'][0];

	$settings = array('media_buttons' => false, 'editor_height' => 300, 'textarea_name' => 'new_message');
	?>
	<?php include('pages/dashboard/clan-message.php'); ?>
<?php } ?>
<div class="pageSpacer"></div>
<?php include('pages/dashboard/status-column.php'); ?>
<div class="pageSpacer"></div>
<?php include('pages/dashboard/latest-block.php'); ?>
<div class="pageSpacer"></div>
<?php include('pages/dashboard/round-date.php'); ?>

<?php if(get_field('game_status','option') == 'Live') { ?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/medalpositions.php'); ?>
<?php } ?>

<?php //include('pages/dashboard/latest.php'); ?>
</div>

<?php
get_footer();