<?php
/*
* Template Name: Top lists
*/
get_header();

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'provicenw';

?>

<div class="page normal-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="container containerNZ">

					<?php if (get_field('game_status', 'option') != 'Live'): ?>
                        <div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
					<?php endif; ?>

	                <ul id="myTab" class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item <?php echo $activeTab === 'provicenw' ? 'active' : ''; ?>">
                            <a class="nav-link" data-toggle="tab" data-target="#provicenw" href="/toplists" role="tab">Province networth</a>
                        </li>
	                    <li class="nav-item <?php echo $activeTab === 'clanpoints' ? 'active' : ''; ?>">
		                    <a class="nav-link" data-toggle="tab" data-target="#clanpoints" href="/toplists/?tab=clanpoints" role="tab">Clan points</a>
	                    </li>
	                    <li class="nav-item <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>">
		                    <a class="nav-link" data-toggle="tab" data-target="#clannw" href="/toplists/?tab=clannw" role="tab">Clan networth</a>
	                    </li>
		                <li class="nav-item <?php echo $activeTab === 'clanpointstoday' ? 'active' : ''; ?>">
			                <a class="nav-link" data-toggle="tab" data-target="#clanpointstoday" href="/toplists/?tab=clanpointstoday" role="tab">Clan points today</a>
		                </li>
                    </ul>

	                <div class="tab-content current build_content tabbed-table">
						<?php include 'pages/toplist/provice_nw.php'; ?>
						<?php include 'pages/toplist/clan_points.php'; ?>
						<?php include 'pages/toplist/clan_nw.php'; ?>
						<?php include 'pages/toplist/clan_points_today.php'; ?>
	                </div>

	                <script>
                        jQuery(document).on('shown.bs.tab', function (event) {
                            history.pushState(null, null, jQuery(event.target).attr('href'));
                        });
	                </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>