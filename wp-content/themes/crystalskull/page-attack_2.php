<?php
 /*
 * Template Name: Attack step 2
 */
include 'DO_NOT_DELETE.php';

if(empty($_SESSION['attacktype'])){
wp_redirect(get_permalink(3360).'?fail=4');
exit;
}

$attack_type = $_SESSION['attacktype'];
/* Determine attack name for header */
if($attack_type == 'ground'){ $attack_name = 'Ground attack'; }
if($attack_type == 'air_sea'){ $attack_name = 'Air & Sea attack'; }
if($attack_type == 'regular'){ $attack_name = 'Regular attack'; }
if($attack_type == 'missile'){ $attack_name = 'Launching missile'; }
if($attack_type == 'spy'){ $attack_name = 'Spying'; }
if($attack_type == 'thief'){ $attack_name = 'Thieving'; }
if($attack_type == 'satellite'){ $attack_name = 'Using satellite'; }
	
$attackUserId = $_SESSION['target_id'];
count_all_stats($attackUserId);

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php 
			
			
			
			 if (!empty($_GET['fail'])) { $attacksuccess = $_GET['fail']; ?>
			<?php if($attacksuccess == 1):?>
				<div class="marketnotice insuffunds">Send units in to battle!</div>
			<?php elseif($attacksuccess == 2):?>
				<div class="marketnotice insuffunds">Insufficient morale</div>
			<?php elseif($attacksuccess == 3):?>
				<div class="marketnotice insuffunds">No units available for this attack type</div>
			<?php endif;?><?php }?>

<?php if ( ! empty($attackUserId)) : ?>
		

		
		
		<ul class="target_info media-list">
		<li class="media ">
		<div class="media-left">
		
        <div class="leftAvatar"><?php echo small_avatar($attackUserId,'');?></div>
      
	    </div>
		<div class="media-body">
		<h4 class="media-heading">Attacking <?php echo LinkUtil::user_link($attackUserId); ?></h4>
		<?php echo $attack_name;?>
    	</div>
		</li>
		</ul>
           
        <?php endif; ?>

