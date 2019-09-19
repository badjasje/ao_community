<?php
/**
 * Template Name: Send aid
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$aid_sent = $province->get('aid_sent_today');
$maxAmount = round(min(250000,$province->getMoney()));

$clan = $province->getClan();
$members = array();
if($clan) {
	$clanmembers = $clan->getMembers();//ID's
	foreach($clanmembers as $member_id) {
		$member = Province::make($member_id);
		if($member_id != $province->get('id') && $member->getNetworth() <= $province->getNetworth()) $members[] = $member;
	}
}
?>
<div class="row pageRow">
	<div class="fw-row">
		<form id="aid">

			<div class="blockHeader" style="border-bottom:0px;">
				You can send aid 3 times every 24 hours, with a maximum of <?=Format::money(250000)?> per aid.
			</div>
			<div class="blockHeader spaceNotice">
				You have sent aid <span id="aidssent"><?=$aid_sent?></span> times today
			</div>

			<? if(count($members)) {?>
			<div class="row no-gutters">
				<div class="col-md-6 no-gutters">
					<div class="row no-gutters">
						<div class="attackDropdown statCol-1 no-gutters">
							Player to aid
						</div>
						<div style="padding:0px;" class="attackDropdown statCol-2 no-gutters">
							<select name="receiver" class="attackTypeInput">
								<? foreach ($members as $member) { ?>
									<option name="receiver" value="<?=$member->get('id')?>"><?=$member->getName()?></option>
								<? } ?>
							</select>
						</div>
					</div>
				</div>

				<div class="col-md-6 no-gutters">
					<div class="row no-gutters">
						<div class="col-sm-6 bankCol">
							<input class="unitInput" min="0" max="<?=$maxAmount?>" placeholder="Enter amount"type="number" id="amount" name="amount" style="border: none;"/>
						</div>
						<div id="maxaid" class="col-sm-6 bankCol mainSubmit" style="border-top:0px;background-color:rgba(70, 118, 94, 0.8);">
							MAX
						</div>
					</div>
				</div>
			</div>
			<? } else if($aid_sent < 3)  { ?>
				<div class="blockHeader spaceNotice">
				<?
				if(!$clan || count($clanmembers)==1) echo 'Clanmates can help each other by sending aid.';
				else echo 'You can only help clanmates who have a lower networth than you.';
				?>
				</div>
			<? } ?>

			<input type="submit" value="Send aid" class="mainSubmit">
		</form>
	</div>

	<script>
		(function($) {
			$("#maxaid").click(function() {
				$("#amount").val("<?php echo $maxAmount;?>");
			});

			var request;
			$('form').submit(function(event) {
				$('.pageLoader, #page-cover').show();

				event.preventDefault();
				if (request) request.abort();

				var $form = $(this);
				var $inputs = $form.find("input, select, button, textarea");
				var serializedData = $form.serialize();

				request = $.ajax({url: "/send_aid.php",type: "post",data: serializedData});
				request.done(function (response, textStatus, jqXHR){
					$('.pageLoader, #page-cover').fadeOut("fast");
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

</div>
<?php
get_footer();