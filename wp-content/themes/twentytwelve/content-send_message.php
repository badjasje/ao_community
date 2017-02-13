<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
 $receiver_ID = $_GET['id'];
 $user = get_userdata( $receiver_ID );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
			<center><h1>Send message to <a href="/users/profile/?id=<?php echo $receiver_ID;?> "><?php echo $user->display_name.' (#'.$receiver_ID;?>)</a></h1></center>
			
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
			
			<form class="form" action="<?php echo home_url() ?>/message.php" name="" id="message" method="post">
				<table style="margin-left:auto;margin-right:auto;max-width:450px;">
					<tr>
						<td><center>
				<input style="width:95%;" type="text" id="title" placeholder="Subject"name="title"/>
				<input type="hidden" name="receiver" value="<?php echo $receiver_ID;?>">
						</td></center>
					</tr>
					<tr>
						<td><center>
				​<textarea style="width:95%;"id="message" rows="10" name="message"placeholder="Your message..."cols="70"></textarea>
						</td></center>
					</tr>
				</table>
				<center><input type="submit" value="Send message to <?php echo $user->display_name.' (#'.$receiver_ID;?>)"></center>
			</form>
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
	<?php session_unset(); ?>
