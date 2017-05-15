<?php
 /*
 * Template Name: Spy Reports
 */
$target_id = $_GET['id'];
$user = get_userdata($target_id);
$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$target_clan_ID = get_user_meta($target_id, 'clan_id_user',true);

$members = get_post_meta($clan_ID,'clan_members',true);
$members[] = $user_ID;
get_header('spyrep'); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            
	        <?php if(get_field('game_status','option') != 'Live'):?>
<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
<?php else:?>
	            
	            <center>
<a class="btn btn-general" href="/users/profile/?id=<?php echo $target_id;?>"><i class="fa fa-user-o" aria-hidden="true"></i> Profile</a> 
<a class="btn btn-general" href="/attack/step-1/?id=<?php echo $target_id;?>"><i class="fa fa-crosshairs" aria-hidden="true"></i> Attack</a> 
<?php if($target_clan_ID != 0):?>
<a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $target_clan_ID;?>"><i class="fa fa-address-card-o" aria-hidden="true"></i> Clan reports</a> 
<?php endif;?>

	          </center>
	            <center><ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Buildings</li>
			<li class="tab-link" data-tab="tab-2">Units</li>
			</ul></center>
			
			<div id="tab-1" class="tab-content current">
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
						'value'	  	=> 'spyplane',
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
	
	
<div class="notice_message">
	Last spied by <a href="/users/profile/?id=<?php echo $author;?>"><?php echo $member_data->display_name.' (#'.$author.')';?></a> \ <?php echo $report->post_date;?> 
		
	<?php if($spy_array['enhance'] > 0):?>
		<br/>Enhanced <?php echo $spy_array['enhance'];?> times	
	<?php endif;?>
		
		
<table style="margin-bottom:0px;"class="responsive-table">
	<tbody>
		<tr>
			<td style="color:#fff">
				Registered: <strong><?php echo number_format(get_post_meta($report->ID, 'spied_land', true), 0, ',', ' '); ?> m<sup>2</sup></strong><br/>
				Current: <strong><?php echo number_format(get_user_meta($target_id, 'land', true), 0, ',', ' '); ?> m<sup>2</sup></strong>
			</td>
			
			<td style="color:#fff">
				Registered: <strong>$ <?php echo number_format(get_post_meta($report->ID, 'spied_nw', true), 0, ',', ' ');?></strong><br/>
				Current: <strong>$ <?php echo number_format(get_user_meta($target_id, 'networth', true), 0, ',', ' ');?></strong>
			</td>
		</tr>
	</tbody>
</table>

</div> <!-- end notice message -->

<br/>
			
			
			
			
<table class="responsive-table">
	<thead>
		<th scope="col">Name</th>
		<th scope="col">Owned</th>
	</thead>
	<tbody>
	
	<?php foreach ($spy_array as $building => $amount) { ?>
		
		<?php if($building != 'enhance'):?>
			
			<tr>
				<td data-title="Name"><?php echo $building;?></td>
				<td data-title="Owned"><?php echo $amount;?></td>	
			</tr>
			
		<?php endif;?>
			
		<?php }?>
		
			
			</tbody>
			</table>
			
			<?php if($count == 0):?>
			<div class="notice_message">No spy reports for this player.</div><br/>
            <?php endif;?>
			</div>
			
			<?php }?>
			
			<div id="tab-2" class="tab-content">
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
			<div class="notice_message">
			Last spied by <a href="/users/profile/?id=<?php echo $author;?>"><?php echo $member_data->display_name.' (#'.$author.')';?></a> \ <?php echo $report->post_date;?> 
			<?php if($spy_array['enhance'] > 0):?>
			<br/>Enhanced <?php echo $spy_array['enhance'];?> times
			<?php endif;?>
			<table style="margin-bottom:0px;"class="responsive-table">
				<tbody>
				
				<tr>
					<td style="color:#fff">
					Registered: <strong><?php echo number_format(get_post_meta($report->ID, 'spied_land', true), 0, ',', ' '); ?> m<sup>2</sup></strong><br/>
					Current: <strong><?php echo number_format(get_user_meta($target_id, 'land', true), 0, ',', ' '); ?> m<sup>2</sup></strong>
					</td>
					<td style="color:#fff">
					Registered: <strong>$ <?php echo number_format(get_post_meta($report->ID, 'spied_nw', true), 0, ',', ' ');?></strong><br/>
					Current: <strong>$ <?php echo number_format(get_user_meta($target_id, 'networth', true), 0, ',', ' ');?></strong>
					</td>
				</tr>
				</tbody>
			</table>
			</div><br/>
			<table class="responsive-table">
				<thead>
				<th scope="col">Name</th>
				<th scope="col">Owned</th>
				</thead>
				<tbody>
			<?php 
			
				foreach ($spy_array as $unit => $amount) {
				
			?>
			<?php if($unit != 'enhance'):?>
			<tr>
				<td data-title="Name"><?php echo $unit;?></td>
				<td data-title="Owned"><?php echo $amount;?></td>	
			</tr>
			<?php endif;?>
			<?php }?>
		
			</tbody>
			</table>
			<?php }?>
			<?php if($count == 0):?>
			<div class="notice_message">No spy reports for this player.</div><br/>
            <?php endif;?>
			
			
			
            
            </div>
            <form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">
				<input style="display:none" type="text" id="target_id"  name="target_id" value="<?php echo $target_id;?>"/>
				<input style="display:none;" type="radio" name="attacktype" checked id="spy" value="spy">
				<input type="submit" value="Re-spy" class="">					<br/><br/>
			</form>
			<?php endif;?>
        </div>
    </div>
</div>
<?php get_footer(); ?>