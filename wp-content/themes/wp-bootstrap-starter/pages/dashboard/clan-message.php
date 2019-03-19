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
	<? if(get_field('game_status','option')=='Pause') { ?>
		<div class="col-md-12 clanMessageButton disabled">
			<i class="fa fa-fire"></i> Clan wars
		</div>
		<div class="col-md-12 clanMessageButton fourthbutton disabled">
			<i class="fas fa-dollar-sign"></i> Send aid
		</div>
	<? } else { ?>
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
	<? } ?>

</div>

<?php if(in_array($userId, $allowed)):?>
	<div class="mainSubmit" id="editmessage"><i class="fas fa-pencil-alt"></i> Edit clan message</div>
<?php endif;?>

<script>
	(function($) {
		$( "#editmessage" ).click(function() {
			$('.message-editor').show(750);
			$('#savedmsg').hide(750);
		});
		$( "#dismiss" ).click(function() {
			$('.message-editor').hide(750);
			$('#savedmsg').show(750);
		});

		// Variable to hold request
		var request;

		// Bind to the submit event of our form
		$("#clan_message").on('submit',function(event){
			var content = tinymce.activeEditor.getContent();
			$('.pageLoader, #page-cover').show();
			$('.pageLoader, #page-cover').delay(250).fadeOut("fast");

			event.preventDefault();
			if (request) request.abort();

			var $form = $(this);
			var $inputs = $form.find("input, select, button, textarea");
			var serializedData = $form.serialize();

			// Fire off the request to /form.php
			request = $.ajax({url: "/clan_message.php",type: "post",data: serializedData+'&new_message='+content});
			request.done(function (response, textStatus, jqXHR){
				var array = JSON.parse(response);
				$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
				$('#savedmsg').html(array.clanmessage);
				$('.message-editor').hide(400);
				$('#savedmsg').show(500);
			});
		});
	})(jQuery);
</script>

