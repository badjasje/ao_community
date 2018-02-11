<?php
 /*
 * Template Name: Clan statistics
 */
$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

$list_pts_24h = get_post_meta($clan_ID, '24h_pts_list', true);
$list_nw_24h = get_post_meta($clan_ID, '24h_nw_list', true);

$clan_members = get_post_meta($clan_ID,'clan_members');

$clanleader = get_post_meta($clan_ID, 'clan_leader', true);
$timestamp = current_time('timestamp');
$ct_1 = get_post_meta($clan_ID,'ct_1',true);
$ct_2 = get_post_meta($clan_ID,'ct_2',true);
$ct_3 = get_post_meta($clan_ID,'ct_3',true);
$ct_4 = get_post_meta($clan_ID,'ct_4',true);
include('count_functions.php');


get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">



<div class="storeDetails-heads button_block">
	<center>
	<strong>Sort:</strong> <a href="" class="sort" data-sort=".memberField">Name</a> - 
	<a href="" class="sort sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort sort-number" data-sort=".land">Land</a> - 
	<a href="" class="sort sort-number" data-sort=".points">PPA</a> -
	<a href="" class="sort sort-number" data-sort=".spied">Spied</a>
	</center>
</div>

<!-- Own clan block -->


<div class="row profile_block storeDetails-heads">	

<div id="values">
<?php 
	$NRmembers = count($clan_members[0]);
	$counter = 0;
	foreach ($clan_members[0] as $key => $member) {
		$attacksMade = get_user_meta($member, 'in_war_attacks', true);
		$pts = get_user_meta($member, 'user_clan_points',true);
		$PPA = round($pts / $attacksMade,1);
		$networth = get_user_meta($member, 'networth', true);
		$land = get_user_meta($member, 'land', true);
		$turns = get_user_meta($member, 'turns', true);
		$money = get_user_meta($member, 'money', true);
		$morale = get_user_meta($member, 'morale', true);
		$pool = get_user_meta($member, 'morale_pool', true);
		$last_online = get_user_meta($member, 'last_online', true);
		$power = get_user_meta($member, 'power', true);
		
		$highest_networth = number_format(get_user_meta($member, 'highest_networth', true), 0, ',', ' ');
		$highest_land = number_format(get_user_meta($member, 'highest_land', true), 0, ',', ' ');
		
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
			<div class="col-md-2"><strong>Networth</strong></div>
			<div class="col-md-2"><strong>Land</strong></div>
			<div class="col-md-2"><strong>Points per attack</strong></div>
			<div class="col-md-2"><strong>Targets spied</strong></div>
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
					}?><a href="/military-overview/?id=<?php echo $member;?>"><i class="fa fa-search" aria-hidden="true"></i></a>
					
		
			
			
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
		<span class="hover-tip"  data-toggle="tooltip" data-original-title="Highest networth: $<?php echo $highest_networth;?>" data-placement="bottom">
		$ <?php echo number_format($networth, 0, ',', ' ');?></span>
		</span>

	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right land">
		<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Highest land: <?php echo $highest_land;?> m<sup>2</sup>" data-placement="bottom">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup></span>
		</span>
	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Points per attack</span>
		<span class="clan_data_right points">
		<?php echo number_format($PPA, 1, ',', ' '); ?>
		</span>
	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Targets spied</span>
		<span class="clan_data_right spied">
		<?php echo number_format($spiednr, 0, ',', ' '); ?>
		</span>
	</div>
	</div> <!-- End first member row -->
	
	
	
	
	
	<div class="row"> <!-- Start second member row -->
	
	<div class="row memberrowSecond">
	<div class="col-md-4"></div>
	<div class="col-md-2"><strong>Turns</strong></div>
	<div class="col-md-2"><strong>Money</strong></div>
	<div class="col-md-2"><strong>Morale</strong></div>
	<div class="col-md-2"><strong>Last online</strong></div>
	</div>
	
	<div class="row">
	<div class="col-md-4 clan_column"></div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Turns</span>
		<span class="clan_data_right">
		<?php echo $turns;?>
		</span>
		
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Money</span>
		<span class="clan_data_right">
		$ <?php echo number_format($money, 0, ',', ' ');?>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		
		<span class="clan_data_left">Morale</span>
		<span class="clan_data_right">
		<?php echo $morale;?>% <sup>(<?php echo $pool;?>%)</sup>
		</span>
		
	</div>
	<div class="col-md-2 clan_column">
		
		<span class="clan_data_left">Last online</span>
		<span class="clan_data_right">
			<?php echo date('H:i | d-m-y', $last_online);?>
		</span>
		
	</div>
		
	</div> <!-- End second member row -->
	
	
	
	
	
	
	
	<div class="row lastmemberrow"> <!-- Start second member row -->
	
	<div class="row memberrowSecond">
	<div class="col-md-4"></div>
	<div class="col-md-2"><strong>Unit types</strong></div>
	<div class="col-md-2"><strong>Can attack</strong></div>
	<div class="col-md-2"><strong>Power usage</strong></div>
	<div class="col-md-2"></div>
	</div>
	
	<div class="row">
	<div class="col-md-4 clan_column"></div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Unit types</span>
		<span class="clan_data_right">
		<?php 
			$typecount = count(unit_types($member));
			foreach (unit_types($member) as $type => $number) { $typecounter++;?>
			<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Owned: <?php echo $number;?>" data-placement="bottom">
			<?php echo $type;?></span><?php if($typecount > $typecounter){echo',';}?>
		<?php  }?>
		</span>
		
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Can attack</span>
		<span class="clan_data_right">
		<?php echo implode(", ", array_values(can_attack($member)));?>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Power usage</span>
		<span class="clan_data_right">
		<?php echo round($power);?>%
		</span>
		
		
	</div>
	<div class="col-md-2 clan_column">
	</div>
	</div>
		
	</div> <!-- End second member row -->
	
	
	
	
	
	<div class="row lastmemberrow"> <!-- Start second member row -->
	
	<div class="row bdsunitsrow">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#units_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units <?php echo count_tot_units($member);?></a>
		
		<div id="units_<?php echo $member;?>" class="collapse collapsebox">
				<?php foreach($units as $key => $order){
						$units_owned = get_user_meta($member, $key.'_owned',true);
						$units_ordered = get_user_meta($member, $key.'_ordered',true);
						if($units_owned > 0 || $units_ordered > 0){
				?>
				<span style="float:left;"><?php echo $order['normalname'];?></span> 
				<span style="float:right;"><?php echo $units_owned;?> (<?php echo $units_ordered;?>)</span>
				<br/>
					
					<?php }}?>
						
						
				
		</div>
		
	</div>
	<div class="col-md-4">
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#buildings_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings <?php echo count_tot_buildings($member);?></a>
		<div id="buildings_<?php echo $member;?>" class="collapse collapsebox">
			<?php foreach($buildings as $key => $order){
						$units_owned = get_user_meta($member, $key,true);
						$units_ordered = get_user_meta($member, $key,true);
						if($units_owned > 0 || $units_ordered > 0){
				?>
				<span style="float:left;"><?php echo $order['normalname'];?></span> 
				<span style="float:right;"><?php echo $units_owned;?></span>
				<br/>
					
					<?php }}?>
		</div>
		
	</div>
	</div>
	</div>
	
	
	
	
	
	
	
	
	</div>
