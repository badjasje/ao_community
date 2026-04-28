<?php

$awardTypeMap = [
    "PTS" => "points champion",
    "UA"  => "united arms",
    "NW"  => "networth champion",
    "UB"  => "united boundaries"
];

$awards = get_posts(array('post_type' => 'award', 'numberposts' => -1, 'meta_key' => 'winning_clan', 'meta_value' => $clan_id));

$awardsPerRound = [];
$otherAwards = [];
foreach ($awards as $award){
    $meta = get_post_meta($award->ID, '', true);
    $round = $meta['round'][0];
    $position = $meta['position_clan'][0];

    $roundNr   = filter_var($round, FILTER_SANITIZE_NUMBER_INT);
    $awardType = array_search(strtolower($award->post_title), $awardTypeMap);
    if ($awardType==false) {
        array_push($otherAwards, ["round" => $round,"color" => $position, "name"  => $award->post_title]);
    } else {
        if (!isSet($awardsPerRound[$roundNr])) $awardsPerRound[$roundNr] = array();
        $awardsPerRound[$roundNr][$awardType] = $position;
    }
}
krsort($awardsPerRound);

if (!empty($awardsPerRound)) { ?>
    <div class="row fw-row">
        <div data-toggle="tooltip" title="Round" class="col-sm-2 col-2">RND</div>
        <? foreach ($awardTypeMap as $awardType => $name) { ?>
            <div data-toggle="tooltip" title="<?=$name?>" class="col-sm-2 col-2">
                <?=$awardType?>
            </div>
        <? } ?>
    </div>
    <?
}

if(empty($otherAwards) && empty($awardsPerRound)) {
    echo '<div>¯\_(ツ)_/¯</div>'.PHP_EOL;
}
else {

    foreach ($awardsPerRound as $roundNr => $awards) { ?>
        <div class="row fw-row">
            <div class="col-sm-2 col-2"><?=$roundNr?></div>
            <?
            foreach ($awardTypeMap as $awardType => $name) {
                $color = array_key_exists($awardType, $awards) ? $awards[$awardType] : false;
                ?>
                <div class="col-sm-2 col-2">
                    <?=($color ? '<i class="fa fa-trophy fa-lg award_'.$color.'" aria-hidden="true"></i>' : ' ')?>
                </div>
                <?
            } ?>
        </div>
    <? } ?>

    <? foreach($otherAwards as $award) { ?>
        <i class="fa fa-trophy fa-lg award_<?=$award['color']?>" aria-hidden="true"></i>
        &nbsp;<?=$award["round"]?>: <?=$award["name"]?>
        <br>
    <? }
}
