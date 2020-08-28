function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

var provinceData = {};
function updateHeaderData(cb) {
    var $ = jQuery;
    $.ajax({url:site_url+'/ajax/header',type:'post'}).done(function(response) { // ajax without loader, returns html in json
        var data = $.parseJSON(response);
        if(data.success) {
            provinceData = data.clean;
            if(data.nonce) $('.nonce').val(data.nonce);
            for(var i in data.formatted) {
                if($('.'+i+'header').length) $('.'+i+'header').html(data.formatted[i]);
            }
            $('.globalsBadge,.localsBadge,.messagesBadge').off('click');
            if(!!data.ghost) {
                data[data.ghost]++;
                $('.'+data.ghost+'Button').on('click', function(e) {
                    e.preventDefault();
                    $('#ghost').animate({width:'100vw',opacity:0}, 300, function() {
                        $(this).css({width:'0vw',opacity:1});
                    });
                    $(this).off('click').find('.badge').text(0).hide();
                });
            }
            $('header .landwrapper .stattext').attr({'data-original-title': 'Free land: '+data.formatted.freeland});
            $('.globalsBadge').text(data.globals).toggle((data.globals>0));
            $('.localsBadge').text(data.locals).toggle((data.locals>0));
            $('.messagesBadge').text(data.messages).toggle((data.messages>0));
            if(!!cb) cb.call();
        }
    });
}

