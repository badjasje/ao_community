<?php
	
	require_once("wp-load.php");


$defender_money = 1000000;
$no_thiefs = 10;
$tot_snipers = 0;
echo 'Defender Money: '.$defender_money.'<br/>';
echo 'Number of thiefs: '.$no_thiefs.'<br/>';



$thief_level = get_user_meta($user_ID, 'level_thieving_effectiveness',true);

if($thief_level == 0){
$money_stolen = ceil($defender_money*pow(1+((rand(10, 20) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(75, 100)+($no_thiefs*7)+($tot_snipers*0.39);
}
if($thief_level == 1){
$money_stolen = ceil($defender_money*pow(1+((rand(20, 30) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(70, 100)+($no_thiefs*6)+($tot_snipers*0.39);
}
if($thief_level == 2){
$money_stolen = ceil($defender_money*pow(1+((rand(30, 40) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(65, 100)+($no_thiefs*5)+($tot_snipers*0.39);
}
if($thief_level == 3){
$money_stolen = ceil($defender_money*pow(1+((rand(40, 50) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(50, 100)+($no_thiefs*2.5)+($tot_snipers*0.39);
}

echo 'Money stolen: '.$money_stolen.'<br/>';
echo 'Caught: '.$caught;