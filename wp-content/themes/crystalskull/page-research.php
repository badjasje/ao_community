<?php
 /*
 * Template Name: Research
 */
 include 'research_array.php';
 $user_ID = get_current_user_id();
 $research_in_progress = get_user_meta($user_ID, 'research_in_progress')[0];
 $research_queued = get_user_meta($user_ID, 'queued_research')[0];
 
$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$research_reduce = 1;
if($startingbonus == 'defensive'){
	$research_reduce = 0.9;
}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			
			
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice">Research started</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Not enough turns</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php elseif($_SESSION['status'] == 13):?>
				<div class="marketnotice">Research queued</div>
			<?php endif;?><?php endif;?>
		
		<?php if(get_field('game_status','option') != 'Live'):?>
		<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
		<?php else:?>
		<div class="notice_message"><span class="rdw-line">One research costs 25 turns. Queuing a research costs an additional 5 turns.</span></div><br/>
		<?php if(empty($research_in_progress) || $research_in_progress = 0):?>
		
		
		
		<form class="form" action="<?php echo home_url() ?>/research.php" name="" id="market" method="post">
				
				
			<div class="container2">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Effect</th>
							<th scope="col">Time</th>
							<th scope="col">Pick research</th>
						</tr>
					</thead>
				<tbody>
			<?php foreach ($researches as $key => $research) {
			?>
			<tr>
				<th scope="row">
					<?php echo $research['name'];?>
				</th>
				<td data-title="Effect">
					<?php 
						$current = get_user_meta($user_ID, 'level_'.$key,true);
						
						if($research['maxlevel'] != $current){
						$level = 'level'.($current+1);
						echo $research[$level];}
						else{
							echo '<strong>Maximum level reached.</strong>'; 
						}
						?>
				</td>
				<td data-title="Time">
					<?php echo $research['duration']*$research_reduce;?> hours
				</td>
			
				<td data-title="Pick research">
					<?php 
						
						if($research['maxlevel'] != $current){
						$level = 'level'.($current+1);
						echo "<input type='radio' name='research' required value='$key'>";}
						
						?>
				</td>
			</tr>
			
			
			<?php }?>
				</tbody>
		</table>
		
		<input type="submit" value="Research" class="">
		<div class="footer_continue">
		<input type="submit" value="Research" class="">
		</div>
		</div><!-- end container div -->
		
		
		
		</form>
		<?php else:?>
		
		<?php 			
					/* Get researches for user */
					$args = array(
							'posts_per_page'   => 1,
							'author'	   => $user_ID,
							'post_type'        => 'research',
							);
							$researches_in_progress = get_posts( $args ); ?>
		
		
		
		
		
			<div class="container2">
				
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Effect</th>
							<th scope="col">Time left</th>
		
						</tr>
					</thead>
				<tbody>
			<?php foreach ($researches as $key => $research) {
			?>
			<tr>
				<th scope="row">
					<?php echo $research['name'];?>
				</th>
				<td data-title="Effect">
					<?php 
						$current = get_user_meta($user_ID, 'level_'.$key,true);
						
						if($research['maxlevel'] != $current){
						$level = 'level'.($current+1);
						echo $research[$level];}
						else{
							echo '<strong>Maximum level reached.</strong>'; 
						}
						?>
				</td>
				
					<?php 
						
						
						$timestamp = strtotime(date('Y-m-d H:i:s'));
					
							$researchhours = $research['duration']*3600*$research_reduce;
							foreach ($researches_in_progress as $research) {
								
								if($key == $research->post_content){
								$researchtime_left = $research->post_title-$timestamp;
								$progress = ($researchhours-$researchtime_left)/$researchhours*100;
								
								?><td data-title="Time">
								<?php 
									if($researchtime_left > 0){
									echo '<span id="countdown_time"></span>';
									}?>
									
								</td>
								<?php
								
								}if($research_queued == $key){
									echo '<td><strong>Queued</strong></td>';}else{
									echo '<td>&nbsp;</td>';
								}}
						
						
						
						
					?>
				
			
			
			</tr>
			<script>
				 var
    diff = <?php echo $researchtime_left*1000;?>;

function updateETime() {

  function pad(num) {
    return num > 9 ? num : '0'+num;
  };


    days = Math.floor( diff / (1000*60*60*24) ),
    hours = Math.floor( diff / (1000*60*60) ),
    mins = Math.floor( diff / (1000*60) ),
    secs = Math.floor( diff / 1000 ),

    dd = days,
    hh = hours - days * 24,
    mm = mins - hours * 60,
    ss = secs - mins * 60;

    document.getElementById("countdown_time")
        .innerHTML =
            pad(hh) + ':' + //' hours ' +
            pad(mm) + ':' + //' minutes ' +
            pad(ss) ; //+ ' seconds' ;
    
    diff -= 142.85714285714285;

}
setInterval(updateETime, 1000 );
		   	</script>
			
			<?php }?>
				</tbody>
		</table>
		</div><!-- end container div -->
		<?php if(empty($research_queued) || $research_queued != 0):?>
		<center><h2>Queue your next research at a cost of 30 turns<h2></center>
		
		<form class="form" action="<?php echo home_url() ?>/queue_research.php" name="" id="queue" method="post">
				
				
			<div class="container2">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Effect</th>
							<th scope="col">Time</th>
							<th scope="col">Pick research</th>
						</tr>
					</thead>
				<tbody>
			<?php foreach ($researches as $key => $research) {
			?>
			<tr>
				<th scope="row">
					<?php echo $research['name'];?>
				</th>
				<td data-title="Effect">
					<?php 
						$current = get_user_meta($user_ID, 'level_'.$key,true);
						
						$queued = get_user_meta($user_ID, 'queued_research',true);
						$in_progress = get_user_meta($user_ID, 'research_in_progress',true);
					
						$max = false;
						
						$extra_level = 0;
						
						if($in_progress == $key && $research['maxlevel'] == $current+1){
						
						$max = true;
						echo '<strong>Maximum level reached.</strong>'; }
						else{
							if($in_progress == $key){
								$extra_level = 1;
							}
							$level = 'level'.($current+1+$extra_level);
							if($research['maxlevel'] < $current){
							echo $research[$level];
							}else{
							if(array_key_exists($level,$researches[$key])){echo $research[$level];}
							}
						}
						
						
						if($research['maxlevel'] == $current){$max = true;
							echo '<strong>Maximum level reached.</strong>'; 
						}
						
						
						
						?>
				</td>
				<td data-title="Time">
					<?php if($max == false){ echo $research['duration']*$research_reduce.' hours';}?>
				</td>
			
				<td data-title="Pick research">
					<?php 
						
						if($max == false){
						if($research['maxlevel'] != $current){
						$level = 'level'.($current+1);
						echo "<input type='radio' name='queue' required value='$key'>";}}
						
						
						?>
				</td>
			</tr>
			
			
			<?php }?>
				</tbody>
		</table>
		
		<input type="submit" value="Queue this research" class="">
		<div class="footer_continue">
			<input type="submit" value="Queue this research" class="">
		</div>
		</div><!-- end container div -->
		
		
		
		</form>
		
		<?php else:?>
		<!--<center><h2>Currently in your queue</h2></center>
		<?php $research = get_user_meta($user_ID, 'queued_research')[0];?>
		<div class="container2">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Effect</th>
							<th scope="col">Starts in</th>
		
						</tr>
					</thead>
				<tbody>
			<tr>
				<th scope="row">
					<?php echo $researches[$research]['name'];?>
				</th>
				<td data-title="Effect">
					<?php 
						$current = get_user_meta($user_ID, 'level_'.$research)[0];
						
						if( $researches[$research]['maxlevel'] != $current){
						$level = 'level'.($current+1);
						echo  $researches[$research][$level];}
						else{
							echo '<strong>Maximum level reached.</strong>'; 
						}
						?>
				</td>
				
				<td data-title="Starts in">
					<?php 
						if($researchtime_left > 0){
						echo date('H:i:s', $researchtime_left);}?>
				</td>
				
			
			
			</tr>
			
			
		
				</tbody>
		</table>

				
		-->
		<?php endif;?>
		
		
		<?php endif;?>
		<?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>