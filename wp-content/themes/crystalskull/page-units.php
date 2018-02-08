<?php
 /*
 * Template Name: Units
 */
get_header(); 
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'air';


$userId = get_current_user_id();
$userData = get_user_meta($userId);
include 'units_array.php';
include 'count_functions.php';
$airspace = $userData['airfield'][0];
$seaspace = $userData['shipyard'][0];
$vehspace = $userData['warfactory'][0];
$infspace = $userData['baracks'][0];
$totalmoney = $userData['money'][0];
$totalturns = $userData['turns'][0];

$spies = $userData['spy_owned'][0];
$spies_ordered = $userData['spy_ordered'][0];
$thiefs = $userData['thief_owned'][0];
$thiefs_ordered = $userData['thief_ordered'][0];
$planes = $userData['spyplane_owned'][0];
$planes_ordered = $userData['spyplane_ordered'][0];
$sniper = $userData['sniper_owned'][0];
$sniper_ordered = $userData['sniper_ordered'][0];

$commandcenter = $userData['command_centre'][0];
$ccspace = ($commandcenter*5)-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;

$totalMoney = $userData['money'][0];
?>
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
	        
             <div class="notice_message"><span class="rdw-line">You can build units using turns. Your units will arrive immediately.</span>
			<span class="rdw-line">Per turn you can build 10 air units, 10 vehicles, 5 sea units or 20 infantry</span>
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
			
			
			
			<form class="form" action="<?php echo home_url() ?>/turnbuild.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />

				<div class="tab-content current build_content tabbed-table">
				<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">
					
					<?php include 'pages/units/air.php'; ?>
					
				</div>

				<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">
				
					<?php include 'pages/units/sea.php'; ?>
				
				</div>


				<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">
				
					<?php include 'pages/units/veh.php'; ?>
				
				</div>

				<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">
				
					<?php include 'pages/units/inf.php'; ?>
				
				</div>
				
					
					<div class="col-md-12 totalsField">
				
						<div class="col-md-3">
							Number of units: <span id="total">0</span>
						</div>
						<div class="col-md-3">
							Total cost: $ <span id="order_total">0</span>
						</div>
						<div class="col-md-3">
							Turns required: <span id="turn_total">0</span>
						</div>
						<div class="col-md-3">
							Added networth: $ <span id="networth_total">0</span>
						</div>
	
					</div>

					
					<input type="submit" value="Turn buy units" class="">
					<div class="footer_continue">
						<input type="submit" value="Turn buy units" class="">
					</div>
					
				</div>
           </form>
            </div>
            <?php endif;?>
        </div>
    </div></div>
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
</script>
<?php session_unset(); ?>
<?php get_footer(); ?>