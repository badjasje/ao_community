<?php
    
    require_once("wp-load.php");
	require_once("coinhive-api.php");

// Instantiate the class with your secret key
$coinhive = new CoinHiveAPI('u4oXesRBWKV1wVrgVeOKrakCF5bLKXB4');

// Make a simple get request without additional parameters
$stats = $coinhive->get('/user/top');

echo '<pre>';
print_r($stats);
echo '</pre>';

// Make a get request that requires an extra parameter
$user = $coinhive->get('/user/balance', ['name' => '1']);
echo '<br/><br/>'.$user->balance;

