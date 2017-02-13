<?php

if (!defined('ABSPATH')) exit;

$forum_counter = 0;
$forum_ID = get_the_ID();

/* public forum query */
if($forum_ID == 3813){
	foreach ($categories as $category) { 
		if($category->term_id == 133){ ?>
		<div class="title-element" id="forum-category-<?php echo $category->term_id; ?>"><?php echo $category->name; ?></div>
		<div class="content-element space">
        <?php
        $frs = $this->get_forums($category->term_id);
        if (count($frs) > 0) {
            foreach ($frs as $forum) {
                $forum_counter++;
                require('forum-element.php');
            }
        } else { ?>
            <div class="notice"><?php _e('In this category are no forums yet!', 'asgaros-forum'); ?></div>
        <?php } ?>
    </div>

<?php }}}

/* clan forum query */

$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_forum_id = get_post_meta($clan_id_user, 'clan_forum_id', true);

foreach ($categories as $category) {
	if($category->term_id == $clan_forum_id){ ?>
	<h2>Clan forum</h2>
    <div class="title-element" id="forum-category-<?php echo $category->term_id; ?>">Clan Forum <?php echo $category->name; ?></div>
    <div class="content-element space">
        <?php
        $frs = $this->get_forums($category->term_id);
        if (count($frs) > 0) {
            foreach ($frs as $forum) {
                $forum_counter++;
                require('forum-element.php');
            }
        } else { ?>
            <div class="notice"><?php _e('In this category are no forums yet!', 'asgaros-forum'); ?></div>
        <?php } ?>
    </div>
<?php

	}}
	

if ($forum_counter > 0) {
    AsgarosForumUnread::showUnreadControls();
}

?>
