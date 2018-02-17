<?php
 /*
 * Template Name: Clan spy report overview
 */
$clan_ID = $_GET['id'];
$clanData = get_post_meta($clan_ID);
$clan_members = $clanData['clan_members'][0];
$visiting_user = get_current_user_id();
$clanleader = $clanData['clan_leader'][0];
$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];

$visiting_clan = get_user_meta($visiting_user, 'clan_id_user', true);
if($visiting_clan != 0){
	$clan_NW = get_post_meta($visiting_clan, 'clan_networth', true);
}
$visiting_members = get_post_meta($visiting_clan,'clan_members',true);
$args = array(
		
		'post_type'		=>	'clan',
		'posts_per_page' => -1,
		);
	
$clans = get_posts($args);

include('units_array.php');


get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
       

<?php if(get_field('game_status','option') != 'Live'):?>
	<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
<?php else:?> 

<script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery(".searchclans").select2();
	});
</script>




<form>
	<select id="clan" name="clan" class="searchclans" onchange="if (this.value) window.location.href=this.value">
		<option disabled selected name="clan" value="<?php echo $clan_ID;?>">
			Currently viewing: <?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
		</option>
		
	<?php if($visiting_clan != 0):?>
		<option disabled  name="clan" value="<?php echo $clan_ID;?>">
			Clans in range &rarrb;
		</option>
		        
	<?php foreach ($clans as $clan) {
		$tot_networth = get_post_meta($clan->ID, 'clan_networth', true);
		?>
				
	<?php if (($tot_networth > $clan_NW/1.4 && $tot_networth < $clan_NW*1.4)){	?>	
		<option class="inrange" name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>">
			<strong><?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)</strong>
		</option>
	<?php }}?>
	<?php endif;?>
	
		<option disabled  name="clan" value="<?php echo $clan_ID;?>">
			Clans out of range &rarrb;
		</option>
	
	<?php foreach ($clans as $clan) {?>
		<option name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>">
			<?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)
		</option>
	<?php }?>
	</select>
</form>

<br/>


<div class="storeDetails-heads button_block">
	<center>
	<strong>Sort:</strong> <a href="" class="sort" data-sort=".memberField">Name</a> - 
	<a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort sort-number" data-sort=".land">Land</a>
	</center>
</div>

<!-- Own clan block -->


<div class="row profile_block storeDetails-heads">	

