<?php
/*
Plugin Name: Marketorders
Plugin URI:
Description:
Version: 1
Author: Kevin Bogaard
Author URI:
License: GPL
Copyright: Kevin Bogaard
 */
require_once('telegrambot.class.php');

function turn_spread($turntype, $addedturns) {
    global $userId;

    $turnSpread = maybe_unserialize(get_user_meta($userId, 'turn_spread', true));
    if (!is_array($turnSpread)) $turnSpread = array();
    if(!isset($turnSpread[$turntype])) $turnSpread[$turntype] = 0;
    $turnSpread[$turntype] += $addedturns;

    update_user_meta($userId, 'turn_spread', maybe_serialize($turnSpread));
}

/**
 * Make sure (normal) users cannot see other users' posts and events via de wp-link plugin in wp-editor
 */
function wpq_link_query( $results, $query ) {
    if($_POST['action'] == 'wp-link-ajax' && !CurrentUser::make()->isAdmin()) $results = array();
    return $results;
}

add_filter('wp_link_query', 'wpq_link_query', 10, 2);

function get_user_ip_address() {
    return Request::getIpAddress();
}

function is_banned($userId) {
    return User::make($userId)->isBanned();
}

// Before login hook
function wp_authenticate_custom($username) {
    return CurrentUser::login($username);
}
add_action('wp_authenticate', 'wp_authenticate_custom');

// After login hook, but CurrentUser is not set yet
function wp_login_custom($login) {
    return CurrentUser::loggedin($login);
}
add_action('wp_login', 'wp_login_custom');

// Redirect after login
function my_login_redirect($redirect_to, $request, $user) {
    return get_site_url() . "/dashboard/";
}
add_filter('login_redirect', 'my_login_redirect', 10, 3);


function filter_get_avatar_url($url, $userId, $args) {
    $avatar = get_user_meta($userId, 'avatar_user', true);
    return $avatar;
};
add_filter('get_avatar_url', 'filter_get_avatar_url', 10, 3);

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

