<?php
require_once("wp-load.php");
nocache_headers();

/*CREATE TABLE `telegram_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `auth_key` varchar(64) NOT NULL,
  `step` varchar(16) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT NOW(),
  `modified_date` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
);
Add "telegram_key" to custom field group
*/

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $bot = new TelegramBot();
    $bot->error($errstr.' in '.$errfile.' on line '.$errline);
});

function get_user_by_authkey($key) {
    $users = get_users(array('meta_key' => 'telegram_key', 'meta_value' => $key));
    $user = is_array($users) && count($users) ? reset($users) : false;
    if(is_object($user)) $data = get_userdata($user->ID);
    return is_object($data) ? $data : false;
}

// Receive message from user
if(isset($_GET['path']) && $_GET['path']=='Chiricahua1829Goyahkla') {
    $bot = new TelegramBot();
    /*if($_GET['debug']==1) {
        $message['text'] = '/claninfo';
        $bot->getChatByUserId(2768);
    }
    else {*/
        $message = $bot->receiveMessage();
    //}
    $telegramChat = $bot->getChat();

    if($message['text']) {
		$msg = trim($message['text']);
		if(substr($msg,0,1) == '/') { // It is a command
            $c = substr($msg,1);
			if(strstr($c,' ')!==false) list($c,$t) = explode(' ',$c,2);
			$commands = $bot->getCommands();
			if(in_array($c, $commands)) {

                $user = false;
                if(in_array($c, array('summary','claninfo'))) {
                    if(!isset($telegramChat['auth_key']) || empty($telegramChat['auth_key'])) {
                        $bot->sendMessage("Please connect your AO account first using /start");
                    }
                    else {
                        $user = get_user_by_authkey($telegramChat['auth_key']);
                        if(!$user) $bot->sendMessage("Your key is invalid, please connect again via the /start command");
                    }
                }

                switch($c) {
                    case 'help':
                        $bot->sendMessage("Use /start connect this chat to your AO account\nYou can then use /summary and /claninfo");
                    break;
                    case 'start':
                        $bot->updateChat(array('step'=>'start'));
                        $bot->sendMessage("Enter the code you can find on <a href='https://assault.online'>your profile page</a>",
                            array('force_reply' => true, 'parse_mode' => 'html'));
                    break;
                    case 'summary':
                        if($user) {
                            $reply = array();
                            $data = get_user_meta($user->ID, '', true);
                            if(isset($data['money']) && is_numeric($data['money'][0])) $reply['money'] = '$ '. number_format($data['money'][0],0,'.',' ');
                            if(isset($data['networth']) && is_numeric($data['networth'][0])) $reply['nw'] = '$ '.number_format($data['networth'][0],0,'.',' ');
                            if(isset($data['turns']) && is_numeric($data['turns'][0])) $reply['turns'] = $data['turns'][0];
                            if(isset($data['morale']) && is_numeric($data['morale'][0])) $reply['morale'] = round($data['morale'][0]) .'%';
                            if(isset($data['land']) && is_numeric($data['land'][0])) $reply['land'] = number_format($data['land'][0],0,'.',' ') .'m2';
                            if(isset($data['power']) && is_numeric($data['power'][0])) $reply['power'] = round($data['power'][0]) .'%';
                            if(is_array($reply) && count($reply) > 0) {
                                array_walk($reply, function(&$i,$k) { return $i=" $k: $i"; });
                                $bot->sendMessage(implode(', ',$reply));
                            } else $bot->sendMessage("Cannot get stats, sorry");
                        }
                    break;
                    case 'claninfo':
                        if($user) {
                            $userData = get_user_meta($user->ID, '', true);
                            $clan_ID = $userData['clan_id_user'][0];
                            if(is_numeric($clan_ID) && $clan_ID > 0) {
                                $clanData = get_post_meta($clan_ID);
                                $clan_members = array();
                                if(is_array($clanData) && isset($clanData['clan_members'])) {
                                    $clan_members = maybe_unserialize($clanData['clan_members'][0]);
                                }
                                if(is_array($clan_members) && count($clan_members) > 0) {
                                    $all = array();
                                    foreach ($clan_members as $key => $member) {
                                        $data = get_user_meta($member, '', true);
                                        $member_data = get_userdata($member);
                                        $name = $member_data->display_name;

                                        $reply = array();
                                        if(isset($data['money']) && is_numeric($data['money'][0])) $reply['money'] = '$ '. number_format($data['money'][0],0,'.',' ');
                                        if(isset($data['networth']) && is_numeric($data['networth'][0])) $reply['nw'] = '$ '.number_format($data['networth'][0],0,'.',' ');
                                        if(isset($data['turns']) && is_numeric($data['turns'][0])) $reply['turns'] = $data['turns'][0];
                                        if(isset($data['morale']) && is_numeric($data['morale'][0])) $reply['morale'] = round($data['morale'][0]) .'%';
                                        if(isset($data['land']) && is_numeric($data['land'][0])) {
                                            $reply['land'] = number_format($data['land'][0],0,'.',' ') .'m2';
                                            $reply['freeLand'] = number_format($data['land'][0]-$data['builtland'][0], 0, ',', ' ') .'m2';
                                        }
                                        if(isset($data['power']) && is_numeric($data['power'][0])) $reply['power'] = round($data['power'][0]) .'%';
                                        if(is_array($reply) && count($reply) > 0) {
                                            array_walk($reply, function(&$i,$k) { return $i=" $k: $i"; });
                                            $all[] = '<b>'.$name.'</b>'. implode(', ', $reply);
                                        }
                                    }
                                    if(is_array($all) && count($all) > 0) {
                                        $bot->sendMessage(implode("\n", $all), array('parse_mode' => 'html'));
                                    } else $bot->sendMessage("Cannot get claninfo, sorry");
                                } else $bot->sendMessage("You are not in a clan");
                            } else $bot->sendMessage("You are not in a clan");
                        }
                    break;
                }
            } else $bot->sendMessage("See /help  for commands");
        } else {
            if(!empty($telegramChat['step'])) {
                switch($telegramChat['step']) {
                    case 'start':
                        if($user = get_user_by_authkey($msg)) {
                            $bot->updateChat(array('step' => '0', 'auth_key' => $msg, 'user_id' => $user->ID));
                            $bot->sendMessage("Thanks ".$user->data->display_name."! I'll try to keep you updated. You can now use /summary and /claninfo",
                                array('reply_markup' => array('hide_keyboard' => true)));
                        } else $bot->sendMessage("No such user found.");
                    break;
                }
            } else {
                $bot->sendMessage("See /help for commands");
            }
        }
    }

    // We might want to save this user's personal data, I don't know why and what for yet.
    if(isset($message['from']) && $telegramChat && $telegramChat['username']!=$message['from']['username']) {
        $bot->updateChat(array(
            'first_name' => $message['from']['first_name'],
            'last_name' => $message['from']['last_name'],
            'username' => $message['from']['username'])
        );
	}
} else wp_redirect(get_permalink(3486));
