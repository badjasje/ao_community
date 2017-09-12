<?php
 /*
 * Template Name: Market Sell
 */

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'air';

$user_ID = get_current_user_id(); 
include 'units_array.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$specialSold = get_user_meta($user_ID, 'special_sold_today',true);
$vehspace = get_user_meta($user_ID, 'warfactory');
$infspace = get_user_meta($user_ID, 'baracks');
$discount_level = get_user_meta($user_ID, 'level_market_discount',true);

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_discount = 1;
if($startingbonus == 'shipping'){
	$shipping_discount = 0.9;
}

if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level == 2){
	$discount = 0.70;
}

/** @TODO: This page contains a lot of duplication, while only a few things are different per tab. Should be refactored */
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
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
				<span class="rdw-line">Selling units returns 50% of the original market price</span>
			</div>

			<ul id="explore-tab" class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item <?php echo $activeTab === 'air' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#air" href="?tab=air" role="tab">Air units</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'sea' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#sea" href="?tab=sea" role="tab">Sea units</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#vehicles" href="?tab=vehicles" role="tab">Vehicles</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#infantry" href="?tab=infantry" role="tab">Infantry</a>
				</li>
			</ul>
			
			
			
			<form class="form" action="<?php echo home_url() ?>/sell_units.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
				<div class="tab-content current build_content tabbed-table">
					<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">
						
						<?php include 'pages/market/sell/air.php'; ?>
							
					</div>

					<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">
					
						<?php include 'pages/market/sell/sea.php'; ?>
					
					</div>


					<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">
				
						<?php include 'pages/market/sell/veh.php'; ?>
				
					</div>

					<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">
					
						<?php include 'pages/market/sell/inf.php'; ?>
					
					</div>
					
				
				<div class="col-md-12 totalsField">
				
					<div class="col-md-4">
						Number of units: <span id="total">0</span>
					</div>
					<div class="col-md-4">
						Total: $ <span id="order_total">0</span>
					</div>
					<div class="col-md-4">
						Networth lost : $ -<span id="networth_total">0</span>
					</div>
	
				</div>
					

				<input type="submit" value="Sell Units" class="">
				
				<div class="footer_continue">
					<input type="submit" value="Sell Units" class="">
				</div>
		
					
					
		
				</div>			
    
				</div>
			</form></div>
			<?php endif;?>
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	
		// Set total number of units value
	jQuery('body').on('change', '.sellunits', function() {
		
    var arr = document.getElementsByClassName('sellunits');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('total').value = tot;
    
    var span = document.getElementById('total');

while( span.firstChild ) {
    span.removeChild( span.firstChild );
}

span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
    
	});
	
	
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
        jQuery('#currentTab').val(currentTab);
    });
</script>

<?php get_footer(); ?>