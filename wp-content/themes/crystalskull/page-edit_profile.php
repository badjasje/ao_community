<?php
 /*
 * Template Name: Edit profile
 */
 /* Initialize some necessary variables */
 $user_ID =get_current_user_id();
 $clan_id = get_user_meta($user_ID, 'clan_id_user',true);
 $user = get_userdata($user_ID);
 $user_NW = get_user_meta($user_ID, 'networth',true);
 include('country_array.php');
 $user_country_code = get_user_meta($user_ID, 'user_country',true);
 $changecount = get_user_meta($user_ID, 'name_change_counter', true);
 $phone_number = get_user_meta($user_ID, 'phone_number', true);
 $low_buildings = get_user_meta($user_ID, 'low_buildings', true);
 $low_power = get_user_meta($user_ID, 'low_power', true);
 $desktopView = get_user_meta($user_ID, 'desktop_view',true);
 
 $desktopcheck = '';
 if($desktopView == 'on'){
	 $desktopcheck = 'checked';
	 
 }
 
 $lowBDScheck = '';
 if($low_buildings == 'on'){
	 $lowBDScheck = 'checked';
	 
 }
 
  $lowPWRcheck = '';
 if($low_power == 'on'){
	 $lowPWRcheck = 'checked';
	 
 }
 
 
 $disable_input = "";
 if($changecount == 1){
	 $disable_input = "disabled";
	 
 }
 
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>



<div class="row profile_block">
	<div class="col-md-2">
		<center>
		
		<?php if(!empty(get_user_meta($user_ID, 'avatar_user', true))):?>
			<div style='border-radius:100%;margin-bottom:20px;height:120px;width:120px;background: url("<?php echo get_user_meta($user_ID, 'avatar_user', true);?>");background-size: cover;'></div><?php endif;?>
			<form action="<?php echo home_url() ?>/update_profile.php" method="post" enctype="multipart/form-data">
    <sup>Select image to upload. Recommended: 120x120.</sup>
    <input type="file" name="file" id="file">
    

		
		
		
		</center>
		<br/><br/>
		
	</div>
	<div class="col-md-10">
		<div class="row">
			<div class="row profile_row">
				<div class="col-xs-5">Player ID</div>
				<div class="col-xs-7">#<?php echo $user_ID;?></div>
			</div>
			<div class="row profile_row">
				<div class="col-xs-5">Player name</div>
				<div class="col-xs-7">
					
					<div class="input-group input-group-sm">
						<span class="input-group-addon" id="sizing-addon3"><i class="fa fa-user-o" aria-hidden="true"></i></span>
						<input <?php echo $disable_input;?> maxlength="25" value="<?php echo $user->display_name;?>" type="text" class="form-control" placeholder="Username" name="username" aria-describedby="sizing-addon3">
					</div>
					</div>
				</div>
				
			<div class="row profile_row">
				<div class="col-xs-5">Mobile phone</div>
				<div class="col-xs-7">
					<div class="input-group input-group-sm">
						<span class="input-group-addon" id="sizing-addon3"><i class="fa fa-mobile fa-lg" aria-hidden="true"></i></span>
						<input type="tel" value="<?php echo $phone_number;?>" type="text" class="form-control" placeholder="example: 316123456789" name="phonenumber" aria-describedby="sizing-addon3">
					</div>
				
				</div>
			</div>
			
			<div class="row profile_row">
				<div class="col-xs-5">Force desktop view on mobile?</div>
				<div class="col-xs-7">
					<div class="input-group">
						      <span class="input-group-addon">
						        <input type="checkbox" aria-label="" name="desktopview" <?php echo $desktopcheck;?>>
						      </span>
						      <input type="text" class="form-control" value="Force desktop view" disabled>
						    </div><!-- /input-group -->
				</div>
			</div>
			
			
			
			<div class="row profile_row">
				<div class="col-xs-5">Notifications</div>
				<div class="col-xs-7">
					<div class="row">
						  <div class="col-lg-6">
							  <span class="hover-tip"  
							  data-toggle="tooltip" 
							  data-original-title="Instantly receive a text message when your power is low." 
							  data-placement="bottom">
						    <div class="input-group">
						      <span class="input-group-addon">
						        <input type="checkbox" aria-label="" name="low_power" <?php echo $lowPWRcheck;?>>
						      </span>
						      <input type="text" class="form-control" value="Low power" disabled>
						    </div><!-- /input-group -->
						    </span>
						  </div><!-- /.col-lg-6 -->
						  <div class="col-lg-6">
							  <span class="hover-tip"  
							  data-toggle="tooltip" 
							  data-original-title="Instantly receive a text message when your buildings are below 50." 
							  data-placement="bottom">
						    <div class="input-group">
						      <span class="input-group-addon">
						        <input type="checkbox" aria-label="" name="buildings" <?php echo $lowBDScheck;?>>
						      </span>
						       <input type="text" class="form-control" value="Low buildings" disabled>
						    </div><!-- /input-group -->
							  </span>
						  </div><!-- /.col-lg-6 -->
						</div><!-- /.row -->
					
					
				
				</div>
			</div>
			
			
			<div class="row profile_row_last">
				<div class="col-xs-5">Clan</div>
				<div class="col-xs-7">
					<?php if($clan_id == 0){
						echo 'none';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
				</div>
			</div><br/><br/>
			<input type="submit" value="UPDATE PROFILE">
			</form>	
			<br/><br/>
		</div>
	</div>
</div>	  
	            
<center>
	<a class="btn btn-general" href="/reset_province.php" onclick="return confirm('Are you sure you want to reset your account? You will lose all your units, research and buildings!')">
		<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;RESET ACCOUNT</a><br>
</center>    
	            
	            
	            

  <?php echo do_shortcode( '[plugin_delete_me]DELETE MY ACCOUNT[/plugin_delete_me]' ); ?>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>