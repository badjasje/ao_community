<?php
require(dirname(__FILE__).'/../vendor/autoload.php');
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

// trigger on 'my-channel' an event called 'my-event' with this payload:

$text = $_POST['message'];

$data['message'] = $text;

$pusher->trigger('notifications', 'new_notification', $data);
?>



