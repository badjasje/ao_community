<?php

function ajax_claninvite($province, $return) {
    if(!empty($province->get('clan_id_user'))) return array('status' => 'You are already a member of a clan');
    $inviteKey = Request::post('hash');
    $clan_id = Request::post('clan');
    $clan = Clan::make($clan_id);
    $target = Request::post('target');
    if(empty($clan->get('id'))) return array('status' => 'No such clan');

    $timestamp = current_time('timestamp');
    $open_invites = $clan->getOpenInvites();
    if(!count($open_invites)) return array('status' => 'No invites found');

    if(empty($province->get('id'))) return array('status' => 'No user found');

    if($target == 'Accept') {
        if(Round::isLive() && Round::timeLeft() < 172800) {
            return array('status' => 'Cannot join a clan the last 48 hours of a round');
        }
        if($clan->isFull()) {
            return array('status' => 'Maximum number of clan members reached');
        }
        $clanMembers = $clan->getMembers(); // user ids
        $clanLeader = $clan->getLeader(); // user id
        foreach ($open_invites as $key => $invite) {
            if($invite['invite'] == $inviteKey && $invite['clan'] == $clan->get('id')) {
                if($invite['user'] != $province->get('id')) return array('status' => 'clan is not the invite you\'re looking for');

                $province->update('clan_id_user', $clan->get('id'));
                $province->update('clan_join_stamp', $timestamp+86400);
                $clanMembers[] = $province->get('id');
                unset($open_invites[$key]);
                $clan->update('clan_members', $clanMembers);
                $clan->update('open_invites', $open_invites);
                update_post_meta($invite['invite_id'], 'invite_status', 'accept');

                $args = [
                    'post_title' => 'Clan member joined a clan: '.$province->get('id'), 'post_status' => 'publish',
                    'post_type' => 'event_local', 'post_author' => $clanLeader
                ];
                $newEventId = wp_insert_post( $args );
                update_field('attacktype', 'user_change', $newEventId);
                update_field('outcome', 'joined', $newEventId);
                update_field('attacker_id', $clanLeader, $newEventId);
                update_field('defender_id', $province->get('id'), $newEventId);
                update_field('attacker_clan_id', $clan->get('id'), $newEventId);
                update_field('time_attacked', $timestamp, $newEventId);
                foreach ($clanMembers as $member_id) {
                    $member = Province::make($member_id);
                    $member->update('new_global_events', $member->get('new_global_events') + 1);
                }
                return array('success' => true, 'status' => "You are now a member of ".$clan->getName());
            }
        }
    }
    else { // decline
        foreach ($open_invites as $key => $invite) {
            if ($invite['invite'] == $inviteKey && $invite['clan'] == $clan->get('id')) {
                if($invite['user'] != $province->get('id')) return array('status' => 'clan is not the invite you\'re looking for');
                unset($open_invites[$key]);
                update_post_meta($invite['invite_id'], 'invite_status', 'accept');
                $clan->update('open_invites', $open_invites);
                return array('success' => true, 'status' => "You declined the invite of ".$clan->getName());
            }
        }
    }
    return array('status' => 'Undefined error');
}