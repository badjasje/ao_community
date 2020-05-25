<?php
class Format extends PhpObject {

    public static function money($n) { // @todo: We could put an euro here sometime for fun
        return sprintf('$ %s', self::number($n));
    }

    public static function number($n) {
        return number_format($n, 0, ',', ' ');
    }

    public static function networth($networth) {
        return self::money(ceil($networth));
    }

    public static function power($n) {
        return self::number($n) .'%';
    }

    public static function morale($n) {
        return self::number($n) .'%';
    }

    public static function land($n) {
        return self::number($n) .'m<sup>2</sup>';
    }

    public static function turns($n) {
        return self::number($n);
    }

    public static function points($n) {
        return intval($n);
    }

    public static function position($n) {
        return self::number($n);
    }

    public static function date($timestamp, $format='H:i:s d F Y') {
        return date_i18n($format, $timestamp);
    }

    public static function time_diff($timestamp1, $timestamp2=null) {
        return human_time_diff($timestamp1, (!!$timestamp2 ? $timestamp2 : current_time('timestamp')));
    }

    public static function isEaster() {
        $easter = strtotime('21-03-'.date('Y').' +'.easter_days().' day');
        if(in_array(date('d-m-Y'), array(date('d-m-Y', $easter), date('d-m-Y', strtotime('+1 day', $easter))))) return true;
        return false;
    }

    public static function strHasProfanity($text) {
        $blacklist = file(SERVER_ROOT.'/wordpress-comment-blacklist-words-list.txt');
        if(is_array($blacklist)) {
            $test_text = str_replace(array('-','_'), ' ', $text);
            if(preg_match('^(fuck|bitch|cock)^mi', $test_text)) return true; // part of word filter
            foreach($blacklist as $word) {
                if(empty($word) || trim($word) == '' || substr($word,0,2) == '##') continue;
                if(preg_match('^\b'.trim($word).'\b^mi', $test_text)) return true;
            }
        }
        return false;
    }

    public static function time_elapsed($timestamp, $level = 1) {
        if(!$timestamp) return 'never';
        $now = new DateTime;
        $diff = $now->diff(date_create('@'.$timestamp));

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

    // 12 units: Format::plural(12, 'unit');
    // 1 unit: Format::plural(1, 'unit');
    // 12 spies: Format::plural(12, 'spy', 'spies');
    // 1 spy: Format::plural(1, 'spy', 'spies');
    public static function plural($n=0, $str='', $pluralstr='') {
        if(empty($pluralstr)) return $n.' '. $str . ($n == 0 || $n > 1 ? 's' : '');
        return $n .' '. ($n == 0 || $n > 1 ? $pluralstr : $str);
    }
}
