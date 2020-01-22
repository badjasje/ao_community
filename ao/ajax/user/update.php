<?php

function ajax_update($province, $return) {
    $msgs = array();
    $user = User::make($province->get('id'));

    $playername = Request::post('playername');
    if(!empty($playername)) $playername = trim(preg_replace('/[^A-Za-z0-9\- ]/', '', $playername));
    if(empty($playername)) {
        return array('status' => 'Invalid player name');
    }

    $email = Request::post('email');
    if(empty($email) || !is_email($email)) {
        return array('status' => 'Invalid email address');
    }
    $email_id = email_exists($email);
    if($email_id != false && $email_id != $user->get('id')) {
        return array('status' => 'Email address already used');
    }

    $phone = Request::post('phone');
    if(!empty($phone) && strlen($phone) < 9) {
        return array('status' => 'Invalid phone number');
    }

    $username = Request::post('username');
    if(!empty($username) && !validate_username($username)) {
        return array('status' => 'Invalid username');
    }

    $username_id = username_exists($username);
    if(!empty($username) && $username_id != $user->get('id')) {
        return array('status' => 'Username already in use');
    }

    $password = Request::post('password');
    if(!empty($password) && strlen($password) < 4) {
        return array('status' => 'Invalid password');
    }

    if($user->getName() != $playername) { // counts as a playername change
        if(Round::isLive() && $user->get('name_change_counter') == 1 && $playername != 'Minion') {
            return array('status' => 'Username already changed this round ');
        }

        $search_player = new WP_User_Query(array('search' => $playername, 'search_fields' => array('display_name'), 'meta_query'=> array(array(
			array('key' => 'last_online', 'value' => current_time('timestamp')-1728000, 'compare' => ">", 'type' => 'numeric'),
        ))));
        if (count($search_player->results) && $playername != 'Minion' && $search_player->results[0]->data->ID != $user->get('id')) {
            return array('status' => 'Playername already exists');
        }
        $user->update('display_name', $playername);
        $user->update('name_change_counter', 1);
        $msgs[] = 'playername';
    }

    $update = array();
    if(!empty($username) && $user->getUsername() != $username) { $update['user_login'] = $username; $msgs[] = 'username'; }
    if(!empty($email) && $user->get('email') != $email) { $update['user_email'] = $email; $msgs[] = 'email'; }
    if(!empty($password)) { $update['user_pass'] = $password; $msgs[] = 'password'; }
    if(count($update)) {
        wp_update_user(array_merge(array('ID' => $user->get('id')), $update));
    }

    if(!empty($phone) && $phone != $user->get('phone_number')) {
        $user->update('phone_number', $phone);  $msgs[] = 'phone';
    }

    $newuserimage = Request::post('newuserimage');
    if(!empty($newuserimage)) {
        $wp_upload_dir = wp_upload_dir();
        $path_parts = pathinfo($wp_upload_dir['path'] . '/' . $newuserimage);
        if(in_array($path_parts['extension'], array('png','jpg','gif'))) {
            /*if(is_ani($wp_upload_dir['path'] . '/' . $newuserimage)) {
                return array('status' => 'Only for AO gold users');
            }*/
            $destimage = 'user-'.$user->get('id').'_'.trim(preg_replace('/[^A-Za-z0-9\- ]/', '', $path_parts['filename'])).'.'.$path_parts['extension'];
            resize_crop_image(120, 120, $wp_upload_dir['path'] . '/' . $newuserimage, $wp_upload_dir['path'] . '/' . $destimage);
            $user->update('avatar_user', $wp_upload_dir['url'] . '/' . $destimage);
            $msgs[] = 'profile picture';
        } else return array('status' => 'Invalid file extension');
    }

    $_SESSION['showError'] = (count($msgs)==0 ? 'Nothing' : ucfirst(implode(', ', $msgs))).' updated';

    return array('success' => true, 'status' => '', 'redirect' => Request::siteUrl().'/users/profile/edit');
}

function is_ani($filename) {
    if(!($fh = @fopen($filename, 'rb'))) return false;
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C)

    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers
    while(!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00[\x2C\x21]#s', $chunk, $matches);
    }

    fclose($fh);
    return $count > 1;
}

function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    if(extension_loaded('imagick')) {
        if($mime == 'image/gif') {
            system("convert ".$source_file." -coalesce -repage 0x0 -gravity center -crop ".$max_width."x".$max_height."+0+0 +repage -layers optimize ".$dst_dir);
        }
        else {
            system("convert ".$source_file." -resize ".$max_width."x".$max_height."^ -gravity center -extent ".$max_width."x".$max_height." ".$dst_dir);
        }
        return;
    }

    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
        break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
        break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
        break;
        default: return false;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}