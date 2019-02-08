<?php
 /*
 * Template Name: Attack
*/
get_header();
nocache_headers();
include 'constants.php';

global $userData;
global $userId;

update_user_meta($userId, 'user_lock', 0);
update_user_meta($userId, 'morale_lock', 0);

$networth = $userData['networth'][0];
$status = $userData['status'][0];
$satOwned = $userData['sat_owned'][0];

$attackUserId = sanitize_text_field($_GET['id']);

if ( ! empty($attackUserId)) {
	//count_all_stats($attackUserId);
}

$attackUserData = get_userdata($attackUserId);


$sat_morale = $userData['sat_morale'][0];
$last_attacked = rtrim($userData['last_attacked'][0], ',');
$last_attacked = explode(',',$last_attacked);

$morale = $userData['morale'][0];
$moralepool = $userData['morale_pool'][0];

$satDisabled = 'disabled';
$satDisabledClass = 'btn-disabled';
if($satOwned != 0 || !empty($satOwned) && $satOwned != 'stealths'){
	$satDisabled = '';
	$satDisabledClass = 'btn-general';
}
$low_range = $networth/$ATTACK_RANGE_MULT;
					
$attackRange = '$ '.number_format($low_range, 0, ',', ' ').' and $ '.number_format($networth*$ATTACK_RANGE_MULT, 0, ',', ' ');

$attackUserNW = get_user_meta($attackUserId, 'networth',true);
	if (($attackUserNW > $networth/1.4 && $attackUserNW < $networth*1.4)){	
		$range_msg = '<i class="fa fa-check-circle" aria-hidden="true"></i> Target in range';
	}
	else {
		$range_msg = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Target out of range';
	}

?>
<div class="row pageRow">
<div class="blockHeader">
		You can target provinces with a networth between <?php echo $attackRange;?>
	</div>
	<div class="row row-no-padding fw-row">
		<div class="col-xs-2 col-no-padding">
			<?php echo small_avatar($attackUserId,'attackAvatar');?>
		</div>
		<div class="col-xs-10 col-no-padding" style="flex:100">
			<div class="col-12 attackingRow statCol-2">Attacking <?php echo get_user_name($attackUserId); ?>
			</div>
			<div class="col-12 attackingRow statCol-3"><?php echo $range_msg;?>
			</div>
		</div>
	</div>
	
	
<div id="attackstep" stepcount="1"></div>
<div id="step-1">	
	<?php include('pages/attack/step-1.php'); ?>
