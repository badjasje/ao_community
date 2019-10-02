<?php
$units = Units::get();
include("../../../../../building_array.php");

$clan_defender_id = $defenderData['clan_id_user'][0];
$clan_ID = $userData['clan_id_user'][0];

$spytype = $_POST['spytype'];
$turns = $userData['turns'][0];
$spies = $userData['spy_owned'][0];
$spyplanes = $userData['spyplane_owned'][0];

if(empty($spytype)){
	exit;
}

/* check if user has enough spies or spy planes */
if($spies <= 0 && $spytype == 'spy'){
	$array['status'] = 'Not enough spies';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if($spyplanes <= 0 && $spytype == 'spyplane'){
	$array['status'] = 'Not enough spy planes';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* check if user has enough turns */
if($turns < 1){
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

update_user_meta($userId,'turns',$turns-1);
turn_spread('spying',3);

$sat_status = (isset($defenderData['stealth_sat_status']) ? $defenderData['stealth_sat_status'][0] : 0);
$snipers = (isset($defenderData['snipers_owned']) ? $defenderData['snipers_owned'][0] : 0);
$land_def = $defenderData['land'][0];
$networth_def = $defenderData['networth'][0];

$success = mt_rand(1,110)+($snipers*0.25);

if($sat_status == 'active'){
	$success = 100;
}

$members = get_post_meta($clan_ID,'clan_members',true);

/* enhancing spy */
$enhanceSpy = 0;

$args = array(
    'posts_per_page'   => -1,
    'author__in'	=> $members,
    'orderby'          => 'date',
    'order'            => 'DESC',
    'meta_query'	=> array(
        'relation'		=> 'AND',
        array(
            'key'	 	=> 'spied_id',
            'value'	  	=> $target_id,
            'compare' 	=> '=',
        ),
        array(
            'key'	 	=> 'spy_type',
            'value'	  	=> 'spy',
            'compare' 	=> '=',
        ),
    ),
    'post_type'        => 'spy_rep',
);
$reports = get_posts( $args );

foreach ($reports as $report) {
    $posttime = strtotime($report->post_date);
    if($posttime-$timestamp+900 > 0){
        $enhanceSpy+=1;
    }
}

if($enhanceSpy >= 3){
    $enhanceSpy = 3;
}

/* enhancing plane */
$enhancePlane = 0;
$args = array(
    'posts_per_page'   => -1,
    'author__in'	=> $members,
    'orderby'          => 'date',
    'order'            => 'DESC',
    'meta_query'	=> array(
        'relation'		=> 'AND',
        array(
            'key'	 	=> 'spied_id',
            'value'	  	=> $target_id,
            'compare' 	=> '=',
        ),
        array(
            'key'	 	=> 'spy_type',
            'value'	  	=> 'spyplane',
            'compare' 	=> '=',
        ),
    ),
    'post_type'        => 'spy_rep',
);
$reports = get_posts( $args );

foreach ($reports as $report) {
    $posttime = strtotime($report->post_date);
    if($posttime-$timestamp+900 > 0){
        $enhancePlane+=1;
    }
}

if($enhancePlane >= 3){
    $enhancePlane = 3;
}

if($spytype == 'spy'):?>
	<?php if($success <= 95): ?>
        <?php
        $winner_id = $userId;
        $result = 'success';
        ?>
        <div class="blockHeader">Your spy entered the base of <?php echo get_user_name($target_id);?></div>
        <div class="blockHeader spaceNotice">
            <?php if($enhanceSpy < 3):?>
                Spy report enhanced <?php echo $enhanceSpy;?> times.
            <?php else:?>
                Spy report fully enhanced.
            <?php endif;?>

            <?php if($enhanceSpy < 3):?>
                Re-spy this target within 15 minutes to enhance spy reports</span>
            <?php endif;?>
        </div>
        <div class="row no-gutters fw-row">
            <a class="col-md-4 mainSubmit hoverEffect" href="<?php echo get_the_permalink($clan_defender_id);?>">
                View clan
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-reports/?id=<?=$target_id?>">
                <i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">
                Spy report overview clan
            </a>
        </div>
        <div class="pageSpacer"></div>
        <?php
        $amountArray = array();
        $spy_array = array();
        foreach ($units as $key => $unit) {
            $owned_units = $defenderData[$key.'_owned'][0];
            if($owned_units <= 0){ continue; }
            $amountArray[$unit['normalname']] = $owned_units;
        }
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-6 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-6 celBlock">
                Owned
            </div>
        </div> <!-- //Close Unit row -->
        <?php
        $count=0;
        foreach ($amountArray as $unit => $amount) {
            $count++;
            $rangeDamp = 1 - sqrt(($amount)*1.4)/100;
            if($rangeDamp < 0){
                $rangeDamp = 0.2;
            }

            $displayamount = max(round($amount/(1+(mt_rand(20*$rangeDamp, 30*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(36*$rangeDamp, 72*$rangeDamp)/100)))));

            if($enhanceSpy == 1){
                $displayamount = max(round($amount/(1+(mt_rand(10*$rangeDamp, 20*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(12*$rangeDamp, 36*$rangeDamp)/100)))));
            }
            if($enhanceSpy == 2){
                $displayamount = max(round($amount/(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)))));
            }
            if($enhanceSpy >= 3){
                $displayamount = max(round($amount/(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)))));
            }
            $spy_array[$unit] = $displayamount;
			?>
			<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
			    <div class="col-md-6 celBlock nameBlock sea_heading">
			        <?php echo $unit;?>
			    </div>
			    <div class="col-md-6 celBlock">
				    <span class="columnDataLeft">Owned</span>
					<span class="columnDataRight"><?php echo $displayamount;?></span>
			    </div>
			</div> <!-- //Close Unit row -->
			<?php
			$spy_array['enhance'] = $enhanceSpy;
        }

        $args = array(
            'post_title'    => 'Spy report by '.$userId.' Defender: '.$target_id.' '.$spytype,
            'post_status'   => 'publish',
            'post_type'		=> 'spy_rep',
            'post_author'   => $userId
        );
        $new_event_id = wp_insert_post( $args );

        update_field('spied_id', $target_id, $new_event_id);
        update_field('clan_id_report', $clan_ID, $new_event_id);
        update_field('spy_type', 'spy', $new_event_id);
        update_field('spy_array', $spy_array, $new_event_id);


        update_field('spied_land', $land_def, $new_event_id);
        update_field('spied_nw', $networth_def, $new_event_id);
     else: ?>

        <?php
        $winner_id = $target_id;
        $result = 'failure';
        $spies = get_user_meta( $userId, 'spy_owned', true );
        update_user_meta($userId, 'spy_owned', $spies-1);
        ?>
        <div class="blockHeader">Your spy was caught and killed by <?php echo get_user_name($target_id);?></div>
        <div class="blockHeader spaceNotice"><?php echo $spies-1; if($spies-1 == 1){ echo ' spy';} else { echo ' spies';}?> remaining</div>
        <div class="row no-gutters fw-row">
            <a class="col-md-4 mainSubmit hoverEffect" href="<?php echo get_the_permalink($clan_defender_id);?>">
                View clan
            </a>
            <a class="col-md-4 profileButton bg-2" href="/spy-reports/?id=<?=$target_id?>">
                <i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">
                Spy report overview clan
            </a>
        </div>
        <div class="pageSpacer"></div>
    <?php endif;?>
