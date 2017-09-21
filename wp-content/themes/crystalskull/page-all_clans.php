<?php
 /*
 * Template Name: All clans
 */
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'all';

$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user');
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <?php if($clan_ID == 0):?>	
			
			
			
		<table>
			<tr>
				<td>Clan
				</td>
				<td>Total networth
				</td>
			</tr>
		<?php $args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'clan'
			);
			$clans = get_posts($args);
			foreach ($clans as $clan) { 
				
			
				
						
			?>
			<tr>
				<td><a href="<?php echo get_the_permalink($clan->ID);?>"><?php echo get_the_title($clan->ID). ' (#'.$clan->ID;?>)</a>
				</td>
				<td>$ 
				<?php $clan_members = get_post_meta($clan->ID,'clan_members');
					
					$tot_networth = 0;
					foreach ($clan_members[0] as $member) {
					$networth = get_user_meta($member, 'networth');
					$tot_networth+=$networth[0];}
					update_post_meta($clan->ID,'clan_networth',$tot_networth);
					echo number_format($tot_networth, 0, ',', ' ');
					
				?>
				</td>
			</tr>
			
			<?php }?>
		</table>
		
		
		
		<?php else:?>
		
		
			<ul id="clans-tab" class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item <?php echo $activeTab === 'all' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#all" href="?tab=all" role="tab">All clans</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#in-range" href="?tab=in-range" role="tab">In range</a>
				</li>
			</ul>

		<div class="tab-content current build_content tabbed-table">
			<div class="tab-pane <?php echo $activeTab === 'all' ? 'active' : ''; ?>"  id="all" role="tabpanel">
				
				
			<div class="row toplist_block">	
				
				<div class="row clan_header_row storeDetails-heads">

				<div class="col-md-1"></div>
				<div class="col-md-6"><strong>Name</strong></div>
				<div class="col-md-4"><strong>Clan networth</strong></div>
				<div class="col-md-1"><strong>Members</strong></div>
			</div>

		
			<?php $args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'clan'
				);
				$clans = get_posts($args);
				foreach ($clans as $clan) {
					$clan_members = get_post_meta($clan->ID,'clan_members');
					
				?>
				
				<div class="row clan_profile_row2">
			
					<div class="col-md-1">
						<?php echo clan_avatar($clan->ID,'');?>
					</div>
					
					
					<div class="col-md-6 clan_column center_clan_col border_bottom_mobile">
						<a href="<?php echo get_permalink($clan); ?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a>		
					</div>
					
				
					
					<div class="col-md-4 clan_column border_bottom_mobile">
						
						<span class="clan_data_left">Clan networth</span>
						<span class="clan_data_right store-pop-span2">
							$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth',true), 0, ',', ' ')?>
						</span>
					
					</div>
					
					
					<div class="col-md-1 clan_column">
						
						<span class="clan_data_left">Members</span>
						<span class="clan_data_right store-pop-span2">
							<?php echo count($clan_members);?>
						</span>
					
					</div>
				</div> <! // Close profile row -- >
				
				
				
				
				
				

				<?php }?>
			</div>
		</div> <!-- CLOSE TAB 1 -->





			<div class="tab-pane <?php echo $activeTab === 'in-range' ? 'active' : ''; ?>"  id="in-range" role="tabpanel">


			<div class="row toplist_block">	
				
				<div class="row clan_header_row storeDetails-heads">

				<div class="col-md-1"></div>
				<div class="col-md-6"><strong>Name</strong></div>
				<div class="col-md-4"><strong>Clan networth</strong></div>
				<div class="col-md-1"><strong>Members</strong></div>
			</div>
			<?php $args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'clan'
				);
				$clans = get_posts($args);




		$dec_clan_members = get_post_meta($clan_ID[0],'clan_members');

		$dec_tot_networth = 0;
			foreach ($dec_clan_members[0] as $dec_member) {
				$dec_networth = get_user_meta($dec_member, 'networth');
				$dec_tot_networth+=$dec_networth[0];
	}




				foreach ($clans as $clan) {


				$clan_members = get_post_meta($clan->ID,'clan_members');

						$tot_networth = 0;
						foreach ($clan_members[0] as $member) {
						$networth = get_user_meta($member, 'networth');
						$tot_networth+=$networth[0];}
						update_post_meta($clan->ID,'clan_networth',$tot_networth);

				?>
				<?php if (($tot_networth > $dec_tot_networth/1.4 && $tot_networth < $dec_tot_networth*1.4)){	?>
				<div class="row clan_profile_row2">
			
					<div class="col-md-1">
						<?php echo clan_avatar($clan->ID,'');?>
					</div>
					
					
					<div class="col-md-6 clan_column center_clan_col border_bottom_mobile">
						<a href="<?php echo get_permalink($clan); ?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a>		
					</div>
					
				
					
					<div class="col-md-4 clan_column border_bottom_mobile">
						
						<span class="clan_data_left">Clan networth</span>
						<span class="clan_data_right store-pop-span2">
							$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth',true), 0, ',', ' ')?>
						</span>
					
					</div>
					
					
					<div class="col-md-1 clan_column">
						
						<span class="clan_data_left">Members</span>
						<span class="clan_data_right store-pop-span2">
							<?php echo count($clan_members);?>
						</span>
					
					</div>
				</div> <! // Close profile row -- >

				<?php }?><?php }?>
			</div>




			</div> <!-- CLOSE TAB 2 -->
		</div>
		
		
		
		
		<?php endif;?>

            
            </div>
        </div>
    
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
    });
</script>
<?php get_footer(); ?>