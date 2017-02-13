<?php
 /*
 * Template Name: User search
 */
if( isset( $_GET['usersearch'] ) ){
    //$wpdb needs to be made global, this lets us use it on a page template
    global $wpdb;
    //some cleanup to the search term, as well as caching it to $usersearch
    $usersearch = stripslashes( trim($_GET['usersearch']) );
    //$wpdb->prepare() is a fast and safe method for performing a MySQL query
    $stmt = $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta AS um
        WHERE ( um.meta_key='nickname' AND um.meta_value LIKE '%%%s%%') OR
        (um.meta_key='user_nicename' AND um.meta_value LIKE '%%%s%%')
        ORDER BY um.meta_value 
        LIMIT 150",
        $usersearch, $usersearch );
    //results are cached in the variable $results using get_col()
    $results = $wpdb->get_col( $stmt );
} 

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php $st = (isset($_GET['usersearch']) ? $_GET['usersearch'] : '' ); ?>
<form action="" method="get">
    <input name="usersearch" id="usersearch" value="<?php echo $st; ?>" type="text" placeholder="Search by username">
    <input name="dosearch" type="submit" value="Submit">
</form>
<br/>
<table class="responsive-table">
 
       
       
        <?php foreach($results as $u){
	        $member_data = get_userdata($u);?>
        <tr>
	        <th data-title="User"><a class="<?php echo get_user_meta($u,'status',true);?>" href="/users/profile/?id=<?php echo $u; count_all_stats($member);?>"><?php echo $member_data->display_name.' (#'.$u.')';?></a>

			</th>
			<th><?php echo number_format(get_user_meta($u, 'land', true), 0, ',', ' '); ?> m<sup>2</sup>
			</th>
			<th>$ <?php echo number_format(get_user_meta($u, 'networth', true), 0, ',', ' '); ?>
			</th>
        </tr>
        <?php } ?>

</table>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>