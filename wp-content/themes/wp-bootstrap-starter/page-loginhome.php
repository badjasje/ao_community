<?php
 /*
 * Template Name: Home Login
 */
get_header();
$gameType = get_field('game_type','option');
?>

<div class="row pageRow">
	<div class="col-md-3" style="padding:0px;"></div>

	<div class="col-md-6"  style="padding:0px;">
		<div class="blockHeader">Login</div>

		<form id="loginform" action="/wp-login.php" method="post" name="loginform">
			<input name="rememberme" checked hidden type="checkbox" id="rememberme" value="forever">
			<div class="col-md-12 loginfield statCol-1">
				<input class="login_name_home" id="user_login" class="input" style="cursor: auto;" autocomplete="off"
					name="log" size="20" type="text" value="" placeholder="username"/>
			</div>
			<div class="col-md-12 loginfield statCol-3" style="border-top:1px solid #fff;">
				<input class="password_home" id="user_pass" class="input" autocomplete="off" name="pwd"
					size="20" type="password" value="" placeholder="password" />
			</div>
			<input id="wp-submit" class="mainSubmit" name="wp-submit" type="submit" value="Log In" />
			<input name="redirect_to" type="hidden" value="<?php echo get_site_url(); ?>/dashboard" />
			<? if(isset($reCAPTCHA3_Login_Form)) $reCAPTCHA3_Login_Form->captcha_display() ?>
		</form>

		<?php if(!in_array($gameType, array('Test','Development'))) { ?>
		<a href="/wp-login.php?loginSocial=facebook" data-plugin="nsl" data-action="connect" data-redirect="current"
			data-provider="facebook" data-popupwidth="475" data-popupheight="175">
			<button style="background-color:#4266b2"class="mainSubmit"><i class="fab fa-facebook-square"></i> Login or register with Facebook</button>
		</a>
		<a style="background-color:#ddd;color:#000;text-align:center;border:1px solid #000;padding:5px;margin-top:10px;margin-bottom:10px;display: block;font-weight: bold;"href="https://assault.online/wp-login.php?action=lostpassword">HELP I lost my Password</a>
		<?php } ?>

		<a href="<?php echo get_site_url();?>/register/"><button style="background-color:#7e7b7b"class="mainSubmit">
		Register<? if(!in_array($gameType, array('Test','Development'))) { ?> without Facebook<? } ?></button></a>

		<div class="hometext">
			<?php if($gameType == 'Test') { ?>
				<h2 style="margin-top:0;">Welcome to Assault.Online Test</h2>
				We use this place to test the game. Please do not use it as a sandbox, thank you.
			<?php } else if($gameType == 'Development') { ?>
				<h2 style="margin-top:0;">Welcome to Assault.Online Development</h2>
				That's right! You've reached the Assault.Online development environment, a place where dreams come true!
				Somewhat highly experimental, so if something breaks, please report back on the
				<a target="_blank" href="https://assault.online/forum">Assault.Online forum.</a>. Happy testing!
			<?php } else { ?>
				<h2 style="margin-top:0;">Welcome to Assault.Online</h2>
				Assault.Online is the unofficial successor to Nukezone.nu. Assault.Online is a strategic text based browser game. It is played entirely in the browser, so no downloads needed! Assault.Online is free to play No downloads required
			<?php } ?>
		</div>

		<div class="pageSpacer"></div>

		<div class="blockHeader">Resources</div>

		<a class="col-md-12 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="https://assault.online/manual/">
			Manual
		</a>
		<a class="col-md-12 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="https://assault.online/category/changes/">
			Round changes
		</a>
		<a class="col-md-12 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="https://assault.online/category/awards-medals/">
			Medals & awards
		</a>
		<a class="col-md-12 profileButton" style="background-color: rgba(70, 118, 94, 0.7);" href="https://assault.online/toplists/">
			Toplists
		</a>
	</div>

	<div class="col-md-3" style="padding:0px;"></div>
</div>
<?php

get_sidebar();
get_footer();