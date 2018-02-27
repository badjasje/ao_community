<!DOCTYPE html>
<html  <?php language_attributes(); ?>>
    <head>

	    <meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    
		<?php wp_head(); ?>
	
		<?php include_once 'css/colours.css.php'; ?>
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
            		<a class="brand" href="<?php  echo esc_url(site_url('/home/')); ?>"> 
	            		<img src="<?php echo esc_url(of_get_option('logo')); ?>" alt="logo"  /> 
	            	</a>
          	</div>

            <div class="navbar-collapse">
				<?php wp_nav_menu( 
					array( 'theme_location'  => 'loggedout-menu', 
					'depth' => 0,
					'sort_column' => 'menu_order', 
					'items_wrap' => '<ul  class="nav navbar-nav">%3$s</ul>', 
					'walker'  => new crystalskull_Walker_Quickstart_Menu()) ); ?>
            </div><!--/.nav-collapse -->

          </div><!-- /.navbar-inner -->

    </div><!-- /.navbar -->

<div class="title_wrapper container">

	<div class="col-lg-12">
		<h1>Home</h1>
	</div>
	<div class="col-lg-12 breadcrumbs" style="float:left;margin-top: 0;"><strong><?php crystalskull_breadcrumbs(); ?></strong></div>
	<div class="clear"></div>

</div>

