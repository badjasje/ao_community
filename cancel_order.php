<?php
/**
 * Temporary file used while market is still in old style code
 */
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$array = array('next' => false, 'status' => 'Undefined error');
$orderId = intval($_POST['order']);
$order = Order::make($orderId);
if(!empty($order->get('id'))) {
    $result = $order->cancel();
    if($result != true) $array['status'] = $result;
    else $array = array(
        'next' => true,
        'status' => 'Order canceled. You received '. $order->cashback(true),
        'remove' => $orderId,
        'money' => 0,
    );
}

echo json_encode($array);
exit;
