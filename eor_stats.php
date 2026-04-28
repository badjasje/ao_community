<?php 
	require_once("wp-load.php");
	
$stats = array(
	'succesful_attacks' 		=> array(
			'title'			=>	'Successful attacks made',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'nw_damage_attacks' 		=> array(
			'title'			=>	'Networth damage dealt',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'units_killed' 		=> array(
			'title'			=>	'Units killed',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'buildings_killed' 		=> array(
			'title'			=>	'Buildings destroyed',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'money_gained_combat' 		=> array(
			'title'			=>	'Money gained in combat',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'land_gained_combat' 		=> array(
			'title'			=>	'Land gained in combat',
			'afterdata'		=>	'm2',
			'beforedata'	=>	'',),
	'kills_made' 		=> array(
			'title'			=>	'Players killed',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'missiles_launched' 		=> array(
			'title'			=>	'Missiles launched',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'nw_damage_missiles' 		=> array(
			'title'			=>	'Networth damage missiles',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'thieving_attempts' 		=> array(
			'title'			=>	'Thieving attempts',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'money_gained_thieving' 		=> array(
			'title'			=>	'Money gained thieving',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'attacks_received' 		=> array(
			'title'			=>	'Attacks received',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'nw_damage_lost' 		=> array(
			'title'			=>	'Networth damage received',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'units_lost' 		=> array(
			'title'			=>	'Units lost',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'buildings_lost' 		=> array(
			'title'			=>	'Buildings lost',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'money_lost_combat' 		=> array(
			'title'			=>	'Money lost in combat',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'land_lost_combat' 		=> array(
			'title'			=>	'Land lost in combat',
			'afterdata'		=>	'm2',
			'beforedata'	=>	'',),	
	'times_killed' 		=> array(
			'title'			=>	'Number of times killed',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),	
	'missiles_received' 		=> array(
			'title'			=>	'Missiles received',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'nw_damage_missiles_rec' 		=> array(
			'title'			=>	'Missiles networth damage received',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'attempts_received' 		=> array(
			'title'			=>	'Thieving attempts received',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'money_lost_thieving' 		=> array(
			'title'			=>	'Money lost thieving',
			'afterdata'		=>	'',
			'beforedata'	=>	'$',),
	'buildings_built' 		=> array(
			'title'			=>	'Total number of buildings built',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'units_built_turns' 		=> array(
			'title'			=>	'Total number of units built using turns',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'units_ordered' 		=> array(
			'title'			=>	'Total number of units ordered on market',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),
	'units_sold' 		=> array(
			'title'			=>	'Total number of units sold',
			'afterdata'		=>	'',
			'beforedata'	=>	'',),



);

foreach ($stats as $key => $stat):
	
	$args = array(
		'meta_key'     	=> $key,
		'number'		=> 10,
		'orderby'      	=> 'meta_value_num',
		'order'        	=> 'DESC',);
		
		$users = get_users($args); ?>
		
		<strong><?php echo $stat['title'];?></strong>
		<table>
		<?php 
			
			$count = 0;
			foreach ($users as $user) {
			
			$user_ID = $user->ID;
			$member_data = get_userdata($user_ID);
			$dataCount = get_user_meta($user_ID, $key, true);
			$count++;
	
			?>
			<tr>
				<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)
				</td>
				<td><?php echo $stat['beforedata'];?> <?php echo number_format($dataCount, 0, ',', ' '); ?> <?php echo $stat['afterdata'];?>
				</td>
			</tr>
			
			
		<?php } ?>
		</table><br/>
	<?php endforeach;