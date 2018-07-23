<?php

$aw_args = array(
    'post_type'   => 'award',
    'numberposts' => -1,
    'meta_key'    => 'winning_clan',
    'meta_value'  => $clan_id);
$awards = get_posts($aw_args);

$awardsPerRound = [];

$colorMap = [
    "Bronze" => "#CD7F32",
    "Silver" => "silver",
    "Gold"   => "gold"
];

$awardTypeMap = [
    "PTS" => "points champion",
    "UA"  => "united arms",
    "NW"  => "networth champion",
    "UB"  => "united boundaries"
];

$otherAwards = [];

foreach ($awards as $award){
    $meta = get_post_meta($award->ID, '', true);
    $round = $meta['round'][0];
    $position = $meta['position_clan'][0];

    $roundNr   = filter_var($round, FILTER_SANITIZE_NUMBER_INT);
    $color     = $colorMap[$position];
    $awardType = array_search(strtolower($award->post_title), $awardTypeMap);

    if (!isset($color) || $awardType==false) {
        array_push($otherAwards, [
            "round" => $round,
            "color" => $color,
            "name"  => $award->post_title
        ]);

    } else {
        if (!isSet($awardsPerRound[$roundNr])) {
            $awardsPerRound[$roundNr] = array();
        }

        $awardsPerRound[$roundNr][$awardType]=$color;
    }
}
krsort($awardsPerRound);

if (!empty($awardsPerRound)) { ?>

<div class="row fw-row">
  <div data-toggle="tooltip" title="Round" class="col-sm-2 col-2">RND</div>

  <?php foreach ($awardTypeMap as $awardType => $name){ ?>

  <div data-toggle="tooltip" title='<?php echo "$name" ?>' class="col-sm-2 col-2">
    <?php echo "$awardType" ?></div>

  <?php } ?>

</div>

<?php
}

foreach ($awardsPerRound as $roundNr => $awards){ ?>
<div class="row fw-row">
  <div class="col-sm-2 col-2"><?php echo "$roundNr" ?></div>
  <?php
  foreach ($awardTypeMap as $awardType => $name){
    $color = array_key_exists($awardType, $awards) ? $awards[$awardType] : false;
    if ($color) { ?>
    <div class="col-sm-2 col-2">
      <i class="fa fa-trophy fa-lg"
         style="color:<?php echo $color;?>"
         aria-hidden="true"></i>
    </div>
    <?php } else { ?>
    <div class="col-sm-2 col-2">-</div>
    <?php } ?>
  <?php } ?>
</div>

<?php } ?>

<?php foreach ($otherAwards as $award){ ?>
  <i class="fa fa-trophy fa-lg"
     style="color:<?php echo $award['color'];?>"
     aria-hidden="true"></i>
  &nbsp;<?php echo $award["round"];?>: <?php echo $award["name"];?>
  <br ?>
<?php } ?>

<?php if (empty($otherAwards) && empty($awardsPerRound)) { ?>
  <div>¯\_(ツ)_/¯</div>
<?php } ?>
