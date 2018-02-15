<?php
 /*
 * Template Name: Explore
 */
get_header();
$userId = get_current_user_ID();
$userData = get_user_meta($userId);
$ownedland = $userData['land'][0];
$builtLand = $userData['builtland'][0];
$freeland = $ownedland-$builtLand;
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'explore';
?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
				
				
				
				
	
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
			<?php else:?>
			
			<div class="notice_message">
				<span class="rdw-line">You can currently explore <?php
				
				if(200-((ceil($ownedland*0.002))) < 25){echo '25';}else{echo 200-((ceil($ownedland*0.002)));} ?>
				
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

					<?php include 'pages/explore/explore.php'; ?>
					
				</div>


				<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active' : ''; ?>"  id="sell" role="tabpanel">

					<?php include 'pages/explore/sell.php'; ?>
					
				</div>
			</div>
			
			
	</div>
	<?php endif;?>
	<?php session_unset(); ?>
            
         
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        history.pushState(null, null, jQuery(event.target).attr('href'));
    });
</script>

<?php get_footer(); ?>