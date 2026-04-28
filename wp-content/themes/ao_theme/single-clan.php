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
                <div class="profileColumn">Members</div> 
                
                
           <?= ($clan_id == 51238) ? (count($clanMembers) + 1) : count($clanMembers) ?>

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
                    <td class="provinceName">
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
            <?php if($clan_id == 51238):?>
            <tr style="opacity: 0.8;" class="unitRow userRow6">
            <td class="col-no-padding"><a href="https://assault.online/users/profile/?id=312" title="Nymph"><div class="allUsersAvatar setAvatar uploaded"><img src="https://assault.online/wp-content/uploads/2017/07/giphy-downsized.gif"></div></a></td>
            <td class="provinceName">
            <span class="name-sort"><a class="memberField" href="https://assault.online/users/profile/?id=312"><span class="name">Nymph</span> <span class="nameId">(#312)</span> 

            
        
        <span class="hover-tip" data-toggle="tooltip" data-original-title="This is a post mortem profile." data-placement="right">
            <i class="fa-solid fa-cross"></i>
        </span>
        
        </a></span>
            </td>
            <td class="nw-sort"><span>$ 416 979 <span class="hover-tip" data-toggle="tooltip" data-placement="bottom" data-title="Out of range, min $ 297 843, max $ 583 771" data-original-title="" title=""><i class="far fa-times-circle"></i></span></span></td>
            <td class="land-sort">35 780m<sup>2</sup></td>
                            </tr>
                            <?php endif;?>
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
        $resumetime = (!!$userClan ? $clan->getResumetime($userClan->get('id')) : false);
        $inRange = (!!$userClan ? $clan->inRange() || $warType == 'outgoing' : false);
        $canPeace = (!!$userClan ? $clan->canPeace($userClan->get('id')) : false);
        $canResume = (!!$userClan ? $clan->canResume($userClan->get('id')) : false);

        if(!!$userClan) {
            $current_modifiers = $current_totals = array();
            $newWarType = $userClan->getWarType($clan->get('id')); // Look, we switched it around
            if($newWarType != 'none') {
                list($current_modifiers,$current_totals) = $userClan->getWarModifiers($clan->get('id'), $newWarType);
            }

            $action_modifiers = $action_totals = array();
            $newWarType=false;
            if($incomingWar == false) {
                $newWarType = (!!$outgoingWar ? 'mutual' : 'outgoing');
            }
            else if($canPeace) {
                $newWarType = (!!$outgoingWar ? 'outgoing' : 'none');
            }
            if(!!$newWarType) {
                list($action_modifiers,$action_totals) = $userClan->getWarModifiers($clan->get('id'), $newWarType);
            }
            ?>
            <div class="fw-row d-md-flex">
                <div class="col-md-6 px-0 order-md-2">
                    <div class="attackingRow statCol-3" data-toggle="collapse" data-target="#currentDiff">
                        <strong>Current modifiers:</strong><br>
                        <?=(count($current_totals)?implode_assoc(', ', $current_totals, '%s: %s%%'):'<em>none</em>')?>
                    </div>
                    <div id="currentDiff" class="px-3 py-2 collapse">
                        <?
                        if(count($current_modifiers)) {
                            foreach($current_modifiers as $mod) echo $mod.'<br>';
                        } else echo '<em>none</em>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6 px-0 order-md-1">
                    <div class="attackingRow statCol-3" data-toggle="collapse" data-target="#actionDiff">
                        <strong><?=(!$incomingWar?'Modifiers if you declare:':(!!$canPeace?'Modifiers if you peace:':''))?></strong><br>
                        <?=(count($action_totals)?implode_assoc(', ', $action_totals, '%s: %s%%'):'<em>none</em>')?>
                    </div>
                    <div id="actionDiff" class="px-3 py-2 collapse">
                        <?
                        if(count($action_modifiers)) {
                            foreach($action_modifiers as $mod) echo $mod.'<br>';
                        } else echo '<em>none</em>';
                        ?>
                    </div>
                </div>
            </div>
            <?
        }
        ?>
        <div class="row fw-row no-gutters">
            <div class="col">
                <?
                if($userCanDeclare && $canPeace) {
                    echo '<button class="mainSubmit declarePeaceButton" data-toggle="modal" data-target="#declareModal">
                        <i class="fas fa-dove" aria-hidden="true"></i> Declare peace
                    </button>'.PHP_EOL;
                }
                elseif(in_array($warType, array('incoming','mutual'))) {
                    echo '<button class="mainSubmit disabled">In a'.($warType=='mutual'?' mutual':'n outgoing').' war</button>'.PHP_EOL;
                }
                elseif($inCooldown) {
                    if($canResume) {
                        if($userCanDeclare) { ?>
                        <form id="declare" method="POST">
                            <input type="hidden" name="clan_id" value="<?=$clan_id?>">
                            <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                            <button type="submit" name="submit" class="mainSubmit resumeWarButton">
                                <i class="fas fa-fire" aria-hidden="true"></i> Resume war
                            </button>
                        </form>
                    <? } else echo '<button class="mainSubmit disabled">
                        War resumable: <span data-countdown="'.($userCooldownlist[$clan_id]-$timestamp).'"></span>
                        </button>'.PHP_EOL;
                    } else {
                        if($resumetime!==0) {
                            echo '<button class="mainSubmit disabled">
                                Resumable in: <span data-countdown="'.($resumetime-$timestamp).'"></span>
                            </button>'.PHP_EOL;
                        }
                        else {
                            echo '<button class="mainSubmit disabled">
                                Cooldown: <span data-countdown="'. ($userCooldownlist[$clan_id]-$timestamp) .'"></span>
                            </button>'.PHP_EOL;
                        }
                    }
                }
                else if($inRange) {
                    if($userCanDeclare) echo '<button class="mainSubmit warDecSubmit" data-toggle="modal" data-target="#declareModal">
                        <i class="fas fa-fire" aria-hidden="true"></i> Declare'.($warType=='outgoing'?' mutual':'').' war
                    </button>'.PHP_EOL;
                    else echo '<button class="mainSubmit disabled">In range'.($warType=='outgoing'?', incoming war':'').'</button>'.PHP_EOL;
                }
                else echo '<button class="mainSubmit disabled">Currently not in range</button>'.PHP_EOL;
                ?>
            </div>
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
            'userCanDeclare' => $userCanDeclare,
            'wartype' => $warType,
            'cooldown' => $userCooldownlist,
            'inrange' => $inRange,
            'inCooldown' => $inCooldown,
            'resumetime' => $resumetime,
            'canpeace' => $canPeace,
            'canResume' => $canResume,
            'incoming' => !!$incomingWar,
            'outgoing' => !!$outgoingWar,
        ));
    }
    ?>

    <div class="pageSpacer"></div>
</div>

<div class="modal fade" id="declareModal" tabindex="-1" role="dialog" aria-labelledby="declareModalLabel" aria-hidden="true">
    <form method="POST" id="declare" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Are you sure?</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <label>Message</label>
                <input placeholder="Max. 50 characters." class="unitInput" type="text" name="dec_msg" maxlength="50" style="border:none;">
            </div>
            <div class="modal-footer">
                <input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
                <input type="hidden" name="clan_id" value="<?=$clan_id?>">
                <button type="submit" class="mainSubmit">Declare</button>
            </div>
        </div>
    </form>
</div>
<?
get_footer();
