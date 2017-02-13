<?php
 /*
 * Template Name: Top lists Overhauled
 */
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
				<div class="container">
<table class="responsive-table">
 <?php 

 $no=15;// total no of author to display

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if($paged==1){
      $offset=0;  
    }else {
       $offset= ($paged-1)*$no;
    }
	$args = array(
					'orderby'    => 'meta_value_num',
					'meta_key' => 'networth',
					'order'      => 'DESC',
					'number' => $no, 
					'offset' => $offset);

 $user_query = new WP_User_Query($args);
 	$position = 0;
    foreach ( $user_query->results as $user ) {
	    count_all_stats($user->ID);
	    $user_NW = get_user_meta($user->ID, 'networth');
		$user_land = get_user_meta($user->ID, 'land');?>
	    <tr>
				<td><?php echo $offset+$position+=1;?></td>
				<td><a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' #('.$user->ID;?>)</a></td>
				<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php 
					$user_clan = get_user_meta($user->ID, 'clan_id_user')[0];
					if($user_clan != 0){echo '<a href="'.get_the_permalink($user_clan).'">'.get_the_title($user_clan).' (#'.$user_clan.')</a>';}else{echo 'none';}?></td>
				
				
				</tr>
    
 <?php }?>           
</table>
<?php
            $total_user = $user_query->total_users;  
            $total_pages=ceil($total_user/$no);

              echo paginate_links(array(  
                  'base' => get_pagenum_link(1) . '%_%',  
                  'format' => '?paged=%#%',  
                  'current' => $paged,  
                  'total' => $total_pages,  
                  'prev_text' => 'Previous',  
                  'next_text' => 'Next',
                  
                )); 
?>
				</div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>