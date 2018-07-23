<?php if(!in_array($startingbonus, $boni)): // Check if player has starting bonus ?>
	<div class="startingBonusPicker">
	    <div id="startingBonus" class="blockHeader">Welcome back! You either died, reset, or this is a brand new round of Assault.Online.<br/><h3 style="color:#fff !important;"> Please pick a starting strategy. </h3>Note that once selected, only a death or reset will let you change your mind. If you are new to Assault.online, we strongly recommend you review the <strong><a href="./getting-started" target="_blank">Getting Started</a></strong> guide before selecting a bonus/strategy.<br/><br/></div>
			
	    	<form class="form" name="" id="pickStartingBonus" method="post">	
	       	            
				<input required style="display:none;" type="radio" name="bonustype" id="offensive" value="offensive" >
				
					<label class="startingbonus" for="offensive">
						<h3 class="startinghead"><i class="fa fa-fire" aria-hidden="true"></i> Offensive</h3>
						Gain twice the land and money during every ground, regular or air & sea attack. You will receive an additional 75 turns.
					</label>
	    
	    
				<input required style="display:none;" type="radio" name="bonustype" id="defensive" value="defensive" >
	    	
					<label class="startingbonus darkerBonus" for="defensive">
						<h3 class="startinghead"><i class="fas fa-shield-alt" aria-hidden="true"></i> Defensive</h3>
						Constructing 10 buildings per turn by default (to a maximum of 20 with full research), 
						plus 20% extra defense for all defending units, plus 10% time deduction when researching, plus 3 500m<sup>2</sup> of land.
					</label>
	    
	    
				<input required style="display:none;" type="radio" name="bonustype" id="finance" value="finance" >
	    	
					<label class="startingbonus darkerBonus" for="finance">
						<h3 class="startinghead"><i class="fas fa-dollar-sign" aria-hidden="true"></i> Finance</h3>
						Hourly income increased by 10%. Your bank capacity is raised by 50%. You will receive an additional$ 400 000
					</label>
					
	    
				<input required style="display:none;" type="radio" name="bonustype" id="shipping" value="shipping" >
					
					<label class="startingbonus" for="shipping">
						<h3 class="startinghead"><i class="fa fa-truck" aria-hidden="true"></i> Shipping</h3>
						Missile orders ship 50% faster, plus ability to choose exact arrival time for units (up to 6 hours delayed), 
						plus 10% default market discount (max 40% with research), 2 500 m<sup>2</sup> land and $250 000 money.
					</label>
	    
	    
				<input type="submit" value="Pick starting bonus" class="mainSubmit hoverEffect bonusSubmit">
				
			</form>
			
 <div class="pageSpacer"></div>
	</div>
 
 
 <script>
(function($) {

var request;

$("#pickStartingBonus").submit(function(event){
	$('.bonusSubmit').remove();
	$('.startingBonusPicker').hide(250);
	
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();
    if (request) {request.abort();}

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/startingbonus.php",
        type: "post",
        data: serializedData
    });

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
			$('#money').html(number_format(array.money, 0, ',', ' '));
			$('#turns').html(number_format(array.money, 0, ',', ' '));
			$('#networth').html(number_format(array.money, 0, ',', ' '));
			
});	});	
})(jQuery);
</script>
 
 
 
 
 
 
<?php endif; // End starting bonus picking ?>
