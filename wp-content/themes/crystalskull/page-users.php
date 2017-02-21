<?php
 /*
 * Template Name: Users
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'all';

$user_ID = get_current_user_id();

$users = get_users();
$networth_you = get_user_meta($user_ID, 'networth');
include 'constants.php';
$results = [];
if( isset( $_GET['usersearch'] ) ){
    //$wpdb needs to be made global, this lets us use it on a page template
    global $wpdb;
    //some cleanup to the search term, as well as caching it to $usersearch
    $usersearch = stripslashes( trim($_GET['usersearch']) );
    //$wpdb->prepare() is a fast and safe method for performing a MySQL query
    $stmt = $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta AS um
        WHERE ( um.meta_key='nickname' AND um.meta_value LIKE '%%%s%%') OR
        (um.meta_key='user_nicename' AND um.meta_value LIKE '%%%s%%')
        ORDER BY um.meta_value 
        LIMIT 150",
        $usersearch, $usersearch );
    //results are cached in the variable $results using get_col()
    $results = $wpdb->get_col( $stmt );
} 

$timestamp = strtotime(date('Y-m-d H:i:s'));
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<div class="container">
				
				<?php $st = (isset($_GET['usersearch']) ? $_GET['usersearch'] : '' ); ?>
<form action="" method="get">
    <input name="usersearch" id="usersearch" value="<?php echo $st; ?>" type="text" placeholder="Search by username">
    <input name="dosearch" type="submit" value="Submit">
</form>
<br/>
<table class="responsive-table">
 
       
       
        <?php foreach($results as $u){
	        $member_data = get_userdata($u);?>
        <tr>
	        <th>
					<?php if(!empty(get_user_meta($u, 'avatar_user', true))):?>
                    
			<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($u, 'avatar_user', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
                    
			<?php endif;?>
				</th>
	        <th data-title="User"><a class="<?php echo get_user_meta($u,'status',true);?>" href="/users/profile/?id=<?php echo $u;?>"><?php echo $member_data->display_name.' (#'.$u.')';?></a>

			</th>
			<th sorttable_customkey="<?php echo get_user_meta($u, 'land', true);?>"><?php echo number_format(get_user_meta($u, 'land', true), 0, ',', ' '); ?> m<sup>2</sup>
			</th>
			<th sorttable_customkey="<?php echo get_user_meta($u, 'networth', true);?>">$ <?php echo number_format(get_user_meta($u, 'networth', true), 0, ',', ' '); ?>
			</th>
        </tr>
        <?php } ?>

</table>

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


				<table class="responsive-table sortable">
					<thead>
						<tr>
							<td></td>
							<td>Name</td>
							<td>Networth</td>
							<td>Clan</td>
							<td>Land</td>
	                    </tr>
					</thead>
				<tbody>
				<?php


					foreach ($users as $user) {
					$user_NW = get_user_meta($user->ID, 'networth');
					$user_land = get_user_meta($user->ID, 'land');
					$networth = get_user_meta($user->ID, 'networth');
					$user_status = get_user_meta($user->ID, 'status');
					$clan_id = get_user_meta($user->ID, 'clan_id_user');
					$last_online = get_user_meta($user->ID, 'last_online');
					if(!empty($last_online)){
					$last_seen = $timestamp - $last_online[0];}
					?>
					<tr>
						<td>
						<?php if(!empty(get_user_meta($user->ID, 'avatar_user', true))):?>

				<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true);?>");background-size: cover;'></div>
				<?php else:?>
				<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>

				<?php endif;?>
					</td>
					<td>
						<a class="<?php echo get_user_meta($user->ID,'status',true);?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID; ?>)</a><?php
							if(!empty($last_online)){
							if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?>

					</td>
					<td sorttable_customkey="<?php echo $user_NW[0];?>">$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
					<td><?php if($clan_id[0] == 0){
								echo 'none';}else{
								echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
								}?></td>
					<td sorttable_customkey="<?php echo $user_land[0];?>"><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>

					</tr>
					<?php }?>
				</tbody>
				</table>


			</div>




			<div class="tab-pane <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>"  id="in-range" role="tabpanel">
					<center>You can target provinces with a networth between <?php echo '$ '.number_format($networth_you[0]/$ATTACK_RANGE_MULT, 0, ',', ' ').' and $ '.number_format($networth_you[0]*$ATTACK_RANGE_MULT, 0, ',', ' ');?></center><br/>


				<table class="responsive-table">
					<thead>
						<tr>
							<td></td>
							<td>Name</td>
							<td>Networth</td>
							<td>Clan</td>
							<td>Land</td>
	                    </tr>
					</thead>
					<tbody>

				<?php


					foreach ($users as $user) {
					$user_land = get_user_meta($user->ID, 'land');

					$user_NW = get_user_meta($user->ID, 'networth');
					if (($user_NW[0] > $networth_you[0]/$ATTACK_RANGE_MULT && $user_NW[0] < $networth_you[0]*$ATTACK_RANGE_MULT)){
						$clan_id = get_user_meta($user->ID, 'clan_id_user');
					?>
					<tr>
						<td>
						<?php if(!empty(get_user_meta($user->ID, 'avatar_user', true))):?>

				<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true);?>");background-size: cover;'></div>
				<?php else:?>
				<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>

				<?php endif;?>
					</td>
					<td><a class="<?php echo $user_status[0];?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a></td>
					<td sorttable_customkey="<?php echo $user_NW[0];?>">$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
					<td><?php if($clan_id[0] == 0){
								echo 'none';}else{
								echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
								}?></td>
					<td sorttable_customkey="<?php echo $user_land[0];?>"><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>

					</tr>
					<?php }}?>
					</tbody>
				</table>
			</div>



			<div class="tab-pane <?php echo $activeTab === 'online' ? 'active' : ''; ?>"  id="online" role="tabpanel">


				<table class="responsive-table">
						<tr>
							<thead>
							<td></td>
							<td>Name</td>
							<td>Networth</td>
							<td>Clan</td>
							<td>Land</td>
							</thead>
	                    </tr>
	                    <tbody>

				<?php


					foreach ($users as $user) {
					$user_NW = get_user_meta($user->ID, 'networth');
					$user_land = get_user_meta($user->ID, 'land');
					$networth = get_user_meta($user->ID, 'networth');
					$user_status = get_user_meta($user->ID, 'status');
					$clan_id = get_user_meta($user->ID, 'clan_id_user');
					$last_online = get_user_meta($user->ID, 'last_online');

					if(!empty($last_online[0])){
					$last_seen = $timestamp - $last_online[0];

					if($last_seen < 7200 && !empty($last_online[0])) { ?>
					<tr>
						<td>
						<?php if(!empty(get_user_meta($user->ID, 'avatar_user', true))):?>

				<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true);?>");background-size: cover;'></div>
				<?php else:?>
				<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>

				<?php endif;?>
					</td>
					<td>
						<a class="<?php echo $user_status[0];?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a><span style="color:#ff0000"> *</span>

					</td>
					<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
					<td><?php if($clan_id[0] == 0){
								echo 'none';}else{
								echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
								} ?></td>
					<td><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>

					</tr>
					<?php }}} ?>
	                    </tbody>
				</table>


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