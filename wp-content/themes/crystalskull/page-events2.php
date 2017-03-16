<?php
 /*
 * Template Name: Events2
 */
 $user_ID = get_current_user_id();
 $clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
 $list_24h = get_post_meta(79298, '24h_pts_list', true);
 $highest = max($list_24h);
 $lowest = min($list_24h);
 $number = count($list_24h);

get_header(); ?>


<div class="page normal-page">
     <div class="container">
	     
<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Earth</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moe_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moe_next', true);?> m<sup>2</sup></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moe_prev', true);?> m<sup>2</sup></div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Honor</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moh_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moh_next', true);?> pts</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moh_prev', true);?> pts</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Growth</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'mog_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'mog_next', true), 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'mog_prev', true), 0, ',', ' ');?></div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Courage</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moc_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moc_next', true);?> attacks</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'moc_prev', true);?> attacks</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Death</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'mod_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'mod_next', true);?> kills</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'mod_prev', true);?> kills</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Thievery</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'mot_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'mot_next', true), 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'mot_prev', true), 0, ',', ' ');?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header"><strong>Medal of Destruction</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo get_user_meta($user_ID, 'modes_position', true);?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'modes_next', true), 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format(get_user_meta($user_ID, 'modes_prev', true), 0, ',', ' ');?></div>
		</div>
	</div>
	
</div>
	     
	     
	     <br/><br/><br/>
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
        <div class="row">
            <div class="col-lg-12 col-md-12">
	  
	<div style="height:500px;background-color:#2d4350;width:100%;min-width:1000px;  overflow-x: auto;
    white-space: nowrap;">
		<?php foreach ($list_24h as $key => $pts){ ?>
		<div style="position: relative;
			
					<?php if($pts == $highest):?>
					background-color:#2d4350;
					<?php else:?>
					background-color:#ddd;
					<?php endif;?>
					height:<?php 
					$height = 100-($pts/$highest*100);
					if($height == 0){ $height = 100;}
					echo $height;?>%;width:<?php echo 100/$number;?>%;float: left;text-align:center;">
			<div class="barlabels">
			<?php echo $pts;?>
			</div>
			</div>
		
		<?php }?>
	
	</div>
		        
	            
	            
	            
	            
		            
	         
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>