add_filter('next_posts_link_attributes', 'posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'posts_link_attributes_2');

add_filter('manage_pages_columns', 'page_column_views');
add_action('manage_pages_custom_column', 'page_custom_column_views', 5, 2);
function page_column_views($defaults) {
    $defaults['page-layout'] = __('Template');
    return $defaults;
}

function page_custom_column_views($column_name, $id) {
    if ($column_name === 'page-layout') {
        $set_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if ($set_template == 'default') {
            echo 'Default';
        }
        $templates = get_page_templates();
        ksort($templates);
        foreach (array_keys($templates) as $template):
            if ($set_template == $templates[$template]) {
                echo $template;
            }
        endforeach;
    }
}

function do_thief($level, $thieves, $snipers, $defender_money) {
    //Set thief_multiplier based on number sent. The higher the multiplier, the lower the success chance but higher the money stolen

    //Sets some variables based on research level
    switch ($level) {
        case 0:
            if (!(isset($thief_multiplier))) {
                $thief_multiplier = 3.8;
            }
            $sqrtThieves = sqrt($thieves);

            //Set the number that must be higher than caughtChance in order for thieving to work
            $randMax = mt_rand(0, 100);
            //Sets the damage an individual sniper does to the thieves

            $snipersHit = ($snipers * 0.59) * (mt_rand(70, 130) / 100);
            $cashMultiplier = ((mt_rand(1, 5) * ($sqrtThieves / $thief_multiplier)) / 100);

            break;
        case 1:
            if (!(isset($thief_multiplier))) {
                $thief_multiplier = 3.8;
            }
            $sqrtThieves = sqrt($thieves);

            //Set the number that must be higher than caughtChance in order for thieving to work
            $randMax = mt_rand(10, 100);
            //Sets the damage an individual sniper does to the thieves

            $snipersHit = ($snipers * 0.59) * (mt_rand(70, 130) / 100);
            $cashMultiplier = ((mt_rand(2, 7) * ($sqrtThieves / $thief_multiplier)) / 100);
            break;
        case 2:
            if (!(isset($thief_multiplier))) {
                $thief_multiplier = 3.5;
            }
            $sqrtThieves = sqrt($thieves);

            //Set the number that must be higher than caughtChance in order for thieving to work
            $randMax = mt_rand(20, 100);
            //Sets the damage an individual sniper does to the thieves

            $snipersHit = ($snipers * 0.59) * (mt_rand(70, 130) / 100);
            $cashMultiplier = ((mt_rand(4, 9) * ($sqrtThieves / $thief_multiplier)) / 100);
            break;
        default:
            if (!(isset($thief_multiplier))) {
                $thief_multiplier = 3;
            }
            //MEGA use square root of thieves count. So 10 thieves = 3, 1 tihef = 1
            //20170531
            $sqrtThieves = sqrt($thieves);

            //Set the number that must be higher than caughtChance in order for thieving to work
            $randMax = mt_rand(40, 100);
            //Sets the damage an individual sniper does to the thieves

            $snipersHit = ($snipers * 0.59) * (mt_rand(70, 130) / 100);
            $cashMultiplier = ((mt_rand(5, 9) * ($sqrtThieves / $thief_multiplier)) / 100);

            //Old:
            // 5 * (1/3) =0.33    / 100 = 2%
            // 9 * 1/3 =          / 100 = 3%
            // 5 * 10/3 =         / 100 = 16%
            // 9 * 10/3 =         / 100 = 29% !!!

            //New:
            // 5 * (1/3) = 0.33   / 100 = 2%
            //                          =3%
            // 9 * 3.122/3 = 1    / 100 = 9%
            // 5 * 3.122/3 = 1 = 5/ 100 = 5%

            //.. with max edu. Need to test other values
    }

    if ($thieves == 1) {
        $dice = 1 + $snipersHit;
    } else {
        $dice = ($thieves * $thief_multiplier) + $snipersHit;
    }

    /* Debug stuff.. uncomment to enable
    echo "successNo:".$randMax."<br/>";
    echo "thieves:".$thieves."<br/>";
    echo "snipers:".$snipers."<br/>";
    echo "thief_multipler:".$thief_multiplier."<br/>";
    echo "snipersHit:".$snipersHit."<br/>";
    echo "dice:".$dice."<br/>";
    echo "thiefChance:".(100-$dice)."<br/>";
    echo "cashMultiplier:".$cashMultiplier."<br/>";
    */

    if ($randMax > $dice) {
        //Winner
        return $cashMultiplier;
    } else {
        //LOSER
        return 0;
    }
}

function networth_range($user_ID) {
    $user = User::make($user_ID);
    if(!$province = $user->getProvince()) return false;
    return $province->getNetworth(true);
}

function clan_avg_networth_range($clanId) {
    global $userId;
    $viewerId = $userId;
    $viewerClanId = get_post_meta($viewerId, 'clan_id_user', true);

    $clanMembers = count(get_post_meta($clanId, 'clan_members', true));
    $viewer_clan = get_post_meta($viewerClanId, 'clan_members', true);
    $decClanMembers = (!empty($viewer_clan) ? count($viewer_clan) : 1);

    $clanNetworth = get_post_meta($clanId, 'clan_networth', true) / $clanMembers;
    $decClanNetworth = get_post_meta($viewerClanId, 'clan_networth', true) / $decClanMembers;

    return '<span>$ ' . number_format($clanNetworth, 0, ',', ' ') . '</span>';
}

function clan_networth_range($clanId) {
    global $userId;
    $viewerId = $userId;
    $viewerClanId = get_user_meta($viewerId, 'clan_id_user', true);

    $clanNetworth = get_post_meta($clanId, 'clan_networth', true);
    $decClanNetworth = get_post_meta($viewerClanId, 'clan_networth', true);

    if (($decClanNetworth / 1.4 <= $clanNetworth) && ($clanNetworth <= $decClanNetworth * 1.4)) {
        return '<strong>$ ' . number_format($clanNetworth, 0, ',', ' ') . ' <span class="hover-tip"  data-toggle="tooltip" data-original-title="This clan is in your networth range" data-placement="bottom"><i class="fas fa-check-circle"></i></span></strong>';
    } else {
        return '<span>$ ' . number_format($clanNetworth, 0, ',', ' ') . '</span>';
    }
}

function get_user_name($user_ID) {
    $prv = Province::make($user_ID);
    if($prv->get('id')) return $prv->getLink(true);
    return false;
}

function plural_func($number) {
    if ($number == 0 || $number > 1) {
        return 's';
    }
}

function small_avatar($user_ID, $type) {
    return User::make($user_ID)->getAvatar($type);
}

function clan_avatar($clan_ID, $type) {
    return Clan::make($clan_ID)->getAvatar($type);
}

function wpse_76815_remove_publish_box() {
    remove_meta_box('submitdiv', 'clan', 'side');
}
add_action('admin_menu', 'wpse_76815_remove_publish_box');

function wpse66094_no_admin_access() {
    if(defined('DOING_AJAX')) return;
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url('/');
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    if ($user_role === 'subscriber') {
        exit(wp_redirect($redirect));
    }
}
add_action('admin_init', 'wpse66094_no_admin_access', 100);

function create_post_type() {
    register_post_type('market_order', array(
        'labels' => array(
            'name' => __('Orders'),
            'singular_name' => __('Order'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'author', 'excerpt'),
    ));
    register_post_type('event_local', array(
        'labels' => array(
            'name' => __('Events'),
            'singular_name' => __('Event'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'rest_base' => 'event_local',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'supports' => array('title', 'editor', 'author', 'excerpt'),
    ));
    register_post_type('clan', array(
        'labels' => array(
            'name' => __('Clans'),
            'singular_name' => __('Clan'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('user_message', array(
        'labels' => array(
            'name' => __('Messages'),
            'singular_name' => __('Message'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('wars', array(
        'labels' => array(
            'name' => __('Wars'),
            'singular_name' => __('War'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('deposit', array(
        'labels' => array(
            'name' => __('Deposits'),
            'singular_name' => __('Deposit'),
        ),
        'public' => true,
        'show_ui' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'author'),
    ));
    register_post_type('research', array(
        'labels' => array(
            'name' => __('Researches'),
            'singular_name' => __('Research'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'author', 'excerpt'),
    ));
    register_post_type('sat', array(
        'labels' => array(
            'name' => __('Satellites'),
            'singular_name' => __('Satellite'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('spy_rep', array(
        'labels' => array(
            'name' => __('Spy report'),
            'singular_name' => __('Spy report'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('award', array(
        'labels' => array(
            'name' => __('Clan award'),
            'singular_name' => __('Clan award'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('medal', array(
        'labels' => array(
            'name' => __('Medal'),
            'singular_name' => __('Medal'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
    register_post_type('emp', array(
        'labels' => array(
            'name' => __('EMP'),
            'singular_name' => __('EMP'),
        ),
        'public' => true,
        'has_archive' => false,
    ));
}

add_action('init', 'create_post_type');

// Hook immediately after user is added to the database
add_action('user_register', 'user_register_custom', 10, 1);
function user_register_custom($user_id) {
    return CurrentUser::make($user_id)->register();
}

function after_death($user_id) {
    if(empty($user_id)) return false;
    return Province::make($user_id)->afterDeath();
}

function count_all_stats($user_id) {
    if(empty($user_id)) return false;
    return Province::make($user_id)->count_all_stats();
}

/* Extra columns in user backend */
function new_modify_user_table($column) {
    $column['networth'] = 'Networth';
    $column['land'] = 'Land';
    $column['playername'] = 'Playername';
    $column['lastseen'] = 'Last seen';
    $column['clan'] = 'Clan';
    return $column;
}
add_filter('manage_users_columns', 'new_modify_user_table');

function new_modify_user_table_row($val, $column_name, $user_id) {
    $member_data = get_userdata($user_id);
    $userData = get_user_meta($user_id);
    $lastseen = date('G:i:s | d-m-Y', $userData['last_online'][0]);
    $clan_id = $userData['clan_id_user'][0];

    switch ($column_name) {
        case 'networth':
            return '$ ' . number_format($userData['networth'][0], 0, ',', ' ');
            break;
        case 'land':
            return number_format($userData['land'][0], 0, ',', ' ') . ' m<sup>2</sup>';
            break;
        case 'playername':
            return '<a target="_blank" href="/users/profile/?id=' . $user_id . '">' . $member_data->display_name . ' (#' . $user_id . ')</a>';
            break;
        case 'lastseen':
            return $lastseen;
            break;
        case 'clan':
            if ($clan_id == 0) {
                return 'none';
            } else {
                return '<a target="_blank" href="' . get_the_permalink($clan_id) . '">' . get_the_title($clan_id) . ' (#' . $clan_id . ')</a>';
            }
            break;
        default:
    }
    return $val;
}
add_filter('manage_users_custom_column', 'new_modify_user_table_row', 10, 3);

/* extra medal columns */
add_filter('manage_medal_posts_columns', 'set_custom_edit_medal_columns');
add_action('manage_medal_posts_custom_column', 'custom_medal_column', 10, 2);

function set_custom_edit_medal_columns($columns) {
    $columns['winner'] = 'Winner';
    $columns['round'] = 'Round';
    return $columns;
}

function custom_medal_column($column, $post_id){
    $user_ID = get_post_meta($post_id, 'winning_user', true);
    $round = get_post_meta($post_id, 'medal_round', true);
    $member_data = get_userdata($user_ID);
    $userName = $member_data->display_name;
    switch ($column) {
        case 'winner':
            echo $userName . ' (#' . $user_ID . ')';
            break;
        case 'round':
            echo $round;
            break;
    }
}

/* extra award columns */
add_filter('manage_award_posts_columns', 'set_custom_edit_award_columns');
add_action('manage_award_posts_custom_column', 'custom_award_column', 10, 2);

function set_custom_edit_award_columns($columns){
    $columns['winner'] = 'Winner';
    $columns['position'] = 'Position';
    $columns['round'] = 'Round';
    return $columns;
}

function custom_award_column($column, $post_id) {
    $clanID = get_post_meta($post_id, 'winning_clan', true);
    $round = get_post_meta($post_id, 'round', true);
    $position = get_post_meta($post_id, 'position_clan', true);

    switch ($column) {
        case 'winner':
            echo get_the_title($clanID) . ' (#' . $clanID . ')';
            break;
        case 'position':
            echo $position;
            break;
        case 'round':
            echo $round;
            break;
    }
}

add_shortcode('buildings-manual', 'display_all_buildings');
function display_all_buildings() {
    $buildings = Buildings::get();
    $allBDS = '<div class="row">';
    foreach ($buildings as $building) {
        $name = $building['normalname'];
        $desc = $building['description'];
        $attacks = $building['attacks'];
        $power = $building['power'];
        $price = $building['price'];
        $powerProd = $building['powerprod'];
        $allBDS .= "<div class='col-md-6 querymanualbds'><strong>$name</strong><br/><i>$desc</i><br/><br/>Power usage: $power<br/>Power production: $powerProd<br/>Price: $$price<br/><br/></div>";
    }
    $allBDS .= '</div>';
    return $allBDS;
}

add_shortcode('units-manual', 'display_all_units');

function display_all_units() {
    $units = Units::get();
    $allunits = '<div class="row">';
    foreach ($units as $unit) {
        $name = $unit['normalname'];
        $desc = $unit['description'];
        $attack = $unit['attack'];
        $life = $unit['life'];
        if (isset($desc)) {
            $desc = $unit['description'];
            $desc = "<i>$desc</i><br/><br/>";
        } else {
            $desc = '';
        }
        $attacks = implode(", ", $unit['attacks']);

        $price = $unit['price'];
        $type = $unit['type'];
        $allunits .= "<div class='col-md-6 querymanualunits'><strong>$name</strong><br/>$desc Price: $$price<br/>Attacks: $attacks<br/>Attack: $attack / Life: $life<br/>Type: $type</div>";
    }
    $allunits .= '</div>';
    return $allunits;
}

function fcm_send_notification($receiver, $type, $attacker=0) {

    $attacker_data = get_userdata($attacker);
    if(is_object($attacker_data)) $attacker_name = $attacker_data->display_name;
    $receiver_data = get_userdata($receiver);
    if(!$receiver_data || !is_object($receiver_data)) return;
    $receiver_name = $receiver_data->display_name;

    if ($type == 'regular' || $type == 'ground' || $type == 'air_sea') {
        $avatar = get_user_meta($attacker, 'avatar_user', true);
        $body = 'You were attacked by ' . $attacker_name . ' (#' . $attacker . ')';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'spy') {
        $avatar = get_site_url() . '/unknown.png';
        $body = 'Someone spied your base';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'thief') {
        $avatar = get_site_url() . '/unknown.png';
        $body = 'Someone sent a thief';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'sniper') {
        $avatar = get_site_url() . '/unknown.png';
        $body = 'Someone sent a sniper';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'missile') {
        $avatar = get_user_meta($attacker, 'avatar_user', true);
        $body = $attacker_name . ' (#' . $attacker . ') launched a missile at your base';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'satellite') {
        $avatar = get_user_meta($attacker, 'avatar_user', true);
        $body = $attacker_name . ' (#' . $attacker . ') fired a satellite at your base';
        $url = get_site_url() . '/events/incoming/';
    }

    if ($type == 'research') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Research completed';
        $url = get_site_url() . '/research/';
    }

    if ($type == 'message') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'New message received from '. $attacker_name .' (#' . $attacker . ')';
        $url = get_site_url() . '/conversations/';
    }

    // -> /declare_war.php
    if ($type == 'wardeclared') { // $attacker is a clan-member, but we want to show the clan info
        $declarer_clan_ID = get_user_meta($attacker, 'clan_id_user', true);
        if(!empty($declarer_clan_ID)) {
            $tag = get_post_meta($declarer_clan_ID, 'clan_tag', true);
            $avatar = get_user_meta($receiver, 'avatar_user', true);
            $body = get_the_title($declarer_clan_ID) .' ('.$tag.') declared war on you';
            $url = get_site_url() . '/clan-wars/';
        }
    }

    // Nukeprotection removed -> /marketordercheck.php (cron)
    if ($type == 'nukeprotectremoved') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Your assault protection has been removed';
        $url = get_site_url() . '/dashboard/';
    }

    // Market Order received
    if ($type == 'orderarrived') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Your order has arrived';
        $url = get_site_url() . '/dashboard/';
    }

    // Max money? -> /add_money.php (cron)

    // Sattelite crashed -> /marketordercheck.php (cron)
    if ($type == 'satcrash') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Your satellite crashed and burned up in the atmosphere';
        $url = get_site_url() . '/satellites/';
    }

    // Power -> /marketordercheck.php (cron)
    $lp_notified = get_user_meta($receiver, 'low_power_notified', true);
    if ($type == 'lowpower'  && (empty($lp_notified) || $lp_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Your power is currently offline. Restore your power as soon as possible';
        $url = get_site_url() . '/buildings/';
        update_user_meta($receiver, 'low_power_notified', 'yes');
    }

    // Power -> /marketordercheck.php (cron)
    $hp_notified = get_user_meta($receiver, 'high_power_notified', true);
    if ($type == 'highpower' && (empty($hp_notified) || $hp_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Your power is currently high which makes you an easy target';
        $url = get_site_url() . '/buildings/';
        update_user_meta($receiver, 'high_power_notified', 'yes');
    }

    // Buildings -> /marketordercheck.php (cron)
    $lb_notified = get_user_meta($receiver, 'low_buildings_notified', true);
    if ($type == 'buildings' && (empty($lb_notified) || $lb_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        if(date('d-m-Y', Round::startDate()) == date('d-m-Y')) $body = 'A new round has begun';
        else $body = 'You have less then 50 buildings. Rebuild as soon as possible';
        $url = get_site_url() . '/buildings/';
        update_user_meta($receiver, 'low_buildings_notified', 'yes');
    }

    // Max turns -> /add_turns.php (cron)
    $mt_notified = get_user_meta($receiver, 'max_turns_notified', true);
    if ($type == 'maxturns' && (empty($mt_notified) || $mt_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Max turns reached, spend some on building, exploring or attacking';
        $url = get_site_url() . '/buildings/';
        update_user_meta($receiver, 'max_turns_notified', 'yes');
    }

    // Max morale -> /add_morale.php (cron)
    $mm_notified = get_user_meta($receiver, 'max_morale_notified', true);
    if ($type == 'maxmorale' && (empty($mm_notified) || $mm_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Max morale reached, go attack someone';
        $url = get_site_url() . '/users/?tab=in-range';
        update_user_meta($receiver, 'max_morale_notified', 'yes');
    }

    // Max sat morale -> /add_morale.php (cron)
    $msm_notified = get_user_meta($receiver, 'max_satmorale_notified', true);
    $sat_owned = get_user_meta($receiver, 'sat_owned', true);
    if ($type == 'maxsatmorale' && !empty($sat_owned) && (empty($msm_notified) || $msm_notified == 'no')) {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'Max satellite morale reached, go fire that thing';
        $url = get_site_url() . '/satellites/';
        update_user_meta($receiver, 'max_satmorale_notified', 'yes');
    }

    if(!isset($body) || empty($body)) return;

    // No notifications to others on Dev!
    $gameType = get_field('game_type','option');
    if(in_array($gameType, array('Development','Test'))) {
        if($receiver != 2768) return;
        //wtf('<a href="'.$url.'">'.$body.'</a>');
    }

    $registrationIds = maybe_unserialize(get_user_meta($receiver, 'device_tokens', true));
    if(!empty($registrationIds)) {
        $serverKey = 'AAAAtMYygfc:APA91bEMDKTi556dx98bDJRF0KoG4IiG6L5xfiYvxOcRDL2yFWKhvnEwpqS-JHbLkUTdpmNqbQT0nn7mAt0B4ftxBQ6-zrI_yM_cWzwjLoTH-t51aCILfKbG_l6BcltB3MkGx6Yh9XBW';
        $sendurl = 'https://fcm.googleapis.com/fcm/send';

        $message = array(
            'title' => 'Assault.Online',
            'body' => $body,
            'click_action' => $url,
            "icon" => $avatar,
            'vibrate' => 1,
            'sound' => 1,
        );
        $fields = array(
            'registration_ids' => $registrationIds,
            'notification' => $message,
        );
        $headers = array(
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sendurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    $bot = new TelegramBot();
    if($bot->getChatByUserId($receiver)) {
        $bot->sendMessage('<a href="'.$url.'">'.$body.'</a>', array('parse_mode' => 'html'));
    }
}
