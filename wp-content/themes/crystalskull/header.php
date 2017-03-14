<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //globals
	    
    global $post, $page, $paged, $woocommerce;
    
    if(!is_user_logged_in()){
	    wp_redirect(get_permalink(3491));
    }
$user_ID = get_current_user_ID();
$new_events = get_user_meta($user_ID, 'new_events');
$new_global_events = get_user_meta($user_ID, 'new_global_events',true);
$new_messages = get_user_meta($user_ID, 'new_messages');
$user_status = get_user_meta($user_ID, 'status');
$nuke_protection_timestamp = get_user_meta($user_ID,'nuke_protection_timestamp');
$clan_ID = get_user_meta($user_ID, 'clan_id_user')[0];

$level_money_production = get_user_meta($user_ID, 'level_money_production',true);
$sat_level = get_user_meta($user_ID, 'level_satellite_construction',true);
$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

$morale = get_user_meta($user_ID, 'morale',true);
$moralepool = get_user_meta($user_ID, 'morale_pool',true);
$totalmoney = get_user_meta($user_ID, 'money');
	$networth = get_user_meta($user_ID, 'networth');
	$turns = get_user_meta($user_ID, 'turns');
	$morale = get_user_meta($user_ID, 'morale');
	$moralepool = get_user_meta($user_ID, 'morale_pool');
	$land = get_user_meta($user_ID, 'land');
	$builtland = get_user_meta($user_ID, 'builtland');
if($user_status[0] == 'dead'){
	
	after_death($user_ID);
}
$user = get_userdata($user_ID);
	?>

    <?php include_once 'css/colours.css.php'; ?>
	<?php $currentlang = apply_filters( "wpml_home_url", esc_url(home_url('/')));  ?>

<?php wp_head(); 	
?>
<script type='text/javascript' src='/wp-content/themes/crystalskull/js/tabbed.js'></script>
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
            		<a class="brand" href="<?php  echo esc_url(site_url('/')); ?>"> <img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> </a><div class="events_head"><a href="/events/incoming/"><?php if($new_events[0] > 0):?> <span style="color:#ff0000"><?php echo $new_events[0];?></span> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php else:?> <?php echo $new_events[0];?> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php endif;?></a> - <a href="/events/global/"><?php if($new_global_events > 0):?> <span style="color:#ff0000"><?php echo $new_global_events;?></span> new global event<?php if($new_global_events > 1 || $new_global_events == 0){echo 's';}?> <?php else:?> <?php echo $new_global_events;?> new global event<?php if($new_global_events > 1 || $new_global_events == 0){echo 's';}?> <?php endif;?></a></div>
          		</div>
			 <?php if(!function_exists( 'ubermenu' )){ ?>
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'crystalskull'); ?></span>
                <span class="fa fa-bars"></span>
              </button>
            </div>
            <?php } ?>

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
						<?php if (!empty(get_user_meta($user->ID, 'avatar_user', true))): ?>
							<div style='border: 1px solid #fff;position: relative;vertical-align: middle;display: inline-block;border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true); ?>");background-size: cover;'></div>
						<?php else: ?>
							<div style='border: 1px solid #fff;position: relative;vertical-align: middle;display: inline-block;border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (is_page(3520)): $user__ID = $_GET['id'];
						$user = get_userdata($user__ID);
						$last_online = get_user_meta($user__ID, 'last_online');
						if (!empty($last_online)) {
							$timestamp = strtotime(date('Y-m-d H:i:s'));
							$last_seen = $timestamp - $last_online[0];
						} ?><?php echo $user->display_name; ?> (#<?php echo $user__ID; ?>) <?php
						if (!empty($last_online)) {
							if ($last_seen < 7200 && !empty($last_online[0])) {
								echo ' <span style="color:#ff0000">*</span>';
							}
						} ?>
					<?php endif; ?>
				</h1>
			</div>
            <div class="col-lg-12 breadcrumbs" style="float: left; margin-top: 0;"><strong><?php crystalskull_breadcrumbs(); ?></strong></div>
            <div class="clear"></div>

</div>



<div class="after-nav ">

	<!-- Desktop View -->

	<div class="container globalstats">
		<div class="statitem"><strong>Money:</strong> $ <?php echo number_format($totalmoney[0], 0, ',', ' '); ?></div>
		<div class="statitem"><strong>Networth:</strong> $ <?php echo number_format($networth[0], 0, ',', ' '); ?></div> 
		<div class="statitem"><strong>Turns:</strong> <?php echo $turns[0]; ?></div>
		<div class="statitem"><strong>Morale:</strong> <?php echo $morale[0]; ?>% <sup>(<?php echo $moralepool[0];?>%)</sup></div>
		<div class="statitem"><strong>Land:</strong> <?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup></div>
		<div class="statitem"><strong>Sat. power:</strong> <?php echo $sat_morale; ?>%</div>
	</div>
            
	<!-- mobile view -->
	
	<table class="statsmobile" style="border:none;width:350px;margin-left:auto;margin-right:auto;">
		<tr>
			<td width="50%">
				<a href="/events/incoming/"><?php if($new_events[0] > 0):?> <span style="color:#ff0000"><?php echo $new_events[0];?></span> 
				new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php else:?> <?php echo $new_events[0];?> 
				new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php endif;?></a><hr/>
				<strong>Turns:</strong> <?php echo $turns[0]; ?><br/>
				<strong>Morale:</strong> <?php echo $morale[0]; ?>% <sup>(<?php echo $moralepool[0];?>%)</sup><br/>
				<strong>Land:</strong> <?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup><br/>
			</td>

			<td width="50%">
				<a href="/inbox/"><?php if($new_messages[0] > 0):?> <span style="color:#ff0000"><?php echo $new_messages[0];?></span> 
				new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?> <?php else:?> <?php echo $new_messages[0];?> 
				new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?> <?php endif;?></a><hr/>
				<strong>Money:</strong> $ <?php echo number_format($totalmoney[0], 0, ',', ' '); ?><br/>
				<strong>Networth:</strong> $ <?php echo number_format($networth[0], 0, ',', ' '); ?><br/>
				<strong>Sat. power:</strong> <?php echo $sat_morale; ?>%<br/>
			</td>
		</tr>
	</table>
</div>

