<?php
 /*
 * Template Name: Edit Clan
*/
get_header();
global $userData;
global $userId;

$user_ID = $userId;
$clan_ID = $userData['clan_id_user'][0];
$clanImg = get_post_meta($clan_ID, 'clan_image', true); // landscape, 750x400
$clanThumb = get_post_meta($clan_ID, 'clan_thumb', true); // square, 90x90
$clanData = get_post_meta($clan_ID);
$clanleader = $clanData['clan_leader'][0];
$leader_data = get_userdata($clanleader);
$clanmembers = maybe_unserialize(get_post_meta( $clan_ID, 'clan_members', true ));
$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = get_post_meta($clan_ID, 'ct_'.$i, true);
}
//$settings = array( 'media_buttons' => false );
$changecount = get_post_meta($clan_ID, 'clan_name_change', true);
if(get_field('game_status', 'option') != 'Live') $changecount = 0;
$allowed = array_merge($cts,array($clanleader));

$clan = get_post($clan_ID);
$wp_upload_dir = wp_upload_dir();
?>
<style>
.dropzone .dz-preview.dz-image-preview { display: none; }
.dropzone { min-width: 150px; background-size:cover!important; border:1px solid #999!important; overflow: hidden; }
</style>
<div class="row pageRow clanContentRow">
    <form id="editclan" method="post">
        <div class="row row-no-padding fw-row">

            <div class="col-12 attackingRow statCol-2">
                <h3>Edit clan header image <sup>(750x400)</sup></h3>
                <div id="clan_header_dz" class="clanImage clanUpload" style='background-image:url("<?php echo $clanImg;?>")'></div>
                <input type="hidden" name="newclanimage" class="newclanimage" id="newclanimage" value=""/>
            </div>
            <div class="col-12 attackingRow statCol-1">
                <h3>Edit clan profile image <sup>(90x90)</sup></h3>
                <div id="clan_avatar_dz" class="setAvatar clanAvatar profileAvatar clanUpload" style='background-image:url("<?php echo $clanThumb;?>")'></div>
                <input type="hidden" name="newclanavatar" class="newclanavatar" id="newclanavatar" value=""/>
            </div>
            <div class="col-12 attackingRow statCol-2">
                <h3>Edit public message</h3>
                <textarea rows="10" class="messageBox" type="text" name="publicmessage" id="clanmessager"><?php echo $clan->post_content;?></textarea>
            </div>


            <div class="pageSpacer"></div>

            <div class="col-md-12 col-no-padding">
                <div class="blockHeader">
                    Clan leader & Clan trustee management
                </div>
            </div>
            <div class="col-md-12 editClanCol">
                <div class="row mt-3">
                    <div class="col-md-4">Switch clan leader</div>
                    <div class="col-md-8">
                        <select id="clanleader" name="new_leader" class="form-control">
                            <option value="<?php echo $clanleader;?>" selected="selected">
                                <?php echo $leader_data->display_name;?>
                            </option>
                            <?php foreach($clanmembers as $key => $member) {
                                if($member != $clanleader) {
                                    $member_data = get_userdata($member);?>
                                    <option name="new_leader" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
                                    <?php
                                }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-3 mb-4">
                    <div class="col-md-4">
                        <span id="clan_trustee_num" data-value="<?=Settings::get('clan_trustee_num')?>">Pick clan trustees (max <?=Settings::get('clan_trustee_num')?>)</span>
                    </div>
                    <div class="col-md-8">
                    <?php foreach ($clanmembers as $key => $member) {
                        if($member != $clanleader) {
                            $member_data = get_userdata($member); ?>
                            <label><input type="checkbox" name="clantrustees[]" value="<?=$member?>"<?=(in_array($member,$cts)?'checked':'')?>> <?=$member_data->display_name?> (#<?=$member?>)</label><br>
                            <?php
                        }
                    } ?>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $clan_ID;?>">
            <input class="mainSubmit" type="submit" value="Edit clan" name="submit">
	    </div>
    </form>

    <?php if($user_ID == $clanleader):?>
        <?php if(empty($changecount) || $changecount != 1):?>
            <div class="pageSpacer"></div>
            <form class="form fw-row" id="clanname" method="post">
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="blockHeader">Change your clan name and/or tag</div>
                        <div class="col-md-12 loginfield statCol-1">
                            <input required type="text" id="display_name" minlength="3" maxlength="30" value="<?php echo get_the_title($clan_ID);?>" name="clanname">
                        </div>
                        <div class="col-md-12 loginfield statCol-3" style="border-top:1px solid #fff;">
                            <input required value="<?php echo get_post_meta($clan_ID, 'clan_tag', true);?>" class="new_user_name" type="text" name="clantag" id="clantag" maxlength="5">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $clan_ID;?>">
                        <input class="mainSubmit" type="submit" value="Change" />
                    </div>
                </div>
                <div class="hometext">
                    You can change your clan name and tag once every round.
                </div>
            </form>
        <?php endif;?>
    <?php endif;?>

    <script>
    (function($) {

        var request;
        $("#editclan").submit(function(event){
            $('.pageLoader, #page-cover').show();

            event.preventDefault();
            if (request) request.abort();
            var serializedData = $(this).serialize();
            request = $.ajax({url: "/editclan.php",type: "post",data: serializedData});
            request.done(function (response, textStatus, jqXHR){
                $('.pageLoader, #page-cover').fadeOut("fast");
                var array = JSON.parse(response);
                if(array.imagechanged == true) {
                    var myDropzone = Dropzone.forElement(".clanUpload");
                    myDropzone.removeAllFiles(true);
                    location.reload();
                } else $.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
            });
        });

        var namerequest;
        $("#clanname").on('submit', function(e) {
            if(!confirm('Are you sure you want to change your clan name and tag?')) return;
            $('.pageLoader, #page-cover').show();
            e.preventDefault();
            if (namerequest) namerequest.abort();
            namerequest = $.ajax({url: "/change_clan_name.php",type: "post",data: $(this).find('input').serialize()});
            namerequest.done(function(response) {
                $('.pageLoader, #page-cover').fadeOut( "fast");
                var array = JSON.parse(response);
                if(array.clan_updated == true) location.reload();
                else $.notify({message: array.status},{type:'info', delay:5000, allow_dismiss:true, newest_on_top: true});
            });
        });

        var maxTrustee = parseInt($('#clan_trustee_num').data('value'));
        $("[name='clantrustees[]']").on('change click', function(e) {
            if($("[name='clantrustees[]']:checked").length > maxTrustee) e.preventDefault();
        });

        $(window).on('load', function() {
            Dropzone.autoDiscover = false;
            $(".clanUpload").dropzone({
                url: "<?php echo get_stylesheet_directory_uri();?>/dropzoneUpload.php",
                addRemoveLinks: true,
                init: function() {
                    $(".clanUpload").addClass('dropzone');
                    this.on("sending", function(file, xhr, formData){
                        formData.append("my_nonce_field", "<?php echo wp_create_nonce('protect_content') ?>");
                        formData.append("action", "submit_dropzonejs");
                    });
                    this.on("removedfile", function(file, xhr, formData) {
                        console.log(file.previewTemplate.getAttribute('rel')); // todo add server delete and check if deleted image is from author
                    });
                },
                success: function (file, response) {
                    if($(file.previewElement).parents('.clanUpload').is('#clan_avatar_dz')) {
                        $('input[name="newclanavatar"]').attr('value',response);
                        $('#clan_avatar_dz').css('background-image', 'url(<?php echo $wp_upload_dir['url'];?>/' + response + ')');
                    }
                    else {
                        $('input[name="newclanimage"]').attr('value',response);
                        $('#clan_header_dz').css('background-image', 'url(<?php echo $wp_upload_dir['url'];?>/' + response + ')');
                    }
                    file.previewElement.classList.add("dz-success");
                },
                error: function (file, response) {
                    file.previewElement.classList.add("dz-error");
                }
            });
        });
    })(jQuery);
    </script>
</div> <!-- end pageRow -->
<?php
get_footer();