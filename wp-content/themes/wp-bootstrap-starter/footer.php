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
<div class="col-xs-11 col-sm-4 alert alert-info animated fadeInDown sessionAlert" data-fade-out="5000">
    <span><?=$_SESSION['showError']?></span>
</div>
<?php $_SESSION['showError']=false; } ?>

<?php if(in_array(date('d-m'), array('31-10'))) { ?>
<div id="ghost">
    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	     viewBox="0 0 490.75 490.75" style="enable-background:new 0 0 490.75 490.75;" xml:space="preserve">
        <path id="XMLID_390_" d="M289.338,113.93c-9.081,0-16.429,10.077-16.429,22.51c0,12.449,7.349,22.523,16.429,22.523
            c9.08,0,16.422-10.074,16.422-22.523C305.761,124.007,298.418,113.93,289.338,113.93z"/>
        <path id="XMLID_386_" d="M292.61,0c-82.903,0-150.125,79.955-150.125,178.566c0,93.109,0.34,167.109-91.757,290.264
            c-3.178,4.233-3.624,9.898-1.156,14.566c2.437,4.682,7.366,7.54,12.642,7.345C380.662,479.078,442.76,274.369,442.76,178.566
            C442.76,79.955,375.544,0,292.61,0z M282.839,195.713c-19.991,0-36.174-25.813-36.174-57.639c0-31.827,16.183-57.637,36.174-57.637
            c19.969,0,36.173,25.81,36.173,57.637C319.011,169.9,302.808,195.713,282.839,195.713z M371.136,195.713
            c-19.97,0-36.173-25.813-36.173-57.639c0-31.827,16.203-57.637,36.173-57.637c19.99,0,36.173,25.81,36.173,57.637
            C407.309,169.9,391.125,195.713,371.136,195.713z"/>
        <path id="XMLID_385_" d="M364.636,113.93c-9.081,0-16.422,10.077-16.422,22.51c0,12.449,7.342,22.523,16.422,22.523
            c9.082,0,16.429-10.074,16.429-22.523C381.064,124.007,373.717,113.93,364.636,113.93z"/>
    </svg>
</div>
<?php } ?>

<link href="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/css/select2.min.css" rel="stylesheet" />
<script src="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/select2.min.js"></script>
<link href="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/css/dropzone.css" rel="stylesheet">
<script src="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/dropzone.js"></script>

<script type='text/javascript' src='<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/sortingdivs.js'></script>
<?/* Crap ass Google crap */ ?>
<script src="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/firebase-app.js"></script>
<script src="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/firebase-messaging.js"></script>
<script src="<?=Request::siteUrl()?>/wp-content/themes/wp-bootstrap-starter/js/firebase-functions.js"></script>
<? /* */ ?>

<?php wp_footer(); ?>
</body>
</html>