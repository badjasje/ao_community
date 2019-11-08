<?php

function ajax_invite($province, $return) {
    if(!empty($province->get('clan_id_user'))) {
        return array('status' => 'You are already a member of a clan');
    }

    $inviteKey = Request::post('hash');
    $clan_id = Request::post('clan');
    $clan = Clan::make($clan_id);
    $target = Request::post('target');
    if(empty($clan->get('id'))) {
        return array('status' => 'No such clan');
    }

    $timestamp = current_time('timestamp');
    $open_invites = $clan->getOpenInvites();
    if(!count($open_invites)) {
        return array('status' => 'No invites found');
    }

    if(empty($province->get('id'))) {
        return array('status' => 'No user found');
    }

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
                if($invite['user'] != $province->get('id')) {
                    return array('status' => 'This is not the invite you\'re looking for');
                }

                $province->update('clan_id_user', $clan->get('id'));
                $province->update('clan_join_stamp', $timestamp+86400);
                $clanMembers[] = $province->get('id');
                unset($open_invites[$key]);
                $clan->update('clan_members', $clanMembers);
                $clan->update('open_invites', $open_invites);
                update_post_meta($invite['invite_id'], 'invite_status', 'accept');

                $ev = Event::create(array(
                    'title' => 'Clan member joined a clan: ' . $province->get('id'),
                    'author' => $clanLeader,
                    'type' => 'user_change',
                    'send' => 'global',
                    'outcome' => 'joined',
                    'attacker_id' => $clanLeader,
                    'defender_id' => $province->get('id'),
                    'attacker_clan_id' => $clan->get('id')
                ), $clanMembers);

                return array('success' => true, 'status' => "You are now a member of ".$clan->getName());
            }
        }
    }
    else { // decline
        foreach ($open_invites as $key => $invite) {
            if ($invite['invite'] == $inviteKey && $invite['clan'] == $clan->get('id')) {
                if($invite['user'] != $province->get('id')) {
                    return array('status' => 'This is not the invite you\'re looking for');
                }
                unset($open_invites[$key]);
                update_post_meta($invite['invite_id'], 'invite_status', 'accept');
                $clan->update('open_invites', $open_invites);
                return array('success' => true, 'status' => "You declined the invite of ".$clan->getName());
            }
        }
    }
    return array('status' => 'Undefined error');
}