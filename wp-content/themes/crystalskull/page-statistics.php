<?php
 /*
 * Template Name: Game statistics
 */
$user_ID = get_current_user_id();

$nw_user = get_user_meta($user_ID, 'networth', true);
$highest_networth = get_user_meta($user_ID, 'highest_networth', true);
if($highest_networth < $nw_user){
	update_user_meta($user_ID, 'highest_networth', $nw_user);
}

$land = get_user_meta($user_ID, 'land',true);

$highest_land = get_user_meta($user_ID, 'highest_land', true);
if($highest_land < $land){
	update_user_meta($user_ID, 'highest_land', $land);
}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	        <div class="col-md-6">
	         <h2>Attacking statistics</h2>
	         <h4>General attacking statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Attacks made</th>
					<th><?php echo get_user_meta($user_ID, 'attacks_made', true);?></th>
				</tr>
				<tr>
					<th>Successful attacks</th>
					<th><?php echo get_user_meta($user_ID, 'succesful_attacks', true);?></th>
				</tr>
				<tr>
					<th>Networth damage dealt</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'nw_damage_attacks', true), 0, ',', ' ');?></th>
				</tr>
				<tr>
					<th>Units killed</th>
					<th><?php echo get_user_meta($user_ID, 'units_killed', true);?></th>
				</tr>
				<tr>
					<th>Buildings destroyed</th>
					<th><?php echo get_user_meta($user_ID, 'buildings_killed', true);?></th>
				</tr>
				<tr>
					<th>Money gained in combat</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'money_gained_combat', true), 0, ',', ' ');?></th>
				</tr>
				<tr>
					<th>Land gained in combat</th>
					<th><?php echo number_format(get_user_meta($user_ID, 'land_gained_combat', true), 0, ',', ' ');?> m<sup>2</sup></th>
				</tr>
				<tr>
					<th>Players killed</th>
					<th><?php echo get_user_meta($user_ID, 'kills_made', true);?></th>
				</tr>
			</table>
			<h4>Missile statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Missiles launched</th>
					<th><?php echo get_user_meta($user_ID, 'missiles_launched', true);?></th>
				</tr>
				<tr>
					<th>Missiles hit</th>
					<th><?php echo get_user_meta($user_ID, 'missiles_hit', true);?></th>
				</tr>
				<tr>
					<th>Networth damage dealt</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'nw_damage_missiles', true), 0, ',', ' ');?></th>
				</tr>
			</table>
			<h4>Thieving statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Thieving attempts</th>
					<th><?php echo get_user_meta($user_ID, 'thieving_attempts', true);?></th>
				</tr>
				<tr>
					<th>Successful attempts</th>
					<th><?php echo get_user_meta($user_ID, 'succesful_attempts', true);?></th>
				</tr>
				<tr>
					<th>Money stolen</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'money_gained_thieving', true), 0, ',', ' ');?></th>
				</tr>
			</table>
	        </div>
	        <div class="col-md-6">
			<h2>Defending statistics</h2>
	         <h4>General defending statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Attacks received</th>
					<th><?php echo get_user_meta($user_ID, 'attacks_received', true);?></th>
				</tr>
				<tr>
					<th>Battles lost</th>
					<th><?php echo get_user_meta($user_ID, 'attacks_lost', true);?></th>
				</tr>
				<tr>
					<th>Networth damage dealt</th>
					<th><?php echo number_format(get_user_meta($user_ID, 'nw_damage_lost', true), 0, ',', ' ');?></th>
				</tr>
				<tr>
					<th>Units lost</th>
					<th><?php echo get_user_meta($user_ID, 'units_lost', true);?></th>
				</tr>
				<tr>
					<th>Buildings lost</th>
					<th><?php echo get_user_meta($user_ID, 'buildings_lost', true);?></th>
				</tr>
				<tr>
					<th>Money lost in combat</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'money_lost_combat', true), 0, ',', ' ');?></th>
				</tr>
				<tr>
					<th>Land lost in combat</th>
					<th><?php echo number_format(get_user_meta($user_ID, 'land_lost_combat', true), 0, ',', ' ');?> m<sup>2</sup></th>
				</tr>
				<tr>
					<th>Number of times killed</th>
					<th><?php echo get_user_meta($user_ID, 'times_killed', true);?></th>
				</tr>
			</table>
			<h4>Missile statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Missiles received</th>
					<th><?php echo get_user_meta($user_ID, 'missiles_received', true);?></th>
				</tr>
				<tr>
					<th>Missiles hit</th>
					<th><?php echo get_user_meta($user_ID, 'missiles_hit_rec', true);?></th>
				</tr>
				<tr>
					<th>Networth damage dealt</th>
					<th><?php echo number_format(get_user_meta($user_ID, 'nw_damage_missiles_rec', true), 0, ',', ' ');?></th>
				</tr>
			</table>
			<h4>Thieving statistics</h4>
			<table class="responsive-table">
				<tr>
					<th>Thieving attempts</th>
					<th><?php echo get_user_meta($user_ID, 'attempts_received', true);?></th>
				</tr>
				<tr>
					<th>Successful attempts</th>
					<th><?php echo get_user_meta($user_ID, 'succesful_attempts_rec', true);?></th>
				</tr>
				<tr>
					<th>Money lost</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'money_lost_thieving', true), 0, ',', ' ');?></th>
				</tr>
			</table>
	        </div>  
	        <h2>General statistics</h2>
			<table class="responsive-table">
				<tr>
					<th>Total buildings built</th>
					<th><?php echo get_user_meta($user_ID, 'buildings_built', true);?></th>
				</tr>
				<tr>
					<th>Units built using turns</th>
					<th><?php echo get_user_meta($user_ID, 'units_built_turns', true);?></th>
				</tr>
				<tr>
					<th>Units ordered</th>
					<th><?php echo get_user_meta($user_ID, 'units_ordered', true);?></th>
				</tr>
				<tr>
					<th>Units sold</th>
					<th><?php echo get_user_meta($user_ID, 'units_sold', true);?></th>
				</tr>
				<tr>
					<th>Morale lost</th>
					<th><?php echo get_user_meta($user_ID, 'morale_lost', true);?>%</th>
				</tr>
				<tr>
					<th>Turns lost</th>
					<th><?php echo get_user_meta($user_ID, 'turns_lost', true);?></th>
				</tr>
				<tr>
					<th>Highest land</th>
					<th><?php echo number_format(get_user_meta($user_ID, 'highest_land', true), 0, ',', ' ');?> m<sup>2</sup></th>
				</tr>
				<tr>
					<th>Highest networth</th>
					<th>$ <?php echo number_format(get_user_meta($user_ID, 'highest_networth', true), 0, ',', ' ');?></th>
				</tr>
			</table>
	        
	        
	        
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>