<?php
class Round extends DataObject {

    static $data = false;

    public static function init() { // @wp
        global $wpdb;
        
        $config = array();
        $fields = array('starting_date','end_date','new_round_start','game_type','game_status','golden_shotgun','round_nr');
        foreach($fields as $key) $config[$key] = false; // prefill

        $options = $wpdb->get_results("SELECT `option_name`,`option_value` FROM `{$wpdb->prefix}options` WHERE `option_name` IN ('options_".implode("','options_",$fields)."')", ARRAY_A);
        foreach ($options as $option) {
            $config[str_replace('options_','', $option['option_name'])] = $option['option_value'];
        }

        static::$data = array(
            'start_date' => strtotime($config['starting_date']),
            'end_date' => strtotime($config['end_date']),
            'time_left' => strtotime($config['end_date']) - current_time('timestamp'),
            'new_round_start' => $config['new_round_start'], // plain text
            'type' => strtolower($config['game_type']), // regular, speed, test, development
            'status' => strtolower($config['game_status']), // live, pause
            'golden_shotgun' => $config['golden_shotgun'],
            'round_nr' => $config['round_nr'],
        );
    }

    public static function setGoldenShotgun($clan_id=0) {
        update_field('golden_shotgun', $clan_id, 'option');
        static::$data['golden_shotgun'] = $clan_id;
        if($clan_id > 0) {
            // @todo: use Award::create()
            $args = array('post_title' => 'Golden Shotgun', 'post_status' => 'publish', 'post_type' => 'award', 'post_author' => 1);
            $newAwardId = wp_insert_post($args);
            update_field('round', 'Beta round ' . Round::getRoundNr(), $newAwardId);
            update_field('winning_clan', $clan_id, $newAwardId);
            update_field('position_clan', 'Gold', $newAwardId);
        }
    }

    public static function getGoldenShotgun() {
        return static::$data['golden_shotgun'];
    }

    public static function getRoundNr() {
        if(empty(static::$data['round_nr'])) {
            update_field('round_nr', 28, 'option');
            static::$data['round_nr'] = 28;
        }
        return static::$data['round_nr'];
    }

    public static function startDate($format=false) {
        return ($format ? Format::date(static::$data['start_date']) : static::$data['start_date']);
    }

    public static function endDate($format=false) {
        return ($format ? Format::date(static::$data['end_date'],'d F Y') : static::$data['end_date']); // random time
    }

    public static function timeLeft($format=false) {
        return static::$data['time_left'];
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