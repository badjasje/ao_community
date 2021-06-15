<?php
require_once("wp-load.php");

$winnerArray = array();

$args = array('post_type' =>  'clan', 'posts_per_page' => -1);
$clans = get_posts($args);
foreach ($clans as $clan) {
    $clan_members = get_post_meta($clan->ID, 'clan_members');
    $tot_land = 0;
    $tot_attacks = 0;
    foreach ($clan_members[0] as $member) {
        $land = get_user_meta($member, 'land', true);
        $attacks = get_user_meta($member, 'succesful_attacks', true);
        $tot_land+=$land;
        $tot_attacks+=$attacks;
    }
    update_post_meta($clan->ID, 'ub_total', $tot_land);
    update_post_meta($clan->ID, 'ua_total', $tot_attacks);
}


$args = array(
    'meta_key'     => 'ub_total',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
);
$count = 0;
$clans = get_posts($args);
?>
<strong>United Boundaries</strong>
<table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $land = get_post_meta($clan_ID, 'ub_total', true);
        $count++;
        if($count < 4){
        	$winnerArray['United Boundaries'][$count] = array($clan_ID, 'Land: '.number_format($land, 0, ',', ' ').'m2');
        }
        ?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)</td>
            <td><?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup></td>
        </tr>
    <?php } ?>
</table>
<br/>
<?php

$args = array(
    'meta_key'     => 'ua_total',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
);
$clans = get_posts($args);
$count = 0;
?>
<strong>United Arms</strong>
<table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $attacks = get_post_meta($clan_ID, 'ua_total', true);
        $count++;
        if($count < 4){
        	$winnerArray['United Arms'][$count] = array($clan_ID, 'Attacks: '.number_format($attacks, 0, ',', ' '));
        }
        ?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)</td>
            <td><?php echo number_format($attacks, 0, ',', ' '); ?> attacks</td>
        </tr>
    <?php } ?>
</table>
<br/>
<?php

$args = array(
    'meta_key'     => 'clan_networth',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
);
$clans = get_posts($args);
$count = 0;
?>
<strong>Networth</strong>
<table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $networth = get_post_meta($clan_ID, 'clan_networth', true);
        $count++;
        if($count < 4){
        	$winnerArray['Networth Champion'][$count] = array($clan_ID,'Networth: $ '.number_format($networth, 0, ',', ' '));
        }
        ?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)</td>
            <td>$ <?php echo number_format($networth, 0, ',', ' '); ?></td>
        </tr>
    <?php } ?>
</table>
<br/>
<?php

$args = array(
    'meta_key'     => 'clan_points',
    'posts_per_page'   => 10,
    'offset'           => 0,
    'post_type'     => 'clan',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
);
$clans = get_posts($args);
$count = 0;
?>
<strong>Clan Points</strong>
<table>
    <?php foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $points = get_post_meta($clan_ID, 'clan_points', true);
        $count++;
        if($count < 4){
        	$winnerArray['Points Champion'][$count] = array($clan_ID, 'Points: $ '.number_format($points, 0, ',', ' '));
        }
        ?>
        <tr>
            <td><?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)</td>
            <td><?php echo number_format($points, 0, ',', ' '); ?>pts</td>
        </tr>
    <?php } ?>
</table>
<?php

if($_GET['add'] == 1) {
    foreach ($winnerArray as $key => $winners) {
        foreach ($winners as $position => $winner) {
            if($position == 1) {
                $position = 'Gold';
            }
            if($position == 2) {
                $position = 'Silver';
            }
            if($position == 3) {
                $position = 'Bronze';
            }
            $args = [
                'post_title' => $key,
                'post_status' => 'publish',
                'post_type' => 'award',
                'post_author' => 1
            ];
            $newAwardId = wp_insert_post($args);
            update_field('round', 'Beta round ' . Round::getRoundNr(), $newAwardId);
            update_field('winning_clan', $winner[0], $newAwardId);
            update_field('position_clan', $position, $newAwardId);
        }
    }
}

