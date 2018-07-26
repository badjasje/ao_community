<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php 

	global $userId;
	$userId = get_current_user_id();
	global $userData;
	$userData = get_user_meta($userId);
	$inProgress = $userData['research_in_progress'][0];
	include('research_array.php');
	wp_head(); 
	$hideitems = 'false';
	if(!is_user_logged_in()){
		$hideitems = 'true';
	}
	$pageId = get_the_id();
	$endDate = get_field('end_date','option');
	$endStamp = strtotime($endDate);
	$timestamp = current_time('timestamp');
	$timeLeft = $endStamp-$timestamp;
	$marketClose = $timeLeft + 86400;

	?>
<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/jquery.countdown.min.js?ver=4.9.4'></script>
<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/sortingdivs.js'></script>



<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
<?php if($hideitems == 'true' && $pageId != 3491 && $pageId != 3484):?>
	 
	<script type="text/javascript">
	window.location.href = '<?php echo get_site_url();?>/home';
	</script>

<?php exit; endif;?>
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/moment.js'></script>
<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/moment-with-locales.js'></script>
<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/moment-timezone-with-data.js'></script>
</head>

<body <?php body_class(); ?>>

<div style="display:none;" class="splashHeader" id="successsplash">
	<div style="margin-top:20%">
		S U C C E S S
	</div>
</div>
<div style="display:none;" class="splashHeader failSplash" id="failsplash">
	<div style="margin-top:20%">
		F A I L U R E
	</div>
</div>
<div id="page-cover"></div>
<div class="pageLoader"><i class="fa fa-circle-notch fa-spin"></i></div>

<div id="page" class="site">
	<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
		<?php if($hideitems == 'false'):?>
		<button id="nextbt" class="toggle-menu-open hamburger hamburger--collapse" type="button">
			<span class="hamburger-box">
