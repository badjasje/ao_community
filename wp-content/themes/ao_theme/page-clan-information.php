<?php
/**
 * Template Name: Clan Information
 */
get_header();

global $userData;
global $userId;

$clan_id_user = $userData['clan_id_user'][0];
$clanCreate = $userData['clan_create_counter'][0];
if(Round::isDev() || Round::isTest()) $clanCreate = 0;

if($clan_id_user != 0) {
	$clanData = get_post_meta($clan_id_user);

	$clan_leader = $clanData['clan_leader'][0];

	$cts=array();
	for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
		$cts[$i] = (isset($clanData['ct_'.$i]) ? $clanData['ct_'.$i][0] : 0);
	}
	$allowed = array_merge($cts,array($clan_leader));
}
?>

<div class="row pageRow">
<?php if($clan_id_user != 0) {?>
	<?php include('pages/view-clan/member.php'); ?>
	<?php if(in_array($userId, $allowed)) {?>
		<div class="pageSpacer"></div>
		<?php include('pages/view-clan/message-all-members.php'); ?>
	<?php } ?>
<?php } else { ?>
	<?php include('pages/view-clan/nonmember.php'); ?>
<?php } ?>
</div> <!-- // pageRow -->
<?php
get_footer();