var countdown_int, countdown_objs;
function start_countdowns() {
    countdown_objs = jQuery('[data-countdown]');
    if(!!countdown_int) clearInterval(countdown_int);
    countdown_int = setInterval(update_countdowns, 1000);
    update_countdowns();
}
function update_countdowns() {
    countdown_objs.each(function() {
        var $this = jQuery(this), diff = (typeof $this.data('diff') != 'undefined' ? $this.data('diff') : parseInt($this.data('countdown'))*1000);
        if(diff < 0) { $this.html('<em>none</em>'); return; }
        var days = Math.floor( diff / (1000*60*60*24) ),
        hours = Math.floor( diff / (1000*60*60) ),
        mins = Math.floor( diff / (1000*60) ),
        secs = Math.floor( diff / 1000 ),
        dd = days,
        hh = hours - days * 24,
        mm = mins - hours * 60,
        ss = secs - mins * 60;
        var t = ''+(days > 0 ? days+' '+(days==1?'day':'days')+' and ' : '');
        t+=('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' + ('00'+ss).slice(-2);
        $this.text(t).data('diff', (diff-1000));
    });
}

var site_url;
jQuery(function($) {

    site_url = $('body').data('siteurl');

    function standardNotify(msg) {
        $.notify({message:msg},{type:'info',delay:5000,allow_dismiss:true,newest_on_top:true});
    }

    // Fixed html notifications may wanna fade out too
    if($('[data-fade-out]').length) {
        $('[data-fade-out]').each(function() {
            setTimeout(function() { $(this).fadeOut('slow'); }.bind(this), parseInt($(this).data('fade-out')));
        });
    }

    // Wrapper function for most ajaxCalls
    var requests={};
    jQuery.ajaxSetup({cache: false});
    function singleAjax(url,post,cb) {
        //var $ = jQuery;
        if(!!requests[url]) requests[url].abort();
        var serializedData = (post instanceof jQuery ? post.serialize() : post);
        $('.pageLoader, #page-cover').show();
        requests[url] = $.ajax({url:url,type:'post',data:serializedData}).done(function(response) {
            var data = $.parseJSON(response);
            if(!!data.redirect) { location.href=data.redirect; return; }
            if(!!data.refresh) { location.reload(); return; }
            $('.pageLoader, #page-cover').fadeOut("fast");
            if(data.nonce) $('.nonce').val(data.nonce);
            updateHeaderData(function() {
                if(!!cb) cb.call(this, data);
            }.bind((post instanceof jQuery ? post : this))); // Something happened, so it probably costed money or something
            if(!!data.status) standardNotify(data.status);
        });
    }

    // Extra footer data
    var d=new Date();
    $('#footerTime').text(d.toLocaleString('nl-NL'));
    $('#footerResolution').text(window.innerWidth +'x'+ window.innerHeight);

    if(getCookie('menuOpen') === null) setCookie('menuOpen', 0, 256);
    else { setCookie('menuOpen', getCookie('menuOpen'), 256); } // Remember forever

    $("#nextbt").on('click', function(e) {
        e.preventDefault();
        if($('body').hasClass('menuOpen')) {
            $('body').removeClass('menuOpen');
            $('.menuText').hide(500);
            $('[data-toggle=tooltip]').tooltip('enable');
            setCookie('menuOpen', 0, 256);
        } else {
            $('body').addClass('menuOpen');
            $('.menuText').show(750);
            $('[data-toggle=tooltip]').tooltip('disable');
            if($(window).width() >= 1400) setCookie('menuOpen', 1, 256);
        }
    });

    $(".viewmemberinfo.active").toggle(function(){
        var member = $(this).attr('member-id');
        var viewtype = $(this).attr('viewtype');
        $('.'+viewtype+'_'+member).show(150);
    }, function(){
        var member = $(this).attr('member-id');
        var viewtype = $(this).attr('viewtype');
        $('.'+viewtype+'_'+member).hide(150);
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Used on buildings and users page
    function toggleDescriptions(type, s) {
        setCookie(type+'_descriptions', s, 256);
        $('#'+type+' .descriptionRow').toggle((s==1?true:false));
        $('.descriptionToggle span').text((s==1?'Hide':'Show'));
    }
    if($('.descriptionToggle').length) {
        var t = $('.descriptionToggle').data('type'), c = t+'_descriptions';
        $('.descriptionToggle').on('click', function() {
            var s = (getCookie(c)==1?0:1);
            toggleDescriptions(t, s);
        });
        toggleDescriptions(t, getCookie(c)==1?1:0);
    }

    $(document).on('keyup paste change', '[name="build[spyplane]"],[name="build[spy]"]', function() {
        if(''+$(this).val() === '007') {
            $.notify({message:'The name is Bond, James Bond'},{type:'info', allow_dismiss:true, newest_on_top:true, delay:5000});
        }
    });

    // /users/ - page
    $('.searchusers').on('change', function() {
        if($(this).val()) window.location.href = $(this).val();
    }).select2({placeholder:"Start typing to find a player"});

    // Tabs to url
    if($('.searchusers').length) {
        $(document).on('shown.bs.tab', function (e) {
            history.pushState(null, null, $(e.target).attr('href'));
        });
    }

    if($('body').hasClass('logged-in')) {
        var i = setInterval(function() { updateHeaderData(); }, 10000);
        updateHeaderData();
    }

    start_countdowns();

    if($('.pageTitle').hasClass('.deadback')) {
        $(".splashmessage").text('You died');
        $("#splashback").addClass("failsplash");
        $("#splashback,.splashmessage").show();
        $("#splashback,.splashmessage").delay(1500).fadeOut("slow");
    }

    // Dashboard page
    $("#receiveFunds").on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/devfunds', $(this));
    });

    $("#pickStartingBonus").on('submit', function(e) {
        e.preventDefault();
        $('.bonusSubmit').hide();
        singleAjax(site_url+'/ajax/startingbonus', $(this), function(response) {
            if(response.success) $('.startingBonusPicker').fadeOut('fast');
            else $('.bonusSubmit').show();
        });
    });

    $(".retrieveBonusForm").on('submit', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to receive your bonus?')) return;
        $(this).find('.mainSubmit').attr('disabled', true);
        singleAjax(site_url+'/ajax/clanbonus', $(this), function(response) {
            if(response.success) $(this).parent().hide(300);
            $(this).find('.mainSubmit').attr('disabled', false);
        });
    });

    $('#editClanMessage').on('click', function() {
        $('.message-editor').fadeIn(500);
        $('#savedmsg').hide();
    });
    $('#dismissEditClanMessage').on('click', function() {
        $('.message-editor').hide();
        $('#savedmsg').fadeIn(500);
    });
    $("#edit_clan_message").on('submit', function(e) {
        e.preventDefault();
        $('[name="new_message"]').val(tinymce.activeEditor.getContent());
        singleAjax(site_url+'/ajax/clanmessage', $(this), function(data) {
            if(data.success) {
                $('#savedmsg').html(data.clanmessage);
                $('.message-editor').hide();
                $('#savedmsg').fadeIn(500);
            }
        });
    });

    $('#removeProtection').on('submit', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to remove protection?')) return;
        singleAjax(site_url+'/ajax/removenp', $(this), function(data) {
            if(data.success) $('.npMessage').removeClass('py-0').text('Status: online');
        });
    });

    // BANK
    $(document).on('click', ".maxdep", function() {
        $("#amount").val($(this).attr("data-max"));
    });
    $('#bankform').on('submit', function(e) {
        e.preventDefault();
        if(Math.round($('#amount').val()) == 0) return standardNotify('Invalid amount');
        singleAjax(site_url+'/ajax/bank_deposit', $(this), function(data) {
            if(data.success) {
                $('.noDeposits').addClass('hidden');
                $("#amount").attr({"max":data.max_input});
                $(".maxdep").attr({"data-max":data.max_input});
                $('.totaldeposits').text(data.dep_num);
                $('.total_amount').text(data.total_amount);
                $('.total_final').text(data.total_final);
                $('.total_available').text(data.total_available);
                var lastrow = $('.withdraw.hidden').clone();
                lastrow.find('.deposited').text(data.deposited);
                lastrow.find('.finalamount').text(data.finalamount);
                lastrow.find('.timeleft').attr('data-countdown', data.timeleft);
                lastrow.removeClass('hidden').insertAfter('.withdraw.hidden');
                start_countdowns();
                $(this).trigger('reset');
            }
        });
    });
    $('.withdraw').on('submit', function(e) {
        e.preventDefault();
        var bankvalue = $(this).find('.available').val();
        if(!confirm("Are you sure? This deposit will return "+bankvalue)) return;
        singleAjax(site_url+'/ajax/bank_withdraw', $(this), function(data) {
            if(data.success) {
                $("#amount").attr({"max":data.max_input});
                $(".maxdep").attr({"data-max":data.max_input});
                $('.totaldeposits').text(data.dep_num);
                $('.total_amount').text(data.total_amount);
                $('.total_final').text(data.total_final);
                $('.total_available').text(data.total_available);
                $(this).remove();
                if($('.withdraw:not(.hidden)').length == 0) $('.noDeposits').removeClass('hidden');
            }
        });
    });

    // Research page
    $('#research').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/research',  $(this), function(data) {
            if(data.success) {
                $('#researchsubmit').val('Queue research');
                $('.researchlabel').text('Queue select');
                $(data.hidebutton).hide();
                if(data.endtime!='queued') {
					$(`<div class="blockHeader fw-row">
						<i class="fa fa-circle-notch fa-spin"></i> Time left:
						<div class="timeLeft" id="countdown_time" data-countdown="`+data.endtime+`"></div>
					</div>`).insertAfter('#research_'+data.started);
					start_countdowns();
					$('#research_'+data.started+' .unitRow').addClass('loader');
				} else {
					$('<div class="blockHeader fw-row"><i class="fa fa-clock"></i> Research queued</div>').insertAfter('#research_'+data.started);
					$('#researchsubmit').remove();
					$('.researchselector').html('<label class="mainSubmit disabled">No Selection Possible</label>');
				}
				$(this).trigger('reset');
            }
        });
    });

    // Explore land / sell land
    $(document).on('click', ".maxexp", function() {
        $("#turnsinput").val($(this).attr("data-max"));
    });
    $(document).on('click', ".maxsell", function() {
        $("#landinput").val($(this).attr("data-max"));
    });
    $('#exploreform').on('submit', function(e) {
        e.preventDefault();
        if($('#turnsinput').val() >= 50 && !confirm('This will cost a lot of turns, are you sure?')) return;
        singleAjax(site_url+'/ajax/land_explore', $(this), function(data) {
            if(data.success) {
                $(".explNotice").html(data.exploredtoday);
                $(".sellNotice").html(data.soldtoday);
                $('#exprate').html(data.newrate);
                $("#turnsinput").attr({"max": data.maxturns});
                $("#landinput").attr({"max": data.maxsell});
                $(".maxexp").attr({"data-max": data.maxturns});
                $(".maxsell").attr({"data-max": data.maxsell});
                if(data.expResult) $('#expResult').html(data.expResult);
            }
            $(this).trigger('reset');
        });
    });
    $('#sellform').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/land_sell', $(this), function(data) {
            if(data.success) {
                $(".sellNotice").html(data.soldtoday);
                $("#landinput").attr({"max": data.maxsell});
                $(".maxsell").attr({"data-max": data.maxsell});
            }
            $(this).trigger('reset');
        });
    });

    // Buildings page
    function calculateBuildingsTotals() {
        if(typeof provinceData.networth == 'undefined' || $('#buildings').length==0) return;
        var totals = {build:0,demo:0,cost:0,turns:0,nw:provinceData.networth};
        var bpt=parseInt($('#buildingsPerTurn').text());
		$('#buildings .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var b = Math.abs($('.buildBlock .unitInput',this).val()), d = Math.abs($('.demoBlock .unitInput',this).val());
			totals.build += b;
            totals.demo += d;
            totals.cost += (b*parseInt($(this).data('buildprice')))+(d*parseInt($(this).data('demoprice')));
            totals.nw += (b*parseInt($(this).data('nw'))) - (d*parseInt($(this).data('nw')));
        });
        totals.turns += Math.ceil(totals.build/bpt);
        // Demolishing creates more space
        var landpb = $('#buildings .landpb').data('amount');
        var freeland = provinceData.freeland + (totals.demo*landpb) - (totals.build * landpb);
        var turns = provinceData.turns - totals.turns;
        var money = provinceData.money - totals.cost;
        $('#buildings .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var nm = Math.min( Math.floor(money/$(this).data('buildprice')), turns*bpt, Math.floor(freeland / landpb));
            var sm = Math.abs($('.buildBlock .unitInput', this).val()) + nm;
            $('.buildmax', this).attr('data-amount', sm ).text(nm);
            $('.buildBlock .unitInput', this).attr('max', sm);
        });
		$('#total').text('+'+totals.build+' / -'+totals.demo);
		$('#order_total').attr('data-amount',totals.cost).text(number_format(totals.cost, 0, ',', ' '));
		$('#turn_total').attr('data-amount',totals.turns).text(totals.turns);
		$('#networth_new').text(number_format(totals.nw, 0, ',', ' '));
	}
    $('#buildings').on('submit', function(e) {
        e.preventDefault();
        if($('#order_total').attr('data-amount') > provinceData.money) return standardNotify('Insufficient funds');
        if($('#turn_total').attr('data-amount') > provinceData.turns) return standardNotify('Not enough turns');
        singleAjax(site_url+'/ajax/buildings', $(this), function(data) {
            $('.maxbuild', this).text(data.maxbuild);
            $('.buildspace', this).text(data.buildspace);
            for(var key in data.buildmax) {
                var bm = data.buildmax[key], dm = data.demomax[key], o = data.owned[key], r = $('.unitRow.'+key,this);
                $('.buildmax', r).attr('data-amount', bm).text(bm);
                $('.buildBlock .unitInput', r).attr('max', bm);
                $('.demomax', r).attr('data-amount', dm).text(o);
                $('.demoBlock .unitInput', r).attr('max', dm);
            }
            $(this).trigger('reset');
            calculateBuildingsTotals();
        });
    });
    calculateBuildingsTotals();

    // Units page
    function calculateUnitsTotals() {
        if(typeof provinceData.networth == 'undefined' || $('#turnbuild').length==0) return;
        var totals = {build:0,cost:0,turns:0,nw:provinceData.networth};
        $('#turnbuild .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var b = Math.abs($('.buildBlock .unitInput',this).val()), bpt = parseInt($(this).data('bpt'));
            totals.build += b;
            totals.cost += (b * parseInt($(this).data('buildprice')));
            totals.nw += (b * parseInt($(this).data('nw')));
            totals.turns += b / bpt;
        });
        totals.turns = Math.ceil(totals.turns);
        var turns = provinceData.turns - totals.turns;
        var money = provinceData.money - totals.cost;
        $('#turnbuild .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var bpt = parseInt($(this).data('bpt')), space = parseInt($(this).data('space'));
            var special_space = ($(this).data('specialspace') != undefined ? parseInt($(this).data('specialspace')) : false);
            var ttl = specialttl = 0;
            $(this).siblings(':not(.headerRow,.descriptionRow)').add(this).each(function() {
                if($(this).data('specialspace') != undefined) specialttl += Math.abs($('.buildBlock .unitInput',this).val());
                ttl += Math.abs($('.buildBlock .unitInput',this).val());
            });
            var nm = Math.min( Math.floor(money/$(this).data('buildprice')), turns*bpt, Math.floor(space - ttl));
            nm = (special_space !== false ? Math.min(nm, Math.floor(special_space - specialttl)) : nm);
            /*console.log(special_space, specialttl, Math.abs($('.buildBlock .unitInput',this).val()));*/
            var sm = Math.abs($('.buildBlock .unitInput', this).val()) + nm;
            $('.buildmax', this).attr('data-amount', sm ).text(nm);
            $('.buildBlock .unitInput', this).attr('max', sm);
        });
        $('#total').text(totals.build);
        $('#order_total').attr('data-amount',totals.cost).text(number_format(totals.cost, 0, ',', ' '));
        $('#turn_total').attr('data-amount',totals.turns).text(totals.turns);
        $('#networth_new').text(number_format(totals.nw, 0, ',', ' '));
    }
    $('#turnbuild').on('submit', function(e) {
        e.preventDefault();

        if($('#order_total').attr('data-amount') > provinceData.money) return standardNotify('Insufficient funds');
        if($('#turn_total').attr('data-amount') > provinceData.turns) return standardNotify('Not enough turns');
		if($('#turn_total').attr('data-amount') >= 50 && !confirm('This will cost a lot of turns, are you sure?')) return;
        singleAjax(site_url+'/ajax/units', $(this), function(data) {
            for(var key in data.buildmax) {
                var bm = data.buildmax[key], o = data.owned[key], sp = data.space[key], ssp = data.specialspace[key], r = $('.unitRow.'+key,this);
                $('.buildmax', r).attr('data-amount', bm).text(bm);
                $('.buildBlock .unitInput', r).attr('max', bm);
                $('.owned', r).text(o);
                r.attr('data-space', sp).attr('data-specialspace', ssp);
            }
            $(this).trigger('reset');
            calculateUnitsTotals();
        });
    });
    calculateUnitsTotals();

    // Market page
    function calculateMarketTotals() {
        if(typeof provinceData.networth == 'undefined' || $('#market').length==0) return;
        var totals = {build:0,demo:0,buy:0,buytotal:0,cost:0,sell:0,trade:0,nw:provinceData.networth};
        $('#market .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var b = Math.abs($('.buildBlock .unitInput',this).val()), d = Math.abs($('.demoBlock .unitInput',this).val());
            totals.build += b;
            totals.demo += d;
            totals.buy += (b * parseInt($(this).data('buyprice')));
            totals.nw += (b * parseInt($(this).data('nw'))) - (d * parseInt($(this).data('nw')));
        });
        totals.buytotal = totals.buy;
        if(totals.demo > 0) { // Trading is cheaper than selling / buying
            $('#market .unitRow:not(.headerRow,.descriptionRow)').each(function() {
                var d = Math.abs($('.demoBlock .unitInput',this).val()), tp=parseInt($(this).data('tradeprice')), sp=parseInt($(this).data('sellprice'));
                if(d > 0) {
                    if(totals.buy > 0) {
                        var tradenum = Math.min(d, Math.ceil(totals.buy/tp)); // how many do we need?
                        totals.buy -= tradenum * tp;
                        totals.sell += ((d-tradenum) > 0 ? (d-tradenum) * sp : 0);
                    } else totals.sell += d * sp;
                    totals.trade += d * tp; // how much WOULD we like to trade?
                }
            });
        }
        if(totals.buy < 0) {
            totals.sell += -totals.buy;
            totals.buy = 0;
        }
        totals.cost = totals.sell - totals.buy;

        var space = {'special':-1}; //money = provinceData.money + totals.cost,
        $('#market .unitBuildTable').each(function() {
            var type = $(this).attr('id');
            space[type] = parseInt($('#'+type+'spacecount').text());
            $('.unitRow:not(.headerRow,.descriptionRow)', this).each(function() {
                var b = Math.abs($('.buildBlock .unitInput',this).val()), d = Math.abs($('.demoBlock .unitInput',this).val());
                if($(this).data('specialspace') != undefined && space.special == -1) space.special = parseInt($(this).data('specialspace'));
                if($(this).data('specialspace') != undefined) space.special += d - b;
                space[type] += d - b;
            });
        });

        var money = provinceData.money;
        $('#market .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            var type = $(this).parents('.unitBuildTable').attr('id');
            var nm = space[type];
            nm = ($(this).data('specialspace') != undefined ? Math.min(nm, space.special) : nm);
            var tradeMax = Math.floor( ((money-totals.buytotal)+totals.trade) / $(this).data('buyprice')); // max we can trade
            nm = Math.min( tradeMax, nm);
            var sm = Math.abs($('.buildBlock .unitInput', this).val()) + nm;
            $('.buildmax', this).attr('data-amount', sm ).text(nm);
            $('.buildBlock .unitInput', this).attr('max', sm);
        });
        $('#total').text('+'+totals.build+' / -'+totals.demo);
        $('#cost_total').attr('data-amount', totals.cost).text(number_format(totals.cost, 0, ',', ' '))
        //$('#cost_total').parent().toggleClass('red-text', totals.cost < 0).toggleClass('green-text', totals.cost > 0);
        $('#networth_new').text(number_format(totals.nw, 0, ',', ' '));
    }
    $('#market').on('submit', function(e) {
        e.preventDefault();
        if($('#cost_total').attr('data-amount')*-1 > provinceData.money) return standardNotify('Insufficient funds');
        var specialttl = 0;
        $('#market .unitRow:not(.headerRow,.descriptionRow)').each(function() {
            if($(this).data('specialspace') != undefined) specialttl += Math.abs($('.demoBlock .unitInput',this).val());
        });
        if(specialttl > $('.maxSpecialSell').data('amount')) {
            return standardNotify('You cannot sell more than '+$('.maxSpecialSell').data('amount')+' special units per day');
        }
        singleAjax(site_url+'/ajax/market', $(this), function(data) {
            $('.specialSold', this).text(data.specialsold);
            for(var key in data.buildmax) {
                var bm = data.buildmax[key], dm = data.demomax[key], o = data.owned[key], b = data.ordered[key],
                sp = data.space[key], ssp = data.specialspace[key], r = $('.unitRow.'+key,this);
                $('.buildmax', r).attr('data-amount', bm).text(bm);
                $('.buildBlock .unitInput', r).attr('max', bm);
                $('.demomax', r).attr('data-amount', dm).find('.num_owned').text(o);
                $('.demomax', r).find('.num_ordered').text( (b>0 ? ' ('+b+')' : '') );
                $('.demoBlock .unitInput', r).attr('max', dm);
                r.attr('data-space', sp).attr('data-specialspace', ssp);
            }
            for(var key in data.typespace) {
                $('#'+key+'spacecount', this).text(data.typespace[key] - data.usedtypespace[key]);
            }
            $(this).trigger('reset');
            calculateMarketTotals();
        });
    });
    calculateMarketTotals();


    // Buildings, Units & Market page
    $(document).on("keyup paste blur change", ".unitInput", function() {
        calculateBuildingsTotals();
        calculateUnitsTotals();
        calculateMarketTotals();
	});
    $('.buildmax').on('click', function(e) {
        e.preventDefault();
        $(this).siblings('.buildBlock').find('.unitInput').val($(this).attr('data-amount'));
        calculateBuildingsTotals();
        calculateUnitsTotals();
        calculateMarketTotals();
    });
    $('.demomax').on('click', function(e) {
        e.preventDefault();
        $(this).siblings('.demoBlock').find('.unitInput').val($(this).attr('data-amount'));
        calculateBuildingsTotals();
        calculateMarketTotals();
    });

    // satelliteForm
    $('.satelliteForm .orderSubmit').on('click', function(e) {
        if(!confirm('Are you sure you want to order this satellite?')) {
            e.preventDefault();
            return;
        }
    });
    $('.satelliteForm .demoSubmit').on('click', function(e) {
        if(!confirm('Are you sure you want to demolish this satellite?')) {
            e.preventDefault();
            return;
        }
    });
    $('.activateSatellite').on('click', function() {
        $('input[name="action"]').val('activate');
    });
    $('.satelliteForm').on('submit', function(e) {
        e.preventDefault(); // this orders, activates and crashes satellites
        singleAjax(site_url+'/ajax/satellite', $(this), function(data) {
            $('.satelliteForm').trigger('reset');
        });
    });

    // clan page
    $('#declare').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/clandeclare', $(this));
    });

    // Sending aid
    $("#maxaid").click(function() {
        $("#amount").val(parseInt($('#amount').attr('max')));
    });
    $('#aid').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/sendaid', $(this), function(data) {
            $('#aidssent').html(data.noaids);
            $('#amount').attr('max', data.max);
            $('#aid').trigger('reset');
        });
    });

    // Sending message
    $("#message").on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/message', $(this), function(data) {
            $('#message').trigger('reset');
        });
    });
    $("#claninvite").on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/claninvite', $(this), function(data) {
            $('.inviteButtonRow').remove();
            $('.blockHeader').html('You have used the clan invite');
        });
    });
    $('#claninvite .mainSubmit').on('click', function() {
        $('#claninvite .target').val($(this).val());
    });

    // Edit profile
    $('#referralInput').on('click', function() { $(this)[0].select(); });
    $("#editprofile").on('submit', function(e){
        e.preventDefault();
        singleAjax(site_url+'/ajax/userupdate', $(this));
    });
    $('#resetprofile').on('submit', function(e) {
        e.preventDefault();
        if(!confirm("Are you sure you want to reset your account? You will lose all your units, research and buildings!")) return;
        singleAjax(site_url+'/ajax/reset', $(this));
    });
    if($('#editprofile').length) {
        Dropzone.autoDiscover = false;
        $("#user_avatar_dz").dropzone({
            url: $('#user_avatar_dz').attr('data-url'),
            addRemoveLinks: true,
            init: function() {
                $("#user_avatar_dz").addClass('dropzone');
                this.on("sending", function(file, xhr, formData){
                    formData.append("my_nonce_field", $('.nonce').val());
                    formData.append("action", "submit_dropzonejs");
                });
                this.on("removedfile", function(file, xhr, formData) {
                    console.log(file.previewTemplate.getAttribute('rel'));
                });
            },
            success: function (file, response) {
                $('input[name="newuserimage"]').attr('value',response);
                file.previewElement.classList.add("dz-success");
            },
            error: function (file, response) {
                file.previewElement.classList.add("dz-error");
            }
        });
    }
});

