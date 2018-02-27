<?php
 /*
 * Template Name: Attack 1
 */
get_header();
include 'constants.php';

$userId = get_current_user_id();
$userData = get_user_meta($userId);
update_user_meta($userId, 'user_lock', 0);
update_user_meta($userId, 'morale_lock', 0);

$networth = $userData['networth'][0];
$status = $userData['status'][0];
$satOwned = $userData['sat_owned'][0];

$attackUserId = sanitize_text_field($_GET['id']);

if ( ! empty($attackUserId)) {
	count_all_stats($attackUserId);
}

$attackUserData = get_userdata($attackUserId);


$sat_morale = $userData['sat_morale'][0];
$last_attacked = rtrim($userData['last_attacked'][0], ',');
$last_attacked = explode(',',$last_attacked);

$morale = $userData['morale'][0];
$moralepool = $userData['morale_pool'][0];

$satDisabled = 'disabled';
$satDisabledClass = 'btn-disabled';
if($satOwned != 0 || !empty($satOwned) && $satOwned != 'stealths'){
	$satDisabled = '';
	$satDisabledClass = 'btn-general';
}

 ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php if($status == 'nukeprotection'):?>
			<div class="notice_message "><span class="rdw-line">You are under Assault Protection and cannot attack.</span></div>
		
		<?php else:?>
		
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
		


<?php if(get_field('game_status','option') != 'Live'):?>
<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
<?php else:?>




<div class="notice_message notice_margin">
	<span class="rdw-line">You can target provinces with a networth between 
		<strong>
			<?php 
				$low_range = $networth/$ATTACK_RANGE_MULT;
				
				
					
				echo '$ '.number_format($low_range, 0, ',', ' ');?> 
				and 
				<?php 
				echo '$ '.number_format($networth*$ATTACK_RANGE_MULT, 0, ',', ' ');
				
				?>
		</strong>
	</span>
	<span class="rdw-line">
		Your morale is currently at <?php echo $morale;?>%. 
		<?php if(!empty($satOwned)){ echo 'Satellite power is currently at '. $sat_morale.'%';}?>
	</span>
</div>



<form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">

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
           <input type="hidden" label="erw" id="target_id" name="target_id" value="<?php echo $attackUserId; ?>" />
<?php endif; ?>





