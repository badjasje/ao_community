<?php
 /*
 * Template Name: Market Sell
 */

$activeTab = isset($_GET['tab']) ?  sanitize_text_field($_GET['tab']) : 'air';

include 'units_array.php';

$userId = get_current_user_id();

$specialSold = get_user_meta($userId, 'special_sold_today',true);
$startingBonus = get_user_meta($userId, 'starting_bonus',true);

$unitTypes = [
    'air' => 'Air',
    'sea' => 'Sea',
    'veh' => 'Vehicles',
    'inf' => 'Infantry'
];

$specialUnitsArray = [
    'spyplane',
    'sniper',
    'thief',
    'spy'
];

$marketSellMultiplier = (2.2 * 0.5);

get_header(); ?>
<div class="page normal-page">
    <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                    <?php if (!empty($_SESSION['status'])) : ?>
                        <?php echo alert_notification($_SESSION['status']);?>
                    <?php endif; ?>

                    <?php if (get_field('game_status','option') != 'Live') : ?>
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
                            <li class="nav-item <?php echo $activeTab === 'veh' ? 'active' : ''; ?>">
                                <a class="nav-link" data-toggle="tab" data-target="#veh" href="?tab=veh" role="tab">Vehicles</a>
                            </li>
                            <li class="nav-item <?php echo $activeTab === 'inf' ? 'active' : ''; ?>">
                                <a class="nav-link" data-toggle="tab" data-target="#inf" href="?tab=inf" role="tab">Infantry</a>
                            </li>
                        </ul>

                        <form class="form" action="<?php echo home_url() ?>/sell_units.php" name="" id="market" method="post">
                            <input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
                            <div class="tab-content current build_content tabbed-table">
                                <?php include('pages/market/sell/type.php'); ?>

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
                        </form>
                    </div>
                <?php endif; ?>
                </div>
			<?php session_unset(); ?>
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