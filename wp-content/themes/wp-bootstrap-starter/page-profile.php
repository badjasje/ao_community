<?php
 /*
 * Template Name: Profile page
*/
get_header();
$viewedId = $_GET['id'];
global $userId;
$visiting_user = $userId;
if(empty($viewedId)){
	wp_redirect(get_permalink(3486));
}
$user = get_userdata($viewedId);
if ( $user === false ) {
	wp_redirect(get_permalink(3486));
}
count_all_stats($viewedId);

$profileData = get_user_meta($viewedId);
$user_NW = $profileData['networth'][0];
$status = $profileData['status'][0];
$user_land = $profileData['land'][0];
$clan_id = $profileData['clan_id_user'][0];
$timestamp = current_time('timestamp');
$clan_timestamp = $profileData['new_clan_timestamp'][0];
$telegram_key = $profileData['telegram_key'][0];
if(empty($telegram_key)) {
	$telegram_key = uniqid();
	update_user_meta($viewedId, 'telegram_key', $telegram_key);
}

include('country_array.php');
$user_country_code = $profileData['user_country'][0];

$last_online = $profileData['last_online'][0];
if(!empty($last_online)){
	$last_seen = $timestamp - $last_online;
}

$visitorData = get_user_meta($visiting_user);

$savedUsers = $visitorData['saved_users'][0];
$savedUsers = json_decode($savedUsers);
$savedUsers = is_array($savedUsers) ? $savedUsers : [];

$clan_id_user = $visitorData['clan_id_user'][0];

$visitorClanData = get_post_meta($clan_id_user);

$previous_members = maybe_unserialize(get_post_meta($clan_id_user, 'previous_members', true));
if(!is_array($previous_members) || get_field('game_status', 'option') == 'Pause') $previous_members = array();

$ct_1 = $visitorClanData['ct_1'][0];
$ct_2 = $visitorClanData['ct_2'][0];
$ct_3 = $visitorClanData['ct_3'][0];
$ct_4 = $visitorClanData['ct_4'][0];
$cl_1 = $visitorClanData['clan_leader'][0];

$CT_CL_array = array($ct_1,$ct_2,$ct_3,$ct_4,$cl_1);
$members = $visitorClanData['clan_members'][0];
if(!is_array($members)) $members = array();

$game_live = (get_field('game_status','option')=='Live');
?>

