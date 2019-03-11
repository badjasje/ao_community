<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock">From</div>
	<div class="col-md-4 celBlock">Subject</div>
	<div class="col-md-2 celBlock">Date</div>
	<div class="col-md-1 celBlock"></div>
</div>

<?php
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$inboxargs = array(
	'posts_per_page'   => 20,
	'post_type'		=> 'user_message',
	'meta_key' => 'last_update_stamp',
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'meta_query'	=> array(
		'relation'		=> 'OR',
		array(
			'key'	 	=> 'receiver_id',
			'value'	  	=> $userId,
			'compare' 	=> '=',
		),
		array(
			'key'	 	=> 'sender_id',
			'value'	  	=> $userId,
			'compare' 	=> '=',
		),
	),
);





// Instantiate custom query
$custom_query = new WP_Query( $inboxargs );

// Pagination fix
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $custom_query;

// Output custom query loop
if ( $custom_query->have_posts() ) :
	while ( $custom_query->have_posts() ) :
	$custom_query->the_post();
$messageId = get_the_id();
$sender = get_userdata( get_the_author_meta('ID') );

$messageData = get_post_meta($messageId);
$parent_ID = $messageData['parent_message_id'][0];

$receiver_id = $messageData['receiver_id'][0];
$receiver = get_userdata( $receiver_id );
?>

<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($sender->ID,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($user_ID);?></span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol">

		<?php echo get_user_name($sender->ID);?>

	</div>
	<div class="col-md-4 celBlock">
		<span class="columnDataLeft">Subject</span>
		<span class="columnDataRight store-pop-span2">

			<a href="<?php echo get_the_permalink($parent_ID);?>"> <?php

			if (strlen(get_the_title($parent_ID)) > 55) {
			echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
			echo get_the_title($parent_ID);
			}?></a>

		</span>

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Date</span>
		<span class="columnDataRight">
			<?php echo date('H:i | d-m-Y', $messageData['last_update_stamp'][0]);?>
		</span>
	</div>

	<div class="col-md-1 celBlock">
		<?php
			$repeater = get_field('sub_messages_rep',$messageId);
			$last_row = end($repeater);
			if($last_row['sender_id_rep'] != $userId){
				if($messageData['general_status'][0] != 'Read'){
					echo 'New messages';
				}
			}
		?>

	</div>
</div> <!-- //Close profile row -->



<?php endwhile; endif; ?>