<?php
 /*
 * Template Name: Edit clan
 */
$user_ID = get_current_user_id();
 $clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
 $clanleader = get_post_meta($clan_ID,'clan_leader',true);
 $clanmembers = get_post_meta($clan_ID,'clan_members');
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

$casual = '';
$points = '';
$networth = '';
$other = '';
if($playstyle == 'Casual'){
	$casual = 'selected="selected"';
}
if($playstyle == 'Points'){
	$points = 'selected="selected"';
}
if($playstyle == 'Networth'){
	$networth = 'selected="selected"';
}
if($playstyle == 'Other'){
	$other = 'selected="selected"';
}


$autojoinYes = '';
$autojoinNo = '';

if($autojoin == 'yes'){
	$autojoinYes = 'selected="selected"';
}
if($autojoin == 'no'){
	$autojoinNo = 'selected="selected"';
}


$clan = get_post($clan_ID);
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>

<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
<?php if(in_array($user_ID, $allowed)):?>		
			
<div class="row edit_clan_first">
	<div class="col-md-6 edit_clan_box">
		<h2>Set clan image</h2>		
		<?php if(!empty(get_post_meta($clan_ID, 'clan_image', true))):?>
				<center><img style="width:200px;" src="<?php echo get_post_meta($clan_ID, 'clan_image', true); ?>"></center><br/><?php endif;?>
				<form action="/set_clan_image.php" method="post" enctype="multipart/form-data">
				Select image to upload.<br/>Recommended height:450px.
				<input type="file" name="file" id="file">
				<input type="submit" value="Upload Image" name="submit">
				</form>
	</div>
	<div class="col-md-6 edit_clan_box">	
		<h2>Edit public message</h2>
		<form class="form" action="<?php echo home_url() ?>/public_message.php" name="" id="market" method="post">
		<input type="hidden" name="clan" value="<?php echo $clan_ID;?>">
		<textarea rows="5" class="message_box" type="text" name="publicmessage" id="clanmessager"><?php echo $clan->post_content;?></textarea><center>
		<input type="submit" value="Edit public message"></form>
	</div>
</div>







<div class="row edit_clan_first">
	<div class="col-md-12 edit_clan_box">
		<h2>Edit clan message</h2>
		<form class="form" action="<?php echo home_url() ?>/clan_message.php" name="" id="market" method="post">
		<input type="hidden" name="clan" value="<?php echo $clan_ID;?>">
		<?php wp_editor(get_post_meta($clan_ID, 'clan_message')[0],'clanmessage',$settings);?>
	
		<input type="submit" value="Edit clan message"></form>
	</div>
</div>









<?php endif;?>			



<?php if($user_ID == $clanleader):?>	

<?php if(empty($changecount) || $changecount != 1):?>
<br/><br/>
	<center>
			<div class="welcome_text"><p>You can change your clan name once every round.</p><hr/>
<h4>Your current clan name is</h4>
				<h2><?php echo get_the_title($clan_ID);?></h2>
				
				<hr/>
				<h4>New clan name</h4>
				<p>
					<form class="form" action="<?php echo home_url() ?>/change_clan_name.php?id=<?php echo $clan_ID;?>" name="" id="clanname" method="post">
						<input required class="new_user_name" type="text" id="display_name" name="clanname"><br/><br/>
						<input type="submit" value="CHANGE CLAN NAME">
					</form>	
				</p>
</div><br/><br/>
<?php endif;?>	


<div class="row edit_clan_first">
	<div class="col-md-8 edit_clan_box">
		<h2 class="leftH2">Set clan trustees</h2>
		<form class="form" action="<?php echo home_url() ?>/clan_trustee.php" name="" id="clan_trustee" method="post">
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
					<?php if($ct_1 == 0):?>
					<option value="0" selected="selected">
					-- Clan trustee 1 --
					</option>
					<?php else:?>
					<option value="0">
					-- Clan trustee 1 --
					</option>
					<option value="<?php echo $ct_1;?>" selected="selected">
					<?php 
						$member_data = get_userdata($ct_1);
						echo $member_data->display_name;?>
					</option>
					<?php endif;?>
			
				<?php foreach ($clanmembers[0] as $key => $member) {
					if($member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){
						$member_data = get_userdata($member);
				
				
				?>	
					
					<option name="ct_1" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
					<?php }}?>
					</select>
					
					</td>
				<td data-title="Clan trustee 2">
					<select name="ct_2">
					<?php if($ct_2 == 0):?>
					<option value="0" selected="selected">
					-- Clan trustee 2 --
					</option>
					<?php else:?>
					<option value="0">
					-- Clan trustee 2 --
					</option>
					<option value="<?php echo $ct_2;?>" selected="selected">
					<?php 
						$member_data = get_userdata($ct_2);
						echo $member_data->display_name;?>
					</option>
					<?php endif;?>
			
				<?php foreach ($clanmembers[0] as $key => $member) {
					if($member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){
						$member_data = get_userdata($member);
				
				
				?>	
					
					<option name="ct_1" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
					<?php }}?>
					</select>
					
					
					
				</td>
				<td data-title="Clan trustee 3">
					
					<select name="ct_3">
					<?php if($ct_3 == 0):?>
					<option value="0" selected="selected">
					-- Clan trustee 3 --
					</option>
					<?php else:?>
					<option value="0">
					-- Clan trustee 3 --
					</option>
					<option value="<?php echo $ct_3;?>" selected="selected">
					<?php 
						$member_data = get_userdata($ct_3);
						echo $member_data->display_name;?>
					</option>
					<?php endif;?>
			
				<?php foreach ($clanmembers[0] as $key => $member) {
					if($member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){
						$member_data = get_userdata($member);
				
				
				?>	
					
					<option name="ct_3" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
					<?php }}?>
					</select>

					
					
				</td>
				<td data-title="Clan trustee 4">
					
					
					<select name="ct_4">
					<?php if($ct_4 == 0):?>
					<option value="0" selected="selected">
					-- Clan trustee 4 --
					</option>
					<?php else:?>
					<option value="0">
					-- Clan trustee 4 --
					</option>
					<option value="<?php echo $ct_4;?>" selected="selected">
					<?php 
						$member_data = get_userdata($ct_4);
						echo $member_data->display_name;?>
					</option>
					<?php endif;?>
			
				<?php foreach ($clanmembers[0] as $key => $member) {
					if($member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){
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
	<div class="col-md-4 edit_clan_box">
		<h2 class="leftH2">Switch clan leader</h2>
		<form class="form" action="<?php echo home_url() ?>/clan_leader.php" name="" id="clan_leader" method="post">
		<table class="responsive-table">
			<thead>
			<tr>
				<th scope="col">Clan Leader</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td data-title="Clan leader">
					<select name="new_leader">
					<option value="<?php echo $clanleader;?>" selected="selected">
					<?php 
						$member_data = get_userdata($clanleader);
						echo $member_data->display_name;?>
					</option>
					
			
				<?php foreach ($clanmembers[0] as $key => $member) {
					if($member != $clanleader && $member != $ct_1 && $member != $ct_2 && $member != $ct_3 && $member != $ct_4){
						$member_data = get_userdata($member);
				
				
				?>	
					
					<option name="ct_1" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
					<?php }}?>
					</select>
					
					</td>
				</tr>
				</tbody>
		</table>
		<input type="submit" value="Save" class="">
				</form>
		
		
		
	</div>
	

	
	
</div>
<?php endif;?>

<?php endif;?>

            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>