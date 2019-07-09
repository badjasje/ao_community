<?php

?>
<div class="row row-no-padding fw-row">
	<? foreach($province->getMedals(null, true) as $medal) { ?>
		<div class="col-md-4 medal_col">
			<div class="row medal_box">
				<div class="col-md-12 medal_header">
					<strong><?=$medal['name']?>
						<span class="hover-tip" data-toggle="tooltip" data-original-title="<?=$medal['description']?>" data-placement="bottom">
						<i class="fa fa-info-circle" aria-hidden="true"></i></span>
					</strong>
				</div>
				<div class="col-md-6 col-xs-6 medal_row">Position:</div>
				<div class="col-md-6 col-xs-6 medal_row"><?=$medal['position']?></div>
				<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
				<div class="col-md-6 col-xs-6 medal_row"><?=$medal['next']?></div>
				<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
				<div class="col-md-6 col-xs-6 medal_row"><?=$medal['prev']?></div>
			</div>
		</div>
	<? } ?>
</div>