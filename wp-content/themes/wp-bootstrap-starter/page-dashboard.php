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


$savedUsers 				= $userData['saved_users'][0];
$decodedSavedUsers          = json_decode($savedUsers);
$savedUsers 				= is_array($decodedSavedUsers) ? $decodedSavedUsers : [];
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

/* Check for nightmode */

$nightmode = $userData['nightmode'][0];
$regular = '';
if($nightmode == 'regular'){
	$regular = 'selected';
}
$night = '';
if($nightmode == 'night'){
	$night = 'selected';
}
$nostalgia = '';
if($nightmode == 'nostalgia'){
	$nostalgia = 'selected';
}
$blackwhite = '';
if($nightmode == 'blackwhite'){
	$blackwhite = 'selected';
}
$grayscale = '';
if($nightmode == 'grayscale'){
	$grayscale = 'selected';
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

if($clanId == 0){
    $clans = get_posts(
        [
            'numberposts'	=> -1,
            'post_type'		=> 'clan',
            'meta_key'		=> 'autojoin_allowed',
            'meta_value'	=> 'yes'
        ]
    );

    $clanCount = 0;

    foreach ($clans as $clan) {
        $members = count(get_post_meta($clan->ID,'clan_members',true));
        if ($members < 7) {
            $clanCount++;
        }
    }
}
?>

<div class="row pageRow">		
	
		

<?php if(get_field('game_status','option') == 'Pause' /*&& $userId != 1*/): // Check if game is live or not ?>
	<?php include('pages/dashboard/status-column.php'); ?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/latest-block.php'); ?>	
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/round-date.php'); ?>

<?php else: // If game is live ?> 
	<?php if($gameType == 'Development'):?>
	<div class="blockHeader">Welcome to dev.assault.online. To receive turns/money/morale, hit the button below!</div>
	<div class="blockHeader spaceNotice">If you are dead, hitting this button will revive you as well.</div>
	<button style="background-color:#A00000;border:0px;" class="mainSubmit receiveFunds">Receive funds</button>
	<div class="pageSpacer"></div>
	
	
	<script>
(function($) {
	var devfunding;
	
	$(document).on('click','.receiveFunds',function(){
	
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
	var target = $(this).attr('data-target');
		devfunding = $.ajax({
			url: "/devfunds.php",
			type: "post",
			data: ''
		});
						
						// Callback handler that will be called on success
						devfunding.done(function (response, textStatus, jqXHR){
							
							var response = $.parseJSON(response);
							
							$('#money').html(number_format(response.money, 0, ',', ' '));
							$('#morale').html(number_format(response.morale, 0, ',', ' '));
							$('#turns').html(number_format(response.turns, 0, ',', ' '));
							
							console.log(response);
							$.notify({
								message: response.status,
								},{
								type: 'info',
								delay: 5000,
								template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
											'<i class="fa fa-info-circle"></i> ' +
											'' +
											'<span data-notify="message">{2}</span>' +
											'</div>'
							});	
						
						
					
					});
				});

})(jQuery);
</script>
	
	
	
	
	<?php endif;?>

	<div class="blockHeader">Problems with attacking or other display problems? Clear your browser cache!</div>
	<div class="blockHeader spaceNotice">Not sure how to do this? Check out <a target="_blank" href="https://refreshyourcache.com/en/home/">refreshyourcache.com</a></div>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/pick-startingbonus.php'); ?>
	<?php include('pages/dashboard/bonus-receive.php'); ?>
	<?php if($clanId != 0):?>
		<?php 	
				$clanData = get_post_meta($clanId);
				
				$ct_1 = $clanData['ct_1'][0];
				$ct_2 = $clanData['ct_2'][0];
				$ct_3 = $clanData['ct_3'][0];
				$ct_4 = $clanData['ct_4'][0];
				$clanleader = $clanData['clan_leader'][0];
				
				$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);
				$clanMessage = $clanData['clan_message'][0];
				
				$settings = array( 
					'media_buttons' => false,
					'editor_height' => 300,
					'textarea_name' => 'new_message' );
		?>
		<?php include('pages/dashboard/clan-message.php'); ?>
	<?php endif;?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/status-column.php'); ?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/latest-block.php'); ?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/round-date.php'); ?>
	<div class="pageSpacer"></div>
	<?php include('pages/dashboard/medalpositions.php'); ?>

	<?php //include('pages/dashboard/latest.php'); ?>

<?php endif; // End game is live check. After this, no more content. ?>

</div>

<?php
get_footer();