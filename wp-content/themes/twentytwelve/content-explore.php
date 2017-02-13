<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_ID();
$ownedland = get_user_meta($user_ID, 'land');
$freeland = $ownedland[0]-get_user_meta($user_ID, 'builtland')[0];
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<center><h1>Explore</h1></center>
			<center><p>You can currently explore <?php 
				
				if(200-((ceil($ownedland[0]*0.002))) < 0){echo '0';}else{echo 200-((ceil($ownedland[0]*0.002)));} ?>
				
				m<sup>2</sup> per turn.</p></center>
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Not enough turns</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice"><?php echo number_format($_SESSION['explored'], 0, ',', ' '); ?> m<sup>2</sup> explored</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">You can only explore <?php echo number_format(20000-get_user_meta($user_ID, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> more land.</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Cannot sell! Not enough free land.</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice">You sold <?php echo number_format($_SESSION['sold'], 0, ',', ' '); ?> m<sup>2</sup></div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice insuffunds">Cannot sell any more land!</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php elseif($_SESSION['status'] == 16):?>
				<div class="marketnotice insuffunds">No more exploring possible</div>
			<?php elseif($_SESSION['status'] == 17):?>
				<div class="marketnotice insuffunds">Cannot explore more land. Fill up your free land first</div>
			<?php elseif($_SESSION['status'] == 18):?>
				<div class="marketnotice insuffunds">Cannot explore more land than you currently own</div>
			<?php endif;?><?php endif;?>
			<div class="container">
			<center>
			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Explore</li>
			<li class="tab-link" data-tab="tab-2">Sell land</li>
			</ul>
			</center>
			<div id="tab-1" class="tab-content current">
			
			<?php if(empty(get_user_meta($user_ID, 'explored_today')[0]) || get_user_meta($user_ID, 'explored_today')[0] == 0):?>
			<center><p>You haven't explored any land today. You can explore 20 000 m<sup>2</sup> </p></center>
			<?php else:?>
			<center><p>You have explored <strong><?php echo number_format(get_user_meta($user_ID, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> today. You can explore an additional <strong><?php echo number_format(20000-get_user_meta($user_ID, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo floor((20000-get_user_meta($user_ID, 'explored_today')[0])/(200-((ceil($ownedland[0]*0.002)))));?> turns)</i></p></center>
			<?php endif;?>
			<form class="form" action="<?php echo home_url() ?>/explore.php" name="" id="explore" method="post">
			<table>
				<tr>
					<td>
						<strong>Enter the amount of turns you wish to explore</strong>
					</td>
					<td>
						<input class="small_input" type="text" id="turns" name="turns" value=""/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Explore" class="">
			</form>
			</div>
			
			
			<div id="tab-2" class="tab-content">
				
			<?php if(empty(get_user_meta($user_ID, 'land_sold_today')[0]) || get_user_meta($user_ID, 'land_sold_today')[0] == 0):?>
			<center><p>You can sell <strong><?php echo number_format(20000-get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong>. You currently have <strong><?php if($freeland > 0){echo number_format($freeland, 0, ',', ' ');}else{echo '0';} ?> m<sup>2</sup></strong> free land.</p></center>
			<?php else:?>
			<center><p>You have sold <strong><?php echo number_format(get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> today. You can sell an additional <strong><?php echo number_format(20000-get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong></p></center>
			<?php endif;?>
			<form class="form" action="<?php echo home_url() ?>/sell_land.php" name="" id="explore" method="post">
			<table>
				<tr>
					<td>
						<strong>Enter the amount of land you wish to sell. 1 m<sup>2</sup> has a value of $ 150</strong>
					</td>
					<td>
						<input class="small_input" type="text" id="land" name="land" value=""/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Sell land" class="">
			</form>
			</div>
			
			
	</div>
	<?php session_unset(); ?>
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
