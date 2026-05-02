<?php
/**
 * Template Name: Users
 */
get_header();

$timestamp = current_time('timestamp');

$allUsers = array();
$users = get_users(array('fields'=>array('ID'),'meta_key'=>'last_online','meta_value'=>$timestamp-1728000,'meta_compare'=>'>'));
foreach($users as $allUser) {
	$userObj = User::make($allUser->ID);
	if($userObj->isBanned()) continue;
	$allUsers[$allUser->ID] = $userObj->getProvince();
}

$activeTab = isset($_GET['tab']) ? Request::get('tab') : 'all';
$backColor = "45, 67, 81";
?>
<div class="row pageRow">
	<form class="fw-row">
		<select id="clan" name="clan" class="searchusers">
			<option></option>
			<?php foreach ($allUsers as $allUser) { ?>
			<option name="clan" value="/users/profile/?id=<?=$allUser->get('id')?>">
				<?=$allUser->getName()?> (#<?=$allUser->get('id')?>)
			</option>
			<?php }?>
		</select>
	</form>

	<div class="pageSpacer"></div>

	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem <?=($activeTab==='all' ? 'active' : '')?>" data-toggle="tab" data-target="#all" href="?tab=all">All</a>
			<a class="nav-item nav-link navItem sort2 <?=($activeTab==='in-range' ? 'active' : '')?>" data-toggle="tab" data-target="#in-range" href="?tab=in-range">In range</a>
			<a class="nav-item nav-link navItem sort3 <?=($activeTab==='online' ? 'active' : '')?>" data-toggle="tab" data-target="#online" href="?tab=online">Online</a>
			<a href="/all-clans" class="nav-item nav-link navItem u-bg-3">All clans</a>
		</nav>
	</div>

	<div class="tab-content tabbed-table">
		<div class="tab-pane <?=($activeTab==='all' ? 'active' : '')?>" id="all" role="tabpanel">
			<? include 'pages/users/all.php'; ?>
		</div>

		<div class="tab-pane <?=($activeTab==='in-range' ? 'active' : '')?>" id="in-range" role="tabpanel">
			<? include 'pages/users/inrange.php'; ?>
		</div>

		<div class="tab-pane <?=($activeTab==='online' ? 'active' : '')?>" id="online" role="tabpanel">
			<? include 'pages/users/online.php'; ?>
		</div>
	</div>
</div>

<?php
get_footer();