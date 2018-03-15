<?php
/*
* Template Name: Buildings NEW DESIGN
*/
get_header(); 
$user_ID   = get_current_user_id();
$userData = get_user_meta($user_ID);
$activeTab = sanitize_text_field($_GET['tab']);
$PwrUsage = $userData['power'][0];
$currentWeather = get_field('weather','options');

include 'building_array.php';
include 'units_array.php';

$land       = $userData['land'][0];
$builtland  = $userData['builtland'][0];
$totalMoney = $userData['money'][0];
$totalturns = $userData['turns'][0];

$airspace = $userData['airfield'][0] * 10;
$seaspace = $userData['shipyard'][0] * 5;
$vehspace = $userData['warfactory'][0] * 10;
$infspace = $userData['baracks'][0] * 20;

$EElevel = $userData['level_engineering_effectiveness'][0];

$startingbonus = $userData['starting_bonus'][0];
$extra_divide  = 0;
if ($startingbonus == 'defensive') {
	$extra_divide = 5;
}


$totalair = 0;
$totalsea = 0;
$totalveh = 0;
$totalinf = 0;
foreach ($units as $key => $order) {
	$units_owned   = $userData[$key.'_owned'][0];
	$units_ordered = $userData[$key.'_ordered'][0];
	$unittype      = $units[$key]['type'];
	if ($unittype == 'air') {
		$totalair += $units_ordered + $units_owned;
	}

	if ($unittype == 'sea') {
		$totalsea += $units_ordered + $units_owned;
	}

	if ($unittype == 'inf') {
		$totalinf += $units_ordered + $units_owned;
	}

	if ($unittype == 'veh') {
		$totalveh += $units_ordered + $units_owned;
	}
}
?>
    <div class="page normal-page">
    <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
		

				<?php if (get_field('game_status', 'option') != 'Live'): ?>
                    <div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
				<?php else: ?>


<div class="notice_message">
	<span class="rdw-line">

	<?php if ($EElevel == 0 || empty($EElevel)) {
		$buildings_per_turn = 5 + $extra_divide;
		echo 'You can currently build <strong>' . $buildings_per_turn . '</strong> buildings per turn.';
		$turns_multiplier = 5 + $extra_divide;
	}

	if ($EElevel == 1) {
		$buildings_per_turn = 10 + $extra_divide;
		echo 'You can currently build <strong>' . $buildings_per_turn . '</strong> buildings per turn.';
		$turns_multiplier = 10 + $extra_divide;
	}
	if ($EElevel == 2) {
		$buildings_per_turn = 15 + $extra_divide;
		echo 'You can currently build <strong>' . $buildings_per_turn . '</strong> buildings per turn.';
		$turns_multiplier = 15 + $extra_divide;
	}

	?></span>
	<span class="rdw-line">
		<?php if($PwrUsage > 100):?>
			<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
		<?php endif;?>
		Power usage: <?php echo number_format($PwrUsage, 0, ',', ' ');?>% 
	</span>
	</div>


                    <style>
                        .tab-content {
                            display: block;
                        }

                        table {
                            border: none;
                        }

                        .responsive-table tbody tr {
                            border: 0px;
                        }
                    </style>
                <br/>
                    <!-- Nav tabs -->
                    <ul id="myTab" class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item <?php echo $activeTab === 'build' ? 'active' : ''; ?>">
                            <a class="nav-link" data-toggle="tab" data-target="#build" href="?tab=build" role="tab">Build</a>
                        </li>
                        <li class="nav-item <?php echo $activeTab === 'demolish' ? 'active' : ''; ?>">
                            <a class="nav-link" data-toggle="tab" data-target="#demolish" href="?tab=demolish"
                               role="tab">Demolish</a>
                        </li>
                    </ul>

                    <script>
                        jQuery(document).on('shown.bs.tab', function (event) {
                            history.pushState(null, null, jQuery(event.target).attr('href'));
                        });
                    </script>


                    <!-- Tab panes -->
                    <div class="tab-content build_content">
						<?php include 'pages/buildings/build.php'; ?>

						<?php include 'pages/buildings/demolish.php'; ?>
						
					
                    </div>

				<?php endif; ?>
				<?php session_unset(); ?>
<script>
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

jQuery('body').on('change', '.demobds', function() {
		
    var arr = document.getElementsByClassName('demobds');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demototal').value = tot;
    
    var span = document.getElementById('demototal');

while( span.firstChild ) {
    span.removeChild( span.firstChild );
}
span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );


	});
</script>

            </div>
        </div>
    </div>
<?php get_footer(); ?>