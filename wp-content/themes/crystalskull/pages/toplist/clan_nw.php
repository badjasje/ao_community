<div class="tab-pane <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>" id="clannw" role="tabpanel" >
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