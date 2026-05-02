<?php
require_once("../../../../../wp-load.php");
$units = Units::get();
$missiles = Missiles::get();
$satellites = Satellites::get();

$attackType = filter_input(INPUT_POST, 'attacktype', FILTER_SANITIZE_STRING);
$target_id = filter_input(INPUT_POST, 'target_id', FILTER_VALIDATE_INT);

global $userId;
$userData = get_user_meta($userId);
$tomahawkOwned = $userData['tomahawk_owned'][0];
$typeArray = array('air_sea','ground','regular');
$backColor = "45, 67, 81";
$count = 0;
?>
<div class="pageSpacer"></div>

<div class="attackStep3Table">
<?php
if(in_array($attackType, $typeArray)):
    $attackArray = $_POST['attackarray'];
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-4 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack / Life
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-2 celBlock">
            Sending to battle
        </div>
    </div> <!-- //Close Unit row -->

    <?php
    foreach ($attackArray as $unitKey => $unit) {
        if($unitKey != 'tomahawk'){
			if(in_array($attackType,$units[$unitKey]['attacktype'])){
    			$count++;
			    $unitsOwned = $userData[$unitKey.'_owned'][0];
    			if($unitsOwned > 0){
	    			$canAttack = is_array($units[$unitKey]['attacks']) && !empty($units[$unitKey]['attacks']) ? implode(', ', $units[$unitKey]['attacks']) : 'N/A';
				    $unitTypeKey = $units[$unitKey]['type'];
        			?>
                    <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
                        <div class="col-md-4 celBlock nameBlock sea_heading">
                            <?php echo $units[$unitKey]['normalname'];?>
                            <?php if(isset($units[$unitKey]['description'])):?>
                                <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $units[$unitKey]['description'];?>" data-placement="bottom">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            <?php endif;?>
                        </div>
                        <div class="col-md-3 celBlock">
                            <span class="columnDataLeft">Attack / Life</span>
                            <span class="columnDataRight"><?php echo $units[$unitKey]['attack'];?>/<?php echo $units[$unitKey]['life'];?></span>
                        </div>
                        <div class="col-md-3 celBlock">
                            <span class="columnDataLeft">Targets</span>
                            <span class="columnDataRight"><?php echo $canAttack; ?></span>
                        </div>
                        <div class="col-md-2 celBlock">
                            <span class="columnDataLeft">Sending to battle</span>
                            <span class="columnDataRight"><?php echo $unitsOwned*$unit;?> <sup><?php echo round(ceil($unit*100)); ?>%</sup></span>
                        </div>
                    </div> <!-- //Close Unit row -->
                    <?php
                }
            }
        }
    }

    if(array_key_exists('tomahawk', $_POST['attackarray'])){ ?>
        <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.9-($count/25);?>);">
            <div class="col-md-4 celBlock nameBlock sea_heading">
                Tomahawk
            </div>
            <div class="col-md-3 celBlock">
                <span class="columnDataLeft">Attack / Life</span>
                <span class="columnDataRight">1000 / 0</span>
            </div>
            <div class="col-md-3 celBlock">
                <span class="columnDataLeft">Targets</span>
                <span class="columnDataRight">bds</span>
            </div>
            <div class="col-md-2 celBlock">
                <span class="columnDataLeft">Sending to battle</span>
                <span class="columnDataRight"><?php echo $tomahawkOwned*$unit;?> <sup><?php echo ceil($unit*100); ?>%</sup></span>
            </div>
        </div> <!-- //Close Tomahawk row -->
        <div class="blockHeader">
            Final attack step. Attack when ready!
        </div>
        <?php
    }
endif;

if($attackType == 'missile'):
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-3 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-3 celBlock">
            Launching
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    foreach ($missiles as $missileKey => $missile) {
		if($missileKey != $_POST['missiletype']){
			continue;
		}
		$missilesOwned = $userData[$missileKey.'_owned'][0];

		if($missilesOwned > 0){
			$count++;
			$canAttack = implode(', ', $missile['attacks']);
		    ?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.4-($count/25);?>);">
                <div class="col-md-3 celBlock nameBlock sea_heading">
                    <?php echo $missile['normalname'];?>
                    <?php if(isset($missile['description'])):?>
                        <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $missile['description'];?>" data-placement="bottom">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Attack</span>
                    <span class="columnDataRight"><?php echo $missile['attack'];?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight"><?php echo $canAttack; ?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Lauching</span>
                    <span class="columnDataRight">1</span>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
    }
endif;

