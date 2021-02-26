<?php
/**
 * Template Name: Military info
 */
get_header();

$targetID = $_GET['id'];
if(empty($targetID) || !is_numeric($targetID)) {
	wp_redirect(get_permalink(3486));
}
if (get_userdata($targetID) === false ) {
	wp_redirect(get_permalink(3486));
}

$user = CurrentUser::make();
$province = $user->getProvince();
if(!$province->isFellowClanMember($targetID)) {
    wp_redirect(get_permalink(3486));
}

$targetProvince = Province::make($targetID);
?>
<div class="row pageRow">

	<div class="blockHeader"><?=$targetProvince->getLink(true)?></div>
    <div class="row fw-row no-gutters">
        <div class="col-md-6">
	        <div class="blockHeader spaceNotice">Current networth: <?=$targetProvince->getNetworth(true)?></div>
        </div>
        <div class="col-md-6">
            <select class="blockHeader spaceNotice redirectOnChange">
                <option disabled selected>Select clanmember</option>
                <? foreach($targetProvince->getClanMembers() as $member_id) { ?>
                    <option value="?id=<?=$member_id?>"><?=Province::make($member_id)->getName()?></option>
                <? } ?>
            </select>
        </div>
    </div>

	<div class="blockHeader spaceNotice"></div>

	<? /* owned/ordered unites block */ ?>
	<div class="row no-gutters aoTable grey">
		<div class="col-md-6">
            <div class="blockHeader">Units</div>
            <div class="row unitRow headerRow">
                <div class="col-md-6 celBlock nameBlock">Name</div>
                <div class="col-md-6 celBlock">Owned (ordered)</div>
            </div>
            <? foreach ($targetProvince->getUnits() as $key => $unit) {
                if($unit['num'] == 0 && $unit['ordered'] == 0) continue;
                ?>
                <div class="row unitRow">
                    <div class="col-8 col-md-6 celBlock"><?=$unit['normalname']?></div>
                    <div class="col-4 col-md-6 celBlock"><?=$unit['num']?> (<?=$unit['ordered']?>)</div>
                </div>
            <? } ?>
    	</div>
        <div class="col-md-6">
	        <div class="blockHeader">Current orders</div>
            <div class="row unitRow headerRow">
                <div class="col-md-4 celBlock nameBlock">Name</div>
                <div class="col-md-4 celBlock nameBlock">Ordered</div>
                <div class="col-md-4 celBlock nameBlock">Time left</div>
            </div>
            <? foreach($targetProvince->getOrders() as $key => $order) { ?>
                <div class="row unitRow" id="order<?=$order->get('id')?>">
                    <div class="col-4 celBlock"><?=$order->title(true)?></div>
                    <div class="col-4 celBlock"><?=$order->amount(true)?></div>
                    <div class="col-4 celBlock"><span data-countdown="<?=$order->timeLeft()?>"></span></div>
                </div>
            <? } ?>
        </div>
    </div>

    <div class="blockHeader spaceNotice"></div>

    <? /* owned/ordered missiles block */ ?>
    <div class="aoTable grey">
        <div class="blockHeader">Missiles</div>
        <div class="row unitRow headerRow">
            <div class="col-md-6 celBlock nameBlock">Name</div>
            <div class="col-md-6 celBlock nameBlock">Owned (ordered)</div>
        </div>
        <? foreach ($targetProvince->getMissiles() as $key => $missile) {
            if($missile['num'] == 0 && $missile['ordered'] == 0) continue;
            ?>
            <div class="row unitRow">
                <div class="col-8 col-md-6 celBlock"><?=$missile['normalname']?></div>
                <div class="col-4 col-md-6 celBlock"><?=$missile['num']?> (<?=$missile['ordered']?>)</div>
            </div>
        <? } ?>
    </div>

    <div class="blockHeader spaceNotice"></div>

    <? /* owned buildings block */ ?>
    <div class="aoTable grey">
        <div class="blockHeader">Buildings</div>
        <div class="row unitRow headerRow">
            <div class="col-md-6 celBlock nameBlock">Name</div>
            <div class="col-md-6 celBlock nameBlock">Owned</div>
        </div>
        <? foreach ($targetProvince->getBuildings() as $key => $building) {
            if($building['num'] == 0) continue;
            ?>
            <div class="row unitRow">
                <div class="col-8 col-md-6 celBlock"><?=$building['normalname']?></div>
                <div class="col-4 col-md-6 celBlock"><?=$building['num']?></div>
            </div>
        <? } ?>
    </div>

    <div class="blockHeader spaceNotice"></div>

    <? /* Bank block */ ?>
    <div class="aoTable grey">
        <div class="blockHeader">Bank</div>
        <div class="row unitRow headerRow bankHeader">
            <div class="col-md-3 celBlock nameBlock">Deposited</div>
            <div class="col-md-3 celBlock">Including interest</div>
            <div class="col-md-6 celBlock">Releases</div>
        </div>
        <?php foreach ($targetProvince->getDeposits() as $depositId => $deposit) { ?>
            <div class="row unitRow">
                <div class="col-3 celBlock"><?=$deposit->deposited(true)?></div>
                <div class="col-3 celBlock"><?=$deposit->finalAmount(true)?></div>
                <div class="col-6 celBlock"><span data-countdown="<?=$deposit->timeLeft()?>"></span></div>
            </div>
        <?php } ?>
    </div>

    <div class="blockHeader spaceNotice"></div>

    <? /* Research block */ ?>
    <div class="aoTable grey">
        <div class="blockHeader">Research</div>
        <div class="row unitRow headerRow">
            <div class="col-md-6 celBlock nameBlock">Name</div>
            <div class="col-md-6 celBlock">Level</div>
        </div>
        <? foreach($targetProvince->getResearches() as $key => $research) {
            if($research['level']==0) continue;?>
            <div class="row unitRow">
                <div class="col-8 col-md-6 celBlock"><?=$research['name']?></div>
                <div class="col-4 col-md-6 celBlock"><?=$research['level']?></div>
            </div>
        <? } ?>
    </div>

</div>
<?php
get_footer();