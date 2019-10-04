<?php

$pageId = get_the_id();
$user = CurrentUser::make();
$province = ($user->isLoggedIn() ? $user->getProvince() : false);

$menuOpen = (isset($_COOKIE['menuOpen'])&&$_COOKIE['menuOpen']==1?true:false);
if(!$user->isLoggedIn()) $menuOpen = false;

$researchInProgress = false;
if(!!$province && $r = $province->getCurrentResearch()) $researchInProgress = $r->get('name');

$provinceDied = (!!$province && $province->isDead() && $province->get('times_killed') > 0);
if($provinceDied) { // this also happens in currentuser construct, but only the first time I guess?
	$province->afterDeath();
	$province->update('status', 'nukeprotection');
	$province->update('nuke_protection_timestamp', current_time('timestamp') + Settings::get('nuke_protection_length'));
}

// Auto tab of unittype you have most of
$tab = 'air'; $nums = array(); $max = 0;
foreach(array_keys(Settings::get('unit_types')) as $type) $nums[$type] = (!!$province ? $province->getUnitTypeNum($type) : 0);
foreach($nums as $type => $num) {
	if($num > $max) { $tab = $type; $max = $num; }
}

$timeLeft = Market::timeLeft();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php
	wp_head();
	/* not needed because of fontawesome 4 from forum
	<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js" data-auto-add-css="false" integrity="sha384-xymdQtn1n3lH2wcu0qhcdaOpQwyoarkgLVxC/wZ5q7h9gHtxICrpcaSUfygqZGOe" crossorigin="anonymous"></script>
	<link href="https://use.fontawesome.com/releases/v5.0.13/css/svg-with-js.css" rel="stylesheet" />
	*/?>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/img/favicon.png">
	<link rel="manifest" href="<?=Request::siteUrl()?>/manifest.json">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-40825301-45"></script>
</head>

