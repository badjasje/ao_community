<?php
 /*
 * Template Name: Login Tracking
*/

get_header(); 

$loginArray = get_post_meta( 139664, 'login_array_general', true );
?>

<div class="row pageRow">	
	<?php
		echo '<pre>';
		print_r($loginArray);
		echo '</pre>';
		?>
</div> <!-- end .pageRow -->
<?php
get_footer();