<?php
 /*
 * Template Name: Dashboard
 */
 $user_ID = get_current_user_ID();
$new_events = get_user_meta($user_ID, 'new_events');
$new_messages = get_user_meta($user_ID, 'new_messages');
$user_status = get_user_meta($user_ID, 'status');
$nuke_protection_timestamp = get_user_meta($user_ID,'nuke_protection_timestamp');
$clan_ID = get_user_meta($user_ID, 'clan_id_user')[0];
include('startingbonus_array.php');

$level_money_production = get_user_meta($user_ID, 'level_money_production',true);
$sat_level = get_user_meta($user_ID, 'level_satellite_construction',true);
$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

$morale = get_user_meta($user_ID, 'morale',true);
$moralepool = get_user_meta($user_ID, 'morale_pool',true);

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);


	$finance_multi = 1;
	if($startingbonus == 'finance'){
		$finance_multi = 1.1;
	}


if($user_status[0] == 'dead'){
    
    after_death($user_ID);
}
$user = get_userdata($user_ID);
?><?php get_header('home'); ?>
<div class="page normal-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				
				<?php if(!empty($_SESSION['status'])):?><?php if($_SESSION['status'] == 0):?>

				<div class="marketnotice">
					Units ordered
				</div>
				<?php elseif($_SESSION['status'] == 1):?>

				<div class="marketnotice">
					Nuke protection removed.
				</div>
				<?php elseif($_SESSION['status'] == 2):?>

				<div class="marketnotice insuffunds">
					Build more warfactories
				</div>
				<?php elseif($_SESSION['status'] == 3):?>

				<div class="marketnotice insuffunds">
					Build more shipyards
				</div>
				<?php elseif($_SESSION['status'] == 4):?>

				<div class="marketnotice insuffunds">
					Build more baracks
				</div>
				<?php elseif($_SESSION['status'] == 5):?>

				<div class="marketnotice insuffunds">
					Insufficient funds
				</div>
				<?php elseif($_SESSION['status'] == 1337):?>

				<div class="marketnotice">
					Username changed
				</div>
				<?php elseif($_SESSION['status'] == 1231):?>

				<div class="marketnotice">
					Startingbonus picked
				</div>
				<?php elseif($_SESSION['status'] == 20):?>

				<div class="marketnotice">
					Message sent to all users
				</div>
				<?php endif;?><?php endif;?>

				<div class="col-lg-12 col-md-12">
					
			<?php if(get_field('game_status','option') == 'Pause' && $user_ID != 1):?>
			<div class="notice_message"><span class="rdw-line">The round has ended! Expect a new round on the 5th of february.</span></div><br/>
			<?php else:?>
			
			
			<?php
							   	$bonuses = array('offensive','defensive','finance','shipping');
								if(!in_array($startingbonus, $bonuses)):?>
	    
	            <center><h2>Pick a starting bonus</h2></center>
	    <form class="form" action="<?php echo home_url() ?>/startingbonus.php" name="" id="starting_bonus" method="post">	
	       	            
	    <input style="display:none;" type="radio" name="bonustype" id="offensive" value="offensive" >
	    	<label class="startingbonus" for="offensive">
	    		<h3 class="startinghead"><i class="fa fa-fire" aria-hidden="true"></i> Offensive</h3>
	    		Gain twice the land and money during attacks, plus 75 turns.
	    	
	    	</label>
	    
	    <input style="display:none;" type="radio" name="bonustype" id="defensive" value="defensive" >
	    	<label class="startingbonus" for="defensive">
	    		<h3 class="startinghead"><i class="fa fa-shield" aria-hidden="true"></i> Defensive</h3>
	    		Constructing 10 buildings per turn by default (to a maximum of 20 with full research), plus 20% extra defense for all defending units, plus 10% time deduction when researching, plus 3 500 m<sup>2</sup> of land.
	    		</label>
	    
	    <input style="display:none;" type="radio" name="bonustype" id="finance" value="finance" >
	    	<label class="startingbonus" for="finance">
	    		<h3 class="startinghead"><i class="fa fa-usd" aria-hidden="true"></i> Finance</h3>
	    		Hourly income increased by 10%, bank capacity is raised by 50% and $400 000 money.
	    		</label>
	    
	    <input style="display:none;" type="radio" name="bonustype" id="shipping" value="shipping" >
	    	<label class="startingbonus" for="shipping">
				<h3 class="startinghead"><i class="fa fa-truck" aria-hidden="true"></i> Shipping</h2>
	    		Missile orders ship 50% faster, plus ability to choose exact arrival time for units (up to 6 hours delayed), plus 10% default market discount (max 40% with research), 2 500 m<sup>2</sup> land and $250 000 money.
	    		</label>
	    <input type="submit" value="Pick starting bonus" class="">
	    </form>
		<br/>
		<hr/>
		<br/>
	    <?php endif;?>
					
					<?php 
					$args = array(
					'author'        =>  $user_ID, // I could also use $user_ID, right?
					'numberposts'	=> -1,
					'orderby'       =>  'post_date',
					'post_type'		=>	'event_local',
					
					'meta_query'	=> array(
						'relation'		=> 'AND',
						array(
							'key'	 	=> 'attacktype',
							'value'	  	=> array('bonus'),
							'compare' 	=> 'IN',
						),
						),			
					'order'         =>  'ASC' );	
					$bonus_posts = get_posts( $args );
					foreach ($bonus_posts as $bonus) {
					$event_ID = $bonus->ID;
					$money = get_post_meta($event_ID, 'bonus_money', true);
					$turns = get_post_meta($event_ID, 'bonus_turns', true);
					$used = get_post_meta($event_ID, 'bonus_used', true);
					if($used != 'yes'){
					$time = get_post_meta($bonus->ID,'time_attacked', true)+(86400*2);
					$autoreceive = $time - $timestamp;
						
					?>
					<div class="bonus_message">
						You can now receive a clan bonus of $ <?php echo number_format($money, 0, ',', ' '); ?> and 
						<?php echo $turns;?> turns. Auto receive in <?php echo human_time_diff( $time,$timestamp);?>
						
						
						<a class="btn btn-bonus" href="/receive_bonus.php/?id=<?php echo $event_ID;?>">Receive Bonus</a></div><br/>
						
					<?php }}?>
			
			
			<?php if($clan_ID != 0){ ?>
	
			<div class="notice_message">
				<h2 style="color:#fff;"><i class="fa fa-info-circle" aria-hidden="true"></i> Clan Message</h2>
				<?php if(!empty(get_post_meta($clan_ID, 'clan_message')[0])){echo get_post_meta($clan_ID, 'clan_message')[0];}?>
			</div>
			<br/>
		<?php }?>
					
					
					
					
					<table class="responsive-table">
						<thead>
							<tr>
								<th scope="col">Status</th>

								<th scope="col">Points rank</th>

								<th scope="col">Networth rank</th>

								<th scope="col">Power usage</th>

								<th scope="col">Events</th>

								<th scope="col">Inbox</th>

								<th scope="col">Hourly income</th>

								<th scope="col">Morale <sup>(Pool)</sup></th>
								
								<?php if(in_array($startingbonus, $bonuses)):?>
								<th scope="col">Starting bonus</th>
								<?php endif;?>
								
								<?php if(!empty($sat_level) || $sat_level != 0):?>

								<th scope="col">Satellite power</th><?php endif;?>
							</tr>
						</thead>


						<tbody>
							<tr>
								<th scope="row">
									<?php if($user_status[0] =='nukeprotection'):
									                                                                                                                                    $timestamp = strtotime(date('Y-m-d H:i:s'));
									                                                                                                                            
									                                                                                                                                    $timeleft = $nuke_protection_timestamp[0]-$timestamp;
