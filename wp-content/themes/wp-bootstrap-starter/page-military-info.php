<?php
 /*
 * Template Name: Military info
*/
get_header();
global $userId;
global $userData;
$backColor = "127, 82, 67";
$backColorOrders = "45, 67, 81";
$backColorMissiles = "86, 113, 61";
$backColorBuildings = "126, 100, 68";

$user__ID = $_GET['id'];
$viewerID = $userId;
if(empty($user__ID)){
	wp_redirect(get_permalink(3486));
}
$user = get_userdata($user__ID);
if ( $user === false ) {
	wp_redirect(get_permalink(3486));
}
$userData = get_user_meta($user__ID);
$clan_id = $userData['clan_id_user'][0];

$clanmembers = get_post_meta($clan_id,'clan_members',true);

if(!in_array($viewerID, $clanmembers)){
	wp_redirect(get_permalink(3486));
}

$units = Units::get();
include 'building_array.php';
$missiles = Missiles::get();
$rates = Bank::getAllRates();
$researches = Researches::get();

// Get orders
$args = array(
	'posts_per_page'   => -1,
	'meta_key'		=> 'user_placed_id',
	'meta_value'	=> $user__ID,
	'post_type'        => 'market_order',
);
$orders = get_posts( $args );

$timestamp = current_time('timestamp');

$banklevel = $userData['level_bank_management'][0];
$startingbonus = $userData['starting_bonus'][0];
	$finance_multi = 1;
	if($startingbonus == 'finance'){
		$finance_multi = 1.5;
	}

if($banklevel == 0){
	$extra_interest = 0;
	$max_dep = 250000*$finance_multi;
	$max_tot = 2500000*$finance_multi;
}
if($banklevel == 1){
	$extra_interest = 0.5;
	$max_dep = 350000*$finance_multi;
	$max_tot = 3500000;
}
if($banklevel == 2){
	$extra_interest = 0.75;
	$max_dep = 450000*$finance_multi;
	$max_tot = 4500000;
}
if($banklevel == 3){
	$extra_interest = 1;
	$max_dep = 500000*$finance_multi;
	$max_tot = 5000000*$finance_multi;
}
$total_deposited = 0;
$total_final = 0;
$unlocked = 0;

?>

