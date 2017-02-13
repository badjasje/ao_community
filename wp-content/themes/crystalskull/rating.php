
<?php
$categories = wp_get_post_categories(get_the_ID());
if(!isset($categories[0]))$categories[0]='';
$cat_data = get_option("category_$categories[0]");

$overall_rating = get_post_meta(get_the_ID(), 'overall_rating', true);

if(!empty($overall_rating)){ ?>
	<div class="carousel_rating">
<?php }

if($overall_rating != "0" && $overall_rating=="0.5"){
?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star-half-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "1"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "1.5"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
	<i class="fa fa-star"></i>
	<i class="fa fa-star-half-o"></i>
	<i class="fa fa-star-o"></i>
	<i class="fa fa-star-o"></i>
	<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "2"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "2.5"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-half-o"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "3"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "3.5"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-half-o"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "4"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-o"></i>
	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "4.5"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star-half-o"></i>

	</div>
	<?php } ?>

	<?php
if($overall_rating != "0" && $overall_rating == "5"){
	?>
	<div  style="text-shadow: 0px 0px 10px <?php echo esc_attr($cat_data['catBG']); ?>">
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
	</div>
	<?php }
if(!empty($overall_rating)){ ?>
	</div><!-- blog-rating -->
<?php }
