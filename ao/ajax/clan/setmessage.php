<?php

function ajax_setmessage($province, $result) {
    $clan = $province->getClan();
    if($clan == false) return array('status' => 'No such clan');
    if(!$clan->canEditMessage()) return array('status' => 'You cannot do that');
    $content = sanitize_text_field(htmlentities(wp_kses_post(Request::post('new_message','raw'))));
    $clan->update('clan_message', $content);
    return array('success' => true, 'status' => 'Clan message updated', 'clanmessage' => $clan->getMessage(true));
}
