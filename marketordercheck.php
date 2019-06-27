<?php
require(dirname(__FILE__) . '/wp-load.php');
if (get_field('game_status', 'option') != 'Live') { exit; }

    include 'building_array.php';

    $timestamp = current_time('timestamp');

    $args = array();
    $gameType = get_field('game_type','option');
    if(in_array($gameType, array('Development'))) { // Just me on dev.
        $args = array('include' => array(2768));
    }

    $users = get_users($args);
    foreach ($users as $user) {
        $user_ID = $user->ID;
        $userData = get_user_meta($user_ID);

        // Check power level
        $power = isset($userData['power'][0]) ? $userData['power'][0] : 0;
        $plants = (isset($userData['powerplant'][0]) ? $userData['powerplant'][0] : 0);
        $plants += (isset($userData['advancedpowerplant'][0]) ? $userData['advancedpowerplant'][0] : 0);
        if($plants > 0 && $power >= 100) {
            fcm_send_notification($user_ID, 'lowpower', $user_ID);
        }
        if($plants > 0 && $power > 50) {
            fcm_send_notification($user_ID, 'highpower', $user_ID);
        }

        // Count total buildings
        $total = 0;
        foreach($buildings as $key => $value) {
            $num = isset($userData[$key][0]) ? $userData[$key][0] : 0;
            if($num > 0) $total += $num;
        }
        if($total > 0 && $total < 50) {
            fcm_send_notification($user_ID, 'buildings', $user_ID);
        }

        /* sat crash */
        $sat_owned = $userData['sat_owned'][0];
        $sat_endlife = 0;
		$sat_endlife = !empty( $userData['sat_endlife'][0]) ?  $userData['sat_endlife'][0] : 0;

        $timeleft = $sat_endlife-$timestamp;
        if ($timeleft <= 0 && $sat_owned != '0') {
            update_user_meta($user_ID, 'sat_owned', 0);
            update_user_meta($user_ID, 'sat_endlife', 0);

            $args = array(
                'post_title'    => 'Sat crash: '.$user_ID,
                'post_status'   => 'publish',
                'post_type'     => 'event_local',
                'post_author'   => $user_ID
            );
            $new_event_id = wp_insert_post($args);
            update_field('attacktype', 'sat_crash', $new_event_id);
            update_field('attacker_id', 0, $new_event_id);
            update_field('defender_id', $user_ID, $new_event_id);
            update_field('time_attacked', $timestamp, $new_event_id);

            /* update event count */
            $event_count = $userData['new_events'][0];
            update_user_meta($user_ID, 'new_events', $event_count + 1);
            fcm_send_notification($user_ID,'satcrash',$user_ID);
        } // End sat crash

        /* deactivate stealth sat */
        $stealth_sat_time = 0;
		$stealth_sat_time = isset($userData['stealth_sat_time'][0]) ?  $userData['stealth_sat_time'][0] : 0;
		$stealth_sat_time = !empty( $userData['stealth_sat_time'][0]) ?  $userData['stealth_sat_time'][0] : 0;
        $timeleft = $stealth_sat_time-$timestamp;
        if ($timeleft <= 0) {
            update_user_meta($user_ID, 'stealth_sat_status', 'inactive');
        }

        $args = array(
            'posts_per_page'   => -1,
            'author'            => $user_ID,
            'post_type'        => 'research',
        );
        $researches = get_posts($args);
        foreach ($researches as $research) {
            $researchtime_left = $research->post_title-$timestamp;
            $research_in_progress = $research->post_content;

            /* Check research time left */
            if ($researchtime_left <= 0) {

                /* Update user */
                update_user_meta($user_ID, 'research_in_progress', 0);
                $current_level = get_user_meta($user_ID, 'level_'.$research_in_progress);
                update_user_meta($user_ID, 'level_'.$research_in_progress, $current_level[0]+1);
				fcm_send_notification($user_ID,'research',$user_ID);

                /* Create research event */
                $args = array(
                    'post_title'    => 'Research done for '.$user_ID,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $user_ID
                );
                $new_event_id = wp_insert_post($args);
                update_field('outcome', $research_in_progress, $new_event_id);
                update_field('attacktype', 'research_ready', $new_event_id);

                update_user_meta($user_ID, 'new_events', get_user_meta($user_ID, 'new_events')[0]+1);
                update_field('defender_id', $user_ID, $new_event_id);
                update_field('attacker_id', $user_ID, $new_event_id);
                update_field('time_attacked', $timestamp, $new_event_id);

                /* Delete research post */
                wp_trash_post($research->ID);

                $queued_research = get_user_meta($user_ID, 'queued_research', true);

                if (!empty($queued_research) || $queued_research != 0) {
                    include 'research_array.php';
                    $time = $researches[$queued_research]['duration'];
                    $args = array(
                        'post_title'    => $timestamp+($time*60*60),  /* Receive research timestamp */
                        'post_status'   => 'publish',
                        'post_content'  => $queued_research,
                        'post_type'     => 'research',
                        'post_author'   => $user_ID
                    );
                    $new_research_id = wp_insert_post($args);

                    update_user_meta($user_ID, 'research_in_progress', $queued_research);
                    update_user_meta($user_ID, 'queued_research', 0);
                }
            }
        }

        $status = get_user_meta($user_ID, 'status');
        if ($status[0] == 'nukeprotection') {
            $nuke_protection_timestamp = get_user_meta($user_ID, 'nuke_protection_timestamp');
            $nuke_protection_timeleft = $nuke_protection_timestamp[0]-$timestamp;

            if ($nuke_protection_timeleft < 0) {
                update_user_meta($user_ID, 'status', 'online');
                fcm_send_notification($user_ID,'nukeprotectremoved',$user_ID);

                /* Create nuke protection event */
                $args = array(
                    'post_title'    => 'Nukeprotection removed for '.$user_ID,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $user_ID
                );

                $new_event_id = wp_insert_post($args);
                update_field('attacktype', 'nukeprotection', $new_event_id);
                update_user_meta($user_ID, 'new_events', get_user_meta($user_ID, 'new_events')[0]+1);
                update_field('defender_id', $user_ID, $new_event_id);
                update_field('attacker_id', $user_ID, $new_event_id);
                update_field('time_attacked', $timestamp, $new_event_id);
            }
        }
    }


    include 'bonus_array.php';
    $args = array(
        'post_type'     =>  'clan',
        'posts_per_page' => -1,
    );
    $clans = get_posts($args);
    foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        $clanData = get_post_meta($clan_ID);

        $cooldownlist = (isset($clanData['cooldown_list']) ? maybe_unserialize($clanData['cooldown_list'][0]) : false);
        if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
        if(!is_array($cooldownlist)) $cooldownlist = array();
        foreach ($cooldownlist as $key => $unset_time) {
            if ($unset_time < $timestamp) unset($cooldownlist[$key]);
            update_post_meta($clan_ID, 'cooldown_list', $cooldownlist);
        }

        if (empty($clan_points)) {
            $clan_points = 0;
        }

        $clan_members   = get_post_meta($clan_ID, 'clan_members');
        $clan_points    = $clanData['clan_points'][0];
        if($clan_points == 'NAN'){
	        $clan_points = 0;
        }
        $bonus_level    = $clanData['bonus_level'][0];

		if($bonus_level == 'NAN'){
	        $bonus_level = 0;
        }

        $level = "level_";
        $level .= $bonus_level;
        $high_end   =   $bonus[$level]['points'];

        if ($clan_points >= $high_end) {
            update_post_meta($clan_ID, 'bonus_level', $bonus_level+1);

            $bonusMoney = round($bonus[$level]['money']/count($clan_members[0]));
            $bonusTurns = round($bonus[$level]['turns']/count($clan_members[0]));

            foreach ($clan_members[0] as $member) {
                $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                );

                $new_event_id = wp_insert_post($args);
                update_field('attacktype', 'bonus', $new_event_id);
                update_field('bonus_money', $bonusMoney, $new_event_id);
                update_field('bonus_turns', $bonusTurns, $new_event_id);
                update_field('defender_id', $member, $new_event_id);
                update_field('time_attacked', $timestamp, $new_event_id);

                $event_count = get_user_meta($member, 'new_events')[0];
                update_user_meta($member, 'new_events', $event_count + 1);
            }
        }
    }

    $bonuses = get_posts(array(
        'numberposts'   => -1,
        'post_type'     => 'event_local',
        'meta_key'      => 'attacktype',
        'meta_value'    => 'bonus'
    ));
    foreach ($bonuses as $bonus) {
        $used = get_post_meta($bonus->ID, 'bonus_used', true);
        $receiver_ID = get_post_meta($bonus->ID, 'defender_id', true);
        $moraleLock = get_user_meta($receiver_ID, 'morale_lock', true);
        $turnLock = get_user_meta($receiver_ID, 'turn_lock', true);

        if ($used != 'yes') {
            $time = get_post_meta($bonus->ID, 'time_attacked', true)+(86400*2);
            if ($timestamp > $time && $moraleLock == 0 && $turnLock == 0) {
                $bonus_money = get_post_meta($bonus->ID, 'bonus_money', true);

                $money = get_user_meta($receiver_ID, 'money', true);
                $money_new = $money + $bonus_money;
                update_user_meta($receiver_ID, 'money', $money_new);

                /* Add bonus turns */
                $turns = get_user_meta($receiver_ID, 'turns', true);
                $bonus_turns = get_post_meta($bonus->ID, 'bonus_turns', true);

                $turns_new = $turns + $bonus_turns;

                update_user_meta($receiver_ID, 'turns', $turns_new);
                update_post_meta($bonus->ID, 'bonus_used', 'yes');
            }
        }
    }

    /* Get all current wars */
    $wars = get_posts(array(
        'numberposts'   => -1,
        'post_status'   => 'publish',
        'post_type'     => 'wars',
    ));
    foreach ($wars as $war) {
        /* get war declared time */
        $war_time = $war->post_title;

        /* check if 3 days have passed */
        if ($war_time+(86400*3) < $timestamp) {
            $declarer_clan_ID = get_post_meta($war->ID, 'declared_by', true);
            $declarer_ID = get_post_meta($declarer_clan_ID, 'clan_leader', true);

            $declared_on = get_post_meta($war->ID, 'declared_on', true);
            $def_clan_leader = get_post_meta($declared_on, 'clan_leader', true);

            /* Create peace event */
            $args = array(
                'post_title'    => 'PEACE',
                'post_status'   => 'publish',
                'post_type'     => 'event_local',
                'post_author'   => 1
            );
            $new_event_id = wp_insert_post($args);

            update_field('attacktype', 'peace_declared', $new_event_id);

            update_field('attacker_clan_id', $declarer_clan_ID, $new_event_id);
            update_field('defender_clan_id', $declared_on, $new_event_id);

            update_field('attacker_id', $declarer_ID, $new_event_id);
            update_field('defender_id', $def_clan_leader, $new_event_id);

            update_field('time_attacked', $timestamp, $new_event_id);

            /* add clan to cooldown list */
            $cooldownlist = maybe_unserialize(get_post_meta($declarer_clan_ID, 'cooldown_list', true));
            if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
            if(!is_array($cooldownlist)) $cooldownlist = array();
            $clan_ID = $declared_on;
            $cooldownlist[$clan_ID] = $timestamp+(72 * 3600);
            update_post_meta($declarer_clan_ID, 'cooldown_list', $cooldownlist);

            /* update events */
            $clan_members = get_post_meta($declared_on, 'clan_members');

            foreach ($clan_members[0] as $member) {
                $globals = get_user_meta($member, 'new_global_events', true);
                update_user_meta($member, 'new_global_events', $globals+1);
            }

            $clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');

            foreach ($clan_members2[0] as $member2) {
                $globals = get_user_meta($member2, 'new_global_events', true);
                update_user_meta($member2, 'new_global_events', $globals+1);
            }

            wp_trash_post($war->ID);
        }
    }