<?php

if (!defined('ABSPATH')) exit;

echo '<h1 class="main-title">'.__('Forum', 'asgaros-forum').'</h1>';
$forum_counter = 0;
$forum_ID = get_the_ID();

$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_forum_id = get_post_meta($clan_id_user, 'clan_forum_id', true);


if($forum_ID == 3813){
if ($categories) {
    $forumsAvailable = false;

    foreach ($categories as $category) {
	    if($category->term_id == 133){ 
        echo '<div class="title-element" id="forum-category-'.$category->term_id.'">';
            echo $category->name;
            echo '<span class="last-post-headline">'.__('Last post:', 'asgaros-forum').'</span>';
        echo '</div>';
        echo '<div class="content-element">';
            $forums = $this->get_forums($category->term_id);
            if (empty($forums)) {
                echo '<div class="notice">'.__('In this category are no forums yet!', 'asgaros-forum').'</div>';
            } else {
                $elementMarker = '';
                $forumsCounter = 0;
                foreach ($forums as $forum) {
                    $forumsAvailable = true;
                    $forumsCounter++;
                    $elementMarker = ($forumsCounter & 1) ? 'odd' : 'even';
                    require('forum-element.php');
                }
            }
        echo '</div>';
    }}

    if ($forumsAvailable) {
        AsgarosForumUnread::showUnreadControls();
    }

    AsgarosForumStatistics::showStatistics();
} else {
    echo '<div class="notice">'.__('There are no categories yet!', 'asgaros-forum').'</div>';
}

echo '<h2>Clan forum</h2>';
if ($categories) {
    $forumsAvailable = false;

    foreach ($categories as $category) {
	    if($category->term_id == $clan_forum_id){ 
        echo '<div class="title-element" id="forum-category-'.$category->term_id.'">';
            echo $category->name;
            echo '<span class="last-post-headline">'.__('Last post:', 'asgaros-forum').'</span>';
        echo '</div>';
        echo '<div class="content-element">';
            $forums = $this->get_forums($category->term_id);
            if (empty($forums)) {
                echo '<div class="notice">'.__('In this category are no forums yet!', 'asgaros-forum').'</div>';
            } else {
                $elementMarker = '';
                $forumsCounter = 0;
                foreach ($forums as $forum) {
                    $forumsAvailable = true;
                    $forumsCounter++;
                    $elementMarker = ($forumsCounter & 1) ? 'odd' : 'even';
                    require('forum-element.php');
                }
            }
        echo '</div>';
    }}

    if ($forumsAvailable) {
        AsgarosForumUnread::showUnreadControls();
    }

} else {
    echo '<div class="notice">'.__('There are no categories yet!', 'asgaros-forum').'</div>';
}












}