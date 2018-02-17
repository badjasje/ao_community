<?php
 /*
 * Template Name: Clan player statistics
 */
get_header();
$userId = get_current_user_id();
$userData = get_user_meta($userId);
$clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_ID);


$list_pts_24h = maybe_unserialize($clanData['24h_pts_list'][0]);
$list_nw_24h = maybe_unserialize($clanData['24h_nw_list'][0]);

$clan_members = maybe_unserialize($clanData['clan_members'][0]);
$clanleader = $clanData['clan_leader'][0];

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];
include('count_functions.php');
include('research_array.php');
 ?>
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
	$NRmembers = count($clan_members);
	$counter = 0;
	foreach ($clan_members as $key => $member) {
		$timestamp = current_time('timestamp');
		$memberData = get_user_meta($member);
		$attacksMade = $memberData['in_war_attacks'][0];
		$pts = $memberData['user_clan_points'][0];
		$PPA = round($pts / $attacksMade,1);
		$networth = $memberData['networth'][0];
		$land = $memberData['land'][0];
		$turns = $memberData['turns'][0];
		$money = $memberData['money'][0];
		$morale = $memberData['morale'][0];
		$pool = $memberData['morale_pool'][0];
		$last_online = $memberData['last_online'][0];
		$power = $memberData['power'][0];
		
		$totAidSent = $memberData['total_aid_sent'][0];
		$noAids = $memberData['number_of_aids'][0];
		$aidRec = $memberData['aid_received'][0];
		
		$attMade = $memberData['attacks_made_current'][0];
		$attRec = $memberData['attacks_rec_current'][0];
		
		$highest_networth = number_format($memberData['highest_networth'][0], 0, ',', ' ');
		$freeLand = number_format($memberData['land'][0]-$memberData['builtland'][0], 0, ',', ' ');
		
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		$member_data = get_userdata($member);
		$last_online = $memberData['last_online'][0];
		$spiednr = $memberData['spied_current_clan'][0];
		
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
			<?php echo get_user_name($member);?>
			<a href="/military-overview/?id=<?php echo $member;?>"><i class="fa fa-search" aria-hidden="true"></i></a>
					
		
			
			
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
		<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Free land: <?php echo $freeLand;?> m<sup>2</sup>" data-placement="bottom">
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
	<div class="col-md-2"><strong>Attacks made</strong></div>
	<div class="col-md-2"><strong>Attacks received</strong></div>
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
		<?php  echo rtrim(can_attack($member),", ");?>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Attacks made</span>
		<span class="clan_data_right">
			<?php echo $attMade;?>
		</span>
		
		
	</div>
	<div class="col-md-2 clan_column">
		<span class="clan_data_left">Attacks received</span>
		<span class="clan_data_right">
			<?php echo $attRec;?>
		</span>
	</div>
	</div>
		
	</div> <!-- End second member row -->
	
	
	
	
	
	
	<div class="row lastmemberrow"> <!-- Start third member row -->
	
	<div class="row memberrowSecond">
	<div class="col-md-4"></div>
	<div class="col-md-2"><strong>Aid sent</strong></div>
	<div class="col-md-2"><strong>Aid received</strong></div>
	<div class="col-md-2"><strong>Power usage</strong></div>
	<div class="col-md-2"></div>
	</div>
	
	<div class="row">
	<div class="col-md-4 clan_column"></div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Aid sent</span>
		<span class="clan_data_right">
		<span class="hover-tip"  data-toggle="tooltip" data-html="true"  data-original-title="Aided in <?php echo $noAids;?> times." data-placement="bottom">
			$ <?php echo number_format($totAidSent, 0, ',', ' ');?></span>
			</span>
		
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Aid received</span>
		<span class="clan_data_right">
			$ <?php echo number_format($aidRec, 0, ',', ' ');?></span>
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
	<div class="col-md-4">
		
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#research_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Research</a>
		
		<div id="research_<?php echo $member;?>" class="collapse collapsebox">
				<?php 
					$inprogress = $memberData['research_in_progress'][0];
					foreach ($researches as $key => $research) {
					$level = $memberData['level_'.$key][0];
					 ?>	
		
				<span style="float:left;"><?php echo $research['name'];?></span> 
				<span style="float:right;">Level: <?php echo $level;?></span>
				<br/>
					
					<?php }?>
				<?php if($inprogress != '0'):?>
					<br/>
					<strong>In progress: <?php echo $researches[$inprogress]['name'];?></strong>
				<?php endif;?>
				
		</div>
		
	</div>
	<div class="col-md-4">
		
		<a class="btn btn-general profilebutton" data-toggle="collapse" href="#units_<?php echo $member;?>">
		 	<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units <?php echo count_tot_units($member);?></a>
		
		<div id="units_<?php echo $member;?>" class="collapse collapsebox">
				<?php foreach($units as $key => $order){
						$units_owned = $memberData[$key.'_owned'][0];
						$units_ordered = $memberData[$key.'_ordered'][0];
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
						$units_owned = $memberData[$key][0];
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