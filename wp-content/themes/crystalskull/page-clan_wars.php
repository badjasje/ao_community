<?php
 /*
 * Template Name: Clan Wars
 */
$declarer_ID = get_current_user_ID();
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user');
$clan_leader = get_post_meta($declarer_clan_ID[0], 'clan_leader');

 $ct_1 = get_post_meta($declarer_clan_ID[0],'ct_1')[0];
 $ct_2 = get_post_meta($declarer_clan_ID[0],'ct_2')[0];
 $ct_3 = get_post_meta($declarer_clan_ID[0],'ct_3')[0];
 $ct_4 = get_post_meta($declarer_clan_ID[0],'ct_4')[0];

$clan_networth = get_post_meta($declarer_clan_ID[0], 'clan_networth', true);

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID[0]
));
$wars_by = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_on',
	'meta_value'	=> $declarer_clan_ID[0]
));
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>     
	       
           <div class="notice_message">
	           <span class="rdw-line">This is where you manage your clan wars.</span>
	           <span class="rdw-line">After 24 hours you are able to declare peace with a clan</span>
	           <span class="rdw-line">You can target clans with a networth between <?php echo GameUtil::format_networth($clan_networth/1.4); ?> and <?php echo GameUtil::format_networth($clan_networth*1.4);?></span>
           </div><br/>

			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice"></div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice">Peace declared</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Build more warfactories</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">Units ordered</div>
			<?php endif;?><?php endif;?>
			
			<table class="responsive-table">
				<tr>
					<th class="report_header"colspan="3"><center><strong>DECLARED WAR ON</strong></center>
					</th>
				</tr>
				
				<?php foreach ($wars_on as $war){
					$declared_on_ID = get_post_meta($war->ID, 'declared_on');
					$timestamp = strtotime(date('Y-m-d H:i:s'));
					
				?>
				<tr>
					<td><center><a href="<?php echo get_the_permalink($declared_on_ID[0]);?>"><?php echo get_the_title($declared_on_ID[0]).' (#'.$declared_on_ID[0];?>)</a></center>
					</td>
					<td data-title="Duration"><?php echo human_time_diff( get_the_title($war->ID), $timestamp );?>
					</td>
					<td><?php if($timestamp-get_the_title($war->ID) > 86400){?>
					<?php if($clan_leader[0] == $user_ID || $user_ID == $ct_1 || $user_ID == $ct_2 || $user_ID == $ct_3 || $user_ID == $ct_4):?>
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
					$timestamp = strtotime(date('Y-m-d H:i:s'));
					
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
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>