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
if(!in_array($user_ID, $messagearray)){
	wp_redirect(get_permalink(3656));exit;
}
?>

<div class="row pageRow">	
	
<?php while ( have_posts() ) : the_post(); ?>

<?php if(empty($invite_hash)):

	update_post_meta($mainmessage_ID, 'general_status', 'Read');
				
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'sub_user_message',
		'meta_key'		=> 'parent_message_id',
		'meta_value'	=> $mainmessage_ID
	);
			
	
	$messages = get_posts($args);
		
		foreach ($messages as $message) {
			$sender_ID = get_post_meta($message->ID, 'sender_id');
			$sender = get_userdata( $sender_ID[0] );
			
			$receiver_id = get_post_meta($message->ID, 'receiver_id')[0];
		
			if($receiver_id == $user_ID){
				update_post_meta($message->ID, 'receiver_status', 'Read');
			}
			
			if($sender_ID != $user_ID){
				$sender_ID = $sender->ID;
			}
	
	?>
		
		
<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.75-(1/100);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($sender_ID,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($sender_ID);?></span>
		</div>
	
		<div class="col-md-11 celBlock allUsersNameCol">
			<?php echo get_user_name($sender_ID);?>		
		</div>
</div>
<div class="row fw-row row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-(1/40);?>);">
	
		<div class="col-md-12 celBlock">
			<?php echo str_replace("\r", "<br />", $message->post_content);?>
		</div>
</div>
<div class="pageSpacer"></div>	
	
			
<?php }?>


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
				template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
							'<i class="fa fa-info-circle"></i> ' +
							'' +
							'<span data-notify="message">{2}</span>' +
							'</div>'
					});	
			if(array.next == true){
				location.reload();
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
<?php endif;?>

		
		
<?php endwhile; // end of the loop. ?>
	
	
	
	
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();