<?php if($_SESSION['attacktype'] != 'missile' && $_SESSION['attacktype'] != 'spy'){?>

	<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">	
		<?php //////////////// AIR & SEA ATTACK ////////////////
			$units_total = 0;
			
			if($_SESSION['attacktype'] == 'air_sea'){?>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Att/Life</strong></th>
						<th scope="col"><strong>Targets</strong></th>
						<th scope="col"><strong>Owned</strong></th>
						<th scope="col"><strong>Send</strong></th>
						
  					</tr>
					</thead>
  				<tbody>
				
			<?php
				$sendall = array();
				$tot_units = 0;
			foreach($units as $key => $unit){
				$units_owned = get_user_meta($user_ID, $key.'_owned');
				$tot_units+=$units_owned[0];
				
				if($unit['type'] == 'air' || $unit['type'] == 'sea' and $unit['normalname'] != 'SR-71 Spyplane'){
				
				if($units_owned[0]>0){
					$sendall[] = $units_owned[0];
					?>
					
			<tr>
				<td data-title="Name">
					<strong><?php echo $unit['normalname'];?></strong>
				</td>
				
				<td data-title="Attack/Life">
					<?php echo $unit['attack'];?> / <?php echo $unit['life'];?>			
				</td>
				
				<td data-title="Targets">
						<?php 
						$i = 0;
						$len = count($unit['attacks']);
						foreach($unit['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
				</td>
					
				<td data-title="Owned">
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>				</td>
					
				<td>
					<input class="unit_input" placeholder="Enter amount to send, or click unit amount" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
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
  				</tbody>
				</table>
			
				<?php if($tot_units > 0):?>
		
		<div class="row">
		  <div class="col-md-8"><input  id="button" type="submit" value="Next step" class=""></div>
		  <div class="col-md-4"><center><div class="send_all btn btn-general" style="margin-top: 11px;"data-val="<?php echo implode('|',$sendall);?>">Send all available units</div></center></div>
		</div>
		
		<script>
			jQuery(".send_all").on("click", function() {
		    var val = jQuery(this).data("val").toString().split("|");
		    jQuery(".unit_input").val(function(i) {
		        return val[i] || "";
		    });
			});	
					
		</script>
		
		<div class="footer_continue">
			<input  id="button" type="submit" value="Next step" class="">
		</div><?php endif;?>
	</form>
	
	
				
	
			<?php }?>
			<?php //////////////// SENDING THIEF ////////////////
				
				$units_total = 0;
				
				
				if($_SESSION['attacktype'] == 'thief'){
					
					$thief_owned = get_user_meta($user_ID, 'thief_owned',true);
				?>
				<div class="notice_message"><span class="rdw-line">Thiefs are used to steal money.</span> <span class="rdw-line">Sending more thiefs increases the amount of money stolen but also increases the chance of getting caught.</span></div><br/>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned</th>
						<th scope="col">Send</th>
						
  					</tr>
					</thead>
				
				<?php
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					
					
					if($unit['normalname'] == 'Thief'){
					if($units_owned[0]>0){
					?>
					<tr>
					<td data-title="Name">
					<?php echo $unit['normalname'];?>
					</td>
					
			
					<td data-title="Owned">
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
			<?php if($thief_owned>0):?>
		<input  id="button" type="submit" value="Next step" class="">
		<?php endif;?>
	</form>
			<?php }?>	
			
			
			
			
			
			
			<?php //////////////// SENDING SNIPER ////////////////
				
				$units_total = 0;
				
				
				if($_SESSION['attacktype'] == 'sniper'){
					
					$thief_owned = get_user_meta($user_ID, 'sniper_owned',true);
				?>
				<div class="notice_message"><span class="rdw-line">Snipers are used to kill thiefs and spies</span> <span class="rdw-line">Sending more snipers increases the amount of thiefs and spies killed but also increases the chance to get caught.</span></div><br/>
				<table class="responsive-table">
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Owned</strong></td>
						<td><strong>Send</strong></td>
						
  					</tr>
				
				
				<?php
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					
					
					if($unit['normalname'] == 'Sniper'){
					if($units_owned[0]>0){
					?>
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $unit['normalname'];?></strong></label>
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
			<?php if($thief_owned>0):?>
		<input  id="button" type="submit" value="Next step" class="">
		<?php endif;?>
	</form>
			<?php }?>	
			
			
			
			
			
			
			
			
				
				
			<?php   /////////////// REGULAR ATTACK ////////////////
				
		
				
				if($_SESSION['attacktype'] == 'regular'){?>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Att/Life</strong></th>
						<th scope="col"><strong>Targets</strong></th>
						<th scope="col"><strong>Owned</strong></th>
						<th scope="col"><strong>Send</strong></th>
						
  					</tr>
					</thead>
  				<tbody>
				
				
				<?php
					$units_total = 0;
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_total+=$units_owned[0];
					if($unit['type'] == 'veh' || $unit['type'] == 'inf' || $unit['type'] == 'air' and $unit['normalname'] != 'Thief' and $unit['normalname'] != 'Spy' and $unit['normalname'] != 'SR-71 Spyplane' and $unit['normalname'] != 'Sniper'){
					if($units_owned[0]>0){
						$sendall[] = $units_owned[0];
					?>
					<tr>
					<td data-title="Name"><strong><?php echo $unit['normalname'];?></strong></td>
					<td data-title="Attack/Life"><?php echo $unit['attack'];?> / <?php echo $unit['life'];?></td>
					<td data-title="Attacks">
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
				
					</td>
					<td data-title="Owned">
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>		
					</td>
					<td>
					<input class="unit_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
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
  				</tbody>
				</table>
				<br/>
				<?php if($units_total > 0):?>
		<div class="row">
		  <div class="col-md-8"><input  id="button" type="submit" value="Next step" class=""></div>
		  <div class="col-md-4"><center><div class="send_all btn btn-general" style="margin-top: 11px;"data-val="<?php echo implode('|',$sendall);?>">Send all available units</div></center></div>
		</div>
		
		<script>
			jQuery(".send_all").on("click", function() {
		    var val = jQuery(this).data("val").toString().split("|");
		    jQuery(".unit_input").val(function(i) {
		        return val[i] || "";
		    });
			});	
					
		</script>		
		
		
		<?php endif;?>
	</form>
			<?php }?>
				
				
				
				
				<?php   /////////////// GROUND ATTACK ////////////////
				
				
				
				if($_SESSION['attacktype'] == 'ground'){?>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Att/Life</strong></th>
						<th scope="col"><strong>Targets</strong></th>
						<th scope="col"><strong>Owned</strong></th>
						<th scope="col"><strong>Send</strong></th>
						
  					</tr>
					</thead>
  				<tbody>
				
				
				<?php
					$units_total = 0;
				foreach($units as $key => $unit){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_total+=$units_owned[0];
					if($unit['type'] == 'veh' || $unit['type'] == 'inf' and $unit['normalname'] != 'Thief' and $unit['normalname'] != 'Spy' and $unit['normalname'] != 'SR-71 Spyplane'){
					if($units_owned[0]>0){
						$sendall[] = $units_owned[0];
					?>
					<tr>
					<td data-title="Name"><strong><?php echo $unit['normalname'];?></strong></td>
					<td data-title="Attack/Life"><?php echo $unit['attack'];?> / <?php echo $unit['life'];?></td>
					<td data-title="Attacks">
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
					
					</td>
					<td data-title="Owned">
					<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];$units_total+=$units_owned[0];?></span>		
					</td>
					<td>
					<input min="0" class="unit_input" placeholder="Enter how many units you want to send to battle" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						
						});
					
					</script>
					
					
				<?php }}}?>
  				</tbody>
				</table>
				<br/>
				<?php if($units_total>0):?>
		<div class="row">
		  <div class="col-md-8"><input  id="button" type="submit" value="Next step" class=""></div>
		  <div class="col-md-4"><center><div class="send_all btn btn-general" style="margin-top: 11px;"data-val="<?php echo implode('|',$sendall);?>">Send all available units</div></center></div>
		</div>
		
		<script>
			jQuery(".send_all").on("click", function() {
		    var val = jQuery(this).data("val").toString().split("|");
		    jQuery(".unit_input").val(function(i) {
		        return val[i] || "";
		    });
			});	
					
		</script>	
		
		<?php endif;?>
	</form>
			<?php }?>
		</form>		
		
		<?php }?>
		
		
		<?php  ////// SEND SPYYYY
					
					
					if($_SESSION['attacktype'] == 'spy'):?>
		<div class="notice_message">Select which spy type you wish to send. You can only send 1 spy per attack.</div><br/>
		<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
		<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Spies on</strong></th>
						<th scope="col"><strong>Att/Life</strong></th>
						<th scope="col"><strong>Owned</strong></th>
						<th scope="col"><strong>Send</strong></th>
  					</tr>
			</thead>
			<tbody>
		<?php 
			$units_total = 0;
			foreach($units as $key => $unit){
			if($unit['normalname'] == 'Spy' || $unit['normalname'] == 'SR-71 Spyplane'){
					$spies_owned = get_user_meta($user_ID, $key.'_owned');
					$units_total+=$spies_owned[0];
					if($spies_owned[0]>0){
					?>
					<tr>
						<td data-title="Name">
							<strong><?php echo $unit['normalname'];?></strong>
						</td>
						<td data-title="Spies">
						<?php if($unit['normalname'] == 'Spy'){echo'Units';}else{echo'Buildings';}?>
						</td>
						<td data-title="Attack/Life">
							n.a / <?php echo $unit['life'];?>		
						</td>
						<td data-title="Send">
							<?php echo $spies_owned[0];?>		
						</td>
						<td>
						<?php /*?><input name="sendspy" type="radio" name="<?php echo $key;?>" name="<?php echo $key;?>" value="<?php echo $key;?>"><?php */?>
<input style="display:none;" type="radio" name="sendspy" id="send_spy_<?php echo $key;?>" required value="<?php echo $key;?>"><label class="btn btn-general" for="send_spy_<?php echo $key;?>">Select</label>
							
							
							
						
						</td>
					</tr>
					
					
					
					
				<?php }}}?>
			</tbody>
		</table><br/>
		<?php if($units_total >0):?>
		<input type="submit" value="Next step" class="spy">
		<?php endif;?>
		</form>
		<?php endif;?>
		
		
		
		
		
		
		
		<?php 
			////// SEND MISSILE
			
			if($_SESSION['attacktype'] == 'missile'):  ?>
		<div class="notice_message">Select which missile you want to launch. You can only launch one missile in every attack</div><br/>
		<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
		<table class="responsive-table">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Attack</th>
						<th scope="col">Targets</th>
						<th scope="col">Owned</th>
						<th scope="col">Send</th>
  					</tr>
				</thead>
				<tbody>
		<?php 
			$owned = 0;
			foreach($missiles as $key => $missile){
					$missiles_owned = get_user_meta($user_ID, $key.'_owned');
					$owned+=$missiles_owned[0];
					if($missiles_owned[0]>0){
					?>
					<tr>
						<td data-title="Name">
							<strong><?php echo $missile['normalname'];?></strong>
						</td>
						<td data-title="Attack">
							<?php echo $missile['attack'];?>			
						</td>
						<td data-title="Targets">
						<?php 
						$i = 0;
						$len = count($missile['attacks']);
						foreach($missile['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
				
						</td>
						<td data-title="Owned">
							<?php echo $missiles_owned[0];?>		
						</td>
						<td>
							
							<input style="display:none;" type="radio" name="missile" id="missile_<?php echo $key;?>" required value="<?php echo $key;?>"><label class="btn btn-general" for="missile_<?php echo $key;?>">Select</label>
						</td>
					</tr>
					
					
					
					
				<?php }}?>
				</tbody>
		</table><br/>
		<?php if($owned>0):?>
		<input type="submit" value="Next step" class="">
		<?php endif;?>
		</form>
		<?php endif;?>



<?php /* use satellite */			

if($_SESSION['attacktype'] == 'satellite'):  ?>

<?php 
	include 'satellite_array.php';
	$sat_owned = get_user_meta($user_ID, 'sat_owned', true);?>
			
			
<form class="form" action="<?php echo home_url() ?>/attack2.php" name="" id="attack2" method="post">
			
	<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Attack</th>
						<th scope="col">Targets</th>
						<th scope="col">Send</th>
  					</tr>
			</thead>
					<tr>
						<td data-title="Name"> 
						<?php echo $satellites[$sat_owned]['name'];?>
						</td>
						<td data-title="Attack">
						<?php echo $satellites[$sat_owned]['attack'];?>	
						</td>
						<td data-title="Targets">
						<?php echo $satellites[$sat_owned]['targets'];?>
						</td>
						<td>
							<input style="display:none;" type="radio" name="sattype" value="<?php echo $sat_owned;?>" id="sat_<?php echo $sat_owned;?>" checked required value="1"><label class="btn btn-general" for="sat_<?php echo $sat_owned;?>">Select</label>
							<?php /*<input checked="checked" type="radio" name="<?php echo $key;?>" value="1"> */?>
						</td>
					</tr>
					
					
					
					
		</table><br/>
		<input type="submit" value="Next step" class="">
		</form>

			
			<?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>