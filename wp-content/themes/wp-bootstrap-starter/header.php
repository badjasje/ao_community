<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-TXGKNL3');</script>
	<!-- End Google Tag Manager -->
	<?php
	global $userId;
	global $userData;
	if($userData['status'][0] == 'banned'){
		echo '<br/><br/><center>Your account is banned from Assault.Online.</center>'; die;
	}

	$inProgress = $userData['research_in_progress'][0];
	$researchTimeLeft ='';
	if(!empty($inProgress)) {
		$timestamp = current_time('timestamp');
		$args = array('posts_per_page' => 1, 'author' => $userId, 'post_type' => 'research');
		$researches_in_progress = get_posts( $args );
		$completionTime = $researches_in_progress[0]->post_title;
		$researchTimeLeft = human_time_diff($completionTime, $timestamp);
	}
	include('research_array.php');
	wp_head();

	$hideitems = 'false';
	if(!is_user_logged_in()){
		$hideitems = 'true';
	}
	$menuOpen = (isset($_COOKIE['menuOpen'])&&$_COOKIE['menuOpen']==1?true:false);

	$pageId = get_the_id();
	$endDate = get_field('end_date','option');
	$endStamp = strtotime($endDate);
	$timestamp = current_time('timestamp');
	$timeLeft = $endStamp-$timestamp;
	$marketClose = $timeLeft + 86400;
	$msgs = $userData['new_messages'][0];
	$locals = $userData['new_events'][0];
	$globals = $userData['new_global_events'][0];
	?>

	<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
	<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
	<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/jquery.countdown.min.js?ver=4.9.4'></script>
	<script type='text/javascript' src='/wp-content/themes/wp-bootstrap-starter/js/sortingdivs.js'></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
	<script>
		jQuery.ajaxSetup({cache: false});
	</script>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link rel="icon" type="image/png" href="/wp-content/themes/wp-bootstrap-starter/img/favicon.png">
	<link rel="manifest" href="/manifest.json">

	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1603414756640075');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=1603414756640075&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-40825301-45"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-40825301-45');
	</script>

	<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-messaging.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-functions.js"></script>
	<script>
		var config = {
			apiKey: "AIzaSyBBkuM6n38eUe5yqw50KjpM7HHAR2RGdOQ",
			authDomain: "assaultonline-21594.firebaseapp.com",
			databaseURL: "https://assaultonline-21594.firebaseio.com",
			projectId: "assaultonline-21594",
			storageBucket: "assaultonline-21594.appspot.com",
			messagingSenderId: "776419312119"
		};
		firebase.initializeApp(config);
		const messaging = firebase.messaging();
		messaging.usePublicVapidKey("BPywnXWNiczMF1nEPWQ6hZOudN81OwAbvcBWQBaDx5FVFUG7Rdl0J9sd1GjqA7KpzDKYtOoWnlx-vY39C9uh3h0");
		messaging.getToken().then(function(currentToken) {
			if (currentToken) {
				jQuery.post("/addtoken.php",{usertoken : currentToken});
			} else {
				// Show permission request.
				console.log('No Instance ID token available. Request permission to generate one.');
				// Show permission UI.
				updateUIForPushPermissionRequired();
				setTokenSentToServer(false);
			}
		}).catch(function(err) {
			console.log('An error occurred while retrieving token. ', err);
			if(typeof showToken == 'function') showToken('Error retrieving Instance ID token. ', err);
			if(typeof setTokenSentToServer == 'function') setTokenSentToServer(false);
		});
	</script>
</head>

