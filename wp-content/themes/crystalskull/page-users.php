<?php
 /*
 * Template Name: Users
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'all';

$user_ID = get_current_user_id();


$allUsers = get_users();

$networth_you = get_user_meta($user_ID, 'networth',true);
include 'constants.php';


$timestamp = strtotime(date('Y-m-d H:i:s'));
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<div class="container">
				
				
				
       <script type="text/javascript">
jQuery(document).ready(function() {
  jQuery(".searchclans").select2();
});
</script>


	     
	     
<form>
	<select id="clan" name="clan" class="searchclans" onchange="if (this.value) window.location.href=this.value">


	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);

		?>

  
		<option name="clan" value="/users/profile/?id=<?php echo $user_ID;?>">
			<a class="<?php echo get_user_meta($user_ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
			<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a></option>
		<?php }?>
	</select>
</form>
<br/>				


<ul id="users-tab" class="nav nav-tabs nav-justified" role="tablist">
	<li class="nav-item <?php echo $activeTab === 'all' ? 'active' : ''; ?>">
		<a class="nav-link" data-toggle="tab" data-target="#all" href="?tab=all" role="tab">All users</a>
	</li>
			
	<li class="nav-item <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>">
		<a class="nav-link" data-toggle="tab" data-target="#in-range" href="?tab=in-range" role="tab">In range</a>
	</li>
			
	<li class="nav-item <?php echo $activeTab === 'online' ? 'active' : ''; ?>">
		<a class="nav-link" data-toggle="tab" data-target="#online" href="?tab=online" role="tab">Online</a>
	</li>
</ul>


<div class="tab-content current build_content tabbed-table">
	<div class="tab-pane <?php echo $activeTab === 'all' ? 'active' : ''; ?>"  id="all" role="tabpanel">
				
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
</div>
</div>
</div>
</div>


<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
    });
</script>

<?php get_footer(); ?>