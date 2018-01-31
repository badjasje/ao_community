<?php
 /*
 * Template Name: HomepageClean
 */
get_header('loginhome'); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php if(!empty($_SESSION['status'])):?>
				<?php echo alert_notification($_SESSION['status']);?>
			<?php endif; // End empty status check ?>
			
				<div class="row loginHome">
					<div class="col-md-4 homeColumn">
						<h2>Login</h2>
							<form id="loginform" action="/wp-login.php" method="post" name="loginform">
								<input 	class="login_name_home" 
										id="user_login" 
										class="input" 
										style="cursor: auto;" 
										autocomplete="off" 
										name="log" 
										size="20" 
										type="text" 
										value="" 
										placeholder="username"/>
								<input 	class="password_home" 
										id="user_pass" 
										class="input" 
										autocomplete="off" 
										name="pwd" 
										size="20" 
										type="password" 
										value="" 
										placeholder="password"/>
								<input 	id="wp-submit" 
										name="wp-submit" 
										type="submit" 
										value="Log In" /> 
								<input 	name="redirect_to" 
										type="hidden" 
										value="https://assault.online/dashboard" />
							</form>

							<center>
							<a style="margin-top: 10px; font-size: 11px;" href="/register">Register with email</a> - <a style="margin-top: 10px; font-size: 11px;" href="http://assault.online/wp-login.php?loginFacebook=1&redirect=https://assault.online" onclick="window.location = 'https://assault.online/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">Register/login with Facebook</a></center>
							
							<br/><br/>
							<center><a class="facebookbutton" href="http://assault.online/wp-login.php?loginFacebook=1&redirect=http://assault.online" onclick="window.location = 'http://assault.online/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">Login/Register with Facebook</a></center>
						
						
						
					</div>
					<div class="col-md-8 homeColumn">
						<h2 style="text-align: left;">Welcome to Assault.Online</h2>
Assault.Online is the <span style="text-decoration: underline;"><strong>unofficial</strong></span> successor to Nukezone.nu. Assault.Online is a strategic text based browser game. It is played entirely in the browser, so no downloads needed!

Assault.Online is free to play
No downloads required
						
						
					</div>
				</div>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>