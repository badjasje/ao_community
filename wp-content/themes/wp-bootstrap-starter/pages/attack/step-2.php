<?php
require_once("../../../../../wp-load.php");
include("../../../../../units_array.php");
include("../../../../../missiles_array.php");
include("../../../../../satellite_array.php");

$attackType = filter_input(INPUT_POST, 'attacktype', FILTER_SANITIZE_STRING);
$target_id = filter_input(INPUT_POST, 'target_id', FILTER_VALIDATE_INT);
global $userId;
$userData = get_user_meta($userId);

$typeArray = array('air_sea','ground','regular');
$backColor = "45, 67, 81";
$count = 0;
?>
<div class="pageSpacer"></div>

<form id="attack2" class="attackStep2Table">
    <?php
    if(in_array($attackType, $typeArray)):
		$sendall = array();
		$tomahawkOwned = $userData['tomahawk_owned'][0];
		?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack / Life
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->
    	<?php foreach ($units as $unitKey => $unit) { ?>
    		<?php if(in_array($attackType,$unit['attacktype'])) {
    			$unitsOwned = $userData[$unitKey.'_owned'][0];
                if($unitsOwned > 0) {
                    $count++;
                    $canAttack = is_array($unit['attacks']) && !empty($unit['attacks']) ? implode(', ', $unit['attacks']) : 'N/A';
                    $unitTypeKey = $unit['type'];
                    $sendall[] = $unitsOwned;
			        ?>
        			<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    				    <div class="col-md-3 celBlock nameBlock sea_heading">
	    			        <?php echo $unit['normalname'];?>
                            <?php if(isset($unit['description'])):?>
                                <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $unit['description'];?>" data-placement="bottom">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            <?php endif;?>
				        </div>
                        <div class="col-md-2 celBlock">
                            <span class="columnDataLeft">Attack / Life</span>
                            <span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
                        </div>
                        <div class="col-md-3 celBlock">
                            <span class="columnDataLeft">Targets</span>
                            <span class="columnDataRight"><?php echo $canAttack; ?></span>
                        </div>
                        <div id="button<?php echo $unitKey;?>" class="col-md-2 celBlock maxBlock" data-key="<?php echo $unitKey;?>" data-units-owned="<?php echo $unitsOwned; ?>">
                            <span class="columnDataLeft">Owned</span>
                            <span class="columnDataRight"><?php echo $unitsOwned; ?></span>
                        </div>
                        <div class="col-md-2 celBlock inputBlock">
                            <input id="<?php echo $unitKey;?>" style="border: 5px solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);" class="unitInput <?php echo $unitKey;?>-input" min="0" type="number" id="<?php echo $unitKey;?>" name="<?php echo $unitKey;?>"/>
                        </div>
                    </div> <!-- //Close Unit row -->
                    <?php
                }
            }
        }

        if($tomahawkOwned > 0 && $attackType == 'air_sea') {
            $tot_units = 0;
            $tomahawkspace = $userData['submarine_owned'][0]*2;
            $maxNetworth = round($userData['networth'][0]/10000*2);
            $maxTomahawk = min($tomahawkspace,$maxNetworth,$tomahawkOwned);
            $sendall[] = $maxTomahawk;
			?>
			<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.9-($count/25);?>);">
                <div class="col-md-3 celBlock nameBlock sea_heading">
                    Tomahawk
                </div>
                <div class="col-md-2 celBlock">
                    <span class="columnDataLeft">Attack / Life</span>
                    <span class="columnDataRight">1000 / 0</span>
                </div>
                <div class="col-md-3 celBlock">
                    <span class="columnDataLeft">Targets</span>
                    <span class="columnDataRight">bds</span>
                </div>
                <div id="button_tomahawk" class="col-md-2 celBlock maxBlock">
                    <span class="columnDataLeft">Owned</span>
                    <span class="columnDataRight"><?php echo $maxTomahawk; ?></span>
                </div>
                <div class="col-md-2 celBlock inputBlock">
                    <input id="tomahawk"
                            style="border: 5px solid rgba(<?php echo $backColor;?>, <?php echo 0.9-($count/25);?>);"
                            class="unitInput"
                            min="0"
                            max="<?php echo $maxTomahawk;?>"
                            type="number"
                            name="tomahawk"/>
                </div>
            </div> <!-- //Close Tomahawk row -->
			<div class="blockHeader">
				You can send a maximum of 2 tomahawk missiles per submarine with a maximum of 2 tomahawks for every $10 000 networth you own.
			</div>
			<script type="text/javascript">
                jQuery("#button_tomahawk").click(function() {
                    var maxtomahawk = Math.min(jQuery('#submarine').val() * 2,<?php echo $maxTomahawk;?>);
                jQuery("#tomahawk").val(maxtomahawk);
                });

                jQuery("#buttonsubmarine").click(function() {
                    var maxtomahawk = Math.min(jQuery('#submarine').val() * 2,<?php echo $maxTomahawk;?>);
                    jQuery('#button_tomahawk').text(maxtomahawk);
                    jQuery("#tomahawk").attr({
                        "max" : maxtomahawk        // substitute your own

                        });

                    });

                jQuery(document).ready(function(){
                    jQuery("#submarine").bind("change paste propertychange click", function() {
                        var maxtomahawk = Math.min(jQuery('#submarine').val() * 2,<?php echo $maxTomahawk;?>);
                        jQuery('#button_tomahawk').text(maxtomahawk);
                        jQuery("#tomahawk").attr({
                        "max" : maxtomahawk        // substitute your own

                        });
                    });
                });
            </script>
            <?php
        } ?>
        <div class="row statusBlockButtons">
            <a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-4 totalsField statCol-1">
                Back
            </a>
            <div id="sendAll" class="col-md-4 totalsField statCol-2" data-val="<?php echo implode('|',$sendall);?>">
                Send all available units
            </div>
            <div id="nextstep3" class="col-md-4 attackStep-2-submit" style="padding:0px;">
                <input class="mainSubmit" type="submit" value="Next step" style="border-top:0px;">
            </div>
        </div>
        <script>
            jQuery("body").on('click','.maxBlock', function() {
                var key = jQuery(this).attr('data-key');
                var owned = jQuery(this).attr('data-units-owned');
                jQuery("."+key+"-input").val(owned);
            });
            (function($) {
                $("body").on('click','.mainSubmit', function(e) {
                    var total = 0;
                    $('.unitInput').each(function() {
                        if($(this).val()!=''&&parseInt($(this).val())>0) total+=parseInt($(this).val());
                    });
                    if(total==0 && !confirm('No units selected, are you sure?')) e.preventDefault();
                });
            })(jQuery);
        </script>
    <?php
    endif;

    if($attackType == 'missile'): ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->

        <?php
        foreach ($missiles as $missileKey => $missile) {
            if($missileKey == 'tomahawk'){
                continue;
            }
    		$missilesOwned = $userData[$missileKey.'_owned'][0];
            if($missilesOwned > 0){
                $count++;
                $canAttack = implode(', ', $missile['attacks']);
                ?>
				<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
				    <div class="col-md-3 celBlock nameBlock sea_heading">
				        <?php echo $missile['normalname'];?>
				        <?php if(isset($missile['description'])):?>
				            <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $missile['description'];?>" data-placement="bottom">
								<i class="fa fa-info-circle" aria-hidden="true"></i>
                            </span>
				        <?php endif;?>
				    </div>
				    <div class="col-md-2 celBlock">
					    <span class="columnDataLeft">Attack</span>
						<span class="columnDataRight"><?php echo $missile['attack'];?></span>
				    </div>
				    <div class="col-md-3 celBlock">
						<span class="columnDataLeft">Targets</span>
						<span class="columnDataRight"><?php echo $canAttack; ?></span>
				    </div>
                    <div class="col-md-2 celBlock">
                        <span class="columnDataLeft">Owned</span>
                        <span class="columnDataRight"><?php echo $missilesOwned; ?></span>
				    </div>
				    <div class="col-md-2 celBlock inputBlock">
				        <input style="display:none;" type="radio" name="missiletype" id="<?php echo $missileKey;?>" value="<?php echo $missileKey;?>" checked>
                        <label style="background-color:rgba(70, 118, 94,<?php echo 0.95-($count/12);?>)" class="mainSubmit hoverEffect attackSelect" for="<?php echo $missileKey;?>">
                            Select
                        </label>
                    </div>
                </div> <!-- //Close Unit row -->
                <?php
            }
        }
        ?>
		<div class="row statusBlockButtons">
			<a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
        		Back
	        </a>
	        <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0;">
		        <input class="mainSubmit" type="submit" value="Next step" style="border-top:0px;">
	        </div>
        </div>
    <?php
    endif;

    if($attackType == 'spy'):?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack / Life
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->
        <?php
        foreach ($units as $key => $unit) {
		    if($key != 'spy' && $key != 'spyplane'){
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
				    <div class="col-md-2 celBlock">
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
                    <div class="col-md-2 celBlock">
                        <span class="columnDataLeft">Owned</span>
                        <span class="columnDataRight"><?php echo $unitsOwned; ?></span>
				    </div>
				    <div class="col-md-2 celBlock inputBlock">
				        <input style="display:none;" type="radio" name="spytype" id="<?php echo $key;?>_select" value="<?php echo $key;?>" required>
                        <label style="background-color:rgba(70, 118, 94,<?php echo 0.95-($count/12);?>)" class="mainSubmit hoverEffect attackSelect" for="<?php echo $key;?>_select">
                            Select
                        </label>
				    </div>
				</div> <!-- //Close Unit row -->
                <?php
            }
        } ?>
		<div class="row statusBlockButtons">
			<a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
        		Back
	        </a>
	        <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0;">
		        <input class="mainSubmit" type="submit" value="Next step" style="border-top:0px;">
	        </div>
        </div>
        <?php
    endif;

    if($attackType == 'thief'):
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack / Life
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->

	    <?php foreach ($units as $unitKey => $unit) {
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
				    <div class="col-md-2 celBlock">
					    <span class="columnDataLeft">Attack / Life</span>
						<span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
				    </div>
				    <div class="col-md-3 celBlock">
						<span class="columnDataLeft">Targets</span>
						<span class="columnDataRight">Steals money</span>
				    </div>
                    <div class="col-md-2 celBlock">
                        <span class="columnDataLeft">Owned</span>
                        <span class="columnDataRight"><?php echo $unitsOwned; ?></span>
				    </div>
				    <div class="col-md-2 celBlock inputBlock">
						<div style="padding:0px; width:100%;" class="attackDropdown statCol-4 no-gutters">
							<select name="nothiefs" class="attackTypeInput">
								<option name="nothiefs" value="1"<?=($unitsOwned==1?' selected':'')?>>1</option>
								<option name="nothiefs" value="2"<?=($unitsOwned==2?' selected':'')?>>2</option>
								<option name="nothiefs" value="3"<?=($unitsOwned==3?' selected':'')?>>3</option>
								<option name="nothiefs" value="4"<?=($unitsOwned==4?' selected':'')?>>4</option>
								<option name="nothiefs" value="5"<?=($unitsOwned==5?' selected':'')?>>5</option>
								<option name="nothiefs" value="6"<?=($unitsOwned==6?' selected':'')?>>6</option>
								<option name="nothiefs" value="7"<?=($unitsOwned==7?' selected':'')?>>7</option>
								<option name="nothiefs" value="8"<?=($unitsOwned==8?' selected':'')?>>8</option>
								<option name="nothiefs" value="9"<?=($unitsOwned==9?' selected':'')?>>9</option>
								<option name="nothiefs" value="10"<?=($unitsOwned>=10?' selected':'')?>>10</option>
							</select>
						</div>
				    </div>
				</div> <!-- //Close Unit row -->
                <?php
            }
        } ?>
		<div class="row statusBlockButtons">
            <a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
                Back
            </a>
            <?php if($unitsOwned > 0):?>
            <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0;">
                <input class="mainSubmit" type="submit" value="Next step" style="border-top:0px;">
            </div>
            <?php endif;?>
        </div>
        <?php
    endif;

    if($attackType == 'sniper'):
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack / Life
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->

    	<?php foreach ($units as $unitKey => $unit) {
	    	if($unitKey != 'sniper'){ continue; }

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
				    <div class="col-md-2 celBlock">
					    <span class="columnDataLeft">Attack / Life</span>
						<span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
				    </div>
				    <div class="col-md-3 celBlock">
						<span class="columnDataLeft">Targets</span>
						<span class="columnDataRight">Spies, thiefs, snipers, saboteurs</span>
				    </div>
				    <div id="button<?php echo $unitKey;?>" class="col-md-2 celBlock maxBlock" data-key="<?php echo $unitKey;?>" data-units-owned="<?php echo $unitsOwned; ?>">
                        <span class="columnDataLeft">Owned</span>
                        <span class="columnDataRight"><?php echo $unitsOwned; ?></span>
				    </div>
				    <div class="col-md-2 celBlock inputBlock">
				        <input id="<?php echo $unitKey;?>" style="border: 5px solid rgba(<?php echo $backColor;?>, <?php echo 0.3-($count/25);?>);" max="10" class="unitInput <?php echo $unitKey;?>-input" min="0" value="" type="number" id="<?php echo $unitKey;?>" name="<?php echo $unitKey;?>"/>
				    </div>
				</div> <!-- //Close Unit row -->
                <?php
            }
        } ?>
		<div class="row statusBlockButtons">
    		<a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
	        	Back
	        </a>
            <?php if($unitsOwned > 0):?>
                <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0px;">
                    <input class="mainSubmit" type="submit" value="Next Step" style="border-top:0px;">
                </div>
            <?php endif;?>
        </div>
        <script>
            jQuery( "body" ).on('click','.maxBlock',function() {
                var key = jQuery(this).attr('data-key');
                var owned = jQuery(this).attr('data-units-owned');
                jQuery("."+key+"-input").val(owned);
            });
        </script>
    <?php
    endif;

    if($attackType == 'saboteur'):
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-3 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock">
                Attack / Life
            </div>
            <div class="col-md-3 celBlock">
                Targets
            </div>
            <div class="col-md-2 celBlock">
                Owned
            </div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- //Close Unit row -->

        <?php foreach ($units as $key => $unit) {
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

				    <div class="col-md-2 celBlock">
					    <span class="columnDataLeft">Attack / Life</span>
						<span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
				    </div>
				    <div class="col-md-3 celBlock">
						<span class="columnDataLeft">Targets</span>
						<span class="columnDataRight">
							Disables up to 2 missile silos
						</span>
				    </div>
				        <div class="col-md-2 celBlock">
							<span class="columnDataLeft">Owned</span>
							<span class="columnDataRight"><?php echo $unitsOwned; ?></span>
				    </div>
				    <div class="col-md-2 celBlock inputBlock">
				        <input style="display:none;" type="radio" name="saboteur" id="<?php echo $key;?>_select" value="<?php echo $key;?>" required>
							<label style="background-color:rgba(70, 118, 94,<?php echo 0.95-($count/12);?>)" class="mainSubmit hoverEffect attackSelect" for="<?php echo $key;?>_select">
								Select
							</label>
				    </div>
				</div> <!-- //Close Unit row -->
                <?php
            }
        }
        ?>
		<div class="row statusBlockButtons">
            <a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
                Back
            </a>
            <?php if($unitsOwned > 0):?>
                <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0;">
                    <input class="mainSubmit" type="submit" value="Next step" style="border-top:0px;">
                </div>
            <?php endif;?>
        </div>
    <?php
    endif;

    if($attackType == 'satellite'):
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-4 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-4 celBlock">
                Effect
            </div>
            <div class="col-md-4 celBlock">
            </div>
        </div> <!-- //Close Unit row -->

        <?php
        $count = 0;
        foreach ($satellites as $key => $satellite) {
            if($key != $userData['sat_owned'][0]){
                continue;
            }
            $count++;
		    ?>
            <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
                <div class="col-md-4 celBlock nameBlock sea_heading">
                    <?php echo $satellite['name'];?>
                </div>

                <div class="col-md-4 celBlock">
                    <span class="columnDataLeft">Effect</span>
                    <span class="columnDataRight"><?php echo $satellite['desc'];?></span>
                </div>
                <div class="col-md-4 celBlock inputBlock">
                    <input style="display:none;" type="radio" name="satellitetype" id="<?php echo $key;?>_select" value="<?php echo $key;?>" required>
                    <label style="background-color:rgba(70, 118, 94,<?php echo 0.95-($count/12);?>)" class="mainSubmit hoverEffect attackSelect" for="<?php echo $key;?>_select">
                        Select
                    </label>
                </div>
            </div> <!-- //Close Unit row -->
            <?php
        }
        ?>
		<div class="row statusBlockButtons">
            <a id="stepback" href="/attack/?id=<?php echo $target_id?>" class="col-md-6 totalsField statCol-1">
                Back
            </a>
            <?php if($userData['sat_owned'][0] == 'laser' || $userData['sat_owned'][0] == 'empsat'):?>
            <div id="nextstep3" class="col-md-6 attackStep-2-submit" style="padding:0px;">
                <input class="mainSubmit" type="submit" value="Next Step" style="border-top:0px;">
            </div>
            <?php endif;?>
        </div>
    <?php
    endif;
    ?>
</form>

<script>
(function($) {
    $("#sendAll").on("click", function() {
        var val = $(this).data("val").toString().split("|");
        $(".unitInput").val(function(i) {
            return val[i] || "";
        });
    });
    var val2 = $("#sendAll").data("val");
    if(!!val2 && val2.toString().split("|").length ==1) {
        $("#sendAll").trigger('click');
    }
})(jQuery);
</script>
