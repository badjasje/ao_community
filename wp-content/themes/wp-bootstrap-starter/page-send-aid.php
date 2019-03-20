<?php
/*
 * Template Name: Send aid
 */
get_header();
global $userData;
global $userId;

$clan_ID = $userData['clan_id_user'][0];
$clanmembers = maybe_unserialize(get_post_meta( $clan_ID, 'clan_members', true ));
$user_NW = $userData['networth'][0];
$money = $userData['money'][0];
$aid_sent = $userData['aid_sent_today'][0];
$maxAmount = round(min(250000,$money));
?>

<div class="row pageRow">
	<div class="fw-row">
		<form id="aid">

			<div class="blockHeader" style="border-bottom:0px;">
				You can send aid 3 times every 24 hours, with a maximum of $ 250 000 per aid.
			</div>
			<div class="blockHeader spaceNotice">
				You have sent aid <span id="aidssent"><?php echo $aid_sent;?></span> times today
			</div>

			<div class="row no-gutters">
				<div class="col-md-6 no-gutters">
					<div class="row no-gutters">
						<div class="attackDropdown statCol-1 no-gutters">
							Player to aid
						</div>
						<div style="padding:0px;" class="attackDropdown statCol-2 no-gutters">
							<select name="receiver" class="attackTypeInput">
								<?php
								foreach ($clanmembers as $key => $member) {
									$nw_user = get_user_meta($member, 'networth',true);
									if($member != $userId && $user_NW > $nw_user){
										$member_data = get_userdata($member);
										?>
										<option name="receiver" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
										<?php
									}
								} ?>
							</select>
						</div>
					</div>
				</div>

				<div class="col-md-6 no-gutters">
					<div class="row no-gutters">
						<div class="col-sm-6 bankCol">
							<input class="unitInput" min="0" max="<?php echo $maxAmount;?>" placeholder="Enter amount"type="number" id="amount" name="amount" style="border: none;"/>
						</div>
						<div id="maxaid" class="col-sm-6 bankCol mainSubmit" style="border-top:0px;background-color:rgba(70, 118, 94, 0.8);">
							MAX
						</div>
					</div>
				</div>
			</div>

			<input type="submit" value="Send aid" class="mainSubmit">
		</form>
	</div>

	<script>
		(function($) {
			$("#maxaid").click(function() {
				$("#amount").val("<?php echo $maxAmount;?>");
			});

			var request;
			$('form').submit(function( event ) {
				$('.pageLoader, #page-cover').show();
				$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

				event.preventDefault();
				if (request) request.abort();

				var $form = $(this);
				var $inputs = $form.find("input, select, button, textarea");
				var serializedData = $form.serialize();

				request = $.ajax({url: "/send_aid.php",type: "post",data: serializedData});
				request.done(function (response, textStatus, jqXHR){
					updateHeaderData();
					var array = JSON.parse(response);
					$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
					if(array.next == true){
						$('#money').html(number_format(array.money, 0, ',', ' '));
						$('#aidssent').html(array.noaids);
						$('form').trigger("reset");
					}
				});
			});
		})(jQuery);
	</script>

</div> <!-- end .pageRow -->
<?php
get_footer();