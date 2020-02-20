<?php

// Placeholder for if we ever want to translate the interface
// This will use a translation class at some point
function t($str) {
    return $str;
}

// Easy debug
function wtf() {
	array_map(function($x) {
        if(is_object($x)||is_array($x)) echo '<pre>'.print_r($x,1).'</pre>'.PHP_EOL;
        else { var_dump($x); echo '<br>'.PHP_EOL; }
    }, func_get_args());
}

// Shuffle associative and non-associative array while preserving key, value pairs.
function shuffle_assoc($list) {
    $new = array();
    $keys = array_keys($list);
    shuffle($keys);
    foreach($keys as $key) {
        $new[$key] = $list[$key];
    }
    return $new;
}

// Get one random item out of an array
function array_rand_item($array) {
    $randomIndex = array_rand($array);
    $randomElement = $array[$randomIndex];
    return $randomElement;
}

function implode_assoc($glue, $array, $format='%s (%s)') {
    return implode(', ', array_map(function ($k, $v) use ($format) {
        return sprintf($format, $k, $v);
    }, array_keys($array), array_values($array)));
}