<body <?php body_class(array(($menuOpen?'menuOpen':''),'game-type-'.Round::get('type'))) ?> data-siteurl="<?=Request::siteUrl()?>">
	<?/* noscript? really?
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXGKNL3" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1603414756640075&ev=PageView&noscript=1" /></noscript>
	*/?>
	<script>fbq('track', 'ViewContent', {value: 1,content_ids: '<?php echo get_the_title();?>'});</script>

	<div id="splashback" class=""></div>
	<div class="splashmessage"></div>

	<div id="page-cover"></div>
	<div class="pageLoader"><i class="fa fa-circle-notch fa-spin"></i></div>

	<div id="page" class="site">

		<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
			<?php if($user->isLoggedIn()) { ?>
				<button id="nextbt" class="toggle-menu-open hamburger hamburger--collapse<?=($menuOpen?' is-active':'')?>" type="button">
					<span class="hamburger-box"><span class="hamburger-inner"></span></span>
				</button>

				<a href="/dashboard/">
					<button class="menu-item dashMobile" type="button"><i class="fas fa-tachometer-alt"></i></button>
				</a>

				<div><?=$province->getAvatar('menuAvatar')?></div>

				<a href="<?=Request::siteUrl()?>/conversations">
					<button class="menu-item inboxButton" type="button" >
						<i class="fas fa-envelope"></i>
						<span class="badge badge-pill badge-info inboxBadge">0</span>
					</button>
				</a>
				<a href="<?=Request::siteUrl()?>/events/incoming">
					<button class="menu-item" type="button" >
						<i class="fas fa-arrow-circle-down"></i>
						<span class="badge badge-pill badge-primary localsBadge">0</span>
					</button>
				</a>
				<a href="<?=Request::siteUrl()?>/events/global">
					<button class="menu-item" type="button" >
						<i class="fas fa-globe"></i>
						<span class="badge badge-pill badge-danger globalsBadge">0</span>
					</button>
				</a>

				<div class="row topstatheader">
					<div class="col-md-2 statitem">
						<span class="stattext">
							<strong>Money:</strong> <span class="moneyheader"><?=$province->getMoney(true)?></span>
						</span>
					</div>
					<div class="col-md-2  statitem">
						<span class="stattext">
							<strong>Networth:</strong> <span class="networthheader"><?=$province->getNetworth(true)?></span>
						</span>
					</div>
					<div class="col-md-2 statitem">
						<span class="stattext">
							<strong>Turns:</strong> <span class="turnsheader"><?=$province->getTurns(true)?></span>
						</span>
					</div>
					<div class="col-md-2 statitem">
						<span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Satellite power: <?=$province->getSatMorale(true)?>" class="satpower stattext">
							<strong>Morale:</strong> <span class="moraleheader"><?=$province->getMorale(true)?></span>
							<sup><span id="poolmorale"><?=$province->getMoralePool(true)?></span></sup>
							<span class="float-right"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-md-2 statitem">
						<span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Free land: <?=$province->getFreeLand(true)?>" class="freeland stattext">
						<strong>Land:</strong> <span class="landheader"><?=$province->getLand(true); ?></span>
						<span class="float-right"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-md-2 statitem">
						<span class="stattext"><strong>Power usage:</strong>
						<span class="powerheader"><?=$province->getPower(true)?></span></span>
					</div>
				</div>
			<?php } ?>
		</header>

		<?php if($user->isLoggedIn()) { ?>
			<div id="mySidenav" class="sidenav">

				<div class="row menuRow  hide-menu-icon">
					<div class="col-md-2 col-xs-2 buttonItem ">
						<a href="<?=Request::siteUrl()?>/dashboard/">
							<button class="menu-item" type="button"><i class="fas fa-tachometer-alt"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/dashboard/">Dashboard</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/bank">
							<button class="menu-item" type="button"><i class="fas fa-university"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/bank">Bank
						<span class="badge badge-secondary"><?=$province->getDepositNum()?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/research">
							<button class="menu-item" type="button"><i class="fas fa-flask"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/research">Research
							<?php if(!!$researchInProgress) { ?>
								<span class="badge badge-secondary" data-toggle="tooltip" data-placement="top"
									title="Research currently in progress: <?=$researchInProgress?>, <?=$province->getResearchTimeLeft(true)?> left">
									<i class="fas fa-circle-notch fa-spin"></i>
								</span>
							<?php } ?>
						</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/explore">
							<button class="menu-item" type="button"><i class="fas fa-map"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/explore">Explore</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/buildings">
							<button class="menu-item" type="button"><i class="fas fa-industry"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/buildings">Buildings
						<span class="badge badge-secondary"><?=$province->getBuildingsNum()?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/units?tab=<?=$tab?>">
							<button class="menu-item" type="button"><i class="fas fa-fighter-jet"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/units?tab=<?=$tab?>">Units
						<span class="badge badge-secondary"><?=$province->getUnitsNum()?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/missiles">
							<button class="menu-item" type="button"><i class="fas fa-rocket"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/missiles">Missiles
						<span class="badge badge-secondary"><?=$province->getMissileNum()?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/satellites">
							<button class="menu-item" type="button"><i class="fas fa-bullseye"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/satellites">Satellites
						<span class="badge badge-secondary"><?=$province->getSatelliteNum()?></span></a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/buy/?tab=<?=$tab?>">
							<button class="menu-item" type="button"><i class="fas fa-shopping-cart"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/buy/?tab=<?=$tab?>" class="marketMenu">Buy</a>
						<a href="<?=Request::siteUrl()?>/sell/?tab=<?=$tab?>" class="marketMenu">Sell</a>
						<a href="<?=Request::siteUrl()?>/orders/" class="marketMenu">Orders</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/clan-information">
							<button class="menu-item" type="button"><i class="fas fa-users"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/clan-information" class="marketMenu">Clan</a>
						<a href="<?=Request::siteUrl()?>/clan-wars" class="marketMenu">Wars</a>
						<a href="<?=Request::siteUrl()?>/send-aid" class="marketMenu">Send aid</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/users/">
							<button class="menu-item" type="button"><i class="fas fa-search"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/users/" class="marketMenu">All users</a>
						<a href="<?=Request::siteUrl()?>/all-clans/" class="marketMenu">All clans</a>
					</div>
				</div>

				<div class="row menuRow ">
					<div class="col-md-2 col-xs-2 buttonItem">
						<a href="<?=Request::siteUrl()?>/toplists/">
							<button class="menu-item" type="button"><i class="fas fa-trophy"></i></button>
						</a>
					</div>
					<div class="col-md-10 col-xs-10 menuText">
						<a href="<?=Request::siteUrl()?>/toplists/" class="marketMenu">Toplists (nw)</a>
						<a href="<?=Request::siteUrl()?>/toplists/?tab=clanpoints" class="marketMenu">Clan points</a>
						<a href="<?=Request::siteUrl()?>/toplists/?tab=clannw" class="marketMenu">Clan nw</a>
					</div>
				</div>

			<?php if($user->isLoggedIn()) { ?>
			<div id="page-sub-header">
				<div class="row statheader">
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Money:</strong> <span class="moneyheader"><?=$province->getMoney(true)?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Net.:</strong> <span class="networthheader"><?=$province->getNetworth(true)?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext">
							<strong>Turns:</strong> <span class="turnsheader"><?=$province->getTurns(true)?></span>
						</span>
					</div>
					<div class="col-6 statitem">
						<span data-toggle="tooltip" data-placement="bottom" title="Satellite power: <?=$province->getSatMorale(true)?>" class="stattext satpower">
						<strong>Morale:</strong> <span class="moraleheader"><?=$province->getMorale(true)?></span>
						<sup><?=$province->getMoralePool(true)?></sup>
						<span class="float-right"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-6 statitem">
						<span data-toggle="tooltip" data-placement="bottom" data-html="true" title="Free land: <?=$province->getFreeLand(true)?>" class="stattext freeland">
						<strong>Land:</strong> <span class="landheader"><?=$province->getLand(true)?></span>
						<span class="float-right"><i class="fas fa-caret-down"></i></span></span>
					</div>
					<div class="col-6 statitem">
						<span class="stattext"><strong>Power usage:</strong> <span class="powerheader"><?=$province->getPower(true)?></span></span>
					</div>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>

	<?php if(Round::isPaused()) { ?>
		<div class="permaNotification">
			<span class="rdw-line">
				<i class="fas fa-info-circle"></i> The round has ended! Expect a new round on <?=Round::nextRoundStartDate();?>
			</span>
		</div>
	<?php } ?>

	<?php if($timeLeft < 172800 && $timeLeft > 0) {?>
		<div class="permaNotification">
			<i class="fas fa-info-circle"></i> <span id="market_timer" data-countdown="<?=$timeLeft?>"></span> left before the market closes
		</div>
	<?php } ?>

	<?php if(Round::isLive() && $timeLeft < 1 && $pageId == 3179):?>
		<div class="permaNotification">
			<i class="fas fa-info-circle"></i> You cannot order units during the last 24 hours of the round
		</div>
	<?php endif;?>

	<div id="content" class="site-content">

		<div class="container mainContainer">
			<div class="titleBackWrapper<?=(!!$province && $province->getSatellites('stealths')['status']=='active'?' stealthsatactive':'')?>">
				<div class="pageTitle <?=($provinceDied ? ' deadback':'')?>">
					<?=($provinceDied ? t('You died') : get_the_title()) ?>
				</div>
			</div>
			<div class="row contentRow">