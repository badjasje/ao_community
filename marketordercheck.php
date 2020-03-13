<?php
require(dirname(__FILE__) . '/wp-load.php');
if (get_field('game_status', 'option') != 'Live') { exit; }

    $timestamp = current_time('timestamp');

    $args = array();
    $gameType = get_field('game_type','option');
    if(in_array($gameType, array('Development'))) { // Just me on dev.
        $args = array('include' => array(2,2768));
    }

    $users = get_users($args);
    foreach ($users as $user) {
        $user_ID = $user->ID;
        $province = Province::make($user_ID);

        // Check power level
        $power = $province->getPower();
        $buildings = $province->getBuildings();
        $plants = $buildings['powerplant']['num'] + $buildings['advancedpowerplant']['num'];
        if($plants > 0 && $power >= 100) {
            $province->notify('lowpower', $user_ID);
        }
        if($plants > 0 && $power > 50) {
            $province->notify('highpower', $user_ID);
        }

        // Total buildings error
        if($province->getBuildingsNum() < 50) {
            $province->notify('buildings', $user_ID);
        }

        /* sat crash */
        $sat_owned = $province->get('sat_owned');
        if(!empty($sat_owned)) {
            $sat = $province->getSatellites($sat_owned);
            $timeleft = (!!$sat && $sat['num'] > 0 ? $sat['timeleft'] : 0);
            if ($timeleft <= 0) {
                $province->crashSatellite($sat_owned);
                $province->notify('satcrash', $user_ID);
            }
        }

        /* deactivate stealth sat */
        if(($province->get('stealth_sat_time') - $timestamp) <= 0) {
            $province->update('stealth_sat_status', 'inactive');
        }

        /* finish research */
        if($research = $province->getCurrentResearch()) {
            if($research->timeLeft() <= 0) $research->end(); // starts queued research too, sends notification
        }

        /* remove NP */
        if ($province->isProtected()) {
            if(($province->get('nuke_protection_timestamp') - $timestamp) < 0) {
                $province->update('status', 'online');
                $province->notify('nukeprotectremoved', $user_ID);
                Event::create(array(
                    'title' => 'Assault protection removed for '.$user_ID, 'author' => $user_ID, 'type' => 'nukeprotection',
                    'defender_id' => $user_ID, 'attacker_id' => $user_ID
                ), $user_ID);
            }
        }
    }


    $bonus = Bonuses::get();
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
                $evt = Event::create(array(
                    'title' => 'Bonus for: #'.$member, 'author' => $member, 'type' => 'bonus', 'defender_id' => $member,
                    'bonus_money' => $bonusMoney, 'bonus_turns' => $bonusTurns, 'attacker_clan_id' => $clan_ID
                ), $member);
            }

            if($level == 'level_2' && empty(Round::getGoldenShotgun())) {
                Round::setGoldenShotgun($clan_ID);
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
            $cooldownlist[$clan_ID] = $timestamp + Settings::get('cooldown_time');
            update_post_meta($declarer_clan_ID, 'cooldown_list', $cooldownlist);

            /* update events */
            $clan_members = get_post_meta($declared_on, 'clan_members');
            if(isset($clan_members[0])) {
                foreach ($clan_members[0] as $member) {
                    $globals = get_user_meta($member, 'new_global_events', true);
                    update_user_meta($member, 'new_global_events', $globals+1);
                }
            }

            $clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');
            if(isset($clan_members2[0])) {
                foreach ($clan_members2[0] as $member2) {
                    $globals = get_user_meta($member2, 'new_global_events', true);
                    update_user_meta($member2, 'new_global_events', $globals+1);
                }
            }
            wp_trash_post($war->ID);
        }
    }