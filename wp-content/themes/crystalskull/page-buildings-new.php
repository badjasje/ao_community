<?php
/*
* Template Name: Buildings NEW DESIGN
*/
$user_ID   = get_current_user_id();
$activeTab = sanitize_text_field($_GET['tab']);

include 'building_array.php';
include 'units_array.php';
$newToken = generateFormToken('form1');

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
				<?php if (!empty($_SESSION['status'])): ?>
					<?php if ($_SESSION['status'] == 7): ?>
                        <div class="marketnotice"><?php echo $_SESSION['buildings'];
							if ($_SESSION['buildings'] > 1) {
								echo ' buildings';
							} else {
								echo ' building';
							} ?> built using <?php echo $_SESSION['turns_used'];
							if ($_SESSION['turns_used'] > 1) {
								echo ' turns';
							} else {
								echo ' turn';
							}


							?></div>
					<?php elseif ($_SESSION['status'] == 1): ?>
                        <div class="marketnotice insuffunds">Not enough turns</div>
					<?php elseif ($_SESSION['status'] == 2): ?>
                        <div class="marketnotice insuffunds">Insufficient funds</div>
					<?php elseif ($_SESSION['status'] == 3): ?>
                        <div class="marketnotice insuffunds">You cannot enter negative amounts</div>
					<?php elseif ($_SESSION['status'] == 4): ?>
                        <div class="marketnotice insuffunds">Not enough free land</div>
					<?php elseif ($_SESSION['status'] == 5): ?>
                        <div class="marketnotice insuffunds">Insufficient funds</div>
					<?php elseif ($_SESSION['status'] == 12): ?>
                        <div class="marketnotice insuffunds">Enter a valid number</div>
					<?php elseif ($_SESSION['status'] == 1322): ?>
                        <div class="marketnotice insuffunds">Cannot demolish all your buildings</div>
					<?php elseif ($_SESSION['status'] == 14): ?>
                        <div class="marketnotice">Buildings demolished</div>
					<?php elseif ($_SESSION['status'] == 17): ?>
                        <div class="marketnotice insuffunds">You must sell units occupying the buildings before you can
                            demolish them
                        </div>
					<?php endif; ?><?php endif; ?>


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