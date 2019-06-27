<?php
class Market extends PhpObject {

    public static function closeTime() {
        return Round::endDate() - (24*60*60); // last 24 hours
    }

    public static function timeLeft() {
        return static::closeTime() - current_time('timestamp'); // returns seconds, @wp
    }

    public static function isOpen() {
        return static::timeLeft() > 0;
    }

    public function buy() { // order

    }

    public function sell() {

    }
}