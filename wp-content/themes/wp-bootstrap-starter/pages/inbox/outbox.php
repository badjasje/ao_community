<div class="tab-pane"  id="outbox" role="tabpanel">
<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock">To</div>
	<div class="col-md-4 celBlock">Subject</div>
	<div class="col-md-2 celBlock">Date</div>
	<div class="col-md-1 celBlock"></div>
</div>



<?php
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$args = array(
	'posts_per_page'   => 20,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'paged'				=>  $custom_query_args['paged'],
	'post_type'        => 'sub_user_message',
	'meta_query'	=> array(
		'relation'		=> 'OR',
			array(
				'key'	 	=> 'sender_id',
				'value'	  	=> $userId,
				'compare' 	=> '=',
				),



			)
		);
// Instantiate custom query
$custom_query = new WP_Query( $args );

// Pagination fix
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $custom_query;

// Output custom query loop
if ( $custom_query->have_posts() ) :
	while ( $custom_query->have_posts() ) :
	$custom_query->the_post();
$message_ID = get_the_id();
$sender = get_userdata( get_the_author_meta('ID') );

$messageData = get_post_meta($message_ID);
$parent_ID = $messageData['parent_message_id'][0];

$receiver_id = $messageData['receiver_id'][0];
$receiver = get_userdata( $receiver_id );
?>

<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($receiver_id,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($user_ID);?></span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol">

		<?php echo get_user_name($receiver_id);?>

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
			<?php echo get_the_date('G:i | d-m-Y'); ?>
		</span>
	</div>

	<div class="col-md-1 celBlock">



	</div>
</div> <!-- //Close profile row -->



<?php endwhile; endif; ?>

</div> <!-- // End .tab-pane -->
