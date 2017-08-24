<?php 
	require_once("wp-load.php");
	
	$args = array(
	'meta_key'     => 'land',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$land = get_user_meta($user_ID, 'land', true);
		$next_land = get_user_meta($next_ID, 'land', true); 
		$prev_land = get_user_meta($prev_ID, 'land', true); 
		
		update_user_meta($user_ID, 'moe_position', $position);
		update_user_meta($user_ID, 'moe_prev', round($land-$prev_land));
		
		if($position == 1){
			update_user_meta($user_ID, 'moe_next', 0);
		}else{
			update_user_meta($user_ID, 'moe_next', round($next_land-$land));
		}
		
		}
		
		
$args = array(
	'meta_key'     => 'user_clan_points',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$points = get_user_meta($user_ID, 'user_clan_points', true);
		$next_points = get_user_meta($next_ID, 'user_clan_points', true); 
		$prev_points = get_user_meta($prev_ID, 'user_clan_points', true); 
		
		update_user_meta($user_ID, 'moh_position', $position);
		update_user_meta($user_ID, 'moh_prev', $points-$prev_points);
		
		if($position == 1){
			update_user_meta($user_ID, 'moh_next', 0);
		}else{
			update_user_meta($user_ID, 'moh_next', $next_points-$points);
		}
		
		}


$args = array(
	'meta_key'     => 'networth',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$networth = get_user_meta($user_ID, 'networth', true);
		$next_networth = get_user_meta($next_ID, 'networth', true); 
		$prev_networth = get_user_meta($prev_ID, 'networth', true); 
		
		update_user_meta($user_ID, 'mog_position', $position);
		update_user_meta($user_ID, 'mog_prev', $networth-$prev_networth);
		
		if($position == 1){
			update_user_meta($user_ID, 'mog_next', 0);
		}else{
			update_user_meta($user_ID, 'mog_next', $next_networth-$networth);
		}
		
		}
		
$args = array(
	'meta_key'     => 'attacks_made',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$attacks = get_user_meta($user_ID, 'attacks_made', true);
		$next_attacks = get_user_meta($next_ID, 'attacks_made', true); 
		$prev_attacks = get_user_meta($prev_ID, 'attacks_made', true); 
		
		update_user_meta($user_ID, 'moc_position', $position);
		update_user_meta($user_ID, 'moc_prev', $attacks-$prev_attacks);
		
		if($position == 1){
			update_user_meta($user_ID, 'moc_next', 0);
		}else{
			update_user_meta($user_ID, 'moc_next', $next_attacks-$attacks);
		}
		
		}

$args = array(
	'meta_key'     => 'kills_made',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$kills = get_user_meta($user_ID, 'kills_made', true);
		$next_kills = get_user_meta($next_ID, 'kills_made', true); 
		$prev_kills = get_user_meta($prev_ID, 'kills_made', true); 
		
		update_user_meta($user_ID, 'mod_position', $position);
		update_user_meta($user_ID, 'mod_prev', $kills-$prev_kills);
		
		if($position == 1){
			update_user_meta($user_ID, 'mod_next', 0);
		}else{
			update_user_meta($user_ID, 'mod_next', $next_kills-$kills);
		}
		
		}
		
$args = array(
	'meta_key'     => 'money_gained_thieving',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$thiefed = get_user_meta($user_ID, 'money_gained_thieving', true);
		$next_thiefed = get_user_meta($next_ID, 'money_gained_thieving', true); 
		$prev_thiefed = get_user_meta($prev_ID, 'money_gained_thieving', true); 
		
		update_user_meta($user_ID, 'mot_position', $position);
		update_user_meta($user_ID, 'mot_prev', $thiefed-$prev_thiefed);
		
		if($position == 1){
			update_user_meta($user_ID, 'mot_next', 0);
		}else{
			update_user_meta($user_ID, 'mot_next', $next_thiefed-$thiefed);
		}
		
		}
		
$args = array(
	'meta_key'     => 'nw_damage_missiles',
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); 

	$position = 0;
	foreach ($users as $key => $user) {
		$position += 1;
		$user_ID = $user->ID;
		
		$next_ID = $users[$key-1]->ID;
		$prev_ID = $users[$key+1]->ID;
		
		$damage = get_user_meta($user_ID, 'nw_damage_missiles', true);
		$next_damage = get_user_meta($next_ID, 'nw_damage_missiles', true); 
		$prev_damge = get_user_meta($prev_ID, 'nw_damage_missiles', true); 
		
		update_user_meta($user_ID, 'modes_position', $position);
		update_user_meta($user_ID, 'modes_prev', $damage-$prev_damge);
		
		if($position == 1){
			update_user_meta($user_ID, 'modes_next', 0);
		}else{
			update_user_meta($user_ID, 'modes_next', $next_damage-$damage);
		}
		
		}
		
		/*
		
			
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
	