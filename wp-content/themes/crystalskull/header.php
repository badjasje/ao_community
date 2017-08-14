<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
		$user_ID = get_current_user_ID();
		echo desktop_view($user_ID);
		?>
    <?php //globals
		
    global $post, $page, $paged, $woocommerce;
    
    if(!is_user_logged_in()){
	    wp_redirect(get_permalink(3491));
    }


$new_events 				= 	get_user_meta($user_ID, 'new_events',true);
$new_global_events 			= 	get_user_meta($user_ID, 'new_global_events',true);
$new_messages 				= 	get_user_meta($user_ID, 'new_messages',true);
$user_status 				= 	get_user_meta($user_ID, 'status',true);
$nuke_protection_timestamp 	= 	get_user_meta($user_ID,'nuke_protection_timestamp',true);
$clan_ID 					= 	get_user_meta($user_ID, 'clan_id_user',true);

$level_money_production 	= 	get_user_meta($user_ID, 'level_money_production',true);
$sat_level 					= 	get_user_meta($user_ID, 'level_satellite_construction',true);
$sat_morale					= 	get_user_meta($user_ID, 'sat_morale',true);

$morale 					= 	get_user_meta($user_ID, 'morale',true);
$moralepool 				= 	get_user_meta($user_ID, 'morale_pool',true);
$totalmoney 				= 	get_user_meta($user_ID, 'money',true);
$networth 					= 	get_user_meta($user_ID, 'networth',true);
$turns 						= 	get_user_meta($user_ID, 'turns',true);
$morale 					= 	get_user_meta($user_ID, 'morale',true);
$moralepool 				= 	get_user_meta($user_ID, 'morale_pool',true);
$land 						= 	get_user_meta($user_ID, 'land',true);
$builtland 					= 	get_user_meta($user_ID, 'builtland',true);


if($user_status == 'dead'){
	
	after_death($user_ID);
}
$user = get_userdata($user_ID);
	?>

    <?php include_once 'css/colours.css.php'; ?>


<?php wp_head(); 	
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>

<script type='text/javascript' src='/wp-content/themes/crystalskull/js/sortingdivs.js'></script>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/html2canvas.js'></script>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/FileSaver.js'></script>
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

    <!-- NAVBAR
    ================================================== -->
      <div class="navbar-wrapper ">
      	<div class="top-menu-bar">
      	<div class="container">

        <div class="top-menu">
        	 <?php if(has_nav_menu('top-menu')) { ?>
			<?php wp_nav_menu( array( 'theme_location'  => 'top-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>') ); ?>
		 	<?php } ?>
		 </div>

		

    </div><!-- /.container -->
    </div><!-- /.top-menu-bar -->

<div class="navbar navbar-inverse navbar-static-top container" role="navigation">
	<div class="logo col-lg-3 col-md-3">
		<a class="brand" href="<?php  echo esc_url(site_url('/dashboard')); ?>"> 
			<img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> 
		</a>
	
		<?php echo header_events($user_ID);?>
	</div>
			 

            <div class="navbar-collapse <?php if(!function_exists( 'ubermenu' )){ echo 'collapse'; } ?>">


                <?php if(has_nav_menu('header-menu')) { ?>
              <?php if(function_exists( 'ubermenu' )){ ?>
              	<?php ubermenu( 'main' , array( 'theme_location' => 'header-menu' ) ); ?>
			  <?php }else{ ?>
              <?php wp_nav_menu( array( 'theme_location'  => 'header-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>', 'walker'  => new crystalskull_Walker_Quickstart_Menu()) ); ?>
                <?php } ?>

                <?php }else { ?>
                   <ul  class="nav"><li>
                   <a href="#"><?php esc_html_e('No menu assigned!', 'crystalskull'); ?></a>
                   </li></ul>
                <?php } ?>

               
            </div><!--/.nav-collapse -->

          </div><!-- /.navbar-inner -->

    </div><!-- /.navbar -->

<div class="title_wrapper container">


			<div class="col-lg-12">

				<?php if (is_single() && (get_post_type($post->ID) == 'post')) {
					$categories = wp_get_post_categories($post->ID);
					echo "<div class='cat-single'>";
					foreach ($categories as $category) { ?>
						<?php $cat_data = get_option("category_$category"); ?>
						<a href="<?php echo esc_url(get_category_link($category)); ?>" class="ncategory" style="background-color: <?php echo esc_attr($cat_data['catBG']); ?> !important">
							<?php echo esc_attr(get_cat_name($category)); ?>
						</a>
					<?php }
					echo "</div>";
				} ?>
				<h1><?php the_title(); ?>
					<?php if (is_page(3486)): ?>
						<a href="/users/profile/?id=<?php echo $user->ID; ?>"><?php echo $user->display_name . ' (#' . $user->ID; ?>)</a>
						
						<div style='position: relative;vertical-align: middle;display: inline-block;'><?php echo small_avatar($user->ID,'');?></div>
						
					<?php endif; ?>

					<?php if (is_page(3520)): $user__ID = $_GET['id'];
						$user = get_userdata($user__ID);
						$last_online = get_user_meta($user__ID, 'last_online',true);
						if (!empty($last_online)) {
							$timestamp = current_time('timestamp');
							$last_seen = $timestamp - $last_online;
						} ?><?php echo $user->display_name; ?> (#<?php echo $user__ID; ?>) <?php
						if (!empty($last_online)) {
							if ($last_seen < 7200 && !empty($last_online)) {
								echo ' <span style="color:#ff0000">*</span>';
							}
						} ?>
					<?php endif; ?>
				</h1>
			</div>
            <div class="col-lg-12 breadcrumbs" style="float:left;margin-top: 0;"><strong><?php crystalskull_breadcrumbs(); ?></strong></div>
            <div class="clear"></div>

</div>



<div class="after-nav ">



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
	
		

	</div>
	
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
		

	</div>

	
</div>
	</div>

