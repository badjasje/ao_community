<?php
$timestamp = current_time('timestamp');
$args = array(
    'posts_per_page'=> 5,
    'meta_key'      => 'user_placed_id',
    'post_status'   => 'publish',
    'meta_value'    => $userId,
    'post_type'     => 'market_order',
);
$orders = get_posts($args);

$args = array(
    'posts_per_page'   => 5,
    'orderby'          => 'date',
    'order'            => 'DESC',
    'post_status'      => 'publish',
    'post_type'        => 'post'
);
$posts = get_posts( $args );

global $wpdb;
$topics = $wpdb->get_results("SELECT * FROM 23zx_forum_topics ORDER BY 23zx_forum_topics.id DESC LIMIT 5");

$inboxargs = array(
	'posts_per_page' => 5, 'post_type' => 'user_message', 'meta_key' => 'last_update_stamp', 'orderby' => 'meta_value', 'order' => 'DESC',
    'meta_query' => array(
        'relation'		=> 'OR',
        array('key' => 'receiver_id', 'value' => $userId, 'compare' => '='),
        array('key' => 'sender_id', 'value'	=> $userId, 'compare' => '='),
    ),
);
$messages = get_posts( $inboxargs );
?>
<div class="statusBlock">
	<div class="row statusTotalRow">

		<div class="col-md-6 col-lg-3 statusRow statCol-4">
			<div class="blockHeader">Latest orders</div>
			<?php foreach ($orders as $order):
				$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
				$order_type = get_post_meta($order->ID,'order_type',true);
				$delivery_time = get_post_meta($order->ID,'delivery_time',true);
				?>
				<div class="row unitRow">
					<div class="col-md-4 celBlock nameBlock sea_heading">
						<?php echo get_the_title($order->ID);?>
					</div>
					<div class="col-md-4 celBlock">
						<span class="columnDataLeft">Ordered</span>
						<span class="columnDataRight"><?php echo $units_in_this_order;?></span>
					</div>
					<div class="col-md-4 celBlock">
						<span class="columnDataLeft">Time left</span>
						<span class="columnDataRight" data-countdown="<?=($delivery_time-$timestamp)?>"></span>
					</div>
				</div>
			<?php endforeach;?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-3">
			<div class="blockHeader">Latest news</div>
			<?php foreach ($posts as $post): ?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="<?php echo get_the_permalink($post->ID);?>">
							<?php echo $post->post_title;?>
						</a>
					</div>
				</div>
			<?php endforeach;?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-2">
			<div class="blockHeader">Latest forum topics</div>
			<?php foreach ($topics as $topic):?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="/forum/topic/<?php echo $topic->id;?>">
							<?php echo $topic->name;?>
						</a>
					</div>
				</div>
			<?php endforeach;?>
		</div>

		<div class="col-md-6 col-lg-3 statusRow statCol-1">
			<div class="blockHeader">Recent conversations</div>
			<?php foreach ($messages as $message):
				$messageId = $message->ID;
				?>
				<div class="row unitRow">
					<div class="col-md-12 celBlock">
						<a href="<?php echo get_the_permalink($messageId);?>/#lastrow">
							<?php if (strlen(get_the_title($messageId)) > 55) {
								echo substr(get_the_title($messageId), 0, 55) . '...'; } else {
								echo get_the_title($messageId);
							}?>
						</a>
					</div>
				</div>
				<?php
			endforeach;?>
		</div>

	</div> <!-- // End row -->
 </div>
