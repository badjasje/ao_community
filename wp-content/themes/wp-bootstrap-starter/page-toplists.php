<?php
 /*
 * Template Name: Toplists
*/
if(!is_user_logged_in()) {
	exit(wp_redirect(home_url('/')));
}

get_header();
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'provincenw';
$toplistArray = maybe_unserialize(get_field('toplistarray','option'));
$backColorPNW = "127, 82, 67";
$backColorCP = "45, 67, 81";
$backColorCNW = "86, 113, 61";
$backColor24h = "126, 100, 68";
?>

<div class="row pageRow">


<div class="fw-row">
	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem <?php echo $activeTab === 'provincenw' ? 'active' : ''; ?>" data-toggle="tab" data-target="#provicenw" href="?tab=provicenw">Province Networth</a>
		<a class="nav-item nav-link navItem <?php echo $activeTab === 'clanpoints' ? 'active' : ''; ?>" data-toggle="tab" data-target="#clanpoints" href="?tab=clanpoints">Clan Points</a>
		<a class="nav-item nav-link navItem <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>" data-toggle="tab" data-target="#clannw" href="?tab=clannw">Clan Networth</a>
		<a class="nav-item nav-link navItem <?php echo $activeTab === 'clanpointstoday' ? 'active' : ''; ?>" data-toggle="tab" data-target="#clanpointstoday" href="?tab=clanpointstoday">Clan points today</a>
	</nav>
</div>

<div class="tab-content current tabbed-table">
	<div class="tab-pane <?php echo $activeTab === 'provincenw' ? 'active' : ''; ?>" id="provicenw" role="tabpanel">

		<?php include 'pages/toplist/provice_nw.php'; ?>

	</div>

	<div class="tab-pane <?php echo $activeTab === 'clanpoints' ? 'active' : ''; ?>"  id="clanpoints" role="tabpanel">

		<?php include 'pages/toplist/clan_points.php'; ?>

	</div>

	<div class="tab-pane <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>"  id="clannw" role="tabpanel">

		<?php include 'pages/toplist/clan_nw.php'; ?>

	</div>

	<div class="tab-pane <?php echo $activeTab === 'clanpointstoday' ? 'active' : ''; ?>"  id="clanpointstoday" role="tabpanel">

		<?php include 'pages/toplist/clan_points_today.php'; ?>

	</div>
</div>




</div> <!-- end .pageRow -->
<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
    });
</script>
<?php
get_footer();