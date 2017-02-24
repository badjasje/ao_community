<div class="tab-pane <?php echo $activeTab === 'provicenw' ? 'active' : ''; ?>"  id="provicenw" role="tabpanel">

	<div>
		<table class="responsive-table">
			<?php

			$no = 15;// total no of author to display

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$offset = $paged == 1 ? 0 : ($paged - 1) * $no;

			$args = array(
				'meta_key' => 'networth',
				'orderby'  => 'meta_value_num',
				'order'    => 'DESC',
				'number'   => $no,
				'offset'   => $offset
			);

			$user_query = new WP_User_Query($args);
			$position   = 0;

			foreach ($user_query->results as $user) :

				$user_NW   = get_user_meta($user->ID, 'networth');
				$user_land = get_user_meta($user->ID, 'land'); ?>
				<tr>
					<td><?php echo $offset + $position += 1; ?></td>
					<td>
						<?php if (!empty(get_user_meta($user->ID, 'avatar_user', true))): ?>
							<div style='border-radius: 100%;height:40px;width:40px;background: url("<?php echo get_user_meta($user->ID, 'avatar_user', true); ?>");background-size: cover;'></div>
						<?php else: ?>
							<div style='border-radius: 100%;height:40px;width:40px;background: url("/wp-content/uploads/2016/11/default_large.png");background-size: cover;'></div>
						<?php endif; ?>
					</td>
					<td>
						<a class="<?php echo get_user_meta($user->ID, 'status', true); ?>" href="/users/profile/?id=<?php echo $user->ID; ?> "><?php echo $user->display_name . ' (#' . $user->ID; ?>)</a></td>
					<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ') ?></td>
					<td>
						<?php
							$user_clan = get_user_meta($user->ID, 'clan_id_user')[0];

							if ($user_clan != 0):
						?>
							<a href="<?php echo get_the_permalink($user_clan); ?>"><?php echo get_the_title($user_clan) . ' (#' . $user_clan . ')'; ?></a>
						<?php else : ?>
							none
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>

		<div class="pagination-bar">
			<?php
			$total_user  = $user_query->total_users;
			$total_pages = ceil($total_user / $no);

			echo paginate_links(array(
				'base'      => get_pagenum_link(1) . '%_%',
				'format'    => 'page/%#%',
				'current'   => $paged,
				'total'     => $total_pages,
				'prev_text' => 'Previous',
				'next_text' => 'Next',

			));
			?>
		</div>
	</div>
</div>