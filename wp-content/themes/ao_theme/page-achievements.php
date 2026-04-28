<?php
 /*
 * Template Name: Achievements
*/

get_header(); 
$achievements = maybe_unserialize(get_user_meta( $user_ID, 'achievements', true ));


include $_SERVER['DOCUMENT_ROOT'].'/achievements_array.php';

?>

<div class="row pageRow">	
	<div class="blockHeader">
        Available achievements
    </div>
	<?php foreach ($achievementsArray as $key => $singleAchievement):?>
	<div 
		<?php if($achievements[$key] != 1):?> 
			style="opacity: 0.7;background-color:#545445"
		<?php endif;?> 
		<?php if($achievements[$key] == 1):?> 
			style="background-color:#46765E;color:#fff;"
		<?php endif;?> 
			
			
		class="col-md-2  achievementBlock">
	
		<?php if($achievements[$key] == 1):?>
			<h3 style="color:#fff;"><i  class="fas fa-medal"></i> <?php echo $singleAchievement['title'];?></h3>
		<?php else:?>
			<h3><?php echo $singleAchievement['title'];?></h3>
		<?php endif;?>
		<?php echo $singleAchievement['description'];?>
		<div class="xpptsblock">Experience points: <?php echo $singleAchievement['xp'];?></div>
	</div>
	
	<?php endforeach;?>
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();