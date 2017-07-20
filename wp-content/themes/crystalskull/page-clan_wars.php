<?php
 /*
 * Template Name: Clan Wars
 */
$declarer_ID = get_current_user_ID();
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user',true);
$clan_leader = get_post_meta($declarer_clan_ID, 'clan_leader',true);

$war_array = get_post_meta($declarer_clan_ID, 'war_array', true);

 $ct_1 = get_post_meta($declarer_clan_ID,'ct_1',true);
 $ct_2 = get_post_meta($declarer_clan_ID,'ct_2',true);
 $ct_3 = get_post_meta($declarer_clan_ID,'ct_3',true);
 $ct_4 = get_post_meta($declarer_clan_ID,'ct_4',true);

$clan_networth = get_post_meta($declarer_clan_ID, 'clan_networth', true);

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID
));
$wars_by = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_on',
	'meta_value'	=> $declarer_clan_ID
));
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
			<?php if(!empty($_SESSION['status'])):?>
				<?php echo alert_notification($_SESSION['status']);?>
			<?php endif; // End empty status check ?>
	            
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>     
	       
           <div class="notice_message">
	           <span class="rdw-line">This is where you manage your clan wars.</span>
	           <span class="rdw-line">After 24 hours you are able to declare peace with a clan. A war will auto peace after 72 hours.</span>
	           <span class="rdw-line">You can target clans with a networth between <?php echo GameUtil::format_networth($clan_networth/1.4); ?> and <?php echo GameUtil::format_networth($clan_networth*1.4);?></span>
           </div><br/>

			
			<table class="responsive-table">
				<tr>
					<th class="report_header"colspan="3"><center><strong>DECLARED WAR ON</strong></center>
					</th>
				</tr>
				
				<?php foreach ($wars_on as $war){
					$declared_on_ID = get_post_meta($war->ID, 'declared_on');
					$timestamp = current_time('timestamp');
					
				?>
				<tr>
					<td><center><a href="<?php echo get_the_permalink($declared_on_ID[0]);?>"><?php echo get_the_title($declared_on_ID[0]).' (#'.$declared_on_ID[0];?>)</a></center>
					</td>
					<td data-title="Duration"><?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
					</td>
					<td><?php if($timestamp-get_the_title($war->ID) > 86400){?>
					<?php if($clan_leader == $user_ID || $user_ID == $ct_1 || $user_ID == $ct_2 || $user_ID == $ct_3 || $user_ID == $ct_4):?>
					<a onclick="return confirm('Are you sure you want to declare peace?')" class="btn btn-general" href="/declare_peace.php/?war=<?php echo $war->ID;?>">DECLARE PEACE</a>
					<?php endif;?>
					
					<?php }?>
					<a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $declared_on_ID[0];?>">Spy report overview</a>
					</td>
				</tr>
				<?php }?>
			</table>
			
			
			
			
			
			<table class="responsive-table">
				<tr>
					<th class="report_header" colspan="3"><center><strong>DECLARED WAR BY</strong></center>
					</th>
				</tr>
				
				<?php foreach ($wars_by as $war){
					$declared_on_ID = get_post_meta($war->ID, 'declared_by');
					$timestamp = current_time('timestamp');
					
				?>
				<tr>
					<td><center><a href="<?php echo get_the_permalink($declared_on_ID[0]);?>"><?php echo get_the_title($declared_on_ID[0]).' (#'.$declared_on_ID[0];?>)</a></center>
					</td>
					<td data-title="Duration"><?php echo human_time_diff( get_the_title($war->ID), $timestamp );?> 
					</td>
					<td>
					</td>
				</tr>
				<?php }?>
			</table>
			<?php endif;?>
            
<div class="row profile_block">	
<div class="row clan_header_row ">
	<div class="col-md-2"><strong>Date</strong></div>
	<div class="col-md-3"><strong>War against</strong></div>
	<div class="col-md-3"><strong>First declared by</strong></div>
	<div class="col-md-1"><strong>Mutual?</strong></div>
	<div class="col-md-3"></div>

</div>

<?php foreach ($war_array as $key => $war) {
	$warred_clan =  array_shift(array_diff(array($war['declarer_id'],$war['receiver_id']), array($declarer_clan_ID)));
	
?>
<div class="row clan_profile_row">
	<div class="col-md-2"><?php echo date('H:i | d-m-Y', $war['date']);?></div>
	<div class="col-md-3">
		<a href="<?php echo get_the_permalink($warred_clan);?>"><?php echo get_the_title($warred_clan);?></a></div>
	<div class="col-md-3">
		<a href="<?php echo get_the_permalink($war['declarer_id']);?>"><?php echo get_the_title($war['declarer_id']);?></a>
	</div>
	<div class="col-md-1">
		<?php if($war['mutual_date'] != 0):?>
			Yes
		<?php endif;?>
	</div>
	<div class="col-md-3">
		<a class="btn btn-general profilebutton" href="/war-statistics/?id=<?php echo $key;?>">
		 <i class="fa fa-graph" aria-hidden="true"></i> &nbsp;View statistics</a>
	</div>


</div>
<?php }?>
            
            
            
            
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>