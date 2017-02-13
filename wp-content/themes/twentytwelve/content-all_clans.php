<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 $user_ID = get_current_user_id();
 $clan_ID = get_user_meta($user_ID, 'clan_id_user');
 
 
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<center><h1>All clans</h1></center>
			
		<?php if($clan_ID == 0):?>	
			
			
			
		<table>
			<tr>
				<td>Clan
				</td>
				<td>Total networth
				</td>
			</tr>
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'clan'
			);
			$clans = get_posts($args);
			foreach ($clans as $clan) { 
				
			
				
						
			?>
			<tr>
				<td><a href="<?php echo get_the_permalink($clan->ID);?>"><?php echo get_the_title($clan->ID). ' (#'.$clan->ID;?>)</a>
				</td>
				<td>$ 
				<?php $clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',$tot_networth);
					echo number_format($tot_networth, 0, ',', ' ');
					
				?>
				</td>
			</tr>
			
			<?php }?>
		</table>
		
		
		
		<?php else:?>
		
		
		<div class="container">

			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">All clans</li>
			<li class="tab-link" data-tab="tab-2">In range</li>
			</ul>	
		</center>
		
		<br/>
		<div id="tab-1" class="tab-content current">
		
		
		<table>
			<tr>
				<td>Clan
				</td>
				<td>Total networth
				</td>
			</tr>
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'clan'
			);
			$clans = get_posts($args);
			foreach ($clans as $clan) { 
						
			?>
			<tr>
				<td><a href="<?php echo get_the_permalink($clan->ID);?>"><?php echo get_the_title($clan->ID). ' (#'.$clan->ID;?>)</a>
				</td>
				<td>$ 
				<?php $clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',$tot_networth);
					echo number_format($tot_networth, 0, ',', ' ');
					
				?>
				</td>
			</tr>
			
			<?php }?>
		</table>
		</div> <!-- CLOSE TAB 1 -->
		
		
		
		
		
		<div id="tab-2" class="tab-content">
			
			
			
			<table>
			<tr>
				<td>Clan
				</td>
				<td>Total networth
				</td>
			</tr>
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'clan'
			);
			$clans = get_posts($args);
			
			
			
	
	$dec_clan_members = get_post_meta($clan_ID[0],'clan_members');

	$dec_tot_networth = 0;
		foreach ($dec_clan_members[0] as $dec_member) {
			$dec_networth = get_user_meta($dec_member, 'networth');
			$dec_tot_networth+=$dec_networth[0];
}

			
			
			
			foreach ($clans as $clan) { 
				
				
			$clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',$tot_networth);
						
			?>
			<?php if (($tot_networth > $dec_tot_networth/1.3 && $tot_networth < $dec_tot_networth*1.30)){	?>	
			<tr>
				<td><a href="<?php echo get_the_permalink($clan->ID);?>"><?php echo get_the_title($clan->ID). ' (#'.$clan->ID;?>)</a>
				</td>
				<td>$ 
				<?php echo number_format($tot_networth, 0, ',', ' ');
					
				?>
				</td>
			</tr>
			
			<?php }?><?php }?>
		</table>
			
			
			
			
		</div> <!-- CLOSE TAB 2 -->
		
		
		
		
		
		<?php endif;?>
		
		
		
		</div><!-- .entry-content -->

	</article><!-- #post -->
