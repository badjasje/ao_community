<?php

class Conversation extends PostObject {

    function __construct($postData=null) {
        parent::__construct($postData);

        if(!empty($this->post_author)) {
            $this->setPropertiesFromArray(array(
                'author_id' => intval($this->post_author), 'subject' => $this->post_title
            ));
        }
    }

    public static function create($from, $to, $title, $message_text) {
        $args = array(
            'post_title' => $title, 'post_content' => $message_text, 'post_author' => $from,
            'post_status' => 'publish', 'post_type' => 'user_message', 'post_name' => md5(uniqid(rand(), true)),
        );
        $message_ID = wp_insert_post($args);
        $c = new Conversation($message_ID);
        $c->update('receiver_id', $to);
        $c->update('sender_id', $from);
        return $c;
    }

    function addMessage($from, $to, $message_text) {
        $timestamp = current_time('timestamp');
        $this->update('general_status', 'New');
        $this->update('last_update_stamp', $timestamp);

        $row = array(
            'field_5b5ef267154f1' => $from,
            'field_5b5ef27b154f2' => $message_text,
            'field_5b5f0429b56ca' => $to,
            'field_5d9217a58552c' => $timestamp,
        );
        add_row('field_5b5ef246154f0', $row, $this->get('id'));
        if(!$this->hasProfanity($to)) {
            $receiver = Province::make($to);
            $receiver->update('new_messages', $receiver->get('new_messages')+1);
            $receiver->notify('message', $from);
        }
    }

    function with($userId) { // Convo with the other user
        return ($userId == $this->get('receiver_id') ? $this->get('sender_id') : $this->get('receiver_id'));
    }

    function hasProfanity($user_id=false) {
        if(!$user_id) $user_id = CurrentUser::make()->get('id');
        if($user_id == $this->get('sender_id')) return false;
        if(Format::strHasProfanity($this->getSubject())) return true;
        $messages = $this->getMessages();
        foreach($messages as $msg) {
            if($msg->hasProfanity($user_id)) return true;
        }
        return false;
    }

    function hasNewMessage($userId) {
        $messages = $this->getMessages();
        if(count($messages) == 0) return false;
        if($this->hasProfanity($userId)) return false;
        $lastMsg = end($messages);
        return ($lastMsg->getSender() != $userId && $this->get('general_status') != 'Read');
    }

    function getSubject() {
        return $this->get('subject');
    }

    function getLink($format=false) {
        $link = get_the_permalink($this->get('id')).'/#lastrow';
        $subject = $this->getSubject();
        if(strlen($subject) > 55) $subject = substr($subject, 0, 55) . '...';
        return ($format ? '<a href="'.$link.'">'. $subject .'</a>' : $link);
    }

    function getLastUpdate($format=false) {
        $diff = intval($this->get('last_update_stamp'));
        return ($format ? Format::time_diff($diff).' ago' : $diff);
    }

    function getMessages() {
        $repeater = get_field('sub_messages_rep', $this->get('id'));
        $messages = array();
        if(is_array($repeater) && count($repeater)) {
            foreach($repeater as $post) {
                $messages[] = new Message($post);
            }
        }
        return $messages;
    }

    function getInviteKey() {
        return $this->get('invite_hash');
    }

    function getClanId() {
        return (!empty($this->get('clan_id_invited')) ? $this->get('clan_id_invited') : 0);
    }
}