<div class="row pageRow">

	<div class="blockHeader">
		<?php echo get_user_name($viewedId); ?>
	</div>

	<div class="row row-no-padding fw-row">
		<div class="col-xs-2 col-no-padding eventImageCol" style="border-right: 1px solid #fff;">
			<?php echo small_avatar($viewedId,'profileAvatar');?>
		</div>

		<div class="col-xs-10 col-no-padding" style="flex:100">
			<div class="col-12 attackingRow statCol-1">
				<div class="profileColumn">ID</div> <?php echo $viewedId;?>
			</div>

			<div class="col-12 attackingRow statCol-2 elipOverflow">
				<div class="profileColumn">Player name</div> <?php echo $user->display_name;?>
			</div>

			<div class="col-12 attackingRow statCol-3">
				<?php if($status != 'banned'):?>
					<?php
						$aw_args = array(
							'post_type'		=>	'medal',
							'numberposts' 	=> 	-1,
							'meta_key' 		=> 	'winning_user',
							'meta_value'    => 	$viewedId
						);

						$medals = get_posts($aw_args);

						if(count($medals) == 0): ?>
							<div class="profileColumn">Medals</div> none
						<?php else:?>
							<h3>Medals</h3>
							<?php foreach ($medals as $medal) {
								$round = get_post_meta($medal->ID, 'medal_round', true);
								?>
								<i class="fa fa-star fa-lg" aria-hidden="true"></i> &nbsp;<?php echo $round;?>:
								<strong><?php echo $medal->post_title;?></strong><br/>
							<?php } ?>
						<?php endif;?>
				<?php else:?>
					<br/>n.a
				<?php endif;?>
			</div>
			<div class="col-12 attackingRow statCol-4">
				<div class="profileColumn">Registered</div> <?php echo date( "d M Y", strtotime( $user->user_registered ));?>
			</div>
			<div class="col-12 attackingRow statCol-3">
				<div class="profileColumn">Networth</div> <?php echo networth_range($viewedId);?>
			</div>
			<div class="col-12 attackingRow statCol-2">
				<div class="profileColumn">Land</div> <?php echo number_format($user_land, 0, ',', ' ')?>m<sup>2</sup>
			</div>
			<div class="col-12 attackingRow statCol-1 elipOverflow">
				<div class="profileColumn">Clan</div>
				<?php if($clan_id == 0):?>
					None
				<?php else:?>
					<a href="<?php echo get_the_permalink($clan_id);?>"><?php echo get_the_title($clan_id);?> (#<?php echo $clan_id;?>)</a>
				<?php endif;?>
			</div>
			<?php if($viewedId == $visiting_user) { ?>
			<div class="col-12 attackingRow statCol-2">
				<div class="profileColumn">Push notifications</div>
				Install <a href="https://t.me/assaultonlinebot" style="text-decoration:underline;" target="_blank">Telegram</a> on your mobile
				device and use this code <strong><?php echo $telegram_key ?></strong> to get instant notifications.</a>
			</div>
			<?php } ?>
		</div>
	</div>


<?php $count = 0;?>
<?php if($visiting_user != $viewedId && ($clan_id != $clan_id_user || $clan_id == 0) && !in_array($visiting_user, $CT_CL_array)):?>
<?php $count = 1;?>

<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewedId)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewedId)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewedId;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewedId, $savedUsers)):?>
		<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
			<i class="fas fa-save"></i> &nbsp;User saved
		</a>
	<?php else:?>

		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $viewedId;?>&return=<?php echo get_the_id();?>">
			<i class="fas fa-save"></i> &nbsp;Save user
		</a>
	<?php endif;?>

</div>
<?php endif;?>

<?php if($visiting_user != $viewedId && $clan_id != $clan_id_user && in_array($visiting_user, $CT_CL_array) && count($members) == 7):?>
<?php $count = 1;?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewedId)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewedId)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewedId;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewedId, $savedUsers)):?>
		<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
			<i class="fas fa-save"></i> &nbsp;User saved
		</a>
	<?php else:?>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $viewedId;?>&return=<?php echo get_the_id();?>">
			<i class="fas fa-save"></i> &nbsp;Save user
		</a>
	<?php endif;?>

</div>
<?php endif;?>


<?php if($visiting_user != $viewedId && $clan_id != $clan_id_user && $clan_id == 0 && in_array($visiting_user, $CT_CL_array) && count($members) < 6):?>
<?php $count = 1;?>

