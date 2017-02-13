<?php
 /*
 * Template Name: Open invites
 */
$user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID,'clan_id_user');
$open_invites = get_post_meta($clan_id_user[0],'open_invites');
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <table>
				<tr>
					<td>Invite sent to
					</td>
					<td>Invite key
					</td>
					<td>
					</td>
				</tr>
			<?php 
				if(!empty($open_invites)){
				foreach (array_shift($open_invites) as $invite) {
				$member_data = get_userdata($invite['user']);
				if($invite['clan'] == $clan_id_user[0]){
			?>
				<tr>
					<td><a href="/users/profile/?id=<?php echo $invite['user'];?>"><?php echo $member_data->user_nicename.' (#'.$invite['user'].')';?></a>
					</td>
					<td><?php echo $invite['invite'];?>
					</td>
					<td><a href="/cancel_invite.php/?invite=<?php echo $invite['invite'];?>&clan=<?php echo $clan_id_user[0];?>">Cancel invite</a>
					</td>
				</tr>
			<?php }}}?>
			</table>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>