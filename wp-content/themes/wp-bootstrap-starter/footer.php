<?php
$user = CurrentUser::make();
$province = ($user->isLoggedIn() ? $user->getProvince() : false);

if(!is_page_template('blank-page.php') && !is_page_template('blank-page-with-container.php')) {
?>
			</div>
		</div>
	</div>

    <?php get_template_part('footer-widget'); ?>
	<footer id="colophon" class="site-footer <?=wp_bootstrap_starter_bg_class()?>" role="contentinfo">

        <div class="container-fluid p-2 p-md-5">
            <?php if($user->isLoggedIn()) { ?>
            <div class="row no-gutters footer-nav">
                <div class="col-xs-6 col-md-3">
                    <h4>Beginners</h4>
                    <ul class="footer-list">
                        <li><a href="http://bit.ly/2US8Dh0" target="_blank"><strong>Join discord channel!</strong></a></li>
                        <li><a href="<?=Request::siteUrl()?>/getting-started">Getting started manual</a></li>
                        <li><a href="<?=Request::siteUrl()?>/manual">Complete Manual</a></li>
                        <li><a href="<?=Request::siteUrl()?>/rules">Rules</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-md-3">
                    <h4>Toplists</h4>
                    <ul class="footer-list">
                        <li><a href="<?=Request::siteUrl()?>/toplists/">Highest nw</a></li>
                        <li><a href="<?=Request::siteUrl()?>/toplists/?tab=clanpoints">Clan points</a></li>
                        <li><a href="<?=Request::siteUrl()?>/toplists/?tab=clannw">Clan nw</a></li>
                        <li><a href="<?=Request::siteUrl()?>/toplists/?tab=clanpointstoday">Clan pts today</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-md-3">
                    <h4>Information</h4>
                    <ul class="footer-list">
                        <li><a href="<?=Request::siteUrl()?>/forum">Forum</a></li>
                        <li><a href="<?=Request::siteUrl()?>/category/awards-medals/">Awards & Medals</a></li>
                        <li><a href="<?=Request::siteUrl()?>/all-clans">Clan list</a></li>
                        <li><a href="<?=Request::siteUrl()?>/users">User list</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-md-3">
                    <h4><?php echo $province->getName(); ?></h4>
                    <ul class="footer-list">
                        <li><a href="<?=$province->getLink()?>">Profile</a></li>
                        <li><a href="<?=Request::siteUrl()?>/player-statistics">Statistics</a></li>
                        <li><a href="<?=$province->getLink()?>">Set push notifications</a></li>
                    </ul>
                </div>
            </div>

            <hr size="1">
            <? } ?>

            <div class="site-info">
	            <center>
                    &copy; <?=date('Y')?> <a href="<?=Request::siteUrl()?>"><?=get_bloginfo('name')?></a>
                    <span class="sep"> | </span>
                    Your local date/time is <strong id="footerTime"></strong>.<br>
                    Current server date/time is
                    <strong><?=date("d-m-Y G:i:s", strtotime('+1 hours'))?></strong>
                    | Resolution <span id="footerResolution"></span><br>
                    <? if($user->isLoggedIn()) { ?>
                        <a href="<?=wp_logout_url( Request::siteUrl()."/home/")?>">Logout</a>
                    <? } ?>
                </center>
            </div>
		</div>
	</footer>
<?php } ?>
</div>

<?php if(!empty($_SESSION['showError'])) {  ?>
<div class="col-xs-11 col-sm-4 alert alert-info animated fadeInDown" data-fade-out="5000" style="margin:0px auto;position:fixed;z-index:1031;right:20px;">
    <span><?=$_SESSION['showError']?></span>
</div>
<?php $_SESSION['showError']=false; } ?>

<?php wp_footer(); ?>
</body>
</html>