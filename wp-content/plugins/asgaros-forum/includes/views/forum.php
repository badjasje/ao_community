<?php

if (!defined('ABSPATH')) exit;

echo '<h1 class="main-title">'.esc_html(stripslashes($this->get_name($this->current_forum, $this->tables->forums))).'</h1>';

$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_forum_id = get_post_meta($clan_id_user, 'clan_forum_id', true);
$current_cat = $this->current_category;
if($clan_forum_id != $current_cat && $current_cat != 252){
	wp_redirect(get_permalink(3486)); exit;
}

?>

<div>
    <?php
    $pageing = ($counter_normal > 0) ? $this->pageing($this->tables->topics) : '';
    echo $pageing;
    ?>
    <div class="forum-menu"><?php echo $this->forum_menu('forum'); ?></div>
    <div class="clear"></div>
</div>

<?php
// Subforums
$subforums = $this->get_forums($this->current_category, $this->current_forum);
if (count($subforums) > 0) {
    echo '<div class="title-element">';
        echo __('Subforums', 'asgaros-forum');
        echo '<span class="last-post-headline">'.__('Last post:', 'asgaros-forum').'</span>';
    echo '</div>';
    echo '<div class="content-element">';
    $elementMarker = '';
    $forumsCounter = 0;
    foreach ($subforums as $forum) {
        $forumsCounter++;
        $elementMarker = ($forumsCounter & 1) ? 'odd' : 'even';
        require('forum-element.php');
    }
    echo '</div>';
}

if ($counter_total > 0) {
    echo '<div class="title-element">';
        echo __('Topics', 'asgaros-forum');
        echo '<span class="last-post-headline">'.__('Last post:', 'asgaros-forum').'</span>';
    echo '</div>';
    echo '<div class="content-element">';
        // Sticky threads
        if ($sticky_threads && !$this->current_page) { ?>
            <?php
            $elementMarker = '';
            $elementsCounter = 0;
            foreach ($sticky_threads as $thread) {
                $elementsCounter++;
                $elementMarker = ($elementsCounter & 1) ? 'odd' : 'even';
                require('thread-element.php');
            }
        }

        if ($counter_normal > 0 && (($sticky_threads && !$this->current_page))) {
            echo '<div class="sticky-bottom"></div>';
        }

        $elementMarker = '';
        $elementsCounter = 0;
        foreach ($threads as $thread) {
            $elementsCounter++;
            $elementMarker = ($elementsCounter & 1) ? 'odd' : 'even';
            require('thread-element.php');
        } ?>
    </div>

    <div>
        <?php echo $pageing; ?>
        <div class="forum-menu"><?php echo $this->forum_menu('forum'); ?></div>
        <div class="clear"></div>
    </div>
<?php } else {
    echo '<div class="title-element">'.esc_html(stripslashes($this->get_name($this->current_forum, $this->tables->forums))).'</div>';
    echo '<div class="content-element">';
    echo '<div class="notice">'.__('There are no topics yet!', 'asgaros-forum').'</div>';
    echo '</div>';
}

AsgarosForumNotifications::showForumSubscriptionLink();

?>
