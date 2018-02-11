<?php
 /*
 * Template Name: Spy Reports
 */
$target_id = $_GET['id'];
$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'buildings';
$user = get_userdata($target_id);
$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$target_clan_ID = get_user_meta($target_id, 'clan_id_user',true);

$savedUsers = get_user_meta($user_ID, 'saved_users', true);
$savedUsers = json_decode($savedUsers);

$members = get_post_meta($clan_ID,'clan_members',true);
$members[] = $user_ID;
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            


  
<?php if(get_field('game_status','option') != 'Live'):?>
	<div class="notice_message">
		<span class="rdw-line">The round has ended!</span>
	</div>
	<br/>
<?php else:?>
	            
	            
	            
<div class="row button_block">
 	
 	<div class="col-md-3 buttoncol">
	 	<center><a class="btn btn-attack profilebutton" href="/attack/step-1/?id=<?php echo $target_id;?>">
		 	<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
		<center><a class="btn btn-general profilebutton" href="/users/profile/?id=<?php echo $target_id;?>">
			<i class="fa fa-user-o" aria-hidden="true"></i> &nbsp;Profile</a></center>
	</div>
	
	<div class="col-md-3 buttoncol">
	  <center><a class="btn btn-general profilebutton" href="/spy-report-overview/?id=<?php echo $target_clan_ID;?>">
		  <i class="fa fa-address-card-o" aria-hidden="true"></i> &nbsp;Clan reports</a></center>
	</div>
	
	<?php if(in_array($target_id, $savedUsers)):?>
	
		<div class="col-md-3 buttoncol">
			<center><a class="btn profilebutton savedUser" href="/saved-users">
				<i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp;User saved</a></center>
		</div>
		
	<?php else:?>
	
		<div class="col-md-3 buttoncol">
			<center><a class="btn btn-general profilebutton" href="/save_user.php/?id=<?php echo $target_id;?>&return=<?php echo get_the_id();?>">
				<i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp;Save user</a></center>
		</div>
		
	<?php endif;?>
  
</div>



<ul id="explore-tab" class="nav nav-tabs nav-justified" role="tablist">
	<li class="nav-item <?php echo $activeTab === 'buildings' ? 'active' : ''; ?>">
		<a class="nav-link" data-toggle="tab" data-target="#buildings" href="?id=<?php echo $target_id;?>&?tab=buildings" role="tab">Buildings</a>
	</li>
	<li class="nav-item <?php echo $activeTab === 'units' ? 'active' : ''; ?>">
		<a class="nav-link" data-toggle="tab" data-target="#units" href="?id=<?php echo $target_id;?>&?tab=units" role="tab">Units</a>
	</li>
</ul>


<div class="tab-content current build_content tabbed-table">

	<div class="tab-pane <?php echo $activeTab === 'buildings' ? 'active' : ''; ?>"  id="buildings" role="tabpanel">
					
		<?php include 'pages/spyrep/buildings.php'; ?>

	</div>

	<div class="tab-pane <?php echo $activeTab === 'units' ? 'active' : ''; ?>"  id="units" role="tabpanel">

		<?php include 'pages/spyrep/units.php'; ?>

	</div>
	
<form class="form" action="<?php echo home_url() ?>/attack.php" name="" id="attack" method="post">
	<input style="display:none" type="text" id="target_id"  name="target_id" value="<?php echo $target_id;?>"/>
	<input style="display:none;" type="radio" name="attacktype" checked id="spy" value="spy">
	<input type="submit" value="Re-spy" class="">
</form>
	
</div>


<?php endif;?>
			
<script>
	jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
        jQuery('#currentTab').val(currentTab);
    });
</script>
        </div>
    </div>
</div>
<?php get_footer(); ?>