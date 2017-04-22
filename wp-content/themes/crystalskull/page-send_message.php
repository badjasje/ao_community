<?php
 /*
 * Template Name: Send message
 */
$receiver_ID = $_GET['id'];
$user = get_userdata( $receiver_ID );
if(!is_user_logged_in()) {
wp_redirect(get_permalink(3491)); exit;
}

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
   
			
	
			
<div class="row">
<div class="col-md-2"></div>

<div class="col-md-8">
	<ul class="single_inbox_message media-list">
		<li class="media ">
			<div class="media-left">
				<div class="leftAvatar"><?php echo small_avatar($receiver_ID,'');?></div>
			</div>
			
			<div class="media-body">
				<h4 class="media-heading"><?php echo LinkUtil::user_link($receiver_ID); ?></h4>
				Sending message
    		</div>
		</li>
	</ul>
</div>

<div class="col-md-2"></div>
</div> <!-- /row -->
			
<div class="row">
<div class="col-md-2"></div>

<div class="col-md-8">
	<div class="message_field">
		<form class="form" action="<?php echo home_url() ?>/message.php" name="" id="message" method="post">
			<input style="margin-bottom:10px;"type="text" id="title" required placeholder="Subject"name="title"/>
			<input type="hidden" name="receiver" value="<?php echo $receiver_ID;?>">
			​<textarea id="message" required rows="10" name="message" placeholder="Your message..."></textarea>
			
				<input class="submitBtn" type="submit" value="Send">
	
		</form>
		<script>
			jQuery(document).ready(function () {
			jQuery("#message").submit(function () {
	        jQuery(".submitBtn").attr("disabled", true);
	        return true;
	    	});
		});
		</script>
	</div>
	
</div>

<div class="col-md-2"></div>
</div><!-- /row -->
			
	            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>