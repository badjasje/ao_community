<?php
/*
 * Template Name: Users
 */

get_header();
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'all';
global $userData;
global $userId;
$backColor = "45, 67, 81";
$timestamp = current_time('timestamp');

$transient = get_transient('allusers_query');

if(!empty($transient)) {
	$allUsers = $transient;
} else {
	$args = array(
		'meta_key'     	=> 'last_online',
		'orderby'      	=> 'meta_value_num',
		'meta_value'	=> $timestamp-1728000,
		'meta_compare'	=> '>',
	);

    $args = array(
        'meta_query'=> array(
            array(
                'relation' => 'AND',
	            array('key' => 'last_online', 'value' => $timestamp-1728000, 'compare' => ">", 'type' => 'numeric'),
	            array('key' => 'networth', 'value' =>  10, 'compare' => ">", 'type' => 'numeric'),
	        )
    	)
    );
    $users = get_users( $args );

	$allUsers = get_users($args);
	set_transient( 'allusers_query', $allUsers, 12 * 60 * 60 );
}

$networth_you = $userData['networth'][0];
include 'constants.php';
include 'attack_functions.php';
?>
<div class="row pageRow">
	<form class="fw-row">
		<select id="clan" name="clan" class="searchusers" onchange="if (this.value) window.location.href=this.value">
		<option></option>
		<?php foreach ($allUsers as $user) {
			$user_ID = $user->ID;
			$member_data = get_userdata($user_ID);
			?>
			<option name="clan" value="/users/profile/?id=<?php echo $user_ID;?>">
				<a class="<?php echo get_user_meta($user_ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
				<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a></option>
			<?php }?>
		</select>
	</form>

	<div class="pageSpacer"></div>

	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'all' ? 'active' : ''; ?>" data-toggle="tab" data-target="#all" href="?tab=all">All</a>
			<a class="nav-item nav-link navItem sort2 <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>" data-toggle="tab" data-target="#in-range" href="?tab=in-range">In range</a>
			<a class="nav-item nav-link navItem sort3 <?php echo $activeTab === 'online' ? 'active' : ''; ?>" data-toggle="tab" data-target="#online" href="?tab=online">Online</a>
			<a class="nav-item nav-link navItem" href="/all-clans" style="background-color: rgba(70, 118, 94, 0.8);">All clans</a>
		</nav>
	</div>

	<div class="tab-content tabbed-table">
		<div class="tab-pane <?php echo $activeTab === 'all' ? 'active' : ''; ?>" id="all" role="tabpanel">
			<?php include 'pages/users/all.php'; ?>
		</div> <!-- // End tab pane 1 -->

		<div class="tab-pane <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>"  id="in-range" role="tabpanel">
			<?php include 'pages/users/inrange.php'; ?>
		</div> <!-- // End pane 2 -->

		<div class="tab-pane <?php echo $activeTab === 'online' ? 'active' : ''; ?>"  id="online" role="tabpanel">
			<?php include 'pages/users/online.php'; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".searchusers").select2({
			placeholder: "Start typing to find a player"
		});
	});
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
    });
</script>
<?php
get_footer();