<div id="values">
<?php 
	$NRmembers = count($clan_members);
	$counter = 0;
	foreach ($clan_members as $key => $member) {
		
		
		
		
		
		
		// Get latest unit report
$unitargs = array(
'posts_per_page'   => 1,
'author__in'   => $visiting_members,
'meta_query'	=> array(
	'relation'		=> 'AND',
		array(
			'key'	 	=> 'spied_id',
			'value'	  	=> $member,
			'compare' 	=> '=',
			),
		array(
			'key'	 	=> 'clan_id_report',
			'value'	  	=> $visiting_clan,
			'compare' 	=> '=',
			),
		array(
			'key'	 	=> 'spy_type',
			'value'	  	=> 'spy',
			'compare' 	=> '=',
			),
			
		
		),
'post_type'        => 'spy_rep',
);

$unitRep = get_posts( $unitargs );
$unitRep_ID = $unitRep[0]->ID;
if(count($unitRep) > 0){
	$unitRep_date = get_the_date('G:i | d-m-Y',$unitRep_ID);
}else{
	$unitRep_date = 'No reports';
}
$unitrepStamp = get_the_time('U',$unitRep_ID);	
$unitarray = get_post_meta($unitRep_ID, 'spy_array', true);

$attack_array = array();
foreach ($units as $unit) {
	foreach ($unitarray as $unitname => $amount) {
		if($unitname == $unit['normalname']){
			$attack_array[] = array_shift($attacks);
			$attacks = $unit['attacks'];
				
				if(!empty($attacks)){
					$attack_array[] = array_shift($attacks);
				}
			}
		}	
	}

$attack_array = array_diff($attack_array,array('n.a',''));
$attack_array = array_unique($attack_array);

$type_array = array();
foreach ($units as $unit) {
	foreach ($unitarray as $unitname => $amount) {
		if($unitname == $unit['normalname'] && $unitname != 'Spy' && $unitname != 'SR-71 Spyplane'){
		
			$types = $unit['type'];
			$type_array[] = $types;
			
			}
		}	
	}

$type_array = array_diff($type_array,array('n.a',''));
$type_array = array_unique($type_array);

// Get latest building report
$buildingargs = array(
'posts_per_page'   => 1,
'author__in'   => $visiting_members,
'meta_query'	=> array(
	'relation'		=> 'AND',
		array(
			'key'	 	=> 'spied_id',
			'value'	  	=> $member,
			'compare' 	=> '=',
			),
		array(
			'key'	 	=> 'clan_id_report',
			'value'	  	=> $visiting_clan,
			'compare' 	=> '=',
			),
		array(
			'key'	 	=> 'spy_type',
			'value'	  	=> 'spyplane',
			'compare' 	=> '=',
			),
			
		
		),
'post_type'        => 'spy_rep',
);

$bldRep = get_posts( $buildingargs );
$bldRep_ID = $bldRep[0]->ID;

if(count($bldRep) > 0){
	$bldRep_date = get_the_date('G:i | d-m-Y',$bldRep_ID);
}else{
	$bldRep_date = 'No reports';
}	

$bldrepStamp = get_the_time('U',$bldRep_ID);	
$bldarray = get_post_meta($bldRep_ID, 'spy_array', true);

$regNW = 0;
$regLand = 0;
if($unitrepStamp > $bldrepStamp){
	$regNW = get_post_meta($unitRep_ID, 'spied_nw', true);
	$regLand = get_post_meta($unitRep_ID, 'spied_land', true);
}else{
	$regNW = get_post_meta($bldRep_ID, 'spied_nw', true);
	$regLand = get_post_meta($bldRep_ID, 'spied_land', true);
}

		
		
		
		$land = get_user_meta($member, 'land', true);
		
		
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		$member_data = get_userdata($member);
		$last_online = get_user_meta($member, 'last_online',true);
		$spiednr = get_user_meta($user_ID, 'spied_current_clan',true);
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
			?>

	<div class="row clan_profile_row">
		<div class="row firstRow">
			<div class="col-md-4"></div>
			<div class="col-md-2"><strong>Networth current</strong></div>
			<div class="col-md-2"><strong>Land current</strong></div>
			<div class="col-md-2"><strong>Units spied date</strong></div>
			<div class="col-md-2"><strong>Buildings spied date</strong></div>
		</div>
	
	<div class="row">
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		<div class="ctclField">
			<?php if($member == $clanleader ){
			echo '<strong>CL</strong>';
			} ?>
			<?php if($member == $ct_1 || $member == $ct_2 || $member == $ct_3 || $member == $ct_4 ){
				echo '<strong>CT</strong>';
			} ?>
			</div>
		<a class="memberField <?php echo get_user_meta($member,'status',true);?>" href="/users/profile/?id=<?php echo $member;?>">
			
			<?php echo $member_data->display_name.' (#'.$member.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?><a href="/military-overview/?id=<?php echo $member;?>"><i class="fa fa-binoculars" aria-hidden="true"></i></a>
					
		
			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth current</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo networth_range($member);?>
		</span>

	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land current</span>
		<span class="clan_data_right land">
		<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Highest land: <?php echo $highest_land;?> m<sup>2</sup>" data-placement="bottom">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup></span>
		</span>
	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Units spied date</span>
		<span class="clan_data_right points">
			<?php echo $unitRep_date; ?>
		</span>
	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Buildings spied date</span>
		<span class="clan_data_right spied">
			<?php echo $bldRep_date; ?>
		</span>
	</div>
	</div> <!-- End first member row -->	
	
	
	
	
	<div class="row lastmemberrow"> <!-- Start second member row -->
	
	<div class="row memberrowSecond">
	<div class="col-md-4"></div>
	<div class="col-md-2"><strong>Networth registered</strong></div>
	<div class="col-md-2"><strong>Land registered</strong></div>
	<div class="col-md-2"><strong>Unit types</strong></div>
	<div class="col-md-2"><strong>Can attack</strong></div>
	</div>
	
	<div class="row">
	<div class="col-md-4 clan_column"></div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth registered</span>
		<span class="clan_data_right">
			$ <?php echo number_format($regNW, 0, ',', ' '); ?>
		</span>
		
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land registered</span>
		<span class="clan_data_right">
			<?php echo number_format($regLand, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		
		<span class="clan_data_left">Unit types</span>
		<span class="clan_data_right">
			<?php echo implode(", ", array_values($type_array));?>
		</span>
		
	</div>
	<div class="col-md-2 clan_column">
		
		<span class="clan_data_left">Can attack</span>
		<span class="clan_data_right">
			<?php echo implode(", ", array_values($attack_array));?>
		</span>
		
	</div>
	</div>
		
	</div> <!-- End second member row -->
	
	
	
	
	
	<div class="row lastmemberrow"> <!-- Start second member row -->
	
	<div class="row bdsunitsrow">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#units_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Unit report</a>
		
		<div id="units_<?php echo $member;?>" class="collapse collapsebox">
			<?php foreach($unitarray as $key => $amount){?>
				<?php if($key != 'enhance'):?>
					<span style="float:left;"><?php echo $key;?></span>
					<span style="float:right;"><strong><?php echo $amount;?></strong></span>
					<br/>
				<?php endif;?>
			<?php }?>
						
						
				
		</div>
		
	</div>
	<div class="col-md-4">
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#buildings_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Building report</a>
		<div id="buildings_<?php echo $member;?>" class="collapse collapsebox">
			<?php foreach($bldarray as $key => $amount){?>
				<?php if($key != 'enhance'):?>
					<span style="float:left;"><?php echo $key;?></span>
					<span style="float:right;"><strong><?php echo $amount;?></strong></span>
					<br/>
				<?php endif;?>
			<?php }?>
		</div>
		
	</div>
	</div>

	
	
	
	
	
	
	
	
	</div>
</div> <!-- // End profile row -->

<?php $typecounter = 0;} ?>
</div>
<div id="result"></div>
</div>









      
<?php endif;?> <!-- End live check -->
       
       
       
       
       
            
            </div> <!-- col-lg-12 col-md-12 -->
        </div> <!-- End main row -->
    </div> <!-- End container -->
</div> <!-- End page normal-page -->
<?php get_footer(); ?>