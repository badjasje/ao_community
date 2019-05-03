<?php
$toplistArray = maybe_unserialize(get_field('toplistarray','option'));

if(count($toplistArray) && isset($toplistArray['clannetworth'])) {
    $topPtsToday = '';
    foreach(array_slice($toplistArray['24h_pts'],0,3) as $topClanId) {
        $pts = ceil(get_post_meta($topClanId, '24h_pts',true));
        if($pts > 0) {
            $topPtsToday .= '<div class="col-xs-6 col-sm-8 celBlock"><a href="'.
                get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
                '<div class="col-xs-6 col-sm-4 celBlock">'.$pts.'</div>';
        }
    }
    $topClanNw = '';
    foreach(array_slice($toplistArray['clannetworth'],0,3) as $topClanId) {
        $topClanNw .= '<div class="col-xs-6 col-sm-8 celBlock"><a href="'.
            get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
            '<div class="col-xs-6 col-sm-4 celBlock">$ '.number_format(get_post_meta($topClanId, 'clan_networth',true), 0, ',', ' ') .'</div>';
    }
    $topPts = '';
    foreach(array_slice($toplistArray['clanpoints'],0,3) as $topClanId) {
        $pts = ceil(get_post_meta($topClanId, 'clan_points',true));
        if($pts > 0) {
            $topPts .= '<div class="col-xs-6 col-sm-8 celBlock"><a href="'.
                get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
                '<div class="col-xs-6 col-sm-4 celBlock">'.$pts.'</div>';
        }
    }
    if(!empty($topPtsToday) && !empty($topClanNw) && !empty($topPts)) {
    ?>
    <div class="row row-no-padding fw-row statusTotalRow">
        <div class="col-md-4">
            <div class="row statusRow statCol-4">
                <div class="blockHeader"><strong>Top clan pts today</strong></div>
                <?=(!empty($topPtsToday) ? $topPtsToday : str_repeat('<div class="col-sm-12 celBlock">&nbsp;</div>', 3))?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row statusRow statCol-2">
                <div class="blockHeader"><strong>Top clan nw</strong></div>
                <?=(!empty($topClanNw) ? $topClanNw : str_repeat('<div class="col-sm-12 celBlock">&nbsp;</div>', 3))?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row statusRow statCol-3">
                <div class="blockHeader"><strong>Top clan pts total</strong></div>
                <?=(!empty($topPts) ? $topPts : str_repeat('<div class="col-sm-12 celBlock">&nbsp;</div>', 3))?>
            </div>
        </div>
        <div class="col-md-12 statCol-1 text-right" style="border-top:1px solid #FFF;padding: 3px 10px;">
            <a href="<?php echo get_site_url(); ?>/toplists/">More <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>


    <div class="pageSpacer"></div>
    <?php
    }
}
