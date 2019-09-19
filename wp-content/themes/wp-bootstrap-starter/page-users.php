<?php
/**
 * Template Name: Users
 */
get_header();

$transient = get_transient('allusers_query');
if(!empty($transient)) $users = $transient;
else {
    $args = array('meta_query'=> array(array(
		'relation' => 'AND',
		array('key' => 'last_online', 'value' => $timestamp-1728000, 'compare' => ">", 'type' => 'numeric'),
		array('key' => 'networth', 'value' => 10, 'compare' => ">", 'type' => 'numeric'),
	)));
	$users = get_users($args);
	set_transient('allusers_query', $users, 12 * 60 * 60);
}

$allUsers = array();
foreach($users as $allUser) {
	$userObj = User::make($allUser->ID);
	if($userObj->isBanned()) continue;
	$allUsers[$allUser->ID] = $userObj->getProvince();
}

$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'all';
$backColor = "45, 67, 81";
?>
<div class="row pageRow">
	<form class="fw-row">
		<select id="clan" name="clan" class="searchusers">
			<option></option>
			<? foreach ($allUsers as $allUser) { ?>
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
			<a class="nav-item nav-link navItem" href="/all-clans" style="background-color: rgba(70, 118, 94, 0.8);">All clans</a>
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

<script type="text/javascript">
	jQuery(function($) {
		$(".searchusers").select2({placeholder: "Start typing to find a player"});
		$(document).on('shown.bs.tab', function (event) {
			history.pushState(null, null, $(event.target).attr('href'));
		});
		$('.searchusers').on('change', function() {
			if($(this).val()) window.location.href = $(this).val();
		});
	});
</script>
<?php
get_footer();