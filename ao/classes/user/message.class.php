<?php

class Message extends PhpObject {

    function getSender() {
        return $this->get('sender_id_rep');
    }

    function getReceiver() {
        return $this->get('receiver_id_rep');
    }

    function getText($format=false) {
        $s = trim($this->get('message_rep'));
        return ($format == true ? str_replace("\r", "<br />", $s) : $s);
    }

    function getDate($format=false) {
        $n = $this->get('message_date_rep');
        return ($format == true && intval($n) > 0 ? Format::time_diff($n).'  ago' : $n);
    }

    function hasProfanity($user_id=false) {
        if(!$user_id) $user_id = CurrentUser::make()->get('id');
        if($user_id == $this->getSender()) return false;
        $text = $this->getText(false);
        if(empty($text)) return false;
        return Format::strHasProfanity($text);
    }
}