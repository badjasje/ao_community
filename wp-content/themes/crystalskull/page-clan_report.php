<?php
 /*
 * Template Name: Clan Spy rep overview
 */
$clan_ID = $_GET['id'];
$clan_members = get_post_meta($clan_ID,'clan_members');
$visiting_user = get_current_user_id();
$nw_att = get_user_meta($visiting_user, 'networth', true);

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
	
get_header('spyoverview'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
	       <script type="text/javascript">
jQuery(document).ready(function() {
  jQuery(".searchclans").select2();
});
</script>


	     
	     
	        <form>
	        <select id="clan" name="clan" class="searchclans" onchange="if (this.value) window.location.href=this.value">
		        <option disabled selected name="clan" value="<?php echo $clan_ID;?>">Currently viewing: <?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)</option>
		        <?php if($visiting_clan != 0):?>
		        <option disabled  name="clan" value="<?php echo $clan_ID;?>">Clans in range &rarrb;</option>
		        <?php foreach ($clans as $clan) {
			    $tot_networth = get_post_meta($clan->ID, 'clan_networth', true);
		        ?>
				<?php if (($tot_networth > $clan_NW/1.4 && $tot_networth < $clan_NW*1.4)){	?>	
				  <option class="inrange" name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>"><strong><?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)</strong></option>
				 <?php }}?>
				 <?php endif;?>
				 <option disabled  name="clan" value="<?php echo $clan_ID;?>">Clans out of range &rarrb;</option>
		        <?php foreach ($clans as $clan) {?>
				  
				  <option name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>"><?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)</option>
				  <?php }?>
			</select>
	        </form>
	        <br/>
            <div class="col-lg-12 col-md-12">
	        <div class="clan_sorter">
			<center>Sort by
			<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('landsort'), [])">Land</a>
			<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('nwsort'), [])">Networth</a>
			<?php if (in_array($user_ID, $clan_members[0])):?>
			<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('ptssort'), [])">Points</a>
			<?php endif;?>
			</center>
			<br/>
			</div>
	        
	        <table class="responsive-table sortable">
			<thead>
			<tr style="text-align:center;">
				
			
				<td><strong>Name</strong>
				</td>
				<td id="nwsort"><strong>Networth<br/><sup>current/registered</sup></strong>
				</td>
				<td id="landsort"><strong>Land<br/><sup>current/registered</sup></strong>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
				</td>
				</thead>
			</tr>
			<tbody>
	            <?php 
			
	
			
			foreach ($clan_members[0] as $key => $member) {
				$member_data = get_userdata($member);
				$networth = get_user_meta($member, 'networth');
				$land = get_user_meta($member, 'land');
				$last_online = get_user_meta($member, 'last_online');
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online[0];}
			?>
		<tr>

			<td data-title="User">
				<?php
			   $args = array(
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
			
			
			$buildingreports = get_posts( $buildingargs ); 
			$reports = get_posts( $args ); 
			$buildingreportcount = count($buildingreports);
			$unitreportcount = count($reports);
			
			$report_ID = $reports[0]->ID;
			$buildingreport_ID = $buildingreports[0]->ID;
			
			$report_date = get_the_date('G:i:s | d-m-Y',$report_ID);
			
			$spied_nw = get_post_meta($report_ID, 'spied_nw', true);
			$spied_land = get_post_meta($report_ID, 'spied_land', true);
			$spy_array = get_post_meta($report_ID, 'spy_array', true);
			
			$building_array = get_post_meta($buildingreport_ID, 'spy_array', true);
			?>
				
				
				<?php if($report_ID):?><sup>Last spied: <?php echo $report_date;?></sup><br/><?php endif;?>
				<a class="<?php echo get_user_meta($member,'status',true);?>" href="/users/profile/?id=<?php echo $member;?>"><?php echo $member_data->display_name.' (#'.$member.')';?></a> <?php
						if(!empty($last_online)){
						if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?> 

			</td>
			<td sorttable_customkey="<?php echo $networth[0];?>" data-title="Networth">
				
			
			
				
				<?php if(($nw_att/1.4 <= $networth[0]) && ($networth[0] <= $nw_att*1.4)):?>
				<strong>$ <?php echo number_format($networth[0], 0, ',', ' '); ?></strong>
				<?php else:?>
				$ <?php echo number_format($networth[0], 0, ',', ' '); ?>
				<?php endif;?>
				<?php if($spied_nw):?> / $ <?php echo number_format($spied_nw, 0, ',', ' '); ?><?php endif;?>
			</td>
	
			<td sorttable_customkey="<?php echo $land[0];?>" data-title="Land" sorttable_customkey="<?php echo $land[0];?>">
				<?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup> <?php if($spied_land):?> / <?php echo number_format($spied_land, 0, ',', ' '); ?> m<sup>2</sup><?php endif;?>
			</td>
			
			<td data-title="Spy report">
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
				
				<script>
				jQuery(document).ready(function(){
					jQuery("#content_<?php echo $member;?>building").hide();
					
					jQuery("#show_<?php echo $member;?>building").click(function(){
					jQuery("#content_<?php echo $member;?>building").toggle();
					
					
				    jQuery('.fontawesome<?php echo $member;?>').toggle('1000');
				    jQuery(".fa", this).toggleClass("fa-arrow-right fa-arrow-left");
			
    				});

				});
				</script>
				
				<span style="border: 1px solid #172d3a;padding: 0px 10px;cursor: pointer" id="show_<?php echo $member;?>research">Unit report <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				<div  id="content_<?php echo $member;?>research" class="member_units">
					
					
					<?php 
						if($unitreportcount > 0){
						foreach($spy_array as $key => $amount){?>
						<?php if($key != 'enhance'):?>
							
							<?php if($amount > 49):?>
								<?php echo $key;?> 
								<strong><?php echo $amount;?></strong>
								<br/>
							<?php endif;?>
						<?php endif;?>
					<?php }?>
					
					<?php }?>
					
					
				</div>
				
				
				
				
				
				
				
				
				
				</td>
				<td>
					<span style="border: 1px solid #172d3a;padding: 0px 10px;cursor: pointer" id="show_<?php echo $member;?>building">Building report <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
				<div  id="content_<?php echo $member;?>building" class="member_units">
					
					
					<?php 
						if($buildingreportcount > 0){
						foreach($building_array as $key => $amount){?>
						<?php if($amount > 49):?>
						<?php echo $key;?> 
						<strong><?php 
							if($amount >= 50 && $amount < 100){echo '50-99';}
							if($amount >= 100 && $amount < 250){echo '100-249';}
							if($amount >= 250 && $amount < 500){echo '250-499';}
							if($amount >= 500 && $amount < 1000){echo '500-999';}
							if($amount >= 1000 && $amount < 2000){echo '1000-1999';}
							if($amount >= 2000 && $amount < 3000){echo '2000-2999';}
							if($amount >= 3000 && $amount < 5000){echo '3000-4999';}
							if($amount >= 5000 && $amount < 7500){echo '5000-7499';}
							if($amount >= 7500 && $amount < 10000){echo '7500-9999';}
							if($amount >= 10000 && $amount < 15000){echo '10000-14999';}
							if($amount >= 15000 && $amount < 20000){echo '15000-19999';}
							if($amount >= 20000 && $amount < 25000){echo '20000-24999';}
							if($amount >= 25000 && $amount < 30000){echo '25000-29999';}
						?>
						</strong>
						<br/>

						<?php endif;?>
					<?php }?>
					<br/><strong>All other buildings below 49</strong>
					
					
					<?php }?>
				</div>
				</td>
			<td data-title="Actions">
				<a href="/attack/step-1/?id=<?php echo $member;?>"><i class="fa fa-crosshairs fa-lg" aria-hidden="true"></i></a> <a href="/spy-reports/?id=<?php echo $member;?>"><i class="fa fa-binoculars" aria-hidden="true"></i></a>
			</td>
			
			
			<td>
				<form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">
				<input style="display:none" type="text" id="target_id"  name="target_id" value="<?php echo $member;?>"/>
				<input style="display:none;" type="radio" name="attacktype" checked id="spy" value="spy">
				<input type="submit" value="Re-spy" class="">					
			</form>
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