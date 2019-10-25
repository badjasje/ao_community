<div class="row fw-row no-gutters">
	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a href="<?php echo get_the_permalink( $clan_id_user );?>">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,1)"><i class="fa fa-info-circle"></i> View clan</button>
		</a>
	</div>
	<?php if(in_array($userId, $allowed)):?>
		<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
			<a href="/edit-clan">
				<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.95)"><i class="fa fa-wrench"></i> Edit clan</button>
			</a>
		</div>
		<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
			<a href="/open-invites">
				<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.9)"><i class="fa fa-envelope-open"></i> Open invites</button>
			</a>
		</div>
	<?php endif;?>

	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a href="/clan-member-information">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.85)"><i class="fa fa-users"></i> Clan member information</button>
		</a>
	</div>
	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a href="/clan-wars">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.80)"><i class="fa fa-fire"></i> Clan wars</button>
		</a>
	</div>

	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a href="/bonus-overview">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.75)"><i class="fa fa-chart-bar"></i> Bonus overview</button>
		</a>
	</div>
	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a href="/send-aid">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.70)"><i class="fa fa-dollar-sign"></i> Send aid</button>
		</a>
	</div>
	<?php if($clan_leader != $userId):?>
	<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
		<a onclick="return confirm('Are you sure you want to leave your clan? Your clan will lose 25% of your total clan points.')" href="/leave.php/?user=<?php echo $userId;?>">
			<button class="cancelButton hoverEffect" style="background-color:rgba(66, 92, 107,0.65)"><i class="fa fa-arrow-circle-o-down"></i> Leave clan</button>
		</a>
	</div>
	<?php endif;?>

	<?php if($clan_leader == $userId):?>
		<div class="col-md-6 col-lg-4 celBlock" style="padding:0px;">
			<button class="cancelButton deleteClan hoverEffect" style="background-color:rgba(66, 92, 107,0.65)"><i class="fa fa-trash"></i> Delete clan</button>
		</div>
		<script>
		(function($) {
			var deleteclan;
			$(document).on('click','.deleteClan',function(e) {
				if(!confirm('Are you sure you want to delete your clan?')) {
					e.preventDefault();
					return;
				}
				$('.pageLoader, #page-cover').show();
				deleteclan = $.ajax({url: "/deleteclan.php",type: "post",data: '&clan=<?php echo $clan_id_user;?>'});
				deleteclan.done(function (response, textStatus, jqXHR){
					$('.pageLoader, #page-cover').fadeOut( "fast");
					var response = $.parseJSON(response);
					$.notify({message: response.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
					if(response.next == true){
						location.reload();
					}
				});
			});
		})(jQuery);
		</script>
	<?php endif;?>
</div>
