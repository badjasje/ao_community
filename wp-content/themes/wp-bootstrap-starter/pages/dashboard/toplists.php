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
    ///if(!empty($topPtsToday) && !empty($topClanNw) && !empty($topPts)) {
    ?>
    <div class="row row-no-padding fw-row statusTotalRow">
        <div class="col-md-3 statusRow statCol-4">
            <div class="blockHeader">
                <strong>Top clan pts today</strong>
                <a href="<?php echo get_site_url(); ?>/toplists/?tab=clanpointstoday" class="float-right"><small>more &raquo;</small></a>
            </div>
            <?=(!empty($topPtsToday) ? '<div class="row unitRow">'.$topPtsToday.'</div>' : '')?>
        </div>
        <div class="col-md-3 statusRow statCol-2">
            <div class="blockHeader">
                <strong>Top clan nw</strong>
                <a href="<?php echo get_site_url(); ?>/toplists/?tab=clannw" class="float-right"><small>more &raquo;</small></a>
            </div>
            <?=(!empty($topClanNw) ? '<div class="row unitRow">'.$topClanNw.'</div>' : '')?>
        </div>
        <div class="col-md-3 statusRow statCol-3">
            <div class="blockHeader">
                <strong>Top clan pts total</strong>
                <a href="<?php echo get_site_url(); ?>/toplists/?tab=clanpoints" class="float-right"><small>more &raquo;</small></a>
            </div>
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
