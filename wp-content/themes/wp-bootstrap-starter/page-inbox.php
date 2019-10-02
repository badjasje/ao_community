<?php
/**
 * Template Name: Conversations
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();
$convos = $province->getInbox();
?>
<div class="row pageRow">
	<div class="row headerRow blockHeader d-md-flex fw-row row-no-padding">
		<div class="col-md-4 celBlock"></div>
		<div class="col-md-5 celBlock">Topic</div>
		<div class="col-md-3 celBlock text-right">Last updated</div>
	</div>

	<? foreach($convos as $count => $convo) {
		$with = Province::make($convo->with($user->get('id')));
		?>
		<div class="row <?=($count%2==0?' statCol-4':' statCol-3')?> fw-row d-flex p-0 row-no-padding">
			<div class="col-md-4 p-0 d-none d-md-flex">
				<?=$with->getAvatar('allUsersAvatar')?>
				<div class="celBlock"><?=$with->getLink(true)?></div>
			</div>
			<div class="blockHeader d-md-none"><?=$with->getLink(true)?></div>
			<div class="col-8 col-md-5 celBlock">
				<?=$convo->getLink(true)?>
				<?=($convo->hasNewMessage($user->get('id')) ? ' <span class="red-text">New messages</span>' : '')?>
			</div>
			<div class="col-4 col-md-3 celBlock text-right"><?=$convo->getLastUpdate(true)?></div>
		</div>
	<? } ?>
</div>
<?php
get_footer();