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

function get_user_ip_address() {
    return Request::getIpAddress();
}

function is_banned($userId) {
    $status = get_user_meta($userId, 'status', true);
    if($status == 'banned') return true;
    return false;
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

/*
function my_login_logo() { ?>
<style type="text/css">
#login h1 a, .login h1 a {
background-image: url("/wp-content/uploads/2016/03/AO_logo.png");
height:30px;
width:320px;
background-size: 320px;
background-repeat: no-repeat;
}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
 */
function unit_types($user_ID) {
    $units = Units::get();
    $userData = get_user_meta($user_ID);
    $type_array = array();
    foreach ($units as $key => $unit) {
        if(!isset($type_array[$unit['type']])) $type_array[$unit['type']] = 0;
        $units = $userData[$key . '_owned'][0];
        if ($units > 0 && $unit['sectype'] != 'special') {
            $type_array[$unit['type']] += $units;
        }

    }
    return $type_array;
}

function can_attack($user_ID) {
    $units = Units::get();
    $userData = get_user_meta($user_ID);
    $attack_array = array();
    foreach ($units as $key => $unit) {
        $units = $userData[$key . '_owned'][0];
        if ($units > 0) {
            $attacks = $unit['attacks'];
            $attack_array = array_merge($attack_array, $attacks);
        }

    }
    $attack_array = array_unique($attack_array);
    return $attack_array;
}

function networth_range($user_ID) {
    global $userId;
    global $userData;
    $viewerID = $userId;
    $userStatus = get_user_meta($user_ID, 'status', true);
    $networth = get_user_meta($user_ID, 'networth', true);
    $viewerNetworth = $userData['networth'][0];
    if ($userStatus == 'dead') {
        $networth = 0;
    }

    if (($networth > $viewerNetworth / 1.4 && $networth < $viewerNetworth * 1.4)) {
        return '<strong>$ ' . number_format($networth, 0, ',', ' ') . ' <span class="hover-tip"  data-toggle="tooltip" data-original-title="This user is in your networth range" data-placement="bottom"><i class="far fa-check-circle"></i></span></strong>';
    } else {
        return '<span>$ ' . number_format($networth, 0, ',', ' ') . '</span>';
    }
    /*
    if(($viewerNetworth/1.4 <= $networth) && ($networth <= $viewerNetworth*1.4)){
    return '<strong>$ '.number_format($networth, 0, ',', ' ').' <span class="hover-tip"  data-toggle="tooltip" data-original-title="This user is in your networth range" data-placement="bottom"><i class="far fa-check-circle"></i></span></strong>';
    }else{
    return '<span>$ '.number_format($networth, 0, ',', ' ').'</span>';
    }            */
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

function get_spy_units($user_ID) {
    $userData = get_user_meta($user_ID);
    $spiesOwned = array();
	$units = Units::get();
	foreach ($units as $unitKey => $unit) {
		if(in_array($unitKey,array('spy','spyplane'))) {
			$unitsOwned = $userData[$unitKey.'_owned'][0];
			if($unitsOwned > 0) {
				$spiesOwned[$unitKey] = $unit['normalname'];
			}
		}
    }
    return $spiesOwned;
}

function get_user_name($user_ID) {
    $userData = get_user_meta($user_ID);
    $timestamp = current_time('timestamp');
    $status = $userData['status'][0];
    $last_online = $userData['last_online'][0];
    $member_data = get_userdata($user_ID);
    $displayName = $member_data->display_name;

    if (!empty($last_online)) {
        $last_seen = $timestamp - $last_online;
    }

    $onlineStar = '';
    if ($last_seen < 7200 && !empty($last_online)) {
        $onlineStar = '<span style="color:#ff0000">*</span>';
    }
    $extraStyle = '';
    $banned = '';
    $icon = '';
    if ($status == 'dead' || $status == 'banned') {
        //$extraStyle = 'style="color:#ff0000"';
        $icon = '<span class="hover-tip"  data-toggle="tooltip" data-original-title="This user is dead" data-placement="bottom"><i class="fas fa-skull"></i></span>';
    }
    if ($status == 'banned') {
        $banned = '<strong>banned</strong>';
    }
    if ($status == 'nukeprotection') {
        //$extraStyle = 'style="color:#009eff"';
        $icon = '<span class="hover-tip"  data-toggle="tooltip" data-original-title="This user is under protection" data-placement="bottom"><i class="fas fa-umbrella"></i></span>';
    }
    if ($status == 'banned') {
        return "<strike><a class='memberField' $extraStyle href='/users/profile/?id=$user_ID'>$displayName (#$user_ID)</a></strike> $onlineStar $banned";
    } else {
        return "<a class='memberField' $extraStyle href='/users/profile/?id=$user_ID'>$displayName (#$user_ID) $icon </a> $onlineStar $banned";
    }
}

function plural_func($number) {
    if ($number == 0 || $number > 1) {
        return 's';
    }
}

function small_avatar($user_ID, $type) {
    $addClass = '';
    if (!empty($type)) {
        $addClass = $type;
    }
    if ($user_ID != 0) {
        $avatar = get_user_meta($user_ID, 'avatar_user', true);
        if (!empty($avatar)) {
            $avatar = str_replace("http://", "//", $avatar);
            return "<a href='/users/profile/?id=$user_ID'><div class='setAvatar clan_avatar $addClass' style='background: url(" . $avatar . ");'></div></a>";
        } else {
            $member_data = get_userdata($user_ID);
            $userName = $member_data->display_name;
            $frstLetter = strtoupper(substr($userName, 0, 1));
            $color = '#2D434E'; // Basic color
            if (in_array($frstLetter, array('A'))) {
                $color = '#2D434E';
            }
            if (in_array($frstLetter, array('B'))) {
                $color = '#607782';
            }
            if (in_array($frstLetter, array('C'))) {
                $color = '#425D69';
            }
            if (in_array($frstLetter, array('D'))) {
                $color = '#1B3642';
            }
            if (in_array($frstLetter, array('E'))) {
                $color = '#0D2632';
            }
            if (in_array($frstLetter, array('F'))) {
                $color = '#343855';
            }
            if (in_array($frstLetter, array('G'))) {
                $color = '#6C708E';
            }
            if (in_array($frstLetter, array('H'))) {
                $color = '#4C5173';
            }
            if (in_array($frstLetter, array('I'))) {
                $color = '#212648';
            }
            if (in_array($frstLetter, array('J'))) {
                $color = '#121636';
            }
            if (in_array($frstLetter, array('K'))) {
                $color = '#315842';
            }
            if (in_array($frstLetter, array('L'))) {
                $color = '#6A937C';
            }
            if (in_array($frstLetter, array('M'))) {
                $color = '#49775D';
            }
            if (in_array($frstLetter, array('N'))) {
                $color = '#1C4B31';
            }
            if (in_array($frstLetter, array('O'))) {
                $color = '#0D3820';
            }
            if (in_array($frstLetter, array('P'))) {
                $color = '#7B6C44';
            }
            if (in_array($frstLetter, array('Q'))) {
                $color = '#CEBE95';
            }
            if (in_array($frstLetter, array('R'))) {
                $color = '#CEBE95';
            }
            if (in_array($frstLetter, array('S'))) {
                $color = '#A79566';
            }
            if (in_array($frstLetter, array('T'))) {
                $color = '#695728';
            }
            if (in_array($frstLetter, array('U'))) {
                $color = '#4F3E12';
            }
            if (in_array($frstLetter, array('V'))) {
                $color = '#7B5044';
            }
            if (in_array($frstLetter, array('W'))) {
                $color = '#CEA195';
            }
            if (in_array($frstLetter, array('X'))) {
                $color = '#A77366';
            }
            if (in_array($frstLetter, array('Y'))) {
                $color = '#693528';
            }
            if (in_array($frstLetter, array('Z'))) {
                $color = '#4F1F12';
            }
        }
        return "<a href='/users/profile/?id=$user_ID'><div class='clan_avatar smallAvatar $addClass' style='background-color:$color;'>$frstLetter</div></a>";
    } else {
        return "<div class='clan_avatar smallAvatar $addClass' style='background-color:#ddd;'>?</div>";
    }
}

function clan_avatar($clan_ID, $type) {
    $addClass = '';
    if (!empty($type)) {
        $addClass = $type;
    }
    if ($clan_ID != 0) {
        $avatar = get_post_meta($clan_ID, 'clan_thumb', true);
        if(empty($avatar)) $avatar = get_post_meta($clan_ID, 'clan_image', true);
        if (!empty($avatar)) {
            //$avatar = str_replace("http://", "https://", $avatar);
            return "<a href='" . get_the_permalink($clan_ID) . "'><div class='setAvatar clan_avatar $addClass' style='background: url(" . $avatar . ");'></div></a>";
        } else {
            $userName = get_the_title($clan_ID);
            $frstLetter = strtoupper(substr($userName, 0, 1));
            $color = '#2D434E'; // Basic color
            if (in_array($frstLetter, array('A'))) {
                $color = '#2D434E';
            }
            if (in_array($frstLetter, array('B'))) {
                $color = '#607782';
            }
            if (in_array($frstLetter, array('C'))) {
                $color = '#425D69';
            }
            if (in_array($frstLetter, array('D'))) {
                $color = '#1B3642';
            }
            if (in_array($frstLetter, array('E'))) {
                $color = '#0D2632';
            }
            if (in_array($frstLetter, array('F'))) {
                $color = '#343855';
            }
            if (in_array($frstLetter, array('G'))) {
                $color = '#6C708E';
            }
            if (in_array($frstLetter, array('H'))) {
                $color = '#4C5173';
            }
            if (in_array($frstLetter, array('I'))) {
                $color = '#212648';
            }
            if (in_array($frstLetter, array('J'))) {
                $color = '#121636';
            }
            if (in_array($frstLetter, array('K'))) {
                $color = '#315842';
            }
            if (in_array($frstLetter, array('L'))) {
                $color = '#6A937C';
            }
            if (in_array($frstLetter, array('M'))) {
                $color = '#49775D';
            }
            if (in_array($frstLetter, array('N'))) {
                $color = '#1C4B31';
            }
            if (in_array($frstLetter, array('O'))) {
                $color = '#0D3820';
            }
            if (in_array($frstLetter, array('P'))) {
                $color = '#7B6C44';
            }
            if (in_array($frstLetter, array('Q'))) {
                $color = '#CEBE95';
            }
            if (in_array($frstLetter, array('R'))) {
                $color = '#CEBE95';
            }
            if (in_array($frstLetter, array('S'))) {
                $color = '#A79566';
            }
            if (in_array($frstLetter, array('T'))) {
                $color = '#695728';
            }
            if (in_array($frstLetter, array('U'))) {
                $color = '#4F3E12';
            }
            if (in_array($frstLetter, array('V'))) {
                $color = '#7B5044';
            }
            if (in_array($frstLetter, array('W'))) {
                $color = '#CEA195';
            }
            if (in_array($frstLetter, array('X'))) {
                $color = '#A77366';
            }
            if (in_array($frstLetter, array('Y'))) {
                $color = '#693528';
            }
            if (in_array($frstLetter, array('Z'))) {
                $color = '#4F1F12';
            }
        }
        return "<a href='" . get_the_permalink($clan_ID) . "'><div class='clan_avatar smallAvatar $addClass' style='background-color:$color;'>$frstLetter</div></a>";
    }
}


function wpse_76815_remove_publish_box() {
    remove_meta_box('submitdiv', 'clan', 'side');
}
add_action('admin_menu', 'wpse_76815_remove_publish_box');

function count_deposits($user_ID) {
    $args = array(
        'posts_per_page' => -1,
        'author' => $user_ID,
        'post_type' => 'deposit',
    );
    $deposits = get_posts($args);
    return count($deposits);
}

function clan_tag($user_ID) {
    $clanId = get_user_meta($user_ID, 'clan_id_user', true);
    if ($clanId != 0) {
        $clantag = get_post_meta($clanId, 'clan_tag', true);
        $chars = array("[", "]");
        $clantag = str_replace($chars, "", $clantag);
        return '<strong>[' . $clantag . ']</strong>';
    }
}

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

    CurrentUser::make()->register($user_id);

    update_user_meta($user_id, 'clan_id_user', 0);

// Set points & NW position
    update_user_meta($user_id, 'points_position', 0);
    update_user_meta($user_id, 'networth_position', 0);

// SET BUILDING NEW USER
    update_user_meta($user_id, 'silo', 0);
    update_user_meta($user_id, 'command_centre', 0);
    update_user_meta($user_id, 'shipyard', 0);
    update_user_meta($user_id, 'airfield', 0);
    update_user_meta($user_id, 'warfactory', 0);
    update_user_meta($user_id, 'baracks', 0);
    update_user_meta($user_id, 'powerplant', 50);
    update_user_meta($user_id, 'advancedpowerplant', 0);
    update_user_meta($user_id, 'torpedolauncher', 0);
    update_user_meta($user_id, 'samsite', 0);
    update_user_meta($user_id, 'missileturret', 0);
    update_user_meta($user_id, 'machinegunturret', 0);
    update_user_meta($user_id, 'antimissile', 0);

// SET MISSILES NEW USER
    update_user_meta($user_id, 'nuke_owned', 0);
    update_user_meta($user_id, 'nuke_ordered', 0);
    update_user_meta($user_id, 'chemical_owned', 0);
    update_user_meta($user_id, 'chemical_ordered', 0);
    update_user_meta($user_id, 'bio_owned', 0);
    update_user_meta($user_id, 'bio_ordered', 0);
    update_user_meta($user_id, 'moab_owned', 0);
    update_user_meta($user_id, 'moab_ordered', 0);
    update_user_meta($user_id, 'tomahawk_owned', 0);
    update_user_meta($user_id, 'tomahawk_ordered', 0);
    update_user_meta($user_ID, 'empmis_owned', 0);
    update_user_meta($user_ID, 'empmis_ordered', 0);

// SET STATS
    update_user_meta($user_id, 'money', 450000);
    update_user_meta($user_id, 'land_sold_today', 0);
    update_user_meta($user_id, 'explored_today', 0);
    update_user_meta($user_id, 'turns', 200);
    update_user_meta($user_id, 'networth', 0);
    update_user_meta($user_id, 'land', 2000);
    update_user_meta($user_id, 'power', 0);
    update_user_meta($user_id, 'builtland', 1000);
    update_user_meta($user_id, 'morale', 100);
    update_user_meta($user_id, 'morale_pool', 100);
    update_user_meta($user_id, 'clan_id_user', 0);
    update_user_meta($user_id, 'new_events', 0);
    update_user_meta($user_id, 'status', 'nukeprotection');
    $timestamp = current_time('timestamp');
    update_user_meta($user_id, 'nuke_protection_timestamp', $timestamp + (48 * 3600));
    update_user_meta($user_id, 'sat_in_progress', 0);
    update_user_meta($user_id, 'sat_owned', 0);
    update_user_meta($user_id, 'total_deposits', 0);
    update_user_meta($user_id, 'new_messages', 0);
    update_user_meta($user_id, 'new_events', 0);
    update_user_meta($user_id, 'user_country', 0);
    update_user_meta($user_ID, 'user_clan_points', 0);

// SET RESEARCH ///
    update_user_meta($user_id, 'level_money_production', 0);
    update_user_meta($user_id, 'level_missile_accuracy', 0);
    update_user_meta($user_id, 'level_sattelite_construction', 0);
    update_user_meta($user_id, 'level_sattelite_construction', 0);
    update_user_meta($user_id, 'level_shipping_time', 0);
    update_user_meta($user_id, 'level_market_discount', 0);
    update_user_meta($user_id, 'level_thieving_effectiveness', 0);
    update_user_meta($user_id, 'level_engineering_effectiveness', 0);
    update_user_meta($user_id, 'level_bank_management', 0);
    update_user_meta($user_id, 'level_powerplant_efficiency', 0);
    update_user_meta($user_id, 'research_in_progress', 0);
    update_user_meta($user_id, 'queued_research', 0);
    update_user_meta($user_id, 'first_visit', 0);

    $units = Units::get();

    foreach ($units as $key => $unit) {
        update_user_meta($user_id, $key . '_owned', 0);
        update_user_meta($user_id, $key . '_ordered', 0);

    }
}

function after_death($user_id) {

    if (!empty($user_id)) {
        // SET BUILDING after death
        update_user_meta($user_id, 'silo', 0);
        update_user_meta($user_id, 'command_centre', 0);
        update_user_meta($user_id, 'shipyard', 0);
        update_user_meta($user_id, 'airfield', 0);
        update_user_meta($user_id, 'warfactory', 0);
        update_user_meta($user_id, 'baracks', 0);
        update_user_meta($user_id, 'powerplant', 50);
        update_user_meta($user_id, 'advancedpowerplant', 0);
        update_user_meta($user_id, 'torpedolauncher', 0);
        update_user_meta($user_id, 'samsite', 0);
        update_user_meta($user_id, 'missileturret', 0);
        update_user_meta($user_id, 'machinegunturret', 0);
        update_user_meta($user_id, 'antimissile', 0);

        // SET MISSILES after death
        update_user_meta($user_id, 'nuke_owned', 0);
        update_user_meta($user_id, 'nuke_ordered', 0);
        update_user_meta($user_id, 'chemical_owned', 0);
        update_user_meta($user_id, 'chemical_ordered', 0);
        update_user_meta($user_id, 'bio_owned', 0);
        update_user_meta($user_id, 'bio_ordered', 0);
        update_user_meta($user_id, 'moab_owned', 0);
        update_user_meta($user_id, 'moab_ordered', 0);
        update_user_meta($user_id, 'tomahawk_owned', 0);
        update_user_meta($user_id, 'tomahawk_ordered', 0);
        update_user_meta($user_id, 'empmis_owned', 0);
        update_user_meta($user_id, 'empmis_ordered', 0);

        // SET STATS after death
        update_user_meta($user_id, 'money', 450000);

        update_user_meta($user_id, 'land_sold_today', 0);
        update_user_meta($user_id, 'explored_today', 0);
        update_user_meta($user_id, 'turns', 200);
        update_user_meta($user_id, 'networth', 0);
        update_user_meta($user_id, 'land', 2000);
        update_user_meta($user_id, 'power', 0);
        update_user_meta($user_id, 'builtland', 1000);
        update_user_meta($user_id, 'morale', 0);
        update_user_meta($user_id, 'sat_morale', 0);
        update_user_meta($user_id, 'morale_pool', 0);
        update_user_meta($user_id, 'total_deposits', 0);

        // RESET RESEARCH ///
        update_user_meta($user_id, 'level_money_production', 0);
        update_user_meta($user_id, 'level_missile_accuracy', 0);
        update_user_meta($user_id, 'level_satellite_construction', 0);
        update_user_meta($user_id, 'level_shipping_time', 0);
        update_user_meta($user_id, 'level_market_discount', 0);
        update_user_meta($user_id, 'level_thieving_effectiveness', 0);
        update_user_meta($user_id, 'level_engineering_effectiveness', 0);
        update_user_meta($user_id, 'level_bank_management', 0);
        update_user_meta($user_id, 'level_powerplant_efficiency', 0);

        update_user_meta($user_id, 'research_in_progress', 0);
        update_user_meta($user_id, 'queued_research', 0);
        update_user_meta($user_id, 'sat_in_progress', 0);
        update_user_meta($user_id, 'sat_owned', 0);
        update_user_meta($user_id, 'starting_bonus', '');
        update_user_meta($user_id, 'stealth_sat_status', 0);

        $args = array(
            'posts_per_page' => -1,
            'author' => $user_id,
            'post_type' => 'research',
        );
        $researches_in_progress = get_posts($args);
        foreach ($researches_in_progress as $research) {

            wp_delete_post($research->ID);
        }

        $args = array(
            'posts_per_page' => -1,
            'author' => $user_id,
            'post_type' => 'deposit',
        );
        $deposits = get_posts($args);
        foreach ($deposits as $deposit) {

            wp_trash_post($deposit->ID);
        }

        $args = array(
            'posts_per_page' => -1,
            'author' => $user_id,
            'post_type' => 'market_order',
        );
        $orders = get_posts($args);
        foreach ($orders as $order) {

            wp_trash_post($order->ID);
        }

        $units = Units::get();

        foreach ($units as $key => $unit) {
            update_user_meta($user_id, $key . '_owned', 0);
            update_user_meta($user_id, $key . '_ordered', 0);

        }
    } // End empty userID check

} // End after death

function count_all_stats($user_ID) {

    $userData = get_user_meta($user_ID);

    $status = $userData['status'][0];

    if (!empty($user_ID) && $status != 'banned') {

        $units = Units::get();
        $missiles = Missiles::get();
        include ABSPATH . 'building_array.php';
        $researches = Researches::get();
        include ABSPATH . 'constants.php';
        $satellites = Satellites::get();

/* calculate unit NW */
        $unit_networth = 0;
        foreach ($units as $key => $unit) {
            $units_owned = 0;
            $units_owned = isset($userData[$key . '_owned'][0]) ? $userData[$key . '_owned'][0] : 0;
            $units_owned = !empty($userData[$key . '_owned'][0]) ? $userData[$key . '_owned'][0] : 0;

            if ($units_owned > 0) {
                $unit_networth += $units_owned * $unit['price'] * ($unit['networth'] / 100);
            }
        } // End calculate unit NW

/* calculate missile NW */
        $missile_networth = 0;
        foreach ($missiles as $key => $missile) {

            $missiles_owned = 0;
            $missiles_owned = isset($userData[$key . '_owned'][0]) ? $userData[$key . '_owned'][0] : 0;
            $missiles_owned = !empty($userData[$key . '_owned'][0]) ? $userData[$key . '_owned'][0] : 0;

            if ($missiles_owned > 0) {
                $missile_networth += $missiles_owned * $missile['price'] * ($missile['networth'] / 100);
            }
        } // End calculate missile NW

        $building_networth = 0;
        $totalbuildings = 0;
        $used_power = 0;
        $power_production = 0;

        $PPE_level = $userData['level_powerplant_efficiency'][0];
        $PPE_multi = 1;

        if ($PPE_level == 1) {
            $PPE_multi = 1.5;
        }

/* calculate building NW */
        foreach ($buildings as $key => $building) {
            $buildings_owned = $userData[$key][0];

            if ($buildings_owned > 0) {
                $totalbuildings += $buildings_owned;
                $building_networth += $buildings_owned * $building['price'] * ($building['networth'] / 100);
                $power_production += $building['powerprod'] * $buildings_owned;
                $used_power += $building['power'] * $buildings_owned;
            }
        } // End calculate building NW

        $research_NW = 0;
        foreach ($researches as $key => $research) {

            $level = 0;
            $level = isset($userData['level_' . $key][0]) ? $userData['level_' . $key][0] : 0;
            $level = !empty($userData['level_' . $key][0]) ? $userData['level_' . $key][0] : 0;

            if ($level > 0) {
                $research_NW += $research['duration'] * $RESEARCH_NW_PER_HOUR * $level;
            }
        }

        $sat_owned = $userData['sat_owned'][0];

        $sat_NW = 0;

        if ($sat_owned != 0 || !empty($sat_owned)) {
            $sat_NW = $satellites[$sat_owned]['price'] * 0.04;
        }

        $land = $userData['land'][0];
        $land_networth = round($land * 0.85);

        $totalNW = round($sat_NW + $research_NW + $building_networth + $unit_networth + $land_networth + $missile_networth);
        update_user_meta($user_ID, 'networth', $totalNW);

        update_user_meta($user_ID, 'sat_nw', round($sat_NW));
        update_user_meta($user_ID, 'research_nw', round($research_NW));
        update_user_meta($user_ID, 'building_nw', round($building_networth));
        update_user_meta($user_ID, 'unit_nw', round($unit_networth));
        update_user_meta($user_ID, 'land_nw', round($land_networth));
        update_user_meta($user_ID, 'missile_nw', round($missile_networth));

        update_user_meta($user_ID, 'builtland', $totalbuildings * 20);

        $highestNW = $userData['highest_networth'][0];
        if ($totalNW > $highestNW) {
            update_user_meta($user_ID, 'highest_networth', $totalNW);
        }

        $highestLand = $userData['highest_land'][0];
        if ($land > $highestLand) {
            update_user_meta($user_ID, 'highest_land', $land);
        }

        $empReduction = 0;

        $emps = get_posts(array(
            'numberposts' => -1,
            'post_type' => 'emp',
            'meta_key' => 'defender_emp',
            'meta_value' => $user_ID,
        ));
        $empReduction = 0;
        foreach ($emps as $emp) {
            $empReduction += get_post_meta($emp->ID, 'deduction_emp', true);
        }

        if ($power_production > 0) {
            update_user_meta($user_ID, 'power', $used_power / ($power_production * $PPE_multi) * 100 + $empReduction);
        } else {
            update_user_meta($user_ID, 'power', $used_power * 100 + $empReduction);
        }

        $power = $userData['power'][0];
//update_user_meta($user_ID,'power',$power+$empReduction);

    } // end empty user ID check
} // end count stats


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
    include 'building_array.php';
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
        $body = 'Your protection has been removed';
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
        $body = 'You have less then 50 buildings. Rebuild as soon as possible';
        $url = get_site_url() . '/buildings/';
        update_user_meta($receiver, 'low_buildings_notified', 'yes');
    }

    /*if ($type == 'roundstart') {
        $avatar = get_user_meta($receiver, 'avatar_user', true);
        $body = 'A new round has begun';
        $url = get_site_url() . '/dashboard/';
    }*/

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

/**
 * @todo: check if user wants these help-texts
 * @todo: find a way to "stack" these texts
 */
function helpText($message, $source='generic', $type='tip') {
    echo "<script>(function($) { setTimeout(function() {
        $.notify({message:'".ucfirst($type).": ".$message."'},{type:'help',newest_on_top:true});
    },200); })(jQuery);</script>";
}