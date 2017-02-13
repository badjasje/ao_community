<?php

if (!defined('ABSPATH')) exit;

?>
<div>
    <?php
    $pageing = $this->pageing($this->table_posts);
    echo $pageing;
    ?>
    <div class="forum-menu"><?php echo $this->forum_menu('thread');?></div>
    <div class="clear"></div>
</div>

<div class="title-element"><?php echo esc_html($this->cut_string(stripslashes($this->get_name($this->current_thread, $this->table_threads)), 70)).$meClosed; ?></div>
<div class="content-element">
    <?php
    $counter = 0;
    $avatars_available = get_option('show_avatars');
    $threadStarter = $this->get_thread_starter($this->current_thread);
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
                    if ($this->options['highlight_authors'] && ($counter > 1 || $this->current_page > 0) && $threadStarter == $post->author_id) {
                        echo '<small class="post-author-marker">'.__('Thread Author', 'asgaros-forum').'</small>';
                    }

                    if ($avatars_available) {?>
                    <center>
                    <?php if(!empty(get_user_meta($post->author_id, 'avatar_user', true))):?>
                    
			<div style='height:60px;width:60px;background: url("<?php echo get_user_meta($post->author_id, 'avatar_user', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='height:60px;width:60px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
                    
			<?php endif;?>
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
                    ?>
                </div>
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
                    AsgarosForumUploads::getFileList($post->id, $post->uploads, true);
                    echo '<div class="post-footer">';
                    if ($this->options['show_edit_date'] && (strtotime($post->date_edit) > strtotime($post->date))) {
                        echo sprintf(__('Last edited on %s', 'asgaros-forum'), $this->format_date($post->date_edit)).'&nbsp;&middot;&nbsp;';
                    }
                    echo '<a href="'.$this->get_postlink($this->current_thread, $post->id, ($this->current_page + 1)).'">#'.(($this->options['posts_per_page'] * $this->current_page) + $counter).'</a>';
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
AsgarosForumNotifications::showSubscriptionLink();
?>
