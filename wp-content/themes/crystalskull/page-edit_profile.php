<?php
 /*
 * Template Name: Edit profile
 */
 /* Initialize some necessary variables */
 $user_ID =get_current_user_id();
 $user = get_userdata($user_ID);
 include('country_array.php');
 $user_country_code = get_user_meta($user_ID, 'user_country',true);
 $changecount = get_user_meta($user_ID, 'name_change_counter', true);
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
		<div class="container2">
			


				<table class="responsive-table">
					
					<tr>
						<th scope="row" style="width: 105px; vertical-align: top;border-right: 1px solid #9F9F9F;"rowspan='7'>
							<?php if(!empty(get_user_meta($user_ID, 'avatar_user', true))):?>
			<div style='height:90px;width:90px;background: url("<?php echo get_user_meta($user_ID, 'avatar_user', true);?>");background-size: cover;'></div><?php endif;?>
			<form action="/set_user_avatar.php" method="post" enctype="multipart/form-data">
    Select image to upload. Recommended: 90x90.
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload Image" name="submit">
</form>

							
						</th>
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

			</table>
	
			
			</div>
<?php if(empty($changecount) || $changecount != 1):?>
<br/><br/>
	<center>
			<div class="welcome_text"><p>You can change your username once every round. This is your public display name used in-game.</p><hr/>
<h4>Your current username is</h4>
				<h2><?php echo $user->display_name;?></h2>
				
				<hr/>
				<h4>New user name</h4>
				<p>
					<form class="form" action="<?php echo home_url() ?>/change_display_name.php" name="" id="displayname" method="post">
						<input class="new_user_name" type="text" id="display_name" name="username"><br/><br/>
						<input type="submit" value="CHANGE USERNAME">
					</form>	
				</p>
</div><br/><br/>
<?php endif;?>	
	
	<?php echo do_shortcode( '[plugin_delete_me]DELETE MY ACCOUNT[/plugin_delete_me]' ); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>