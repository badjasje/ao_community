<?php
 /*
 * Template Name: Buildings
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'inbox';

$userId = get_current_user_ID();
update_user_meta($userId,'new_messages',0);
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
		
			
	<?php if(!empty($_SESSION['status'])):?>
			<?php echo alert_notification($_SESSION['status']);?>
	<?php endif; // End empty status check ?>
	
	
	<ul id="inbox-tab" class="nav nav-tabs nav-justified" role="tablist">
		<li class="nav-item <?php echo $activeTab === 'inbox' ? 'active' : ''; ?>">
			<a class="nav-link" data-toggle="tab" data-target="#inbox" href="?tab=inbox" role="tab">Inbox</a>
		</li>
		<li class="nav-item <?php echo $activeTab === 'outbox' ? 'active' : ''; ?>">
			<a class="nav-link" data-toggle="tab" data-target="#outbox" href="?tab=outbox" role="tab">Outbox</a>
		</li>
	</ul>

	<div class="tab-content current build_content tabbed-table">
		
		<div class="tab-pane <?php echo $activeTab === 'inbox' ? 'active' : ''; ?>"  id="inbox" role="tabpanel">
		
		
		
		
			
	<div class="row toplist_block">	
		<div class="row clan_header_row storeDetails-heads">
			<div class="col-md-1"></div>
			<div class="col-md-4"><strong>From</strong></div>
			<div class="col-md-4"><strong>Subject</strong></div>
			<div class="col-md-2"><strong>Date</strong></div>
			<div class="col-md-1"></div>
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
						'key'	 	=> 'receiver_id',
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
			
	<div class="row clan_profile_row2">
		
	
		<div class="col-md-1">
			<?php echo small_avatar($sender->ID,'');?>
		</div>
		
		
		<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
			<?php echo get_user_name($sender->ID);?>		
		</div>
		
		<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
	
			<a href="<?php echo get_the_permalink($parent_ID);?>"> <?php 
					
			if (strlen(get_the_title($parent_ID)) > 55) {
			echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
			echo get_the_title($parent_ID);
			}?></a>

		</div>
		
		
		<div class="col-md-2 clan_column border_bottom_mobile">
			<span class="clan_data_left">Date</span>
			<span class="clan_data_right store-pop-span2">
				<?php echo get_the_date('G:i | d-m-Y'); ?> 
			</span>
		</div>
		
		<div class="col-md-1 clan_column border_bottom_mobile">
			
			<?php 
						
				if($receiver_id == $userId){
					if(!empty(get_post_meta($message_ID, 'receiver_status')[0])){
						if(get_post_meta($message_ID, 'receiver_status')[0] == 'New'){
							echo '<span style="color:#ff0000;">'.get_post_meta($message_ID, 'receiver_status')[0].'</span>';
						}else{
						echo get_post_meta($message_ID, 'receiver_status')[0];	
					}}}?>
		</div>
					
					

			
		
	


</div> <! // Close profile row -- >
			

<?php endwhile; endif; ?>
				
</div>
			<div class="padded">
				<?php
					add_filter('previous_posts_link_attributes', 'previous_post_id');
					function previous_post_id() {
						return 'id="inbox-previous-link"';
					}

					add_filter('next_posts_link_attributes', 'next_post_id');
					function next_post_id() {
						return 'id="inbox-next-link"';
					}
				?>
				<center><?php previous_posts_link('Previous') ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php next_posts_link('Next') ?></center>
			</div>

		
		</div><!-- end tab 1 -->
				
				<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 
					
					$wp_query = NULL;
					$wp_query = $temp_query; 
				?>
			
		
		
		<!-- OUTBOX -->
		<div class="tab-pane <?php echo $activeTab === 'outbox' ? 'active' : ''; ?>"  id="outbox" role="tabpanel">
		
		
		
		<div class="row toplist_block">	
		<div class="row clan_header_row storeDetails-heads">
			<div class="col-md-1"></div>
			<div class="col-md-4"><strong>To</strong></div>
			<div class="col-md-4"><strong>Subject</strong></div>
			<div class="col-md-3"><strong>Date</strong></div>
		</div>
		
		
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'sub_user_message',
			'meta_query'	=> array(
				'relation'		=> 'OR',
				
					array(
						'key'	  	=> 'sender_id',
						'value'	  	=> $userId,
						'compare' 	=> '=',
						),
						
					
					)
				);
			$messages = get_posts($args);
			foreach ($messages as $message) { 
			$parent_ID = get_post_meta($message->ID, 'parent_message_id')[0];
			$sender = get_userdata( $message->post_author );
			$receiver_id = get_post_meta($message->ID, 'receiver_id')[0];
			
			$sender_id = get_post_meta($message->ID, 'receiver_id')[0];		
			$receiver = get_userdata( $receiver_id );	
			?>
			
			
			
			<div class="row clan_profile_row2">
		
	
		<div class="col-md-1">
			<?php echo small_avatar($receiver_id,'');?>
		</div>
		
		
		<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
			<?php echo get_user_name($receiver_id);?>		
		</div>
		
		<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
	
			<a href="<?php echo get_the_permalink($parent_ID);?>"> <?php 
					
			if (strlen(get_the_title($parent_ID)) > 55) {
			echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
			echo get_the_title($parent_ID);
			}?></a>

		</div>
		
		
		<div class="col-md-3 clan_column border_bottom_mobile">
			<span class="clan_data_left">Date</span>
			<span class="clan_data_right store-pop-span2">
				<?php echo get_the_date('G:i | d-m-Y'); ?> 
			</span>
		</div>
		


</div> <! // Close profile row -- >
			
			
	<?php }?>
			</div>
	
		

		</div><!-- end tab 2 -->
	
		
		</div><!-- end tab container -->
		
<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);

        var prevLinkElement = jQuery('#inbox-previous-link');
        var prevHref = prevLinkElement.attr('href');

        if (prevHref) {
            prevLinkElement.attr('href', prevHref.replace(/(\?tab=[a-z]*)$/, currentTab));
        }

        var nextLinkElement = jQuery('#inbox-next-link');
        var nextHref = nextLinkElement.attr('href');

        if (nextHref) {
            console.log(nextHref, currentTab);
            nextLinkElement.attr('href', nextHref.replace(/(\?tab=[a-z]*)$/, currentTab));
        }
    });
</script>

<?php get_footer(); ?>