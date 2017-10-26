<?php
 /*
 * Template Name: Market Buy
 */

$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'air';

$userId = get_current_user_id();
include 'units_array.php';
include 'count_functions.php';

$totalMoney = get_user_meta($userId, 'money');

// Calculate space for special units.
$spies = get_user_meta($userId, 'spy_owned',true);
$spiesOrdered = get_user_meta($userId, 'spy_ordered',true);
$thieves = get_user_meta($userId, 'thief_owned',true);
$thievesOrdered = get_user_meta($userId, 'thief_ordered',true);
$planes = get_user_meta($userId, 'spyplane_owned',true);
$planesOrdered = get_user_meta($userId, 'spyplane_ordered',true);
$sniper = get_user_meta($userId, 'sniper_owned',true);
$snipersOrdered = get_user_meta($userId, 'sniper_ordered',true);


$commandCenters = get_user_meta($userId, 'command_centre',true);
$space = [
    'air' => get_user_meta($userId, 'airfield', true) * 10,
    'sea' => get_user_meta($userId, 'shipyard', true) * 5,
    'veh' => get_user_meta($userId, 'warfactory', true) * 10,
    'inf' => get_user_meta($userId, 'baracks', true) * 20,
    'special' => ($commandCenters * 5) - $spies - $thieves - $planes - $spiesOrdered - $thievesOrdered - $planesOrdered - $sniper - $snipersOrdered
];

$usedSpace = [
    'air' => count_airspace($userId),
    'sea' => count_seaspace($userId),
    'veh' => count_vehspace($userId),
    'inf' => count_infspace($userId),
];

$discountLevel = get_user_meta($userId, 'level_market_discount')[0];

$discount = 1.0;

if($discountLevel == 1){
	$discount = $discount - 0.15;
} elseif($discountLevel >= 2){
	$discount = $discount - 0.4;
}
$startingBonus = get_user_meta($userId, 'starting_bonus',true);
if($startingBonus == 'shipping'){
    $discount = $discount - 0.1;
}

$endDate = get_field('end_date','option');
$endStamp = strtotime($endDate);
$timestamp = current_time('timestamp');
$timeLeft = $endStamp-$timestamp;
$marketClose = $timeLeft - 86400;

$specialUnits = [
    'spy',
    'thief',
    'sniper',
    'spyplane'
];

$unitTypes = [
    'air' => 'Air',
    'sea' => 'Sea',
    'veh' => 'Vehicles',
    'inf' => 'Infantry'
];

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
                    <?php $marketShippingLevel = get_user_meta($userId, 'level_shipping_time')[0];
                        if($marketShippingLevel == 1){
                            $hours = 6;
                        } elseif($marketShippingLevel == 2){
                            $hours = 3;
                        } else {
                            $hours = 12;
                        }
                    ?>
            <?php if($timeLeft<86400):?>
                <span class="rdw-line">You cannot order units during the last 24 hours of the round.</span</div></div>
            <?php else:?>
                <span class="rdw-line">There is a waiting time of <?php echo $hours;?> hours based on your completed research.</span>
            <?php if($timeLeft < 172800 + 86400):?>
                <span class="rdw-line-2" style="margin-top:10px;"><span id="countdown_time"></span> left before the market closes.</span>
            <?php endif;?>
        </div>
    </div>

    <ul id="explore-tab" class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item <?php echo $activeTab === 'air' ? 'active' : ''; ?>">
            <a class="nav-link" data-toggle="tab" data-target="#air" href="?tab=air" role="tab">Air units</a>
        </li>
        <li class="nav-item <?php echo $activeTab === 'sea' ? 'active' : ''; ?>">
            <a class="nav-link" data-toggle="tab" data-target="#sea" href="?tab=sea" role="tab">Sea units</a>
        </li>
        <li class="nav-item <?php echo $activeTab === 'veh' ? 'active' : ''; ?>">
            <a class="nav-link" data-toggle="tab" data-target="#veh" href="?tab=veh" role="tab">Vehicles</a>
        </li>
        <li class="nav-item <?php echo $activeTab === 'inf' ? 'active' : ''; ?>">
            <a class="nav-link" data-toggle="tab" data-target="#inf" href="?tab=inf" role="tab">Infantry</a>
        </li>
    </ul>

    <form class="form" action="<?php echo home_url() ?>/market2.php" name="" id="market" method="post">
        <input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
        <div class="tab-content current build_content tabbed-table">

            <?php include('pages/market/buy/type.php'); ?>

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

    <?php endif;?>
<?php endif;?>
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

    <?php if($timeLeft < 172800+86400):?>
        var diff = <?php echo $marketClose*1000;?>;

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

            document.getElementById("countdown_time").innerHTML =
                pad(hh) + ':' + //' hours ' +
                pad(mm) + ':' + //' minutes ' +
                pad(ss) ; //+ ' seconds' ;

            diff -= 1000;
        }
        setInterval(updateETime, 1000 );
    <?php endif;?>
</script>	 

<?php get_footer(); ?>