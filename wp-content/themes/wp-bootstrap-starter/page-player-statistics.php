<?php
 /*
 * Template Name: Player statistics
 */
get_header(); 
global $userId;
global $userData;
$turnSpread = maybe_unserialize(maybe_unserialize($userData['turn_spread'][0]));
?>
<div class="row pageRow no-gutters">	
<div class="col-md-6">
	<div class="blockHeader">Attacking statistics</div>
	<div class="blockHeader spaceNotice">General attacking statistics</div>
	
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks made</span>
			<span class="dataVisibleRight"><?php echo $userData['attacks_made'][0];?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attacks</span>
			<span class="dataVisibleRight"><?php echo $userData['succesful_attacks'][0];?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['nw_damage_attacks'][0], 0, ',', ' ');?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units killed</span>
			<span class="dataVisibleRight"><?php echo $userData['units_killed'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings destroyed</span>
			<span class="dataVisibleRight"><?php echo $userData['buildings_killed'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money gained in combat</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['money_gained_combat'][0], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land gained in combat</span>
			<span class="dataVisibleRight"><?php echo number_format($userData['land_gained_combat'][0], 0, ',', ' ');?> m<sup>2</sup></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Players killed</span>
			<span class="dataVisibleRight"><?php echo $userData['kills_made'][0];?></span>
		</div>
	
	<div class="blockHeader spaceNotice">Missile statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles launched</span>
			<span class="dataVisibleRight"><?php echo $userData['missiles_launched'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?php echo $userData['missiles_hit'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['nw_damage_missiles'][0], 0, ',', ' ');?></span>
		</div>
	
	<div class="blockHeader spaceNotice">Thieving statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving attempts</span>
			<span class="dataVisibleRight"><?php echo $userData['thieving_attempts'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attempts</span>
			<span class="dataVisibleRight"><?php echo $userData['succesful_attempts'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money stolen</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['money_gained_thieving'][0], 0, ',', ' ');?></span>
		</div>
	</div>
</div>


<div class="col-md-6">
	<div class="blockHeader">Defending statistics</div>
	<div class="blockHeader spaceNotice">General defending statistics</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks received</span>
			<span class="dataVisibleRight"><?php echo $userData['attacks_received'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Battles lost</span>
			<span class="dataVisibleRight"><?php echo $userData['attacks_lost'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['nw_damage_lost'][0], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units lost</span>
			<span class="dataVisibleRight"><?php echo $userData['units_lost'][0];?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings lost</span>
			<span class="dataVisibleRight"><?php echo $userData['buildings_lost'][0];?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money lost in combat</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['money_lost_combat'][0], 0, ',', ' ');?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land lost in combat</span>
			<span class="dataVisibleRight"><?php echo number_format($userData['land_lost_combat'][0], 0, ',', ' ');?> m<sup>2</sup></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Number of times killed</span>
			<span class="dataVisibleRight"><?php echo $userData['times_killed'][0];?></span>
		</div>

	<div class="blockHeader spaceNotice">Missile statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles received</span>
			<span class="dataVisibleRight"><?php echo $userData['missiles_received'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?php echo $userData['missiles_hit_rec'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['nw_damage_missiles_rec'][0], 0, ',', ' ');?></span>
		</div>

	<div class="blockHeader spaceNotice">Thieving statistics</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving attempts</span>
			<span class="dataVisibleRight"><?php echo $userData['attempts_received'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attempts</span>
			<span class="dataVisibleRight"><?php echo $userData['succesful_attempts_rec'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money lost</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['money_lost_thieving'][0], 0, ',', ' ');?></span>
		</div>
	</div>
</div>
<div class="pageSpacer"></div>

<div class="col-md-6">
	<div class="blockHeader">General statistics</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Total buildings built</span>
			<span class="dataVisibleRight"><?php echo $userData['buildings_built'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units built using turns</span>
			<span class="dataVisibleRight"><?php echo $userData['units_built_turns'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units ordered</span>
			<span class="dataVisibleRight"><?php echo $userData['units_ordered'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units sold</span>
			<span class="dataVisibleRight"><?php echo $userData['units_sold'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Morale lost</span>
			<span class="dataVisibleRight"><?php echo $userData['morale_lost'][0];?>%</span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Turns lost</span>
			<span class="dataVisibleRight"><?php echo $userData['turns_lost'][0];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Highest land</span>
			<span class="dataVisibleRight"><?php echo number_format($userData['highest_land'][0], 0, ',', ' ');?> m<sup>2</sup></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Highest networth</span>
			<span class="dataVisibleRight">$ <?php echo number_format($userData['highest_networth'][0], 0, ',', ' ');?></span>
		</div>
	</div>
</div>

<div class="col-md-6">
	<div class="blockHeader">Turn usage breakdown</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Regular, Ground and Air & Sea attacks</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['unit_attack'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['thieving'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Spying</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['spying'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Sending snipers</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['sniper'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Laser beam satellite</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['laser_sattelite'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">EMP satellite</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['emp_satellite'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Activating stealth satellite</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['activate_sat'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Sending saboteurs</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['saboteur'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Launching missiles</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['regular_missile'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Launching EMP missiles</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['emp_missile'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Unit turn build</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['unit_turn_build'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Building satellites</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['build_satellite'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Building missiles</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['missiles'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['buildings'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Exploring</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['exploring'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Research</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['research'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Queue research</span>
			<span class="dataVisibleRight"><?php echo $turnSpread['research_queue'];?></span>
		</div>

	</div>
</div>
</div> <!-- end .pageRow -->
<?php
get_footer();