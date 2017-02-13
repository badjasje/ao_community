<?php //if(@fsockopen($_SERVER['REMOTE_ADDR'], 80, $errstr, $errno, 1)) die("Proxy access not allowed");?>
<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //globals
	    
    global $post, $page, $paged, $woocommerce;
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
            		<a class="brand" href="<?php  echo esc_url(site_url('/')); ?>"> <img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> </a>
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


                <?php if(has_nav_menu('loggedout-menu')) { ?>
              <?php if(function_exists( 'ubermenu' )){ ?>
              	<?php ubermenu( 'main' , array( 'theme_location' => 'loggedout-menu' ) ); ?>
			  <?php }else{ ?>
              <?php wp_nav_menu( array( 'theme_location'  => 'loggedout-menu', 'depth' => 0,'sort_column' => 'menu_order', 'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>', 'walker'  => new crystalskull_Walker_Quickstart_Menu()) ); ?>
                <?php } ?>

                <?php }else { ?>
                   <ul  class="nav"><li>
                   <a href="#"><?php esc_html_e('No menu assigned!', 'crystalskull'); ?></a>
                   </li></ul>
                <?php } ?>

               
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
             <h1><?php the_title();?></h1>
            </div>
       

        <div class="clear"></div>
</div>
<?php } ?>
