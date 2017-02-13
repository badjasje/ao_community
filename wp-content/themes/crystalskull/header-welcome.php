<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //globals
    global $post, $page, $paged, $woocommerce;
$user_ID = get_current_user_ID();
$new_events = get_user_meta($user_ID, 'new_events');
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

<?php wp_head(); ?>
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

        

		

    </div><!-- /.container -->
    </div><!-- /.top-menu-bar -->

       <div class="navbar navbar-inverse navbar-static-top container" role="navigation">
       	<div class="logo col-lg-3 col-md-3">
            		<a class="brand" href="<?php  echo esc_url(site_url('/')); ?>"> <img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> </a>
          		</div>
			 <?php if(!function_exists( 'ubermenu' )){ ?>
            <div class="navbar-header">
 
            </div>
            <?php } ?>

            <div class="navbar-collapse <?php if(!function_exists( 'ubermenu' )){ echo 'collapse'; } ?>">


             
               
            </div><!--/.nav-collapse -->

          </div><!-- /.navbar-inner -->

    </div><!-- /.navbar -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
<?php
if(is_plugin_active('buddypress/bp-loader.php') && function_exists( 'bp_current_component' ) ){
    $component = bp_current_component();
    if($component == 'members'){ ?>
        <div class="title_wrapper container">

            <div class="col-lg-12">
           		<h1><?php esc_html_e('Search members', 'crystalskull'); ?></h1>
            </div>

        <div class="clear"></div>
</div>
    <?php }
}else{
    $component = false;
}
?>
<?php if(is_singular('clan') or $component or is_front_page() or is_page_template('tmp-home.php')  or is_page_template('tmp-no-title.php') or is_page_template('tmp-home-left.php') or is_page_template('tmp-home-right.php') or is_page_template('tmp-home-news.php')){}elseif(is_search()){ ?>
<div class="title_wrapper container">

            <div class="col-lg-12"><h1><?php esc_html_e('Search: ', 'crystalskull');  echo get_search_query(); ?></h1></div>
            <div class="col-lg-12 breadcrumbs"><strong><?php crystalskull_breadcrumbs(); ?></strong></div>

</div>
<?php }else{ ?>
<div class="title_wrapper container">


            <div class="col-lg-12">

            	<?php if(is_single() && ( get_post_type($post->ID) == 'post')){
				  	$categories = wp_get_post_categories($post->ID);
					echo "<div class='cat-single'>";
					foreach ($categories as $category) { ?>
					<?php $cat_data = get_option("category_$category");  ?>
					<a href="<?php echo esc_url(get_category_link($category)); ?>" class="ncategory" style="background-color: <?php echo esc_attr($cat_data['catBG']); ?> !important" >
       							  <?php	echo esc_attr(get_cat_name($category)); ?>
					</a>
					<?php }
					echo "</div>";
				}  ?>
             <h1><?php the_title();?> <?php if(is_page(3486)):?><a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a><?php endif;?>
            </h1>
            </div>
            

        <div class="clear"></div>
</div>
<?php } ?>




        <div class="after-nav ">
        	<div class="container globalstats">
            <div class="statitem"><strong>Money:</strong> $ <?php echo number_format($totalmoney[0], 0, ',', ' '); ?></div>
			<div class="statitem"><strong>Networth:</strong> $ <?php echo number_format($networth[0], 0, ',', ' '); ?></div> 
			<div class="statitem"><strong>Turns:</strong> <?php echo $turns[0]; ?></div>
			<div class="statitem"><strong>Morale:</strong> <?php echo $morale[0]; ?>% <sup>(<?php echo $moralepool[0];?>%)</sup></div>
			<div class="statitem"><strong>Land:</strong> <?php echo number_format($land[0], 0, ',', ' '); ?> m<sup>2</sup></div>
            </div>
        </div>

