<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_id();
$networth = get_user_meta($user_ID, 'networth');
$status = get_user_meta($user_ID, 'status');
include 'constants.php';
$sat_owned = get_user_meta($user_ID, 'sat_owned',true);


$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

$morale = get_user_meta($user_ID, 'morale',true);
$moralepool = get_user_meta($user_ID, 'morale_pool',true);


?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
		
		<?php if($status[0] == 'nukeprotection'):?><br/><br/><br/>
		<center><h2>You are under Nuke Protection and cannot attack.</h2></center>
		
		<?php else:?>
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
		<div class="entry-content">
			<center><h1>Step 1</h1></center>
		<center><p>You can target provinces with a networth between <?php echo '$ '.number_format($networth[0]/$ATTACK_RANGE_MULT, 0, ',', ' ').' and $ '.number_format($networth[0]*$ATTACK_RANGE_MULT, 0, ',', ' ');?>
		<br/>Your morale is currently at <?php echo $morale;?>%. <?php if(!empty($sat_owned)){ echo 'Satellite power is currently at '. $sat_morale.'%';}?>
		</p></center>
		
		
		
		
		
		
		
		<form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">	
			<table>
				<tr>
				<td><strong>Target ID</strong></td>
				<td>
			<input class="small_input" type="text" id="target_id" name="target_id" value="<?php if (!empty($_GET['id'])){echo $_GET['id'];}?>"/></td></tr>
				<tr>
				<td><input type="radio" name="attacktype" value="air_sea" checked> Air & Sea Attack
				</td>
				<td><input type="radio" name="attacktype" value="regular"> Regular Attack
				</td>
				</tr>
				
				<tr>
				<td><input type="radio" name="attacktype" value="ground"> Ground Attack
				</td>
				<td><input type="radio" name="attacktype" value="missile"> Launch Missile
				</td>
				</tr>
				
				<tr>
				<td><input type="radio" name="attacktype" value="spy"> Send spy
				</td>
				<td><input type="radio" name="attacktype" value="thief"> Send thief
				</td>
				</tr>
				
				<?php if($sat_owned == 'laser'):?>
				<tr>
				<td><input type="radio" name="attacktype" value="satellite"> Use satellite
				</td>
				<td>
				</td>
				</tr>
				<?php endif;?>
				<tr>
					<td><input type="submit" value="Next Step" class=""></td>
					<td>
					</td>
				</tr>
				
				
					
				
		</table>						
		</form>		
		<?php endif;?>
			
			
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
