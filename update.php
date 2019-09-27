<?php
/**
 * Generic file to do database updates without access to database or wp-admin
 */
if(in_array($_SERVER['REMOTE_ADDR'],array('83.80.24.164','87.209.229.255','217.121.5.245','213.125.228.34'))) {
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

    echo '<p><strong>Update done, thank you.</strong></p>';
}