<div class="row pageRow">

	<div class="blockHeader"><?php echo get_user_name($user__ID);?></div>
    <div class="row fw-row no-gutters">
        <div class="col-md-6">
	        <div class="blockHeader spaceNotice">Current networth <?php echo networth_range($user__ID);?></div>
        </div>
        <div class="col-md-6">
            <select class="blockHeader spaceNotice" onchange="location.href='?id='+this.value;">
                <option disabled selected>Select clanmember</option>
                <?php foreach($clanmembers as $member_id) {
                    $member_data = get_userdata($member_id);
                    echo '<option value="'.$member_id.'">'.$member_data->display_name.'</option>';
                } ?>
            </select>
        </div>
    </div>

	<div class="pageSpacer"></div>

	<!-- owned/ordered unites block -->
	<div class="row fw-row no-gutters">

		<div class="col-md-6">

            <div class="blockHeader">Units</div>

            <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
                <div class="col-md-6 celBlock nameBlock">Name</div>
                <div class="col-md-6 celBlock">Owned (ordered)</div>
            </div>
            <?php
            $count = 0;
            foreach ($units as $key => $unit) {
                $owned = $userData[$key.'_owned'][0];
                $ordered = $userData[$key.'_ordered'][0];
                if($owned > 0 || $ordered > 0){
                    $count++;
                    ?>
                    <div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
                        <div class="col-md-6 celBlock">
                            <span class="columnDataLeft">Name</span>
                            <span class="columnDataRight">
                            <?php echo $unit['normalname'];?>
                            </span>
                        </div>
                        <div class="col-md-6 celBlock">
                            <span class="columnDataLeft">Owned (ordered)</span>
                            <span class="columnDataRight">
                            <?php echo $owned;?> (<?php echo $ordered;?>)
                            </span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
    	</div>

        <div class="col-md-6">

            <!-- on order block -->
	        <div class="blockHeader">Current orders</div>

        	<?php if(count($orders) == 0):?>
	            <div class="blockHeader spaceNotice">No orders to display</div>
	        <?php else:?>

                <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorOrders;?>, 0.75);">
                    <div class="col-md-4 celBlock"><strong>Name</strong></div>
                    <div class="col-md-4 celBlock"><strong>Ordered</strong></div>
                    <div class="col-md-4 celBlock"><strong>Time left</strong></div>
                </div>
                <?php
                $count = 0;
                foreach ($orders as $key => $order) {
                    $orderData = get_post_meta($order->ID);
                    $units_in_this_order = $orderData['amount_ordered'][0];
                    $order_type = $orderData['order_type'][0];

                    $user_ID = $order->post_author;
                    $delivery_time = $orderData['delivery_time'][0];

                    $timeleft = $delivery_time-$timestamp;
                    $timeleft = date('H:i:s', $timeleft);
                    $count++;
                    ?>
                    <div class="row unitRow" style="background-color: rgba(<?php echo $backColorOrders;?>, <?php echo 0.6-($count/50);?>);">
                        <div class="col-md-4 celBlock">
                            <span class="columnDataLeft">Name</span>
                            <span class="columnDataRight">
                                <?php echo get_the_title($order->ID);?>
                            </span>
                        </div>
                        <div class="col-md-4 celBlock">
                            <span class="columnDataLeft">Ordered</span>
                            <span class="columnDataRight">
                                <?php echo $units_in_this_order;?>
                            </span>
                        </div>
                        <div class="col-md-4 celBlock">
                            <span class="columnDataLeft">Time left</span>
                            <span class="columnDataRight">
                                <?php echo $timeleft;?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            <?php endif;?>
        </div>
    </div>

    <!-- owned/ordered missiles block -->
    <div class="row fw-row no-gutters">
        <div class="col-md-12">

            <div class="pageSpacer"></div>
            <div class="blockHeader">Missiles</div>

            <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorMissiles;?>, 0.75);">
                <div class="col-md-6 celBlock"><strong>Name</strong></div>
                <div class="col-md-6 celBlock"><strong>Owned (ordered)</strong></div>
            </div>

            <?php
            $count = 0;
            foreach ($missiles as $key => $missile) {
                $owned = $userData[$key.'_owned'][0];
                $ordered = $userData[$key.'_ordered'][0];
                if($owned > 0 || $ordered > 0){
                    $count++;
                    ?>
                    <div class="row unitRow" style="background-color: rgba(<?php echo $backColorMissiles;?>, <?php echo 0.6-($count/25);?>);">
                        <div class="col-md-6 celBlock">
                            <span class="columnDataLeft">Name</span>
                            <span class="columnDataRight">
                            <?php echo $missile['normalname'];?>
                            </span>
                        </div>
                        <div class="col-md-6 celBlock">
                            <span class="columnDataLeft">Owned (ordered)</span>
                            <span class="columnDataRight">
                            <?php echo $owned;?> (<?php echo $ordered;?>)
                            </span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
	    </div>
    </div>

    <!-- owned buildings block -->
    <div class="pageSpacer"></div>
    <div class="blockHeader">Buildings</div>

    <div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorBuildings;?>, 0.75);">
        <div class="col-md-6 celBlock"><strong>Name</strong></div>
        <div class="col-md-6 celBlock"><strong>Owned</strong></div>
    </div>

    <?php
	$count = 0;
	foreach ($buildings as $key => $building) {
		$owned = $userData[$key][0];
		if($owned > 0){
            $count++;
            ?>
            <div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColorBuildings;?>, <?php echo 0.6-($count/25);?>)">
                <div class="col-md-6 celBlock">
                    <span class="columnDataLeft">Name</span>
                    <span class="columnDataRight">
                    <?php echo $building['normalname'];?>
                    </span>
                </div>
                <div class="col-md-6 celBlock">
                    <span class="columnDataLeft">Owned</span>
                    <span class="columnDataRight">
                    <?php echo $owned;?>
                    </span>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <!-- Bank block -->
    <div class="pageSpacer"></div>
    <div class="blockHeader">Bank</div>
    <div class="row unitRow headerRow fw-row bankHeader" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	    <div class="col-md-3 celBlock nameBlock">
		    Deposited
        </div>
        <div class="col-md-3 celBlock">
            Including interest
        </div>
        <div class="col-md-3 celBlock">
            Release date
        </div>
        <div class="col-md-3 celBlock">
        </div>
    </div> <!-- // Close Unit row -->

    <?php
	$args = array(
		'posts_per_page'   => -1,
		'author'	=> $user__ID,
		'post_type'        => 'deposit',
		'meta_key' => 'release_date',
		'orderby'    => 'meta_value_num',
    );
	$deposits = get_posts( $args );
	$count = 0;
	foreach ($deposits as $deposit) :
		$depositId = $deposit->ID;
		$depositData = get_post_meta($depositId);

		$days = $depositData['days'][0];
		$deposited = $depositData['amount'][0];
		$total_deposited+=$deposited;
		$amount = $depositData['amount'][0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = $depositData['release_date'][0];
		$time_left = $release_stamp-$timestamp;
		$placedStamp = $depositData['deposit_placed'][0];
		$count++;
	    ?>
        <div class="row unitRow fw-row deposit_<?php echo $depositId;?>" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
            <div class="col-md-3 celBlock">
                <span class="columnDataLeft">Deposited</span>
                <span class="columnDataRight">$ <?php echo number_format($deposited, 0, ',', ' '); ?></span>
            </div>

            <div class="col-md-3 celBlock">
                <span class="columnDataLeft">Including interest</span>
                <span class="columnDataRight">$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?></span>
            </div>

            <div class="col-md-3 celBlock">
                <span class="columnDataLeft">Release date</span>
                <span class="columnDataRight"><?php echo date('H:i | d-m-Y', $release_stamp);?></span>
            </div>

            <div class="col-md-3 celBlock" style="padding:0px;">
            </div>
        </div>
    <?php endforeach;?>

    <!-- Research block -->
    <div class="pageSpacer"></div>
    <div class="blockHeader">Research</div>

    <div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorOrders;?>, 0.75);">
        <div class="col-md-6 celBlock nameBlock">Name</div>
        <div class="col-md-6 celBlock">Level</div>
    </div>

    <?php
	$count = 0;
	foreach ($researches as $key => $research) { $count++;
		$level = $userData['level_'.$key][0];
        ?>
        <div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColorOrders;?>, <?php echo 0.6-($count/25);?>);">
            <div class="col-md-6 celBlock">
                <span class="columnDataLeft">Name</span>
                <span class="columnDataRight">
                <?php echo $research['name'];?>
                </span>
            </div>
            <div class="col-md-6 celBlock">
                <span class="columnDataLeft">Level</span>
                <span class="columnDataRight">
                <?php echo $level;?>
                </span>
            </div>
        </div>
        <?php
    }
    ?>

</div> <!-- end .pageRow -->
<?php
get_footer();