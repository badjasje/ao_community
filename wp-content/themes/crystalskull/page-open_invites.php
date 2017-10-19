<?php
 /*
 * Template Name: Open invites
 */
$userID = get_current_user_ID();
$clanId = get_user_meta($userID,'clan_id_user');
$openInvites = get_post_meta($clanId[0],'open_invites');
get_header(); ?>
<div class="page normal-page">
    <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <table>
				    <tr>
					    <td>Invite sent to</td>
					    <td>Invite key</td>
					    <td></td>
				</tr>
                    <?php
                    if (!empty($openInvites)) {
                        $foundInvites = false;
                        $invites = array_shift($openInvites);

                        if(is_array($invites)) {
                            foreach ($invites as $invite) {
                                if (!is_array($invite)) {
                                    continue;
                                }
                                $foundInvites = true;
                                $memberData = get_userdata($invite['user']);
                                if ($invite['clan'] == $clanId[0]) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="/users/profile/?id=<?php echo $invite['user']; ?>"><?php echo $memberData->user_nicename . ' (#' . $invite['user'] . ')'; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $invite['invite']; ?>
                                        </td>
                                        <td>
                                            <a href="/cancel_invite.php/?invite=<?php echo $invite['invite']; ?>&clan=<?php echo $clanId[0]; ?>">Cancel
                                                invite</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        }

                        if($foundInvites == false) {
                            ?>
                            <tr>
                                <td colspan="3">You have no open clan invites.</td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>