<?php
 /*
 * Template Name: Trophies
 */
get_header();
include 'trophiesArray.php';
 ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            
	            
<div class="row">
	
	<?php foreach ($trophies as $key => $trophy) {
		$trophyName = $trophy['name'];
		$trophyDesc = $trophy['description'];
		$numberStars = $trophy['stars'];
		$trophyType = $trophy['type'];
	?>
		<div class="col-md-3 medal_col">
			<div class="row medal_box">
				<div class="col-md-12 trophy_header">
					<center>
						<h2>
							<?php echo str_repeat('<i class="fa fa-star"></i>', $numberStars);?>
							<?php echo str_repeat('<i class="fa fa-star-o"></i>', 5-$numberStars);?>
						<br/>
						<span class="trophyName"><strong><?php echo $trophyName;?></strong></span>
						</h2>
					</center>
					  
				</div>
				<div class="col-md-12 trophyDescr">
					<?php echo $trophyDesc;?>
				</div>
				<div class="col-md-6 col-xs-6 medal_row statusTrophy">Status</div>
				<div class="col-md-6 col-xs-6 medal_row statusTrophy"><strong>Completed</strong></div>
			</div>
		</div>

	<?php }?>
</div>        
	            
	            

	            
	            
       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>