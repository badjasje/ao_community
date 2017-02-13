<?php

if (!defined('ABSPATH')) exit;

echo '<h1 class="main-title">'.__('Search', 'asgaros-forum').'</h1>';

$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_forum_id = get_post_meta($clan_id_user, 'clan_forum_id', true);

$results = $this->getSearchResults();


if ($results) {
    echo '<div>'.$this->pageing('search').'<div class="clear"></div></div>';
}

echo '<div class="title-element">';
    echo __('Search results:', 'asgaros-forum').' '.AsgarosForumSearch::$searchKeywords;
    echo '<span class="last-post-headline">'.__('Last post:', 'asgaros-forum').'</span>';
echo '</div>';
echo '<div class="content-element">';

if ($results) {
    $elementMarker = '';
    $elementsCounter = 0;
    foreach ($results as $thread) {
	    if($thread->parent_id == 1 || $thread->parent_id == 2){
	    $elementsCounter++;
        $elementMarker = ($elementsCounter & 1) ? 'odd' : 'even';
        require('thread-element.php');}
    }
} else {
    echo __('No results found for:', 'asgaros-forum').' <b>'.AsgarosForumSearch::$searchKeywords.'</b>';
}

echo '</div>';
