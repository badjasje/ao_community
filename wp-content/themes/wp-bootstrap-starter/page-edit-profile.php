<?php
/*
 * Template Name: Edit profile
 */
get_header();
global $userId;
global $userData;

$user = get_userdata($userId);

$user_NW = $userData['networth'][0];
$status = $userData['status'][0];
$user_land = $userData['land'][0];
$clan_id = $userData['clan_id_user'][0];
$timestamp = current_time('timestamp');
$clan_timestamp = $userData['new_clan_timestamp'][0];

$user_country_code = $userData['user_country'][0];

$last_online = $userData['last_online'][0];
if(!empty($last_online)){
	$last_seen = $timestamp - $last_online;
}

$visiting_user = $userId;

$visitorData = get_user_meta($visiting_user);

$clan_id_user = $visitorData['clan_id_user'][0];

$visitorClanData = get_post_meta($clan_id_user);

$previous_members = maybe_unserialize(get_post_meta($clan_id_user, 'previous_members', true));

$ct_1 = $visitorClanData['ct_1'][0];
$ct_2 = $visitorClanData['ct_2'][0];
$ct_3 = $visitorClanData['ct_3'][0];
$ct_4 = $visitorClanData['ct_4'][0];
$cl_1 = $visitorClanData['clan_leader'][0];

$CT_CL_array = array($ct_1,$ct_2,$ct_3,$ct_4,$cl_1);
$members = $visitorClanData['clan_members'][0];

$reset_status = get_user_meta($userId, 'reset_status', true);

$disable_input = "";
if($userData['name_change_counter'][0] == 1 && get_field('game_status', 'option') == 'Live') {
	 $disable_input = "disabled";
}

?>
<div class="row pageRow">
    <div class="blockHeader">
        <?php echo get_user_name($userId); ?>
    </div>
    <form class="fw-row" id='editprofile'>
        <div class="row row-no-padding fw-row">
            <div class="col-xs-2 col-no-padding eventImageCol" style="border-right: 1px solid #fff;">
                <div id="user_avatar_dz" class="setAvatar clan_avatar profileAvatar needsclick dz-clickable" style="background: url(<?php echo $userData['avatar_user'][0];?>);"></div>
                <input hidden type="text" name="newuserimage" class="newuserimage" id="newuserimage" value=""/>
            </div>
	        <div class="col-xs-10 col-no-padding" style="flex:100">

                <div class="col-12 attackingRow statCol-2">
                    <div class="editProfileColumn">
                        <label>Player name</label>
                        <input style="border:0px;" <?php echo $disable_input;?> maxlength="25" value="<?php echo $user->display_name;?>" type="text" class="unitInput playername" placeholder="Username" name="username">
                    </div>
                </div>

                <div class="col-12 attackingRow statCol-4">
                    <div class="editProfileColumn">
                        <label>Phone number</label>
                        <input style="border:0px;"  maxlength="25" value="<?php echo $userData['phone_number'][0];?>" type="numbers" class="unitInput" placeholder="Phone number" name="phone">
                    </div>
                </div>

                <div class="col-12 attackingRow statCol-3">
                    <div class="editProfileColumn">
                        <label>Email address</label>
                        <input style="border:0px;"  value="<?php echo $user->user_email;?>" type="text" class="unitInput" placeholder="Username" name="email">
                    </div>
                </div>

	        </div>
        </div>
        <input type="submit" class="mainSubmit" value="update profile">
    </form>

    <div class="pageSpacer"></div>
    <div class="pageSpacer"><em>You can only reset once per round</em></div>

    <? if(empty($reset_status)) { ?>
    <button id="resetaccount"style="background-color:#A00000;border:0px;" class="mainSubmit">
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;RESET ACCOUNT
    </button>
    <? } ?>

</div> <!-- end pageRow --->

<script>
	(function($) {

        var resetaccount;
		$(document).on('click','#resetaccount',function(){
	        if(confirm("Are you sure you want to reset your account? You will lose all your units, research and buildings!")){
	            $('.pageLoader, #page-cover').show();
	            $('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
	            var target = $(this).attr('data-target');
		        resetaccount = $.ajax({url: "/reset_province.php",type: "post",data: ''});
                resetaccount.done(function (response, textStatus, jqXHR){
		    		var response = $.parseJSON(response);
				    $.notify({message: response.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
	        		if(response.next == true){
                        $('#money').html(number_format(450000, 0, ',', ' '));
                        $('#turns').html(number_format(200, 0, ',', ' '));
                        $('#land').html(number_format(2000, 0, ',', ' '));
                        $('#moralepool').html(number_format(0, 0, ',', ' '));
                        $('#power').html(number_format(0, 0, ',', ' '));
			        }
	            });
            }
        });

        var request;
        $("#editprofile").submit(function(event){
        	$('.pageLoader, #page-cover').show();
	        $('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

            event.preventDefault();
            if (request) { request.abort();}

            var $form = $(this);
            var $inputs = $form.find("input, select, button, textarea");
            var serializedData = $form.serialize();

            request = $.ajax({url: "/update_profile.php",type: "post",data: serializedData});
            request.done(function (response, textStatus, jqXHR){
                var array = JSON.parse(response);
        	    $.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
			    if(array.imagechanged == true){
				    $('.clan_avatar').css('background-image', 'url(' + array.newuserimage + ')');
				    var myDropzone = Dropzone.forElement("#user_avatar_dz");
				    myDropzone.removeAllFiles(true);
			    }
			    if(array.usernamechanged == true){
				    $(".playername").attr("disabled", "disabled");
			    }
            });
        });

        $(window).on('load', function() {
            Dropzone.autoDiscover = false;
            $("#user_avatar_dz").dropzone({
                url: "<?php echo get_stylesheet_directory_uri();?>/dropzoneUpload.php",
                addRemoveLinks: true,
                init: function() {
                    $("#user_avatar_dz").addClass('dropzone');
                    this.on("sending", function(file, xhr, formData){
                        formData.append("my_nonce_field", "<?php echo wp_create_nonce('protect_content') ?>");
                        formData.append("action", "submit_dropzonejs");
                    });
                    this.on("removedfile", function(file, xhr, formData) {
                        // todo add server delete and check if deleted image is from author
                        console.log(file.previewTemplate.getAttribute('rel'));
                    });
                },
                success: function (file, response) {
	                $('input[name="newuserimage"]').attr('value',response);
                    //var imgName = response;
                    file.previewElement.classList.add("dz-success");
                },
                error: function (file, response) {
                    file.previewElement.classList.add("dz-error");
                }
            });
        });
    })(jQuery);
</script>

<?php
get_footer();