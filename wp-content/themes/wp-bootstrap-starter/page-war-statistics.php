<?php
 /*
 * Template Name: War stats template
 
 */
get_header(); 
global $userData;
global $userId;

$clanId = get_user_meta($userId, 'clan_id_user',true);
$members = get_post_meta($clanId,'clan_members');

$war_array = maybe_unserialize(get_post_meta($clanId, 'war_array', true));
$war_array = maybe_unserialize($war_array[$_GET['id']]);
?>
<div class="row pageRow no-gutters">	
	



<div class="blockHeader">War against 
	<?php if($clanId == $war_array['declarer_id']):?>
		<a href="<?php echo get_the_permalink($war_array['receiver_id']);?>">
			<?php echo get_the_title($war_array['receiver_id']);?> (#<?php echo $war_array['receiver_id'];?>)
		</a>
	<?php elseif($clanId == $war_array['receiver_id']):?>
		<a href="<?php echo get_the_permalink($war_array['declarer_id']);?>">
			<?php echo get_the_title($war_array['declarer_id']);?> (#<?php echo $war_array['declarer_id'];?>)
		</a>
	<?php endif;?>
</div>



<div class="col-md-6">
	<div class="blockHeader spaceNotice">Outgoing</div>
	
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks made</span>
			<span class="dataVisibleRight"><?php echo $war_array['attacks_made'];?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful attacks</span>
			<span class="dataVisibleRight"><?php echo $war_array['successfull_att'];?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles sent</span>
			<span class="dataVisibleRight"><?php echo $war_array['missiles_sent'];?>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?php echo $war_array['missiles_hit_att'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage done</span>
			<span class="dataVisibleRight">$ <?php echo number_format($war_array['nw_dmg_done'], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Highest networth damage</span>
			<span class="dataVisibleRight">$ <?php echo number_format($war_array['highest_nw_dmg'], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings destroyed</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['bds_killed'], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units killed</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['units_killed'], 0, ',', ' ');?></span>
		</div>
	
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land gained</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['land_gained'], 0, ',', ' ');?> m<sup>2</sup></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money gained</span>
			<span class="dataVisibleRight">$ <?php echo number_format($war_array['money_gained'], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Clan points</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['clan_points'], 0, ',', ' ');?></span>
		</div>
	
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Kills</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['kills'], 0, ',', ' ');?></span>
		</div>
		
	</div>
</div>


<div class="col-md-6">
	<div class="blockHeader spaceNotice">Incoming</div>
	<div class="row unitRow">
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Attacks received</span>
			<span class="dataVisibleRight"><?php echo $war_array['attacks_received'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Successful defends</span>
			<span class="dataVisibleRight"><?php echo $war_array['successfull_def'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles received</span>
			<span class="dataVisibleRight"><?php echo $war_array['missiles_received'];?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Missiles hit</span>
			<span class="dataVisibleRight"><?php echo $war_array['missiles_hit_def'];?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Networth damage received</span>
			<span class="dataVisibleRight">$ <?php echo number_format($war_array['nw_dmg_rec'], 0, ',', ' ');?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Buildings lost</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['bds_lost'], 0, ',', ' ');?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Units lost</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['units_lost'], 0, ',', ' ');?></span>
		</div>
				
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Land lost</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['land_lost'], 0, ',', ' ');?> m<sup>2</sup></span>
		</div>

		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Money lost</span>
			<span class="dataVisibleRight">$ <?php echo number_format($war_array['money_lost'], 0, ',', ' ');?></span>
		</div>
		
		<div class="col-md-12 celBlock">
			<span class="dataVisibleLeft">Deaths</span>
			<span class="dataVisibleRight"><?php echo number_format($war_array['deaths'], 0, ',', ' ');?></span>
		</div>
		
	</div>
</div>
	
</div> <!-- end .pageRow -->
<?php
get_footer();