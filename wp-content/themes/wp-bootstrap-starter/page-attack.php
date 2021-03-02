<?php
/**
 * Template Name: Attack
 */
$user = CurrentUser::make();
$province = $user->getProvince();

if(!$user->isLoggedIn()) {
	exit(wp_redirect(home_url('/')));
}


get_header();
nocache_headers();
include 'constants.php';
include('attack_functions.php');

global $userData;
global $userId;

update_user_meta($userId, 'user_lock', 0);
update_user_meta($userId, 'morale_lock', 0);

$networth = $userData['networth'][0];
$status = $userData['status'][0];
$satOwned = $userData['sat_owned'][0];

$attackUserId = sanitize_text_field($_GET['id']);
if ($attackUserId == $userId) {
	exit(wp_redirect(home_url('/')));
}
$attackUser = Province::make($attackUserId);
$attackUserData = get_userdata($attackUserId);

$attackArray = array('target_id' => $attackUserId, 'attackarray' => array());
if(isset($_SESSION['tokens']) && isset($_POST['token'])) {
	$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
	if(!empty($token)) $attackArray['token'] = $token;
	$attacktype = filter_input(INPUT_POST, 'attacktype', FILTER_SANITIZE_STRING);
	if(!empty($attacktype)) $attackArray['attacktype'] = $attacktype;
	$attackmode = filter_input(INPUT_POST, 'attackmode', FILTER_SANITIZE_STRING);
	if(!empty($attackmode)) $attackArray['attackmode'] = $attackmode;
	$maintarget = filter_input(INPUT_POST, 'maintarget', FILTER_SANITIZE_STRING);
	if(!empty($maintarget)) $attackArray['maintarget'] = $maintarget;
	$spytype = filter_input(INPUT_POST, 'spytype', FILTER_SANITIZE_STRING);
	if(!empty($spytype)) $attackArray['spytype'] = $spytype;
}


$sat_morale = $userData['sat_morale'][0];
$last_attacked = rtrim($userData['last_attacked'][0], ',');
$last_attacked = explode(',',$last_attacked);

$morale = $userData['morale'][0];
$moralepool = $userData['morale_pool'][0];

$satDisabled = 'disabled';
$satDisabledClass = 'btn-disabled';
if($satOwned != 0 || !empty($satOwned) && $satOwned != 'stealths'){
	$satDisabled = '';
	$satDisabledClass = 'btn-general';
}
$low_range = $networth/$ATTACK_RANGE_MULT;

$attackRange = '$ '.number_format($low_range, 0, ',', ' ').' and $ '.number_format($networth*$ATTACK_RANGE_MULT, 0, ',', ' ');

