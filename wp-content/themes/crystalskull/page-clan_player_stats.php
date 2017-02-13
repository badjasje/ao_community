<?php
 /*
 * Template Name: Clan player statistics
 */
 
$user_ID = get_current_user_id();
$clan_id_user = get_user_meta($user_ID, 'clan_id_user',true);

$clan_leader = get_post_meta($clan_id_user, 'clan_leader',true);
$ct_1 = get_post_meta($clan_id_user,'ct_1',true);
$ct_2 = get_post_meta($clan_id_user,'ct_2',true);
$ct_3 = get_post_meta($clan_id_user,'ct_3',true);
$ct_4 = get_post_meta($clan_id_user,'ct_4',true);

$allowed = array($clan_leader,$ct_1,$ct_2,$ct_3,$ct_4);


include 'count_functions.php';
include 'units_array.php';
include 'building_array.php';
include 'research_array.php';
include 'interest_array.php';
$members = get_post_meta($clan_id_user,'clan_members',true);
get_header(); ?>


<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	        <table class="responsive-table">
		        <thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Turns</th>
						<th scope="col">Money</th>
						<th scope="col">Morale</th>
						<th scope="col">Land</th>
						<th scope="col">Research</th>
						<th scope="col">Last active</th>
						<th scope="col">Units</th>
						<th scope="col">Buildings</th>
						
  					</tr>
  				</thead>
		        <tbody>
			<?php foreach ($members as $member) { 				
				
				$member_data = get_userdata($member);
				$money = get_user_meta($member,'money',true);
				$land = get_user_meta($member,'land',true);
				$last_online = get_user_meta($member,'last_online',true);
				$in_progress = get_user_meta($member, 'research_in_progress', true);
				$qued = get_user_meta($member, 'queued_research', true);
			?>
			<tr>
				<td data-title="Name">
					<a class="<?php echo get_user_meta($member,'status',true);?>" href="/users/profile/?id=<?php echo $member; count_all_stats($member);?>"><?php echo $member_data->display_name.' (#'.$member.')';?>
				</td>
				<td data-title="Turns">
					<?php echo get_user_meta($member,'turns',true);?>
				</td>
				<td data-title="Money">
						<script>
				jQuery(document).ready(function(){
					jQuery("#content_<?php echo $member;?>bank").hide();
					
					jQuery("#show_<?php echo $member;?>bank").click(function(){
					jQuery("#content_<?php echo $member;?>bank").toggle();
					
					
				    jQuery('.fontawesome<?php echo $member;?>').toggle('1000');
				    jQuery(".fa", this).toggleClass("fa-arrow-right fa-arrow-left");
			
    				});

				});
				</script>
					
					$ <?php echo number_format($money, 0, ',', ' '); ?>
					<span style="cursor: pointer" id="show_<?php echo $member;?>bank"><i class="fa fa-university" aria-hidden="true"></i></span>
				<div  id="content_<?php echo $member;?>bank" class="member_units">
					<?php 	
	
		$args = array(
	'posts_per_page'   => -1,
	'author'	=> $member,
	'post_type'        => 'deposit',
	'meta_key' => 'release_date',
	'orderby'    => 'meta_value_num',
	);
	$deposits = get_posts( $args ); 
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	$total_deposited = 0;
	$total_final = 0;
	$unlocked = 0;
	foreach ($deposits as $deposit) {
		$banklevel = get_user_meta($member, 'level_bank_management')[0];
		$days = get_post_meta($deposit->ID,'days',true);
		$deposited = get_post_meta($deposit->ID,'amount',true);
		$total_deposited+=$deposited;
		$amount = get_post_meta($deposit->ID,'amount')[0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = get_post_meta($deposit->ID,'release_date',true);
		$startingbonus = get_user_meta($member, 'starting_bonus',true);
					$finance_multi = 1;
						if($startingbonus == 'finance'){
							$finance_multi = 1.5;
						}
					
					if($banklevel == 0){
						$extra_interest = 0;
						$max_dep = 250000*$finance_multi;
						$max_tot = 2500000*$finance_multi;
					}
					if($banklevel == 1){
						$extra_interest = 0.5;
						$max_dep = 350000*$finance_multi;
						$max_tot = 3500000;
					}
					if($banklevel == 2){
						$extra_interest = 0.75;
						$max_dep = 450000*$finance_multi;
						$max_tot = 4500000;
					}
					if($banklevel == 3){
						$extra_interest = 1;
						$max_dep = 500000*$finance_multi;
						$max_tot = 5000000*$finance_multi;
						}
	?>
		<span style="float:left;">Deposited:</span> <span style="float:right;">$ <?php echo number_format($deposited, 0, ',', ' '); ?></span><br/>
		<span style="float:left;">Final:</span> <span style="float:right;">$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?></span><br/>
		<span style="float:left;">Release date:</span> <span style="float:right;"><?php echo date('H:i | d-m-Y', $release_stamp);?></span><hr/>
					
					
					<?php }?>
					
				</div>
					
				</td>
				<td data-title="Morale">
					<?php echo get_user_meta($member,'morale',true);?>% <sup>(<?php echo get_user_meta($member,'morale_pool',true);?>%)</sup>
				</td>
				<td data-title="Land">
					<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
				</td>
				<td data-title="Research">
					<script>
				jQuery(document).ready(function(){
					jQuery("#content_<?php echo $member;?>research").hide();
					
					jQuery("#show_<?php echo $member;?>research").click(function(){
					jQuery("#content_<?php echo $member;?>research").toggle();
					
					
				    jQuery('.fontawesome<?php echo $member;?>').toggle('1000');
				    jQuery(".fa", this).toggleClass("fa-arrow-right fa-arrow-left");
			
    				});

				});
				</script>
				<span style="border: 1px solid #172d3a;padding: 0px 10px;cursor: pointer" id="show_<?php echo $member;?>research">Research <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				<div  id="content_<?php echo $member;?>research" class="member_units">
					<?php if($in_progress):?>
					In progress: <strong><?php echo $researches[$in_progress]['name']  ?></strong><br/>
					<?php endif;?>
					<?php if($qued):?>
					Queued: <strong><?php echo $researches[$qued]['name']  ?></strong>
					<?php endif;?>
					<br/>
					<?php foreach($researches as $key => $research){
						$level = get_user_meta($member, 'level_'.$key,true);
					
						
				?>
					<span style="float:left;"><?php echo $research['name'];?></span> <span style="float:right;">Level: <?php echo $level;?></span><br/>
					
					<?php }?>
					
					
					
				</div>
				</td>
				<td data-title="Last active">
					<?php echo date('H:i | d-m-y', $last_online);?>
				</td>
				<td data-title="Units">
					
				<script>
				jQuery(document).ready(function(){
					jQuery("#content_<?php echo $member;?>").hide();
					
					jQuery("#show_<?php echo $member;?>").click(function(){
    
					jQuery("#content_<?php echo $member;?>").toggle();
					jQuery(".fa", this).toggleClass("fa-arrow-right fa-arrow-left");
					
					
					
    				});

				});
				</script>
					
					
					<span style="border: 1px solid #172d3a;padding: 0px 10px;cursor: pointer" id="show_<?php echo $member;?>"><?php echo count_tot_units($member);?> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
					<div  id="content_<?php echo $member;?>" class="member_units">
					<?php foreach($units as $key => $order){
						$units_owned = get_user_meta($member, $key.'_owned',true);
						$units_ordered = get_user_meta($member, $key.'_ordered',true);
						if($units_owned > 0 || $units_ordered >0){
				?>
				<span style="float:left;"><?php echo $order['normalname'];?></span> <span style="float:right;"><?php echo $units_owned;?> (<?php echo $units_ordered;?>)</span><br/>
					
					<?php }}?>
						
						
					</div>



					
				</td>
				<td data-title="Buildings">
					
					
					<script>
				jQuery(document).ready(function(){
					jQuery("#content_<?php echo $member;?>buildings").hide();
					
					jQuery("#show_<?php echo $member;?>buildings").click(function(){
    
					jQuery("#content_<?php echo $member;?>buildings").toggle();
					jQuery(".fa", this).toggleClass("fa-arrow-right fa-arrow-left");
    				});

				});
				</script>
					
					
					<span style="border: 1px solid #172d3a;padding: 0px 10px;cursor: pointer" id="show_<?php echo $member;?>buildings"><?php echo count_tot_buildings($member);?> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
					
					<div  id="content_<?php echo $member;?>buildings" class="member_units">
					<?php foreach($buildings as $key => $order){
						$units_owned = get_user_meta($member, $key,true);
						
						if($units_owned > 0){
				?>
				<span style="float:left;"><?php echo $order['normalname'];?></span> <span style="float:right;"><?php echo $units_owned;?></span><br/>
					
					<?php }}?>
						
				
					</div>
				</td>
				
			</tr>
			
			
			<?php }?>
			        
			        
			        
			        
		        </tbody>
	        </table>
	        
	            
	            
	            
	            
	            
	            
	            
	            
       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>