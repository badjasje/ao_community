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
