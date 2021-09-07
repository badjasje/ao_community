<?php
 /*
 * Template Name: XP points
*/

get_header(); 
$achievements = maybe_unserialize(get_user_meta( $user_ID, 'achievements', true ));


include $_SERVER['DOCUMENT_ROOT'].'/xparray.php';

?>

<div class="row pageRow">	
	<div class="blockHeader">
        Experience points
    </div>
	<?php
		
		$count = 0;
		 foreach ($xparray as $key => $item):
		 $count++;
		 if($count==5){
			 $count = 1;
		 }
		 ?>
	<div class="col-md-4 celBlock statCol-<?php echo $count;?>">
		<?php echo $item['title'];?>
	</div> 
		
	
	<?php endforeach;?>
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();