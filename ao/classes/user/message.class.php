<?php

class Message extends PhpObject {

    function getSender() {
        return $this->get('sender_id_rep');
    }

    function getReceiver() {
        return $this->get('receiver_id_rep');
    }

    function getText($format=false) {
        $s = $this->get('message_rep');
        return ($format == true ? str_replace("\r", "<br />", $s) : $s);
    }

    function getDate($format=false) {
        $n = $this->get('message_date_rep');
        return ($format == true && intval($n) > 0 ? Format::time_diff($n).'  ago' : $n);
    }
}