<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$defender_ID = $_SESSION['target_id'];
$succes = (rand(80,100));


	$turns = get_user_meta($userID, 'turns');
	update_user_meta($userID,'turns',$turns[0]-2);
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<center><h1>Spy result</h1></center>
		</header>
		<div class="entry-content">
			
			<?php if($_SESSION['attack_array']['sendspy'] == 'spy'):?>
			<?php if($succes != 100):include('units_array.php');?>
			<center><h2>S U C C E S</h2>
			<p>Your spy entered the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			<table>
				<tr>
					<td>
						<strong>Name</strong>
					</td>
					<td>
						<strong>Owned by <?php echo $playername->nickname;?></strong>
					</td>
				</tr>
			<?php foreach ($units as $key => $unit) {
			$owned_units = get_user_meta($defender_ID, $key.'_owned');
			
			?>
				<tr>
					<td>
						<?php echo $unit['normalname'];?>
					</td>
					<td>
						<?php 
							if($owned_units[0] < 49){echo 'Few: 0-49';}
							if($owned_units[0] > 49 && $owned_units[0] < 124){echo 'Several: 50-99';}
							if($owned_units[0] > 124 && $owned_units[0] < 249){echo 'Pack: 100-249';}
							if($owned_units[0] > 249 && $owned_units[0] < 499){echo 'Lots of: 250-499';}
							if($owned_units[0] > 499 && $owned_units[0] < 999){echo 'Many: 500-999';}
							if($owned_units[0] > 999 && $owned_units[0] < 2499){echo 'Crowd: 1000-1999';}
							if($owned_units[0] > 2499 && $owned_units[0] < 4999){echo 'Legion: 2000-2999';}
							if($owned_units[0] > 4999){echo 'Super legion: 3000-4999';}
						?>
					</td>
				</tr>
		
		
			
			<?php }?></table>
			<?php else:?>
			<center><h2>F A I L U R E</h2>
			<p>Your spy was caught and killed by <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			
			<?php endif;?><?php endif;?>
			
			
			
			
			
			
			
			<?php if($_SESSION['attack_array']['sendspy'] == 'spyplane'):?>
			<?php if($succes != 100): include('building_array.php');?>
			<center><h2>S U C C E S</h2>
			<p>Your spyplane flew over the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			<table>
				<tr>
					<td>
						<strong>Name</strong>
					</td>
					<td>
						<strong>Owned by <?php echo $playername->nickname;?></strong>
					</td>
				</tr>
			<?php foreach ($buildings as $key => $unit) {
			$owned_units = get_user_meta($defender_ID, $key);
			
			?>
				<tr>
					<td>
						<?php echo $unit['normalname'];?>
					</td>
					<td>
						<?php 
							if($owned_units[0] < 49){echo 'Few: 0-49';}
							if($owned_units[0] > 49 && $owned_units[0] < 124){echo 'Several: 50-99';}
							if($owned_units[0] > 124 && $owned_units[0] < 249){echo 'Pack: 100-249';}
							if($owned_units[0] > 249 && $owned_units[0] < 499){echo 'Lots of: 250-499';}
							if($owned_units[0] > 499 && $owned_units[0] < 999){echo 'Many: 500-999';}
							if($owned_units[0] > 999 && $owned_units[0] < 2499){echo 'Crowd: 1000-1999';}
							if($owned_units[0] > 2499 && $owned_units[0] < 4999){echo 'Legion: 2000-2999';}
							if($owned_units[0] > 4999){echo 'Super legion: 3000-4999';}
						?>
					</td>
				</tr>
		
		
			
			<?php }?></table>
			<?php else:?>
			<center><h2>F A I L U R E</h2>
			<p>Your spy was caught and killed by <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			
			<?php endif;?><?php endif;?>
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
