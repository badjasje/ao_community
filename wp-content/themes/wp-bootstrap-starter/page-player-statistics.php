<?php
/**
 * Template Name: Player statistics
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();
$turnSpread = $province->getTurnSpread();
?>
<div class="row pageRow no-gutters">
<div class="col-md-6">
	<div class="blockHeader">Attacking statistics</div>
	<div class="blockHeader spaceNotice">General attacking statistics</div>

	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks made</span>
			<span class="dataVisibleRight"><?=$province->get('attacks_made')?>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attacks</span>
			<span class="dataVisibleRight"><?=$province->get('succesful_attacks')?>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight"><?=Format::networth($province->get('nw_damage_attacks'))?>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units killed</span>
			<span class="dataVisibleRight"><?=$province->get('units_killed')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings destroyed</span>
			<span class="dataVisibleRight"><?=$province->get('buildings_killed')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money gained in combat</span>
			<span class="dataVisibleRight"><?=Format::money($province->get('money_gained_combat'))?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land gained in combat</span>
			<span class="dataVisibleRight"><?=Format::land($province->get('land_gained_combat'), 0, ',', ' ')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Players killed</span>
			<span class="dataVisibleRight"><?=$province->get('kills_made')?></span>
		</div>

	<div class="blockHeader spaceNotice">Missile statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles launched</span>
			<span class="dataVisibleRight"><?=$province->get('missiles_launched')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?=$province->get('missiles_hit')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight"><?=Format::networth($province->get('nw_damage_missiles'))?></span>
		</div>

	<div class="blockHeader spaceNotice">Thieving statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving attempts</span>
			<span class="dataVisibleRight"><?=$province->get('thieving_attempts')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attempts</span>
			<span class="dataVisibleRight"><?=$province->get('succesful_attempts')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money stolen</span>
			<span class="dataVisibleRight"><?=Format::money($province->get('money_gained_thieving'))?></span>
		</div>
	</div>
</div>


<div class="col-md-6">
	<div class="blockHeader">Defending statistics</div>
	<div class="blockHeader spaceNotice">General defending statistics</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks received</span>
			<span class="dataVisibleRight"><?=$province->get('attacks_received')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Battles lost</span>
			<span class="dataVisibleRight"><?=$province->get('attacks_lost')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight"><?=Format::networth($province->get('nw_damage_lost'))?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units lost</span>
			<span class="dataVisibleRight"><?=$province->get('units_lost')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings lost</span>
			<span class="dataVisibleRight"><?=$province->get('buildings_lost')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money lost in combat</span>
			<span class="dataVisibleRight"><?=Format::money($province->get('money_lost_combat'))?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land lost in combat</span>
			<span class="dataVisibleRight"><?=Format::land($province->get('land_lost_combat'))?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Number of times killed</span>
			<span class="dataVisibleRight"><?=$province->get('times_killed')?></span>
		</div>

	<div class="blockHeader spaceNotice">Missile statistics</div>
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles received</span>
			<span class="dataVisibleRight"><?=$province->get('missiles_received')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?=$province->get('missiles_hit_rec')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage dealt</span>
			<span class="dataVisibleRight"><?=Format::networth($province->get('nw_damage_missiles_rec'))?></span>
		</div>

	<div class="blockHeader spaceNotice">Thieving statistics</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving attempts</span>
			<span class="dataVisibleRight"><?=$province->get('attempts_received')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attempts</span>
			<span class="dataVisibleRight"><?=$province->get('succesful_attempts_rec')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money lost</span>
			<span class="dataVisibleRight"><?=Format::money($province->get('money_lost_thieving'))?></span>
		</div>
	</div>
</div>
<div class="pageSpacer"></div>

<div class="col-md-6">
	<div class="blockHeader">General statistics</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Total buildings built</span>
			<span class="dataVisibleRight"><?=$province->get('buildings_built')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units built using turns</span>
			<span class="dataVisibleRight"><?=$province->get('units_built_turns')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units ordered</span>
			<span class="dataVisibleRight"><?=$province->get('units_ordered')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units sold</span>
			<span class="dataVisibleRight"><?=$province->get('units_sold')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Morale lost</span>
			<span class="dataVisibleRight"><?=$province->get('morale_lost')?>%</span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Turns lost</span>
			<span class="dataVisibleRight"><?=$province->get('turns_lost')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Highest land</span>
			<span class="dataVisibleRight"><?=Format::land($province->get('highest_land'))?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Highest networth</span>
			<span class="dataVisibleRight"><?=Format::networth($province->get('highest_networth'))?></span>
		</div>
	</div>
</div>

<div class="col-md-6">
	<div class="blockHeader">Turn usage breakdown</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Regular, Ground and Air & Sea attacks</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('unit_attack')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Thieving</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('thieving')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Spying</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('spying')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Sending snipers</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('sniper')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Laser beam satellite</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('laser_sattelite')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">EMP satellite</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('emp_satellite')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Activating stealth satellite</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('activate_sat')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Sending saboteurs</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('saboteur')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Launching missiles</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('regular_missile')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Launching EMP missiles</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('emp_missile')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Unit turn build</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('unit_turn_build')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Building satellites</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('build_satellite')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Building missiles</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('missiles')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('buildings')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Exploring</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('exploring')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Research</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('research')?></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Queue research</span>
			<span class="dataVisibleRight"><?=$turnSpread->get('research_queue')?></span>
		</div>

	</div>
</div>
</div> <!-- end .pageRow -->
<?php
get_footer();