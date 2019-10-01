<?php
/**
 * Generic file to do database updates without access to database or wp-admin
 */
require_once("wp-load.php");

function getWpOption($key) {
    global $wpdb;
    $data = $wpdb->get_row("SELECT `option_value` FROM `{$wpdb->prefix}options` WHERE `option_name` = '".$key."'", ARRAY_A);
    return unserialize($data['option_value']);
}
function setWpOption($key,$value) {
    global $wpdb;
    return $wpdb->query("UPDATE `{$wpdb->prefix}options` SET `option_value` = '". serialize($value) ."' WHERE `option_name` = '".$key."'");
}

if(isset($_GET['secret']) && isset($_GET['v']) && $_GET['secret']=='kutcloudflare') {

    if($_GET['v'] == '2') {
        $data = $wpdb->get_row("SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'acf-field' AND `post_title` = 'sub_messages_rep'", ARRAY_A);
        $fieldID = $data['ID'];
        $added = false;
        $fields = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_parent` = '".$fieldID."'", ARRAY_A);
        foreach ($fields as $field) {
            if($field['post_title']=='message_date_rep') $added = true;
        }
        if(!$added) {
            $data = array(
                'post_author' => 1,
                'post_content' => 'a:10:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";}',
                'post_title' => 'message_date_rep',
                'post_excerpt' => 'message_date_rep',
                'post_status' => 'publish',
                'post_name' => 'field_5d9217a58552c',
                'post_parent' => $fieldID,
                'menu_order' => 3,
                'post_type' => 'acf-field',
            );
            wp_insert_post($data);
            wtf('date-rep added');
        }
    }

    if($_GET['v'] == '1') {
        // Set permissions to profile-fields, so only admins can edit profile-fields
        // I am looking at you mega ;-)
        $fieldgroups = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_type` = 'acf-field-group' AND `post_title` IN (
            'Buildings','Extra User Info','Medal data','Missiles','Research | USER','Stats | USER (e.g nw, money, turns etc.)','Units','Game statistics | USER'
        )", ARRAY_A);
        foreach ($fieldgroups as $fieldgroup) {
            $settings = unserialize($fieldgroup['post_content']);
            if(!isset($settings['location'][0][1])) {
                $settings['location'][0][1] = array('param' => 'current_user_role', 'operator' => '==', 'value' => 'administrator');
                $wpdb->query("UPDATE `{$wpdb->prefix}posts` SET `post_content` = '".serialize($settings)."' WHERE `ID` = ".$fieldgroup['ID']);
            }
        }

        // Remove Stonefish and Testgebruiker from Administrators-role
        $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_value='0' WHERE `meta_key`='23zx_user_level' AND `user_id` IN (2,10) ");
        $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_value='a:1:{s:10:\"subscriber\";b:1;}' WHERE `meta_key`='23zx_capabilities' AND `user_id` IN (2,10)");


        // Add role "poll editor" that manages polls
        $user_roles = getWpOption($wpdb->prefix.'user_roles');
        if(!isset($user_roles['poll_editor'])) {
            $user_roles['poll_editor'] = array('name' => 'Poll Editor', 'capabilities' => array('read' => 1, 'level_0' => 1, 'manage_polls' => 1));
            setWpOption($wpdb->prefix.'user_roles', $user_roles);
        }

        // Give Dikdap 'poll editor' role on LIVE
        if(!Round::isDev() && !Round::isTest()) {
            $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_value='a:1:{s:11:\"poll_editor\";b:1;}' WHERE `meta_key`='23zx_capabilities' AND `user_id` IN (2768)");
        }

        // Change role "editor" so they can only change pages they are the author of
        $user_roles = getWpOption($wpdb->prefix.'user_roles');
        if(isset($user_roles['editor'])) {
            $user_roles['editor']['capabilities'] = array(
                'edit_published_pages' => 1, 'level_0' => 1, 'level_1' => 1, 'level_2' => 1, 'level_3' => 1, 'level_4' => 1, 'level_5' => 1, 'level_6' => 1, 'level_7' => 1,
            );
            setWpOption($wpdb->prefix.'user_roles', $user_roles);
        }

        // Give access to the "Manual"-page
        $userForManual = (isset($_GET['userForManual']) ? $_GET['userForManual'] : 0);
        if(Round::isDev()) $userForManual = 2;
        if($userForManual > 0) {
            // Give -SOMEONE- editor role
            $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_value='7' WHERE `meta_key`='23zx_user_level' AND `user_id` = ".$userForManual);
            $wpdb->query("UPDATE {$wpdb->prefix}usermeta SET meta_value='a:1:{s:6:\"editor\";b:1;}' WHERE `meta_key`='23zx_capabilities' AND `user_id` = ".$userForManual);

            // Give -SOMEONE- access to the manual page
            $data = $wpdb->get_row("SELECT ID FROM `{$wpdb->prefix}posts` WHERE `post_status`='publish' AND `post_name`='manual' AND `post_type`='page'", ARRAY_A);
            if(isset($data['ID'])) {
                $wpdb->query("UPDATE {$wpdb->prefix}posts SET `post_author` = ".$userForManual." WHERE `ID` = ".$data['ID']);
                echo '<p>Manual edit link: '. Request::siteUrl() .'/wp-admin/post.php?post='.$data['ID'].'&action=edit&classic-editor</p>';
            }
        }
    }

    echo '<p><strong>Update done, thank you.</strong></p>';
}
