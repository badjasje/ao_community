<div class="pageSpacer"></div>
<form id="attack">
	<input type="hidden" id="target_id" name="target_id" value="<?php echo $attackUserId; ?>" />
	
	
	<div class="blockHeader">
		Your morale is currently at <?php echo $morale;?>%. 
		<?php if(!empty($satOwned)){ echo 'Satellite power is currently at '. $sat_morale.'%';}?>
	</div>
	<div class="row no-gutters fw-row">
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="air_sea" value="air_sea" checked>
			<label style="background-color:rgba(66, 92, 107,1)" class="mainSubmit hoverEffect attackSelect" for="air_sea" data-show="air_sea-info">
				<i class="flaticon-ship"></i> Air & Sea Attack
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="regular" value="regular">
			<label style="background-color:rgba(66, 92, 107,0.95)"class="mainSubmit hoverEffect attackSelect" for="regular">
				<i class="flaticon-fighter-plane"></i> Regular Attack
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="ground" value="ground">
			<label style="background-color:rgba(66, 92, 107,0.9)"class="mainSubmit hoverEffect attackSelect" for="ground">
				<i class="flaticon-tank"></i> Ground Attack
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="missile" value="missile">
			<label style="background-color:rgba(66, 92, 107,0.85)"class="mainSubmit hoverEffect attackSelect" for="missile">
				<i class="flaticon-radioactive"></i> Launch Missile
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="spy" value="spy">
			<label style="background-color:rgba(66, 92, 107,0.8)"class="mainSubmit hoverEffect attackSelect" for="spy">
				<i class="flaticon-fighter-plane-1"></i> Send spy
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="thief" value="thief">
			<label style="background-color:rgba(66, 92, 107,0.75)"class="mainSubmit hoverEffect attackSelect" for="thief">
				<i class="flaticon-secret-agent"></i> Send thief
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="sniper" value="sniper">
			<label style="background-color:rgba(66, 92, 107,0.7)"class="mainSubmit hoverEffect attackSelect" for="sniper">
				<i class="flaticon-bullet"></i> Send sniper
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="satellite" value="satellite">
			<label style="background-color:rgba(66, 92, 107,0.65)"class="mainSubmit hoverEffect attackSelect" for="satellite">
				<i class="flaticon-objective"></i> Use satellite
			</label>
		</div>
		<div class="col-md-6 col-lg-4 no-gutters">
			<input style="display:none;" type="radio" name="attacktype" id="saboteur" value="saboteur">
			<label style="background-color:rgba(66, 92, 107,0.6)"class="mainSubmit hoverEffect attackSelect" for="saboteur">
				<i class="flaticon-bomb-1"></i> Send saboteur
			</label>
		</div>
	</div>
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
					<option name="maintarget" value="power">Power plants</option>
					<option name="maintarget" value="silo">Missile silos</option>
					<option name="maintarget" value="command">Command centres</option>
					<option name="maintarget" value="shipyard">Shipyards</option>
					<option name="maintarget" value="airfield">Airfields</option>
					<option name="maintarget" value="barracks">Barracks</option>
					<option name="maintarget" value="warfactory">Warfactories</option>
					<option name="maintarget" value="defense">Defense buildings</option>
					<option name="maintarget" value="ams">Anti-Missile System</option>
				</select>
			</div>
		</div>
	</div>
</div>
	
	
	<input type="submit" value="Next Step" class="mainSubmit">
</form>	