<?php
 /*
 * Template Name: Join a clan
 */
$clans = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'clan',
	'meta_key'		=> 'autojoin_allowed',
	'meta_value'	=> 'yes'
));

$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
if($clan_ID != 0){
	$_SESSION['status'] = 'Already part of a clan';
	wp_redirect(get_permalink(3601)); exit;
	
}

$count = 0;

foreach ($clans as $clan) { 
	
	$members = count(get_post_meta($clan->ID,'clan_members',true));
	
	if($members < 7){
		$count++;
	}
}
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">

<div class="row textNotify">
	<div class="col-md-12">
 	<center><span class="rdw-line">Join a clan to get the full assault.online experience.</span> <span class="rdw-line">
 	<?php echo $count;?> clan<?php if($count == 0 || $count > 1){ echo 's';}?> currently looking for players.</span></center>
</div>
</div>


 
<div class="row profile_block">	
	
<?php if($count == 0):?>
<center><h2 class="smallMargin">Currently no clans available</h2></center>
<?php endif;?>
<?php if($count != 0):?>
<div class="row clan_header_row">
	<div class="col-md-1"></div>
	<div class="col-md-3"><strong>Name</strong></div>
	<div class="col-md-1"><strong>Members</strong></div>
	<div class="col-md-3"><strong>Description</strong></div>
	<div class="col-md-2"><strong>Playstyle/goal</strong></div>
	<div class="col-md-1"><strong></strong></div>
</div>        
<?php endif;?>         
<?php 

	foreach ($clans as $clan) { 
	$clan_ID = $clan->ID;
	$members = count(get_post_meta($clan->ID,'clan_members',true));
	$autojoinDesc = get_post_meta($clan_ID, 'autojoin_description', true);
	$playstyle = get_post_meta($clan_ID, 'autojoin_playstyle', true);
	
	if($members < 7){

?>

<div class="row clan_profile_row<?php echo $extraClass;?>">
	<div class="col-md-1">
		
		<?php echo small_avatar(1,'');?>
		
	</div>
	<div class="col-md-3 clan_column center_clan_col border_bottom_mobile">
		
		<a href="<?php echo get_the_permalink($clan_ID);?>"><?php echo get_the_title($clan_ID). ' (#'.$clan_ID;?>)</a>
					

			
			
	</div>
	<div class="col-md-1 clan_column border_bottom_mobile">
		
		<span class="clan_data_left">Members</span>
		<span class="clan_data_right"><?php echo $members; ?></span>

	</div>
	<div class="col-md-3 clan_column border_bottom_mobile">
		<span class="clan_data_left">Description</span>
		<span class="clan_data_right">
		<?php echo $autojoinDesc;?>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Playstyle/goal</span>
		<span class="clan_data_right">
		<?php echo $playstyle;?>
		</span>
	</div>
	
	<div class="col-md-1 clan_column center_clan_col">
		
		<a class="btn btn-general profilebutton" href="/autojoin.php/?clan=<?php echo $clan_ID;?>">
		 	Join</a>
	
	</div>
</div>





<?php }?>

	
<?php } // End clan loop ?> 
</div>

       
       
       
       
       
       
       
       
            
            </div> <!-- // End col-lg-12 col-md-12 -->
        </div>
    </div>
</div>
<?php get_footer(); ?>