<?php
 /*
 * Template Name: Open Invites
*/
get_header();
global $userData;
global $userId;
$clanId = $userData['clan_id_user'][0];

$openInvites = maybe_unserialize(get_post_meta($clanId,'open_invites',true));
$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94";

?>

<div class="row pageRow">

<div class="row headerRow fw-row row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock">Invited</div>
	<div class="col-md-3 celBlock">Networth</div>
	<div class="col-md-2 celBlock">Key</div>
	<div class="col-md-2 celBlock"></div>
</div>
<?php
if (!empty($openInvites)) {
	$foundInvites = false;
	$invites = $openInvites;

	if(is_array($invites)) {

		foreach ($invites as $invite) {
			if (!is_array($invite)) {
				continue;
			}

			$foundInvites = true;
			$memberData = get_userdata($invite['user']);
			$user_ID = $invite['user'];
			if ($invite['clan'] == $clanId) {
                                    ?>
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($user_ID,'allUsersAvatar');?><span class="mobileUserName" style="top:-4px;"><?php echo get_user_name($user_ID);?></span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol">

		<?php echo get_user_name($user_ID);?>

	</div>
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Networth</span>
		<span class="columnDataRight store-pop-span2">

			<?php echo networth_range($user_ID);?>

		</span>

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Key</span>
		<span class="columnDataRight">
		<?php echo $invite['invite'];?>
		</span>
	</div>

	<div class="col-md-2 celBlock u-no-padding">
		<a href="/cancel_invite.php/?invite=<?php echo $invite['invite']; ?>&clan=<?php echo $clanId; ?>">
		<button class="cancelButton hoverEffect" onclick="return confirm('Are you sure you want to cancel this invite?')" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);" type="submit">Cancel</button>
		</a>

	</div>



</div> <!-- //Close profile row -->

                                    <?php
                                }
                            }
                        }

                        if($foundInvites == false) {
                            ?>
                            <div class="col-md-12">You have no open clan invites.</div>

                            <?php
                        }
                    }
                    ?>



</div> <!-- end .pageRow -->
<?php
get_footer();