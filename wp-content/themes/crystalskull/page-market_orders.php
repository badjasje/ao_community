<?php
 /*
 * Template Name: Market orders
 */
$userId = get_current_user_ID();
update_user_meta($userId, 'user_lock', 0);
include 'units_array.php';
include 'missiles_array.php';
get_header(); ?>
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
                    <?php if(get_field('game_status','option') != 'Live'):?>
                        <div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
                    <?php else:?>
			
			
                    <div class="notice_message">
                        <span class="rdw-line">Missile orders cannot be canceled.</span>
                    </div><br/>

                    <div class="spaceNotice">
                        Your current orders. Canceled unit orders return 75% of the initial order value.
                    </div>
                    <div class="row market_block">
                        <div class="row clan_header_row storeDetails-heads">
                            <div class="col-md-3"><strong>Name</strong></div>
                            <div class="col-md-2"><strong>Ordered</strong></div>
                            <div class="col-md-2"><strong>Order value</strong></div>
                            <div class="col-md-2"><strong>Time left</strong></div>
                            <div class="col-md-3"></div>
                        </div>
                    <?php
		            $args = [
                        'posts_per_page'   => -1,
                        'meta_key'		=> 'user_placed_id',
                        'meta_value'	=> $userId,
                        'post_type'        => 'market_order',
                    ];

                    $orders = get_posts( $args );

                    $timestamp = current_time('timestamp');
                    $totalOrder = 0;
                    $totalNetworth = 0;
                    $totalOrderValue = 0;

                    foreach ($orders as $order) {
	                    $orderId = $order->ID;
	                    $orderData = get_post_meta($orderId);
	                    
                        $units_in_this_order = $orderData['amount_ordered'][0];

                        $order_type = $orderData['order_type'][0];
                        $unit_type = $orderData['unit_type'][0];
                        $userId = $order->post_author;
                        $delivery_time = $orderData['delivery_time'][0];

                        $timeLeft = $delivery_time-$timestamp;

                        if($timeLeft >= 0) {
                            $orderValue = $orderData['order_value'][0];

                            if($order_type == 'missile'){
                                $totalNetworth += $orderValue*$missiles[$unit_type]['networth']/100;
                            }

                            if($order_type == 'units'){
                                $totalNetworth += (($units[$unit_type]['price'] *$units[$unit_type]['networth']) / 100) * $units_in_this_order;
                            }
                            $timeLeft = date('H:i:s', $timeLeft);
                            $totalOrder += $units_in_this_order;
                            $totalOrderValue += $orderValue;
                        ?>
		                <div class="row clan_profile_row2">
                            <div class="col-md-3 center_clan_col market_column marketHeader">
                                <?php echo get_the_title($order->ID);?>
                            </div>
	
                            <div class="col-md-2 clan_column border_bottom_mobile">
                                <span class="clan_data_left">Ordered</span>
                                <span class="clan_data_right">
                                    <?php echo $units_in_this_order;?>
                                </span>
                            </div>
	
                            <div class="col-md-2 clan_column border_bottom_mobile">
                                <span class="clan_data_left">Order value</span>
                                <span class="clan_data_right">
                                    $ <?php echo number_format($orderValue, 0, ',', ' ');?>
                                </span>

                            </div>

                            <div class="col-md-2 clan_column">
                                <span class="clan_data_left">Time left</span>
                                <span class="clan_data_right">
                                    <?php echo $timeLeft;?>
                                </span>
                            </div>
                            <div class="col-md-3 clan_column border_bottom_mobile">
                                <?php if($order_type != 'missile'):?>
                                    <form class="form" action="<?php echo home_url() ?>/cancel_order.php" name="" id="cancel" method="post">
                                    <input style="display:none;"type="text" id="order" name="order" value="<?php echo $order->ID;?>"/>
                                    <input onclick="return confirm('Are you sure you want to cancel this order?')" class="btn btn-general submitBtn" type="submit" value="Cancel">
                                    </form>
                                <?php endif;?>
                            </div>
                        </div> <! // Close Unit row -->
                        <?php
                    }
                }
		        ?>
            </div>
        <div class="col-md-12 totalsField">
            <div class="col-md-4">
                Units on order: <?php echo $totalOrder;?>
            </div>
            <div class="col-md-4">
                Total order value: $ <?php echo number_format($totalOrderValue, 0, ',', ' ');?>
            </div>
            <div class="col-md-4">
                Added networth : $ <?php echo number_format($totalNetworth, 0, ',', ' ');?>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>

    <script>
        jQuery(document).ready(function () {
            jQuery("#cancel").submit(function () {
                jQuery(".submitBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
		
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>