<?php
 /*
 * Template Name: Users
*/
get_header();
global $userData;
global $userId;
$backColor = "45, 67, 81";
$timestamp = current_time('timestamp');
$args = array(
	'meta_key'     	=> 'last_online',
	'orderby'      	=> 'meta_value_num',
	'meta_value'	=> $timestamp-1728000,
	'meta_compare'	=> '>',

); 
$allUsers = get_users($args);

$networth_you = $userData['networth'][0];
include 'constants.php';
include 'attack_functions.php';



 ?>

<div class="row pageRow">	
	
	
<div class="fw-row">
	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#all" href="?tab=all">All</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#in-range" href="?tab=in-range">In range</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#online" href="?tab=online">Online</a>
		<a class="nav-item nav-link navItem" href="/all-clans" style="background-color: rgba(70, 118, 94, 0.8);">All clans</a>
	</nav>
</div>

<div class="tab-content tabbed-table">
	<div class="tab-pane active" id="all" role="tabpanel">
				
		<?php include 'pages/users/all.php'; ?>
				
	</div> <!-- // End tab pane 1 -->

	<div class="tab-pane "  id="in-range" role="tabpanel">
	
		<?php include 'pages/users/inrange.php'; ?>
	
	</div> <!-- // End pane 2 -->

	<div class="tab-pane"  id="online" role="tabpanel">

		<?php include 'pages/users/online.php'; ?>

	</div>
</div>
	
	
</div>
<?php
get_footer();