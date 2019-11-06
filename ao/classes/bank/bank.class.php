<?php
class Bank extends PhpObject {

    public static function closeTime() {
        return Round::endDate() - (3*24*60*60); // last 3 days
    }

    public static function timeLeft() {
        return static::closeTime() - current_time('timestamp'); // returns seconds, @wp
    }

    public static function isOpen() {
        return  (Round::isPaused() ? false : floor(static::timeLeft()/60/60/24) > 0);
    }

    public static function getAllRates() {
        return array(
            '3' => array('interest' =>  1.010),
            '4' => array('interest' =>  1.015),
            '5' => array('interest' =>  1.02),
            '6' => array('interest' =>  1.025),
            '7' => array('interest' =>  1.03),
            '8' => array('interest' =>  1.035),
            '9' => array('interest' =>  1.04),
            '10' => array('interest' =>  1.045),
        );
    }

    // We might make this dynamic like real banks >:-)
    public static function getRates($all=false) {
        $rates = array();
        foreach(self::getAllRates() as $k => $r) {
            $rates[$k] = ($r['interest']-1)*100;
        }
        if($all) return $rates; // When calculating final amount of a deposit, we want do want the 10 days rate
        $daysleft = floor(Round::timeLeft()/60/60/24);
        if($daysleft <= 3) return array();
        $return = array();
        foreach(range(3, min($daysleft, 10)) as $length) {
            $return[$length] = $rates[$length];
        }
        return $return;
    }
}
