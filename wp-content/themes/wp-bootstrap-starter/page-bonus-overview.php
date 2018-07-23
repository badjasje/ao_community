<?php
 /*
 * Template Name: Bonus overview
*/

get_header(); 
global $userId;
global $userData;
include('bonus_array.php');
$backColor = "45, 67, 81";
$clan_ID = $userData['clan_id_user'][0];
$clan_points = get_post_meta($clan_ID, 'clan_points', true );

?>
<div class="row pageRow">	
	

<?php 
   $count = 0;
   $total_pts = 0;
   foreach ($bonus as $key => $bon) {
      $count++;
	  $total_pts+=$bon['points'];

	if(($bon['range'] <= $clan_points) && ($clan_points <= $bon['points'])):
		$width = ($clan_points-$bon['range'])/($bon['points']-$bon['range'])*100;
	elseif($clan_points > $bon['points']):
		$width = 100;
	else:
		$width = 0;
	endif;
	

   
?>
	           
<div class="col-md-6 bonusCol">
	<div class="blockHeader">
		<?php if($clan_points > $bon['points']):?>
			<i class="fas fa-check"></i>
		<?php endif;?> Level <?php echo $count;?> - <?php echo $bon['points'];?>pts
	</div>
	<div class="blockHeader spaceNotice">
		$<?php echo number_format($bon['money'], 0, ',', ' ');?> and <?php echo $bon['turns'];?> turns divided equally between each clan member.</span>
	</div>
	<div class="row fw-row no-gutters bonusRow">
		
		<div class="bonusTrack" style="width:<?php echo $width;?>%;background-color: rgba(<?php echo $backColor;?>, <?php echo 0.5-($count/70);?>);">
			<span class="bonusCompleted">Completed: <?php echo round($width);?>%</span>
		</div>
	</div>		
</div>		 
		   
				
<?php $count++; }?>
	
	
	
	
	
	
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();