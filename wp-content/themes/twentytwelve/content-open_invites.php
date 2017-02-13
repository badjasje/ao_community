<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 $user_ID = get_current_user_ID();
$clan_id_user = get_user_meta($user_ID,'clan_id_user');
$open_invites = get_post_meta($clan_id_user[0],'open_invites');
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php if ( ! is_page_template( 'page-templates/front-page.php' ) ) : ?>
			<?php the_post_thumbnail(); ?>
			<?php endif; ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
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
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
