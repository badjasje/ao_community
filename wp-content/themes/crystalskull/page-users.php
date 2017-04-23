<?php
 /*
 * Template Name: Users
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'all';

$user_ID = get_current_user_id();

$users = get_users();
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
				



				
				
				
				
<!-- All Users block -->
<div class="row toplist_block">	
<div class="row clan_header_row">
	<div class="col-md-1"></div>
	<div class="col-md-4"><strong>Name</strong></div>
	<div class="col-md-2"><strong>Networth</strong></div>
	<div class="col-md-2"><strong>Land</strong></div>
	<div class="col-md-3"><strong>Clan</strong></div>
</div>

<?php 
	

	

	
	$NRmembers = count($users);
	$counter = 0;
	foreach ($users as $user) {
		
		$user_ID = $user->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
		$networth = get_user_meta($user_ID, 'networth',true);
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
			?>
<div class="row clan_profile_row<?php echo $extraClass;?>">
	<div class="col-md-1">
		
		<?php echo small_avatar($user_ID,'');?>
		
	</div>
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		
		<a class="<?php echo get_user_meta($user->ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
			<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?>
					

			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right">
		
			$ <?php echo number_format($networth, 0, ',', ' '); ?>
					
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-3 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
</div>

<?php  }?>
</div>
			</div> <!-- // End tab pane 1 -->




			<div class="tab-pane <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>"  id="in-range" role="tabpanel">
				





<!-- All Users In range block -->
<div class="row toplist_block">	
		<center>You can target provinces with a networth between <?php echo '$ '.number_format($networth_you/$ATTACK_RANGE_MULT, 0, ',', ' ').' and $ '.number_format($networth_you*$ATTACK_RANGE_MULT, 0, ',', ' ');?></center><br/>
<div class="row clan_header_row">
	<div class="col-md-1"></div>
	<div class="col-md-4"><strong>Name</strong></div>
	<div class="col-md-2"><strong>Networth</strong></div>
	<div class="col-md-2"><strong>Land</strong></div>
	<div class="col-md-3"><strong>Clan</strong></div>
</div>




<?php 

	
	$NRmembers = count($users);
	$counter = 0;
	foreach ($users as $user) {
		
		$user_ID = $user->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
		$networth = get_user_meta($user_ID, 'networth',true);
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
		if (($networth > $networth_you/$ATTACK_RANGE_MULT && $networth < $networth_you*$ATTACK_RANGE_MULT)){
		
			?>
<div class="row clan_profile_row<?php echo $extraClass;?>">
	<div class="col-md-1">
		
		<?php echo small_avatar($user_ID,'');?>
		
	</div>
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		
		<a class="<?php echo get_user_meta($user->ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
			<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?>
					

			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right">
		
			$ <?php echo number_format($networth, 0, ',', ' '); ?>
					
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-3 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
</div>

<?php  }}?>
</div>
				
			</div> <!-- // End pane 2 -->



			<div class="tab-pane <?php echo $activeTab === 'online' ? 'active' : ''; ?>"  id="online" role="tabpanel">



<!-- All Users In range block -->
<div class="row toplist_block">	
<div class="row clan_header_row">
	<div class="col-md-1"></div>
	<div class="col-md-4"><strong>Name</strong></div>
	<div class="col-md-2"><strong>Networth</strong></div>
	<div class="col-md-2"><strong>Land</strong></div>
	<div class="col-md-3"><strong>Clan</strong></div>

</div>




<?php 

	
	$NRmembers = count($users);
	$counter = 0;
	foreach ($users as $user) {
		
		$user_ID = $user->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
		$networth = get_user_meta($user_ID, 'networth',true);
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}


		if($last_seen < 7200 && !empty($last_online)) {
		
			?>
<div class="row clan_profile_row<?php echo $extraClass;?>">
	<div class="col-md-1">
		
		<?php echo small_avatar($user_ID,'');?>
		
	</div>
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		
		<a class="<?php echo get_user_meta($user->ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
			<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?>
					

			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right">
		
			$ <?php echo number_format($networth, 0, ',', ' '); ?>
					
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	<div class="col-md-3 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
	

</div>

<?php  }}?>
</div>


			</div>
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