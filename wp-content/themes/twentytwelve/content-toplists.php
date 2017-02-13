<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			

		<div class="entry-content">
		<center><h1>Toplists</h1></center>
		
		
		<div class="container">
			<center>
			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Province networth</li>
			<li class="tab-link" data-tab="tab-2">Clan points</li>
			<li class="tab-link" data-tab="tab-3">Clan networth</li>

			</ul></center>
		<div id="tab-1" class="tab-content current">
		<table>
					<tr><td>Position</td>
						<td>Name</td>
						<td>Networth</td>
						<td>Clan</td>
					
  					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    => 'meta_value_num',
					'meta_key' => 'networth',
					'order'      => 'DESC');
				$users = get_users($args);
				
				foreach ($users as $user) {
					$user_NW = get_user_meta($user->ID, 'networth');
					$user_land = get_user_meta($user->ID, 'land');
	
					
				?>
				<tr>
				<td><?php echo $position+=1;?></td>
				<td><a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' #('.$user->ID;?>)</a></td>
				<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php 
					$user_clan = get_user_meta($user->ID, 'clan_id_user')[0];
					if($user_clan != 0){echo '<a href="'.get_the_permalink($user_clan).'">'.get_the_title($user_clan).' (#'.$user_clan.')</a>';}else{echo 'none';}?></td>
				
				
				</tr>
				<?php }?>
			</table>
		</div>
		
		
		<div id="tab-2" class="tab-content">
		<table>
					<tr><td>Position</td>
						<td>Name</td>
						<td>Clan points</td>
					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    	=> 'meta_value_num',
					'posts_per_page' => -1,
					'post_type'		=>	'clan',
					'meta_key' 		=> 'clan_points',
					'order'     	 => 'DESC');
				$clans = get_posts($args);
				
				foreach ($clans as $clan) {
				
	
	
				?>
				<tr>
				<td><?php echo $position+=1;?></td>
				<td><a href="<?php echo $clan->guid;?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a></td>
				<td><?php echo ceil(get_post_meta($clan->ID, 'clan_points')[0]);?></td>
				
				
				</tr>
				<?php }?>
			</table>
		</div>
		
		
		
		<div id="tab-3" class="tab-content">
		<table>
					<tr><td>Position</td>
						<td>Name</td>
						<td>Clan networth</td>
					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    	=> 'meta_value_num',
					'post_type'		=>	'clan',
					'posts_per_page' => -1,
					'meta_key' 		=> 'clan_networth',
					'order'     	 => 'DESC');
				$clans = get_posts($args);
				foreach ($clans as $clan) {
				
					$clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',$tot_networth);
				?>
				<tr>
				<td><?php echo $position+=1;?></td>
				<td><a href="<?php echo $clan->guid;?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a></td>
				<td>$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth')[0], 0, ',', ' ')?></td>
				
				
				</tr>
				<?php }?>
			</table>
		</div>
		
		
		</div>
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
