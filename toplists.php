<?php 
	require_once("wp-load.php");
	if(!current_user_can('administrator')) {
		wp_redirect(get_permalink(3582)); exit;
	}
	
	$args = array(
	'meta_key'     => 'land',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Earth</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$land = get_user_meta($user_ID, 'land', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td><?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
	<?php
		
		$args = array(
	'meta_key'     => 'user_clan_points',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Honor</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$points = get_user_meta($user_ID, 'user_clan_points', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td><?php echo number_format($points, 0, ',', ' '); ?>pts
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
	
	<?php
		
		$args = array(
	'meta_key'     => 'networth',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Growth</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$networth = get_user_meta($user_ID, 'networth', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td>$ <?php echo number_format($networth, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
	<?php
		
		$args = array(
	'meta_key'     => 'attacks_made',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Courage</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$attacks = get_user_meta($user_ID, 'attacks_made', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td><?php echo number_format($attacks, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
	<?php
		
		$args = array(
	'meta_key'     => 'kills_made',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Death</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$kills = get_user_meta($user_ID, 'kills_made', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td><?php echo number_format($kills, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>

<?php
		
		$args = array(
	'meta_key'     => 'money_gained_thieving',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Thievery</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$stolen = get_user_meta($user_ID, 'money_gained_thieving', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td>$ <?php echo number_format($stolen, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
	
<?php
		
		$args = array(
	'meta_key'     => 'nw_damage_missiles',
	'number'	=> 10,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Destruction</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$damage = get_user_meta($user_ID, 'nw_damage_missiles', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td>$ <?php echo number_format($damage, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>

<?php
		
		$args = array(
	'meta_key'     => 'nw_damage_defender',
	'posts_per_page'	=> 200,
	'post_type'		=> 'event_local',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',
	'meta_query'	=> array(
		'relation'		=> 'OR',
		array(
			'key'		=> 'war_status',
			'compare'	=> '=',
			'value'		=> 'incoming',
		),
		array(
			'key'		=> 'war_status',
			'compare'	=> '=',
			'value'		=> 'mutual',
		),
		array(
			'key'		=> 'war_status',
			'compare'	=> '=',
			'value'		=> 'outgoing',
		)),
	
	);
	
	$attacks = get_posts($args); ?>
	
	
	<strong>Medal of Devastation</strong>
	<table>
	<?php 
		$users = array();
		$count = 0;
		foreach ($attacks as $attack) {
		
		$user_ID = $attack->post_author;
		if(!in_array($user_ID, $users)){
		$count++;
		$member_data = get_userdata($user_ID);
		$damage = get_post_meta($attack->ID, 'nw_damage_defender', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td>$ <?php echo number_format($damage, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php 
		
		$users[] = $user_ID;
		if($count == 10){die;}
		}} ?>
	</table><br/>