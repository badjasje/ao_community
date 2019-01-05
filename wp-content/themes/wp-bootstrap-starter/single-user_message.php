<?php
get_header(); 
global $userId;
$backColor = "45, 67, 81";
$author_id = $post->post_author; 

$mainmessage_ID = get_the_ID();
$invite_hash = get_post_meta($mainmessage_ID,'invite_hash',true);

$receiver_main 	= get_post_meta($mainmessage_ID, 'receiver_id', true);
$sender_main	= get_post_meta($mainmessage_ID, 'sender_id', true);
$messagearray = array($receiver_main,$sender_main,1);
if(!in_array($userId, $messagearray)){
	wp_redirect(get_permalink(3656));exit;
}
?>

<div class="row pageRow">	

<?php if(empty($invite_hash)):

	
	$repeater = get_field('sub_messages_rep',$mainmessage_ID);
	$noRows = count($repeater);
	$last_row = end($repeater);		
	if($last_row['sender_id_rep'] != $userId){
		update_post_meta($mainmessage_ID, 'general_status', 'Read');
	}
	
	$firstRow = $repeater[0];

	if($firstRow['sender_id_rep'] != $userId){
		$receiver_id = $firstRow['sender_id_rep'];
	}
	$count = 0;
    while ( have_rows('sub_messages_rep') ) : the_row();
    $sender_ID = get_sub_field('sender_id_rep');
	$count++;
	$idAdd = '';
	if($count == $noRows){
		$idAdd = 'id="lastrow"';
	}
    ?>
		
		
<div <?php echo $idAdd;?> class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.75-(1/100);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($sender_ID,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($sender_ID);?></span>
		</div>
	
		<div class="col-md-11 celBlock allUsersNameCol">
			<?php echo get_user_name($sender_ID);?>		
		</div>
</div>
<div class="row fw-row row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-(1/40);?>);">
	
		<div class="col-md-12 celBlock">
			<?php echo str_replace("\r", "<br />", get_sub_field('message_rep'));?>
		</div>
</div>
<div class="pageSpacer"></div>	
	
			
<?php endwhile;?>


<form class="form fw-row" id="message" method="post">
	<input type="hidden" name="main_message" value="<?php echo $mainmessage_ID;?>">
	<input type="hidden" name="receiver" value="<?php echo $receiver_id;?>">
	​<textarea id="message" required rows="10" name="message" class="fw-row" placeholder="Your message..."></textarea>
				<input class="mainSubmit hoverEffect" type="submit" value="Send">
		</form>
		
		
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
				allow_dismiss: true,
				newest_on_top: true,
					});	
			if(array.next == true){
				
				$(".form").prepend(array.newmsg);
			}
			$('#message').trigger("reset");
			

});	});	
})(jQuery);
</script>

<?php endif;?>
		
		
<?php if(!empty($invite_hash)):
	
	$clan_ID = get_post_meta($mainmessage_ID,'clan_id_invited',true);
	$invite_status = get_post_meta($mainmessage_ID,'invite_status',true);
		
		
		
	if($userId == $author_id):
		$receiver_id = get_post_meta($mainmessage_ID,'receiver_id',true);?>
			
		<div class="blockHeader">You sent this invite to <?php echo get_user_name($receiver_id);?></div>

	<?php endif;?>
	
	<?php if($invite_status == 'accept'):?>
		<div class="blockHeader">You have already used this clan invite</div>
	<?php else:?>
			
		<div class="blockHeader" style="border-bottom:0px;">You've been invited to join <?php echo get_the_title($clan_ID);?> (# <?php echo $clan_ID;?>). If you wish to accept this invite, hit the accept button.</div>
		
		<div class="row fw-row no-gutters profileButtonRow">
			<a class="col-md-6 profileButton invitebutton" style="background-color: rgba(70, 118, 94, 1);" data-target="accept" href="#">
				Accept
			</a>
 	
			<a class="col-md-6 profileButton invitebutton" style="background-color: rgba(70, 118, 94, 0.9);" data-target="decline" href="#">
				Decline
 			</a>
	
		</div>
<script>
(function($) {
	var accept;
	
	$(document).on('click','.invitebutton',function(){
	
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
	var target = $(this).attr('data-target');
		accept = $.ajax({
			url: "/handleinvite.php",
			type: "post",
			data: '&hash=<?php echo $invite_hash;?>&target='+target+'&clan=<?php echo $clan_ID;?>'
		});
						
						// Callback handler that will be called on success
						accept.done(function (response, textStatus, jqXHR){
							console.log(response);
							$('.profileButtonRow').remove();
							$('.blockHeader').html('You have already used this clan invite');
							var response = $.parseJSON(response);
							console.log(response);
							$.notify({
								message: response.status,
								},{
								type: 'info',
								delay: 5000,
								allow_dismiss: true,
								newest_on_top: true,
							});	
						
						
					
					});
				});

})(jQuery);
</script>
		
			<?php endif;?>
<?php endif;?>

		

	
	
	
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();