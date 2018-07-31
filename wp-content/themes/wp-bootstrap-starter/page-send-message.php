<?php
 /*
 * Template Name: Send message
*/
get_header(); 
$backColor = "45, 67, 81";
$receiver_ID = $_GET['id'];
?>

<div class="row pageRow">	
	
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.75-(1/100);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($receiver_ID,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($sender_ID);?></span>
		</div>
	
		<div class="col-md-11 celBlock allUsersNameCol">
			Sending message to <?php echo get_user_name($receiver_ID);?>		
		</div>
</div>
<div class="row fw-row row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-(1/40);?>);">
	<form class="form fw-row" id="message" >
		<div class="col-md-12 loginfield">
			<input type="text" id="title" required placeholder="Enter the subject of your message" name="title"/>
			<input type="text" id="title" hidden value="<?php echo $receiver_ID;?>" name="receiver"/>
			<input type="hidden" name="main_message" value="first">
		</div>
		<div class="col-md-12 loginfield statCol-2">
		<textarea class="fw-row" id="message" required rows="10" name="message" placeholder="Your message..."></textarea>
		</div>
		
		<input class="mainSubmit hoverEffect" type="submit" value="Send">
	</form>
		
</div>

<script>
(function($) {
var request;

$("#message").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();
    if (request) { request.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/message.php",
        type: "post",
        data: serializedData
    });

    request.done(function (response, textStatus, jqXHR){

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
			$('#message').trigger("reset");
			

});	});	
})(jQuery);
</script>

</div> <!-- end .pageRow -->
<?php
get_footer();