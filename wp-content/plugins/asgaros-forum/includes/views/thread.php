<?php

if (!defined('ABSPATH')) exit;

$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);
$clan_forum_id = get_post_meta($clan_id_user, 'clan_forum_id', true);
$current_cat = $this->current_category;
if($clan_forum_id != $current_cat && $current_cat != 252){
	wp_redirect(get_permalink(3486)); exit;
}
echo '<h1 class="main-title">'.esc_html(stripslashes($this->get_name($this->current_topic, $this->tables->topics))).'</h1>';

?>
<div>
    <?php
    $pageing = $this->pageing($this->tables->posts);
    echo $pageing;
    ?>
    <div class="forum-menu"><?php echo $this->forum_menu('thread');?></div>
    <div class="clear"></div>
</div>

<div class="title-element"><?php echo $meClosed; ?></div>
<div class="content-element">
    <?php
    $counter = 0;
    $avatars_available = get_option('show_avatars');
    $threadStarter = $this->get_thread_starter($this->current_topic);
    foreach ($posts as $post) {

        $counter++;
        ?>
        <div class="post" id="postid-<?php echo $post->id; ?>">
            <div class="post-header">
                <div class="post-date"><?php echo $this->format_date($post->date); ?></div>
                <?php echo $this->post_menu($post->id, $post->author_id, $counter); ?>
                <div class="clear"></div>
            </div>
            <div class="post-content">
                <div class="post-author">
                    <?php

                    if ($avatars_available) {?>
                    <center>
                    
                    
			<?php echo small_avatar($post->author_id,'forumAvatar');?>
             </center>
                    <?php }
                    ?>
                    <?php $member_data = get_userdata($post->author_id);?>
                    <strong><a class="font-weight:bold;"href="/users/profile/?id=<?php echo $post->author_id;?>"><?php echo $member_data->display_name.' (#'.$post->author_id.')';?></a></strong><br />
                    <?php
                    // Only show post-counter for existent users.
                    if (get_userdata($post->author_id) != false) {
                        echo '<small>'.sprintf(_n('%s Post', '%s Posts', $post->author_posts, 'asgaros-forum'), $post->author_posts).'</small>';
                    }

                    if (AsgarosForumPermissions::isBanned($post->author_id)) {
                        echo '<br /><small class="banned">'.__('Banned', 'asgaros-forum').'</small>';
                    }

                    do_action('asgarosforum_after_post_author', $post->author_id, $post->author_posts);
                    ?>                </div>
                <div class="post-message">
                    <?php
                    $post_content = make_clickable(wpautop($wp_embed->autoembed(stripslashes($post->text))));

                    if ($this->options['allow_shortcodes']) {
                        // Prevent executing specific shortcodes in posts.
                        $filtered_shortcodes = array();
                        $filtered_shortcodes[] = 'forum';
                        $filtered_shortcodes = apply_filters('asgarosforum_filter_post_shortcodes', $filtered_shortcodes);

                        foreach ($filtered_shortcodes as $value) {
                            remove_shortcode($value);
                        }

                        // Run shortcodes.
                        $post_content = do_shortcode($post_content);
                    }

                    $post_content = apply_filters('asgarosforum_filter_post_content', $post_content);
                    echo $post_content;
                    AsgarosForumUploads::getFileList($post);
                    echo '<div class="post-footer">';
                    if ($this->options['show_edit_date'] && (strtotime($post->date_edit) > strtotime($post->date))) {
                        echo sprintf(__('Last edited on %s', 'asgaros-forum'), $this->format_date($post->date_edit)).'&nbsp;&middot;&nbsp;';
                    }
                    echo '<a href="'.$this->get_postlink($this->current_topic, $post->id, ($this->current_page + 1)).'">#'.(($this->options['posts_per_page'] * $this->current_page) + $counter).'</a>';

                    // Show signature.
                    if ($this->options['allow_signatures']) {
                        $signature = trim(esc_html(get_user_meta($post->author_id, 'asgarosforum_signature', true)));

                        if (!empty($signature)) {
                            echo '<div class="signature">'.$signature.'</div>';
                        }
                    }

                    echo '</div>';

                    do_action('asgarosforum_after_post_message', $post->author_id, $post->id);
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div>
    <?php echo $pageing; ?>
    <div class="forum-menu"><?php echo $this->forum_menu('thread', false); ?></div>
    <div class="clear"></div>
</div>

<?php
AsgarosForumNotifications::showTopicSubscriptionLink();
?>
