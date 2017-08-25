<?php
/*
* Template Name: Buildings NEW DESIGN
*/
$user_ID   = get_current_user_id();
$activeTab = sanitize_text_field($_GET['tab']);

include 'building_array.php';
include 'units_array.php';

$land       = get_user_meta($user_ID, 'land');
$builtland  = get_user_meta($user_ID, 'builtland');
$totalmoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');

$airspace = get_user_meta($user_ID, 'airfield')[0] * 10;
$seaspace = get_user_meta($user_ID, 'shipyard')[0] * 5;
$vehspace = get_user_meta($user_ID, 'warfactory')[0] * 10;
$infspace = get_user_meta($user_ID, 'baracks')[0] * 20;

$EElevel = get_user_meta($user_ID, 'level_engineering_effectiveness')[0];

$startingbonus = get_user_meta($user_ID, 'starting_bonus', true);
$extra_divide  = 0;
if ($startingbonus == 'defensive') {
	$extra_divide = 5;
}


$totalair = 0;
$totalsea = 0;
$totalveh = 0;
$totalinf = 0;
foreach ($units as $key => $order) {
	$units_owned   = get_user_meta($user_ID, $key . '_owned')[0];
	$units_ordered = get_user_meta($user_ID, $key . '_ordered')[0];
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
get_header(); ?>
    <div class="page normal-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
		

				<?php if (get_field('game_status', 'option') != 'Live'): ?>
                    <div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
				<?php else: ?>


                    <div class="notice_message"><span
                                class="rdw-line">Your free land allows you to build <strong><?php echo floor(($land[0] - $builtland[0]) / 20); ?></strong> buildings.</span><span
                                class="rdw-line">

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

			?></span></div>


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


            </div>
        </div>
    </div>
<?php get_footer(); ?>