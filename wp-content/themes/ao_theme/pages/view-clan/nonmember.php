<div class="blockHeader">You are currently not in a clan! <?php if($clanCreate != 1):?>Create a clan or join one<?php endif;?></div>
<? if(!Round::isTest() && !Round::isDev()) { ?>
<div class="blockHeader spaceNotice">You can only create one clan per round</div>
<? } ?>

<?php if($clanCreate != 1) { ?>
<div class="pageSpacer"></div>
<form action="<?php echo home_url() ?>/clan.php" class="fw-row" id="clan" method="post">
	<div class="col-md-12 loginfield statCol-1">
		<input required type="text" name="clanname" minlength="3" maxlength="25" id="clanname" placeholder="Clan Name. Max 20 characters.">
	</div>
	<div class="col-md-12 loginfield statCol-2">
		<input required type="text" name="clantag" id="clantag" maxlength="5" placeholder="Clan Tag. Max 5 characters.">
	</div>
	<div class="pageSpacer"></div>
	<input type="submit"  class="mainSubmit hoverEffect createClan" value="Create your clan">
</form>

<script>
(function($) {
    var request;
    $("#clan").submit(function(event){
        $('.pageLoader, #page-cover').show();

        event.preventDefault();
        if (request) { request.abort(); }

        request = $.ajax({url: "/clan.php", type: "post", data: $(this).find("input").serialize()});
        request.done(function (response, textStatus, jqXHR){
            $('.pageLoader, #page-cover').fadeOut( "fast");
            var array = JSON.parse(response);
            $.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
            if(array.next == true){
                location.reload();
            }
        });
    });
})(jQuery);
</script>
<?php } ?>