</div> <!-- // End profile row -->

<?php $typecounter = 0;} ?>
</div>
<div id="result"></div>
</div>







<div class="chartitem"> 
	<h2>Clan points</h2>
<div class="chartWrapper">
	<div class="chartAreaWrapper">
		<canvas id="pointschart" height="400" width="1100"></canvas>
	</div>
</div>
</div>


<div class="chartitem"> 
	<h2>Clan networth</h2>
<div class="chartWrapper">
	<div class="chartAreaWrapper">
		<canvas id="nwchart" height="400" width="1100"></canvas>
	</div>
</div>
</div>













<script>
var ctx = document.getElementById("pointschart").getContext("2d");

var data = {
labels: [<?php echo "'".implode("','", array_keys($list_pts_24h))."'";?>],
datasets: [
    {
        label: "Clan points",
        fillColor: "rgba(45, 67, 80,0.2)",
        strokeColor: "rgb(45, 67, 80)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [<?php echo "'".implode("','", array_values($list_pts_24h))."'";?>]
    }
   
]
};

new Chart(ctx).Line(data, {
onAnimationComplete: function () {
    var sourceCanvas = this.chart.ctx.canvas;
    var copyWidth = this.scale.xScalePaddingLeft - 5;
    // the +5 is so that the bottommost y axis label is not clipped off
    // we could factor this in using measureText if we wanted to be generic
    var copyHeight = this.scale.endPoint + 5;
    var targetCtx = document.getElementById("myChartAxis").getContext("2d");
    targetCtx.canvas.width = copyWidth;
    targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
}
});
</script>

<script>
var ctx = document.getElementById("nwchart").getContext("2d");

var data = {
labels: [<?php echo "'".implode("','", array_keys($list_nw_24h))."'";?>],
datasets: [
    {
        label: "Networth",
        fillColor: "rgba(45, 67, 80,0.2)",
        strokeColor: "rgb(45, 67, 80)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [<?php echo "'".implode("','", array_values($list_nw_24h))."'";?>]
    }
   
]
};

new Chart(ctx).Line(data, {
onAnimationComplete: function () {
    var sourceCanvas = this.chart.ctx.canvas;
    var copyWidth = this.scale.xScalePaddingLeft - 5;
    // the +5 is so that the bottommost y axis label is not clipped off
    // we could factor this in using measureText if we wanted to be generic
    var copyHeight = this.scale.endPoint + 5;
    var targetCtx = document.getElementById("myChartAxis").getContext("2d");
    targetCtx.canvas.width = copyWidth;
    targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
}
});
</script>

       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>