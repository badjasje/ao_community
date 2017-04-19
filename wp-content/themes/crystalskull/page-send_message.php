<?php
 /*
 * Template Name: Send message
 */
$receiver_ID = $_GET['id'];
$user = get_userdata( $receiver_ID );
if(!is_user_logged_in()) {
wp_redirect(get_permalink(3491)); exit;
}
if(!empty(get_user_meta($receiver_ID, 'avatar_user', true))){
				$avatar = get_user_meta($receiver_ID, 'avatar_user', true);
			} 
			else {
				$avatar = '/wp-content/uploads/2016/11/default_large.png';
			}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
   
			
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Enter a title</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Build more warfactories</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php endif;?><?php endif;?>
			
<div class="row">
<div class="col-md-2"></div>

<div class="col-md-8">
	<ul class="single_inbox_message media-list">
		<li class="media ">
			<div class="media-left">
				<img class="profile_image media-object" src="<?php echo $avatar;?>">
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
			
				<input type="submit" value="Send">
	
		</form>
	</div>
	
</div>

<div class="col-md-2"></div>
</div><!-- /row -->
			
	            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>