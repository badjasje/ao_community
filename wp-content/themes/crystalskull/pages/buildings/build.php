<div class="tab-pane <?php echo $activeTab === 'build' ? 'active': ''; ?>" id="build" role="tabpanel">


    <form class="form" action="<?php echo home_url() ?>/build.php" name="" id="market" method="post">
        <input type="hidden" name="token" value="<?php echo $newToken; ?>">


        <table class="responsive-table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Owned</th>
                <th scope="col">Price</th>
                <th scope="col">Att/Life</th>
                <th scope="col">Power usage</th>
                <th scope="col">Targets</th>
                <th scope="col">Max</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
			<?php // building TABLE
			$totalbuildings = 0;
			foreach ($buildings as $key => $order) {
				$units_owned = get_user_meta($user_ID, $key);

				?>

                <tr>
                    <th scope="row">
						<?php echo $order['normalname']; ?>
						<?php if ($order['description']): ?>
                            <span class="hover-tip" data-toggle="tooltip"
                                  data-original-title="<?php echo $order['description']; ?>" data-placement="right"><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif; ?>
                    </th>

                    <td data-title="Owned">
						<?php echo $units_owned[0];
						$totalbuildings += $units_owned[0]; ?>
                    </td>

                    <td data-title="Price">
	                    <span class="hover-tip"  data-toggle="tooltip" data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per building." data-placement="bottom">
                        $ <?php echo $order['price']; ?>
	                    </span>
                    </td>

                    <td data-title="Att/Life">
						<?php echo $order['attack']; ?>/<?php echo $order['life']; ?>
                    </td>

                    <td data-title="Power usage">
						<?php if ($order['power'] != 0) {
							echo $order['power'];
						} else {
							echo 'n.a';
						} ?>
                    </td>

                    <td data-title="Targets">
						<?php

						$i   = 0;
						$len = count($order['attacks']);
						if (empty($order['attacks'])) {
							echo '&nbsp;';
						}
						foreach ($order['attacks'] as $attack) {
							if ($i == $len - 1) {
								echo $attack;
							} else {
								echo $attack . ', ';
							}

							$i++;;


						} ?>
                    </td>

                    <td data-title="Max">
						<?php $max_money = floor($totalmoney[0] / $order['price']);
						$max_turns       = floor($totalturns[0] * $turns_multiplier);
						$max_land        = floor(($land[0] - $builtland[0]) / 20);
						?>

                        <span class="allbutton"
                              id="button<?php echo $key; ?>"><?php echo(min($max_money, $max_land, $max_turns)); ?></span>
                    </td>

                    <th colspan="2">
                        <input class="small_input" type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>"/>
                    </th>

                </tr>

                <script type="text/javascript">
                    jQuery("#button<?php echo $key;?>").click(function () {
                        jQuery("#<?php echo $key;?>").val("<?php echo min($max_land, $max_turns, $max_money);?>");

                    });

                </script>

			<?php } ?>
            </tbody>
        </table>


        <div class="button_padding"><input type="submit" value="Build" class=""></div>
        <div class="footer_continue">
            <input type="submit" value="Build" class="">
        </div>


    </form>


</div>