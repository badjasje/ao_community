<div class="blockHeader">Clan message <?php echo get_the_title($clanId);?></div>
	
 	<div class="col-md-10 clanMessage">
	 	<div id="savedmsg">
	 		<?php echo wpautop($clanMessage);?>
	 	</div>
	 	<?php if(in_array($userId, $allowed)):?>
	 	<div class="message-editor">
	 		<form class="form" name="new_message" id="clan_message" method="post">
	 			<?php wp_editor($clanMessage,'new_message',$settings);?>
	 			<input class="mainSubmit" type="submit" value="Submit" style="float:left;width:50%">
	 		</form>
	 		<div id="dismiss" class="mainSubmit" style="background-color:#ddd;color:#000;width:50%;float:left;">Dismiss</div>
	 	</div>
	 	<?php endif;?>
 	</div>
 	<div class="col-md-2 clanMessageRow">
	 	<a href="<?php echo get_the_permalink($clanId);?>">
		 	<div class="col-md-12 clanMessageButton hoverEffect">
		 		<i class="fa fa-info-circle"></i> View clan
	 		</div>
	 	</a>
	 	
	 	<a href="<?php echo get_the_permalink(50302);?>">
	 		<div class="col-md-12 clanMessageButton secondButton hoverEffect">
		 		<i class="fa fa-users"></i> Members
	 		</div>
	 	</a>
	 	
	 	<a href="<?php echo get_the_permalink(3842);?>">
	 		<div class="col-md-12 clanMessageButton hoverEffect">
		 		<i class="fa fa-fire"></i> Clan wars
	 		</div>
	 	</a>
	 	<a href="<?php echo get_the_permalink(49609);?>">
	 		<div class="col-md-12 clanMessageButton fourthbutton hoverEffect">
		 		<i class="fas fa-dollar-sign"></i> Send aid
	 		</div>
	 	</a>
 	</div>
 	
<?php if(in_array($userId, $allowed)):?>
	<div class="mainSubmit" id="editmessage"><i class="fas fa-pencil-alt"></i> Edit clan message</div>
<?php endif;?>


<script>
	jQuery( "#editmessage" ).click(function() {
		jQuery('.message-editor').show(750);
		jQuery('#savedmsg').hide(750);
		
	});
	jQuery( "#dismiss" ).click(function() {
		jQuery('.message-editor').hide(750);
		jQuery('#savedmsg').show(750);
	});
	
(function($) {
	

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#clan_message").submit(function(event){
	var content = tinymce.activeEditor.getContent();
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
        url: "/clan_message.php",
        type: "post",
        data: serializedData+'&new_message='+content
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        	
        						
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
								'<i class="fa fa-info-circle"></i> ' +
								'' +
								'<span data-notify="message">{2}</span>' +
								'</div>'
						});	
			
			$('#savedmsg').html(array.clanmessage);
			jQuery('.message-editor').hide(400);
			jQuery('#savedmsg').show(500);
			
		
});	});	
})(jQuery);

	
	
</script>

