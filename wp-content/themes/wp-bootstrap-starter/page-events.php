<?php
get_header();

$prev = get_previous_posts_link('<i class="fas fa-arrow-left"></i> Previous');
$next = get_next_posts_link('Next <i class="fas fa-arrow-right"></i>');
?>
<div class="row pageRow">
    <div class="row no-gutters fw-row profileButtonRow">
        <a class="col-md-4 profileButton firstButton" href="<?=Request::siteUrl()?>/events/incoming"><i class="fa fa-arrow-circle-down"></i> Incoming events</a>
        <a class="col-md-4 profileButton secondButton" href="<?=Request::siteUrl()?>/events/outgoing"><i class="fa fa-arrow-circle-up"></i> Outgoing events</a>
        <a class="col-md-4 profileButton thirdButton" href="<?=Request::siteUrl()?>/events/global"><i class="fa fa-globe"></i> Global events</a>
    </div>

    <div class="pageSpacer"></div>

    <? foreach($events as $event) { ?>
        <div id="event-<?=$event->get('id')?>" class="event <?=$event->get('eventtype')?>">
            <div class="eventHeader">
                <?=$event->getIcon(true)?><div class="blockHeader"><?=$event->getHeader(true)?></div>
            </div>
            <div class="eventMain">
                <div class="eventImageCol"><?=$event->getAvatar(true)?></div>
                <div class="eventBody">
                    <div class="eventMainMessage"><?=$event->getTitle(true)?></div>
                    <div class="eventResultRow"><?=$event->getBody(true)?></div>
                </div>
            </div>
            <div class="row statusBlockButtons eventFooter">
                <div class="col-md-3 totalsField statCol-1"><?=$event->getCol1(true)?></div>
                <div class="col-md-3 totalsField statCol-2"><?=$event->getCol2(true)?></div>
                <div class="col-md-3 totalsField statCol-3"><?=$event->getCol3(true)?></div>
                <div class="col-md-3 totalsField statCol-4"><?=$event->getCol4(true)?></div>
            </div>
        </div>
    <? } ?>

    <div class="pageSpacer"></div>

    <div class="row fw-row no-gutters">
        <?=(!empty($prev)?$prev:'<div class="col-sm-6 statCol-1"></div>')?>
        <?=(!empty($next)?$next:'<div class="col-sm-6 statCol-1"></div>')?>
    </div>
</div>
<?php

get_footer();