<?php endif;?>

<?php if($spytype == 'spyplane'):?>
	<?php if($success <= 95):?>
        <?php
        $winner_id = $userId;
        $result = 'success';
        ?>
        <div class="blockHeader">Your spyplane flew over the base of <?php echo get_user_name($target_id);?></div>
        <div class="blockHeader spaceNotice">
            <?php if($enhancePlane < 3):?>
                Spy report enhanced <?php echo $enhancePlane;?> times.
            <?php else:?>
                Spy report fully enhanced.
            <?php endif;?>

            <?php if($enhancePlane < 3):?>
                Re-spy this target within 15 minutes to enhance spy reports</span>
            <?php endif;?>
        </div>
        <div class="row no-gutters fw-row">
            <a class="col-md-4 mainSubmit hoverEffect" href="<?php echo get_the_permalink($clan_defender_id);?>">
                View clan
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-reports/?id=<?=$target_id?>">
                <i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">
                Spy report overview clan
            </a>
        </div>
        <div class="pageSpacer"></div>
        <?php
        $amountArray = array();
        $spy_array = array();

        foreach ($buildings as $key => $unit) {
            $owned_units = $defenderData[$key][0];
            if($owned_units <= 0){ continue; }
            $amountArray[$unit['normalname']] = $owned_units;
        }
        ?>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-6 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-6 celBlock">
                Owned
            </div>
        </div> <!-- //Close Unit row -->
        <?php
        $count=0;
	    foreach ($amountArray as $unit => $amount) {
		    $count++;
		    $rangeDamp = 1 - sqrt(($amount)*1.4)/100;
			if($rangeDamp < 0){
				$rangeDamp = 0.2;
			}
            $displayamount = max(round($amount/(1+(mt_rand(20*$rangeDamp, 30*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(36*$rangeDamp, 72*$rangeDamp)/100)))));

            if($enhanceSpy == 1){
                $displayamount = max(round($amount/(1+(mt_rand(10*$rangeDamp, 20*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(12*$rangeDamp, 36*$rangeDamp)/100)))));
            }
            if($enhanceSpy == 2){
                $displayamount = max(round($amount/(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)))));
            }
            if($enhanceSpy >= 3){
                $displayamount = max(round($amount/(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)))));
            }
            $spy_array[$unit] = $displayamount;
			?>
			<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
			    <div class="col-md-6 celBlock nameBlock sea_heading">
			        <?php echo $unit;?>
			    </div>
			    <div class="col-md-6 celBlock">
				    <span class="columnDataLeft">Owned</span>
					<span class="columnDataRight"><?php echo $displayamount;?></span>
			    </div>
			</div> <!-- //Close Unit row -->
			<?php
			$spy_array['enhance'] = $enhanceSpy;
        }

        $args = array(
            'post_title'    => 'Spy report by '.$userId.' Defender: '.$target_id.' '.$spytype,
            'post_status'   => 'publish',
            'post_type'		=> 'spy_rep',
            'post_author'   => $userId
        );

        $new_event_id = wp_insert_post( $args );
        update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
        update_field('spied_id', $target_id, $new_event_id);
        update_field('clan_id_report', $clan_ID, $new_event_id);
        update_field('spy_type', 'spyplane', $new_event_id);
        update_field('spy_array', $spy_array, $new_event_id);


        update_field('spied_land', $land_def, $new_event_id);
        update_field('spied_nw', $networth_def, $new_event_id);
        ?>
    <?php else:?>
        <?php
        $winner_id = $target_id;
        $result = 'failure';
        $spies = get_user_meta( $userId, 'spyplane_owned', true );
        update_user_meta($userId, 'spyplane_owned', $spies-1);
        ?>
        <div class="blockHeader">Your spyplane was shot down by <?php echo get_user_name($target_id);?></div>
        <div class="blockHeader spaceNotice"><?php echo $spies-1;?> spyplane<?php echo plural_func($spies);?> remaining</div>
        <div class="row no-gutters fw-row">
            <a class="col-md-4 mainSubmit hoverEffect" href="<?php echo get_the_permalink($clan_defender_id);?>">
                View clan
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-reports/?id=<?=$target_id?>">
                <i class="fa fa-binoculars" aria-hidden="true"></i> &nbsp;Spy reports
            </a>
            <a class="col-md-4 mainSubmit hoverEffect" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">
                Spy report overview clan
            </a>
        </div>
        <div class="pageSpacer"></div>
    <?php endif;?>
<?php endif;?>

<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Re-Spy</div>
<?php
/* Create Spy event post */
$args = array(
	'post_title'    => 'Spy attempt by '.$userId.' Defender: '.$target_id.' '.$spytype,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);

$new_event_id = wp_insert_post( $args );

update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
update_field('time_attacked',$timestamp, $new_event_id);

update_field('defender_id',$target_id, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);

update_field('event_spy_type',$spytype, $new_event_id);

update_field('att_total_units_lost',1, $new_event_id);

update_field('winner_id',$winner_id, $new_event_id);
update_field('attacktype','spy', $new_event_id);

$sender_show = (rand(1,100));
$show = 'no';
if($sender_show > 80){
	$show = 'yes';
}
update_field('show_spy_sender',$show, $new_event_id);

$event_count = $defenderData['new_events'][0];
update_user_meta($target_id, 'new_events', $event_count + 1);

$spied = $userData['spied_current_clan'][0];
update_user_meta($userId, 'spied_current_clan', $spied+1);