$timer_left = $nuke_protection_timestamp[0]-$timestamp;								                                                                                                                        
									                                                                                                                                
									                                                                                                                                    if($timeleft < 0){
									                                                                                                                                    update_user_meta($user_ID, 'status', 'online');}
									                                                                                                                                    $timeleft = date('H:i:s', $timeleft);
									                                                                                                                                    
									                                                                                                                                    
									                                                                                                                                    
									                                                                                                                                ?>

<center>
	Protection time left: <span id="countdown_time"></span> <br/>
	<?php if($timer_left < 43200):?>
	<a onclick="return confirm('Are you sure you want to remove protection?')" class="btn btn-danger" href="/remove_np.php/?user=<?php echo $user_ID;?>">
  <i class="fa fa-trash-o fa-lg"></i> Remove Protection</a>
	<?php endif;?>
</center>
									
			<script>
				 var
    diff = <?php echo $timer_left*1000;?>;

function updateETime() {

  function pad(num) {
    return num > 9 ? num : '0'+num;
  };


    days = Math.floor( diff / (1000*60*60*24) ),
    hours = Math.floor( diff / (1000*60*60) ),
    mins = Math.floor( diff / (1000*60) ),
    secs = Math.floor( diff / 1000 ),

    dd = days,
    hh = hours - days * 24,
    mm = mins - hours * 60,
    ss = secs - mins * 60;

    document.getElementById("countdown_time")
        .innerHTML =
            pad(hh) + ':' + //' hours ' +
            pad(mm) + ':' + //' minutes ' +
            pad(ss) ; //+ ' seconds' ;
    
    diff -= 1000;

}
setInterval(updateETime, 1000 );
		   	</script>									
									
									<?php elseif($user_status[0] =='online'):
									                                                                                                                                
									                                                                                                                                ?>Current status: Online <?php endif;?>
								</th>

								<td data-title="Points rank"><?php $power_usage = get_user_meta($user_ID, 'points_position');echo number_format($power_usage[0], 0, ',', ' ');?>
								</td>

								<td data-title="Networth rank"><?php $power_usage = get_user_meta($user_ID, 'networth_position');echo number_format($power_usage[0], 0, ',', ' ');?>
								</td>

								<td data-title="Power usage"><?php $power_usage = get_user_meta($user_ID, 'power');echo number_format($power_usage[0], 0, ',', ' ');?>%</td>

								<td data-title="New events">
									<a href="/events/incoming/"><?php if($new_events[0] > 0):?> <span style="color:#ff0000"><?php echo $new_events[0];?></span> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php else:?> <?php echo $new_events[0];?> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?> <?php endif;?></a>
								</td>

								<td data-title="Inbox">
									<a href="/inbox/"><?php if($new_messages[0] > 0):?> <span style="color:#ff0000"><?php echo $new_messages[0];?></span> new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?> <?php else:?> <?php echo $new_messages[0];?> new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?> <?php endif;?></a>
								</td>

								<td data-title="Hourly income">$ <?php if($level_money_production == 0){
								   
								$income = 15000*$finance_multi;
							   		echo number_format($income, 0, ',', ' ');
							                                                                                                                        
							 	}elseif($level_money_production == 1){
								   $income = 25000*$finance_multi;
								   echo number_format($income, 0, ',', ' ');
							    }elseif($level_money_production == 2){
								    $income = 35000*$finance_multi;
							    echo number_format($income, 0, ',', ' ');
							  }
								 ?>
								</td>

								<td data-title="Morale & pool"><?php echo $morale.'% <sup>('.$moralepool.'%)</sup>';?>
								</td>
								<?php if(in_array($startingbonus, $bonuses)):?>
								<td data-title="Starting bonus">
									<?php include('startingbonus_array.php');?>
									
								<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $bonuses[$startingbonus]['description'];?>" data-placement="left">
								<i class="fa <?php echo $bonuses[$startingbonus]['icon'];?>" aria-hidden="true"></i> <?php echo $bonuses[$startingbonus]['name'];?>
								</span>
								</td>
								<?php endif;?>
								
								<?php if(!empty($sat_level) || $sat_level != 0):?>
								
								

								<td data-title="Satellite power"><?php echo $sat_morale;?>%</td><?php endif;?>
							</tr>
						</tbody>
					</table>
				<div class="notice_message">Current round date: 5th of February - 15th of March 2017. <span class="hover-tip"  data-toggle="tooltip" data-original-title="The round will end on the 15th of March 2017, at a random time." data-placement="right">
								<i class="fa fa-info-circle" aria-hidden="true"></i>
								</span></div><br/>
				

				<div class="row">
					<div class="col-md-6">
						<h2>Last 5 market orders</h2>
						<script type="text/javascript">
						      
						  jQuery(document).ready(function(){
						  jQuery('#cancel<?php echo $order->ID;?>').click(function(){
						  jQuery.post("<?php get_site_url(); ?>/cancel_order.php?id=<?php echo $order->ID;?>&user=<?php echo $user_ID;?>",{ajax: true},
						  function(data, status){
						     location.reload();
						   });
						});
						});
						</script>

						<table class="responsive-table">
							<thead>
								<tr>
									<th scope="col">Name</th>

									<th scope="col">Ordered</th>

									<th scope="col">Time left</th>

									
								</tr>
							</thead>


							<tbody>
								<?php   
								    
								        $args = array(
								    'posts_per_page'   => 5,
								    'meta_key'      => 'user_placed_id',
								    'meta_value'    => get_current_user_ID(),
								    'post_type'        => 'market_order',
								    );
								    $units = get_posts( $args ); 

								    $timestamp = strtotime(date('Y-m-d H:i:s'));
								    
								    foreach ($units as $order) {
								        $units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
								        $order_type = get_post_meta($order->ID,'order_type',true);

								        $user_ID = $order->post_author;
								        $delivery_time = get_post_meta($order->ID,'delivery_time',true);
								        
								    
								        $timeleft = $delivery_time-$timestamp;
								        
								        if($timeleft >= 0){
								    
								        $timeleft = date('H:i:s', $timeleft);
								        
								        ?>

								<tr>
									<td data-title="Name"><label><strong><?php echo get_the_title($order->ID);?></strong></label>
									</td>

									<td data-title="Units in order"><label><?php echo $units_in_this_order;?></label>
									</td>

									<td data-title="Time left"><label><?php echo $timeleft;?></label>
									</td>

									
								</tr>
								<?php }}
								        
								        ?>
							</tbody>
						</table>
					</div>


					<div class="col-md-6">
						<h2>Last 5 messages</h2>


						<table class="responsive-table">
							<thead>
								<tr>
									<th scope="col">Subject</th>

									<th scope="col">From</th>

									<th scope="col">Date</th>

									<th scope="col">
									</th>
								</tr>
							</thead>


							<tbody>
								<?php 
								                                                                                                                $custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
								                                                                                                                
								                                                                                                                $args = array(
								                                                                                                                'posts_per_page'   => 5,
								                                                                                                                'orderby'          => 'date',
								                                                                                                                'order'            => 'DESC',
								                                                                                                                'paged'             =>  $custom_query_args['paged'],
								                                                                                                                'post_type'        => 'sub_user_message',
								                                                                                                                'meta_query'    => array(
								                                                                                                                    'relation'      => 'OR',
								                                                                                                                        array(
								                                                                                                                            'key'       => 'receiver_id',
								                                                                                                                            'value'     => $user_ID,
								                                                                                                                            'compare'   => '=',
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
										<a href="<?php echo get_the_permalink($parent_ID);?>"><?php 
										                                                                                                                                                
										                                                                                                                                                if (strlen(get_the_title($parent_ID)) > 55) {
										                                                                                                                                                echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
										                                                                                                                                                echo get_the_title($parent_ID);
										                                                                                                                                                }?></a>
									</th>

									<td data-title="From">
										<?php if($sender->ID == $user_ID){echo 'Sent by you';}else{?><a href="/users/profile/?id=<?php echo $sender->ID;?>"><?php echo $sender->display_name.' (#'.$sender->ID.')';?></a> <?php }?>
									</td>

									<td data-title="Date"><?php echo get_the_date('G:i:s | d-m-Y'); ?>
									</td>

									<td data-title=""><strong><?php 
									                                                                                                                                    
									                                                                                                                                    if($receiver_id == $user_ID){
									                                                                                                                                    if(!empty(get_post_meta($message_ID, 'receiver_status')[0])){
									                                                                                                                                    if(get_post_meta($message_ID, 'receiver_status')[0] == 'New'){
									                                                                                                                                        echo '<span style="color:#ff0000;">'.get_post_meta($message_ID, 'receiver_status')[0].'</span>';
									                                                                                                                                        }else{
									                                                                                                                                        echo get_post_meta($message_ID, 'receiver_status')[0];  
									                                                                                                                                        }
									                                                                                                                                        
									                                                                                                                                        
									                                                                                                                                        }}?></strong>
									</td>
								</tr>
								<?php endwhile;
								                                                                                                                            endif; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 
				                                                                        
				                                                                        $wp_query = NULL;
				                                                                        $wp_query = $temp_query; 
				                                                                    ?>
			
		
		
		
		
		<br/>
		<br/>
		<center>
			<a class="btn btn-general" href="/reset_province.php" onclick="return confirm('Are you sure you want to reset your province?')"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;RESET PROVINCE</a><br>
		</center>
		<?php if(current_user_can('activate_plugins')){ ?><br>
		<br>


		<center>
			<h2>Message all users</h2>
		</center>


		<form action="<?php echo home_url() ?>/message_all_users.php" class="form" id="message" method="post" name="">
			<table style="margin-left:auto;margin-right:auto;max-width:450px;">
				<tr>
					<td>
						<center>
							<input id="title" name="title" placeholder="Subject" style="width:95%;" type="text">
						</center>
					</td>
				</tr>


				<tr>
					<td>
						<center>
							​ 

							<textarea cols="70" id="message" name="message" placeholder="Your message..." rows="10" style="width:95%;"></textarea>
						</center>
					</td>
				</tr>
			</table>


			<center>
				<input type="submit" value="Send message to all">
			</center>
		</form>
		<?php }?>
		<?php endif;?>
		<div class="clear">
		</div>
	</div>
	</div>
	</div>
</div>
<!-- Google Code for Aankoop Faciato Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 956719898;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "LmA1CIvs1lsQmsaZyAM";
var google_conversion_value = 1.00;
var google_conversion_currency = "EUR";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/956719898/?value=1.00&amp;currency_code=EUR&amp;label=LmA1CIvs1lsQmsaZyAM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php session_unset(); ?>
<?php get_footer(); ?>