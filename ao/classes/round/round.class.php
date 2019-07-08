<?php
class Round extends DataObject {

    static $data = false;

    public static function init() { // @wp
        static::$data = array(
            'start_date' => strtotime(get_field('starting_date','options')),
            'end_date' => strtotime(get_field('end_date','option')),
            'time_left' => strtotime(get_field('end_date','option')) - current_time('timestamp'),
            'new_round_start' => get_field('new_round_start','option'), // plain text
            'type' => strtolower(get_field('game_type','option')), // regular, speed, test, development
            'status' => strtolower(get_field('game_status','option')), // live, pause
        );
    }

    public static function startDate($format=false) {
        return ($format ? Format::date(static::$data['start_date']) : static::$data['start_date']);
    }

    public static function endDate($format=false) {
        return ($format ? Format::date(static::$data['end_date'],'d F Y') : static::$data['end_date']); // random time
    }

    public static function nextRoundStartDate() {
        return static::$data['new_round_start']; // returns string "the 2nd of May @ 10:00 server time"
    }

    public static function isDev() {
        return static::$data['type'] == 'development';
    }
    public static function isTest() {
        return static::$data['type'] == 'test';
    }
    public static function isSandbox() {
        return static::$data['type'] == 'sandbox';
    }

    public static function isLive() {
        return static::$data['status'] == 'live';
    }
    public static function isPaused() {
        return static::$data['status'] == 'pause';
    }
}