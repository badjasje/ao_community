<?php
$backColor = "45, 67, 81";
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

$count = 0;
$reports = get_posts( $args );
foreach ($reports as $report) {
	$author = $report->post_author;
	$member_data = get_userdata($author);
	$spy_array = get_post_meta($report->ID, 'spy_array', true);
	$count++;
?>
<div class="blockHeader spaceNotice">
	<div class="row">
		<div class="col-md-6">
			<span class="dataVisibleLeft">Registered:</span>
			<span class="dataVisibleRight">
			<?php echo number_format(get_post_meta($report->ID, 'spied_land', true), 0, ',', ' '); ?> m<sup>2</sup>
			</span><br/>
			<span class="dataVisibleLeft">
			Current:
			</span><span class="dataVisibleRight"><?php echo number_format(get_user_meta($target_id, 'land', true), 0, ',', ' '); ?> m<sup>2</sup>
			</span>
			</div>

		<div class="col-md-6">
		<span class="dataVisibleLeft">Registered:</span>
		<span class="dataVisibleRight">$ <?php echo number_format(get_post_meta($report->ID, 'spied_nw', true), 0, ',', ' ');?></span><br/>
		<span class="dataVisibleLeft">Current: </span><span class="dataVisibleRight">$ <?php echo number_format(get_user_meta($target_id, 'networth', true), 0, ',', ' ');?></span>
		</div>
	</div>
</div>

<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-6 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-6 celBlock">
		Owned (ordered)
    </div>
</div> <!-- //Close Unit row -->

<?php foreach ($spy_array as $unit => $amount): ?>

	<?php if($unit == 'enhance'){ continue; }?>


<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">

	<div class="col-md-6 nameBlock sea_heading celBlock">
		<?php echo $unit;?>
	</div>

	<div class="col-md-6 celBlock">
		<span class="columnDataLeft">Owned</span>
			<span class="columnDataRight">
				<?php echo $amount;?>
			</span>
		</div>
	</div>


<?php endforeach;?>



<?php }?>
<div class="blockHeader">
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