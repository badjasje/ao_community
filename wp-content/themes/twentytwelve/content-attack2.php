<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
include 'DO_NOT_DELETE.php';
 

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php 
			
			
			
			 if (!empty($_GET['fail'])) { $attacksuccess = $_GET['fail']; ?>
			<?php if($attacksuccess == 1):?>
				<div class="marketnotice insuffunds">Send units in to battle!</div>
			<?php elseif($attacksuccess == 2):?>
				<div class="marketnotice insuffunds">Insufficient morale</div>
			<?php elseif($attacksuccess == 3):?>
				<div class="marketnotice insuffunds">No units available for this attack type</div>
			<?php endif;?><?php }?>
		<div class="entry-content">
		<center><h1>Step 2</h1></center>
		
		
		
<?php if($_SESSION['attacktype'] != 'missile' && $_SESSION['attacktype'] != 'spy'){?>
	
	<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">	
		<?php //////////////// AIR & SEA ATTACK ////////////////
			$units_total = 0;
			
			if($_SESSION['attacktype'] == 'air_sea'){?>
				<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Att/Life</strong></td>
						<td><strong>Targets</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
						
  					</tr>
				
				
			<?php
			foreach($units as $key => $unit){
				$units_owned = get_user_meta($user_ID, $key.'_owned');
				
				if($unit['type'] == 'air' || $unit['type'] == 'sea' and $unit['normalname'] != 'SR-71 Spyplane'){
				
				if($units_owned[0]>0){
					?>
					
			<tr>
				<td>
					<label class="markettitle"><strong><?php echo $unit['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $unit['shortname'];?></label>
				</td>
				
				<td>
					<?php echo $unit['attack'];?> / <?php echo $unit['life'];?>			
				</td>
				
				<td>
						<span class="markettitle"><?php 
						$i = 0;
						$len = count($unit['attacks']);
						foreach($unit['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					<span class="shorttitle"><?php 
						$i = 0;
						$len = count($unit['shortatt']);
						foreach($unit['shortatt'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
				</td>
					
				<td>
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>				</td>
					
				<td>
					<input class="small_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
				</td>
					
			</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					
				
				<?php }}}?>
				</table>
				<br/>
		<input  id="button" type="submit" value="Next step" class="">
	</form>
			<?php }?>
			<?php //////////////// SENDING THIEF ////////////////
				
				$units_total = 0;
				
				
				if($_SESSION['attacktype'] == 'thief'){?>
				<center>Thiefs are used to steal money of.<br/>Sending more thiefs increases the amount of money stolen but also increases the chance of getting caught.</center><br/>
				<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
						
  					</tr>
				
				
				<?php
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					
						
					if($unit['normalname'] == 'Thief'){
					if($units_owned[0]>0){
					?>
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $unit['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $unit['shortname'];?></label>
					</td>
					
			
					<td>
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>
					</td>
					<td>
					<input class="small_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					
					
				<?php }}}?>
				</table>
				<br/>
		<input  id="button" type="submit" value="Next step" class="">
	</form>
			<?php }?>	
				
				
			<?php   /////////////// REGULAR ATTACK ////////////////
				
				$units_total = 0;
				
				if($_SESSION['attacktype'] == 'regular'){?>
				<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Att/Life</strong></td>
						<td><strong>Targets</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
						
  					</tr>
				
				
				<?php
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					
					if($unit['type'] == 'veh' || $unit['type'] == 'inf' || $unit['type'] == 'air' and $unit['normalname'] != 'Thief' and $unit['normalname'] != 'Spy' and $unit['normalname'] != 'SR-71 Spyplane'){
					if($units_owned[0]>0){
					?>
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $unit['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $unit['shortname'];?></label>
					</td>
					<td>
					<?php echo $unit['attack'];?> / <?php echo $unit['life'];?>			
					</td>
					<td>
						<span class="markettitle"><?php 
						$i = 0;
						$len = count($unit['attacks']);
						foreach($unit['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					<span class="shorttitle"><?php 
						$i = 0;
						$len = count($unit['shortatt']);
						foreach($unit['shortatt'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					</td>
					<td>
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>		
					</td>
					<td>
					<input class="small_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					
					
				<?php }}}?>
				</table>
				<br/>
		<input  id="button" type="submit" value="Next step" class="">
	</form>
			<?php }?>
				
				
				
				
				<?php   /////////////// GROUND ATTACK ////////////////
				
				$units_total = 0;
				
				if($_SESSION['attacktype'] == 'ground'){?>
				<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Att/Life</strong></td>
						<td><strong>Targets</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
						
  					</tr>
				
				
				<?php
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					
					if($unit['type'] == 'veh' || $unit['type'] == 'inf' and $unit['normalname'] != 'Thief' and $unit['normalname'] != 'Spy' and $unit['normalname'] != 'SR-71 Spyplane'){
					if($units_owned[0]>0){
					?>
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $unit['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $unit['shortname'];?></label>
					</td>
					<td>
					<?php echo $unit['attack'];?> / <?php echo $unit['life'];?>			
					</td>
					<td>
						<span class="markettitle"><?php 
						$i = 0;
						$len = count($unit['attacks']);
						foreach($unit['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					<span class="shorttitle"><?php 
						$i = 0;
						$len = count($unit['shortatt']);
						foreach($unit['shortatt'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					</td>
					<td>
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>		
					</td>
					<td>
					<input min="0" class="small_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						
						});
					
					</script>
					
					
				<?php }}}?>
				</table>
				<br/>
		<input  id="button" type="submit" value="Next step" class="">
	</form>
			<?php }?>
		</form>		
		
		<?php }?>
		
		
		<?php  ////// SEND SPYYYY
					
					
					if($_SESSION['attacktype'] == 'spy'):?>
		<center><h3>Select which spy type you wish to send. You can only send 1 spy per attack.</h3></center><br/>
		<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Spies on</strong></td>
						<td><strong>Att/Life</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
  					</tr>
		<?php foreach($units as $key => $unit){
			if($unit['normalname'] == 'Spy' || $unit['normalname'] == 'SR-71 Spyplane'){
					$spies_owned = get_user_meta($user_ID, $key.'_owned');
					if($spies_owned[0]>0){
					?>
					<tr>
						<td>
							<strong><?php echo $unit['normalname'];?></strong>
						</td>
						<td>
						<?php if($unit['normalname'] == 'Spy'){echo'Units';}else{echo'Buildings';}?>
						</td>
						<td>
							n.a / <?php echo $unit['life'];?>		
						</td>
						<td>
							<?php echo $spies_owned[0];?>		
						</td>
						<td>
							<input name="sendspy" type="radio" name="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $key;?>">
						</td>
					</tr>
					
					
					
					
				<?php }}}?>
		</table><br/>
		<input type="submit" value="Next step" class="spy">
		</form>
		<?php endif;?>
		
		
		
		
		
		
		
		<?php 
			////// SEND MISSILE
			
			if($_SESSION['attacktype'] == 'missile'):  ?>
		<center><h3>Select which missile you want to launch. You can only launch one missile in every attack</h3></center><br/>
		<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Attack</strong></td>
						<td><strong>Targets</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
  					</tr>
		<?php foreach($missiles as $key => $missile){
					$missiles_owned = get_user_meta($user_ID, $key.'_owned');
					if($missiles_owned[0]>0){
					?>
					<tr>
						<td>
							<strong><?php echo $missile['normalname'];?></strong>
						</td>
						<td>
							<?php echo $missile['attack'];?>			
						</td>
						<td>
							<span class="markettitle"><?php 
						$i = 0;
						$len = count($missile['attacks']);
						foreach($missile['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					<span class="shorttitle"><?php 
						$i = 0;
						$len = count($missile['shortatt']);
						foreach($missile['shortatt'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
						</td>
						<td>
							<?php echo $missiles_owned[0];?>		
						</td>
						<td>
							<input type="radio" name="missile" value="<?php echo $key;?>">
						</td>
					</tr>
					
					
					
					
				<?php }}?>
		</table><br/>
		<input type="submit" value="Next step" class="">
		</form>
		<?php endif;?>
		<?php 
			////// SEND MISSILE
			
			if($_SESSION['attacktype'] == 'satellite'):  ?>
			
			<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Attack</strong></td>
						<td><strong>Targets</strong></td>
						<td><strong>Send</strong></td>
  					</tr>
	
					<tr>
						<td>
							<strong>Laser beam satellite</strong>
						</td>
						<td>
							10 000		
						</td>
						<td>
							Buildings
						</td>
						<td>
							<input checked="checked" type="radio" name="<?php echo $key;?>" value="1">
						</td>
					</tr>
					
					
					
					
		</table><br/>
		<input type="submit" value="Next step" class="">
		</form>

			
			<?php endif;?>
			
		
		
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
	
