<?php
 /*
 * Template Name: Spy reports single
*/
if(!is_user_logged_in()) {
	exit(wp_redirect(home_url('/')));
}

get_header();

global $userId;
$target_id = $_GET['id'];

$user = get_userdata($target_id);

$clan_ID = get_user_meta($userId, 'clan_id_user',true);
$target_clan_ID = get_user_meta($target_id, 'clan_id_user',true);

$savedUsers = get_user_meta($userId, 'saved_users', true);
$savedUsers = json_decode($savedUsers);

$members = get_post_meta($clan_ID,'clan_members',true);
$members[] = $userId;

?>
<div class="row pageRow">
	<div class="row fw-row no-gutters profileButtonRow">
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/attack/?id=<?php echo $target_id;?>">
			<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
		</a>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/users/profile/?id=<?php echo $target_id;?>">
			<i class="fa fa-user" aria-hidden="true"></i> &nbsp;Profile
		</a>
		<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/spy-report-overview/?id=<?php echo $target_clan_ID;?>">
			<i class="fas fa-address-card" aria-hidden="true"></i> &nbsp;Clan reports
		</a>
		<?php if(in_array($user__ID, $savedUsers)):?>
			<a class="col-md-3 profileButton" style="background-color: rgba(0, 0, 0, 0.5)" href="/saved-users">
				<i class="fas fa-save"></i> &nbsp;User saved
			</a>
		<?php else:?>
			<a class="col-md-3 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="/save_user.php/?id=<?php echo $user__ID;?>&return=<?php echo get_the_id();?>">
				<i class="fas fa-save"></i> &nbsp;Save user
			</a>
		<?php endif;?>
	</div>

	<div class="pageSpacer"></div>
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#buildings" href="?tab=buildings">Buildings</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#units" href="?tab=units">Units</a>
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