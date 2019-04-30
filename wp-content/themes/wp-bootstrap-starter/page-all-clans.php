<?php
 /*
 * Template Name: All Clans
*/
get_header();
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'all';

global $userData;
global $userId;

$user_ID = $userId;
$clan_ID = $userData['clan_id_user'][0];
$backColor = "45, 67, 81";

$transient = get_transient( 'allclans_query' );

if( ! empty( $transient ) ) {
	$clans = $transient;
} else {
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'clan'
	);
	$clans = get_posts($args);
	set_transient( 'allclans_query', $clans, 12 * 60 * 60 );
}
?>

<div class="row pageRow">

	<? if(empty($clan_ID)) { ?>
	<div class="blockHeader noticeBlock">
		<strong>Play together?</strong>
		Join us on <a href="http://bit.ly/2US8Dh0" style="text-decoration:underline" target="_blank">discord</a> and find the clan that fits your playstyle.
	</div>
	<div class="pageSpacer"></div>
	<? } ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".searchclans").select2({
				placeholder: "Start typing to find a clan"
			});
		});
	</script>

	<form class="fw-row">
		<select id="clan" name="clan" class="searchclans" onchange="if (this.value) window.location.href=this.value">
			<option></option>
			<?php foreach ($clans as $clan) {
				$clanId = $clan->ID;
				?>
				<option name="clan" value="<?php echo get_the_permalink( $clanId );?>">
					<a href="<?php echo get_the_permalink( $clanId );?>">
					<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)</a>
				</option>
			<?php }?>
		</select>
	</form>

	<div class="pageSpacer"></div>

	<div class="fw-row">
		<nav id="allthetabs" class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'all' ? 'active' : ''; ?>" data-toggle="tab" data-target="#all" href="?tab=all">All</a>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>" data-toggle="tab" data-target="#in-range" href="?tab=in-range">In range</a>
			<a class="nav-item nav-link navItem" href="/users" style="background-color: rgba(70, 118, 94, 0.8);">All users</a>
		</nav>
	</div>

	<div class="tab-content current tabbed-table">
		<?php include 'pages/all-clans/all.php'; ?>
		<?php include 'pages/all-clans/in-range.php'; ?>
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