<?php
 /*
 * Template Name: Buildings
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'inbox';

$user_ID = get_current_user_ID();
update_user_meta($user_ID,'new_messages',0);
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
		
			
			
			
		
		<div class="container">
			
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
		
		
		
			
		<div class="container2">
			<table class="responsive-table">
			<thead>
			<tr>
				<th scope="col">Subject</th>
				<th scope="col">From</th>
				<th scope="col">Date</th>
				<th scope="col"></th>
			</tr>
			</thead>
			<tbody>
				
				
				
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
						'value'	  	=> $user_ID,
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
			$parent_ID = get_post_meta($message_ID, 'parent_message_id',true);
			$sender = get_userdata( get_the_author_meta('ID') );
			$receiver_id = get_post_meta($message_ID, 'receiver_id',true);
			$sender_id = get_post_meta($message_ID, 'receiver_id',true);		
			$receiver = get_userdata( $receiver_id );	
			?>
			<tr>
				
				<th class="inbox_title" scope="row">
					<a href="<?php echo get_the_permalink($parent_ID);?>"> <?php 
					
					if (strlen(get_the_title($parent_ID)) > 55) {
					echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
					echo get_the_title($parent_ID);
					}?></a>
				</th>
				
				<td data-title="From">
					<?php if($sender->ID == $user_ID){echo 'Sent by you';}else{?>
				
				<a href="/users/profile/?id=<?php echo $sender->ID;?>"><?php echo $sender->display_name.' (#'.$sender->ID.')';?></a>
				
					<?php }?>
				</td>
				
				<td data-title="Date">
					<?php echo get_the_date('G:i:s | d-m-Y'); ?> 
				</td>
				
				
				
				<td data-title="">
					<strong><?php 
					
					if($receiver_id == $user_ID){
					if(!empty(get_post_meta($message_ID, 'receiver_status')[0])){
					if(get_post_meta($message_ID, 'receiver_status')[0] == 'New'){
						echo '<span style="color:#ff0000;">'.get_post_meta($message_ID, 'receiver_status')[0].'</span>';
						}else{
						echo get_post_meta($message_ID, 'receiver_status')[0];	
						}
						
						
						}}?>
				</td></strong>
			</tr>
			
			<?php endwhile;
						endif; ?>
				
										
					</tbody>
		</table>
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

		</div><!-- end responsive table container -->
		</div><!-- end tab 1 -->
				
				<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 
					
					$wp_query = NULL;
					$wp_query = $temp_query; 
				?>
			
		
		
		<!-- OUTBOX -->
		<div class="tab-pane <?php echo $activeTab === 'outbox' ? 'active' : ''; ?>"  id="outbox" role="tabpanel">
		
		<div class="container2">
			<table class="responsive-table">
			<thead>
			<tr>
				<th scope="col">Subject</th>
				<th scope="col">To</th>
				<th scope="col">Date</th>
		
			</tr>
			</thead>
			<tbody>
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'sub_user_message',
			'meta_query'	=> array(
				'relation'		=> 'OR',
				
					array(
						'key'	  	=> 'sender_id',
						'value'	  	=> $user_ID,
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
			<tr>
				<th class="inbox_title" scope="row">
					<a class="inbox_title" href="<?php echo get_the_permalink($parent_ID);?>"> <?php 
					
					if (strlen(get_the_title($parent_ID)) > 35) {
					echo substr(get_the_title($parent_ID), 0, 35) . '...'; } else {
					echo get_the_title($parent_ID);
					}?></a>
				</th>
				
				<td data-title="To">
					<?php  if($receiver_id[0] == $user_ID){echo 'You';}else{ ?>
					
				<a href="/users/profile/?id=<?php echo $receiver_id;?>"><?php echo $receiver->display_name.' (#'.$receiver_id.')';?></a>
				<?php }?>
				</td>
				
				<td data-title="Date">
					<?php echo $message->post_date; ?> 
				</td>
				
				
			</tr>
			
			<?php }?>
			</tbody>
		</table>
		
		</div><!-- close table container -->
		</div><!-- end tab 2 -->
		</div>
		
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