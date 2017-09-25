<?php
/**
 * Handles attacks
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (get_field('game_status', 'option') == 'Live') {
    include('units_array.php');
    include('attack_functions.php');
    nocache_headers();

/* initialize core variables */
    $user_ID = get_current_user_id();
    update_user_meta($user_ID, 'user_lock', 0);
    if (! defined('ABSPATH')) {
        exit;
    }
    if (empty($user_ID)) {
        wp_redirect(get_permalink(3582));
        exit;
    }
    if (!is_user_logged_in()) {
        wp_redirect(get_permalink(3582));
        exit;
    }
    $user_data = get_user_meta($user_ID);
    $clan_id = $user_data['clan_id_user'][0];
    $turns = $user_data['turns'][0];
    $morale = $user_data['morale'][0];
    $maintarget = $_POST['maintarget'];


    $sat_morale = get_user_meta($user_ID, 'sat_morale', true);

    $target_id = $_POST['target_id'];
    count_all_stats($target_id);

    $attackmode = $_POST['attackmode'];
    $extra_morale_cost = 0;
    if ($attackmode == 'aggressive') {
        $extra_morale_cost = 10;
    }
/* == validate the attack == */
/* target id cannot be blank */
    if ($target_id == '') {
        $_SESSION['status'] = 'Choose a player ID to attack';
        wp_redirect(get_permalink(3360));
        exit;
    }

/* validate target id must be numeric */
    if (!is_numeric($target_id)) {
        $_SESSION['status'] = 'Enter a valid number';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* target cannot be yourself */
    if ($target_id == $user_ID) {
        $_SESSION['status'] = 'You cannot target yourself';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* target must be a real user */
    if (get_userdata($target_id) == false) {
        $_SESSION['status'] = 'Player ID not found';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* target cannot be dead or in protection */
    $status = get_user_meta($target_id, 'status')[0];
    if ($status == 'dead') {
        $_SESSION['status'] = 'This player is dead';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }
    if ($status == 'nukeprotection') {
        $_SESSION['status'] = 'You cannot attack someone who is under Assault Protection';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* check if target is in own clan */
    if ($clan_id != 0) {
        $members = get_post_meta($clan_id, 'clan_members')[0];
        if (in_array($target_id, $members)) {
            $_SESSION['status'] = 'You cannot attack your own clan members';
            wp_redirect(get_permalink(3360).'?id='.$target_id);
            exit;
        }
    }

/* determine war type and war points multiplier */
    $attacker_clan_ID = $user_data['clan_id_user'][0];
    $defender_clan_ID = get_user_meta($target_id, 'clan_id_user')[0];

    $war_type = get_war_type($attacker_clan_ID, $defender_clan_ID);
    $war_multiplier = get_war_multiplier($war_type);

/* determine if target is in range */
    $attacktype = $_POST['attacktype'];
    $networth_att = $user_data['networth'][0];
    $networth_def = get_user_meta($target_id, 'networth')[0];

    if ($networth_def < 3500) {
        $_SESSION['status'] = 'Out of networth range';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* determine if target is in range */

    $in_range = target_in_range($attacktype, $networth_att, $networth_def, $war_type);
    if (!$in_range) {
        $_SESSION['status'] = 'Out of networth range';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

// Check if user is member of clan for 24h, if not, cannot attack out of range in mutual
    $join_timestamp = get_user_meta($user_ID, 'clan_join_stamp', true);
    $timestamp = current_time('timestamp');
    $in_range = target_in_range($attack_type, $networth_att, $networth_def, 'none');

    if ($war_type == 'mutual' && $timestamp < $join_timestamp && $in_range != true) {
        $_SESSION['status'] = 'Cannot attack out of networth range in mutual war the first 24 hours after joining a clan';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* determine if attacker has required resourced */
    $turnCost = get_attack_cost_turns($attacktype);
    $moraleCost = get_attack_cost_morale($attacktype, $networth_att, $networth_def);
/* check morale */
    if ($moraleCost+$extra_morale_cost > $morale) {
        $_SESSION['status'] = 'Insufficient morale';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }
/* check satellite morale */
    if ($attacktype == 'satellite') {
        if (100 > $sat_morale) {
            $_SESSION['status'] = 'Not enough satellite power';
            wp_redirect(get_permalink(3360).'?id='.$target_id);
            exit;
        }
    }

/* check turns */
    if ($turnCost > $turns) {
        $_SESSION['status'] = 'Not enough turns';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* check power */
    $user_power = $user_data['power'][0];
    if ($user_power > 100) {
        $_SESSION['status'] = 'You cannot attack with power offline';
        wp_redirect(get_permalink(3360).'?id='.$target_id);
        exit;
    }

/* validations passed - advance to step 2 */
    $_SESSION['attacktype'] = $attacktype;
    $_SESSION['target_id'] = $target_id;
    $_SESSION['attackmode'] = $attackmode;
    $_SESSION['maintarget'] = $maintarget;
    wp_redirect(get_permalink(3363));   //stap 2
    exit;
}
