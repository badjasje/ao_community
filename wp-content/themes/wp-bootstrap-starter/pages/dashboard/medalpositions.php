<?php
$mog_next = !empty($userData['mog_next'][0]) ? $userData['mog_next'][0] : 0;
$mog_prev = !empty($userData['mog_prev'][0]) ? $userData['mog_prev'][0] : 0;

$mot_next = !empty($userData['mot_next'][0]) ? $userData['mot_next'][0] : 0;
$mot_prev = !empty($userData['mot_prev'][0]) ? $userData['mot_prev'][0] : 0;

$modes_position = !empty($userData['modes_position'][0]) ? $userData['modes_position'][0] : 0;
$modes_next = !empty($userData['modes_next'][0]) ? $userData['modes_next'][0] : 0;
$modes_prev = !empty($userData['modes_prev'][0]) ? $userData['modes_prev'][0] : 0;

$mor_position = !empty($userData['mor_position'][0]) ? $userData['mor_position'][0] : 0;
$mor_next = !empty($userData['mor_next'][0]) ? $userData['mor_next'][0] : 0;
$mor_prev = !empty($userData['mor_prev'][0]) ? $userData['mor_prev'][0] : 0;

?>
<div class="row row-no-padding fw-row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Earth
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Highest land area at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>

				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_next'][0];?> m<sup>2</sup></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_prev'][0];?> m<sup>2</sup></div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Honor
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most clan points gained by a province." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>

				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_next'][0];?> pts</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_prev'][0];?> pts</div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Growth
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Highest networth at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>

				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mog_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($mog_next, 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($mog_prev, 0, ',', ' ');?></div>
		</div>
	</div>
</div>

<div class="pageSpacer"></div>
<div class="row row-no-padding fw-row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Courage
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most attacks made by a province during clan war." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_next'][0];?> attacks</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_prev'][0];?> attacks</div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Death
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Killed most provinces during clan wars." data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_next'][0];?> kills</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_prev'][0];?> kills</div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Thievery
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most money stolen at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mot_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($mot_next, 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($mot_prev, 0, ',', ' ');?></div>
		</div>
	</div>
</div>

<div class="pageSpacer"></div>
<div class="row row-no-padding fw-row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Destruction
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most networth damage made using missiles." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $modes_position;?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($modes_next, 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($modes_prev, 0, ',', ' ');?></div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Devastation
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most networth damage done in a single attack. Attack must be done in clan war." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['modev_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Networth damage:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['modev_damage'][0],0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row"></div>
			<div class="col-md-6 col-xs-6 medal_row"></div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Recruitment
					<span class="hover-tip"  data-toggle="tooltip" data-original-title="Succesfully invited most new players" data-placement="bottom">
						<i class="fa fa-info-circle" aria-hidden="true"></i>
					</span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $mor_position; ?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $mor_next;?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $mor_prev;?></div>
		</div>
	</div>
</div>