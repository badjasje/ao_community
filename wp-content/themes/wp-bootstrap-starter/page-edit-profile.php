<?php
/**
 * Template Name: Edit profile
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$reset_status = $province->get('reset_status');
if(Round::isDev() || Round::isTest()) $reset_status = false; //You may reset more than once

?>
<div class="row pageRow">
    <div class="blockHeader spaceNotice">
        You can only change your playername once per round, and it has to be unique among active players. Changing your playername to "minion" is free of limitations.
        You can only reset your province once per round, outside of wars. You will reset back to the starting state.
    </div>
    <div class="blockHeader">
        <?=$province->getName(true)?>
    </div>
    <form action="#" class="fw-row statCol-2" id="editprofile">
        <div class="mx-4 py-4">



            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label">Username</label>
                <div class="col-md-8">
                    <input value="<?=$user->getUsername()?>" autocomplete="off" type="text" class="unitInput username" placeholder="Name to login" name="username" id="username">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label">Password</label>
                <div class="col-md-8">
                    <input value="" autocomplete="new-password" type="password" class="unitInput password" placeholder="Fill to change" name="password" id="password">
                </div>
            </div>

            <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label">Phone number</label>
                <div class="col-md-8">
                    <input maxlength="25" value="<?=$user->get('phone_number')?>" type="numbers" class="unitInput" placeholder="Phone number" name="phone" id="phone">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label">Email address</label>
                <div class="col-md-8">
                    <input value="<?=$user->get('email')?>" type="text" class="unitInput" placeholder="Email address" name="email" id="email">
                </div>
            </div>

            <hr size="1">

            <div class="form-group row">
                <label for="playername" class="col-md-4 col-form-label">Player name</label>
                <div class="col-md-8">
                    <input value="<?=$province->get('display_name')?>" type="text" class="unitInput playername" placeholder="Name to use in game" name="playername" id="playername">
                </div>
            </div>

            <div class="form-group row">
                <label for="user_avatar_dz" class="col-md-4 col-form-label">New profile picture</label>
                <div class="col-md-8">
                    <div id="user_avatar_dz" data-url="<?=get_stylesheet_directory_uri()?>/dropzoneUpload.php">Click to upload new avatar</div>
                    <input type="hidden" name="newuserimage" class="newuserimage" id="newuserimage" value=""/>
                </div>
            </div>

        </div>
        <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
        <input type="submit" class="mainSubmit" value="update profile">
    </form>

    <div class="pageSpacer"></div>
    <? if(!Round::isDev() && !Round::isTest()) { ?>
    <div class="pageSpacer"><em>You can only reset once per round</em></div>
    <? } ?>

    <? if(empty($reset_status)) { ?>
        <form class="fw-row" id="resetprofile">
            <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
            <button type="submit" class="mainSubmit redBg"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> RESET ACCOUNT</button>
        </form>
    <? } ?>

</div>

<?php
get_footer();