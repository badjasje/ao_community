<?php
	
	require_once("wp-load.php");
	/*
	$scores = [100,200,400,500,750,900,1000,1100,1200,1300,1400,1500,1600,1700,1800,1900,2000,2500,3000,4500,5000,6000,7000,8000,9000,10000,15000,17500,20000,25000,30000,35000,40000,45000,50000,60000,70000,80000,90000];
	
	//shuffle($scores);
	foreach ($scores as $score) {
		
	$res = 5.555 * log($score/2.2 / 400);
    echo $score.': '.ceil($res).'<br/>';
    }
/*

/*
    



$ip_array = get_field('login_array','options');?>


<?php
foreach ($ip_array as $ip => $users) {?>
<h2><?php echo $ip;?></h2>

<?php 
	$count = 0;
	foreach ($users as $user => $stuff) {
	$member_data = get_userdata($user);
	$count++;
	
?>

<a href="/users/profile/?id=<?php echo $user;?>"><?php echo $member_data->display_name.' (#'.$user.')';?></a><br/>

<?php echo $stuff[0];?><br/>

<?php echo $stuff[1];?><br/><br/>

<?php if($count >= 2){?>
<h1 style="color:#ff0000;">MULTI</h1>

<?php }}}?>




