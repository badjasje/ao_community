<?php
require_once("wp-load.php");

$user = CurrentUser::make();
if(!$user->isAdmin()) die();

$timestamp = current_time('timestamp');
$args = array('meta_query'=> array(array(
    'relation' => 'AND',
    array('key' => 'last_online', 'value' => $timestamp-1728000, 'compare' => ">", 'type' => 'numeric'),
    array('key' => 'networth', 'value' => 10, 'compare' => ">", 'type' => 'numeric'),
)));
$users = get_users($args);

$title = (isset($_GET['title']) ? $_GET['title'] : '');
$message_text = (isset($_GET['message']) ? $_GET['message'] : '');
if(ctype_space($title) || $title == '') die('Empty title');
if(ctype_space($message_text) || $message_text == '') die('Message is empty');

foreach ($users as $receiver) {
    $conv = Conversation::create($user->get('id'), $receiver->ID, $title, $message_text);
    $conv->addMessage($user->get('id'), $receiver->ID, $message_text);
}
