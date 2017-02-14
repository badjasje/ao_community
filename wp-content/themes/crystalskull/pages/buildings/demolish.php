<div class="tab-pane <?php echo $activeTab === 'demolish' ? 'active': ''; ?>" id="demolish" role="tabpanel">

    <form class="form" action="<?php echo home_url() ?>/demolish.php" name="" id="demolish" method="post">


        <div class="container2">
            <table class="responsive-table">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Owned</th>
                    <th scope="col">Price to demolish</th>
                    <th scope="col">Max</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
				<?php // DEMOLISHHHHH TABLE
				$totalbuildings = 0;
				foreach ($buildings as $key => $order) {
					$units_owned = get_user_meta($user_ID, $key);
					if ($units_owned[0] > 0) {
						?>

                        <tr>

                            <th scope="row">
								<?php echo $order['normalname']; ?>
                            </th>

                            <td data-title="Owned">
								<?php echo $units_owned[0];
								$totalbuildings += $units_owned[0]; ?>
                            </td>

                            <td data-title="Price to demolish">
                                $ <?php echo floor($order['price'] * 0.15); ?>
                            </td>


                            <td data-title="Max">
								<?php $max_demo_money = floor($totalmoney[0] / ($order['price'] * 0.15));
								$max_owned            = $units_owned[0];

								if ($order['normalname'] == 'Airfield') {

									$max_demo_money = floor($max_demo_money - ($totalair / 10));

									if ($max_demo_money < 0) {
										$max_demo_money = 0;
									}

									$max_owned = floor($max_owned - ($totalair / 10));

									if ($max_owned < 0) {
										$max_owned = 0;
									}
								}

								if ($order['normalname'] == 'Shipyard') {

									$max_demo_money = floor($max_demo_money - ($totalsea / 5));

									if ($max_demo_money < 0) {
										$max_demo_money = 0;
									}

									$max_owned = floor($max_owned - $totalsea / 5);

									if ($max_owned < 0) {
										$max_owned = 0;
									}
								}

								if ($order['normalname'] == 'Baracks') {

									$max_demo_money = floor($max_demo_money - ($totalinf / 20));

									if ($max_demo_money < 0) {
										$max_demo_money = 0;
									}

									$max_owned = floor($max_owned - ($totalinf / 20));

									if ($max_owned < 0) {
										$max_owned = 0;
									}
								}
								if ($order['normalname'] == 'Warfactory') {

									$max_demo_money = floor($max_demo_money - ($totalveh / 10));

									if ($max_demo_money < 0) {
										$max_demo_money = 0;
									}

									$max_owned = floor($max_owned - ($totalveh / 10));

									if ($max_owned < 0) {
										$max_owned = 0;
									}
								}


								?>
                                <span class="allbutton"
                                      id="demobutton<?php echo $key; ?>"><?php echo min($max_demo_money, $max_owned); ?></span>
                            </td>

                            <th colspan="2">
                                <input class="small_input" type="text" id="demo<?php echo $key; ?>"
                                       name="<?php echo $key; ?>"/>
                            </th>

                        </tr>

                        <script type="text/javascript">
                            jQuery("#demobutton<?php echo $key;?>").click(function () {
                                jQuery("#demo<?php echo $key;?>").val("<?php echo min($max_demo_money, $max_owned);?>");
                            });
                        </script>

					<?php }
				} ?>
            </table>
            </tbody>
        </div>


        <br/>

        <div class="button_padding"><input type="submit" value="DEMOLISH" class=""></div>
        <div class="footer_continue">
            <input type="submit" value="DEMOLISH" class="">
        </div>


    </form>


</div>