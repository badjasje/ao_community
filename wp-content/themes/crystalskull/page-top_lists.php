<?php
 /*
 * Template Name: Top lists
 */
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <div class="container">
	       
	    <?php if(get_field('game_status','option') != 'Live'):?>
		<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
		<?php endif;?>
			<center>
			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Province networth</li>
			<li class="tab-link" data-tab="tab-2">Clan points</li>
			<li class="tab-link" data-tab="tab-3">Clan networth</li>

			</ul></center>
		<div id="tab-1" class="tab-content current">
		
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
					'meta_key' => 'networth',
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'number' => $no, 
					'offset' => $offset);

 $user_query = new WP_User_Query($args);
 	$position = 0;
 	
    foreach ( $user_query->results as $user ) {
	   
	    $user_NW = get_user_meta($user->ID, 'networth');
		$user_land = get_user_meta($user->ID, 'land');?>
	    <tr>
				<td><?php echo $offset+$position+=1;?></td>
				<td>
					<?php if(!empty(get_user_meta($user->ID, 'avatar_user', true))):?>
                    
			<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
                    
			<?php endif;?>
				</td>
				<td><a class="<?php echo get_user_meta($user->ID,'status',true);?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a></td>
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
?><br/><br/>
		</div>
		
		
		<div id="tab-2" class="tab-content">
		<table class="responsive-table">
					<tr><td>Position</td>
						<td></td>
						<td>Name</td>
						<td>Clan points</td>
					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    	=> 'meta_value_num',
					'posts_per_page' => -1,
					'post_type'		=>	'clan',
					'meta_key' 		=> 'clan_points',
					'order'     	 => 'DESC');
				$clans = get_posts($args);
				
				foreach ($clans as $clan) {
				
	
	
				?>
				<tr>
				<td><?php echo $position+=1;?></td>
				<td>
					<?php if(!empty(get_post_meta($clan->ID, 'clan_image', true))):?>
                    
			<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_post_meta($clan->ID, 'clan_image', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/no_clan_image.jpg");background-size: cover;'></div>
                    
			<?php endif;?>
				</td>
				<td><a href="<?php echo $clan->guid;?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a></td>
				<td><?php echo ceil(get_post_meta($clan->ID, 'clan_points')[0]);?></td>
				
				
				</tr>
				<?php }?>
			</table>
		</div>
		
		
		
		<div id="tab-3" class="tab-content">
		<table class="responsive-table">
					<tr><td>Position</td>
						<td></td>
						<td>Name</td>
						<td>Clan networth</td>
					</tr>
			
			<?php 
				
				$position = 0;
				$args = array(
					'orderby'    	=> 'meta_value_num',
					'post_type'		=>	'clan',
					'posts_per_page' => -1,
					'meta_key' 		=> 'clan_networth',
					'order'     	 => 'DESC');
				$clans = get_posts($args);
				foreach ($clans as $clan) {
				
					$clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',ceil($tot_networth));
				?>
				<tr>
				
				<td><?php echo $position+=1;?></td>
				<td>
					<?php if(!empty(get_post_meta($clan->ID, 'clan_image', true))):?>
                    
			<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_post_meta($clan->ID, 'clan_image', true);?>");background-size: cover;'></div>
			<?php else:?>
			<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/no_clan_image.jpg");background-size: cover;'></div>
                    
			<?php endif;?>
				</td>
				<td><a href="<?php echo $clan->guid;?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a></td>
				<td>$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth')[0], 0, ',', ' ')?></td>
				
				
				</tr>
				<?php }?>
			</table>
		</div>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>