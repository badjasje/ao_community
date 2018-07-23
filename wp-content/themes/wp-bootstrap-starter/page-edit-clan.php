<?php
 /*
 * Template Name: Edit Clan
*/
get_header(); 
global $userData;
global $userId;

$user_ID = $userId;
$clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_ID);
$clanleader = $clanData['clan_leader'][0];
$clanmembers = maybe_unserialize(get_post_meta( $clan_ID, 'clan_members', true ));
$ct_1 = get_post_meta($clan_ID,'ct_1',true);
$ct_2 = get_post_meta($clan_ID,'ct_2',true);
$ct_3 = get_post_meta($clan_ID,'ct_3',true);
$ct_4 = get_post_meta($clan_ID,'ct_4',true);
$settings = array( 'media_buttons' => false );
$changecount = get_post_meta($clan_ID, 'clan_name_change', true);
$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);

$autojoin = get_post_meta($clan_ID, 'autojoin_allowed', true);
$autojoinDesc = get_post_meta($clan_ID, 'autojoin_description', true);
$playstyle = get_post_meta($clan_ID, 'autojoin_playstyle', true);

$casual = '';
$points = '';
$networth = '';
$other = '';
if($playstyle == 'Casual'){
	$casual = 'selected="selected"';
}
if($playstyle == 'Points'){
	$points = 'selected="selected"';
}
if($playstyle == 'Networth'){
	$networth = 'selected="selected"';
}
if($playstyle == 'Other'){
	$other = 'selected="selected"';
}


$autojoinYes = '';
$autojoinNo = '';

if($autojoin == 'yes'){
	$autojoinYes = 'selected="selected"';
}
if($autojoin == 'no'){
	$autojoinNo = 'selected="selected"';
}


$clan = get_post($clan_ID);

?>

<div class="row pageRow">	
	



	
<div class="col-md-6 col-lg-4 col-no-padding editClanCol">
	<div class="blockHeader">
	Set clan avatar
	</div>
	<div class="row no-gutters">
		<div class="col-xs-2 col-no-padding eventImageCol" style="border-right: 1px solid #fff;height: 270px;">
			<?php echo clan_avatar($clan_ID,'profileAvatar');?>
		</div>
		
		<div class="col-xs-10" style="flex:100">
			<form id="editclan" method="post">
				
			<div class="needsclick dz-clickable" id="clan_avatar_dz">
				<div class="dz-message needsclick">				
					Drop files or click to upload. <br/>Squared image for the best result.
          		</div>		
          		<input hidden type="text" name="newclanimage" class="newclanimage" id="newclanimage" value=""/>	
        	</div>
		
				
		</div>
	</div>
</div>
<div class="col-md-6 col-lg-4 col-no-padding editClanCol">
	<div class="blockHeader">
	Edit public message
	</div>
	<textarea rows="10" class="messageBox" type="text" name="publicmessage" id="clanmessager"><?php echo $clan->post_content;?></textarea>
