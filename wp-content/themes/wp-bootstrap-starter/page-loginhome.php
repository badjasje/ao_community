<?php
 /*
 * Template Name: Home Login
 */
get_header();
$gameType = get_field('game_type','option');
 ?>
<?php if(is_user_logged_in()):?>
	 
	<script type="text/javascript">
	window.location.href = '<?php echo get_site_url();?>/dashboard';
	</script>

<?php exit; endif;?>
<div class="row pageRow">	
	<div class="col-md-3" style="padding:0px;">
	</div>
	<div class="col-md-6"  style="padding:0px;">
		<div class="blockHeader">Login</div>
		
		<form id="loginform" action="/wp-login.php" method="post" name="loginform">
			<div class="col-md-12 loginfield statCol-1">
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
			</div>
			<div class="col-md-12 loginfield statCol-3" style="border-top:1px solid #fff;">
			<input 	class="password_home" 
					id="user_pass" 
					class="input" 
					autocomplete="off" 
					name="pwd" 
					size="20" 
					type="password" 
					value="" 
					placeholder="password"/>
			</div>
								<input 	id="wp-submit" 
										class="mainSubmit"
										name="wp-submit" 
										type="submit" 
										value="Log In" /> 
								<input 	name="redirect_to" 
										type="hidden" 
										value="https://assault.online/dashboard" />
							</form>

	<a href="<?php echo get_site_url();?>/wp-login.php?loginFacebook=1&redirect=<?php echo get_site_url();?>" onclick="window.location = '<?php echo get_site_url();?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><button style="background-color:#4266b2"class="mainSubmit"><i class="fab fa-facebook-square"></i> Login or register with Facebook</button></a>
	<a href="<?php echo get_site_url();?>/register"><button style="background-color:#7e7b7b"class="mainSubmit"><i class="fab fa-facebook-square"></i> Register without Facebook</button></a>
	<div class="hometext">
	<?php if($gameType == 'Development'):?>
	<h2>Welcome to Assault.Online Development</h2>
	That's right! You've reached the Assault.Online development environment, a place where dreams come true! Somewhat highly experimental, so if something breaks, please report back on the <a target="_blank" href="https://assault.online/forum">Assault.Online forum.</a>. Happy testing!
	<?php else:?>
	<h2>Welcome to Assault.Online</h2>
	Assault.Online is the unofficial successor to Nukezone.nu. Assault.Online is a strategic text based browser game. It is played entirely in the browser, so no downloads needed! Assault.Online is free to play No downloads required
	<?php endif;?>
	</div>
	</div>
	
	</div>
	<div class="col-md-3" style="padding:0px;">
	</div>
	
</div>
<?php
get_sidebar();
get_footer();