<?php
/**
 * Template Name: Top pts list
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<table>
					<tr><td>Position</td>
						<td>Name</td>
						<td>Points</td>
						<td>Clan</td>
					
  					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    => 'meta_value_num',
					'meta_key' => 'user_clan_points',
					'order'      => 'DESC');
				$users = get_users($args);
				
				foreach ($users as $user) {
					$user_NW = get_user_meta($user->ID, 'user_clan_points');
					$user_land = get_user_meta($user->ID, 'land');
	
					
				?>
				<tr>
				<td><?php echo $position+=1;?></td>
				<td><a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' #('.$user->ID;?>)</a></td>
				<td><?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php 
					$user_clan = get_user_meta($user->ID, 'clan_id_user')[0];
					if($user_clan != 0){echo '<a href="'.get_the_permalink($user_clan).'">'.get_the_title($user_clan).' (#'.$user_clan.')</a>';}else{echo 'none';}?></td>
				
				
				</tr>
				<?php }?>
			</table>


		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>