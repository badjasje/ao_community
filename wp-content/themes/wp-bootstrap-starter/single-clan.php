<?php
/**
 * Template Name: Single Clan
 */
get_header();

$clan_id = get_the_ID();
$clan = Clan::make($clan_id);
$clanImg = $clan->getImage();
$clanMembers = $clan->getMembers();
$clanAwards = $clan->getAwards();

$user = CurrentUser::make();
$province = $user->getProvince();
$userIsMember = $clan->isMember();
?>
<div class="row pageRow clanContentRow">
    <? while (have_posts()) { the_post(); ?>
        <div id="clan-<?=$clan->get('id')?>" class="blockHeader"><?=$clan->getName()?></div>

        <div class="row row-no-padding fw-row">
            <? if(!empty($clanImg)) { ?>
                <div class="col-12 attackingRow statCol-2 row-no-padding">
                    <div class="clanImage" style="background:url(<?=$clanImg?>)"></div>
                </div>
            <? } ?>

            <div class="col-12 attackingRow statCol-1">
                <div class="profileColumn">Members</div> <?=count($clanMembers)?>
            </div>

            <div class="col-12 attackingRow statCol-2 elipOverflow">
                <div class="profileColumn">Tag</div> <?=$clan->getTag(true,false)?>
            </div>

            <div class="col-12 attackingRow statCol-3">
                <h3>Awards (<?=count($clanAwards)?>)</h3>
                <div id="awardlist" class="fw-row">
                    <? include 'pages/clan/awardlist.php'; ?>
                </div>
            </div>

            <div class="col-12 attackingRow statCol-4">
                <div class="profileColumn">Total networth</div> <?=$clan->getNetworth(true, true)?>
            </div>

            <div class="col-12 attackingRow statCol-3">
                <div class="profileColumn">Average networth</div> <?=$clan->getAvgNetworth(true)?>
            </div>

            <div class="col-12 attackingRow statCol-2">
                <div class="profileColumn">Points</div>
                <?=$clan->getPoints(true)?>pts <sup><?=$clan->get24hPoints()?>pts today</sup>
            </div>

            <div class="col-12 attackingRow statCol-1 elipOverflow">
                <h3>Message</h3>
                <div id="clanMessage"><?=$clan->getPublicMessage(true)?></div>
            </div>

        </div>
    <? } ?>

    <div class="pageSpacer"></div>

    <div id="clanMembers" class="aoPage">
        <table id="values6" class="aoTable">
			<tr class="unitRow headerRow">
                <th></th>
	            <th><a href="javascript:void(0);" class="sort6" data-sort=".name-sort">Name <i class="fas fa-sort"></i></a></th>
	            <th><a href="javascript:void(0);" class="sort6 sort-number" data-sort=".nw-sort">Networth <i class="fas fa-sort"></i></a></th>
	            <th><a href="javascript:void(0);" class="sort6 sort-number" data-sort=".land-sort">Land <i class="fas fa-sort"></i></a></th>
                <? if($userIsMember) { ?>
                    <th><a href="javascript:void(0);" class="sort6 sort-number" data-sort=".pts-sort">Points <i class="fas fa-sort"></i></a></th>
                    <th></th>
                <? } ?>
			</tr>
            <? foreach($clanMembers as $member_id) {
                $member = Province::make($member_id);
                $pts = $member->getClanPoints(true);
                ?>
                <tr class="unitRow userRow6">
                    <td class="col-no-padding"><?=$member->getAvatar('allUsersAvatar')?></td>
                    <td>
                        <?=($member_id == $clan->getLeader() ? '<strong>CL</strong>' : '')?>
                        <?=(in_array($member_id, $clan->getTrustees()) ? '<strong>CT</strong>' : '')?>
                        <span class="name-sort"><?=$member->getLink(true)?></span>
                    </td>
                    <td class="nw-sort"><?=$member->getNetworth(true)?></td>
                    <td class="land-sort"><?=$member->getLand(true)?></td>
                    <? if($userIsMember) { ?>
                        <td class="pts-sort"><?=$pts?> pts</td>
                        <td>
                            <? if($clan->canKick($province->get('id'), $member_id)) {
                                echo '<a href="'.Request::siteUrl().'/kick.php?id='.$member_id.'&clan='.$clan_id.'" '.
                                    'onclick="return confirm(\'Are you sure you want to kick '.$member->getName(false).' (#'.$member_id.') from your clan? '.
                                    ' Your clan will lose '.round($pts * Settings::get('clan_kick_penalty')).' clan points.\')" '.
                                    '>kick</a>';
                            } ?>
                        </td>
                    <? } ?>
                </tr>
            <? } ?>
        </table>
    </div>

    <?php
    if(!$userIsMember) {
        $timestamp = current_time('timestamp');

        $userClan = $province->getClan();
        $userCooldownlist = (!!$userClan ? $userClan->getCooldownList() : array());
        $userCanDeclare = (!!$userClan ? $userClan->isCLT() : false);
        if($userCanDeclare) $userClan->getNetworth(false, true); // update my own clan's nw

        $warType = (!!$userClan ? $clan->getWarType($userClan->get('id')) : 'none');
        $incomingWar = (!!$userClan ? $clan->getIncomingWars($userClan->get('id')) : false);
        $outgoingWar = (!!$userClan ? $clan->getOutgoingWars($userClan->get('id')) : false);
        $inCooldown = array_key_exists($clan_id, $userCooldownlist);
        $inRange = $clan->inRange() || $warType == 'outgoing';
        $canPeace = (!!$userClan ? $clan->canPeace($userClan->get('id')) : false);
        $canResume = (!!$userClan ? $clan->canResume($userClan->get('id')) : false);

        if(!!$userClan) {
            ?>
            <div class="fw-row d-md-flex">
                <div class="col-md-6 px-0 order-md-2">
                    <div class="attackingRow statCol-3"><strong>Current modifiers:</strong></div>
                    <div class="px-3 py-2">
                        <?
                        $newWarType = $userClan->getWarType($clan->get('id')); // Look, we switched it around
                        if($newWarType != 'none') {
                            $modifiers = $userClan->getWarModifiers($clan->get('id'), $newWarType);
                            foreach($modifiers as $mod) echo $mod.'<br>';
                        } else echo '<em>none</em>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6 px-0 order-md-1">
                    <div class="attackingRow statCol-3"><strong>
                        <?=(!$incomingWar?'Modifiers if you declare:':(!!$canPeace?'Modifiers if you peace:':''))?>
                    </strong></div>
                    <div class="px-3 py-2">
                        <?
                        $newWarType=false;
                        if($incomingWar == false) {
                            $newWarType = (!!$outgoingWar ? 'mutual' : 'outgoing');
                        }
                        else if($canPeace) {
                            $newWarType = (!!$outgoingWar ? 'outgoing' : 'none');
                        }
                        if(!!$newWarType) {
                            $modifiers = $clan->getWarModifiers($userClan->get('id'), $newWarType);
                            foreach($modifiers as $mod) echo $mod.'<br>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?
        }
        ?>
        <div class="row fw-row no-gutters">
            <?
            if($userCanDeclare) {
                echo '<div class="col">'.PHP_EOL;
                if($canPeace) {
                    echo '<button class="mainSubmit declarePeaceButton" data-toggle="modal" data-target="#declarePeaceModal">
                        <i class="fas fa-dove" aria-hidden="true"></i> Declare peace
                    </button>'.PHP_EOL;
                }
                elseif(in_array($warType, array('incoming','mutual'))) {
                    echo '<button class="mainSubmit disabled">You are at war with this clan</button>'.PHP_EOL;
                }
                elseif($inCooldown) {
                    if($canResume==false) {
                        echo '<button class="mainSubmit disabled">
                            Cooldown: <span data-countdown="'.($userCooldownlist[$clan_id]-$timestamp).'"></span>
                        </button>'.PHP_EOL;
                    }
                    else { ?>
                        <form id="resumeWar" method="POST">
                            <input type="hidden" name="declaredon" value="<?=$clan_id?>">
                            <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                            <button type="submit" name="submit" class="mainSubmit resumeWarButton">
                                <i class="fas fa-fire" aria-hidden="true"></i> Resume war
                            </button>
                        </form>
                    <? }
                }
                else if($inRange) {
                    echo '<button class="mainSubmit warDecSubmit" data-toggle="modal" data-target="#declareWarModal">
                        <i class="fas fa-fire" aria-hidden="true"></i> Declare'.($warType=='outgoing'?' mutual':'').' war
                    </button>'.PHP_EOL;
                }
                else echo '<button class="mainSubmit disabled">Currently not in range</button>'.PHP_EOL;
                echo '</div>'.PHP_EOL;
            }
            ?>
            <a class="col mainSubmit" href="<?=Request::siteUrl()?>/spy-report-overview/?id=<?=$clan_id?>">
                <i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports
            </a>
        </div>
    <?
    }
    if(isset($_GET['claninfo'])) {
        wtf(array(
            'clan_id' => $clan_id,
            'userclan' => (!!$userClan ? $userClan->get('id') : '-'),
            'wartype' => $warType,
            'cooldown' => $userCooldownlist,
            'inrange' => $inRange,
            'canpeace' => $canPeace,
            'canResume' => $canResume,
            'incoming' => !!$incomingWar,
            'outgoing' => !!$outgoingWar,
        ));
    }
    ?>

    <div class="pageSpacer"></div>
</div>

<div class="modal fade" id="declareWarModal" tabindex="-1" role="dialog" aria-labelledby="declareWarModalLabel" aria-hidden="true">
    <form method="POST" id="declareWar" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Are you sure?</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <label>Declaration message</label>
                <input placeholder="Max. 50 characters." class="unitInput" type="text" name="dec_msg" maxlength="50" style="border:none;">
            </div>
            <div class="modal-footer">
                <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                <input type="hidden" name="clan" value="<?=$clan_id?>">
                <button type="submit" class="mainSubmit">Declare war</button>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="declarePeaceModal" tabindex="-1" role="dialog" aria-labelledby="declarePeaceModalLabel" aria-hidden="true">
    <form method="POST" id="declarePeace" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Are you sure?</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <label>Peace message</label>
                <input placeholder="Max. 50 characters." class="unitInput" type="text" name="dec_msg" maxlength="50" style="border:none;">
            </div>
            <div class="modal-footer">
                <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                <input type="hidden" name="war" value="<?=(!!$incomingWar?$incomingWar->ID:0)?>">
                <input type="hidden" name="clan" value="<?=$clan_id?>">
                <button type="submit" class="mainSubmit">Declare peace</button>
            </div>
        </div>
    </form>
</div>
<?
get_footer();
