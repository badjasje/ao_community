<?php
$array['wtf'] = 'Dave';
require_once("wp-load.php");
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$data = maybe_unserialize( $_POST );
$array = array();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

$clanData = get_post_meta($clan_ID);

//Add serverside "if clan has points, reject the request - here
//Add serverside "if clanmember is not CL, reject the request - here
//Add serverside "if optout_status is not 0, they've done this before so reject the request here" 

if ($data['optin_status'] == 'optedout') {
  $array['thing'] = "optedout";
  update_post_meta($clan_ID, 'optout_status','1');
  update_post_meta($clan_ID, 'optout_reset','1');

}
if ($data['optin_status'] == 'optedin') {
  $array['thing'] = "optedin";
  update_post_meta($clan_ID, 'optout_status','0');
  update_post_meta($clan_ID, 'optout_reset','1');
}





//$array['thing'] = json_encode($data);
$array['status'] = 'Opt in/out settings successfully changed';
echo json_encode($array);
exit;
