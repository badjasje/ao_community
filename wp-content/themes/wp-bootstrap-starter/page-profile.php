<?php
/**
 * Template Name: Profile page
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$viewed_id = Request::get('id');
if(empty($viewed_id)) Request::redirect('/dashboard');
$viewed = User::make($viewed_id);
$viewed_province = $viewed->getProvince();
$viewed_province->count_all_stats();
$viewed_clan = $viewed_province->getClan();
$telegram_key = $viewed_province->get('telegram_key');
if(empty($telegram_key)) {
	$telegram_key = uniqid();
	$viewed_province->update('telegram_key', $telegram_key);
}
?>
<div class="row pageRow">

	<div class="blockHeader"><?=$viewed_province->getLink(true)?></div>

	<div class="d-flex fw-row">
		<div class="eventImageCol">
			<?=$viewed->getAvatar('profileAvatar')?>
		</div>

		<div class="w-100">
			<div class="attackingRow statCol-1">
				<div class="profileColumn">ID</div> <?=$viewed->get('id')?>
			</div>

			<div class="attackingRow statCol-2 elipOverflow">
				<div class="profileColumn">Player name</div> <?=$viewed->getName()?>
			</div>

			<div class="attackingRow statCol-3">
				<?
				if(!$viewed->isBanned()) {
					$aw_args = array('post_type' =>	'medal', 'numberposts' => -1, 'meta_key' => 'winning_user', 'meta_value' => $viewed->get('id'));
					$medals = get_posts($aw_args);
					if(count($medals) == 0) {
						echo '<div class="profileColumn">Medals</div> none';
					} else { ?>
						<h3>Medals</h3>
						<? foreach($medals as $medal) {
							$round = get_post_meta($medal->ID, 'medal_round', true);
							?>
							<i class="fa fa-star fa-lg" aria-hidden="true"></i> &nbsp;<?=$round?>:
							<strong><?=$medal->post_title?></strong><br/>
						<? }
					}
				} else echo '<br/>n.a';
				?>
			</div>
			<div class="attackingRow statCol-4">
				<div class="profileColumn">Registered</div> <?=date("d M Y", strtotime($viewed->get('registered')))?>
			</div>
			<div class="attackingRow statCol-3">
				<div class="profileColumn">Networth</div> <?=$viewed_province->getNetworth(true)?>
			</div>
			<div class="attackingRow statCol-2">
				<div class="profileColumn">Land</div> <?=$viewed_province->getLand(true)?>
			</div>
			<div class="attackingRow statCol-1 elipOverflow">
				<div class="profileColumn">Clan</div>
				<?
				if(!$viewed_clan) echo 'None';
				else {?>
					<a href="<?=$viewed_clan->getLink()?>"><?=$viewed_clan->getName()?> (#<?=$viewed_clan->get('id')?>)</a>
				<? } ?>
			</div>
			<?php if($viewed->get('id') == $user->get('id')) { ?>
			<div class="attackingRow statCol-2">
				<div class="profileColumn">Push notifications</div>
				Install <a href="https://t.me/assaultonlinebot" style="text-decoration:underline;" target="_blank">Telegram</a> on your mobile
				device and use this code <strong><?=$telegram_key?></strong> to get instant notifications.</a>
			</div>
			<div class="attackingRow statCol-1">
				<div class="profileColumn">Recruitment link</div>
				<input type="text" id="referralInput" class="w-50" value="<?=Request::siteUrl()?>/register/?referral_userid=<?=$viewed->get('id')?>">
			</div>
			<?php } ?>
		</div>
	</div>
<?php
// I am not ready yet to change all the code below, so we fix it by setting some used variables
$status = $viewed_province->get('status');
$visiting_user = $province->get('id');
$visiting_clan = $province->getClan();
$clan_id_user = ($visiting_clan ? $visiting_clan->get('id') : 0);
$clan_id = ($viewed_clan ? $viewed_clan->get('id') : 0);
$members = ($visiting_clan ? $visiting_clan->getMembers() : array());
$previous_members = ($visiting_clan ? $visiting_clan->getPreviousMembers() : array());
$CT_CL_array = ($visiting_clan ? array_merge($visiting_clan->getTrustees(), array($visiting_clan->getLeader())) : array());
$game_live = (get_field('game_status','option')=='Live');

$count = 0;
?>
<?php if($visiting_user != $viewed_id && ($clan_id != $clan_id_user || $clan_id == 0) && !in_array($visiting_user, $CT_CL_array)):?>
<?php $count = 1;?>

<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewed_id)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewed_id)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewed_id;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
</div>
<?php endif;?>

<?php if($visiting_user != $viewed_id && $clan_id != $clan_id_user && in_array($visiting_user, $CT_CL_array) && count($members) == 7):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewed_id)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewed_id)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewed_id;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
</div>
<?php endif;?>


<?php if($visiting_user != $viewed_id && $clan_id != $clan_id_user && $clan_id == 0 && in_array($visiting_user, $CT_CL_array) && count($members) < 6):?>
<?php $count = 1;?>

<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewed_id)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewed_id)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewed_id;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewed_id, $previous_members)):?>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="#">
			<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Cannot invite
		</a>
	<?php else:?>
		<a class="col-md-3 profileButton inviteButton" style="background-color: rgba(70, 118, 94, 0.7);" href="javascript:void(0);">
			<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Send clan invite
		</a>
	<?php endif;?>
	<script>
		(function($) {
			var request;
			$('.inviteButton').on('click', function(event) {
				event.preventDefault();
				if (request) request.abort();
				if(confirm('Are you sure you want to invite <?php echo $viewed->display_name;?> (#<?php echo $viewed_id;?>)?')) {
					request = $.ajax({url: '/invite.php?user=<?php echo $viewed_id;?>', type: "get"});
					request.done(function (response, textStatus, jqXHR) {
						var array = JSON.parse(response);
						$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
						if(array.next == true){
							$('.inviteButton').addClass('disabled');
						}
					});
				}
			});
		})(jQuery);
	</script>
</div>
<?php endif;?>

<?php if($visiting_user != $viewed_id && $clan_id != $clan_id_user && $count != 1 && in_array($visiting_user, $CT_CL_array)):?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewed_id)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewed_id)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewed_id;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
</div>
<?php endif;?>

<?php if($clan_id == $clan_id_user && $count != 1 && $visiting_user != $viewed_id):?>
<!-- Visiting clanmember profile -->
<div class="row fw-row no-gutters profileButtonRow">
	<a class="col-md-6 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/military-overview/?id='.$viewed_id)?>">
		<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview
	</a>

	<a class="col-md-6 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/send-message/?id=<?php echo $viewed_id;?>">
		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>
</div>
<?php endif;?>

<?php if($visiting_user == $viewed_id):?>
<!-- visiting own profile -->
<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/military-overview/?id='.$viewed_id)?>">
		<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview
	</a>

 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/users/profile/edit/">
 		<i class="fa fa-wrench" aria-hidden="true"></i> &nbsp;Edit your profile
 	</a>

 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/player-statistics/">
 		<i class="fas fa-chart-line"></i> &nbsp;View statistics
	</a>
</div>
<?php endif;?>

<?php
echo $province->get_spy_buttons($viewed_id);

if($user->isAdmin()) {
	$viewedUser = User::make($viewed_id);
	if(isset($_GET['makewhitelist'])) $viewedUser->update('multi_whitelist', $_GET['makewhitelist']);
	$referral_code = $viewedUser->get('referral_code');
	$multi_whitelist = $viewedUser->get('multi_whitelist');
	?>
	<center><a target="_blank" href="/wp-admin/user-edit.php?user_id=<?php echo $viewed_id;?>&wp_http_referer=%2Fwp-admin%2Fusers.php">Backend edit</a></center>
	<?php
	echo '<p>Referral: '.$viewedUser->get('referral_userid').',
		score: '.$viewedUser->get('referral_score').', '.(is_array($referral_code)?implode(', ',$referral_code):'none').' </p>';
	echo '<p>Multi whitelist: '. ($multi_whitelist==1?'yes':'no') .'</p>';
	echo '<div class="logindata">'. $viewedUser->getLoginData(true) .'</div>';
}
?>

</div>
<?php
get_sidebar();
get_footer();
