<?php
    
    require_once("wp-load.php");
   
	

    $ip_array = maybe_unserialize(get_field('login_array_general',139664));
	
	foreach ($ip_array as $ip => $userdata):?>
	<h2><?php echo $ip;?></h2>
	<?php if(count($userdata) > 1){ echo '<span style="color:#ff0000"><strong>MULTI DETECTED</strong></span><br/><br/>';}?>
	<?php foreach ($userdata as $userId => $data):?>
	<?php echo get_user_name($userId);?>
	<?php echo '<pre>';
	print_r($userdata[$userId]);
	echo '</pre>';
	$geodata = json_decode($userdata[$userId][3]);

	?>
	<ul>
	<?
	foreach ($geodata->data->geo as $key => $item):?>
	
	<li><?php echo $key;?>: <?php echo $item;?></li>
	
	<?php endforeach;?>
	</ul>
	<br/>
	<?php endforeach;?>
	
	<?php endforeach;
		
		