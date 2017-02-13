<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->

<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); 
	$user_ID = get_current_user_id();
	$totalmoney = get_user_meta($user_ID, 'money');
	$networth = get_user_meta($user_ID, 'networth');
	$turns = get_user_meta($user_ID, 'turns');
	$morale = get_user_meta($user_ID, 'morale');
	$moralepool = get_user_meta($user_ID, 'morale_pool');
	$land = get_user_meta($user_ID, 'land');
	$builtland = get_user_meta($user_ID, 'builtland');
	$new_messages = get_user_meta($user_ID, 'new_messages');
	$new_events = get_user_meta($user_ID, 'new_events');
?><script type='text/javascript' src='/wp-content/themes/twentytwelve/js/tabbed.js'></script>
<script type='text/javascript' src='/wp-content/themes/twentytwelve/js/sortable.js'></script>
<?php if(!empty($new_messages[0]) and $new_messages[0] != 0):?>
<style>
.menu-item-4118 {
  -webkit-animation-name: greenPulse;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
}
</style>
<?php endif;?>
<?php if(!empty($new_events[0]) and $new_events[0] != 0):?>
<style>
.menu-item-7707 {
  -webkit-animation-name: greenPulse;
  -webkit-animation-duration: 2s;
  -webkit-animation-iteration-count: infinite;
}
</style>
<?php endif;?>

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		
		<hgroup class='hide_menu'>
			<center><a href="/"><img width="350" src="/wp-content/uploads/2016/03/AO_logo.png"></a></center>
		</hgroup>
		<?php /* ?>
		<div style="border:2px solid #ddd;padding:15px; margin:10px;"><center>
			
			<strong><u>EARLY BETA</u></strong></center>
		<center><a style="color:#ff9900;font-weight:bold;" href="/forum/">Found a bug? Post it. Don't like something? Post it. Suggestions?.. POST IT.</a></center></div>
		<?php */?>
		<?php if(is_user_logged_in ()):?>
		<nav id="site-navigation" class="hide_menu main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
		</nav><!-- #site-navigation -->
		
		
			<ul id="list">
			<li><span><strong>Money:</strong> $ <?php echo number_format($totalmoney[0], 0, ',', ' '); ?></span></li> 
			<li><span><strong>Networth:</strong> $ <?php echo number_format($networth[0], 0, ',', ' '); ?></span></li> 
			<li><span><strong>Turns:</strong> <?php echo $turns[0]; ?></span></li>
			<li><span><strong>Morale:</strong> <?php echo $morale[0]; ?>% <sup>(<?php echo $moralepool[0];?>%)</sup></span></li> 
			<li><span><strong>Land:</strong> <?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup></span></li>
			</ul>
		
	<?php else:?>
	<nav id="site-navigation" class="hide_menu main-navigation" role="navigation">
			<button class="menu-toggle"><?php _e( 'Menu', 'twentytwelve' ); ?></button>
			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?><div id="notify">0</div>
		</nav><!-- #site-navigation -->
	<?php endif;?>
	

	
	</header><!-- #masthead -->

	<div id="main" class="wrapper">