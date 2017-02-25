<div class="tab-pane <?php echo $activeTab === 'clanpoints' ? 'active' : ''; ?>" id="clanpoints" role="tabpanel">
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

				<td><a href="<?php echo get_permalink($clan); ?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a></td>
				<td><?php echo ceil(get_post_meta($clan->ID, 'clan_points')[0]);?></td>


			</tr>
		<?php }?>
	</table>
</div>