</div>
<div class="col-md-6 col-lg-4 col-no-padding editClanCol">
	<div class="blockHeader">
	Clan leader & Clan trustee management
	</div>
	<div class="blockHeader" style="background-color:#fff;color:#545454">
	Switch clan leader
	</div>
	<div style="padding: 0px 9px;width:100%;" class="attackDropdown statCol-2 no-gutters">
		<select id="clanleader" name="new_leader" class="attackTypeInput">
			<option value="<?php echo $clanleader;?>" selected="selected">
					<?php 
						$member_data = get_userdata($clanleader);
						echo $member_data->display_name;?>
					</option>
					
			
				<?php foreach ($clanmembers as $key => $member) {
					if($member != $clanleader){
						$member_data = get_userdata($member);?>	
					<option name="new_leader" value="<?php echo $member;?>"><?php echo $member_data->display_name;?></option>
					<?php }}?>
		</select>
	</div>
	
	<div class="form-group">
		<div class="blockHeader" style="background-color:#fff;color:#545454">
	    Pick clan trustees 
		</div>
	    <select name="clantrustees[]" multiple class="form-control ctselect" id="clantrustees">
			<?php foreach ($clanmembers as $key => $member) {
				if($member != $clanleader){
				$member_data = get_userdata($member);?>	
					
				<option name="clantrustee" value="<?php echo $member;?>"><?php echo $member_data->display_name;?> (#<?php echo $member;?>)</option>
			<?php }}?>
					
	    </select>
	    
  	</div>
	
</div>
<input class="mainSubmit" type="submit" value="Edit clan" name="submit">
</form>
	
	
	
<?php if($user_ID == $clanleader):?>	

<?php if(empty($changecount) || $changecount != 1):?>
<div class="pageSpacer"></div>
<form class="form fw-row" action="<?php echo home_url() ?>/change_clan_name.php?id=<?php echo $clan_ID;?>" name="" id="clanname" method="post">
<div class="row no-gutters">
<div class="col-md-3" style="padding:0px;">
	</div>
	<div class="col-md-6"  style="padding:0px;">
		<div class="blockHeader">Change your clan name and/or tag</div>
		
		
			<div class="col-md-12 loginfield statCol-1">
			<input required type="text" id="display_name" minlength="3" maxlength="30" value="<?php echo get_the_title($clan_ID);?>" name="clanname">
			</div>
			<div class="col-md-12 loginfield statCol-3" style="border-top:1px solid #fff;">
			<input required value="<?php echo get_post_meta($clan_ID, 'clan_tag', true);?>" class="new_user_name" type="text" name="clantag" id="clantag" maxlength="5">
			</div>
			<input onclick="return confirm('Are you sure you want to change your clan name and tag?')" class="mainSubmit" type="submit" value="Change" /> 
							
							</form>
	<div class="hometext">
You can change your clan name and tag once every round.
	</div>
	</div>
	
	</div>
	<div class="col-md-3" style="padding:0px;">
	</div>
	</div>
</form>
<?php endif;?><?php endif;?>
	
<script>
	
(function($) {
	
	

	

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#editclan").submit(function(event){

	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();
    
    if (request) { request.abort();}
 
    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");
    
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/editclan.php",
        type: "post",
        data: serializedData,
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        	
        						console.log(array);
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
								'<i class="fa fa-info-circle"></i> ' +
								'' +
								'<span data-notify="message">{2}</span>' +
								'</div>'
						});	
			if(array.imagechanged == true){
				$('.clan_avatar').css('background-image', 'url(' + array.newclanimage + ')');
				var myDropzone = Dropzone.forElement("#clan_avatar_dz");
				myDropzone.removeAllFiles(true);
			}
		
});	});	

	
	
	
	
	
	
	jQuery(function() {
	jQuery('select[name="clantrustees[]"]').children('[value="<?php echo $ct_1;?>"],[value="<?php echo $ct_2;?>"],[value="<?php echo $ct_3;?>"],[value="<?php echo $ct_4;?>"]').attr('selected', 'selected');
});


$("select").on('change', function(e) {
    if (Object.keys($(this).val()).length > 4) {
        console.log($('option[value="' +$(this).val().toString().split(',')[4] + '"]'));
        $('option[value="' +$(this).val().toString().split(',')[4] + '"]').prop('selected', false);
        $.notify({
		message: "Only four clan trustees allowed",
		},{
		type: 'info',
		delay: 5000,
		template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
					'<i class="fa fa-info-circle"></i> ' +
					'' +
					'<span data-notify="message">{2}</span>' +
					'</div>'
			});	
    }
     
});






 
    $(window).on('load', function() {
    
      Dropzone.autoDiscover = false;
      
      $("#clan_avatar_dz").dropzone({
          url: "<?php echo get_stylesheet_directory_uri();?>/dropzoneUpload.php",
          addRemoveLinks: true,
          
          init: function() {
            $("#clan_avatar_dz").addClass('dropzone');
            
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
	          console.log(response);
            
            	$('input[name="newclanimage"]').attr('value',response);
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
	
	
	
	
	
	
	
	
</div> <!-- end pageRow -->
<?php
get_footer();