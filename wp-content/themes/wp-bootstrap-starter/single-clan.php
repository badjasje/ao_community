<?php
get_header();
include('constants.php');
global $userId;
global $userData;
$declarer_ID = $userId;
update_user_meta($declarer_ID, 'user_lock', 0);

$nw_att = $userData['networth'][0];
$declarer_clan_ID = $userData['clan_id_user'][0];
$clan_id = get_the_ID();

$backColor = "45, 67, 81";

$aw_args = array(
    'post_type'   => 'award',
    'numberposts' => -1,
    'meta_key'    => 'winning_clan',
    'meta_value'  => $clan_id);
$awards = get_posts($aw_args);

$declarerClanData = get_post_meta($declarer_clan_ID); // Get all postmeta linked to declarer clan ID

$declarer_clanleader = $declarerClanData['clan_leader'][0];

$clanData = get_post_meta($clan_id);
$clanMembers = maybe_unserialize(maybe_unserialize($clanData['clan_members'][0]));
$membersCount = count($clanMembers);
//Enemy clan avg nw is:
$averageNw = $clanData['clan_networth'][0] / $membersCount;

//Count the members in YOUR clan
$declaringClanMembers = maybe_unserialize($declarerClanData['clan_members'][0]);
$declaringMembersCount = (is_array($declaringClanMembers) ? count($declaringClanMembers) : 1);
$declarerAverageNw = $declarerClanData['clan_networth'][0] / $declaringMembersCount;

$average_OK = "false";
if ($declarerAverageNw*$AVERAGE_DECLARE_NW_ALLOWED > $averageNw) {
  $average_OK = "true";
}
$cooldownlist = maybe_unserialize($declarerClanData['cooldown_list'][0]);
if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
if(!is_array($cooldownlist)) $cooldownlist = array();

$decct_1 = $declarerClanData['ct_1'][0];
$decct_2 = $declarerClanData['ct_2'][0];
$decct_3 = $declarerClanData['ct_3'][0];
$decct_4 = $declarerClanData['ct_4'][0];

$allowed_to_declare = array($declarer_clanleader,$decct_1,$decct_2,$decct_3,$decct_4);

$warcount = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'      => 'publish',
	'meta_query'	=> array( 'relation' => 'AND',
        array(
                'key' => 'declared_by',
                'value' => $clan_id
        ),
        array(
                'key' => 'declared_on',
                'value' => $declarer_clan_ID
        )
    )
));
$warcount = count($warcount);

$timestamp = current_time('timestamp');

// calculating total NW for declaring clan. Not sure if still needed.
if($declarer_clan_ID != 0) {
	$dec_tot_networth = 0;
    foreach ($declaringClanMembers as $dec_member) {
        $dec_networth = get_user_meta($dec_member, 'networth',true);
        if(get_user_meta($dec_member, 'status',true) == 'dead'){
            $dec_networth = 0;
        }
        $dec_tot_networth+=$dec_networth;
    }
}

 $wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'   => 'publish',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID
));

$declared_on = array();
$peaceID = 0;
foreach ($wars_on as $war) {
	$defClanID = get_post_meta($war->ID,'declared_on',true);
	$att_ClanID = get_post_meta($war->ID,'declared_by',true);

	if($defClanID == $clan_id){
		$peaceID = $war->ID;
	}
	$declared_on[] = $defClanID;
}
$_member = false;

if(is_array($declaringClanMembers) && in_array($declarer_ID, $declaringClanMembers)){
	$_member = true;
}

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];
$ctArray = array($ct_1,$ct_2,$ct_3,$ct_4);

$clanleader = $clanData['clan_leader'][0];
$clan_points = $clanData['clan_points'][0];
$clantag = $clanData['clan_tag'][0];

$tot_networth = 0;
foreach ($clanMembers as $member) {
    count_all_stats($member);
    $networth = get_user_meta($member, 'networth',true);
    if(get_user_meta($member, 'status',true) == 'dead'){
        $networth = 0;
    }
    $tot_networth+=$networth;
}

update_post_meta($clan_id, 'clan_networth', ceil($tot_networth));
?>

<div class="row pageRow clanContentRow">

    <?php while ( have_posts() ) : the_post();

        $clanImg = get_post_meta($clan_id, 'clan_image', true); ?>
        <div class="blockHeader">
            <?php echo get_the_title($clan_id) ?>
        </div>

        <div class="row row-no-padding fw-row">
            <?php if(!empty($clanImg)):?>
                <div class="col-12 attackingRow statCol-2 row-no-padding">
                    <div class="clanImage" style="background:url(<?php echo $clanImg;?>)"></div>
                </div>
            <?php endif;?>

            <div class="col-12 attackingRow statCol-1">
                <div class="profileColumn">Members</div> <?php echo count($clanMembers);?>
            </div>

            <div class="col-12 attackingRow statCol-2 elipOverflow">
                <div class="profileColumn">Tag</div> <?php echo $clantag;?>
            </div>

            <div class="col-12 attackingRow statCol-3">
                <h3>Awards (<?php echo count($awards);?>)</h3>
                <div id="awardlist" class="fw-row" style="overflow: hidden;">
                    <?php include 'pages/clan/awardlist.php'; ?>
                </div>
                <a id="awardlistExpandBtn" style="display: none">Show more</a>
            </div>

            <div class="col-12 attackingRow statCol-4">
                <div class="profileColumn">Total networth</div> $ <?php echo number_format($tot_networth, 0, ',', ' ');?>
            </div>

            <div class="col-12 attackingRow statCol-3">
                <div class="profileColumn">Average networth</div> $ <?php echo number_format($averageNw, 0, ',', ' ');?>
            </div>

            <div class="col-12 attackingRow statCol-2">
                <div class="profileColumn">Points</div>
                <?php if(!empty($clan_points)){
                    echo number_format($clan_points, 0, ',', ' ');
                } else {
                    echo '0';
                }?>pts <sup><?php echo (isset($clanData['24h_pts'])?$clanData['24h_pts'][0]:0);?>pts today</sup>
            </div>

            <div class="col-12 attackingRow statCol-1 elipOverflow">
                <h3>Message</h3>
                <div id="clanMessage" style="line-height: 18px;">
                    <?php echo str_replace("\r", "<br />", wp_strip_all_tags(get_the_content($clan_id))); ?>
                </div>
            </div>

        </div>

        <?php
    endwhile;?>

    <div class="pageSpacer"></div>

    <?php include('pages/clan/members.php'); ?>

    <script type="text/javascript">
        initReadMore("awardlist",   "awardlistExpandBtn",   164);
    </script>

    <div class="pageSpacer"></div>

</div> <!-- // pageRow -->
<?php get_footer();