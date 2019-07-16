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

function updateHeaderData() {
    var $ = jQuery;
    $.ajax({url:site_url+'/ajax/header',type:'post'}).done(function(response) { // ajax without loader, returns html in json
        var data = $.parseJSON(response);
        if(data.nonce) $('.nonce').val(data.nonce);
        for(var i in data) {
            if($('.'+i+'header').length) $('.'+i+'header').html(data[i]);
        }
        $('header .freeland').attr({'data-original-title': data.freeland});
        $('.globalsBadge').text(data.globals).toggle((data.globals>0));
        $('.localsBadge').text(data.locals).toggle((data.locals>0));
        $('.inboxBadge').text(data.messages).toggle((data.messages>0));
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

jQuery(function($) {

    var requests={};
    function singleAjax(url,post,cb) {
        //var $ = jQuery;
        if(!!requests[url]) requests[url].abort();
        var serializedData = (post instanceof jQuery ? post.serialize() : post);
        $('.pageLoader, #page-cover').show();
        requests[url] = $.ajax({url:url,type:'post',data:serializedData}).done(function(response) {
            $('.pageLoader, #page-cover').fadeOut("fast");
            var data = $.parseJSON(response);
            if(data.nonce) $('.nonce').val(data.nonce);
            updateHeaderData(); // Something happened, so it probably costed money or something
            if(!!data.status) $.notify({message:data.status},{type:'info',delay:5000,allow_dismiss:true,newest_on_top:true});
            if(!!cb) cb.call( (post instanceof jQuery ? post : this), data);
        });
    }

    var d=new Date();
    $('#footerTime').text(d.toLocaleString('nl-NL'));
    $('#footerResolution').text(window.innerWidth +'x'+ window.innerHeight);

    // Help in icon menu
    $('.menuRow').each(function(i1) {
        var t = $('.menuText>a',this).html();
        if(!!t) {
            $('.buttonItem>a', this).wrapInner('<div data-toggle="tooltip" data-html="true" data-placement="right" title="'+t.replace(/"/g, "'")+'"></div>');
        }
    });

    if(getCookie('menuOpen') === null) setCookie('menuOpen', 0, 256);
    else { setCookie('menuOpen', getCookie('menuOpen'), 256); } // Remember forever

    $("#nextbt, #nextbt2").on('click', function(e) {
        e.preventDefault();
        if($('body').hasClass('menuOpen')) {
            $('body').removeClass('menuOpen');
            $('.menuText').hide(500);
            $(".hamburger").removeClass("is-active");
            $('[data-toggle=tooltip]').tooltip('enable');
            setCookie('menuOpen', 0, 256);
        } else {
            $('body').addClass('menuOpen');
            $('.menuText').show(750);
            $(".hamburger").addClass("is-active");
            $('[data-toggle=tooltip]').tooltip('disable');
            if($(window).width() >= 1400) setCookie('menuOpen', 1, 256);
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

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

    $(document).on('keyup paste change', '.buy_spyplane, .buy_spy', function() {
        if(''+$(this).val() === '007') {
            $.notify({message:'The name is Bond, James Bond'},{type:'info', allow_dismiss:true, newest_on_top:true, delay:5000});
        }
    });

    var i = setInterval(function() { updateHeaderData(); }, 10000);
    updateHeaderData();

    start_countdowns();

    if($('.pageTitle').hasClass('.deadback')) {
        $(".splashmessage").html('You died');
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

    // Research page
    $('#research').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/research',  $(this), function(data) {
            if(data.success) {
                $('#researchsubmit').val('Queue research');
                $('.researchlabel').html('Queue select');
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
        singleAjax(site_url+'/ajax/exploreland', $(this), function(data) {
            if(data.success) {
                $(".explNotice").html(data.exploredtoday);
                $(".sellNotice").html(data.soldtoday);
                $('#exprate').html(data.newrate);
                $("#turnsinput").attr({"max": data.maxturns});
                $("#landinput").attr({"max": data.maxsell});
                $(".maxexp").attr({"data-max": data.maxturns});
                $(".maxsell").attr({"data-max": data.maxsell});
            }
            $(this).trigger("reset");
        });
    });
    $('#sellform').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/sellland', $(this), function(data) {
            if(data.success) {
                $(".sellNotice").html(data.soldtoday);
                $("#landinput").attr({"max": data.maxsell});
                $(".maxsell").attr({"data-max": data.maxsell});
            }
            $(this).trigger("reset");
        });
    });

    // Buildings page
    $('.demomax').on('click', function(e) {
        e.preventDefault();
        $(this).siblings('.demoBlock').find('.unitInput').val($(this).attr('data-amount'));
        calculateBuildingsTotals();
    });
    $('.buildmax').on('click', function(e) {
        e.preventDefault();
        $(this).siblings('.buildBlock').find('.unitInput').val($(this).attr('data-amount'));
        calculateBuildingsTotals();
    });
    function calculateBuildingsTotals() {
        var totals = {build:0,demo:0,cost:0,turns:0,nw: parseInt($('#networth_new').data('oldnw')) };
        var bpt=parseInt($('#buildingsPerTurn').text());
		$('#buildings .unitRow:not(.headerRow)').each(function() {
            var b = Math.abs($('.buildBlock .unitInput',this).val()), d = Math.abs($('.demoBlock .unitInput',this).val());
			totals.build += b;
            totals.demo += d;
            totals.cost += (b*parseInt($(this).data('buildprice')))+(d*parseInt($(this).data('demoprice')));
            totals.turns += Math.ceil(b/bpt);
            totals.nw += (b*parseInt($(this).data('nw'))) - (d*parseInt($(this).data('nw')));
        });
        // Demolishing creates more space
        var landpb = $('#buildings .landpb').data('amount');
        var freeland = $('#buildings .freeland').data('amount') + (totals.demo*landpb) - (totals.build * landpb);
        var turns = $('#buildings #turn_total').attr('data-turns') - totals.turns;
        var money = $('#buildings #order_total').attr('data-money') - totals.cost;
        $('#buildings .unitRow:not(.headerRow)').each(function() {
            var nm = Math.min( Math.floor(money/$(this).data('buildprice')), turns*bpt, Math.floor(freeland / landpb));
            $('.buildmax', this).attr('data-amount', Math.abs($('.buildBlock .unitInput', this).val()) + nm ).text(nm);
        });
		$('#total').text('+'+totals.build+' / -'+totals.demo);
		$('#order_total').text(number_format(totals.cost, 0, ',', ' '));
		$('#turn_total').text(totals.turns);
		$('#networth_new').text(number_format(totals.nw, 0, ',', ' '));
	}
	$(document).on("keyup paste blur change", ".unitInput", function() {
		calculateBuildingsTotals();
	});
    $('#buildings').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/buildings', $(this), function(data) {
            $('.maxbuild', this).text(data.maxbuild);
            $('.buildspace', this).text(data.buildspace);
            $('#networth_new',this).attr('data-oldnw', data.networth);
            $('#turn_total', this).attr('data-turns', data.turns);
            $('#order_total', this).attr('data-money', data.money);
            $('.freeland', this).attr('data-amount',data.freeland).html(data.freeland_formatted);
            $('.power', this).html(data.power);
            for(var key in data.buildmax) {
                var bm = data.buildmax[key], dm = data.demomax[key], o = data.owned[key], r = $('.unitRow.'+key,this);
                $('.buildmax', r).attr('data-amount', bm).text(bm);
                $('.buildBlock .unitInput', r).attr('max', bm);
                $('.demomax', r).attr('data-amount', dm).text(o);
                $('.demoBlock .unitInput', r).attr('max', dm);
            }
            $(this).trigger("reset");
            calculateBuildingsTotals();
        });
    });
    calculateBuildingsTotals();

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
