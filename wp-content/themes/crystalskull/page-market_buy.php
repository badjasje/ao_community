<?php
 /*
 * Template Name: Market Buy
 */

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'air';

$user_ID = get_current_user_id(); 
include 'units_array.php';
include 'count_functions.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$vehspace = get_user_meta($user_ID, 'warfactory');
$infspace = get_user_meta($user_ID, 'baracks');
$totalmoney = get_user_meta($user_ID, 'money');

$spies = get_user_meta($user_ID, 'spy_owned',true);
$spies_ordered = get_user_meta($user_ID, 'spy_ordered',true);
$thiefs = get_user_meta($user_ID, 'thief_owned',true);
$thiefs_ordered = get_user_meta($user_ID, 'thief_ordered',true);
$planes = get_user_meta($user_ID, 'spyplane_owned',true);
$planes_ordered = get_user_meta($user_ID, 'spyplane_ordered',true);
$sniper = get_user_meta($user_ID, 'sniper_owned',true);
$sniper_ordered = get_user_meta($user_ID, 'sniper_ordered',true);

$commandcenter = get_user_meta($user_ID, 'command_centre',true);
$ccspace = ($commandcenter*5)-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;



$discount_level = get_user_meta($user_ID, 'level_market_discount')[0];

if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level >= 2){
	$discount = 0.70;
}

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_discount = 0;
if($startingbonus == 'shipping'){
	$shipping_discount = 0.1;
}

$discount_value = $discount-$shipping_discount;

$enddate = get_field('end_date','option');
$endstamp = strtotime($enddate);
$timestamp = current_time('timestamp');
$timeleft = $endstamp-$timestamp;
$marketclose = $timeleft-86400;
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
			<?php if(!empty($_SESSION['status'])):?>
				<?php echo alert_notification($_SESSION['status']);?>
			<?php endif; // End empty status check ?>
	            
	           
	        <?php if(get_field('game_status','option') != 'Live'):?>
			<div class="marketMessage notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
	        
            <div class="marketMessage notice_message">
	            <span class="rdw-line">The market enables you to buy units without using turns.</span>
				<?php $MSlevel = get_user_meta($user_ID, 'level_shipping_time')[0];

					if($MSlevel == 0 || empty($MSlevel)){
						$hours = 12;	
						}
					
					if($MSlevel == 1){
						$hours = 6;	
						}
					if($MSlevel == 2){
						$hours = 3;	
						}?>
				
<?php if($timeleft<86400):?>
<span class="rdw-line">You cannot order units during the last 24 hours of the round.</span</div></div>	
<?php else:?>
<span class="rdw-line">There is a waiting time of <?php echo $hours;?> hours based on your completed research.</span>
<?php if($timeleft < 172800+86400):?>
<span class="rdw-line-2" style="margin-top:10px;"><span id="countdown_time"></span> left before the market closes.</span>
<?php endif;?>
</div></div>



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

			<form class="form" action="<?php echo home_url() ?>/market2.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
				<div class="tab-content current build_content tabbed-table">

					<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">
					
						<?php include 'pages/market/buy/air.php'; ?>

					</div>

					<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">

						<?php include 'pages/market/buy/sea.php'; ?>

					</div>


					<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">

						<?php include 'pages/market/buy/veh.php'; ?>

					</div>

					<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">
						
						<?php include 'pages/market/buy/inf.php'; ?>
						
					</div>

					<div class="col-md-12 totalsField">
				
							<div class="col-md-4">
								Number of units: <span id="total">0</span>
							</div>
							<div class="col-md-4">
								Total cost: $ <span id="order_total">0</span>
							</div>
							<div class="col-md-4">
								Added networth : $ <span id="networth_total">0</span>
							</div>
	
					</div>



				
						<input type="submit" value="Place order" class="">
						<div class="footer_continue">
							<input type="submit" value="Place order" class="">
						</div>
					



				</div>
			</form>

<?php endif;?><?php endif;?>
			<?php session_unset(); ?>
  
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	
	// Set total number of units value
	jQuery('body').on('change', '.buyunits', function() {
		
    var arr = document.getElementsByClassName('buyunits');
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

<?php if($timeleft < 172800+86400):?>
var
diff = <?php echo $marketclose*1000;?>;

function updateETime() {

function pad(num) {
return num > 9 ? num : '0'+num;
};


days = Math.floor( diff / (1000*60*60*48) ),
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

diff -= 1000;

}
setInterval(updateETime, 1000 );
<?php endif;?>
</script>	 

<?php get_footer(); ?>