// Google Tag Manager
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXGKNL3');

// Facebook Pixel Code
!function(f,b,e,v,n,t,s) {
    if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)
}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1603414756640075');
fbq('track', 'PageView');

// Global site tag (gtag.js) - Google Analytics
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-40825301-45');

// Firebase browser notifications
if(typeof firebase != 'undefined') {
    var config = {
        apiKey: "AIzaSyBBkuM6n38eUe5yqw50KjpM7HHAR2RGdOQ",
        authDomain: "assaultonline-21594.firebaseapp.com",
        databaseURL: "https://assaultonline-21594.firebaseio.com",
        projectId: "assaultonline-21594",
        storageBucket: "assaultonline-21594.appspot.com",
        messagingSenderId: "776419312119"
    };
    firebase.initializeApp(config);
    const messaging = firebase.messaging();
    messaging.usePublicVapidKey("BPywnXWNiczMF1nEPWQ6hZOudN81OwAbvcBWQBaDx5FVFUG7Rdl0J9sd1GjqA7KpzDKYtOoWnlx-vY39C9uh3h0");
    messaging.getToken().then(function(currentToken) {
        if (currentToken) {
            jQuery.post("/addtoken.php",{usertoken : currentToken});
        } else {
            // Show permission request.
            //console.log('No Instance ID token available. Request permission to generate one.');
            // Show permission UI.
            updateUIForPushPermissionRequired();
            setTokenSentToServer(false);
        }
    }).catch(function(err) {
        //console.log('An error occurred while retrieving token. ', err);
        if(typeof showToken == 'function') showToken('Error retrieving Instance ID token. ', err);
        if(typeof setTokenSentToServer == 'function') setTokenSentToServer(false);
    });
}
