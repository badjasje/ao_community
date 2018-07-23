<?php
 /*
 * Template Name: Toplists
*/
get_header(); 
$toplistArray = maybe_unserialize(get_field('toplistarray','option'));
$backColorPNW = "127, 82, 67";
$backColorCP = "45, 67, 81";
$backColorCNW = "86, 113, 61";
$backColor24h = "126, 100, 68";
?>

<div class="row pageRow">	
	
	
<div class="fw-row">
	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#provicenw" href="?tab=provicenw">Province Networth</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#clanpoints" href="?tab=clanpoints">Clan Points</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#clannw" href="?tab=clannw">Clan Networth</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#clanpointstoday" href="?tab=clanpointstoday">Clan points today</a>
	</nav>
</div>

<div class="tab-content current tabbed-table">
	<div class="tab-pane active" id="provicenw" role="tabpanel">
				
		<?php include 'pages/toplist/provice_nw.php'; ?>
				
	</div>

	<div class="tab-pane"  id="clanpoints" role="tabpanel">
	
		<?php include 'pages/toplist/clan_points.php'; ?>
	
	</div>

	<div class="tab-pane"  id="clannw" role="tabpanel">

		<?php include 'pages/toplist/clan_nw.php'; ?>

	</div>
	
	<div class="tab-pane"  id="clanpointstoday" role="tabpanel">

		<?php include 'pages/toplist/clan_points_today.php'; ?>

	</div>
</div>

	
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();