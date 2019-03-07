<?php
class TelegramBot {

    protected $token = '637734574:AAEiW8vlPS1fqjtt_kf3SHLNuQr5LwxOG6E';
    protected $apiUrl = 'https://api.telegram.org/bot';
    protected $handle;
    protected $netDelay = 1;
    protected $netTimeout = 10;
    protected $netConnectTimeout = 5;

    private $chat = false;
    private $chatID = false;
    private $commands = array('start','help','summary','claninfo'); //'','lastmsg','lastevent'

    function __construct() {

    }

    public function receiveMessage() {
        $content = file_get_contents("php://input");
        if(empty($content)) { $this->error('No input'); return false; }
        $update = json_decode($content, true);
        if(!is_array($update)) { $this->error('Invalid input'); return false; }
        if(!isset($update["message"]["chat"]["id"])) { $this->error('Invalid array'); return false; }
        $this->chatID = $update["message"]["chat"]["id"];
        if(!empty($this->chatID)) $this->getChat();
        return $update["message"];
    }

    public function getChat() {
        if(empty($this->chat) && !empty($this->chatID)) {
            global $wpdb;
            if($chat = $wpdb->get_row($wpdb->prepare("SELECT * FROM `telegram_chat` WHERE chat_id=%s", $this->chatID), ARRAY_A)) {
                $this->chat = $chat;
            } else $this->chat = $this->newChat();
        }
        return $this->chat;
    }

    public function getChatByUserId($user_id) {
        global $wpdb;
        if($chat = $wpdb->get_row($wpdb->prepare("SELECT * FROM `telegram_chat` WHERE user_id=%s", $user_id), ARRAY_A)) {
            $this->chat = $chat;
            $this->chatID = $chat['chat_id'];
        }
        return $this->chat;
    }

    private function newChat() {
        global $wpdb;
        if($wpdb->insert('telegram_chat', array('chat_id' => $this->chatID))) {
            $this->chat = array('id' => $wpdb->insert_id, 'chat_id' => $this->chatID, 'step' => '0');
        }
        return $this->chat;
    }

    public function updateChat($data) {
        if(empty($this->chatID)) return false;
        global $wpdb;
        return $wpdb->update('telegram_chat', $data, array('chat_id' => $this->chatID));
    }

    public function sendMessage($msg, $params = array()) {
        if(empty($this->chatID)) return false;
        $params += array('chat_id' => $this->chatID,'text' => $msg);
        return $this->request('sendMessage', $params);
    }

    public function request($method, $params = array(), $options = array()) {
        $options += array('http_method' => 'GET', 'timeout' => $this->netTimeout);
        $params_arr = array();
        foreach ($params as $key => &$val) {
            if (!is_numeric($val) && !is_string($val)) $val = json_encode($val);
            $params_arr[] = urlencode($key).'='.urlencode($val);
        }
        $query_string = implode('&', $params_arr);
        $url = $this->apiUrl.$this->token.'/'.$method;

        $this->handle = curl_init();
        if ($options['http_method'] === 'POST') {
            curl_setopt($this->handle, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($this->handle, CURLOPT_POST, true);
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, $query_string);
        } else {
            $url .= ($query_string ? '?'.$query_string : '');
            curl_setopt($this->handle, CURLOPT_HTTPGET, true);
        }
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, $this->netConnectTimeout);
        curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->netTimeout);
        $response_str = curl_exec($this->handle);
        $errno = curl_errno($this->handle);
        $http_code = intval(curl_getinfo($this->handle, CURLINFO_HTTP_CODE));

        if ($http_code == 401) {
            $this->error('Invalid token');
        } else if ($http_code >= 500 || $errno) {
            $this->error('Error: '.$errno);
        }
        $response = json_decode($response_str, true);
        if(!$response['ok']) $this->error($response_str);
        return $response;
    }

    public function log($msg) {
        var_dump($msg);
        /*$fh = fopen('bot_debug.log', 'w') or die("can't open file");
        fwrite($fh,  date('Y-m-d H:i:s') .': ' . $msg . PHP_EOL);
        fclose($fh);*/
    }

    public function error($msg) {
        var_dump($msg);
        /*$fh = fopen('bot_error.log', 'w') or die("can't open file");
        fwrite($fh, date('Y-m-d H:i:s') .': ' . $msg . PHP_EOL);
        fclose($fh);*/
    }

    public function getCommands() {
        return $this->commands;
    }
}