<?php
 /*
 * Template Name: Explore
 */
$user_ID = get_current_user_ID();
$ownedland = get_user_meta($user_ID, 'land');
$freeland = $ownedland[0]-get_user_meta($user_ID, 'builtland')[0];

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'explore';

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<div class="container">
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
				
				
				
				
	
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
			<?php else:?>
			
			<div class="notice_message">
				<span class="rdw-line">You can currently explore <?php
				
				if(200-((ceil($ownedland[0]*0.002))) < 25){echo '25';}else{echo 200-((ceil($ownedland[0]*0.002)));} ?>
				
				m<sup>2</sup> per turn.</span>
			</div>

			<ul id="explore-tab" class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item <?php echo $activeTab === 'explore' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#explore" href="?tab=explore" role="tab">Explore</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'sell' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#sell" href="?tab=sell" role="tab">Sell land</a>
				</li>
			</ul>


			<div class="tab-content current build_content tabbed-table">
				<div class="tab-pane <?php echo $activeTab === 'explore' ? 'active' : ''; ?>"  id="explore" role="tabpanel">

					<?php if(empty(get_user_meta($user_ID, 'explored_today')[0]) || get_user_meta($user_ID, 'explored_today')[0] == 0):?>
					<center><p>You haven't explored any land today. You can explore 20 000 m<sup>2</sup> </p></center>
					<?php else:?>
					<center><p>You have explored <strong><?php echo number_format(get_user_meta($user_ID, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> today. You can explore an additional <strong><?php echo number_format(20000-get_user_meta($user_ID, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo floor((20000-get_user_meta($user_ID, 'explored_today')[0])/(200-((ceil($ownedland[0]*0.002)))));?> turns)</i></p></center>
					<?php endif;?>

					<form class="form" action="<?php echo home_url() ?>/explore.php" name="" id="explore" method="post">
						<table class="responsive-table">
							<tr>
								<td>
									<center><strong>Enter the amount of turns you wish to explore</strong></center>
								</td>
								<td>
									<input pattern="[0-9]" type="number" class="small_input" type="text" id="turns" name="turns" value=""/>
								</td>
							</tr>
						</table>
						<div class="padded">
							<input type="submit" value="Explore" class="">
						</div>
					</form>
				</div>


				<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active' : ''; ?>"  id="sell" role="tabpanel">

					<?php if(empty(get_user_meta($user_ID, 'land_sold_today')[0]) || get_user_meta($user_ID, 'land_sold_today')[0] == 0):?>
					<center><p>You can sell <strong><?php echo number_format(20000-get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong>. You currently have <strong><?php if($freeland > 0){echo number_format($freeland, 0, ',', ' ');}else{echo '0';} ?> m<sup>2</sup></strong> free land.</p></center>
					<?php else:?>
					<center><p>You have sold <strong><?php echo number_format(get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> today. You can sell an additional <strong><?php echo number_format(20000-get_user_meta($user_ID, 'land_sold_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> You currently have <strong><?php if($freeland > 0){echo number_format($freeland, 0, ',', ' ');}else{echo '0';} ?> m<sup>2</sup></strong> free land</p></center>
					<?php endif;?>
					<form class="form" action="<?php echo home_url() ?>/sell_land.php" name="" id="explore" method="post">
						<table class="responsive-table">
							<tr>
								<td>
									<center><strong><span class="rdw-line-2">Enter the amount of land you wish to sell.</span> <span class="rdw-line-2">1 m<sup>2</sup> has a value of $ 75</span></strong></center>
								</td>
								<td>
									<input pattern="[0-9]" type="number"  class="small_input" type="text" id="land" name="land" value="" placeholder="For example: 2000"/>
								</td>
							</tr>
						</table>

						<div class="padded">
							<input type="submit" value="Sell land" class="">
						</div>
					</form>
				</div>
			</div>
			
			
	</div>
	<?php endif;?>
	<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        history.pushState(null, null, jQuery(event.target).attr('href'));
    });
</script>

<?php get_footer(); ?>