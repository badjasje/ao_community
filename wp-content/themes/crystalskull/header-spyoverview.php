<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
		$user_ID = get_current_user_ID();
		ban_redirect($user_ID);
		echo desktop_view($user_ID);
		
		if(!is_user_logged_in()){
			$_SESSION['status'] = 'Log in or register to view this page.';
	    	wp_redirect(get_permalink(3491));
	    	exit;
    	}

$userData = get_user_meta($user_ID);
$new_events 				= 	$userData['new_events'][0];
$new_global_events 			= 	$userData['new_global_events'][0];
$new_messages 				= 	$userData['new_messages'][0];
$user_status 				= 	$userData['status'][0];
$clan_ID 					= 	$userData['clan_id_user'][0];

$sat_morale					= 	$userData['sat_morale'][0];

$morale 					= 	$userData['morale'][0];
$moralepool 				= 	$userData['morale_pool'][0];
$totalmoney 				= 	$userData['money'][0];
$networth 					= 	$userData['networth'][0];
$turns 						= 	$userData['turns'][0];
$land 						= 	$userData['land'][0];


if($user_status == 'dead'){
	
	after_death($user_ID);
}
$user = get_userdata($user_ID);
	?>

    <?php include_once 'css/colours.css.php'; ?>


<?php wp_head();?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>

<script type='text/javascript' src='/wp-content/themes/crystalskull/js/sortingdivs.js'></script>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/html2canvas.js'></script>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/FileSaver.js'></script>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/numberformat.js'></script>
</head>
<body <?php body_class(); ?>>
	<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '207356919688805',
      xfbml      : true,
      version    : 'v2.8'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<div id="main_wrapper">
	<div class="navbar-wrapper ">


		<div class="navbar navbar-inverse navbar-static-top container" role="navigation">
			<div class="logo col-lg-3 col-md-3">
				<a class="brand" href="<?php  echo esc_url(site_url('/dashboard')); ?>"> 
					<img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> 
				</a>
			<?php echo header_events($user_ID);?>
		</div>
			 

		<div class="navbar-collapse">
			<?php wp_nav_menu( 
				array( 	'theme_location'  => 'header-menu', 
						'depth' => 0,
						'sort_column' => 'menu_order', 
						'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>', 
						'walker'  => new crystalskull_Walker_Quickstart_Menu()) 
					); ?>
		</div><!--/.nav-collapse -->
	
	</div><!-- /.navbar Wrapper -->

<div class="title_wrapper container">

	<div class="col-lg-12">
		<h1>Spy overview
			<?php $clan_ID = $_GET['id']; if($clan_ID):?>
			<?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
			<?php endif;?>
		</h1>
	</div>
	<div class="col-lg-12 breadcrumbs" style="float:left;margin-top: 0;"><strong><?php crystalskull_breadcrumbs(); ?></strong></div>
	<div class="clear"></div>

</div>



<div class="after-nav">
	<div class="container globalstats">      
		<!-- Desktop view -->
		<div class="row statsdesktop">
			
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Money:</strong> $ <?php echo number_format($totalmoney, 0, ',', ' '); ?>
					</li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Networth:</strong> $ <?php echo number_format($networth, 0, ',', ' '); ?>
					</li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Turns:</strong> <?php echo $turns; ?>
					</li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Morale:</strong> <?php echo $morale; ?>% <sup>(<?php echo $moralepool;?>%)</sup>
					</li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Land:</strong> <?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
					</li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="list-group desktopitem">
					<li class="list-group-item">
						<strong>Sat. power:</strong> <?php echo $sat_morale; ?>%
					</li>
				</ul>
			</div>
		</div> <!-- end statsdesktop -->
		
		<!-- Mobile view -->
		<div class="row statsmobile">
			<div class="col-xs-6 mobilestatblock">
				<ul class="list-group listitem_smallspace">
					<li class="list-group-item statitem">
						<a href="/events/incoming/">Events <span class="badge"><?php echo $new_events;?></span></a>
					</li>
					
				</ul>
			</div>
			
			<div class="col-xs-6 mobilestatblock">
				<ul class="list-group listitem_smallspace">
					<li class="list-group-item statitem">
						<a href="/inbox/">Messages <span class="badge"><?php echo $new_messages;?></span></a>
					</li>
					
				</ul>
			</div>
	
			
			<div class="col-xs-6 mobilestatblock">
				<ul class="list-group desktopitem">
					<li class="list-group-item statitem">
						<strong>Money:</strong> $ <?php echo number_format($totalmoney, 0, ',', ' '); ?>
					</li>
					<li class="list-group-item statitem">
						<strong>Networth:</strong> $ <?php echo number_format($networth, 0, ',', ' '); ?>
					</li>
					<li class="list-group-item statitem">
						<strong>Turns:</strong> <?php echo $turns; ?>
					</li>
				</ul>
			</div>
			
			<div class="col-xs-6 mobilestatblock">
				<ul class="list-group desktopitem">
					<li class="list-group-item statitem">
						<strong>Morale:</strong> <?php echo $morale;?>% <sup>(<?php echo $moralepool;?>%)</sup>
					</li>
					<li class="list-group-item statitem">
						<strong>Land:</strong> <?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
					</li>
					<li class="list-group-item statitem">
						<strong>Sat. power:</strong> <?php echo $sat_morale; ?>%
					</li>
				</ul>
			</div>
			
	
		</div> <!-- end statsmobile -->
	</div> <!-- end globalstats -->
</div> <!-- end after-nav -->