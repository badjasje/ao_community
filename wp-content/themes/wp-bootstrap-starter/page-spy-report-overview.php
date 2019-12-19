<?php
 /*
 * Template Name: Spy report overview
*/
if(!is_user_logged_in()) {
	exit(wp_redirect(home_url('/')));
}

$user = CurrentUser::make();
$province = $user->getProvince();

get_header();
$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94";
$clan_ID = $_GET['id'];
$clanData = get_post_meta($clan_ID);
$clan_members = maybe_unserialize($clanData['clan_members'][0]);

global $userId;
global $userData;

$visiting_user = $userId;
$clanleader = $clanData['clan_leader'][0];
$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = (isset($clanData['ct_'.$i]) ? $clanData['ct_'.$i][0] : 0);
}

$visiting_clan = $userData['clan_id_user'][0];
if($visiting_clan != 0){
	$clan_NW = get_post_meta($visiting_clan, 'clan_networth', true);
}
$visiting_members = maybe_unserialize(get_post_meta($visiting_clan,'clan_members',true));
$args = array(
	'post_type'		=>	'clan',
	'posts_per_page' => -1,
);

$clans = get_posts($args);

$units = Units::get();
$buildings = Buildings::get();
?>
<div class="row pageRow">

	<div style="padding:0px;width:100%" class="attackDropdown statCol-2 no-gutters">
		<form>
			<select id="clan" name="clan" class="attackTypeInput" onchange="if (this.value) window.location.href=this.value">
				<option disabled selected name="clan" value="<?php echo $clan_ID;?>">
					Currently viewing: <?php echo get_the_title($clan_ID);?> (#<?php echo $clan_ID;?>)
				</option>

				<?php if($visiting_clan != 0):?>
					<option disabled  name="clan" value="<?php echo $clan_ID;?>">
						Clans in range &rarrb;
					</option>
					<?php foreach ($clans as $clan) {
					$tot_networth = get_post_meta($clan->ID, 'clan_networth', true);
					?>
					<?php if (($tot_networth > $clan_NW/1.4 && $tot_networth < $clan_NW*1.4)){	?>
						<option class="inrange" name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>">
							<strong><?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)</strong>
						</option>
					<?php }}?>
				<?php endif;?>

				<option disabled  name="clan" value="<?php echo $clan_ID;?>">
					Clans out of range &rarrb;
				</option>

				<?php foreach ($clans as $clan) {?>
					<option name="clan" value="/spy-report-overview/?id=<?php echo $clan->ID;?>">
						<?php echo get_the_title($clan->ID);?> (#<?php echo $clan->ID;?>)
					</option>
				<?php }?>
			</select>
		</form>
	</div>
	<?php

	$NRmembers = count($clan_members);
	$counter = $count = 0;

	foreach ($clan_members as $key => $member):
		$unitarray = array();
		$bldarray = array();
		$type_array = array();
		$attack_array = $attacks = array();

		// Get latest unit report
		$unitargs = array(
			'posts_per_page'   => 1,
			'author__in'   => $visiting_members,
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'	 	=> 'spied_id',
					'value'	  	=> $member,
					'compare' 	=> '=',
				),
				array(
					'key'	 	=> 'clan_id_report',
					'value'	  	=> $visiting_clan,
					'compare' 	=> '=',
				),
				array(
					'key'	 	=> 'spy_type',
					'value'	  	=> 'spy',
					'compare' 	=> '=',
				),
			),
			'post_type'        => 'spy_rep',
		);

		$unitRep = get_posts( $unitargs );
		$unitRep_ID = (isset($unitRep[0]) ? $unitRep[0]->ID : array());
		if(count($unitRep) > 0){
			$unitRep_date = get_the_date('G:i | d-m-Y',$unitRep_ID);
			$unitrepStamp = get_the_time('U',$unitRep_ID);
			$unitarray = maybe_unserialize(get_post_meta($unitRep_ID, 'spy_array', true));

			if(is_array($units)) {
				foreach ($units as $unit) {
					foreach ($unitarray as $unitname => $amount) {
						if($unitname == $unit['normalname']){
							if(is_array($attacks)) $attack_array[] = array_shift($attacks);
							$attacks = $unit['attacks'];
							if(!empty($attacks)){
								$attack_array[] = array_shift($attacks);
							}
						}
					}
				}
			}
			$attack_array = array_diff($attack_array,array('n.a',''));
			$attack_array = array_unique($attack_array);

			if(is_array($units)) {
				foreach ($units as $unit) {
					foreach ($unitarray as $unitname => $amount) {
						if($unitname == $unit['normalname'] && $unitname != 'Spy' && $unitname != 'SR-71 Spyplane'){
							$types = $unit['type'];
							$type_array[] = $types;
						}
					}
				}
			}
			$type_array = array_diff($type_array,array('n.a',''));
			$type_array = array_unique($type_array);
		}else{
			$unitRep_date = 'No reports';
		}

		// Get latest building report
		$buildingargs = array(
			'posts_per_page'   => 1,
			'author__in'   => $visiting_members,
			'meta_query'	=> array(
				'relation'		=> 'AND',
				array(
					'key'	 	=> 'spied_id',
					'value'	  	=> $member,
					'compare' 	=> '=',
				),
				array(
					'key'	 	=> 'clan_id_report',
					'value'	  	=> $visiting_clan,
					'compare' 	=> '=',
				),
				array(
					'key'	 	=> 'spy_type',
					'value'	  	=> 'spyplane',
					'compare' 	=> '=',
				),
			),
			'post_type'        => 'spy_rep',
		);

		$bldRep = get_posts( $buildingargs );
		$bldRep_ID = (isset($bldRep[0]) ? $bldRep[0]->ID : array());

		if(count($bldRep) > 0){
			$bldRep_date = get_the_date('G:i | d-m-Y',$bldRep_ID);
			$bldrepStamp = get_the_time('U',$bldRep_ID);
			$bldarray = maybe_unserialize(get_post_meta($bldRep_ID, 'spy_array', true));
		}else{
			$bldRep_date = 'No reports';
		}

		$regNW = 0;
		$regLand = 0;
		if($unitrepStamp > $bldrepStamp){
			$regNW = get_post_meta($unitRep_ID, 'spied_nw', true);
			$regLand = get_post_meta($unitRep_ID, 'spied_land', true);
		}else{
			$regNW = get_post_meta($bldRep_ID, 'spied_nw', true);
			$regLand = get_post_meta($bldRep_ID, 'spied_land', true);
		}

		$land = get_user_meta($member, 'land', true);

		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
		}
		$member_data = get_userdata($member);
		$last_online = get_user_meta($member, 'last_online',true);
		$spiednr = get_user_meta($user_ID, 'spied_current_clan',true);
		$status = get_user_meta($member, 'status', true);

		if(!empty($last_online)){
			$last_seen = $timestamp - $last_online;
		}
		?>
		<div class="blockHeader">
			<?php echo get_user_name($member);?>
		</div>

		<!-- Row 1 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Networth current</span>
				<span class="dataVisibleRight">
					<?php echo networth_range($member);?>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Networth registered</span>
				<span class="dataVisibleRight">
					$ <?php echo number_format($regNW, 0, ',', ' '); ?>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Land current</span>
				<span class="dataVisibleRight">
					<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Land registered</span>
				<span class="dataVisibleRight">
					<?php echo number_format($regLand, 0, ',', ' '); ?> m<sup>2</sup>
				</span>
			</div>

		</div> <!-- //Close Row 1 -->

		<!-- Row 2 -->
		<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.4-($count/70);?>);">
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Units spied date</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $unitRep_date; ?>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Buildings spied date</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo $bldRep_date; ?>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Unit types</span>
				<span class="dataVisibleRight">
					<?php echo implode(", ", array_values($type_array));?>
				</span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Can attack</span>
				<span class="dataVisibleRight store-pop-span2">
					<?php echo implode(", ", array_values($attack_array));?>
				</span>
			</div>
		</div><!-- //Close Row 2 -->

		<!-- Button row -->
		<div class="row fw-row no-gutters">
			<div class="col-md-4 celBlock" style="padding:0px">
				<a href="<?php echo get_site_url();?>/attack/?id=<?php echo $member;?>">
					<button class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/70);?>);">
						<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
					</button>
				</a>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button <?php if(count($unitarray) == 0){echo 'disabled';}?> viewtype="units" member-id="<?php echo $member;?>" class="cancelButton hoverEffect viewmemberinfo" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.95-($count/70);?>);" type="submit">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units
				</button>
				<div class="memberInfo units_<?php echo $member;?>">
					<?php foreach($unitarray as $key => $unitvalue){ ?>
						<span class="dataVisibleLeft"><?php echo $key;?></span>
						<span class="dataVisibleRight"><?php echo $unitvalue;?></span><br/>
					<?php }?>
				</div>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button <?php if(count($bldarray) == 0){echo 'disabled';}?> viewtype="buildings" member-id="<?php echo $member;?>" class="cancelButton hoverEffect viewmemberinfo" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 0.9-($count/70);?>);" type="submit">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings
				</button>
				<div class="memberInfo buildings_<?php echo $member;?>">
					<?php foreach($bldarray as $key => $bld){ ?>
						<span class="dataVisibleLeft"><?php echo $key;?></span>
						<span class="dataVisibleRight"><?php echo $bld;?></span><br/>
					<?php }?>
				</div>
			</div>
		</div>
		<!-- // Button row -->

		<?
		echo $province->get_spy_buttons($member);
		?>

		<div class="pageSpacer"></div>
		<?php
		$count++;
	endforeach; // End clan member loop ?>

	<script>
		jQuery(".viewmemberinfo").toggle(function(){
			var member = jQuery(this).attr('member-id');
			var viewtype = jQuery(this).attr('viewtype');
			jQuery('.'+viewtype+'_'+member).show(150);
		}, function(){
			var member = jQuery(this).attr('member-id');
			var viewtype = jQuery(this).attr('viewtype');
			jQuery('.'+viewtype+'_'+member).hide(150);;
		});
	</script>
</div> <!-- end .pageRow -->
<script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery(".searchclans").select2();
	});
</script>
<?php
get_footer();