<?php
$toplistArray = maybe_unserialize(get_field('toplistarray','option'));

if(count($toplistArray) && isset($toplistArray['clannetworth'])) {
    $topPtsToday = '';
    foreach(array_slice($toplistArray['24h_pts'],0,3) as $topClanId) {
        $pts = ceil(get_post_meta($topClanId, '24h_pts',true));
        if($pts > 0) {
            $topPtsToday .= '<div class="col-xs-6 col-sm-7 celBlock"><a href="'.
                get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
                '<div class="col-xs-6 col-sm-5 celBlock">'.$pts.'</div>';
        }
    }
    $topClanNw = '';
    foreach(array_slice($toplistArray['clannetworth'],0,3) as $topClanId) {
        $topClanNw .= '<div class="col-xs-6 col-sm-7 celBlock"><a href="'.
            get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
            '<div class="col-xs-6 col-sm-5 celBlock">$ '.number_format(get_post_meta($topClanId, 'clan_networth',true), 0, ',', ' ') .'</div>';
    }
    $topPts = '';
    foreach(array_slice($toplistArray['clanpoints'],0,3) as $topClanId) {
        $pts = ceil(get_post_meta($topClanId, 'clan_points',true));
        if($pts > 0) {
            $topPts .= '<div class="col-xs-6 col-sm-7 celBlock"><a href="'.
                get_the_permalink($topClanId).'">'.get_the_title($topClanId).'</a></div>'.
                '<div class="col-xs-6 col-sm-5 celBlock">'.$pts.'</div>';
        }
    }
    ///if(!empty($topPtsToday) && !empty($topClanNw) && !empty($topPts)) {
    ?>
    <div class="row row-no-padding fw-row statusTotalRow">
        <div class="col-md-3 statusRow statCol-4">
            <a href="<?=get_site_url()?>/toplists/?tab=clanpointstoday" class="blockHeader">
                <strong>Top clan pts today</strong>
                <div class="float-right"><small>more &raquo;</small></div>
            </a>
            <?=(!empty($topPtsToday) ? '<div class="row unitRow">'.$topPtsToday.'</div>' : '')?>
        </div>
        <div class="col-md-3 statusRow statCol-2">
            <a href="<?=get_site_url()?>/toplists/?tab=clannw" class="blockHeader">
                <strong>Top clan nw</strong>
                <div class="float-right"><small>more &raquo;</small></div>
            </a>
            <?=(!empty($topClanNw) ? '<div class="row unitRow">'.$topClanNw.'</div>' : '')?>
        </div>
        <div class="col-md-3 statusRow statCol-3">
            <a href="<?=get_site_url()?>/toplists/?tab=clanpoints" class="blockHeader">
                <strong>Top clan pts total</strong>
                <div class="float-right"><small>more &raquo;</small></div>
            </a>
            <?=(!empty($topPts) ? '<div class="row unitRow">'.$topPts.'</div>' : '')?>
        </div>
        <div class="col-md-3 statusRow statCol-4">
            <?php if(function_exists('vote_poll') && !in_pollarchive()): ?>
                <div class="blockHeader">
                    <strong>Poll</strong>
                </div>
                <div class="celBlock">
                    <?php get_poll();?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="pageSpacer"></div>
    <?php
    //}
}