<body <?php body_class(array(($menuOpen?'menuOpen':''))) ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXGKNL3"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<script>
		fbq('track', 'ViewContent', {value: 1,content_ids: '<?php echo get_the_title();?>'});
	</script>

	<div id="splashback" class=""></div>
	<div class="splashmessage"></div>

	<div id="page-cover"></div>
	<div class="pageLoader"><i class="fa fa-circle-notch fa-spin"></i></div>

	<div id="page" class="site">

		<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
			<?php if($hideitems == 'false'):?>
				<button id="nextbt" class="toggle-menu-open hamburger hamburger--collapse<?=($menuOpen?' is-active':'')?>" type="button">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>

				<a href="/dashboard/">
					<button class="menu-item dashMobile" type="button" >
						<i class="fas fa-tachometer-alt"></i>
					</button>
				</a>

				<div class="">
					<?php echo small_avatar($userId,'menuAvatar');?>
				</div>

				<a href="/conversations">
					<button class="menu-item inboxButton" type="button" >
						<i class="fas fa-envelope"></i>
						<span class="badge badge-pill badge-info inboxBadge <?php if($msgs > 0):?>activebadge<?php endif;?>"><?php echo $msgs;?></span>
					</button>
				</a>
				<a href="/events/incoming">
					<button class="menu-item" type="button" >
						<i class="fas fa-arrow-circle-down"></i>
						<span class="badge badge-pill badge-primary localsBadge <?php if($locals > 0):?>activebadge<?php endif;?>"><?php echo $locals;?></span>
					</button>
				</a>
				<a href="/events/global">
					<button class="menu-item" type="button" >
						<i class="fas fa-globe"></i>
						<span class="badge badge-pill badge-danger globalsBadge <?php if($globals > 0):?>activebadge<?php endif;?>"><?php echo $globals;?></span>
					</button>
				</a>

				<div class="row topstatheader">
					<div class="col-md-2 statitem">
						<span class="stattext">
							<strong>Money:</strong> $ <span class="moneyheader"><?php echo number_format($userData['money'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-md-2  statitem">
						<span class="stattext">
							<strong>Networth:</strong> $ <span class="networthheader"><?php echo number_format($userData['networth'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-md-2 statitem">
						<span class="stattext">
							<strong>Turns:</strong> <span class="turnsheader"><?php echo number_format($userData['turns'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-md-2 statitem">
						<span data-toggle="tooltip" data-placement="bottom" title="Satellite power: <?php echo number_format($userData['sat_morale'][0], 0, ',', ' '); ?>%" class="stattext"><strong>Morale:</strong> <span class="moraleheader"><?php echo number_format($userData['morale'][0], 0, ',', ' '); ?></span>%<sup><span id="poolmorale"><?php echo $userData['morale_pool'][0];?></span>%</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-md-2 statitem">
						<span data-toggle="tooltip" data-placement="bottom" title="Free land: <?php echo number_format($userData['land'][0]-$userData['builtland'][0], 0, ',', ' '); ?>m2" class="stattext"><strong>Land:</strong> <span class="landheader"><?php echo number_format($userData['land'][0], 0, ',', ' '); ?></span>m<sup>2</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-md-2 statitem">
						<span class="stattext"><strong>Power usage:</strong> <span class="powerheader"><?php echo number_format($userData['power'][0], 0, ',', ' '); ?></span>%</span>
					</div>
				</div>
			<?php endif;?>
		</header><!-- #masthead -->

		<?php if($hideitems == 'false'):?>
			<div id="mySidenav" class="sidenav">

				<div class="row menuRow  hide-menu-icon">
					<div class="col-md-2 col-xs-2 buttonItem ">
						<a href="<?php echo get_site_url(); ?>/dashboard/">
							<button class="menu-item" type="button" >
								<i class="fas fa-tachometer-alt"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/dashboard/">Dashboard</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/research">
							<button class="menu-item" type="button" >
								<i class="fas fa-flask"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/research">Research
							<?php if($inProgress != '0'):?>
								<span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Research currently in progress: <?php echo $researches[$inProgress]['name'];?>, <?=$researchTimeLeft?> left">
									<i class="fas fa-circle-notch fa-spin"></i>
								</span>
							<?php endif;?>
						</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/bank">
							<button class="menu-item" type="button" >
								<i class="fas fa-university"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/bank">Bank <span class="badge badge-secondary"><?php echo count_deposits($userId);?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/buildings">
							<button class="menu-item" type="button" >
								<i class="fas fa-industry"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/buildings">Buildings <span class="badge badge-secondary"><?php echo do_shortcode('[current-buildings]');?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/explore">
							<button class="menu-item" type="button" >
								<i class="fas fa-map"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/explore">Explore</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/units">
							<button class="menu-item" type="button" >
								<i class="fas fa-fighter-jet"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/units">Units <span class="badge badge-secondary"><?php echo do_shortcode('[current-units]');?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/missiles">
							<button class="menu-item" type="button" >
								<i class="fas fa-rocket"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/missiles">Missiles <span class="badge badge-secondary"><?php echo do_shortcode('[current-missiles]');?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/satellites">
							<button class="menu-item" type="button" >
								<i class="fas fa-bullseye"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/satellites">Satellites <span class="badge badge-secondary"><?php echo do_shortcode('[current-satellites]');?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/users/">
							<button class="menu-item" type="button" >
								<i class="fas fa-search"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/users/">
							<div class="marketMenu">All users</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/all-clans/">
							<div class="marketMenu">All clans</div>
						</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/buy/">
							<button class="menu-item" type="button" >
								<i class="fas fa-shopping-cart"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/buy/">
							<div class="marketMenu">Market</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/sell/">
							<div class="marketMenu">Sell</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/orders/">
							<div class="marketMenu">Orders</div>
						</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/clan-information">
							<button class="menu-item" type="button" >
								<i class="fas fa-users"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/clan-information">
							<div class="marketMenu">Clan</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/clan-wars">
							<div class="marketMenu">Wars</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/send-aid">
							<div class="marketMenu">Send aid</div>
						</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?php echo get_site_url(); ?>/toplists/">
							<button class="menu-item" type="button" >
								<i class="fas fa-trophy"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?php echo get_site_url(); ?>/toplists/">
							<div class="marketMenu">Toplists (nw)</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/toplists/?tab=clanpoints">
							<div class="marketMenu">Clan points</div>
						</a>
						<a href="<?php echo get_site_url(); ?>/toplists/?tab=clannw">
							<div class="marketMenu">Clan nw</div>
						</a>
					</div>
				</div>

				<?/*<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a target="_blank" href="<?php echo get_site_url(); ?>/push-messaging.html">
							<button class="menu-item" type="button" >
								<i class="fas fa-bell"></i>
							</button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a target="_blank" href="<?php echo get_site_url(); ?>/push-messaging.html">Push Messaging</a>
					</div>
				</div>

				<div class="row menuRow ">
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
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/getting-started">Getting Started</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/forum">Forum</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/all-clans/">All Clans</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/users">Users</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/toplists">Toplists</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/player-statistics">Statistics</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/category/awards-medals/">Awards & Medals</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/manual">Manual</a>
								<a class="dropdown-item" href="<?php echo get_site_url(); ?>/rules">Rules</a>
								<a class="dropdown-item" target="_blank" href="http://bit.ly/2US8Dh0">Discord</a>
							</div>
						</div>
					</div>
				</div>*/?>

			<div id="page-sub-header">
				<div class="row statheader">
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Money:</strong> $ <span class="moneyheader"><?php echo number_format($userData['money'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Net.:</strong> $ <span class="networthheader"><?php echo number_format($userData['networth'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Turns:</strong> <span class="turnsheader"><?php echo number_format($userData['turns'][0], 0, ',', ' '); ?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span data-toggle="tooltip" data-placement="bottom" title="Satellite power: <?php echo number_format($userData['sat_morale'][0], 0, ',', ' '); ?>%"  class="stattext"><strong>Morale:</strong> <span class="moraleheader"><?php echo number_format($userData['morale'][0], 0, ',', ' '); ?></span>%<sup><?php echo number_format($userData['morale_pool'][0], 0, ',', ' '); ?>%</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-6 statitem">
						<span data-toggle="tooltip" data-placement="bottom" title="Free land: <?php echo number_format($userData['land'][0]-$userData['builtland'][0], 0, ',', ' '); ?>m2" class="stattext"><strong>Land:</strong> <span class="landheader"><?php echo number_format($userData['land'][0], 0, ',', ' '); ?></span>m<sup>2</sup> <span style="float:right;"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext"><strong>Power usage:</strong> <span class="powerheader"><?php echo number_format($userData['power'][0], 0, ',', ' '); ?></span>%</span>
					</div>
				</div>
			</div>
		<?php endif;?>
	</div>

	<?php if(get_field('game_status','option') == 'Pause' /*&& $userId != 1*/) { // Check if game is live or not ?>
		<div class="permaNotification">
			<span class="rdw-line">
				<i class="fas fa-info-circle"></i> The round has ended! Expect a new round on <?php echo get_field('new_round_start','option');?>
			</span>
		</div>
	<?php } ?>

	<?php if($timeLeft < 172800 && $timeLeft > 0) {?>
		<div class="permaNotification">
			<i class="fas fa-info-circle"></i> <span id="market_timer"></span> left before the market closes
		</div>
	<?php } ?>

	<?php if(get_field('game_status','option') == 'Live' && $timeLeft < 1 && $pageId == 3179):?>
		<div class="permaNotification">
			<i class="fas fa-info-circle"></i> You cannot order units during the last 24 hours of the round
		</div>
	<?php endif;?>

	<div id="content" class="site-content">

		<div class="container mainContainer">
			<div class="titleBackWrapper <?php if($userData['stealth_sat_status'][0] == 'active'):?>stealthsatactive<?php endif;?>">
				<div class="pageTitle <?php if($userData['status'][0] == 'dead' && $userData['times_killed'][0] > 0):?>deadback<?php endif;?>">
					<?php if($userData['status'][0] == 'dead' && $userData['times_killed'][0] > 0):?>
						You died
					<?php else:?>
						<?php echo get_the_title();?>
					<?php endif;?>
				</div>
			</div>
			<div class="row contentRow">
				<script>
					function setCookie(name,value,days) {
						var expires = "";
						if (days) {
							var date = new Date();
							date.setTime(date.getTime() + (days*24*60*60*1000));
							expires = "; expires=" + date.toUTCString();
						}
						document.cookie = name + "=" + (value || "")  + expires + "; path=/";
					}
					function getCookie(name) {
						var nameEQ = name + "=";
						var ca = document.cookie.split(';');
						for(var i=0;i < ca.length;i++) {
							var c = ca[i];
							while (c.charAt(0)==' ') c = c.substring(1,c.length);
							if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
						}
						return null;
					}

					function updateHeaderData() {
						jQuery.getJSON('<?php echo get_site_url();?>/checkevents.php', function(data) {
							var globals = data.globals;
							var locals = data.locals;
							var messages = data.messages;
							var money = data.money;
							jQuery('.moneyheader').text(number_format(money, 0, ',', ' '));
							var networth = data.networth;
							jQuery('.networthheader').text(number_format(networth, 0, ',', ' '));
							var turns = data.turns;
							jQuery('.turnsheader').text(turns);
							var morale = data.morale;
							jQuery('.moraleheader').text(morale);
							var land = data.land;
							jQuery('.landheader').text(number_format(land, 0, ',', ' '));
							var power = data.power;
							jQuery('.powerheader').text(number_format(power, 0, ',', ' '));
							if (globals > 1){
								jQuery('.globalsBadge').text(globals);
								jQuery('.globalsBadge').show(100);
								jQuery('title').text(globals+' new global events');
							}
							if (locals > 1){
								jQuery('.localsBadge').text(locals);
								jQuery('.localsBadge').show(100);
							}
							if (messages > 1){
								jQuery('.inboxBadge').text(messages);
								jQuery('.inboxBadge').show(100);
							}
						});
					}

					(function($) {
						<?php if($userData['status'][0] == 'dead' && $userData['times_killed'][0] > 0) {
							after_death($userId);
							update_user_meta($userId, 'status', 'nukeprotection');
							update_user_meta($userId, 'nuke_protection_timestamp', $timestamp+(48 * 3600));
							?>
							$(".splashmessage").html('You died');
							$("#splashback").addClass( "failsplash" );
							$("#splashback,.splashmessage").show();
							$("#splashback,.splashmessage").delay(1500).fadeOut("slow")
							<?php
						} ?>

						<?php if($timeLeft < 172800+86400) {?>
							var diff = <?php echo ($marketClose-86400)*1000;?>;
							function updateMarketTime() {
								days = Math.floor( diff / (1000*60*60*48) ),
								hours = Math.floor( diff / (1000*60*60) ),
								mins = Math.floor( diff / (1000*60) ),
								secs = Math.floor( diff / 1000 ),
								dd = days,
								hh = hours - days * 24,
								mm = mins - hours * 60,
								ss = secs - mins * 60;
								$("#market_timer").text( ('00'+hh).slice(-2) +':'+ ('00'+mm).slice(-2) +':'+ ('00'+ss).slice(-2) );
								<? if(get_field('game_status','option') == 'Live') { ?>
								diff -= 1000;
								if(diff <= 0){
									$('.permaNotification').html('<i class="fas fa-info-circle"></i> You cannot order units during the last 24 hours of the round');
									return false;
								}
								<? } ?>
							}
							setInterval(updateMarketTime, 1000 );
						<?php } ?>

						// Help in icon menu
						$('.menuRow').each(function(i1) {
							var t = $('.menuText>a',this).html();
							if(!!t) {
								$('.buttonItem>a', this).wrapInner('<div data-toggle="tooltip" data-html="true" data-placement="right" title="'+t.replace(/"/g, "'")+'"></div>');
							}
						});

						if(getCookie('menuOpen') === null) setCookie('menuOpen', 0, 256);
						else { setCookie('menuOpen', getCookie('menuOpen'), 256); } // Remember forever

						$("#nextbt, #nextbt2").on('click', function(e) {
							e.preventDefault();
							if($('body').hasClass('menuOpen')) {
								$('body').removeClass('menuOpen');
								$('.menuText').hide(500);
								$(".hamburger").removeClass("is-active");
								$('[data-toggle=tooltip]').tooltip('enable');
								setCookie('menuOpen', 0, 256);
							} else {
								$('body').addClass('menuOpen');
								$('.menuText').show(750);
								$(".hamburger").addClass("is-active");
								$('[data-toggle=tooltip]').tooltip('disable');
								if($(window).width() >= 1400) setCookie('menuOpen', 1, 256);
							}
						});

						$(document).ready(function() {
							$(function () {
								$('[data-toggle="tooltip"]').tooltip()
							})
						});
						var i = setInterval(function() { updateHeaderData(); }, 10000);
					})(jQuery);
				</script>