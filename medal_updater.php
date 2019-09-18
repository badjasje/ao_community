<?php
require_once("wp-load.php");
if (get_field('game_status', 'option') != 'Live') { exit;}

$extra_args = array();
$gameType = get_field('game_type','option');
if(in_array($gameType, array('Development'))) { // Just me on dev.
    $extra_args = array('include' => array(2,2768));
}

$args = array_merge($extra_args, array('meta_key' => 'land', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $land = get_user_meta($user_ID, 'land', true);
    if($next_ID) $next_land = get_user_meta($next_ID, 'land', true);
    if($prev_ID) $prev_land = get_user_meta($prev_ID, 'land', true);

    update_user_meta($user_ID, 'moe_position', $position);
    update_user_meta($user_ID, 'moe_prev', round($land-$prev_land));

    if ($position == 1) {
        update_user_meta($user_ID, 'moe_next', 0);
    } else {
        update_user_meta($user_ID, 'moe_next', round($next_land-$land));
    }
}


$args = array_merge($extra_args, array('meta_key' => 'user_clan_points', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $points = get_user_meta($user_ID, 'user_clan_points', true);
    if($next_ID) $next_points = get_user_meta($next_ID, 'user_clan_points', true);
    if($prev_ID) $prev_points = get_user_meta($prev_ID, 'user_clan_points', true);

    update_user_meta($user_ID, 'moh_position', $position);
    update_user_meta($user_ID, 'moh_prev', $points-$prev_points);

    if ($position == 1) {
        update_user_meta($user_ID, 'moh_next', 0);
    } else {
        update_user_meta($user_ID, 'moh_next', $next_points-$points);
    }
}


$args = array_merge($extra_args, array('meta_key' => 'networth', 'orderby' => 'meta_value_num', 'order'=> 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $networth = get_user_meta($user_ID, 'networth', true);
    if($next_ID) $next_networth = get_user_meta($next_ID, 'networth', true);
    if($prev_ID) $prev_networth = get_user_meta($prev_ID, 'networth', true);

    update_user_meta($user_ID, 'mog_position', $position);
    update_user_meta($user_ID, 'mog_prev', $networth-$prev_networth);

    if ($position == 1) {
        update_user_meta($user_ID, 'mog_next', 0);
    } else {
        update_user_meta($user_ID, 'mog_next', $next_networth-$networth);
    }
}


$args = array_merge($extra_args, array('meta_key' => 'in_war_attacks', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $attacks = get_user_meta($user_ID, 'in_war_attacks', true);
    if($next_ID) $next_attacks = get_user_meta($next_ID, 'in_war_attacks', true);
    if($prev_ID) $prev_attacks = get_user_meta($prev_ID, 'in_war_attacks', true);

    update_user_meta($user_ID, 'moc_position', $position);
    update_user_meta($user_ID, 'moc_prev', $attacks-$prev_attacks);

    if ($position == 1) {
        update_user_meta($user_ID, 'moc_next', 0);
    } else {
        update_user_meta($user_ID, 'moc_next', $next_attacks-$attacks);
    }
}


$args = array_merge($extra_args, array('meta_key' => 'kills_made', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $kills = get_user_meta($user_ID, 'kills_made', true);
    if($next_ID) $next_kills = get_user_meta($next_ID, 'kills_made', true);
    if($prev_ID) $prev_kills = get_user_meta($prev_ID, 'kills_made', true);

    update_user_meta($user_ID, 'mod_position', $position);
    update_user_meta($user_ID, 'mod_prev', $kills-$prev_kills);

    if ($position == 1) {
        update_user_meta($user_ID, 'mod_next', 0);
    } else {
        update_user_meta($user_ID, 'mod_next', $next_kills-$kills);
    }
}


$args = array_merge($extra_args, array('meta_key' => 'money_gained_thieving', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $thiefed = get_user_meta($user_ID, 'money_gained_thieving', true);
    if($next_ID) $next_thiefed = get_user_meta($next_ID, 'money_gained_thieving', true);
    if($prev_ID) $prev_thiefed = get_user_meta($prev_ID, 'money_gained_thieving', true);

    update_user_meta($user_ID, 'mot_position', $position);
    update_user_meta($user_ID, 'mot_prev', $thiefed-$prev_thiefed);

    if ($position == 1) {
        update_user_meta($user_ID, 'mot_next', 0);
    } else {
        update_user_meta($user_ID, 'mot_next', $next_thiefed-$thiefed);
    }
}


$args = array_merge($extra_args, array('meta_key' => 'nw_damage_missiles', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;

    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $damage = get_user_meta($user_ID, 'nw_damage_missiles', true);
    $prev_damage = $next_damage = 0;
    if($next_ID) $next_damage = get_user_meta($next_ID, 'nw_damage_missiles', true);
    if($prev_ID) $prev_damage = get_user_meta($prev_ID, 'nw_damage_missiles', true);

    update_user_meta($user_ID, 'modes_position', $position);
    update_user_meta($user_ID, 'modes_prev', $damage-$prev_damage);

    if ($position == 1) {
        update_user_meta($user_ID, 'modes_next', 0);
    } else {
        update_user_meta($user_ID, 'modes_next', $next_damage-$damage);
    }
}

$args = array(
    'meta_key'      => 'nw_damage_defender',
    'posts_per_page'=> 300,
    'post_type'     => 'event_local',
    'orderby'       => 'meta_value_num',
    'order'         => 'DESC',
);
$attacks = get_posts($args);
$position = 0;
$users = array();
$count = 0;
foreach ($attacks as $attack) {
    $user_ID = $attack->post_author;
    if (!in_array($user_ID, $users)) {
        $count++;
        $position += 1;
        $war_status = get_post_meta($attack->ID, 'war_status', true);
        if(in_array($war_status, array('incoming','mutual','outgoing'))) {
            $damage = get_post_meta($attack->ID, 'nw_damage_defender', true);
            wtf($war_status, $user_ID, $position);
            update_user_meta($user_ID, 'modev_position', $position);
            update_user_meta($user_ID, 'modev_damage', $damage);
        }
        $users[] = $user_ID;
        if ($count == 200) {
            die;
        }
    }
}

/**
 *  Recruitment:
 * - User has referral_userid set
 * - User has logged in
 * - User has chosen a starting bonus
 * - User is not a multi (based on ip, agent, continent, email similarity, etc)
 */
$referrals = array();
$user_referrees = $wpdb->get_results("SELECT `umeta_id`, `user_id`, `meta_value` AS `from_id`
    FROM ${table_prefix}usermeta WHERE `meta_key` = 'referral_userid'", ARRAY_A);
foreach($user_referrees as $ref) {
    $score = 0;
    $code = array();

    if(is_banned($ref['user_id'])) { $score += 20; $code[] = 'USER_BANNED'; }
    if(is_banned($ref['from_id'])) { $score += 20; $code[] = 'REFERRER_BANNED'; }

    $user_logindata = get_user_meta( $ref['user_id'], 'logindata', true);
    if(empty($user_logindata)) { $score += 20; $code[] = 'USER_NEVER_LOGIN'; }

    $from_logindata = get_user_meta( $ref['from_id'], 'logindata', true);
    if(empty($from_logindata)) { $score += 20; $code[] = 'REFERRER_NEVER_LOGIN'; }

    $user_data = get_userdata($ref['user_id']);
    $user_domain = explode('.',explode('@',$user_data->user_email)[1])[0];
    $from_data = get_userdata($ref['from_id']);
    $from_domain = explode('.',explode('@',$from_data->user_email)[1])[0];
    if(!in_array($user_domain,array('hotmail','gmail','live','aol','yahoo'))) {
        if($user_domain == $from_domain) { $score += 10; $code[] = 'EMAILS_SAME_DOMAIN'; }
    }

    similar_text($user_data->user_email, $from_data->user_email, $perc);
    if($perc > 68) { $score += 5; $code[] = 'EMAILS_SIMILAR'; }

    if(!empty($from_logindata) && !empty($user_logindata)) {
        if(count(array_intersect(array_keys($user_logindata), array_keys($from_logindata))) > 0) {
            $score += 5; $code[] = 'MATCHING_IPS';
        }

        $user_agents = array();
        $user_notwestern = 0;
        foreach($user_logindata as $ip => $data) {
            $user_agents[] = $data[1];
            $user_geo = json_decode($data[3],true);
            if(isset($user_geo['data']['geo']) && !in_array($user_geo['data']['geo']['continent_code'],array('EU','US'))) {
                $user_notwestern += 1;
            }
        }
        if( ($user_notwestern*100)/count($user_logindata) > 49 ) { $score += 5; $code[] = 'OFTEN_OUTSIDE_US'; }

        $from_agents = array();
        foreach($from_logindata as $ip => $data) $from_agents[] = $data[1];
        if(count(array_intersect($user_agents, $from_agents)) > 0) { $score += 5; $code[] = 'MATCHING_AGENTS'; }
    }

    $startingbonus = get_user_meta( $ref['user_id'], 'starting_bonus', true);
    if(empty($startingbonus)) { $score += 10; $code[] = 'USER_NOT_STARTED'; }

    //wtf($user_data->user_email, $score, $code);
    //echo '<hr>';

    update_user_meta($ref['user_id'], 'referral_score', $score);
    update_user_meta($ref['user_id'], 'referral_code', $code);

    if($score < 10) {
        if(!isset($referrals[$ref['from_id']])) $referrals[$ref['from_id']]=0;
        $referrals[$ref['from_id']]++; // Going for that recruitment level!
    }
}

// Update referal users
foreach($referrals as $user_ID => $num) {
    update_user_meta($user_ID, 'referral_num', $num);
}

// Medal stuff
$args = array_merge($extra_args, array('meta_key' => 'referral_num', 'orderby' => 'meta_value_num', 'order' => 'DESC'));
$users = get_users($args);
$position = 0;
foreach ($users as $key => $user) {
    $position += 1;
    $user_ID = $user->ID;
    $next_ID = (isset($users[$key-1]) ? $users[$key-1]->ID : false);
    $prev_ID = (isset($users[$key+1]) ? $users[$key+1]->ID : false);

    $damage = get_user_meta($user_ID, 'referral_num', true);
    if($next_ID) $next_damage = get_user_meta($next_ID, 'referral_num', true);
    if($prev_ID) $prev_damge = get_user_meta($prev_ID, 'referral_num', true);

    update_user_meta($user_ID, 'mor_position', $position);
    update_user_meta($user_ID, 'mor_prev', (!empty($damage)?$damage:0)-(!empty($prev_damge)?$prev_damge:0));

    if ($position == 1) {
        update_user_meta($user_ID, 'mor_next', 0);
    } else {
        update_user_meta($user_ID, 'mor_next', (!empty($next_damage)?$next_damage:0)-(!empty($damage)?$damage:0));
    }
}
/* */

/* New but broken
    require_once("wp-load.php");
if (get_field('game_status', 'option') != 'Live') { exit; }

function update_medal($medalType,$toQuery){
	global $wpdb;
	$users = $wpdb->get_results("
		SELECT * FROM `23zx_usermeta` WHERE `meta_key` = '$toQuery' ORDER BY `23zx_usermeta`.`meta_value` DESC
	");

    $position = 0;
    foreach ($users as $key => $user) {
        $position += 1;
        $user_ID = $user->user_id;

        $userValue = $user->meta_value;
        $valueAbove = $users[$key-1]->meta_value;
        $valueBelow = $users[$key+1]->meta_value;

        $medalPos = $medalType.'_position';
        $next = $medalType.'_next';
        $prev = $medalType.'_prev';

        $wpdb->query("
	        UPDATE `23zx_usermeta`
	        SET `meta_value` = $position
	        WHERE `23zx_usermeta`.`meta_key` = '$medalPos'
	        AND `23zx_usermeta`.`user_id` = $user_ID;
        ");

        $prevValue = round($userValue-$valueBelow);
        $wpdb->query("
	        UPDATE `23zx_usermeta`
	        SET `meta_value` = $prevValue
	        WHERE `23zx_usermeta`.`meta_key` = '$prev'
	        AND `23zx_usermeta`.`user_id` = $user_ID;
        ");

        if ($position == 1) {
             $wpdb->query("
		        UPDATE `23zx_usermeta`
		        SET `meta_value` = 0
		        WHERE `23zx_usermeta`.`meta_key` = '$next'
		        AND `23zx_usermeta`.`user_id` = $user_ID;
			");
        } else {
	        $nextValue = round($valueAbove-$userValue);
	        $wpdb->query("
		        UPDATE `23zx_usermeta`
		        SET `meta_value` = $nextValue
		        WHERE `23zx_usermeta`.`meta_key` = '$next'
		        AND `23zx_usermeta`.`user_id` = $user_ID;
			");
        }
    }
}

update_medal('moe','land');
update_medal('moh','user_clan_points');
update_medal('mog','networth');
update_medal('moc','in_war_attacks');
update_medal('mod','kills_made');
update_medal('mot','money_gained_thieving');
update_medal('modes','nw_damage_missiles');


$args = array(
    'meta_key'     => 'nw_damage_defender',
    'posts_per_page'    => 300,
    'post_type'     => 'event_local',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
    'meta_query'    => array(
        'relation'      => 'OR',
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'incoming',
        ),
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'mutual',
        ),
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'outgoing',
        )
    ),
);
$attacks = get_posts($args);
$position = 0;
$users = array();
$count = 0;
foreach ($attacks as $attack) {
    $user_ID = $attack->post_author;
    if (!in_array($user_ID, $users)) {
        $count++;
        $position += 1;
        $member_data = get_userdata($user_ID);
        $damage = get_post_meta($attack->ID, 'nw_damage_defender', true);
        update_user_meta($user_ID, 'modev_position', $position);
        update_user_meta($user_ID, 'modev_damage', $damage);

        $users[] = $user_ID;
        if ($count == 200) {
            die;
        }
    }
}
*/