<span class="hamburger-inner"></span>
			</span>
		</button>
		
		<a href="/dashboard">
			<button class="menu-item dashMobile" type="button" >
				<i class="fas fa-tachometer-alt"></i> 
			</button>
		</a>
		
		<div class="">
		<?php echo small_avatar($userId,'menuAvatar');?>
		</div>
		<a href="/inbox">
		<button class="menu-item inboxButton" type="button" >
			<i class="fas fa-envelope"></i> 
			<span class="badge badge-pill badge-info inboxBadge"></span>
		</button>
		</a>

		<a href="/events/incoming">
		<button class="menu-item" type="button" >
			<i class="fas fa-globe"></i> 
			<span class="badge badge-pill badge-primary localsBadge"></span>
			<span class="badge badge-pill badge-danger globalsBadge"></span>
		</button>
		</a>
	
	<div class="row topstatheader">
	 <div class="col-md-2 statitem">
		        <span class="stattext"><strong>Money:</strong> $ <span id="money"><?php echo number_format($userData['money'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-md-2  statitem">
		        <span class="stattext"><strong>Networth:</strong> $ <span id="networth"><?php echo number_format($userData['networth'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-md-2 statitem">
		        <span class="stattext"><strong>Turns:</strong> <span id="turns"><?php echo number_format($userData['turns'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-md-2 statitem">
		        <span data-toggle="tooltip" data-placement="bottom" title="Satellite power: <?php echo number_format($userData['sat_morale'][0], 0, ',', ' '); ?>%" class="stattext"><strong>Morale:</strong> <span id="morale"><?php echo number_format($userData['morale'][0], 0, ',', ' '); ?></span>%<sup><span id="poolmorale"><?php echo $userData['morale_pool'][0];?></span>%</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
	        </div>
	        <div class="col-md-2 statitem">
		        <span data-toggle="tooltip" data-placement="bottom" title="Free land: <?php echo number_format($userData['land'][0]-$userData['builtland'][0], 0, ',', ' '); ?>m2" class="stattext"><strong>Land:</strong> <span id="land"><?php echo number_format($userData['land'][0], 0, ',', ' '); ?></span>m<sup>2</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
	        </div>
	        <div class="col-md-2 statitem">
		        <span class="stattext"><strong>Power usage:</strong> <span id="power"><?php echo number_format($userData['power'][0], 0, ',', ' '); ?></span>%</span>
	        </div>
	</div>
	<?php endif;?>
	</header><!-- #masthead -->
	<?php if($hideitems == 'false'):?>
	<div id="mySidenav" class="sidenav">
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/dashboard">
						<button class="menu-item" type="button" >
							<i class="fas fa-tachometer-alt"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/dashboard">Dashboard</a>
				</div>
			</div>
			
			
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/research">
						<button class="menu-item" type="button" >
							<i class="fas fa-flask"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/research">Research 
						<?php if($inProgress != '0'):?>
							<span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Research currently in progress: <?php echo $researches[$inProgress]['name'];?>">
								<i class="fas fa-circle-o-notch fa-spin"></i>
							</span>
						<?php endif;?>
					</a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/bank">
						<button class="menu-item" type="button" >
							<i class="fas fa-university"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/bank">Bank <span class="badge badge-secondary"><?php echo count_deposits($userId);?></span></a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/buildings">
					<button class="menu-item" type="button" >
						<i class="fas fa-industry"></i> 
					</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/buildings">Buildings <span class="badge badge-secondary"><?php echo do_shortcode('[current-buildings]');?></span></a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/explore">
					<button class="menu-item" type="button" >
						<i class="fas fa-map"></i> 
					</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/explore">Explore</a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/units">
						<button class="menu-item" type="button" >
							<i class="fas fa-fighter-jet"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/units">Units <span class="badge badge-secondary"><?php echo do_shortcode('[current-units]');?></span></a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/missiles">
						<button class="menu-item" type="button" >
							<i class="fas fa-rocket"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/missiles">Missiles <span class="badge badge-secondary"><?php echo do_shortcode('[current-missiles]');?></span></a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/satellites">
					<button class="menu-item" type="button" >
						<i class="fas fa-bullseye"></i> 
					</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/satellites">Satellites <span class="badge badge-secondary"><?php echo do_shortcode('[current-satellites]');?></span></a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/users">
					<button class="menu-item" type="button" >
						<i class="fas fa-search"></i> 
					</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/users">
						<div class="marketMenu">All users</div>
					</a>
					<a href="/all-clans">
						<div class="marketMenu">All clans</div>
					</a>
				</div>
			</div>
			
			<div class="row menuRow hideMenuItem">
				
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/buy">
					<button class="menu-item" type="button" >
						<i class="fas fa-shopping-cart"></i> 
					</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/buy">
						<div class="marketMenu">Market</div>
					</a>
					<a href="/sell">
						<div class="marketMenu">Sell</div>
					</a>
					<a href="/orders">
						<div class="marketMenu">Orders</div>
					</a>
				</div>
				
			</div>
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<a href="/clan-information">
						<button class="menu-item" type="button" >
							<i class="fas fa-users"></i> 
						</button>
					</a>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<a href="/clan-information">
						<div class="marketMenu">Clan</div>
					</a>
					<a href="/clan-wars">
						<div class="marketMenu">Wars</div>
					</a>
					<a href="/send-aid">
						<div class="marketMenu">Send aid</div>
					</a>
				</div>
			</div>
			
			
			
			<div class="row menuRow hideMenuItem">
				<div class="col-md-2 col-xs-2 buttonItem">
					<button id="nextbt2" class="menu-item" type="button" >
						<i class="fas fa-list"></i> 
					</button>
				</div>
				<div class="col-md-10 col-xs-10 menuText">
					<!-- Default dropright button -->
						<div class="btn-group dropup" style="width:100%;">
						<button type="button" class="btn btn-secondary dropdown-toggle everythingElse hoverEffect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Everything else
						</button>
						<div class="dropdown-menu">
						<a class="dropdown-item" href="/getting-started">Getting Started</a>
						<a class="dropdown-item" href="/forum">Forum</a>
						<a class="dropdown-item" href="/all-clans/">All Clans</a>
						<a class="dropdown-item" href="/users">Users</a>
						<a class="dropdown-item" href="/toplists">Toplists</a>
						<a class="dropdown-item" href="#">Statistics</a>
						<a class="dropdown-item" href="/category/awards-medals/">Awards & Medals</a>
						<a class="dropdown-item" href="/manual">Manual</a>
						<a class="dropdown-item" href="/rules">Rules</a>
						<a class="dropdown-item" target="_blank" href="https://discord.gg/ttdng4n">Discord</a>
  					</div>
					</div>
				</div>
			</div>
			
			
			
		</div>


        <div id="page-sub-header">
	        <div class="row statheader">
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Money:</strong> $ <span id="money"><?php echo number_format($userData['money'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Net.:</strong> $ <span id="networth"><?php echo number_format($userData['networth'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Turns:</strong> <span id="turns"><?php echo number_format($userData['turns'][0], 0, ',', ' '); ?></span></span>
	        </div>
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Morale:</strong> <span id="morale"><?php echo number_format($userData['morale'][0], 0, ',', ' '); ?></span>%<sup><?php echo number_format($userData['morale_pool'][0], 0, ',', ' '); ?>%</sup></span>
	        </div>
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Land:</strong> <span id="land"><?php echo number_format($userData['land'][0], 0, ',', ' '); ?></span>m<sup>2</sup></span>
	        </div>
	        <div class="col-6 statitem">
		        <span class="stattext"><strong>Power usage:</strong> <?php echo number_format($userData['power'][0], 0, ',', ' '); ?>%</span>
	        </div>
	        </div>
	      	
	<?php endif;?>
            <div class="container">
                
            </div>
        </div>
<?php if(get_field('game_status','option') == 'Pause' /*&& $userId != 1*/): // Check if game is live or not ?>
<div class="permaNotification">
	<span class="rdw-line"><i class="fas fa-info-circle"></i> The round has ended! Expect a new round on <?php echo get_field('new_round_start','option');?></span>
</div>
<?php endif;?>


<?php if($timeLeft < 172800 + 86400 && $timeLeft > 0):?>
<div class="permaNotification">
	<i class="fas fa-info-circle"></i> <span id="countdown_time"></span> left before the market closes
</div>
<?php endif;?>
<?php if($timeLeft < 1 && $pageId == 3179):?>
<div class="permaNotification">
	<i class="fas fa-info-circle"></i> You cannot order units during the last 24 hours of the round
</div>
<?php endif;?>

	<div id="content" class="site-content">
		
		<div class="container mainContainer">
			<div class="titleBackWrapper">
				<div class="pageTitle"><?php echo get_the_title();?></div>
			</div>
			<div class="row contentRow">
            
                
<script>
	
<?php if($timeLeft < 172800+86400):?>
    var diff = <?php echo ($marketClose-86400)*1000;?>;

    function updateETime() {
        function pad(num) {
            return num > 9 ? num : '0'+num;
        };

        days = Math.floor( diff / (1000*60*60*48) ),
        hours = Math.floor( diff / (1000*60*60) ),
        mins = Math.floor( diff / (1000*60) ),
        secs = Math.floor( diff / 1000 ),

        dd = days,
        hh = hours - days * 24,
        mm = mins - hours * 60,
        ss = secs - mins * 60;

        document.getElementById("countdown_time").innerHTML =
            pad(hh) + ':' + //' hours ' +
            pad(mm) + ':' + //' minutes ' +
            pad(ss) ; //+ ' seconds' ;

        diff -= 1000;
    }
    setInterval(updateETime, 1000 );
<?php endif;?>
 
	
jQuery("#nextbt, #nextbt2").toggle(

function(){
jQuery( ".sidenav" ).addClass( "wideMenu" );
jQuery('.menuText').show(750);
jQuery( ".menuRow" ).removeClass( "hideMenuItem" );
jQuery( ".hamburger" ).addClass( "is-active" );

},

function(){
jQuery( ".sidenav" ).removeClass( "wideMenu" );
jQuery('.menuText').hide(500);
jQuery( ".menuRow" ).addClass( "hideMenuItem" );
jQuery( ".hamburger" ).removeClass( "is-active" );

}); 
		
</script>