<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 $user_ID = $_GET['user_id'];
 $totalmoney = get_user_meta($user_ID, 'money')[0];
 $turns = get_user_meta($user_ID, 'turns')[0];
 $status = get_user_meta($user_ID, 'status')[0];
 $land = get_user_meta($user_ID, 'land')[0];
 $explored = get_user_meta($user_ID, 'explored_today')[0];
 $sold = get_user_meta($user_ID, 'land_sold_today')[0];
 $user = get_userdata($user_ID);
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(current_user_can('activate_plugins')){ ?>	

		<div class="entry-content">
			
			
			<center><h2>Edit <?php echo $user->display_name;?> (#<?php echo $user_ID;?>)</h2></center>
		<form class="form" action="<?php echo home_url() ?>/set_user.php" name="" id="market" method="post">
				
	
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">User ID</th>
						<th scope="col">Money</th>
						<th scope="col">Turns</th>
						<th scope="col">Status</th>
						<th scope="col">Land</th>
						<th scope="col">Land explored today</th>
						<th scope="col">Land sold today</th>
  					</tr>
  					</thead>
  					<tbody>
	  				<th scope="row">	
		  				<input class="small_input" value="<?php echo $user_ID;?>" type="text" name="user ID"/>
	  				</th>
	  				
	  				<td data-title="Money">
		  				<input class="small_input" value="<?php echo $totalmoney;?>" type="text" name="money"/>
	  				</td>
	  				
	  				<td data-title="Turns">
		  				<input class="small_input" value="<?php echo $turns;?>" type="text" name="turns"/>
	  				</td>
	  				
	  				<td data-title="Status">
		  				<input class="small_input" value="<?php echo $status;?>" type="text" name="status"/>
	  				</td>
	  				
	  				<td data-title="Land">
		  				<input class="small_input" value="<?php echo $land;?>" type="text" name="land"/>
	  				</td>
	  				
	  				<td data-title="m2 explored today">
		  				<input class="small_input" value="<?php echo $explored;?>" type="text" name="explored"/>
	  				</td>
	  				
	  				<td data-title="m2 sold today">
		  				<input class="small_input" value="<?php echo $sold;?>" type="text" name="sold"/>
	  				</td>
	  					
	  					
  					</tbody>
				</table>
				<input type="submit" value="Update this user" class="">
				</form>
				</div>
		
		<?php }?>
		</div><!-- .entry-content -->

	</article><!-- #post -->