// Check if in mutual war, where range does not matter
$war_type = '';
$attacker_clan_ID = get_user_meta($userId, 'clan_id_user', true);
$defender_clan_ID = get_user_meta($attackUserId, 'clan_id_user', true);
if(!empty($attacker_clan_ID) && !empty($defender_clan_ID)) {
	$war_type = get_war_type($attacker_clan_ID,$defender_clan_ID);
}
if($war_type == 'mutual') {
	$range_msg = 'In a mutual war';
}
else {
	$attackUserNW = get_user_meta($attackUserId, 'networth',true);
	if (($attackUserNW > $networth/1.4 && $attackUserNW < $networth*1.4)){
		$range_msg = 'In range';
	}
	else {
		$range_msg = 'Out of range';
	}
}
?>
<div class="row pageRow">
	<div class="blockHeader">
		You can target provinces with a networth between <?php echo $attackRange;?>
	</div>
	<div class="row row-no-padding fw-row">
		<div class="col-xs-2 col-no-padding">
			<?php echo small_avatar($attackUserId,'attackAvatar');?>
		</div>
		<div class="col-xs-10 col-no-padding" style="flex:100">
			<div class="col-12 attackingRow statCol-2">Attacking <?php echo get_user_name($attackUserId); ?>
			</div>
			<div class="col-12 attackingRow statCol-3"><?=$range_msg.': '.$attackUser->getNetworth(true)?>
			</div>
		</div>
	</div>

	<div id="attackstep" stepcount="1"></div>
	<div id="step-1">
		<?php include('pages/attack/step-1.php'); ?>
	</div>
	<div id="step-2">
	</div>
	<div id="step-3">
	</div>
	<div id="attack-result">
	</div>

	<script>
	(function($) {
		<? if(count($attackArray) > 5 && $attackArray['attacktype']=='spy') {
			?>
			$('.pageLoader, #page-cover').show();
			$( "#step-1" ).hide();
			var attackresult = $.ajax({
				url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/attack-result.php",
				type: "post",
				data: <?=json_encode($attackArray)?>
			});
			attackresult.done(function (attackresultresponse, textStatus, jqXHR){
				$('.pageLoader, #page-cover').fadeOut("fast");
				try {
					json = $.parseJSON(attackresultresponse);
					$.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
					return false;
				} catch (e) {
					$("#attack-result").empty().append(attackresultresponse);
				}
				$('#strikeagain').hide();// Not on quick-spy
			});
		<? } ?>

		var request, finalarray;
		$("#attack").submit(function(event){
			$('.pageLoader, #page-cover').show();
			$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

			event.preventDefault();
			if (request) request.abort();

			var $form = $(this);

			var $inputs = $form.find("input, select, button, textarea");
			var serializedData = $form.serialize();

			request = $.ajax({url: "/attack.php",type: "post",data: serializedData});
			request.done(function (response, textStatus, jqXHR){
				var array = JSON.parse(response);
				if(array.next == false){
					$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
				}
				if(array.next == true){
					var request2;
					$( "#step-1" ).hide();
					$('.pageTitle').html('Attack: Step 2');
					$('#attackstep').attr( "stepcount",2 );
					$( "#step-2" ).show();
					request2 = $.ajax({url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/step-2.php",type: "post",data: array});
					request2.done(function (response2, textStatus, jqXHR){

						$( "#step-2" ).append( response2 );
						var request3;
						$("#attack2").submit(function(event){
							$('.pageLoader, #page-cover').show();
							$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

							event.preventDefault();
							if (request3) request3.abort();

							var $form = $(this);
							var $inputs = $form.find("input, button, textarea");
							var serializedData3 = $form.serialize();
							request3 = $.ajax({url: "/attack2.php",type: "post",data: serializedData +'&'+ serializedData3});
							request3.done(function (response, textStatus, jqXHR){

								$( "#step-2" ).hide();
								jQuery('.pageTitle').html('Attack: Step 3');
								$('#attackstep').attr( "stepcount",3 );

								var request4;
								var finalarray = JSON.parse(response);
								request4 = $.ajax({url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/step-3.php",type: "post",data: finalarray});
								request4.done(function (response4, textStatus, jqXHR){

									$( "#step-3" ).append( response4 );
									$( "#step-3" ).show();

									var attackresult;
									$(document).on('click','#attack3',function(event){

										$('.pageLoader, #page-cover').show();
										$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
										var $form = $(this);
										$( "#attack-result" ).show();

										attackresult = $.ajax({
											url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/attack-result.php",
											type: "post",
											data: finalarray
										});
										$( "#step-3" ).empty();
										attackresult.done(function (attackresultresponse, textStatus, jqXHR){
											try {
												json = $.parseJSON(attackresultresponse);
												$.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
												return false;
											} catch (e) {
												$( "#attack-result" ).empty().append( attackresultresponse );
											}
											$('#strikeagain').show(); // if hidden
											var strikeagain;
											$(document).on('click','#strikeagain',function(strikeevent){

												$( "#strikeagain" ).hide();
												$('.pageLoader, #page-cover').show();
												$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
												strikeagain = $.ajax({
													url: "<?php echo get_stylesheet_directory_uri();?>/pages/attack/attack-result.php",
													type: "post",
													data: finalarray
												});

												// Callback handler that will be called on success
												strikeagain.done(function (strikeagainresponse, textStatus, jqXHR){
													try {
														json = $.parseJSON(strikeagainresponse);
														$.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
														return false;
													} catch (e) {
														$( "#attack-result" ).hide();
														$( "#attack-result" ).empty().append( strikeagainresponse );
														$( "#attack-result" ).show();
													}

													$( "#strikeagain" ).show();
												});
											});

										});
									});

								});
							});
						});

					});

				}
			});
		});
	})(jQuery);
	</script>
</div>
<?php
get_footer();