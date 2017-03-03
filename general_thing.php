<?php
	
	require_once("wp-load.php");

$args = array(
	'meta_key'     => 'turns',
	'number'	=> 100,
	'orderby'      => 'meta_value_num',
	'order'        => 'DESC',);
	
	$users = get_users($args); ?>
	
	<strong>Medal of Earth</strong>
	<table>
	<?php foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$land = get_user_meta($user_ID, 'turns', true);?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
			</td>
			<td><?php echo number_format($land, 0, ',', ' '); ?>
			</td>
		</tr>
		
		
	<?php } ?>
	</table><br/>