<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-2 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewedId)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-2 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewedId)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewedId;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewedId, $previous_members)):?>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="#">
			<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Cannot invite
		</a>
	<?php else:?>
		<a style="background-color: rgba(70, 118, 94, 0.7);" class="col-md-3 profileButton inviteButton" href="javascript:void(0);">
			<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;Send clan invite
		</a>
	<?php endif;?>

	<?php if(in_array($viewedId, $savedUsers)):?>
		<a class="col-md-2 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
			<i class="fas fa-save"></i> &nbsp;User saved
		</a>
	<?php else:?>
		<a class="col-md-2 profileButton" style="background-color: rgba(70, 118, 94, 0.6);" href="/save_user.php/?id=<?php echo $viewedId;?>&return=<?php echo get_the_id();?>">
			<i class="fas fa-save"></i> &nbsp;Save user
		</a>
	<?php endif;?>
	<script>
		(function($) {
			var request;
			$('.inviteButton').on('click', function(event) {
				event.preventDefault();
				if (request) request.abort();
				if(confirm('Are you sure you want to invite <?php echo $user->display_name;?> (#<?php echo $viewedId;?>)?')) {
					request = $.ajax({url: '/invite.php?user=<?php echo $viewedId;?>', type: "get"});
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

<?php if($visiting_user != $viewedId && $clan_id != $clan_id_user && $count != 1 && in_array($visiting_user, $CT_CL_array)):?>
<!-- Visiting non-clanmember as non CT/CL -->
<div class="row fw-row no-gutters profileButtonRow">
 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/attack/?id='.$viewedId)?>">
		<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
	</a>

 	<a class="col-md-3 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 0.9);"
	 	href="<?=(!$game_live?'javascript:void(0);':'/spy-reports/?id='.$viewedId)?>">
 		<i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
 	</a>

 	<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/send-message/?id=<?php echo $viewedId;?>">
 		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewedId, $savedUsers)):?>
		<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
			<i class="fas fa-save"></i> &nbsp;User saved
		</a>
	<?php else:?>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $viewedId;?>&return=<?php echo get_the_id();?>">
			<i class="fas fa-save"></i> &nbsp;Save user
		</a>
	<?php endif;?>

</div>
<?php endif;?>

<?php if($clan_id == $clan_id_user && $count != 1 && $visiting_user != $viewedId):?>
<!-- Visiting clanmember profile -->
<div class="row fw-row no-gutters profileButtonRow">

	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/military-overview/?id='.$viewedId)?>">
		<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview
	</a>

	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/send-message/?id=<?php echo $viewedId;?>">
		<i class="fas fa-envelope" aria-hidden="true"></i> &nbsp;Send message
	</a>

	<?php if(in_array($viewedId, $savedUsers)):?>
		<a class="col-md-4 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
			<i class="fas fa-save"></i> &nbsp;User saved
		</a>
	<?php else:?>
		<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/save_user.php/?id=<?php echo $viewedId;?>&return=<?php echo get_the_id();?>">
			<i class="fas fa-save"></i> &nbsp;Save user
		</a>
	<?php endif;?>

</div>
<?php endif;?>

<?php if($visiting_user == $viewedId):?>
<!-- visiting own profile -->
<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-4 profileButton<?=(!$game_live?' disabled':'')?>" style="background-color: rgba(70, 118, 94, 1);"
		href="<?=(!$game_live?'javascript:void(0);':'/military-overview/?id='.$viewedId)?>">
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
// If I have spy units, and I can spy this person
if($visiting_user != $viewedId && $clan_id != $clan_id_user && !in_array($status, array('dead','banned','nukeprotection'))) {
	$spiesOwned = get_spy_units($userId);
	if(count($spiesOwned)) {
		$btnClass = (count($spiesOwned)==2?'col-md-6':'col-md-12');
		echo '<div class="row no-gutters fw-row profileButtonRow">';
		foreach($spiesOwned as $key => $name) {
			$url = get_site_url().'/attack/?id='.$viewedId.'&attacktype=spy&attackmode=normal&maintarget=none&spytype='.$key;
			?>
			<a class="<?=$btnClass?> profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="<?=$url?>">
				<i class="fas fa-binoculars"></i> &nbsp;Send <?=$name?>
			</a><?
		}
		echo '</div>';
	}
}
?>

<?php if(current_user_can('activate_plugins')){
	$logindata = get_user_meta( $viewedId, 'logindata', true );
	$referral_userid = get_user_meta($viewedId, 'referral_userid', true);
	$referral_score = get_user_meta($viewedId, 'referral_score', true);
	$referral_code = get_user_meta($viewedId, 'referral_code', true);
	?>
	<center><a target="_blank" href="/wp-admin/user-edit.php?user_id=<?php echo $viewedId;?>&wp_http_referer=%2Fwp-admin%2Fusers.php">Backend edit</a></center>
	<?php
	echo '<p>Referral: '.$referral_userid.', score: '.$referral_score.', '.(is_array($referral_code)?implode(', ',$referral_code):'none').' </p>';
	echo '<pre>';
	print_r($logindata);
	echo '</pre>';
}?>

</div>
<?php
get_sidebar();
get_footer();
