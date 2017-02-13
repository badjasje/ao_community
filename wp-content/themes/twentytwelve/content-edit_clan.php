<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 $user_ID = get_current_user_id();
 $clan_ID = get_user_meta($user_ID, 'clan_id_user')[0];
 $clanleader = get_post_meta($clan_ID,'clan_leader')[0];
 $clanmembers = get_post_meta($clan_ID,'clan_members');
 $ct_1 = get_post_meta($clan_ID,'ct_1');
 $ct_2 = get_post_meta($clan_ID,'ct_2');
 $ct_3 = get_post_meta($clan_ID,'ct_3');
 $ct_4 = get_post_meta($clan_ID,'ct_4');
 
 
 $clan = get_post($clan_ID);?>

	<article>
		<div class="entry-content">
			<center><h1>Edit clan</h1></center>
			<?php if(!empty($_SESSION['status'])):?>
				<div class="marketnotice"><?php echo $_SESSION['status'];?></div>
			<?php endif;?>
			
			<?php if($clanleader == $user_ID):?>
			
			<div class="container2">
				<table class="responsive-table">
				
			<tr>
				<td>
					<?php if(!empty(get_post_meta($clan_ID, 'clan_image', true))):?>
<center><img style="width:100%;" src="<?php echo get_post_meta($clan_ID, 'clan_image', true); ?>"></center><br/><?php endif;?>
				</td>
			</tr>
			<tr>
				<td>
			<form action="/set_clan_image.php" method="post" enctype="multipart/form-data">
    Select image to upload. Recommended height:450px.
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload Image" name="submit">
</form></td>
			</tr>
				</table>
				</div>
			
			<div class="container2">
				<table class="responsive-table">
				<tr>
					<td>
						<center><strong>Public message</strong></center>
					</td>
					<td>
						<center><strong>Clan message</strong></center>
					</td>
				</tr>
				<tr>
					<td>
						<form class="form" action="<?php echo home_url() ?>/public_message.php" name="" id="market" method="post">
			<input type="hidden" name="clan" value="<?php echo $clan_ID;?>">
			<textarea rows="5" class="small_input" type="text" name="publicmessage" id="clanmessager"><?php echo $clan->post_content;?></textarea><br/><br/><center><input type="submit" value="Edit public message"></center></form>
					</td>
					<td>
						<form class="form" action="<?php echo home_url() ?>/clan_message.php" name="" id="market" method="post">
			
			<input type="hidden" name="clan" value="<?php echo $clan_ID;?>">
			<textarea rows="5" class="small_input" type="text" name="clanmessage" id="clanmessager"><?php if(!empty(get_post_meta($clan_ID, 'clan_message')[0])){echo get_post_meta($clan_ID, 'clan_message')[0];}?></textarea><br/><br/><center><input type="submit" value="Edit clan message"></center></form>
					</td>
				</tr>
			</table>
			</div>
			
				<form class="form" action="<?php echo home_url() ?>/clan_trustee.php" name="" id="clan_trustee" method="post">
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th colspan="4" scope="col">Clan Trustees</th>
  					</tr>
  					</thead>
  					<tbody>
	  				<tr>
						<td data-title="Clan trustee 1">
							<select name="ct_1">
							<?php if($ct_1[0] == 0):?>
							<option value="0" selected="selected">
							-- Pick a clan trustee 1 --
							</option>
							<?php else:?>
							<option value="0">
							-- Pick a clan trustee 1 --
							</option>
							<option value="<?php echo $ct_1[0];?>" selected="selected">
							<?php 
								$member_data = get_userdata($ct_1[0]);
								echo $member_data->display_name;?>
							</option>
							<?php endif;?>
					
						<?php foreach ($clanmembers[0] as $key => $member) {
							if($member != $clanleader && $member != $ct_1[0] && $member != $ct_2[0] && $member != $ct_3[0] && $member != $ct_4[0]){
								$member_data = get_userdata($member);
						
						
						?>	
							
							<option name="ct_1" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
							<?php }}?>
							</select>
							
							</td>
						<td data-title="Clan trustee 2">
							<select name="ct_2">
							<?php if($ct_2[0] == 0):?>
							<option value="0" selected="selected">
							-- Pick a clan trustee 2 --
							</option>
							<?php else:?>
							<option value="0">
							-- Pick a clan trustee 2 --
							</option>
							<option value="<?php echo $ct_2[0];?>" selected="selected">
							<?php 
								$member_data = get_userdata($ct_2[0]);
								echo $member_data->display_name;?>
							</option>
							<?php endif;?>
					
						<?php foreach ($clanmembers[0] as $key => $member) {
							if($member != $clanleader && $member != $ct_1[0] && $member != $ct_2[0] && $member != $ct_3[0] && $member != $ct_4[0]){
								$member_data = get_userdata($member);
						
						
						?>	
							
							<option name="ct_1" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
							<?php }}?>
							</select>
							
							
							
						</td>
						<td data-title="Clan trustee 3">
							
							<select name="ct_3">
							<?php if($ct_3[0] == 0):?>
							<option value="0" selected="selected">
							-- Pick a clan trustee 3 --
							</option>
							<?php else:?>
							<option value="0">
							-- Pick a clan trustee 3 --
							</option>
							<option value="<?php echo $ct_3[0];?>" selected="selected">
							<?php 
								$member_data = get_userdata($ct_3[0]);
								echo $member_data->display_name;?>
							</option>
							<?php endif;?>
					
						<?php foreach ($clanmembers[0] as $key => $member) {
							if($member != $clanleader && $member != $ct_1[0] && $member != $ct_2[0] && $member != $ct_3[0] && $member != $ct_4[0]){
								$member_data = get_userdata($member);
						
						
						?>	
							
							<option name="ct_3" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
							<?php }}?>
							</select>
		
							
							
						</td>
						<td data-title="Clan trustee 4">
							
							
							<select name="ct_4">
							<?php if($ct_4[0] == 0):?>
							<option value="0" selected="selected">
							-- Pick a clan trustee 4 --
							</option>
							<?php else:?>
							<option value="0">
							-- Pick a clan trustee 4 --
							</option>
							<option value="<?php echo $ct_4[0];?>" selected="selected">
							<?php 
								$member_data = get_userdata($ct_4[0]);
								echo $member_data->display_name;?>
							</option>
							<?php endif;?>
					
						<?php foreach ($clanmembers[0] as $key => $member) {
							if($member != $clanleader && $member != $ct_1[0] && $member != $ct_2[0] && $member != $ct_3[0] && $member != $ct_4[0]){
								$member_data = get_userdata($member);
						
						
						?>	
							
							<option name="ct_3" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
							<?php }}?>
							</select>
							
						</td>
  					</tr>
  					</tbody>
				</table>
				<center><input type="submit" value="Save" class=""></center>
				</form>
				</div>
				
				
			
			
			<?php endif;?>
		
		</div><!-- .entry-content -->
	</article><!-- #post -->
