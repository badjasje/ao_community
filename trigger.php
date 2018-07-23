<?php
require __DIR__ . '/vendor/autoload.php';
//require '../../config.php';
$channel_name = 'my-channel';
$event_name = 'my_event';
$event_data = array('message' => 'Dat dus!');
$options = array(
    'cluster' => 'eu',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    '6d66f5a0511438609aac',
    '879b9a2c5bb2bb20806d',
    '373091',
    $options
  );
  
$response = $pusher->trigger($channel_name, $event_name, $event_data, null, true);
print_r($response);
?>