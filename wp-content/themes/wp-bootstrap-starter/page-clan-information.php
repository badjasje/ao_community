<?php
 /*
 * Template Name: Clan Information
*/
get_header();

global $userData;
global $userId;

$clan_id_user = $userData['clan_id_user'][0];
$clanCreate = $userData['clan_create_counter'][0];

$clanData = get_post_meta($clan_id_user);

$clan_leader = $clanData['clan_leader'][0];
$autojoin = $clanData['autojoin_allowed'][0];
$autojoinDesc = $clanData['autojoin_description'][0];
$playstyle = $clanData['autojoin_playstyle'][0];

$autojoinYes = '';
$autojoinNo = '';

if($autojoin == 'yes'){
	$autojoinYes = 'selected="selected"';
}
if($autojoin == 'no'){
	$autojoinNo = 'selected="selected"';
}

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];

$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clan_leader);

$clans = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'clan',
	'meta_key'		=> 'autojoin_allowed',
	'meta_value'	=> 'yes'
));

$clanCount = 0;
foreach ($clans as $clan) {
	$members = count(get_post_meta($clan->ID,'clan_members',true));
	if($members < 7){
		$clanCount++;
	}
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