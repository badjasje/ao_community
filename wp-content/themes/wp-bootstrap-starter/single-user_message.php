<?php
/**
 * Template Name: Read message
 */
get_header();

$user = CurrentUser::make();
$conv = Conversation::make($post->ID);
if(empty($conv->get('id'))) die('nope1');

if(!in_array($user->get('id'), array($conv->get('receiver_id'), $conv->get('sender_id'), 1))) {
    wp_redirect(get_permalink(3656)); exit;
}

$messages = $conv->getMessages();
if(count($messages)) {
    $lastMsg = end($messages);
    if($lastMsg->getSender() != $user->get('id')) $conv->update('general_status', 'Read');
}
$invite_hash = $conv->getInviteKey();
?>
<div class="row pageRow">
    <?php
    if(empty($invite_hash)) {

        foreach($messages as $message) {
            $sender = Province::make($message->getSender());
            ?>
            <div class="blockHeader d-flex p-0">
                <div class="d-none d-md-block">
                    <?=$sender->getAvatar('allUsersAvatar')?>
                    <span class="mobileUserName"><?=$sender->getLink(true)?></span>
                </div>
                <div class="col-7 px-3 py-2"><?=$sender->getLink(true)?></div>
                <div class="col-5 col-md-4 px-3 py-2 text-right"><?=$message->getDate(true)?></div>
            </div>
            <div class="blockHeader spaceNotice"><?=$message->getText(true)?></div>
            <div class="pageSpacer"></div>
            <?php
        }
        ?>
        <div id="lastrow"></div>
        <form class="form fw-row" id="message" method="post">
            <input type="hidden" name="receiver" value="<?=$conv->with($user->get('id'))?>">
            <input type="hidden" name="main_message" value="<?=$conv->get('id')?>">
            <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
            ​<textarea id="message" required rows="10" name="message" class="fw-row" placeholder="Your message..."></textarea>
            <input class="mainSubmit hoverEffect" type="submit" value="Send">
        </form>
        <?php
    } else {
        $clan_id = $conv->getClanId();
        if($user->get('id') == $conv->get('author_id')) {
            $receiver = Province::make($conv->get('receiver_id'));
            ?>
            <div class="blockHeader">You sent this invite to <?=$receiver->getLink(true)?></div>
            <?php
        }
        else {
            if($conv->get('invite_status') == 'accept') { ?>
                <div class="blockHeader">You have used the clan invite</div>
            <?php } else { ?>
                <div class="blockHeader">You've been invited to join <?=Clan::make($clan_id)->getName()?> (# <?=$clan_id?>).</div>
                <div class="fw-row px-3 py-2 statCol-1">If you wish to accept this invite, hit the accept button.</div>
                <? if($user->get('id') != $conv->get('author_id')) { ?>
                <form class="form fw-row" id="claninvite" method="post">
                    <div class="row fw-row no-gutters inviteButtonRow">
                        <input type="submit" class="col-md-6 mainSubmit secondButton" value="Accept">
                        <input type="submit" class="col-md-6 mainSubmit fourthButton" value="Decline">
                    </div>
                    <input type="hidden" name="target" value="" class="target">
                    <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                    <input type="hidden" name="hash" value="<?=$invite_hash?>">
                    <input type="hidden" name="clan" value="<?=$clan_id?>">
                </form>
                <? }
            }
        }
    } ?>

</div>
<?php
get_footer();