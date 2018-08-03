<?php
	$timestamp = current_time('timestamp');
     $args = array(
	    'posts_per_page'   => 5,
	    'meta_key'      => 'user_placed_id',
	    'post_status'      	=> 'publish',
	    'meta_value'    => $userId,
	    'post_type'        => 'market_order',
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
	
	$args = array(
        'posts_per_page'   => 5,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'sub_user_message',
        'meta_query'    => array(
            'relation'      => 'OR',
                array(
                    'key'       => 'receiver_id',
                    'value'     => $userId,
                    'compare'   => '=',
                    ),                                                                                                                        
				)
			);
	$messages = get_posts( $args ); 
    ?>
   
<div class="statusBlock">
	<div class="row statusTotalRow">
		<div class="col-md-6 col-lg-3 statusRow statCol-4">
		<div class="blockHeader">Latest orders</div>
			
		<?php foreach ($orders as $order):
		   	$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		   	$order_type = get_post_meta($order->ID,'order_type',true);

		   	$userId = $order->post_author;
		   	$delivery_time = get_post_meta($order->ID,'delivery_time',true);
        
    
		   	$timeleft = date('H:i:s', $delivery_time-$timestamp);
			
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
				<span class="columnDataRight"><?php echo $timeleft;?></span>
			</div>
		</div>
			<?php endforeach;?>
		</div>
		
		
		<div class="col-md-6 col-lg-3 statusRow statCol-3">
			<div class="blockHeader">Latest news</div>
				
			<?php foreach ($posts as $post):
			   	$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
			   	$order_type = get_post_meta($order->ID,'order_type',true);
	
			   	$userId = $order->post_author;
			   	$delivery_time = get_post_meta($order->ID,'delivery_time',true);
	        
	    
			   	$timeleft = date('H:i:s', $delivery_time-$timestamp);
				
			?>
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
					<a href="/forum/topic/<?php echo $topic->slug;?>">
						<?php echo $topic->name;?>
					</a>
				</div>
			</div>
			<?php endforeach;?>
		</div>
		
		
		<div class="col-md-6 col-lg-3 statusRow statCol-1">
			<div class="blockHeader">Latest inbox messages</div>
				
			<?php foreach ($messages as $message):
				  $messageId = $message->ID;
				  $messageData = get_post_meta($messageId);
				  $parent_ID = $messageData['parent_message_id'][0];
				  $sender = $messageData['sender_id'][0];
				  $receiver_id = $messageData['receiver_id'][0];
				  $sender_id = $messageData['sender_id'][0];    

				
			?>
			<div class="row unitRow">
				<div class="col-md-12 celBlock">
					<a href="<?php echo get_the_permalink($parent_ID);?>/#lastrow">
						<?php if (strlen(get_the_title($parent_ID)) > 55) {
	                        echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
	                        echo get_the_title($parent_ID);
						}?>
					</a>
				</div>
			</div>
			<?php endforeach;?>
		</div>
			
			
				 	
	 	
	 	</div> <!-- // End row -->
	 	
 </div> 