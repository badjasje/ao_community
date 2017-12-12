<?php
 /*
 * Template Name: Missiles
 */
$user_ID = get_current_user_id(); 
$userId = get_current_user_id(); 
$activeTab = sanitize_text_field($_GET['tab']);
include 'DO_NOT_DELETE.php';
include 'count_functions.php';
$missilespace = get_user_meta($user_ID, 'silo');
$totalMoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');
$totalmissiles = count_missilespace($user_ID);
$tomahawkspace = get_user_meta($user_ID, 'submarine_owned',true)*2;
$missileAccLevel = get_user_meta($user_ID, 'level_missile_accuracy',true);
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
			
			<div class="notice_message">
				<span class="rdw-line">Building one missile costs 5 turns.</span>
				<span class="rdw-line">Selling a missile returns 75% of the original price.</span>
			</div><br/>
			
			
			
			
			 <style>
                        .tab-content {
                            display: block;
                        }

                        table {
                            border: none;
                        }

                        .responsive-table tbody tr {
                            border: 0px;
                        }
                    </style>
                <br/>
                    <!-- Nav tabs -->
                    <ul id="missiles-tab" class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item <?php echo $activeTab === 'buy' ? 'active' : ''; ?>">
                            <a class="nav-link" data-toggle="tab" data-target="#buy" href="?tab=buy" role="tab">Buy</a>
                        </li>
                        <li class="nav-item <?php echo $activeTab === 'sell' ? 'active' : ''; ?>">
                            <a class="nav-link" data-toggle="tab" data-target="#sell" href="?tab=sell" role="tab">Sell</a>
                        </li>
                    </ul>

               


                    <!-- Tab panes -->
                    <div class="tab-content build_content">
						<?php include 'pages/missiles/buy.php'; ?>

						<?php include 'pages/missiles/sell.php'; ?>
                    </div>
			
<script>	                   
// Set total number of units value
jQuery('body').on('change', '.buyunits', function() {

var arr = document.getElementsByClassName('buyunits');
var tot=0;
for(var i=0;i<arr.length;i++){
if(parseInt(arr[i].value))
tot += parseInt(arr[i].value);
}
document.getElementById('total').value = tot;

var span = document.getElementById('total');

while( span.firstChild ) {
span.removeChild( span.firstChild );
}

span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );

});
jQuery(document).on('shown.bs.tab', function (event) {
history.pushState(null, null, jQuery(event.target).attr('href'));
});
</script>			
			
			
			
			<?php endif;?>
			<?php session_unset(); ?>

         
        </div>
    </div> </div></div>
</div>
<?php get_footer(); ?>