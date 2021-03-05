<?php
/**
 * Template Name: Market Orders
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

//update_user_meta($userId, 'user_lock', 0);

$totalOrder = $totalNetworth = $totalOrderValue = $unstuck_orders = 0;
$orders = $province->getOrders();
foreach($orders as $order) {
    if($order->timeLeft() < 0) {
        if($order->end()) $unstuck_orders++;
    } else {
        $totalOrder += $order->amount();
        $totalNetworth += $order->networth();
        $totalOrderValue += $order->value();
    }
}
?>
<div class="row pageRow">

    <table class="aoTable grey">
        <tr class="unitRow headerRow">
            <th class="nameBlock">Name</th>
            <th>Ordered</th>
            <th>Order value</th>
            <th>Time left</th>
            <th></th>
        </tr>
        <? foreach($orders as $order) { if($order->timeLeft() < 0) continue; ?>
            <tr id="order_<?=$order->get('id')?>" class="unitRow">
                <td class="nameBlock sea_heading"><?=$order->title()?></td>
                <td><?=$order->amount(true)?></td>
                <td><?=$order->value(true)?></td>
                <td><span class="columnDataRight" data-countdown="<?=$order->timeLeft()?>"></span></td>
                <td class="p-0">
                    <? if($order->type() != 'missile') { ?>
                        <form name="cancel" id="cancel">
                            <input type="hidden" id="order" name="order" value="<?=$order->get('id')?>"/>
                            <button onclick="return confirm('Are you sure you want to cancel this order?')" class="cancelButton profileButton" type="submit">Cancel</button>
                        </form>
                    <? } ?>
                </td>
            </tr>
        <? } ?>
    </table>

    <div class="row statusBlockButtons">
        <div class="col-md-4 totalsField statCol-1">Ordered: <?=$totalOrder?></div>
        <div class="col-md-4 totalsField statCol-2">Total order value: <?=Format::money($totalOrderValue)?></div>
        <div class="col-md-4 totalsField statCol-3">Added networth: <?=Format::networth($totalNetworth)?></div>
    </div>
    <? if($unstuck_orders>0) echo $unstuck_orders . ' stuck orders resolved'; ?>

    <script>
    (function($) {
        var request;
        $('form').submit(function( event ) {
            $('.pageLoader, #page-cover').show();
            event.preventDefault();
            if (request) request.abort();
            var serializedData = $(this).serialize();
            request = $.ajax({url: "/cancel_order.php",type: "post",data: serializedData});
            request.done(function (response, textStatus, jqXHR){
                $('.pageLoader, #page-cover').fadeOut( "fast");
                var array = JSON.parse(response);
                $( "#order_"+array.remove ).empty();
                $.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true,});
                $('#money').html(number_format(array.money, 0, ',', ' '));
            });
        });
    })(jQuery);
    </script>

</div>
<?php
get_footer();
