<div class="tab-pane <?php echo $activeTab === 'mining' ? 'active' : ''; ?>"  id="mining" role="tabpanel">
	
<?php
	$mining = get_user_meta($userId, 'mining', true);
	?>

<div class="spaceNotice">
	<?php if(empty($mining) || $mining == 'no'):?>
	<form class="form"  action="<?php echo home_url() ?>/mining.php?status=yes" name="" id="mining" method="post">
		You can participate in this toplist by enabling mining right here! <strong>Switch on mining:</strong> <label class="switch">
			<input onChange="this.form.submit()" type="checkbox">
			<span class="slider round"></span>
		</label>
		</form>
	<?php else:?>
	<form class="form"  action="<?php echo home_url() ?>/mining.php?status=no" name="" id="mining" method="post">
		Hurray! You're helping Assault.Online grow. Want to give your CPU a break? <strong>Switch off mining:</strong> <label class="switch">
			<input checked onChange="this.form.submit()" type="checkbox">
			<span class="slider round"></span>
		</label>
		</form>
	<?php endif;?>
</div>
<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-1"></div>
		<div class="col-md-4"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Mined</strong></div>
		<div class="col-md-4"><strong>Clan</strong></div>
	</div>
			<?php
			// Instantiate the class with your secret key
$coinhive = new CoinHiveAPI('u4oXesRBWKV1wVrgVeOKrakCF5bLKXB4');

// Make a simple get request without additional parameters
$users = $coinhive->get('/user/top');
$position   = 0;
foreach ($users->users as $user) :

	$user_ID = $user->name;
	$userData = get_user_meta($user_ID);
	$mined = $user->balance;
	
	if ($mined < 1000000) {
    // Anything less than a million
    $mined_format = number_format($mined, 0, ',', ' ');
	} else if ($mined < 1000000000) {
    // Anything less than a billion
    $mined_format = number_format($mined / 1000000, 2, '.', ' ') . ' M';
	} else {
    // At least a billion
    $mined_format = number_format($mined / 1000000000, 2, '.', ' ') . ' B';
	}
	
	
	$clan_id = $userData['clan_id_user'][0];
?>


<div class="row clan_profile_row2">
		
	<div class="col-md-1">
		<div class="positionNo">
			<?php echo $position += 1; ?>
		</div>
	</div>
	
	
	<div class="col-md-1">
		<?php echo small_avatar($user_ID,'');?>
	</div>
	
	
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		<?php echo get_user_name($user_ID);?>		
	</div>
	
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Mined</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo $mined_format;?>
		</span>
	</div>
	

	
	<div class="col-md-4 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
				echo 'Clanless';}else{
				echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
					}?>	
	
	</div>
</div> <! // Close profile row -- >

<?php endforeach; ?>
</div>


</div> <!-- Close tab pane 1 -->