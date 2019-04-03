<?php
 /*
 * Template Name: Opt In or Out of Clan Wars
*/
wp_redirect(home_url('/'));
exit;
// Disabled!
/*
get_header();
global $userData;
global $userId;

$user_ID = $userId;
$clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_ID);

$clanleader = $clanData['clan_leader'][0];
$clanmembers = maybe_unserialize(get_post_meta( $clan_ID, 'clan_members', true ));
$clanmembers_count = count($clanmembers);
$ct_1 = get_post_meta($clan_ID,'ct_1',true);
$ct_2 = get_post_meta($clan_ID,'ct_2',true);
$ct_3 = get_post_meta($clan_ID,'ct_3',true);
$ct_4 = get_post_meta($clan_ID,'ct_4',true);
$settings = array( 'media_buttons' => false );
$changecount = get_post_meta($clan_ID, 'clan_name_change', true);
$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);

$autojoin = get_post_meta($clan_ID, 'autojoin_allowed', true);
$autojoinDesc = get_post_meta($clan_ID, 'autojoin_description', true);
$playstyle = get_post_meta($clan_ID, 'autojoin_playstyle', true);

$optinout_status = get_post_meta($clan_ID, 'optout_status', true);
$optinout_reset = get_post_meta($clan_ID, 'optout_reset',true);

$clan = get_post($clan_ID);
$timestamp = current_time('timestamp');

$exclusiondate = strtotime(get_field('starting_date','options'));
?>
<div class="row pageRow fw-row">

<div class="blockHeader">
	Opt In or Out of Clan Wars!
</div>

<div class="blockHeader spaceNotice">
        Current Status: <?php if ($optinout_status != 1) {?>Opted<strong> <font color="green">IN!</font></strong> You will be a part of the competetive points list this round<?php ;} else { ?>Opted <strong><font color="red"> OUT!</font></strong> You cannot be declared on, or declare wars this round<?php } ?>
</div>

<div class="col-md-12 clanMessage">

        It is now possible to opt out of clan wars.<br/><br/>If your clan has LESS than 4 members, you can opt out of clan wars once per round within the first 7 days of the round, or the first 7 days of formulation of your clan.<br/><br/>
        ONCE YOU HAVE CHANGED THIS SETTING, IT CANNOT BE CHANGED AGAIN IN A SINGLE ROUND<br/><br/>
        Opting out achieves the following:
        <ul><li>You cannot be declared upon</li><li>You cannot declare on others</li><li>Outgoing attacks from your clan give you 50% less resources at all times</li></ul>
</div>

<?php if ($optinout_reset == 1):?>

	<div class="blockHeader">You have already changed your opt in/out settings this round</div>

	<?php elseif ($timestamp < $exclusiondate):?>

		<?php if ($optinout_reset == 1):?>

		<?php else:?>

			<div class="blockHeader">The round is more than a week old, you can no longer opt out of clan wars this round</div>
		<?php endif;?>
	<?php else:?>

	<form id="optinout-clan" class="fw-row"method="post">
	<div class="row no-gutters fw-row">

		<div class="col-md-6 no-gutters">
			<input style="display:none;" type="radio" value="optedin" name="optin_status" id="optedin" required>
			<label style="background-color:rgba(66, 92, 107,1)" class="mainSubmit hoverEffect attackSelect" for="optedin">
				<i class="fas fa-check"></i> Opt in
			</label>
		</div>
		<div class="col-md-6 no-gutters">
			<input style="display:none;" type="radio" value="optedout" name="optin_status" id="optedout" required>
			<label style="background-color:rgba(66, 92, 107,0.95)"class="mainSubmit hoverEffect attackSelect" for="optedout">
				<i class="fas fa-ban"></i> Opt out
			</label>
		</div>
	</div>

	<?php

if ($user_ID == $clanleader and ($optinout_reset != 1)) {
?><input class="mainSubmit" type="submit" value="Change Opt In or Out Settings" name="submit">
<?php }

elseif ($clanmembers_count > 3) { ?>
 <button class="mainSubmit" disabled value="TooManyClanmember" name="notsubmit">You may only opt out of wars if you have 4 or less members</button>
<?php
}
else {
?>  <button class="mainSubmit" disabled value="Only the Clan Leader may opt in or out of clan wars" name="notsubmit">Either you are not the clan leader, or your clan has already changed opt in/out this round</button>
<?php }

//MEGA DEBUG OVERRIDE THE STUFF
?>
	</form>

                <?php endif; ?>

<div class="pageSpacer"></div>
<div class="col-md-12 clanMessage">
Why would you want to opt out? Easy. If you opt out: top points clans cannot declare your clan. You can build your province in a sustainable way without being killed.<br/>
        If you aren't sure whether or not to go for this - we recommend you first of all ask for some advice in the<strong><u> <a style="color:#fff" href="https://discord.gg/ttdng4n" target="_blank">Discord channel!</a></u></strong> Remember, once you opt out, you cannot change it this round!
		</div>
  	</div>
</div>
<?

?>

<?php if($user_ID == $clanleader):?>

<?php if(empty($changecount) || $changecount != 1):?>
<div class="pageSpacer"></div>
<?php endif;?><?php endif;?>

<script>

(function($) {

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#optinout-clan").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();

    if (request) { request.abort();}

    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");

    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/optinoutclan.php",
        type: "post",
        data: serializedData,
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console

         var array = JSON.parse(response);
						console.log(array);
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					allow_dismiss: true,
					newest_on_top: true,
						});


location.reload();
}

);	}
);
  })(jQuery);
</script>

</div> <!-- end pageRow -->
<?php
get_footer();
*/