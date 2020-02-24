<?php
/**
 * Template Name: Market
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$shipping_time = $province->getShippingTime();
$shipping_discount = $province->getShippingDiscount();
$special_sold = $province->get('special_sold_today');
$max_special_sell = Settings::get('max_special_sell');
$unitTypes = Settings::get('unit_types');
$space = $province->getUnitTypeSpace();
$usedSpace = $province->getUnitTypeUsedSpace();
$units = $province->getUnits();
$buildings = Buildings::get();
$unitTypeBuildingNames = array();
foreach($buildings as $building) {
	if(!isset($building['houses'])) continue;
	$unitTypeBuildingNames[$building['houses']] = $building['normalname'];
}
$disabled = Market::isOpen() ? '' : ' disabled';
$disabledDiv = Market::isOpen() ? '' : ' disabledDiv';


$activeTab = isset($_GET['tab']) ? Request::get('tab') : $province->getMostUsedUnitType();
?>
<div class="row pageRow">

    <nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<?php foreach($unitTypes as $key => $unitType) { ?>
		<a class="nav-item nav-link navItem <?=($activeTab==$key?'active':'')?>" data-toggle="tab" data-target="#<?=$key?>" href="?tab=<?=$key?>"><?=$unitType?></a>
		<?php } ?>
	</nav>

	<form class="form aoPage grey" name="market" id="market" method="post">
        <div class="tab-content current build_content tabbed-table">
			<?php foreach($unitTypes as $key => $unitType) { ?>
                <div class="tab-pane smallTable unitBuildTable <?=($activeTab == $key ? 'active' : '')?>" id="<?=$key?>" role="tabpanel">
                    <div class="blockHeader spaceNotice">
                        <p>
                            Your empty <?=$unitTypeBuildingNames[$key]?> allow you to order a maximum of
                            <strong><span id="<?=$key?>spacecount"><?=($space[$key]-$usedSpace[$key])?></span>
                            <?=strtolower($unitType)?></strong>.
                            Prices are higher because it costs no turns. Selling and buying at the same time will trade units which is cheaper.
                            You can sell a maximum of <span class="maxSpecialSell" data-amount="<?=$max_special_sell?>"><?=$max_special_sell?></span> special units per day.
                            <span class="specialSold"><?=$special_sold?></span> special units sold today.
                            It takes <strong><?=$shipping_time?> hours</strong> before units arrive.
                            <? if($shipping_discount>0) { ?>You have a <strong><?=$shipping_discount*100?>%</strong> discount. <?} ?>
                        </p>
                        <div class="text-right small">
                            <a href="javascript:void(0);" class="descriptionToggle" data-type="market"><span>Show</span> descriptions &nbsp; <i class="fa fa-align-justify"></i></a>
                        </div>
                    </div>

                    <table class="aoTable grey">
                        <tr class="unitRow headerRow">
                            <th class="nameBlock">Name</th><th class="price">Price</th><th class="attacklife">Att / Life</th>
                            <th class="targets">Targets</th><th class="owned">Owned</th><th class="max">Max</th>
                            <th class="buildBlock">Order</th><th class="demoBlock">Sell</th>
                        </tr>
                        <?php $count=0;
			            foreach($units as $unitKey => $unit) {
                            if ($unit['type'] != $key) continue;
                            $canAttack = is_array($unit['attacks']) && count($unit['attacks']) ? implode(', ', $unit['attacks']) : 'N/A';
							$count++;
                            ?>
                            <tr class="unitRow <?=$unitKey?>"
                                data-nw="<?=$unit['networthPerUnit']?>"
                                data-buyprice="<?=$unit['orderprice']?>"
                                data-sellprice="<?=$unit['sellprice']?>"
                                data-tradeprice="<?=$unit['tradeprice']?>"
                                data-key="<?=$unitKey?>"
                                data-space="<?=$unit['space']?>"
                                <?=($unit['sectype']=='special' ? ' data-specialspace="'.$unit['specialspace'].'"' : '')?>
                                >
                                <td class="nameBlock buildings_heading"><?=$unit['normalname']?></td>
                                <td class="price"><?=Format::money($unit['orderprice'])?></td>
                                <td class="attacklife"><?=$unit['attack']?>/<?=$unit['life']?></td>
                                <td class="targets"><?=$canAttack?></td>
                                <td class="owned demomax" data-amount="<?=$unit['maxsell']?>">
                                    <span class="num_owned"><?=$unit['num']?></span>
                                    <span class="num_ordered"><?=($unit['ordered']>0 ? ' ('.$unit['ordered'].')' : '')?></span>
                                </td>
                                <td class="maxBlock buildmax" data-amount="<?=$unit['maxorder']?>"><?=$unit['maxorder']?></td>
                                <td class="inputBlock buildBlock<?=$disabledDiv?>">
                                    <input class="unitInput<?=$disabled?>"<?=$disabled?> min="0" max="<?=$unit['maxorder']?>" tabindex="<?=$count?>" type="number" name="build[<?=$unitKey?>]">
                                </td>
                                <td class="inputBlock demoBlock">
                                    <input class="unitInput" min="0" max="<?=$unit['maxsell']?>" tabindex="<?=($count+count($units))?>" type="number" name="demo[<?=$unitKey?>]">
                                </td>
                            </tr>
                            <tr class="descriptionRow">
								<td colspan="8">
									<?=(isset($unit['description'])?$unit['description'].'<br>':'')?>
                                    Order: <?=Format::money($unit['orderprice'])?>, Sell: <?=Format::money($unit['sellprice'])?>,
                                    Trade: <?=Format::money($unit['tradeprice'])?><br>
									<div class="d-block d-md-none">
										Attack: <?=$unit['attack']?>, Life: <?=$unit['life']?>, Targets: <?=$canAttack?>
									</div>
								</td>
							</tr>
                        <? } ?>
                    </table>
                </div>
            <? } ?>

            <div class="blockHeader spaceNotice">
                <div class="row">
                    <div class="col-7 col-md-5 d-flex">
                        <? if($province->hasStartingBonus('shipping')) { ?>
                        <div class="w-100">Delay order <small>(in minutes) </small></div>
                        <input class="unitInput" type="number" min="0" id="delay" name="delay" placeholder="0">
                        <? } ?>
                    </div>
                    <div class="col-5 col-md-7">
                        <div class="text-right small">
                            <a href="javascript:void(0);" class="descriptionToggle" data-type="market"><span>Show</span> descriptions &nbsp; <i class="fa fa-align-justify"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row statusBlockButtons">
                <div class="col-md-4 totalsField statCol-1">Units: <span id="total"></span></div>
                <div class="col-md-4 totalsField statCol-2">Money: <span>$ <span id="cost_total"></span></span></div>
                <div class="col-md-4 totalsField statCol-4">New Nw.: $ <span id="networth_new"></span></div>
            </div>

            <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
            <input type="submit" value="<?=(Market::isOpen()?'Order / Sell / Trade':'Sell')?>" class="mainSubmit hoverEffect<?=$disabled?>">

        </div>
    </form>

</div>
<?php
get_footer();