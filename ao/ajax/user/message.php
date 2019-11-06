<?php

function ajax_message($user, $return) {
    $receiver_ID = Request::post('receiver');
    $receiver = User::make($receiver_ID);
    if($receiver->get('id') == false) {
        return array('status' => 'Not a user');
    }
    $message_ID = Request::post('main_message');
    $message_text = Request::post('message');
    if(ctype_space($message_text) || $message_text == '') {
        return array('status' => 'Message is empty');
    }
    if($message_ID == 'first') {
        $title = Request::post('title');
        if(ctype_space($title) || $title == '') {
            return array('status' => 'Title is empty');
        }
        $conv = Conversation::create($user->get('id'), $receiver->get('id'), $title, $message_text);
        $link = Request::siteUrl().'/conversations';
    } else {
        $conv = Conversation::make($message_ID);
        $link = $conv->getLink();
    }
    $conv->addMessage($user->get('id'), $receiver->get('id'), $message_text);
    return array_merge($return, array('success' => true, 'status' => 'Message sent to '.$receiver->getName(), 'redirect' => $link));
}