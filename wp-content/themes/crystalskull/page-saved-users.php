<?php
 /*
 * Template Name: Saved Users
 */
$user_ID = get_current_user_ID();
$savedUsers = get_user_meta($user_ID, 'saved_users', true);
$savedUsers = json_decode($savedUsers);
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            
	            <div class="storeDetails-heads button_block sortingHeadMob">
	<center>
	<strong>Sort:</strong> <a href="" class="sort2" data-sort=".memberField">Name</a> - 
	<a href="" class="sort2 sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort2 sort-number" data-sort=".land">Land</a>
	</center>
</div>

<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-2"><strong><a href="" class="sort2" data-sort=".memberField">Name</a></strong></div>
		<div class="col-md-2"><strong><a href="" class="sort2 sort-number" data-sort=".store-pop-span2">Networth</a></strong></div>
		<div class="col-md-2"><strong><a href="" class="sort2 sort-number" data-sort=".land">Land</a></strong></div>
		<div class="col-md-3"><strong>Clan</strong></div>
		<div class="col-md-2"></div>
	</div>
	
<div id="values2">
	
	<?php 
	$timestamp = current_time('timestamp');

	$counter = 0;
	foreach ($savedUsers as $key => $allUser) {
		
		$user_ID = $allUser;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
	
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		
		
			?>
			
	<div class="row clan_profile_row2">
		<div class="col-md-1">
			<?php echo small_avatar($user_ID,'');?>
		</div>
	
	<div class="col-md-2 clan_column center_clan_col border_bottom_mobile">

		<?php echo get_user_name($user_ID);?>		

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
		
			<?php echo networth_range($user_ID);?>
					
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right land">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-3 clan_column center_clan_col border_bottom_mobile">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
	
	<div class="col-md-2 clan_column center_clan_col">
		
		<center><a class="btn btn-general profilebutton" href="/remove.php/?id=<?php echo $user_ID;?>">
			<i class="fa fa-trash-o" aria-hidden="true"></i> &nbsp;Remove</a>
		</center>
	
	</div>
</div> <! // Close profile row -->

<?php  }?>

<div id="result"></div>
</div>
</div>

       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>