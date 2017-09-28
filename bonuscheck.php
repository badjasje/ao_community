<?php
function bonus_update()
{
    include 'bonus_array.php';
    $timestamp = strtotime(date('Y-m-d H:i:s'));
    $args = array(
        
    'post_type'     =>  'clan',
    'posts_per_page' => -1,
    );
    
    $clans = get_posts($args);
    foreach ($clans as $clan) {
        $clan_ID = $clan->ID;
        
        $clan_members   = get_post_meta($clan_ID, 'clan_members');
        $clan_points    = get_post_meta($clan_ID, 'clan_points', true);
        $bonus_level    = get_post_meta($clan_ID, 'bonus_level', true);
    
        if (empty($clan_points)) {
            $clan_points = 0;
        }
    
        $level = "level_";
    
        /* mini clan bonus level 1 */
        if ($bonus_level == 0) {
            if ((500 <= $clan_points) && ($clan_points <= 999)) {
                $level .= 1;
                update_post_meta($clan_ID, 'bonus_level', 1);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
                
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
        
    
        /* regular clan bonus level 2*/
        if ($bonus_level == 1) {
            if ((1000 <= $clan_points) && ($clan_points <= 1499)) {
                $level .= 2;
                update_post_meta($clan_ID, 'bonus_level', 2);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
        
        /* regular clan bonus level 3 */
        if ($bonus_level == 2) {
            if ((1500 <= $clan_points) && ($clan_points <= 1999)) {
                $level .= 3;
                update_post_meta($clan_ID, 'bonus_level', 3);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
    
        /* regular clan bonus level 3 */
        if ($bonus_level == 3) {
            if ((2000 <= $clan_points) && ($clan_points <= 2499)) {
                $level .= 4;
                update_post_meta($clan_ID, 'bonus_level', 4);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
    
        /* regular clan bonus level 4 */
        if ($bonus_level == 4) {
            if ((2500 <= $clan_points) && ($clan_points <= 2999)) {
                $level .= 5;
                update_post_meta($clan_ID, 'bonus_level', 5);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
        
        /* regular clan bonus level 5 */
        if ($bonus_level == 5) {
            if ((3000 <= $clan_points) && ($clan_points <= 3499)) {
                $level .= 6;
                update_post_meta($clan_ID, 'bonus_level', 6);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
        
        /* Mega clan bonus level 6 */
        if ($bonus_level == 6) {
            if ((3500 <= $clan_points) && ($clan_points <= 3999)) {
                $level .= 7;
                update_post_meta($clan_ID, 'bonus_level', 7);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
    
    
        /* Regular clan bonus level 7 */
        if ($bonus_level == 7) {
            if ((4000 <= $clan_points) && ($clan_points <= 4499)) {
                $level .= 8;
                update_post_meta($clan_ID, 'bonus_level', 8);
            
        
                foreach ($clan_members[0] as $member) {
                    $args = array(
                    'post_title'    => 'Bonus for: #'.$member,
                    'post_status'   => 'publish',
                    'post_type'     => 'event_local',
                    'post_author'   => $member
                        );
            
                        $new_event_id = wp_insert_post($args);
                        update_field('attacktype', 'bonus', $new_event_id);
                        update_field('bonus_money', $bonus[$level]['money'], $new_event_id);
                        update_field('bonus_turns', $bonus[$level]['turns'], $new_event_id);
                        update_field('defender_id', $member, $new_event_id);
                        update_field('time_attacked', $timestamp, $new_event_id);
                    
                        $event_count = get_user_meta($member, 'new_events')[0];
                        update_user_meta($member, 'new_events', $event_count + 1);
                }
            }
        }
    }
}
