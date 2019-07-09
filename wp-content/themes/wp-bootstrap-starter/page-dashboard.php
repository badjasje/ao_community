<?php
/**
 * Template Name: Dashboard
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

echo '<div class="row pageRow">';

	if($province->getNetworth() <= 3499) {
		?>
		<div class="blockHeader warning">WARNING: Your networth is below $3500</div>
		<div class="blockHeader spaceNotice">You will not receive resources when below the $3500 treshold.</div>
		<div class="pageSpacer"></div>
		<?
	}

	require_once('pages/dashboard/devtest.php');

	if(Round::isLive()) {
		require_once('pages/dashboard/pick-startingbonus.php');
		require_once('pages/dashboard/bonus-receive.php');
		require_once('pages/dashboard/toplists.php');
	}

	if(!!$province->getClan()) {
		require_once('pages/dashboard/clan-message.php');
		echo '<div class="pageSpacer"></div>';
	}

	require_once('pages/dashboard/status-column.php');
	echo '<div class="pageSpacer"></div>';

	require_once('pages/dashboard/latest-block.php');
	echo '<div class="pageSpacer"></div>';

	require_once('pages/dashboard/round-date.php');

	if(Round::isLive()) {
		echo '<div class="pageSpacer"></div>';
		require_once('pages/dashboard/medalpositions.php');
	}

echo '</div>';

get_footer();
