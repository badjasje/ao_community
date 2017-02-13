<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
 /* Initialize some necessary variables */
 $user_ID =get_current_user_id();
 $user = get_userdata($user_ID);
 include('country_array.php');
 $user_country_code = get_user_meta($user_ID, 'user_country');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		
		
	
		
		<center><h1>Edit profile</h1></center>
		
		
		
	
		</div>
		<form class="form" action="<?php echo home_url() ?>/update_profile.php" name="" id="market" method="post">
		<div class="container2">
				<table class="responsive-table">
					
					<tr>
						<th scope="row" style="width: 105px; vertical-align: top;border-right: 1px solid #9F9F9F;"rowspan='7'><?php 
						/* get shortcode of user avatar/local avater plugin */
						echo do_shortcode('[basic-user-avatars]')?></th>
						<td>Your ID</td>
						<td>#<?php echo $user_ID;?></td>
  					</tr>
  					<tr>
						<td>Display name</td>
						<td><?php echo $user->display_name;?></td>
  					</tr>
  					<tr>
						<td>First name</td>
						<td><?php echo $user->display_name;?></td>
  					</tr>
  					<tr>
						<td>Registered</td>
						<td><?php echo $user->user_registered;?></td>
  					</tr>
  					<tr>
						<td>Country</td>
						<td>
							<select name="countrycode">
							<?php if($user_country_code[0] == '0'):?>
							<option value="" selected="selected">
							-- Pick a country --
							</option>
							<?php else:?>
							<option value="<?php echo $user_country_code[0];?>" selected="selected">
							<?php echo $countries[$user_country_code[0]];?>
							</option>
							<?php endif;?>
						<?php foreach ($countries as $key => $country) {
						
						?>	
							
							<option name="countrycode" value="<?php echo $key;?>"><?php echo $country;?><img src="/flags/<?php echo $key;?>.png"></option>
							<?php }?>
							</select>
						
						</td>
  					</tr>
  					<tr>
						<td></td>
						<td></td>
  					</tr>
  					<tr>
						<td></td>
						<td></td>
  					</tr>
			</table>
			<center><input type="submit" value="Save" class=""></center>
			</form>
			</div>
	
	
	
	<!--
		<form class="form" action="<?php echo home_url() ?>/update_profile.php" name="" id="market" method="post">
		<div class="container2">
		<table class="responsive-table">
		
  			
  			<tr>
	  			<td>
					<strong>Display name</strong>
				</td>
	  			
				<td>
					<input value="<?php echo $user->display_name;?>" class="small_input" type="text" id="displayname" name="displayname"/>
				</td>
				
  			</tr>
  			<tr>
	  			<td>
					<strong>First name</strong>
				</td>
	  			
				<td>
					<input class="small_input" type="text" id="firstname" name="firstname"/>
				</td>
				
  			</tr>
  			<tr>
	  			<td>
					<strong>Country</strong>
				</td>
	  			
				<td>
					<input class="small_input" type="text" id="country" name="country"/>
				</td>
				
  			</tr>
  			

		</table>
		<input type="submit" value="Update profile" class="">
	
		</div>
		</form>
	-->
	
	</div><!-- .entry-content -->
</article><!-- #post -->
