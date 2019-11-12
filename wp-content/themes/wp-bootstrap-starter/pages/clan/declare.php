<?php

?>
<div class="row fw-row no-gutters">
    <?
    if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) && $inRange == 'yes' && $canPeace == false) {
        ?>
        <div class="col">
            <? if (in_array($clan_id, $declared_on)) { ?>
                <button class="mainSubmit" disabled>
                    <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;You are at war with this clan
                </button>
            <? } else { ?>
                <button class="mainSubmit warDecSubmit" data-toggle="modal" data-target="#declareWarModal">
                    <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;Declare <?php echo $warText;?>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="declareWarModal" tabindex="-1" role="dialog" aria-labelledby="declareWarModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
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
                                <button type="button" class="mainSubmit declarewar" data-dismiss="modal">Declare <?php echo $warText;?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                (function($) {
                    var declare;
                    $(document).on('click','.declarewar',function(event){
                        $('.pageLoader, #page-cover').show();

                        var message = $(".unitInput").val();
                        var declare = $.ajax({url: "/declare_war.php", type: "post", data: '&clan=<?php echo $clan_id;?>&dec_msg='+message});
                        // Callback handler that will be called on success
                        declare.done(function (response, textStatus, jqXHR){
                            $('.pageLoader, #page-cover').fadeOut( "fast");
                            var json = $.parseJSON(response);
                            if(json.next == true){
                                $('.warDecSubmit').html('<i class="fas fa-fire" aria-hidden="true"></i> You are at war with this clan');
                                $(".warDecSubmit").attr("disabled", "disabled");
                                $('#declareWarModal').remove();
                            }
                            $.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true,});
                        });
                    });
                })(jQuery);
                </script>
            <? } ?>
        </div>
        <?
    }

    if(in_array($declarer_ID, $allowed_to_declare) && $canPeace == true) { ?>
        <!-- Declare peace block -->
        <div id="peacecontainer" class="col">
            <button class="mainSubmit declarePeaceButton" data-toggle="modal" data-target="#declarePeaceModal">
                <i class="fas fa-dove" aria-hidden="true"></i> &nbsp;Declare peace
            </button>
        </div>
        <div class="modal fade" id="declarePeaceModal" tabindex="-1" role="dialog" aria-labelledby="declarePeaceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
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
                        <button type="button" class="mainSubmit peaceDecSubmit" data-dismiss="modal">Declare peace</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        (function($) {
            var declare;
            $(document).on('click','.peaceDecSubmit',function(event){
                $('.pageLoader, #page-cover').show();
                var message = $(".unitInput").val();
                var declare = $.ajax({url: "/declare_peace.php",type: "post",data: '&war=<?php echo $peaceID;?>&clan=<?php echo $clan_id;?>&dec_msg='+message});
                declare.done(function (response, textStatus, jqXHR){
                    $('.pageLoader, #page-cover').fadeOut( "fast");
                    var json = $.parseJSON(response);
                    if(json.next == true){
                        $("#peacecontainer").addClass("col-md-3");
                        $( "#peacecontainer" ).removeClass( "col-md-6" )
                        $('.declarePeaceButton').remove();
                        $('#declarePeaceModal').remove();
                    }
                    $.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
                });
            });
        })(jQuery);
        </script>
        <?
    }

    if(in_array($declarer_ID, $allowed_to_declare) && !array_key_exists($clan_id, $cooldownlist) &&  $inRange == 'no' && $canPeace == false) {
        ?><button class="mainSubmit col-md-6">
            <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;<?=($peaceID!=0?'You are at war with this clan':'Currently not in range')?>
        </button><?
    }

    if(!in_array($declarer_ID, $allowed_to_declare) || array_key_exists($clan_id, $cooldownlist)) {
        if($warcount > 0) {
            if(in_array($declarer_ID, $allowed_to_declare)) { ?>
                <div class="col-md-6">
                    <button class="mainSubmit resumewar">
                        <i class="fas fa-fire" aria-hidden="true"></i> &nbsp;Resume war
                    </button>
                </div>
                <script>
                (function($) {
                    $(document).on('click','.resumewar',function(event){
                        $('.pageLoader, #page-cover').show();
                        var declare = $.ajax({url: "/resumewar.php",type: "post",data: '&declaredon=<?php echo $clan_id;?>'});
                        declare.done(function (response, textStatus, jqXHR){
                            $('.pageLoader, #page-cover').fadeOut( "fast");
                            var json = $.parseJSON(response);
                            if(json.next == true){
                                $('.warDecSubmit').html('<i class="fas fa-fire" aria-hidden="true"></i> You are at war with this clan');
                                $(".warDecSubmit").attr("disabled", "disabled");
                                $('#declareWarModal').remove();
                                location.reload();
                            }
                            $.notify({message: json.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
                        });
                    });
                })(jQuery);
                </script>
                <?
            }
        } else {
            if(array_key_exists($clan_id, $cooldownlist)) {
                echo '<div class="col-md-6 secundarySubmit disabled">Cooldown: <span data-countdown="'.($cooldownlist[$clan_id]-$timestamp).'"></span></div>';
            }
        }
    }
    ?>
    <a class="mainSubmit col" href="<?=Request::siteUrl()?>/spy-report-overview/?id=<?php echo $clan_id;?>">
        <i class="fas fa-binoculars" aria-hidden="true"></i> &nbsp;View spyreports
    </a>
</div>
