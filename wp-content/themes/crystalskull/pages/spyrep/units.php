<?php
$args = array(
	'posts_per_page'   => 1,
	'author__in'	=> $members,
	'meta_query'	=> array(
'relation'		=> 'AND',
	array(
		'key'	 	=> 'spied_id',
		'value'	  	=> $target_id,
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
$reports = get_posts( $args ); 
		
		
$count = 0;

foreach ($reports as $report) {
	$author = $report->post_author;
	$member_data = get_userdata($author);
	$spy_array = get_post_meta($report->ID, 'spy_array', true);	
	$count++;
	?>
	
	

<div class="spaceNotice">
	<div class="row">
		<div class="col-md-6">
		Registered: <strong><?php echo number_format(get_post_meta($report->ID, 'spied_land', true), 0, ',', ' '); ?> m<sup>2</sup></strong><br/>
		Current: <strong><?php echo number_format(get_user_meta($target_id, 'land', true), 0, ',', ' '); ?> m<sup>2</sup></strong>
		</div>
				
		<div class="col-md-6">
		Registered: <strong>$ <?php echo number_format(get_post_meta($report->ID, 'spied_nw', true), 0, ',', ' ');?></strong><br/>
		Current: <strong>$ <?php echo number_format(get_user_meta($target_id, 'networth', true), 0, ',', ' ');?></strong>
		</div>
	</div>
</div> <!-- end notice message -->
			
			
<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-6"><strong>Owned</strong></div>
	</div>	
			

<?php foreach ($spy_array as $building => $amount) { ?>
	
	<?php if($building != 'enhance'):?>
	
	
		<div class="row clan_profile_row2">
		
			<div class="col-md-6 center_clan_col market_column marketHeader">
				<?php echo $building;?>
			</div>
	
			<div class="col-md-6 clan_column">
				<span class="clan_data_left">Owned</span>
				<span class="clan_data_right">
					<?php echo $amount;?>
				</span>
		
			</div>
		</div>
	
		
	<?php endif;?>
		
<?php }?>
			
</div>		
<?php }?>
<div class="col-md-12 totalsField">
	<?php if($count > 0):?>
		<center>
			Last spied by <a href="/users/profile/?id=<?php echo $author;?>">
			<?php echo $member_data->display_name.' (#'.$author.')';?></a> \ <?php echo $report->post_date;?> 
			
			<?php if($spy_array['enhance'] > 0):?>
			<strong>Enhanced <?php echo $spy_array['enhance'];?> times</strong>
			<?php endif;?>
		</center>
	<?php else:?>
		<center>No spy reports for this player</center>
	<?php endif;?>
</div>