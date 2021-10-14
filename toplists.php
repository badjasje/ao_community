<?php
require_once("wp-load.php");

$winnerArray = array();

$args = array('meta_key' => 'land',	'number' => 10,	'orderby' => 'meta_value_num', 'order' => 'DESC');
$users = get_users($args);
?>
<strong>Medal of Earth</strong>
<table>
	<?php
	$count = 0;
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$land = get_user_meta($user_ID, 'land', true);
		$count++;
		if($count == 1) {
			$winnerArray['Medal of Earth'] = array($user_ID,'Land: '.number_format($land, 0, ',', ' ').'m2');
			
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Earth! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Earth. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Earth. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        
			}
				
		}
		
		
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td><?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'user_clan_points', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Honor</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$points = get_user_meta($user_ID, 'user_clan_points', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Honor'] = array($user_ID,'Points: '.number_format($points, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Honor! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Honor. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Honor. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td><?php echo number_format($points, 0, ',', ' '); ?>pts</td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'networth', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Growth</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$networth = get_user_meta($user_ID, 'networth', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Growth'] = array($user_ID,'Networth: $ '.number_format($networth, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Growth! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Growth. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Growth. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td>$ <?php echo number_format($networth, 0, ',', ' '); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'in_war_attacks', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Courage</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$attacks = get_user_meta($user_ID, 'succesful_attacks', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Courage'] = array($user_ID,'Attacks: '.number_format($attacks, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Courage! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Courage. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Courage. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td><?php echo number_format($attacks, 0, ',', ' '); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'kills_made', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Death</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$kills = get_user_meta($user_ID, 'kills_made', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Death'] = array($user_ID,'Kills: '.number_format($kills, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Death! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Death. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Death. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td><?php echo number_format($kills, 0, ',', ' '); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'money_gained_thieving', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Thievery</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$stolen = get_user_meta($user_ID, 'money_gained_thieving', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Thievery'] = array($user_ID,'Stolen: $ '.number_format($stolen, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Thievery! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Thievery. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Thievery. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td>$ <?php echo number_format($stolen, 0, ',', ' '); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array('meta_key' => 'nw_damage_missiles_war', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Destruction</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$damage = get_user_meta($user_ID, 'nw_damage_missiles_war', true);
		$count++;
		if($count == 1){
			$winnerArray['Medal of Destruction'] = array($user_ID,'Damage: $ '.number_format($damage, 0, ',', ' '));
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Destruction! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Destruction. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Destruction. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td>$ <?php echo number_format($damage, 0, ',', ' '); ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

$args = array(
	'meta_key' => 'nw_damage_defender', 'posts_per_page' => 200, 'post_type' => 'event_local', 'orderby' => 'meta_value_num',
	'order' => 'DESC', 'meta_query'	=> array(
		'relation' => 'OR',
		array('key' => 'war_status', 'compare' => '=', 'value' => 'incoming'),
		array('key' => 'war_status', 'compare' => '=', 'value' => 'mutual'),
		array('key' => 'war_status', 'compare' => '=', 'value' => 'outgoing')
	),
);
$count = 0;
$attacks = get_posts($args);
?>
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
			$damage = get_post_meta($attack->ID, 'nw_damage_defender', true);
			if($count == 1){
				$winnerArray['Medal of Devastation'] = array($user_ID,'Damage: $ '.number_format($damage, 0, ',', ' '));
			}
			
			if($_GET['add'] == 1){
			
				if($count == 1) {
					$xp_points = get_user_meta( $user_ID, 'player_xp', true );
					update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
					
					
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Devastation! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
					
				}
				if($count == 2) {
					$xp_points = get_user_meta( $user_ID, 'player_xp', true );
					update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
					
					
					Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Devastation. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
					
					
				}
				if($count == 3) {
					$xp_points = get_user_meta( $user_ID, 'player_xp', true );
					update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
					
					
					Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Devastation. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
					
					
				}
				
			}
			
			?>
			<tr>
				<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
				<td>$ <?php echo number_format($damage, 0, ',', ' '); ?></td>
			</tr>
			<?php
			$users[] = $user_ID;
			if($count == 10){break;}
		}
	}
	?>
</table>
<br/>
<?php


$args = array('meta_key' => 'referral_num', 'number' => 10, 'orderby' => 'meta_value_num', 'order' => 'DESC');
$count = 0;
$users = get_users($args);
?>
<strong>Medal of Recruitment</strong>
<table>
	<?php
	foreach ($users as $user) {
		$user_ID = $user->ID;
		$member_data = get_userdata($user_ID);
		$referrals = get_user_meta($user_ID, 'referral_num', true);
		$count++;
		if(intval($referrals) < 1) continue;
		if($count == 1){
			$winnerArray['Medal of Recruitment'] = array($user_ID, $referrals);
		}
		
		if($_GET['add'] == 1){
			
			if($count == 1) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+10000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You won the Medal of Recruitment! You gained 10 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 2) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+3000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in second place for the Medal of Recruitment. You gained 3 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
			}
			if($count == 3) {
				$xp_points = get_user_meta( $user_ID, 'player_xp', true );
				update_user_meta( $user_ID, 'player_xp', $xp_points+1000);
				
				
				Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' =>"You came in third place for the Medal of Recruitment. You gained 1 000 XP.", 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
				
				
				
			}
				
		}
		
		?>
		<tr>
			<td><?php echo $member_data->display_name;?> (#<?php echo $user_ID;?>)</td>
			<td><?php echo $referrals; ?></td>
		</tr>
		<?php
	}
	?>
</table>
<br/>
<?php

if($_GET['add'] == 1){
	if(empty($_GET['round']) || !isset($_GET['round'])){
		die;
	}
	foreach ($winnerArray as $key => $winner) {
		$args = [
			'post_title' => $key,
			'post_status' => 'publish',
			'post_type' => 'medal',
			'post_author' => 1
		];
		$newMedalId = wp_insert_post($args);
		update_field('medal_round', 'Beta round '.$_GET['round'], $newMedalId);
		update_field('winning_user', $winner[0], $newMedalId);
	}
}