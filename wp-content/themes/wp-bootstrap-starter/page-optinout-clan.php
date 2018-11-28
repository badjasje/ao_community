<?php
 /*
 * Template Name: Opt In or Out of Clan Wars
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

$optinout_status = get_post_meta($clan_ID, 'optout_status', true);
$optinout_reset = get_post_meta($clan_ID, 'optout_reset',true);

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

<form id="optinout-clan" method="post">
<div class="row pageRow">	
	

<div class="col-md-6 col-lg-4 col-no-padding editClanCol" style="height: 380px !important" >
	<div class="blockHeader">
	Opt In or Out of Clan Wars!
	</div>
        It is now possible to opt out of clan wars.<br/><br/>If your clan has LESS than 4 members, you can opt out of clan wars once per round within the first 7 days of the round, or the first 7 days of formulation of your clan.<br/><br/>
        ONCE YOU HAVE CHANGED THIS SETTING, IT CANNOT BE CHANGED AGAIN IN A SINGLE ROUND<br/><br/>
        Opting out achieves the following:
        <ul><li>You cannot be declared upon</li><li>You cannot declare on others</li><li>Outgoing attacks from your clan give you 50% less resources at all times</li></ul>
</div>
<div class="col-md-6 col-lg-4 col-no-padding editClanCol" style="height: 380px !important">
	<div class="blockHeader">&nbsp;
	</div>
	<div class="blockHeader" style="background-color:#fff;color:#545454">
	Opt In or Out of Clan Wars<br/><br/>
        Current Status: <?php if ($optinout_status != 1) {?>Opted<strong> <font color="green">IN!</font></strong> You will be a part of the competetive points list this round<?php ;} else { ?>Opted <strong><font color="red"> OUT!</font></strong> You cannot be declared on, or declare wars this round<?php } ?>
	</div>
<?php
                                          $exclusiondate=strtotime(get_field('starting_date','options'))+604800;
                                          $currenttimestamp=time();
?>
	<div style="padding: 0px 9px;width:100%;" class="attackDropdown statCol-2 no-gutters">

					<?php
                                          if ($optinout_reset == 1) {
                                            
                                            echo "You have already changed your opt in/out settings this round";
                                          }
                                          else if ($currenttimestamp > $exclusiondate) {
                                            if ($optinout_reset == 1) { echo "";} else { 
                                            echo "The round is more than a week old, you can no longer opt out of clan wars this round";
                                          } }
                                          else { ?>
		<select id="optin_status" name="optin_status" class="attackTypeInput">
                                              <?php 
                                                if ($optinout_status != 1) {
                                                  $optinout_status = "0";
                                                  ?>
                                                  <option value="optedin" name"optin_status" selected="selected">Opted IN</option>
                                                  <option value="optedout" name"optin_status" >Opt OUT now</option>
                                                  <?
                                                }
                                                else {
                                                  $optinout_status = "1";
                                                 
                                                  ?>
                                                  <option value="optedout" name"optin_status" selected="selected">Opted OUT</option>
                                                  <option value="optedin" name"optin_status">Opt IN now</option>
                                                  <?
                                                }
                                              
			                ?>
		</select>
                <?php } ?>
	</div>
	
	<div class="form-group">
		<div class="blockHeader" style="background-color:#fff;color:#545454">
        <?php if (($optinout_reset == 1) or ($currenttimestamp > $exclusiondate)) {} else { ?>Why may you wish to do this? Easy. If you opt out: Those pesky highly aggressive top points clans cannot declare on you. You can build your province in a sustainable way and not get smashed whilst you sleep.<br/>
        If you aren't sure whether or not to go for this - we recommend you first of all ask for some advice in the<strong> <a style="color:#000000" href="https://discord.gg/ttdng4n" target="_blank">Discord channel!</a></strong> Remember, once you opt out, you cannot change it this round! <?php } ?>
		</div>
  	</div>
</div>
<?php
$clanmembers_count = count($clanmembers);

if ($user_ID == $clanleader and ($optinout_reset != 1)) {
?><input class="mainSubmit" type="submit" value="Change Opt In or Out Settings" name="submit">
<?php }

elseif ($clanmembers_count > 3) { ?>
 <button class="mainSubmit" disabled value="TooManyClanmember" name="notsubmit">You may only opt out of wars if you have 4 or less members</button>
<?php 
}
else {
?>  <button class="mainSubmit" disabled value="Only the Clan Leader may opt in or out of clan wars" name="notsubmit">Either you are not the clan leader, or your clan has already changed opt in/out this round</button>
<?php }


//MEGA DEBUG OVERRIDE THE STUFF
?>
 <select id="optin_status" name="optin_status" class="attackTypeInput">
                                                  <option value="optedin" name"optin_status" selected="selected">Opted IN</option>
                                                  <option value="optedout" name"optin_status" >Opt OUT now</option>
                </select>
<?



?>

</form>
	
	
	
<?php if($user_ID == $clanleader):?>	

<?php if(empty($changecount) || $changecount != 1):?>
<div class="pageSpacer"></div>
<?php endif;?><?php endif;?>
	
<script>
	
(function($) {
	
	

	

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#optinout-clan").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();
    
    if (request) { request.abort();}
 
    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");
    
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/optinoutclan.php",
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
		

        
location.reload();
}

);	}
);	

	
	
	
	
	
	








 
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
