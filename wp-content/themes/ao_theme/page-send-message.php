<?php
/**
 * Template Name: Send message
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$receiver_ID = Request::get('id');
$receiver = Province::make($receiver_ID);
if($receiver->get('id') != false) {
	?>
	<div class="row pageRow">
		<div class="blockHeader d-flex p-0">
			<div class="d-none d-md-block">
				<?=$receiver->getAvatar('allUsersAvatar')?>
				<span class="mobileUserName"><?=$receiver->getLink(true)?></span>
			</div>
			<div class="px-3 py-2">Sending message to <?=$receiver->getLink(true)?></div>
		</div>
		<form class="form fw-row" id="messageForm" >
			<div class="row no-gutters">
				<div class="col-md-2">
					<div class="collabel">Subject</div>
				</div>
				<div class="col-md-10">
					<input class="inputtxt" type="text" id="title" required placeholder="Enter the subject of your message" name="title">
				</div>
			</div>
			<div class="row no-gutters">
				<div class="col-md-2">
					<div class="collabel h-100 w-100 statCol-2">Message</div>
				</div>
				<div class="col-md-10">
					<textarea class="w-100" id="message" required rows="10" name="message" placeholder="Your message..."></textarea>
				</div>
			</div>

			<input type="hidden" name="receiver" value="<?=$receiver_ID?>" >
			<input type="hidden" name="main_message" value="first">
			<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
			<input class="mainSubmit" type="submit" value="Send">
		</form>
	</div>
	<?php
}
get_footer();