</div>
<div id="step-2">	
</div>
<div id="step-3">	
</div>
<div id="attack-result">	
</div>
	
	
<script>
(function($) {
	
<?php /*?>
$('html').keypress(function(e){
	
	if(e.keyCode === 8 || e.keyCode === 46){
		var stepnumber = $('#attackstep').attr( "stepcount");
		e.preventDefault();
		if(stepnumber == 2){
			$('#attackstep').attr( "stepcount",1 );
		 	jQuery( "#step-2").empty();
			jQuery( "#step-1").show();
			jQuery('.pageTitle').html('Attack: Step '+stepnumber-1);
			
		}
		if(stepnumber >= 3){
			$('#attackstep').attr( "stepcount",2 );
		 	jQuery( "#step-3").empty();
		 	jQuery( "#attack-result").empty();
		 	jQuery( "#step-1").hide();
			jQuery( "#step-2").show();
			jQuery('.pageTitle').html('Attack: Step '+stepnumber-1);
			
		}
	 }
});
$(document).on('click','#stepback',function(event){
	var stepnumber = $('#attackstep').attr( "stepcount");
	
		if(stepnumber == 2){
			$('#attackstep').attr( "stepcount",1 );
		 	jQuery( "#step-2").empty();
			jQuery( "#step-1").show();
			jQuery('.pageTitle').html('Attack: Step '+stepnumber-1);
			
		}
		if(stepnumber >= 3){
			$('#attackstep').attr( "stepcount",2 );
		 	jQuery( "#step-3").empty();
		 	jQuery( "#step-1").hide();
			jQuery( "#step-2").show();
			jQuery('.pageTitle').html('Attack: Step '+stepnumber-1);
			
		}
});
<?php */ ?>
	

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#attack").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    //$inputs.prop("disabled", true);

    // Fire off the request to /form.php
    request = $.ajax({
        url: "/attack.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        
       
      
        
      
			if(array.next == false){
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					allow_dismiss: true,
					newest_on_top: true,	
					});
			}
		
			if(array.next == true){
				var request2;
				$( "#step-1" ).hide();
				$('.pageTitle').html('Attack: Step 2');
				$('#attackstep').attr( "stepcount",2 );
				$( "#step-2" ).show();
				
				request2 = $.ajax({
					url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/step-2.php",
					type: "post",
					data: array
    			});

					// Callback handler that will be called on success
				request2.done(function (response2, textStatus, jqXHR){
				
				 
		
				 $( "#step-2" ).append( response2 );
				
				 var request3;

// Bind to the submit event of our form
$("#attack2").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request3) {
        request3.abort();
    }
    // setup some local variables
    var $form = $(this);
	
    // Let's select and cache all the fields
    var $inputs = $form.find("input, button, textarea");

    // Serialize the data in the form
    var serializedData3 = $form.serialize();
    

    request3 = $.ajax({
        url: "/attack2.php",
        type: "post",
        data: serializedData +'&'+ serializedData3 ,
    });

    	// Callback handler that will be called on success
   	 	request3.done(function (response, textStatus, jqXHR){
		
				
				$( "#step-2" ).hide();
				
				jQuery('.pageTitle').html('Attack: Step 3');
				$('#attackstep').attr( "stepcount",3 );
				var request4;
				var finalarray = JSON.parse(response)
				request4 = $.ajax({
					url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/step-3.php",
					type: "post",
					data: finalarray
    			});

					// Callback handler that will be called on success
				request4.done(function (response4, textStatus, jqXHR){
		
					$( "#step-3" ).append( response4 );
					$( "#step-3" ).show();
					
					var attackresult;
					
					$(document).on('click','#attack3',function(event){
					$('.pageLoader, #page-cover').show();
					$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
					var $form = $(this);
					
					$( "#attack-result" ).show();
					
					
					attackresult = $.ajax({
						url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/attack-result.php",
						type: "post",
						data: finalarray
						});
					$( "#step-3" ).empty();
					// Callback handler that will be called on success
					attackresult.done(function (attackresultresponse, textStatus, jqXHR){
						$( "#attack-result" ).empty().append( attackresultresponse );
						
						
						var strikeagain;
						$(document).on('click','#strikeagain',function(strikeevent){
						$( "#strikeagain" ).hide();
						$('.pageLoader, #page-cover').show();
						$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
			
					
						strikeagain = $.ajax({
							url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/attack-result.php",
							type: "post",
							data: finalarray
						});
						
						// Callback handler that will be called on success
						strikeagain.done(function (strikeagainresponse, textStatus, jqXHR){
							
						try {
							json = $.parseJSON(strikeagainresponse);
							console.log(json);
							$.notify({
								message: json.status,
								},{
								type: 'info',
								delay: 5000,
								allow_dismiss: true,
								newest_on_top: true,
							
									});	
									return false;
						} catch (e) {
							$( "#attack-result" ).hide();
							$( "#attack-result" ).empty().append( strikeagainresponse );
							$( "#attack-result" ).show();
						}
							
							
						
						$( "#strikeagain" ).show();
					});
				});
						
						
						
						
						
				});
			});
		
		});
	});
});


					
	
			});	
			
			
			
	}
    });

  

});
	



})(jQuery);
    </script>		
</div>
<?php
get_footer();