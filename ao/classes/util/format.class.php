<?php
class Format extends PhpObject {

    public static function money($n) { // @todo: We could put an euro here sometime for fun
        return sprintf('$ %s', number_format($n, 0, ',', ' '));
    }

    public static function networth($networth) {
        return self::money(ceil($networth));
    }

    public static function power($n) {
        return number_format($n, 0, ',', ' ') .'%';
    }

    public static function morale($n) {
        return number_format($n, 0, ',', ' ') .'%';
    }

    public static function land($n) {
        return number_format($n, 0, ',', ' ') .'m<sup>2</sup>';
    }

    public static function turns($n) {
        return number_format($n, 0, ',', ' ');
    }

    public static function points($n) {
        return $n;
    }

    public static function position($n) {
        return number_format($n, 0, ',', ' ');
    }

    public static function date($timestamp, $format='H:i:s d F Y') {
        return date_i18n($format, $timestamp);
    }

    public static function time_diff($timestamp1, $timestamp2=null) {
        return human_time_diff($timestamp1, (!!$timestamp2 ? $timestamp2 : current_time('timestamp')));
    }

    public static function time_elapsed($datetime, $level = 1) {
        if(!$datetime) { return 'never'; }
        $now = new DateTime;
        $diff = $now->diff(date_create($datetime));

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
        foreach ($string as $k => &$v) {
            if ($diff->$k) $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            else unset($string[$k]);
        }

        $string = array_slice($string, 0, $level);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    // I don't know if we ever gonna use this
    public static function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) return 'th';
        else return $ends[$number % 10];
    }

}
