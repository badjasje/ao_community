<? if(empty($province->getStartingBonus())) { ?>

	<div class="startingBonusPicker">
	    <div id="startingBonus" class="blockHeader">
			Welcome back! You either died, reset, or this is a brand new round of Assault.Online.<br/>
			<h3> Please pick a starting strategy. </h3>
			Note that once selected, only a death or reset will let you change your mind.
			If you are new to Assault.online, we strongly recommend you review the
			<strong><a href="<?=Request::siteUrl()?>/getting-started" target="_blank">Getting Started</a></strong>
			guide before selecting a bonus/strategy.<br/><br/>
		</div>

		<form class="form clear" id="pickStartingBonus" method="post">
			<? $c=0; foreach(Startbonuses::get() as $type => $bonus) { ?>
				<input required class="bonustype" type="radio" name="bonustype" id="<?=$type?>" value="<?=$type?>" >
				<label class="startingbonus<?=($c==1||$c==2?' darkerBonus':'')?>" for="<?=$type?>">
					<h3 class="startinghead"><i class="<?=$bonus['icon']?>" aria-hidden="true"></i> <?=$bonus['name']?></h3>
					<?=$bonus['description']?>
				</label>
			<? $c++; } ?>
			<input type="submit" value="Pick starting bonus" class="mainSubmit hoverEffect bonusSubmit">
		</form>

 		<div class="pageSpacer"></div>
	</div>

<? }
