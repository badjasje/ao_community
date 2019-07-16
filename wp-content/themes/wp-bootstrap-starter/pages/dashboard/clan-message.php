<?
$clan = $province->getClan();
$settings = array('media_buttons' => false, 'editor_height' => 300, 'textarea_name' => 'new_message');
?>
<div class="blockHeader">Clan message <?=$clan->getName()?></div>

<div class="col-md-10 clanMessage">
	<div id="savedmsg"><?=$clan->getMessage(true)?></div>

	<? if($clan->canEditMessage()) { ?>
	<div class="message-editor">
		<form class="form" name="new_message" id="edit_clan_message" method="post">
			<? wp_editor($clan->getMessage(true), 'new_message', $settings); ?>
			<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
			<input class="mainSubmit" type="submit" value="Submit">
		</form>
		<div id="dismissEditClanMessage" class="mainSubmit">Dismiss</div>
	</div>
	<? } ?>

</div>

<div class="col-md-2 clanMessageRow">
	<a href="<?=$clan->getLink()?>">
		<div class="col-md-12 clanMessageButton hoverEffect"><i class="fa fa-info-circle"></i> View clan</div>
	</a>
	<a href="<?=Request::link('clanMemberInformation')?>">
		<div class="col-md-12 clanMessageButton secondButton hoverEffect"><i class="fa fa-users"></i> Members</div>
	</a>
	<? if(Round::isPaused()) { ?>
		<div class="col-md-12 clanMessageButton disabled"><i class="fa fa-fire"></i> Clan wars</div>
		<div class="col-md-12 clanMessageButton fourthbutton disabled"><i class="fas fa-dollar-sign"></i> Send aid</div>
	<? } else { ?>
		<a href="<?=Request::link('clanWars')?>">
			<div class="col-md-12 clanMessageButton hoverEffect"><i class="fa fa-fire"></i> Clan wars</div>
		</a>
		<a href="<?=Request::link('clanSendAid')?>">
			<div class="col-md-12 clanMessageButton fourthbutton hoverEffect"><i class="fas fa-dollar-sign"></i> Send aid</div>
		</a>
	<? } ?>
</div>

<? if($clan->canEditMessage()) { ?>
	<div class="mainSubmit" id="editClanMessage"><i class="fas fa-pencil-alt"></i> Edit clan message</div>
<? } ?>
