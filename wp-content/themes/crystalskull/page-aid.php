<?php
 /*
 * Template Name: Aid
 */
$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$clanmembers = get_post_meta($clan_ID,'clan_members');
$user_NW = get_user_meta($user_ID, 'networth',true);
$aid_sent = get_user_meta($user_ID, 'aid_sent_today', true);
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
	            
	    <?php if($aid_sent == 3):?>
	    <div class="notice_message"><span class="rdw-line">You have sent aid 3 times today. You can send new aid in 24 hours.</div><br/>
	    <?php else:?>
       <div class="notice_message"><span class="rdw-line">You've sent aid <?php echo $aid_sent;?> times today</span> <span class="rdw-line">You can send a maximum of $ 250 000</span></div><br/>
       
       <?php if(count($clanmembers[0]) > 1):?>
<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Member</th>
						<th scope="col">Amount</th>
  					</tr>
  					</thead>
  					<tbody>
	  				<tr>
						<td data-title="Send aid to user">
							<form class="form" action="<?php echo home_url() ?>/send_aid.php" name="" id="aid" method="post">	
							<select name="receiver">

						<?php foreach ($clanmembers[0] as $key => $member) {
							$nw_user = get_user_meta($member, 'networth',true);
							if($member != $user_ID && $user_NW > $nw_user){
								$member_data = get_userdata($member);
						
						
						?>	
							
							<option name="receiver" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
							<?php }}?>
							</select>
						</td>
						<td><input type="text" id="amount" name="amount"/>
						</td>
	  				</tr>
  					</tbody>
</table>
<input type="submit" value="Send aid"></form>

<script>
	jQuery('form#aid').submit(function(){
    jQuery(this).find(':input[type=submit]').prop('disabled', true);
});
</script>

            <?php endif;?><?php endif;?><?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>