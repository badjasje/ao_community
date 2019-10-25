<div class="pageSpacer"></div>
<form id="attack" class="attackStep1Table">
	<input type="hidden" id="target_id" name="target_id" value="<?php echo $attackUserId; ?>" />

	<div class="blockHeader">
		Your morale is currently at <?=$province->getMorale(true)?>.
		<? if($province->getSatelliteNum() !== 0) { /*returns shortname */?>
			Satellite power is currently at <?=$province->getSatMorale(true)?>
		<? } ?>
	</div>
	<div class="row no-gutters fw-row">

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="air_sea" value="air_sea" checked>
			<label class="secundarySubmit hoverEffect attackSelect bg-1 <?=($province->getUnitAttackTypeNum('air_sea') > 0  && $province->getMorale() >= Settings::get('attack_morale_tgt_above')?'" for="air_sea':'disabled')?>">
				<i class="fas fa-ship"></i> Air & Sea Attack
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="regular" value="regular">
			<label class="secundarySubmit hoverEffect attackSelect bg-2 <?=($province->getUnitAttackTypeNum('regular') > 0  && $province->getMorale() >= Settings::get('attack_morale_tgt_above')?'" for="regular':'disabled')?>">
				<i class="fas fa-fighter-jet"></i> Regular Attack
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="ground" value="ground">
			<label class="secundarySubmit hoverEffect attackSelect bg-3 <?=($province->getUnitAttackTypeNum('ground') > 0  && $province->getMorale() >= Settings::get('attack_morale_tgt_above')?'" for="ground':'disabled')?>">
				<i class="fas fa-truck-monster"></i> Ground Attack
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="missile" value="missile">
			<label class="secundarySubmit hoverEffect attackSelect bg-4 <?=($province->getMissileNum() > 0  && $province->getMorale() >= Settings::get('missile_morale_tgt_above')?'" for="missile':'disabled')?>">
				<i class="fas fa-rocket"></i> Launch Missile
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="spy" value="spy">
			<label class="secundarySubmit hoverEffect attackSelect bg-5 <?=($province->getUnitAttackTypeNum('spy') > 0 && $province->getMorale() >= Settings::get('spy_morale_cost')?'" for="spy':'disabled')?>">
				<i class="fas fa-binoculars"></i> Send spy
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="thief" value="thief">
			<label class="secundarySubmit hoverEffect attackSelect bg-6 <?=($province->getUnitAttackTypeNum('thief') > 0 && $province->getMorale() >= Settings::get('thief_morale_cost')?'" for="thief':'disabled')?>">
				<i class="fas fa-user-ninja"></i> Send thief
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="sniper" value="sniper">
			<label class="secundarySubmit hoverEffect attackSelect bg-7 <?=($province->getUnitAttackTypeNum('sniper') > 0 && $province->getMorale() >= Settings::get('sniper_morale_cost')?'" for="sniper':'disabled')?>">
				<i class="fas fa-bullseye"></i> Send sniper
			</label>
		</div>

		<? /*returns shortname */?>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="satellite" value="satellite">
			<label class="secundarySubmit hoverEffect attackSelect bg-8 <?=($province->getSatelliteNum() !== 0 && $province->getSatMorale() >= 100 ?'" for="satellite':'disabled')?>">
				<i class="fas fa-satellite"></i> Use satellite
			</label>
		</div>

		<div class="col-md-6 col-lg-4 no-gutters">
			<input class="hidden" type="radio" name="attacktype" id="saboteur" value="saboteur">
			<label class="secundarySubmit hoverEffect attackSelect bg-9 <?=($province->getUnitAttackTypeNum('saboteur') > 0 && $province->getMorale() >= Settings::get('saboteur_morale_cost')?'" for="saboteur"':'disabled')?>">
				<i class="fas fa-bomb"></i> Send saboteur
			</label>
		</div>

	</div>

	<? if(($province->getUnitAttackTypeNum('regular') > 0 || $province->getUnitAttackTypeNum('air_sea') > 0) && $province->getMorale() >= Settings::get('attack_morale_tgt_above')) { ?>
	<div class="row no-gutters">
		<div class="col-md-6 no-gutters">
			<div class="row no-gutters">
				<div class="attackDropdown statCol-1 no-gutters">
					Attack Type
				</div>
				<div style="padding:0px;" class="attackDropdown statCol-2 no-gutters">
					<select name="attackmode" class="attackTypeInput">
						<option name="attackmode" value="normal">Normal</option>
						<option name="attackmode" value="aggressive">Aggressive (Higher gain and higher loss. Costs 10% extra morale.)</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-6 no-gutters">
			<div class="row no-gutters">
				<div class="attackDropdown statCol-3 no-gutters">
					Main target
				</div>
				<div style="padding:0px;" class="attackDropdown statCol-4 no-gutters">
					<select name="maintarget" class="attackTypeInput">
						<option name="maintarget" value="none">-- none --</option>
						<? foreach(Settings::get('attack_maintargets') as $k => $v) { ?>
							<option name="maintarget" value="<?=$k?>"><?=$v?></option>
						<? } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<? } ?>

	<input type="submit" value="Next Step" class="mainSubmit">
</form>