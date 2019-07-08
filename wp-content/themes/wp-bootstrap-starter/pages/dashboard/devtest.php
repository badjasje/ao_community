<?
if(Round::isTest()) {
    ?>
	<div class="blockHeader">Welcome to test.assault.online</div>
	<div class="pageSpacer statCol-4">
		<strong>WARNING: this is NOT a sandbox!</strong>
		Some people are setting up testcases to test the game-engine, so please do not attack out of the blue.<br>
		Ask on <a href="http://bit.ly/2US8Dh0" target="_blank">discord</a> for a volunteer, or attack <a href="/clan/target-practice/">us</a>
	</div>
    <?
}
if(Round::isDev()) {
	echo '<div class="blockHeader">Welcome to dev.assault.online</div>';
}
if(Round::isSandbox()) {
	echo '<div class="blockHeader">Welcome to assault.online - sandbox <i class="fa fa-smile"></i></div><div class="pageSpacer statCol-4">
		<strong>Surprise!</strong> Let`s get completely crazy in between real rounds and have a sandbox mini round.<br>
		1 week, no medals, no awards.
	</div>';
}
if(Round::isTest() || Round::isDev() || Round::isSandbox()) {
    ?>
	<div class="blockHeader spaceNotice">
		To receive turns/money/morale/research/orders, hit the button below!<br>
		If you are dead or under protection, hitting this button will revive you as well.
	</div>
	<button class="mainSubmit receiveFunds">Receive all</button>
	<div class="pageSpacer"></div>
    <?
}