if($attackType == 'spy'):
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-3 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack / Life
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-3 celBlock">
            Sending
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    foreach ($units as $key => $unit) {
		if($key != $_POST['spytype']){
			continue;
		}
		$unitsOwned = $userData[$key.'_owned'][0];
		if($unitsOwned > 0){
			$count++;
    		?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
                <div class="col-md-3 celBlock nameBlock sea_heading">
                    <?php echo $unit['normalname'];?>
                    <?php if(isset($unit['description'])):?>
                        <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $missile['description'];?>" data-placement="bottom">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Attack / Life</span>
                    <span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight">
                        <?php if($key == 'spy'):?>Collects intelligence on units
                        <?php else:?>Collects intelligence on buildings
                        <?php endif;?>
                    </span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Sending</span>
                    <span class="columnDataRight">1</span>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
    }
endif;

if($attackType == 'thief'):
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-3 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack / Life
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-3 celBlock">
            Sending
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    foreach ($units as $unitKey => $unit) {
		if($unitKey != 'thief'){ continue; }
		$unitsOwned = $userData[$unitKey.'_owned'][0];
        if($unitsOwned > 0){
            $count++;
            $canAttack = is_array($unit['attacks']) && !empty($unit['attacks']) ? implode(', ', $unit['attacks']) : 'N/A';
            $unitTypeKey = $unit['type'];
            $sendall[] = $unitsOwned;
            ?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.3-($count/25);?>);">
                <div class="col-md-3 celBlock nameBlock sea_heading">
                    <?php echo $unit['normalname'];?>
                    <?php if(isset($unit['description'])):?>
                        <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $unit['description'];?>" data-placement="bottom">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Attack / Life</span>
                    <span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight">Steals money</span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Sending</span>
                    <span class="columnDataRight"><?php echo $_POST['nothiefs']; ?></span>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
    }
endif;

if($attackType == 'sniper'):
    $attackArray = $_POST['attackarray'];
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-4 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack / Life
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-2 celBlock">
            Sending to battle
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    foreach ($attackArray as $unitKey => $unit) {
		$unitsOwned = $userData[$unitKey.'_owned'][0];
		if($unitsOwned > 0){
			$count++;
			$unitTypeKey = $units[$unitKey]['type'];
		    ?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.3-($count/25);?>);">
                <div class="col-md-4 celBlock nameBlock sea_heading">
                    <?php echo $units[$unitKey]['normalname'];?>
                    <?php if(isset($units[$unitKey]['description'])):?>
                        <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $units[$unitKey]['description'];?>" data-placement="bottom">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Attack / Life</span>
                    <span class="columnDataRight"><?php echo $units[$unitKey]['attack'];?>/<?php echo $units[$unitKey]['life'];?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight">Spies, thiefs, snipers, saboteurs</span>
                </div>
                <div class="col-md-2 celBlock">
                    <span class="columnDataLeft">Sending to battle</span>
                    <span class="columnDataRight"><?php echo floor($unitsOwned*$unit);?> <sup><?php echo round($unit*100); ?>%</sup></span>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
    }
endif;

if($attackType == 'saboteur'):
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-3 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-3 celBlock">
            Attack / Life
        </div>
        <div class="col-md-3 celBlock">
            Targets
        </div>
        <div class="col-md-3 celBlock">
            Sending to battle
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    foreach ($units as $key => $unit) {
		if($key != 'saboteur'){
			continue;
		}
		$unitsOwned = $userData[$key.'_owned'][0];
		if($unitsOwned > 0){
			$count++;
    		?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
                <div class="col-md-3 celBlock nameBlock sea_heading">
                    <?php echo $unit['normalname'];?>
                    <?php if(isset($unit['description'])):?>
                        <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $missile['description'];?>" data-placement="bottom">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Attack / Life</span>
                    <span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight">
                        Disables up to 2 missile silos
                    </span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Sending to battle</span>
                    <span class="columnDataRight">1</span>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
    }
endif;

if($attackType == 'satellite'):
    ?>
    <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
        <div class="col-md-6 celBlock nameBlock">
            Name
        </div>
        <div class="col-md-6 celBlock">
            Effect
        </div>
    </div> <!-- //Close Unit row -->
    <?php
    $count = 0;
    foreach ($satellites as $key => $satellite) {
		if($key != $_POST['satellitetype']){
			continue;
		}
		$count++;
		?>
        <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
            <div class="col-md-6 celBlock nameBlock sea_heading">
                <?php echo $satellite['name'];?>
            </div>
            <div class="col-md-6 celBlock">
                <span class="columnDataLeft">Effect</span>
                <span class="columnDataRight"><?php echo $satellite['desc'];?></span>
            </div>
        </div> <!-- //Close Unit row -->
        <?php
    }
endif;
?>
<div class="row statusBlockButtons">
	<a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
		Back
	</a>
	<div id="nextstep3" class="col-md-6 attackStep-2-submit u-no-padding">
		<button class="mainSubmit u-no-border-top" id="attack3">Attack</button>
	</div>
</div>

</div>