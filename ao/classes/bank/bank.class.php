<?php
class Bank extends PhpObject {

    public static function closeTime() {
        return Round::endDate() - (3*24*60*60); // last 3 days
    }

    public static function timeLeft() {
        return static::closeTime() - current_time('timestamp'); // returns seconds, @wp
    }

    public static function isOpen() {
        return static::timeLeft() > 0;
    }

    // We might make this dynamic like real banks >:-)
    public static function getRates($all=false) {
        $rates = array(
            '3' => 1,
            '4' => 1.5,
            '5' => 2,
            '6' => 2.5,
            '7' => 3,
            '8' => 3.5,
            '9' => 4,
            '10' => 4.5
        );
        if($all) return $rates; // When calculating final amount of a deposit, we want do want the 10 days rate
        $daysleft = floor(self::timeLeft()/60/60/24);
        if($daysleft < 3) return array();
        $return = array();
        foreach(range(3, min($daysleft, 10)) as $length) {
            $return[$length] = $rates[$length];
        }
        return $return;
    }
}
