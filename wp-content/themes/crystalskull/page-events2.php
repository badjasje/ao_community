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
	     
	    <div class="row battlereport-header">
		<div class="col-md-12"><img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/missile.png"> Battle report - Regular attack <span class="hover-tip" data-toggle="tooltip" data-original-title="$20 000 networth damage done" data-placement="right">
								<i class="fa fa-info-circle" aria-hidden="true"></i>
								</span></div>
		</div>
	    <div class="row event-row">
		<div class="col-md-2">
			<div class="row">
				<div class="col-md-12"><div class="attack-profile-image" style="background: url(&quot;http://assault.online/wp-content/uploads/2017/01/giphy-1.gif&quot;);background-size: cover;"></div>
				</div>
			</div>
			
		</div>
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">You were attacked by [Crim] Criminal Disaster (#283) and you lost the battle.<br/>In this attack 95 m2 and $ 8 979 was stolen. 
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 event-result"><strong>Attacker losses: 208 units</strong><br/>
Grenade Soldier: 41, Navy Seal: 35, Rifle infantry: 71, FIM-92 stinger: 61, <br/><br/>

<strong>Defender losses: 34 units and 13 buildings.</strong><br/>
B2 Stealth Bomber: 19, M2 Bradley: 15, Airfield: 3, Warfactory: 1, Powerplant: 3, Torpedo Launcher: 2, SAM Site: 1, Missile Turret: 1, Machinegun Turret: 2,
				</div>
			</div>
		</div>
	</div>
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
	     
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