<?php
 /*
 * Template Name: Bonus overview
 */
include('bonus_array.php');
$user_ID = get_current_user_ID();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$clan_points = get_post_meta($clan_ID,'clan_points',true);
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	         <center><h2><i class="fa fa-line-chart" aria-hidden="true"></i> <?php echo $clan_points;?>pts</h2></center>
	           <?php 
		           $count = 0;
		           $total_pts = 0;
		           foreach ($bonus as $key => $bon) {
			          $count++;
					  $total_pts+=$bon['points'];
		          
		           
	           ?>
	           
	           
	           
	           
			   <div id="progress">
				    <div id="percent">
						<h3 class="startinghead">
							<?php if($clan_points > $bon['points']):?>
								<i class="fa fa-check-circle-o" aria-hidden="true"></i>
							<?php endif;?> Level <?php echo $count;?> - <?php echo $bon['points'];?>pts
						</h3>
				    <span class="rdw-line-2"><span class="rdw-line-2">$<?php echo number_format($bon['money'], 0, ',', ' ');?> and <?php echo $bon['turns'];?> turns divided equally between each clan member.</span>
				    </div>
				    <div id="bar" style=" width: 
					    
					    <?php if(($bon['range'] <= $clan_points) && ($clan_points <= $bon['points'])):?>
							<?php echo (($clan_points-$bon['range'])/($bon['points']-$bon['range']))*100;?>%;">
						<?php elseif($clan_points > $bon['points']):?>
							100%;">
						<?php else:?>
							0%;">
						<?php endif;?>
							 <?php if(($bon['range'] <= $clan_points) && ($clan_points <= $bon['points'])):?>
							<?php echo number_format((($clan_points-$bon['range'])/($bon['points']-$bon['range']))*100, 0, '.', '');?>%
						<?php elseif($clan_points > $bon['points']):?>
							100%
						<?php else:?>
							
						<?php endif;?>
					</div>
				</div>
				
	           
	        
	           
		       	           
	           
	           
	           <?php }?>
	         
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>