<div class="row profile_block">
	
	<?php if (empty($attackUserId)) : ?>
	<div class="row">
		
		<div class="col-md-12 attackField"> 
		<input type="text" label="erw" id="target_id" placeholder="Target ID" name="target_id" list='listid'/>
			<datalist id='listid'>
				<?php foreach ($last_attacked as $last_id) :
					$member_data = get_userdata($last_id);?>
					<option label='<?php echo $member_data->display_name . ' (#' . $last_id . ')'; ?>' value='<?php echo $last_id; ?>'>
				<?php endforeach; ?>
			</datalist>		
	</div>
	</div>
	
	<?php endif; ?>
	
	<div class="row">
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="air_sea" value="air_sea" checked>
				<label class="btn btn-general attackButton" for="air_sea">Air & Sea Attack</label>
			</div>
			<div class="row" id="air_sea_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type air and sea units can be used.<br/>
					Deals more damage than ground and regular attack types but less resources gained per attack.
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="regular" value="regular">
				<label class="btn btn-general attackButton" for="regular">Regular Attack</label>
			</div>
			
			<div class="row" id="regular_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type air units, infantry and vehicles can be used.<br/>
					Deals less damage than ground and air & sea attack types. However, more resources gained per attack. 
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
	</div> <!-- // End row -->
	
	
	
	
	<div class="row">
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="ground" value="ground">
				<label class="btn btn-general attackButton" for="ground">Ground attack</label>
			</div>
			
			<div class="row" id="ground_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type infantry and vehicles can be used.<br/>
					Deals slightly less damage than air & sea attack types but more resources gained per attack.
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
		
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="missile" value="missile">
				<label class="btn btn-general attackButton" for="missile">Launch Missile</label>
			</div>
		
			<div class="row" id="missile_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type missiles can be launched.<br/>
					You currently own <?php echo count_missiles($userId);?> missile<?php echo plural_func(count_missiles($userId));?>
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
	</div> <!-- // End row -->
	
	
	<div class="row">
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="spy" value="spy">
				<label class="btn btn-general attackButton" for="spy">Send spy</label>
			</div>
			
			<div class="row" id="spy_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type a spy or spyplane can be sent.<br/>
					A spy gathers intelligence about units. A spyplane gathers intelligence about buildings.

				</div>
			</div>
			
		</div> <!-- // End col-md-6 -->
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="thief" value="thief">
				<label class="btn btn-general attackButton" for="thief">Send thief</label>
			</div>
			
			<div class="row" id="thief_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type thiefs can be sent.<br/>
					Thiefs are used to steal money. You currently own <?php echo count_unit($userId,'thief');?> thief<?php echo plural_func(count_unit($userId,'thief'));?>
				</div>
			</div>
			
		</div> <!-- // End col-md-6 -->
	</div> <!-- // End row -->
	
	
	<div class="row">
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="sniper" value="sniper">
				<label class="btn btn-general attackButton" for="sniper">Send sniper</label>
			</div>
			
			<div class="row" id="sniper_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type snipers can be sent.<br/>
					Snipers are used to kill thiefs, spies and other snipers. You currently own <?php echo count_unit($userId,'sniper');?> sniper<?php echo plural_func(count_unit($userId,'sniper'));?>
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" <?php echo $satDisabled;?> id="satellite" value="satellite">
				<label class="btn <?php echo $satDisabledClass;?> attackButton" <?php echo $satDisabled;?> for="satellite">Use satellite</label>
			</div>
			
			<div class="row" id="satellite_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack a satellite can be fired <br/>
					EMP or Laser Beam satellite can be used
				</div>
			</div>
			
		</div> <!-- // End col-md-6 -->
	</div> <!-- // End row -->
	
	
	<div class="row">
		
		<div class="col-md-6">
			<div class="row">
				<input style="display:none;" type="radio" name="attacktype" id="saboteur" value="saboteur">
				<label class="btn btn-general attackButton" for="saboteur">Send saboteur</label>
			</div>
			
			<div class="row" id="saboteur_desc">
				<div class="attackDescription">
					<i class="fa fa-info-circle" aria-hidden="true"></i> In this attack type saboteurs can be sent.<br/>
					Saboteurs are used to disable missile silos. You currently own <?php echo count_unit($userId,'saboteur');?> saboteur<?php echo plural_func(count_unit($userId,'saboteur'));?>
				</div>
			</div>
		</div> <!-- // End col-md-6 -->
			
		</div> <!-- // End col-md-6 -->
	</div> <!-- // End row -->
	


<div class="row">
	<div class="col-md-6 attackSelect">
			<div class="attackType_title">Attack Mode</div>
			<div class="styled-select slate">
				<select id="attackmode" name="attackmode">
					<option name="attackmode" value="normal">Normal</option>
					<option name="attackmode" value="aggressive">Aggressive (Higher gain and higher loss. Costs 10% extra morale.)</option>
				</select>
			</div>
	</div>
	
	<div class="col-md-6 attackSelect">
			<div class="attackType_title">Main Target</div>
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

<div class="col-md-12"> 
	<input type="submit" value="Next Step" class="">	
</div>

</div> <!-- // End row entire block -->

</form>

<script>
	jQuery(document).ready(function() {
		jQuery('#regular_desc').hide();
		jQuery('#ground_desc').hide();
		jQuery('#missile_desc').hide();
		jQuery('#spy_desc').hide();
		jQuery('#thief_desc').hide();
		jQuery('#sniper_desc').hide();
		jQuery('#satellite_desc').hide();
		jQuery('#saboteur_desc').hide();
   jQuery('input[type="radio"]').click(function() {
       if(jQuery(this).attr('id') == 'air_sea') {
            jQuery('#air_sea_desc').show(750);           
       }

       else {
            jQuery('#air_sea_desc').hide(750);   
       }
       
       if(jQuery(this).attr('id') == 'regular') {
            jQuery('#regular_desc').show(750);           
       }

       else {
            jQuery('#regular_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'ground') {
            jQuery('#ground_desc').show(750);           
       }

       else {
            jQuery('#ground_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'missile') {
            jQuery('#missile_desc').show(750);           
       }

       else {
            jQuery('#missile_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'spy') {
            jQuery('#spy_desc').show(750);           
       }

       else {
            jQuery('#spy_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'thief') {
            jQuery('#thief_desc').show(750);           
       }

       else {
            jQuery('#thief_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'sniper') {
            jQuery('#sniper_desc').show(750);           
       }

       else {
            jQuery('#sniper_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'satellite') {
            jQuery('#satellite_desc').show(750);           
       }

       else {
            jQuery('#satellite_desc').hide(750);   
       }
       if(jQuery(this).attr('id') == 'saboteur') {
            jQuery('#saboteur_desc').show(750);           
       }

       else {
            jQuery('#saboteur_desc').hide(750);   
       }
       
   });
});
</script>






		<?php endif;?>
		<?php endif;?>
		<?php session_unset();?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>