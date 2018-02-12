<?php

$aw_args = array(
    'post_type'	  => 'award',
    'numberposts' => -1,
    'meta_key' 	  => 'winning_clan',
    'meta_value'  => $clan_id);
$awards = get_posts($aw_args);

$awardsPerRound = [];

$colorMap = [
    "Bronze" => "#CD7F32",
    "Silver" => "silver",
    "Gold"   => "gold"
];

$awardTypeMap = [
    "Points Champion"   => "Pts",
    "Points champion"   => "Pts",
    "United Arms"       => "UA",
    "Networth Champion" => "NW",
    "United Boundaries" => "UB"
];

$otherAwards = [];

foreach ($awards as $award){
    $meta = get_post_meta($award->ID, '', true);
    $round = $meta['round'][0];
    $position = $meta['position_clan'][0];

    $roundNr   = filter_var($round, FILTER_SANITIZE_NUMBER_INT);
    $color     = $colorMap[$position];
    $awardType = $awardTypeMap[$award->post_title];

    if (!isset($color) || !isset($awardType)) {
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
ksort($awardsPerRound);
$awardsPerRound = array_reverse($awardsPerRound, true);

if (!empty($awardsPerRound)) { ?>

<div class="row profile_row">
  <div class="col-xs-2">Round</div>

  <?php foreach ($awardTypeMap as $name => $awardType){ ?>

  <div class="col-xs-1">
    <div data-toggle="tooltip" title='<?php echo "$name" ?>'>
      <?php echo "$awardType" ?></div>
  </div>

  <?php } ?>

</div>

<?php
}

foreach ($awardsPerRound as $roundNr => $awards){ ?>
<div class="row profile_row">
  <div class="col-xs-2"><?php echo "$roundNr" ?></div>
  <?php
  foreach ($awardTypeMap as $name => $awardType){
    $color = $awards[$awardType]
  ?>
    <?php if (isset($color)) { ?>
    <div class="col-xs-1">
      <i class="fa fa-trophy fa-lg"
         style="color:<?php echo $color;?>"
         aria-hidden="true"></i>
    </div>
    <?php } else { ?>
    <div class="col-xs-1">-</div>
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
