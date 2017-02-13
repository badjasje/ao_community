<?php
 /*
 * Template Name: Change Display Name
 */
$user_ID = get_current_user_ID();
$user = get_userdata($user_ID);
get_header('welcome'); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            <center>
			<div class="welcome_text"><p>Welcome to Assault.Online! It seems you have signed up through Facebook. Awesome! This makes logging in a breeze. We received your username through Facebook. However, we urge you to <strong>change</strong> your username for privacy reasons.</p><hr/>
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
				</div>
	            </center>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>