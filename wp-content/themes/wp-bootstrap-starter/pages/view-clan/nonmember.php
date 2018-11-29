<div class="blockHeader">You are currently not in a clan! <?php if($clanCreate != 1):?>Create a clan or join one<?php endif;?></div>
<div class="blockHeader spaceNotice">You can only create one clan per round</div>


<?php if($clanCreate != 1):?>
<div class="pageSpacer"></div>
<form action="<?php echo home_url() ?>/clan.php" class="fw-row" id="clan" method="post">
	<div class="col-md-12 loginfield statCol-1">
		<input required type="text" name="clanname" minlength="3" maxlength="25" id="clanname" placeholder="Clan Name. Max 20 characters.">
	</div>
	<div class="col-md-12 loginfield statCol-2">
		<input required type="text" name="clantag" id="clantag" maxlength="5" placeholder="Clan Tag. Max 5 characters.">
	</div>
        
	<div class="col-md-12 loginfield statCol-2">
          <h2>Opt In or Out of Clan Wars? </h2>
          <select id="optin_status" name="optin_status" class="attackTypeInput" style="background-color:white !important">
            <option value="optedin" name"optin_status" selected="selected">Opt IN: Gain clan points, be eligible for personal and clan awards, be able to declare and recieve wars.</option>
            <option value="optedout" name"optin_status" >Opt OUT: Avoid eligibility for all toplists, but also gain protection from unwanted incoming clan wars (Sandbox mode)</option>

          </select>
          <br/><br/>Please beware: If you OPT OUT of clan wars now, you will NOT be able to change this setting until next round</br>
          <br/>Opt out is designed to allow new clans a safe place to learn the game without being raided by our most active community clans in clan wars. If you choose to opt out, you also forego the ability to declare ANY war, you will gain HALF resources only when attacking, and you will be excluded from all toplists. This is a lovely sandbox to learn the game, but opting out of all competetive playlists is a big choice. If in doubt,<strong> <a href="https://discord.gg/ttdng4n" target="_blank">ask on Discord for more information.</a></strong> <br/><br/><strong>We always recommend you to <font color=green>Opt In</font></strong> unless you are certain you do not want to enjoy the attacking side of our game.<br/><br/>
          Provinces will still be able to attack you, but out of war. Without points on offer: Only the most dedicated Networth seekers will bother you... <br/>
        </div>
	<input type="submit"  class="mainSubmit hoverEffect createClan" value="Create your clan">
</form>

<script>
(function($) {
var request;

$("#clan").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();
    if (request) { request.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/clan.php",
        type: "post",
        data: serializedData
    });

    request.done(function (response, textStatus, jqXHR){

        var array = JSON.parse(response);
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
			if(array.next == true){
				location.reload();
			}

});	});	
})(jQuery);
</script>
<?php endif;?>
