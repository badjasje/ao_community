<?php
 /*
 * Template Name: Clan Wars
 */
$declarer_ID = get_current_user_ID();
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user',true);
$clan_leader = get_post_meta($declarer_clan_ID, 'clan_leader',true);
$timestamp = current_time('timestamp');
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


<div class="row profile_block">	
	<div class="col-md-12">
		<h3>War declared on</h3><hr/>
	</div>
	<div class="row clan_header_row ">
		<div class="col-md-4"><strong>Clan</strong></div>
		<div class="col-md-2"><strong>Date</strong></div>
		<div class="col-md-2"><strong>Duration</strong></div>
		<div class="col-md-4"></div>
	</div>
	
	<?php foreach ($wars_on as $war){
			$declared_on_ID = get_post_meta($war->ID, 'declared_on',true);
			?>
	
	<div class="row clan_profile_row">
		<div class="col-md-4 border_bottom_mobile clan_column">
			<span class="clan_data_left">Clan</span>
			<span class="clan_data_right">
				<a href="<?php echo get_the_permalink($declared_on_ID);?>"><?php echo get_the_title($declared_on_ID).' (#'.$declared_on_ID;?>)</a>
			</span>
		</div>
		<div class="col-md-2 border_bottom_mobile clan_column">
			<span class="clan_data_left">Date</span>
			<span class="clan_data_right">
				<?php echo get_the_date('G:i | d-m-Y',$war->ID); ?>
			</span>
		</div>
		<div class="col-md-2 border_bottom_mobile clan_column">
			<span class="clan_data_left">Duration</span>
			<span class="clan_data_right">
				<?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
			</span>
		</div>
		<div class="col-md-4">
			<a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $declared_on_ID;?>">Spy report overview</a>
		</div>
	</div>
	<?php }?>
</div>





<div class="row profile_block">
	<div class="col-md-12">
		<h3>War declared by</h3><hr/>
	</div>
	<div class="row clan_header_row ">
		<div class="col-md-4"><strong>Clan</strong></div>
		<div class="col-md-2"><strong>Date</strong></div>
		<div class="col-md-2"><strong>Duration</strong></div>
		<div class="col-md-4"></div>
	</div>
	
	<?php foreach ($wars_by as $war){
			$declared_by_ID = get_post_meta($war->ID, 'declared_by',true);
			?>
	
	<div class="row clan_profile_row">
		<div class="col-md-4 border_bottom_mobile clan_column">
			<span class="clan_data_left">Clan</span>
			<span class="clan_data_right">
				<a href="<?php echo get_the_permalink($declared_by_ID);?>"><?php echo get_the_title($declared_by_ID).' (#'.$declared_by_ID;?>)</a>
			</span>
		</div>
		<div class="col-md-2 border_bottom_mobile clan_column">
			<span class="clan_data_left">Date</span>
			<span class="clan_data_right">
				<?php echo get_the_date('G:i | d-m-Y',$war->ID); ?>
			</span>
		</div>
		<div class="col-md-2 border_bottom_mobile clan_column">
			<span class="clan_data_left">Duration</span>
			<span class="clan_data_right">
				<?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
			</span>
		</div>
		<div class="col-md-4">
			<a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $declared_by_ID;?>">Spy report overview</a>
		</div>
	</div>
	<?php }?>
</div>


			
			
<?php endif;?>
            
<div class="row profile_block">	
	<div class="col-md-12">
		<h3>War statistics</h3><hr/>
	</div>
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
	<div class="col-md-2 border_bottom_mobile clan_column">
		<span class="clan_data_left">Date</span>
		<span class="clan_data_right">
			<?php echo date('H:i | d-m-Y', $war['date']);?>
		</span>
	</div>
	<div class="col-md-3 border_bottom_mobile clan_column">
		<span class="clan_data_left">War against</span>
		<span class="clan_data_right">
			<a href="<?php echo get_the_permalink($warred_clan);?>"><?php echo get_the_title($warred_clan);?></a>
		</span>
	</div>
	<div class="col-md-3 border_bottom_mobile clan_column">
		<span class="clan_data_left">First declared</span>
		<span class="clan_data_right">
			<a href="<?php echo get_the_permalink($war['declarer_id']);?>"><?php echo get_the_title($war['declarer_id']);?></a>
		</span>
	</div>
	<div class="col-md-1 border_bottom_mobile clan_column">
		<span class="clan_data_left">Mutual?</span>
		<span class="clan_data_right">
			<?php if($war['mutual_date'] != 0):?>
				Yes
			<?php endif;?>
		</span>
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