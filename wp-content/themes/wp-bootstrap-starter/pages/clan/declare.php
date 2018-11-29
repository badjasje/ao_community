<?php if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) && $inRange == 'yes' && $canPeace == false):?>

<div class="row fw-row no-gutters">
<?php
$optoutstatus=get_post_meta($clan_id,'optout_status',true);
$user_clan_ID= get_user_meta($declarer_ID, 'clan_id_user',true);
$useroptoutstatus=get_post_meta($user_clan_ID, 'optout_status',true);
?>
<br/><br/>
 	<div class="col-md-6">
	 	<?php if (in_array($clan_id, $declared_on)):?>
	 	<button class="mainSubmit" disabled>
		 	<i class="fas fa-fire" aria-hidden="true"></i> &nbsp;You are at war with this clan
		 </button>
		 <?php else:?>
		
                   <?php /*if ($average_OK == "false" && $warcount != 1) {
                     ?>
                    <button class="mainSubmit">
                        <i class="fa fa-fire" aria-hidden="true"></i> &nbsp;Your average NW is too low to declare on this clan</span>
                     <?php
                   }
                   else { */?>

			        <?php if ($optoutstatus == 1 ) { ?>
                                <button class="mainSubmit" disabled>
                                   <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;This clan has opted out of incoming war declarations
                                   </button>
                                

                               <?php }
                                elseif ($useroptoutstatus == "1" ) { ?>
                                  <button class="mainSubmit" disabled>
                                   <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;Your clan has opted out of clan wars this round.
                                   </button>
                                <?
                                
                                }
                                else { ?> 

                                <button class="mainSubmit warDecSubmit" data-toggle="modal" data-target="#declareWarModal">
                                        <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;Declare <?php echo $warText;?>
                                </button>

                                <?php 
                                }
                                ?>

				<!-- Modal -->
<div class="modal fade" id="declareWarModal" tabindex="-1" role="dialog" aria-labelledby="declareWarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Are you sure?</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <label>Declaration message</label>
		  <input placeholder="Max. 50 characters." class="unitInput" type="text" name="dec_msg" maxlength="50" style="border:none;">
      </div>
      <div class="modal-footer">
        <button type="button" class="mainSubmit declarewar" data-dismiss="modal">Declare <?php echo $warText;?></button>
      </div>
    </div>
  </div>
</div>
<script>
(function($) {
	
var declare;
$(document).on('click','.declarewar',function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

	var message = $(".unitInput").val();
	
	declare = $.ajax({
		url: "/declare_war.php",
		type: "post",
		data: '&clan=<?php echo $clan_id;?>&dec_msg='+message
	});

	// Callback handler that will be called on success
	declare.done(function (response, textStatus, jqXHR){


	var json = $.parseJSON(response);
	console.log(json);
	if(json.next == true){
		$('.warDecSubmit').html('<i class="fas fa-fire" aria-hidden="true"></i> You are at war with this clan');
		$(".warDecSubmit").attr("disabled", "disabled");
		$('#declareWarModal').remove();
	}
	$.notify({
		message: json.status,
		},{
		type: 'info',
		delay: 5000,
		template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
					'<i class="fa fa-info-circle"></i> ' +
					'' +
					'<span data-notify="message">{2}</span>' +
					'</div>'
			});	
	});
	
	
});
})(jQuery);
</script>				



<?php //} ?>
		 <?php endif;?>
	</div>

	<div class="col-md-6">
	 	<a href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<button class="mainSubmit">
		 		<i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports
		 	</button>
		 </a>
	</div>

</div>










<?php endif;?>



<?php if(in_array($declarer_ID, $allowed_to_declare) && $canPeace == true):?>
<!-- Declare peace block -->
<div class="row fw-row no-gutters">

 	<div id="peacecontainer" class="col-md-6">
 		<button class="mainSubmit declarePeaceButton" data-toggle="modal" data-target="#declarePeaceModal">
			<i class="fas fa-dove" aria-hidden="true"></i> &nbsp;Declare peace
		</button>
	</div>

	<div class="col-md-6">
	 	<center><a class="btn btn-general mainSubmit" href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports</a></center>
	</div>

</div>



<div class="modal fade" id="declarePeaceModal" tabindex="-1" role="dialog" aria-labelledby="declarePeaceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Are you sure?</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <label>Peace message</label>
		  <input placeholder="Max. 50 characters." class="unitInput" type="text" name="dec_msg" maxlength="50" style="border:none;">
      </div>
      <div class="modal-footer">
        <button type="button" class="mainSubmit peaceDecSubmit" data-dismiss="modal">Declare peace</button>
      </div>
    </div>
  </div>
</div>

<script>
(function($) {
	
var declare;
$(document).on('click','.peaceDecSubmit',function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

	var message = $(".unitInput").val();
	
	declare = $.ajax({
		url: "/declare_peace.php",
		type: "post",
		data: '&war=<?php echo $peaceID;?>&clan=<?php echo $clan_id;?>&dec_msg='+message
	});

	// Callback handler that will be called on success
	declare.done(function (response, textStatus, jqXHR){


	var json = $.parseJSON(response);
	console.log(json);
	if(json.next == true){
		$("#peacecontainer").addClass("col-md-3");
		$( "#peacecontainer" ).removeClass( "col-md-6" )
		$('.declarePeaceButton').remove();
		$('#declarePeaceModal').remove();
	}
	$.notify({
		message: json.status,
		},{
		type: 'info',
		delay: 5000,
		template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
					'<i class="fa fa-info-circle"></i> ' +
					'' +
					'<span data-notify="message">{2}</span>' +
					'</div>'
			});	
	});
	
	
});
})(jQuery);
</script>		



<?php endif;?>




<?php if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) &&  $inRange == 'no' && $canPeace == false):?>

<div class="row fw-row no-gutters">

 	<div class="col-md-6">
 		<button class="mainSubmit">
		 	<i class="fas fa-fire" aria-hidden="true"></i> &nbsp;Currently not in range
		 </button>
	</div>

	<div class="col-md-6">
	 	<a href="/spy-report-overview/?id=<?php echo $clan_id;?>">
		 	<button class="mainSubmit">
		 		<i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports
		 	</button>
		 </a>
	</div>

</div>
<?php endif;?>


<?php if(!in_array($declarer_ID, $allowed_to_declare) || array_key_exists($clan_id, $cooldownlist)):?>

<div class="row fw-row no-gutters">
	<div class="col-md-3 ">
	</div>

	<div class="col-md-6">
		<a href="/spy-report-overview/?id=<?php echo $clan_id;?>">
			<button class="mainSubmit">
		 		<i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports
			</button>
		 </a>
	</div>

	<div class="col-md-3 ">
	</div>

</div>

<?php endif;?>
