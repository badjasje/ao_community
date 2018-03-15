<?php
 /*
 * Template Name: Research
 */
include 'research_array.php';
$userId = get_current_user_id();
$userData = get_user_meta($userId);
$research_in_progress = $userData['research_in_progress'][0];
update_user_meta($userId, 'user_lock', 0);
$research_queued = $userData['queued_research'][0];
$timestamp = current_time('timestamp');
$startingbonus = $userData['starting_bonus'][0];


$args = array(
	'posts_per_page'   => -1,
	'author'        	=>  $userId,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'research'
	);
$researchCount = count(get_posts($args));


$research_reduce = 1;

if($startingbonus == 'defensive'){
	$research_reduce = 0.9;
}

$market_discount_bonus = 0;
if ($startingbonus === 'shipping') {
	$market_discount_bonus = 10;
}

$money_bonus = 0;
if ($startingbonus === 'finance') {
	$money_bonus = 10;
}

$researchURL = '/research.php';
$btnText = 'Research';
$selectText = 'Select';

if($researchCount == 1){
	$researchURL = '/queue_research.php';
	$btnText = 'Queue research';	
	$selectText = 'Queue select';
}

get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	           
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
			
			
		
		<?php if(get_field('game_status','option') != 'Live'):?>
		<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
		<?php else:?>
		<div class="notice_message">
			<span class="rdw-line">One research costs 25 turns. Queuing a research costs an additional 5 turns.</span>
			<span class="rdw-line">Every hour of research adds $ 950 networth</span>
			</div><br/>








<div class="row profile_block">
<div class="row researchHeader">
	<div class="col-md-2"><strong>Name</strong></div>
	<div class="col-md-6"><strong>Effect</strong></div>
	<div class="col-md-2"><strong>Time</strong></div>
	<div class="col-md-2"></div>
</div>

<?php foreach ($researches as $key => $research) {
		$inProgress = 0;
		$current = $userData['level_' . $key][0];
		if($key == $research_in_progress){
			$inProgress = 1;
		}
?>
<form class="form" action="<?php echo home_url() ?><?php echo $researchURL;?>" name="" id="research" method="post">
<div class="row research_row">
	<div class="row">
		<div class="col-md-2 researchAlCenter">
			<span class="researchTitle"><?php echo $research['name'];?></span><br/>
			<sup>Current level: <?php echo $current;?></sup>
		</div>
		<div class="col-md-6 researchAlCenter">
			<?php
				$level = 'level' . ($current + 1);
				
				if ($research['maxlevel'] != $current) {
					switch ($key) {
					
					case 'market_discount':
						$md_discount_research = $research[$level.'_value']+$market_discount_bonus;
						echo str_replace('{value}', $md_discount_research, $research[$level]);
					break;
					case 'money_production':
						$money_research = $research[$level.'_value'] * (1 + ($money_bonus / 100));
						echo str_replace('{value}', GameUtil::format_money($money_research), $research[$level]);
					break;
					default:
						echo $research[$level];
					break;
						}
					} else {
						echo '<strong>Maximum level reached.</strong>';
					}
				?>
			<?php if($inProgress == 1):?>
			<br/><strong>In progress<span id="dots"></span></strong>
			
			<script>
				var dots = 0;
    
				jQuery(document).ready(function()
				{
				    setInterval (type, 600);
				});
				
				function type()
				{
				    if(dots < 3)
				    {
				        jQuery('#dots').append('.');
				        dots++;
				    }
				    else
				    {
				        jQuery('#dots').html('');
				        dots = 0;
				    }
				}
			</script>
			
			<?php endif;?>
		</div>
		
		<div class="col-md-2 researchTime researchAlCenter">
			<?php if($research['maxlevel'] != $current):?>
			<span class="hover-tip"  data-toggle="tooltip" data-original-title="$ <?php echo number_format($research['duration']*950, 0, ',', ' ');?> networth added when completing this research" data-placement="bottom">
				<span class="mobileSpan">Time: </span>
				<?php echo $research['duration']*$research_reduce;?> hours <i class="fa fa-info-circle" aria-hidden="true"></i>
			</span>
			<?php endif;?>
		</div>
		
		<?php 
			$researchCount = 0;
			if($research_in_progress == $key){
				$researchCount = 1;
				}
			?>
		
		<div class="col-md-2">
			<?php if($research_queued == '0' || $researchCount == 0):?>
				<?php if($research['maxlevel'] > $current+$researchCount):?>
					<input style="display:none;" type="radio" name="research" id="<?php echo $key;?>" value="<?php echo $key;?>" required >
					<label class="btn btn-general selectResearch" for="<?php echo $key;?>"><?php echo $selectText;?></label>
				<?php endif;?>
			<?php endif;?>
			
		
			<?php if($research_queued == $key):?>
			<center><strong><i class="fa fa-clock-o" aria-hidden="true"></i> Research in queue</strong></center>
			<?php endif;?>
		</div>
	</div>
	<?php if($inProgress == 1):?>
	<?php	$args = array(
			'posts_per_page'   => 1,
			'author'	   => $userId,
			'post_type'        => 'research',
			);
		$researches_in_progress = get_posts( $args );
		$completionTime = $researches_in_progress[0]->post_title;
		$researchID = $researches_in_progress[0]->ID;
		$timeLeft = $completionTime-$timestamp;
		$totaltime = $research['duration']*60*60*$research_reduce;
		$percentage = round((1-($timeLeft/$totaltime))*100);
		$timeLeft = date('H:i:s', $timeLeft);
		?>
	
	
	
	<div class="row progressRow">
		<div class="col-md-12">
			<div style="background-color:#989898;" class="progress progressBarnomargin">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage;?>%;">
				<div style="position:relative;width:200px;">
				<?php echo $percentage;?>% Complete - Time left: <?php echo $timeLeft;?>
				</div>
  				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
</div>
<?php }?>
<?php if($research_queued == '0' || $researchCount == 0):?>
<input type="submit" value="<?php echo $btnText;?>" class="submitBtn">
		<div class="footer_continue">
		<input type="submit" value="<?php echo $btnText;?>" class="submitBtn">
		</div>
<?php endif;?>
</div>
</form>

<script>
	jQuery(document).ready(function () {
	jQuery("#research").submit(function () {
    jQuery(".submitBtn").attr("disabled", true);
    return true;
	});
});
</script>

<?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>