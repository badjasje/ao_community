<?php
require_once("wp-load.php");

$bot = false;
if((date('H') == '19' && date('i') <= 30) || isset($_GET['file'])) { // Once between 19 and 19:30

    require_once('wp-content/plugins/marketorders/telegrambot.class.php');
    $bot = new TelegramBot();
    $toplistArray = maybe_unserialize(get_field('toplistarray','option'));

    // top 3 pts 24h
    $top324 = array();
    foreach(array_slice($toplistArray['24h_pts'],0,3) as $clanId) {
        $clantag = get_post_meta($clanId, 'clan_tag', true);
        $clantag = str_replace(array("[", "]"), "", $clantag);
        $top324[] = str_pad(get_the_title($clanId),30) .' '.str_pad($clantag,8) .' '.  ceil(get_post_meta($clanId, '24h_pts',true));
    }
    // top 3 nw clans
    $top3nw = array();
    foreach(array_slice($toplistArray['clannetworth'],0,3) as $clanId) {
        $clantag = get_post_meta($clanId, 'clan_tag', true);
        $clantag = str_replace(array("[", "]"), "", $clantag);
        $top3nw[] = str_pad(get_the_title($clanId),30) .' '.str_pad($clantag,8) .' $'. number_format(get_post_meta($clanId, 'clan_networth',true), 0, ',', ' ');
    }
    // top pts clan
    $top3pts = array();
    foreach(array_slice($toplistArray['clanpoints'],0,3) as $clanId) {
        $clantag = get_post_meta($clanId, 'clan_tag', true);
        $clantag = str_replace(array("[", "]"), "", $clantag);
        $top3pts[] = str_pad(get_the_title($clanId),30) .' '.str_pad($clantag,8).' '. get_post_meta($clanId, 'clan_points', true);
    }

    $body = (count($top324) ? "*Clan pts today:*```\n".implode("\n",$top324)."\n```" : '').
        "*Clan nw:*```\n".implode("\n",$top3nw)."\n```".
        "*Clan pts:*```\n".implode("\n",$top3pts)."\n```";
}

$timestamp = current_time('timestamp');
$args = array(
    'meta_key'     	=> 'last_online',
    'orderby'      	=> 'meta_value_num',
    'meta_value'	=> $timestamp-259200, //last 3 days
    'meta_compare'	=> '>',
);
$users = get_users($args);
foreach ($users as $user) {
    $user_ID = $user->data->ID;
    count_all_stats($user_ID);

    if($bot !== false && !empty($body)) {
        if(strpos($_SERVER['SERVER_NAME'], 'assault') !== 0 && $user_ID != 2768) continue; // On dev only to me please
        if($bot->getChatByUserId($user_ID)) {
            $bot->sendMessage($body, array('parse_mode' => 'markdown'));
        }
    }
}

$args = array(
    'post_type'     =>  'clan',
    'posts_per_page' => -1,
);
$clans = get_posts($args);
foreach ($clans as $clan) {
    $clan_members = get_post_meta($clan->ID, 'clan_members');

    $tot_networth = 0;
    foreach ($clan_members[0] as $member) {
	    $status = get_user_meta($member, 'status', true);
	    if($status == 'dead'){
		    $networth = 3500;
	    }else{
        	$networth = get_user_meta($member, 'networth', true);
        }
        $tot_networth+=$networth;
    }
    update_post_meta($clan->ID, 'clan_networth', ceil($tot_networth));
}