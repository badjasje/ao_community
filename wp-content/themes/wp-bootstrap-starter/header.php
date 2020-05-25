<?php

$pageId = get_the_id();
$pageTitle = get_the_title();
if(in_array($pageTitle, array('Market Buy','Market Sell'))) $pageTitle = 'Market';

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

$stats = $menu = array();
if(!!$province) {
	$stats = array(
		0 => array('title' => 'Money', 'value' => $province->getMoney(true)), //moneyheader
		1 => array('title' => 'Networth', 'short' => 'Net.', 'class' => 'networth', 'value' => $province->getNetworth(true)), //networthheader
		2 => array('title' => 'Turns', 'value' => $province->getTurns(true)), //turnsheader
		3 => array('title' => 'Morale', 'value' => $province->getMorale(true), 'sup' => $province->getMoralePool(true)),
		4 => array('title' => 'Land', 'value' => $province->getLand(true), 'tooltip' => 'Free land: '.$province->getFreeLand(true)),
		5 => array('title' => 'Power usage', 'value' => $province->getPower(true)),
	);
	if($province->getSatelliteNum()) {
		$stats[3]['tooltip'] = 'Satellite power: '.$province->getSatMorale(true);
	}
	$stats = (date('d-m')=='01-04' ? shuffle_assoc($stats) : $stats);

	$menu = array(
		0 => array('icon' => 'fas fa-tachometer-alt', 'links' => array(
			array('url' => 'dashboard', 'title' => 'Dashboard'),
		)), // => + .hide-menu-icon
		1 => array('icon' => 'fas fa-university', 'links' => array(
			array('url' => 'bank', 'title' => 'Bank',  'badge' => $province->getDepositNum()),
		)),
		2 => array('icon' => 'fas fa-flask', 'links' => array(
			0 => array('url' => 'research', 'title' => 'Research'),
		)),
		3 => array('icon' => 'fas fa-map', 'url' => 'explore', 'links' => array(
			array('url' => 'explore?tab=explore', 'title' => 'Explore'),
			array('url' => 'explore?tab=sell', 'title' => 'Sell'),
		)),
		4 => array('icon' => 'fas fa-industry', 'links' => array(
			array('url' => 'buildings', 'title' => 'Buildings', 'badge' => $province->getBuildingsNum()),
		)),
		5 => array('icon' => 'fas fa-fighter-jet', 'links' => array(
			array('url' => 'units', 'title' => 'Units', 'badge' => $province->getUnitsNum()),
		)),
		6 => array('icon' => 'fas fa-rocket', 'links' => array(
			array('url' => 'missiles', 'title' => 'Missiles', 'badge' => $province->getMissileNum()),
		)),
		7 => array('icon' => 'fas fa-satellite', 'links' => array(
			array('url' => 'satellites', 'title' => 'Satellites', 'badge' => $province->getSatelliteNum()),
		)),
		8 => array('icon' => 'fas fa-shopping-cart', 'links' => array(
			array('url' => 'buy', 'title' => 'Market'),
			array('url' => 'orders', 'title' => 'Orders'),
		)),
		9 => array('icon' => 'fas fa-users', 'links' => array(
			array('url' => 'clan-information', 'title' => 'Clan'),
			array('url' => 'clan-wars', 'title' => 'Wars'),
			array('url' => 'send-aid', 'title' => 'Send aid'),
		)),
		10 => array('icon' => 'fas fa-search', 'links' => array(
			array('url' => 'users', 'title' => 'All users'),
			array('url' => 'all-clans', 'title' => 'All clans'),
		)),
		11 => array('icon' => 'fas fa-trophy', 'links' => array(
			array('url' => 'toplists', 'title' => 'Toplists (nw)'),
			array('url' => 'toplists/?tab=clanpoints', 'title' => 'Clan points'),
			array('url' => 'toplists/?tab=clannw', 'title' => 'Clan nw'),
		)),
	);
	if(!!$researchInProgress) {
		$menu[2]['links'][0] = array_merge($menu[2]['links'][0], array(
			'badge' => '<i class="fas fa-circle-notch fa-spin"></i>',
			'tooltip' => 'Research currently in progress: '.$researchInProgress.', '.$province->getResearchTimeLeft(true).' left'
		));
	}
	$menu = (date('d-m')=='01-04' ? shuffle_assoc($menu) : $menu);
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
	<? if(in_array(date('d-m'), array('24-12','25-12','26-12'))) { ?>
	<link href="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/css/hohoho.css" rel="stylesheet" />
	<? } ?>
	<? if(in_array(date('d-m'), array('28-10','29-10','30-10','31-10'))) { ?>
	<link href="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/css/boe.css" rel="stylesheet" />
	<? } ?>
	<meta name="google-site-verification" content="SkamiBCpY328MooWRMZNQN5_DshHSBeAp_4de8oiLpU" />
</head>

<body <?php body_class(array(($menuOpen?'menuOpen':''),'game-type-'.Round::get('type'))) ?> data-siteurl="<?=Request::siteUrl()?>">
	<?/* noscript? really?
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXGKNL3" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1603414756640075&ev=PageView&noscript=1" /></noscript>
	*/?>
	<script>fbq('track','ViewContent',{value:1,content_ids:'<?=$pageTitle?>'});</script>

	<div id="splashback" class=""></div>
	<div class="splashmessage"></div>

	<div id="page-cover"></div>
	<div class="pageLoader"><i class="fa fa-circle-notch fa-spin"></i></div>

	<div id="page" class="site">

		<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
			<?php if($user->isLoggedIn()) { ?>
				<button id="nextbt" class="toggle-menu-open" type="button">
					<span class="fa fa-fw fa-bars open-icon"></span>
					<span class="fa fa-fw fa-times close-icon"></span>
				</button>

				<a href="/dashboard/">
					<button class="menu-item dashMobile" type="button"><i class="fas fa-tachometer-alt"></i></button>
				</a>

				<div><?=$province->getAvatar('menuAvatar')?></div>

				<a href="<?=Request::siteUrl()?>/conversations">
					<button class="menu-item messagesButton" type="button" >
						<i class="fas fa-envelope"></i>
						<span class="badge badge-pill badge-info messagesBadge">0</span>
					</button>
				</a>
				<a href="<?=Request::siteUrl()?>/events/incoming">
					<button class="menu-item localsButton" type="button" >
						<i class="fas fa-arrow-circle-down"></i>
						<span class="badge badge-pill badge-primary localsBadge">0</span>
					</button>
				</a>
				<a href="<?=Request::siteUrl()?>/events/global">
					<button class="menu-item globalsButton" type="button" >
						<i class="fas fa-globe"></i>
						<span class="badge badge-pill badge-danger globalsBadge">0</span>
					</button>
				</a>

				<div class="row topstatheader">
					<? foreach($stats as $i => $stat) {
						$stat['class'] = strtolower($stat['title']);
						?>
						<div class="col-md-2 statitem">
							<span class="stattext"<?=(!empty($stat['tooltip'])?' data-toggle="tooltip" data-placement="bottom" data-html="true" title="'.$stat['tooltip'].'"':'')?>>
								<strong><?=$stat['title']?>:</strong> <span class="<?=$stat['class']?>header"><?=$stat['value']?></span>
								<?=(!empty($stat['sup'])?'<sup><span class="'.$stat['class'].'sup">'.$stat['sup'].'</span></sup>':'')?>
								<?=(!empty($stat['tooltip'])?'<span class="float-right"><i class="fas fa-caret-down"></i></span>':'')?>
							</span>
						</div>
					<? } ?>
				</div>
			<?php } ?>
		</header>


		<?php if($user->isLoggedIn()) { ?>
			<div id="mySidenav" class="sidenav">
				<? foreach($menu as $i => $row) {
					$row['url'] = (!isset($row['url']) ? $row['links'][0]['url'] : $row['url']);
					$firstLink = $row['links'][0];
					?>
					<div class="row menuRow <?=($i==0?'hide-menu-icon':'')?>">
						<div class="col-md-2 col-xs-2 buttonItem">
							<a href="<?=Request::siteUrl().'/'.$row['url']?>">
								<div data-toggle="tooltip" data-html="true" data-placement="right" title="<?=$firstLink['title']?> <?
								if(!empty($firstLink['badge'])) {
									echo '<span class=\'badge badge-secondary\'>'.str_replace('"','\'', $firstLink['badge']).'</span>';
								} ?>">
									<button class="menu-item" type="button"><i class="<?=$row['icon']?>"></i></button>
								</div>
							</a>
						</div>
						<div class="col-md-10 col-xs-10 menuText">
							<? foreach($row['links'] as $link) { ?>
								<a href="<?=Request::siteUrl().'/'.$link['url']?>" class="marketMenu">
									<?=$link['title']?>
									<? if(!empty($link['badge'])) {
										if(!empty($link['tooltip'])) {
											echo '<span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="'.$link['tooltip'].'">'.$link['badge'].'</span>';
										} else {
											echo '<span class="badge badge-secondary">'.$link['badge'].'</span>'.PHP_EOL;
										}
									} ?>
								</a>
							<? } ?>
						</div>
					</div>
				<? } ?>

				<div id="page-sub-header">
					<div class="row statheader">
						<? foreach($stats as $i => $stat) {
							$stat['title'] = (!empty($stat['short']) ? $stat['short'] : $stat['title']);
							$stat['class'] = (!empty($stat['class']) ? $stat['class'] : strtolower($stat['title']));
							?>
							<div class="col-6 statitem">
								<span class="stattext"<?=(!empty($stat['tooltip'])?' data-toggle="tooltip" data-placement="bottom" data-html="true" title="'.$stat['tooltip'].'"':'')?>>
									<strong><?=$stat['title']?>:</strong> <span class="<?=$stat['class']?>header"><?=$stat['value']?></span>
									<?=(!empty($stat['sup'])?'<sup><span class="'.$stat['class'].'sup">'.$stat['sup'].'</span></sup>':'')?>
									<?=(!empty($stat['tooltip'])?'<span class="float-right"><i class="fas fa-caret-down"></i></span>':'')?>
								</span>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
		<?php } ?>

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
				<div class="titleBackWrapper<?=(!!$province && $province->getSatellites('stealths')['active']?' stealthsatactive':'')?>">
					<div class="pageTitle <?=($provinceDied ? ' deadback':'')?>">
						<?=($provinceDied ? t('You died') : $pageTitle) ?>
					</div>
				</div>
				<div class="row contentRow">