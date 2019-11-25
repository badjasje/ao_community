<?php
class Log {
// @todo: dedicated log directory
// @todo: log more, attacks, etc
    static $logs = array();
    static $files = array(
        'clan aid'      => 'aidlog.txt',
        'clan bonus'    => 'bonuslog.txt',
        'land explore'  => 'explorelog.txt',
        'land sell'     => 'landselllog.txt',
        'market sell'   => 'marketselllog.txt',
        'market order'  => 'marketlog.txt',
        'turn build'    => 'turnbuildlog.txt',
    );

    public static function add($key, $data=array()) {
        if(empty($data)) return;
        $data['time'] = current_time('G:i:s | d-m-Y');
        if(!isset(self::$logs[$key])) self::$logs[$key] = array();
        self::$logs[$key][] = $data;
        if(isset(self::$files[$key])) {
            $content = '';
            foreach($data as $data_key => $data_value) $content .= $data_key.': '.$data_value.PHP_EOL;
            self::write(self::$files[$key], $content);
        }
    }

    public static function write($file, $content) {
        $path = SERVER_ROOT.'/'.$file;
        $current = file_get_contents($path);
        $current .= $content."\n\n";
        file_put_contents($path, $current);
    }

    public static function get() {
        return self::$data;
    }
}