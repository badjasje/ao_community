<?php
 /*
 * Template Name: Spy reports single
*/
if(!is_user_logged_in()) {
	exit(wp_redirect(home_url('/')));
}

$user = CurrentUser::make();
$province = $user->getProvince();

get_header();

global $userId;
$target_id = $_GET['id'];

$user = get_userdata($target_id);

$clan_ID = get_user_meta($userId, 'clan_id_user',true);
$target_clan_ID = get_user_meta($target_id, 'clan_id_user',true);

$members = get_post_meta($clan_ID,'clan_members',true);
$members[] = $userId;

$profileData = get_user_meta($target_id);
$status = $profileData['status'][0];

?>
<div class="row pageRow">
	<div class="row fw-row no-gutters profileButtonRow">
		<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $target_id;?>">
			<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
		</a>
		<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/users/profile/?id=<?php echo $target_id;?>">
			<i class="fa fa-user" aria-hidden="true"></i> &nbsp;Profile
		</a>
		<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/spy-report-overview/?id=<?php echo $target_clan_ID;?>">
			<i class="fas fa-address-card" aria-hidden="true"></i> &nbsp;Clan reports
		</a>
	</div>

	<?
	echo $province->get_spy_buttons($target_id);
	?>

	<div class="pageSpacer"></div>
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem w-50 active" data-toggle="tab" data-target="#buildings" href="?tab=buildings">Buildings</a>
			<a class="nav-item nav-link navItem w-50" data-toggle="tab" data-target="#units" href="?tab=units">Units</a>
		</nav>
	</div>

	<div class="tab-content current tabbed-table">
		<div class="tab-pane active"  id="buildings" role="tabpanel">
			<?php include 'pages/spyrep/buildings.php'; ?>
		</div> <!-- // End tab pane 1 -->
		<div class="tab-pane"  id="units" role="tabpanel">
			<?php include 'pages/spyrep/units.php'; ?>
		</div> <!-- // End pane 2 -->
	</div>
</div> <!-- end .pageRow -->
<?php
get_footer();