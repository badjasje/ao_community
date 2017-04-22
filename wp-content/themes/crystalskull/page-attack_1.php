<?php
 /*
 * Template Name: Attack 1
 */

$user_ID = get_current_user_id();
$networth = get_user_meta($user_ID, 'networth',true);
$status = get_user_meta($user_ID, 'status');
include 'constants.php';
$sat_owned = get_user_meta($user_ID, 'sat_owned',true);

$attackUserId = sanitize_text_field($_GET['id']);

if ( ! empty($attackUserId)) {
	count_all_stats($attackUserId);
}

$attackUserData = get_userdata($attackUserId);


$sat_morale = get_user_meta($user_ID, 'sat_morale',true);
$last_attacked = rtrim(get_user_meta($user_ID, 'last_attacked',true), ',');
$last_attacked = explode(',',$last_attacked);

$morale = get_user_meta($user_ID, 'morale',true);
$moralepool = get_user_meta($user_ID, 'morale_pool',true);
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php if($status[0] == 'nukeprotection'):?>
			<div class="notice_message"><span class="rdw-line">You are under Nuke Protection and cannot attack.</span></div>
		
		<?php else:?>
		
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
		
		
		
		<?php if (!empty($_GET['fail'])) { $attacksuccess = $_GET['fail']; ?>
			<?php if($attacksuccess == 1):?>
				<div class="marketnotice insuffunds">Not enough turns</div>
			<?php elseif($attacksuccess == 2):?>
				<div class="marketnotice insuffunds">Insufficient morale</div>
			<?php elseif($attacksuccess == 3):?>
				<div class="marketnotice insuffunds">No units available for this attack type</div>
			<?php elseif($attacksuccess == 4):?>
				<div class="marketnotice insuffunds">Choose a province ID to attack</div>
			<?php elseif($attacksuccess == 5):?>
				<div class="marketnotice insuffunds">Province ID not found</div>
			<?php elseif($attacksuccess == 6):?>
				<div class="marketnotice insuffunds">You cannot target yourself</div>
			<?php elseif($attacksuccess == 7):?>
				<div class="marketnotice insuffunds">No units selected</div>
			<?php elseif($attacksuccess == 8):?>
				<div class="marketnotice insuffunds">This player is dead</div>
			<?php elseif($attacksuccess == 9):?>
				<div class="marketnotice insuffunds">Out of networth range</div>
			<?php elseif($attacksuccess == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php elseif($attacksuccess == 11):?>
				<div class="marketnotice insuffunds">You cannot attack your own clanmates</div>
			<?php elseif($attacksuccess == 13):?>
				<div class="marketnotice insuffunds">You cannot attack someone who is under nuke protection</div>
			<?php elseif($attacksuccess == 14):?>
				<div class="marketnotice insuffunds">No more missiles left of this type.</div>
			<?php elseif($attacksuccess == 15):?>
				<div class="marketnotice insuffunds">You cannot attack while power is out.</div>
			<?php elseif($attacksuccess == 16):?>
				<div class="marketnotice insuffunds">Not enough thiefs</div>
			<?php elseif($attacksuccess == 20):?>
				<div class="marketnotice insuffunds">Not enough satellite power</div>
			<?php endif;?><?php }?>

<?php if(get_field('game_status','option') != 'Live'):?>
<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
<?php else:?>




<div class="notice_message">
	<span class="rdw-line">You can target provinces with a networth between 
		<strong>
			<?php 
				$low_range = $networth/$ATTACK_RANGE_MULT;
				
				if($low_range < 3500){
					$low_range = 3500;
				}
					
				echo '$ '.number_format($low_range, 0, ',', ' ');?> 
				and 
				<?php 
				echo '$ '.number_format($networth*$ATTACK_RANGE_MULT, 0, ',', ' ');
				
				?>
		</strong>
	</span>
	<span class="rdw-line">
		Your morale is currently at <?php echo $morale;?>%. 
		<?php if(!empty($sat_owned)){ echo 'Satellite power is currently at '. $sat_morale.'%';}?>
	</span>
</div>





<?php if ( ! empty($attackUserId)) : ?>
		
	<?php 
		
		$attackUserNW = get_user_meta($attackUserId, 'networth',true);
		if (($attackUserNW > $networth/1.4 && $attackUserNW < $networth*1.4)){	
			$range_msg = '<i class="fa fa-check-circle" aria-hidden="true"></i> Target in range';
			}
		else {
			$range_msg = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Target out of range';
		}
		
		if(!empty(get_user_meta($attackUserId, 'avatar_user', true))){
		$avatar = get_user_meta($attackUserId, 'avatar_user', true);
		} 
		else {
		$avatar = '/wp-content/uploads/2016/11/default_large.png';
		}
		?>
		
		
		<ul class="target_info media-list">
		<li class="media ">
		<div class="media-left">
		
        <div class="leftAvatar"><?php echo small_avatar($attackUserId,'');?></div>
      
	    </div>
		<div class="media-body">
		<h4 class="media-heading">Attacking <?php echo LinkUtil::user_link($attackUserId); ?></h4>
		<?php echo $range_msg;?>
    	</div>
		</li>
		</ul>
           
        <?php endif; ?>
		
		<form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">	
			<table class="responsive-table">
				<?php if (empty($attackUserId)) : ?>
                    <tr>

                        <td colspan="2">
                            <input style="font-size: 24px;text-align:center;font-weight: bold;" type="text" label="erw" id="target_id" placeholder="Target ID" name="target_id" list='listid'/>

                            <datalist id='listid'>
								<?php foreach ($last_attacked as $last_id) :
								    $member_data = get_userdata($last_id);
								?>
                                    <option label='<?php echo $member_data->display_name . ' (#' . $last_id . ')'; ?>' value='<?php echo $last_id; ?>'>
                                <?php endforeach; ?>
                            </datalist>
                        </td>
                    </tr>
                <?php else: ?>
                    <input type="hidden" label="erw" id="target_id" name="target_id" value="<?php echo $attackUserId; ?>" />
				<?php endif; ?>

				<tr>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="air_sea" value="air_sea" checked><label class="btn btn-general" for="air_sea">Air & Sea Attack</label>
				</td>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="regular" value="regular"><label class="btn btn-general" for="regular">Regular Attack</label>
				</td>
				</tr>
				
				<tr>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="ground" value="ground"><label class="btn btn-general" for="ground">Ground attack</label>
				</td>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="missile" value="missile"><label class="btn btn-general" for="missile">Launch Missile</label>
				</td>
				</tr>
				
				<tr>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="spy" value="spy"><label class="btn btn-general" for="spy">Send spy</label>
				</td>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="thief" value="thief"><label class="btn btn-general" for="thief">Send thief</label>
				</td>
				</tr>
				<tr>
				<!--<td class="left_text_table"><label for="sniper"><input type="radio" name="attacktype" id="sniper" value="sniper"> Send sniper</label> (not done. dont use.)-->
				</td>
				</tr>
				
				<?php if($sat_owned != 0 || !empty($sat_owned) && $sat_owned != 'stealths'):?>
				<tr>
				<td class="left_text_table"><input style="display:none;" type="radio" name="attacktype" id="satellite" value="satellite"><label class="btn btn-general" for="satellite">Use satellite</label>
				</td>
				<td>
				</td>
				</tr>
				<?php endif;?>
				<tr>
				
				<td colspan="2">
				
				<div class="row">
				<div class="col-md-6">
					<div class="styled-select slate">
						<select id="attackmode" name="attackmode">
							<option name="attackmode" value="normal">Normal</option>
							<option name="attackmode" value="aggressive">Aggressive (Higher gain and higher loss. Costs 10% extra morale.)</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="styled-select slate">
						<select id="maintarget" name="maintarget">
							<option name="maintarget" value="none">-- none --</option>
							<option name="maintarget" value="power">Power plants</option>
							<option name="maintarget" value="silo">Missile silos</option>
							<option name="maintarget" value="command">Command centres</option>
							<option name="maintarget" value="shipyard">Shipyards</option>
							<option name="maintarget" value="airfield">Airfields</option>
							<option name="maintarget" value="barracks">Barracks</option>
							<option name="maintarget" value="warfactory">Warfactories</option>
							<option name="maintarget" value="defense">Defense buildings</option>
							<option name="maintarget" value="ams">Anti-Missile System</option>
						</select>
					</div>
				</div>
				</div>
				
				
				
				
				
				</td>
				</tr>
		</table>	
		<input type="submit" value="Next Step" class="">					
		</form>		
		<?php endif;?>
		<?php endif;?>
		<?php session_unset();?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>